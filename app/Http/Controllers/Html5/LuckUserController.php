<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/10/3
 * Time: 0:38
 */

namespace App\Http\Controllers\Html5;

use App\Components\AdminManager;
use App\Components\UserManager;
use App\Components\WeChatManager;
use App\Libs\CommonUtils;
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
        $app = app('wechat.official_account');
        //以上已经完成用户注册，为每个用户申请小程序邀请码
        $filename = WeChatManager::createUserYQCode($user->id);
        //生成分享配置
        $wx_config = $app->jssdk->buildConfig(array('onMenuShareTimeline', 'onMenuShareAppMessage'), false);

        //获取支付配置
//        $param = array(
//            'openid' => 'oIUk2w6SjIvnUq2_FPQtroK9ovy0',       //测试账号openid
//        );
//        $result = Utils::curl(Utils::SERVER_URL . '/rest/user/public_number/invi_code/', $param, true);   //访问接口
//        dd($result);

        return view('html5.activity.luckUser', ['user' => $user, 'wx_config' => $wx_config]);
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
        //获取支付配置
        $param = array(
            'openid' => 'oIUk2w6SjIvnUq2_FPQtroK9ovy0',       //测试账号openid
        );
        $result = Utils::curl(Utils::SERVER_URL . '/rest/user/public_number/invi_code/', $param, true, true);   //访问接口，生产环境https
        dd($result);
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
        //如果用户没有关注公众号
        if ($user->is_subscribe == "0") {
            return ApiResponse::makeResponse(false, "用户未关注公众号", ApiResponse::NOT_SUBSCRIBE);
        }
        //如果用户已经获得邀请码
        if ($user->yq_num >= 3) {
            return ApiResponse::makeResponse(false, "已经为用户生成邀请码", ApiResponse::ALREADY_CREATE_INVITECODE);
        }
        //发送文字，生成图片素材
        $text = "只需完成以下2步，即可获得邀请码，免费解锁全部景点！\r\n\r\n1.长按保存以下图片分享给好友/朋友圈\r\n2.邀请3位好友扫码并关注美景听听旅行\r\n\r\n（请24小时内完成此任务，逾期活动作废）\r\n\r\n<a href=\"http://mjttfwh.isart.me/luckUser\">土豪请戳此购买</a>";
        $app = app('wechat.official_account');
        $app->customer_service->message($text)
            ->to($user->fwh_openid)
            ->send();
        //生成微信图片素材并发送
        if (Utils::isObjNull($user->yq_hb_media_id)) {
            $filename = WeChatManager::createUserYQHB($user->id);
            $media_id = WeChatManager::createMediaId($filename);
            $user = UserManager::getByIdWithToken($user->id);
            $user->yq_hb_media_id = $media_id;
            $user->save();
        }
        $image = new Image($user->yq_hb_media_id);
        $app->customer_service->message($image)
            ->to($user->fwh_openid)
            ->send();
        return ApiResponse::makeResponse(true, "生成海报成功", ApiResponse::SUCCESS_CODE);
    }
}