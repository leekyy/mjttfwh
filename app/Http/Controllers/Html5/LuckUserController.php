<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/10/3
 * Time: 0:38
 */

namespace App\Http\Controllers\Html5;

use App\Components\AdminManager;
use App\Components\BusiWordManager;
use App\Components\InviteNumManager;
use App\Components\UserManager;
use App\Components\WeChatManager;
use App\Libs\CommonUtils;
use App\Models\InviteCodeRecord;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use App\Models\Admin;
use EasyWeChat\Kernel\Messages\Image;
use Illuminate\Support\Facades\Log;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;

class LuckUserController
{
    /*
     * 幸运用户首页
     *
     * By TerryQi
     *
     * 2018-03-30
     */
    public function index(Request $request)
    {
        $session_val = session('wechat.oauth_user'); // 拿到授权用户资料
        //获取用户相关信息
        $user_val = $session_val['default']->toArray();
        Log::info("user_val:" . json_encode($user_val));
        //在数据库中检索用户信息
        $user = UserManager::getByFWHOpenid($user_val['id']);
        Log::info("user:" . json_encode($user));
        //如果无值，则需要注册
        if (!$user) {
            $data = array(
                'fwh_openid' => $user_val['id'],
                'nick_name' => $user_val['nickname'],
                'avatar' => $user_val['avatar']
            );
            $user = UserManager::registerFWH($data);
        }
        //生成app信息
//        $app = app('wechat.official_account');
        //以上已经完成用户注册，为每个用户申请小程序邀请码
//        $filename = WeChatManager::createUserYQCode($user->id);
        //生成分享配置
//        $wx_config = $app->jssdk->buildConfig(array('onMenuShareTimeline', 'onMenuShareAppMessage'), false);

        ///////此处用于调测微信支付//////////////////////////
        $param = array(
            'url' => Utils::LUCKUSER_URL         //http://mjttfwh.isart.me/luckUser
        );
        $postUrl = Utils::SERVER_URL . '/rest/wechat/config/';
        $wxConfig_result = Utils::curl($postUrl, $param, true);   //访问接口
        Log::info("get wxConfig_result:" . json_encode($wxConfig_result));
        $wxConfig_result = json_decode($wxConfig_result, true);
        $wxConfig_result['data']['jsApiList'] = ['chooseWXPay'];

        //////////////////////////////////////////////////////

        return view('html5.activity.luckUser', ['user' => $user, 'wxConfig' => $wxConfig_result['data']]);
    }


    /*
     * 土豪购买
     *
     * By TerryQi
     *
     * 2018-03-30
     */
    public function richBuy(Request $request)
    {
        $session_val = session('wechat.oauth_user'); // 拿到授权用户资料
        //获取用户相关信息
        $user_val = $session_val['default']->toArray();
        //在数据库中检索用户信息
        $user = UserManager::getByFWHOpenid($user_val['id']);
        //如果无值，则需要注册
        if (!$user) {
            $data = array(
                'fwh_openid' => $user_val['id'],
                'nick_name' => $user_val['nickname'],
                'avatar' => $user_val['avatar']
            );
            $user = UserManager::registerFWH($data);
        }
        //生成app信息
        $param = array(
            'url' => Utils::RICHBUY_URL
        );
        $postUrl = Utils::SERVER_URL . '/rest/wechat/config/';
        $wxConfig_result = Utils::curl($postUrl, $param, true);   //访问接口
        $wxConfig_result = json_decode($wxConfig_result, true);
        $wxConfig_result['data']['jsApiList'] = ['chooseWXPay'];

        return view('html5.activity.richBuy', ['user' => $user, 'wxConfig' => $wxConfig_result['data']]);
    }


    /*
   * 测试支付
   *
   * By TerryQi
   *
   * 2018-03-30
   */
    public function testPay(Request $request)
    {
//        dd($request->all());

        $param = array(
            'url' => 'http://mjttfwh.isart.me/testPay'
        );
        $postUrl = 'http://testapi.gowithtommy.com/rest/wechat/config/';

        $wxConfig_result = Utils::curl($postUrl, $param, true);   //访问接口
        $wxConfig_result = json_decode($wxConfig_result, true);
        $wxConfig_result['data']['jsApiList'] = ['chooseWXPay'];
//        dd($wxConfig_result['data']);

        //获取支付配置
        //测试用openid oIUk2w6SjIvnUq2_FPQtroK9ovy0
        //TerryQi openid oIUk2wwZMTe0FggPf_cp0yV1Y6W8
        $param = array(
            'openid' => 'oIUk2wwZMTe0FggPf_cp0yV1Y6W8',       //测试账号openid
        );
        $postUrl = 'http://testapi.gowithtommy.com/rest/pay/js_pre_order/';
        $wxPay_result = Utils::curl($postUrl, $param, true);   //访问接口
        $wxPay_result = json_decode($wxPay_result, true);
        dd($wxPay_result['data']);

        return view('html5.activity.testPay', ['wxPay' => $wxPay_result['data'], 'wxConfig' => $wxConfig_result['data']]);
    }

    /*
    * 生成海报
    *
    * By TerryQI
    *
    * 2018-04-08
    */
    public function createHaiBao(Request $request)
    {
        $session_val = session('wechat.oauth_user'); // 拿到授权用户资料
        //获取用户相关信息
        $user_val = $session_val['default']->toArray();
        //在数据库中检索用户信息
        $user = UserManager::getByFWHOpenid($user_val['id']);
        //如果不存在用户信息
        if (!$user) {
            return ApiResponse::makeResponse(false, "用户不存在", ApiResponse::NO_USER);
        }
        $is_subscribe = WeChatManager::isUserSubscribe($user->fwh_openid);
        //如果用户没有关注公众号
        if ($is_subscribe == false) {
            return ApiResponse::makeResponse(false, "用户未关注公众号", ApiResponse::NOT_SUBSCRIBE);
        }
        //如果没有生成目标邀请数
        if ($user->target_yq_num == 0) {
            $user->target_yq_num = InviteNumManager::getCurrYQNum();
            $user->save();
        }
        //如果用户已经获得邀请码
        if ($user->yq_num >= $user->target_yq_num) {
            return ApiResponse::makeResponse(false, "已经为用户生成邀请码", ApiResponse::ALREADY_CREATE_INVITECODE);
        }
        //发送文字，生成图片素材
        $text = BusiWordManager::getByTemplateID("TEMPLATE_GET_FREE_INVITECODE");
        $text = str_replace("{yq_num_txt}", InviteNumManager::getCurrYQNum(), $text);
        $app = app('wechat.official_account');
        $app->customer_service->message($text)
            ->to($user->fwh_openid)
            ->send();
        //生成微信图片素材并发送
        $filename = WeChatManager::createUserYQHB($user->id);
        $media_id = WeChatManager::createMediaId('img/', $filename);
        $user = UserManager::getByIdWithToken($user->id);
        $user->yq_hb_media_id = $media_id;
        $user->save();
        //发送邀请海报
        $image = new Image($user->yq_hb_media_id);
        $app->customer_service->message($image)
            ->to($user->fwh_openid)
            ->send();
        return ApiResponse::makeResponse(true, "生成海报成功", ApiResponse::SUCCESS_CODE);
    }

    /*
     * 发送78元购买的邀请码
     *
     * By TerryQi
     *
     * 2018-04-11
     */
    public function buy78(Request $request)
    {
        $session_val = session('wechat.oauth_user'); // 拿到授权用户资料
        //获取用户相关信息
        $user_val = $session_val['default']->toArray();
        //在数据库中检索用户信息
        $user = UserManager::getByFWHOpenid($user_val['id']);
        //如果不存在用户信息
        if (!$user) {
            return ApiResponse::makeResponse(false, "用户不存在", ApiResponse::NO_USER);
        }
        $is_subscribe = WeChatManager::isUserSubscribe($user->fwh_openid);
        //如果用户没有关注公众号
        if ($is_subscribe == false) {
            return ApiResponse::makeResponse(false, "用户未关注公众号", ApiResponse::NOT_SUBSCRIBE);
        }

        $param = array(
            'openid' => Utils::convertOpenid($user->fwh_openid),       //测试账号openid转生产的openid
        );
        $postUrl = Utils::SERVER_URL . '/rest/pay/js_pre_order/';
        $wxPay_result = Utils::curl($postUrl, $param, true);   //获取支付配置信息
        Log::info("buy78 wxPay_result:" . $wxPay_result);
//        dd($wxPay_result);
        $wxPay_result = json_decode($wxPay_result, true);

        return ApiResponse::makeResponse(true, $wxPay_result['data'], ApiResponse::SUCCESS_CODE);
    }

    /*
     * 支付成功，发送78元邀请码
     *
     * By TerryQi
     *
     * 2018-04-11
     */
    public function send78InviteCode(Request $request)
    {
        $data = $request->all();
        //获取传入的out_trade_no
        $out_trade_no = $data['out_trade_no'];
        $session_val = session('wechat.oauth_user'); // 拿到授权用户资料
        //获取用户相关信息
        $user_val = $session_val['default']->toArray();
        //在数据库中检索用户信息
        $user = UserManager::getByFWHOpenid($user_val['id']);
        //如果不存在用户信息
        if (!$user) {
            return ApiResponse::makeResponse(false, "用户不存在", ApiResponse::NO_USER);
        }
        //发送78元邀请码
        $param = array(
            'out_trade_no' => $out_trade_no,
            'sign' => md5(base64_encode("out_trade_no|" . $out_trade_no . "|pay|order"))
        );
        $result = Utils::curl(Utils::SERVER_URL . '/rest/user/public_number_pay/invi_code/', $param, true);   //访问接口
        Log::info("resut:" . json_encode($result));
        $result = json_decode($result, true);   //因为返回的已经是json数据，为了适配makeResponse方法，所以进行json转数组操作
        $inviCode = $result['data']['inviCode'];    //邀请码
        Log::info("inviCode:" . json_encode($inviCode));
        //发送邀请码
        $app = app('wechat.official_account');
        $text0 = BusiWordManager::getByTemplateID("TEMPLATE_ALREAD_BUY_INVITECODE");
        $app->customer_service->message($text0)
            ->to($user->fwh_openid)
            ->send();
        $text1 = $inviCode;
        $app->customer_service->message($text1)
            ->to($user->fwh_openid)
            ->send();
        $text2 = BusiWordManager::getByTemplateID("TEMPLATE_BUY78_INVITECODE");
        $app->customer_service->message($text2)
            ->to($user->fwh_openid)
            ->send();

        //记录邀请码发送信息
        $inviteCodeRecord = new InviteCodeRecord();
        $inviteCodeRecord->user_id = $user->id;
        $inviteCodeRecord->invite_code = $inviCode;
        $inviteCodeRecord->type = '1';
        $inviteCodeRecord->out_trade_no = $out_trade_no;
        $inviteCodeRecord->save();

        return ApiResponse::makeResponse(true, $inviCode, ApiResponse::SUCCESS_CODE);
    }
}
