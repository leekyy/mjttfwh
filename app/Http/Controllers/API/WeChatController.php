<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2018/1/11
 * Time: 9:43
 */

namespace App\Http\Controllers\API;


use App\Components\BusiWordManager;
use App\Components\InviteNumManager;
use App\Components\UserManager;
use App\Components\UserTJManager;
use App\Components\Utils;
use App\Components\WeChatManager;
use App\Http\Controllers\Controller;
use App\Models\InviteCodeRecord;
use App\Models\Menu;
use App\Models\UserTJ;
use EasyWeChat\Kernel\Messages\Image;
use Illuminate\Support\Facades\Log;


class WechatController extends Controller
{

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志
        $app = app('wechat.official_account');
        $app->server->push(function ($message) {
            Log::info("server receive:" . json_encode($message));
            $app = app('wechat.official_account');
            $fwh_openid = $message['FromUserName'];  //服务号openid
            Log::info('fwh_openid:' . $fwh_openid);
            /*
             * 如果该用户不存在，说明没有关注过，需要
             * 1）注册该用户
             * 2）判断是否为扫描进入的用户，如果为扫描进入的用户，则需要处理邀请逻辑
             */
            $new_user_flag = false;
//            $is_subscribe = WeChatManager::isUserSubscribe($fwh_openid);      此处逻辑不正确，因为关注进来的一定是已关注
            $user = UserManager::getByFWHOpenid($fwh_openid);
            if (!$user) {
                $user = WeChatManager::register($fwh_openid);
                $new_user_flag = true;
            }
            Log::info("new_user_flag:" . $new_user_flag);
            //根据消息类型分别进行处理
            switch ($message['MsgType']) {
                case 'event':
                    if ($message['Event'] == 'CLICK') {     //点击事件
                        switch ($message['EventKey']) {

                        }
                    }
                    if ($message['Event'] == 'subscribe') {     //关注事件
                        Log::info("subscribe new_user_flag:" . $new_user_flag);
                        //关注事件，需要将关注标识设置为1
                        $user->is_subscribe = '1';
                        $user->save();
                        //如果有EventKey-代表，关注分享的二维码过来的消息，并且为新注册的用户
                        if (array_key_exists('EventKey', $message) && !Utils::isObjNull($message['EventKey']) && $new_user_flag) {
                            Log::info("message EventKey is new user:" . $message['EventKey']);
                            $key_val = str_replace('qrscene_', '', $message['EventKey']);       //key_val为键值信息，这里为用户openid
                            Log::info("key_val:" . $key_val);
                            $tj_user = UserManager::getByFWHOpenid($key_val);    //找到推荐用户
                            Log::info("user->id:" . $user->id . "  tj_user->id:" . $tj_user->id);
                            //不存在推荐关系
                            if (!UserTJManager::isUserHasBennTJ($tj_user->id, $user->id)) {
                                //新建推荐关系
                                $userTJ = new UserTJ();
                                $userTJ->user_id = $user->id;
                                $userTJ->tj_user_id = $tj_user->id;
                                $userTJ->save();
                                //增加用户的推荐数
                                UserManager::addYQNum($tj_user->id);
                                //同时发送邀请者消息
                                $tj_user = UserManager::getById($tj_user->id);
                                if ($tj_user->yq_num < $tj_user->target_yq_num) {     //小于3个
                                    //发送消息
                                    $text = BusiWordManager::getByTemplateID("TEMPLATE_ZHULI_PROCESS_FOR_TJUSER");
                                    $text = str_replace("{friend_name}", $user->nick_name, $text);
                                    $text = str_replace("{rest_num}", ($tj_user->target_yq_num - $tj_user->yq_num), $text);
                                    $app->customer_service->message($text)
                                        ->to($tj_user->fwh_openid)
                                        ->send();
                                } elseif ($tj_user->yq_num == $tj_user->target_yq_num && $tj_user->target_yq_num != 0) {    //等于target_yq_num
                                    //获取邀请码并发送 三条文字信息 text0、text1、text2
                                    $param = array(
                                        'openId' => $tj_user->fwh_openid,
                                        'sign' => md5(base64_encode("openId|" . $tj_user->fwh_openid . "|Free|Edition"))
                                    );
                                    $result = Utils::curl(Utils::SERVER_URL . '/rest/user/public_number/invi_code/', $param, true);   //访问接口
                                    Log::info("invi_code:" . json_encode($result));
                                    $result = json_decode($result, true);   //因为返回的已经是json数据，为了适配makeResponse方法，所以进行json转数组操作
                                    $inviCode = $result['data']['inviCode'];    //邀请码

                                    $text0 = BusiWordManager::getByTemplateID("TEMPLATE_ZHULI_SUCCESS_FOR_TJUSER");
                                    $text0 = str_replace("{friend_name}", $user->nick_name, $text0);
                                    $app->customer_service->message($text0)
                                        ->to($tj_user->fwh_openid)
                                        ->send();
                                    $text1 = $inviCode;
                                    $app->customer_service->message($text1)
                                        ->to($tj_user->fwh_openid)
                                        ->send();
                                    $text2 = BusiWordManager::getByTemplateID("TEMPLATED_FREE_INVITECODE");
                                    $app->customer_service->message($text2)
                                        ->to($tj_user->fwh_openid)
                                        ->send();

                                    //记录邀请码发送信息
                                    $inviteCodeRecord = new InviteCodeRecord();
                                    $inviteCodeRecord->user_id = $tj_user->id;
                                    $inviteCodeRecord->invite_code = $inviCode;
                                    $inviteCodeRecord->type = '0';
                                    $inviteCodeRecord->save();
                                }
                                //发送被邀请者消息
                                $text = BusiWordManager::getByTemplateID("TEMPLATE_ZHULI_SUCCESS_FOR_USER");
                                $text = str_replace("{friend_name}", $tj_user->nick_name, $text);
                                $app->customer_service->message($text)
                                    ->to($user->fwh_openid)
                                    ->send();
                            }
                        }
                        //如果有EventKey-代表，关注分享的二维码过来的消息，并且非新注册的用户
                        if (array_key_exists('EventKey', $message) && !Utils::isObjNull($message['EventKey']) && !$new_user_flag) {
                            Log::info("message EventKey not new user:" . $message['EventKey']);
                            $text = BusiWordManager::getByTemplateID("TEMPLATED_CANNOT_ZHULI");
                            $app->customer_service->message($text)
                                ->to($user->fwh_openid)
                                ->send();
                        }
                        $text = BusiWordManager::getByTemplateID("TEMPLATE_SCAN_SUB");
                        return $text;
                    }
                    //取消关注事件
                    if ($message['Event'] == 'unsubscribe') {
                        //关注事件，需要将关注标识设置为1
                        $user->is_subscribe = '0';
                        $user->save();
                    }
                    //扫描进入事件
                    if ($message['Event'] == 'SCAN') {
                        //如果是老用户扫码进入
                        if (array_key_exists('EventKey', $message) && !Utils::isObjNull($message['EventKey']) && !$new_user_flag) {
                            Log::info("message EventKey:" . $message['EventKey']);
                            $key_val = str_replace('qrscene_', '', $message['EventKey']);       //key_val为键值信息，这里为用户openid
                            Log::info("key_val:" . $key_val);
                            $tj_user = UserManager::getByFWHOpenid($key_val);    //找到推荐用户
                            Log::info("user->id:" . $user->id . "  tj_user->id:" . $tj_user->id);
                            $text = BusiWordManager::getByTemplateID("TEMPLATED_CANNOT_ZHULI");
                            return $text;
                        } else {
                            $text = BusiWordManager::getByTemplateID("TEMPLATE_SCAN_SUB");
                            return $text;
                        }
                    }
                    return Utils::TEXT_NULL_STR;
                    break;
                case 'text':
                    $fwh_openid = $message['FromUserName'];  //服务号openid
                    /*
                     * 如果该用户不存在，说明没有关注过，需要
                     * 1）注册该用户
                     * 2）判断是否为扫描进入的用户，如果为扫描进入的用户，则需要处理邀请逻辑
                     */
                    if (!UserManager::getByFWHOpenid($fwh_openid)) {
                        $user = WeChatManager::register($fwh_openid);
                    }
                    $reply_content = WeChatManager::matchKeyWords($message['Content']);
                    $app->customer_service->message($reply_content)
                        ->to($user->fwh_openid)
                        ->send();
                    break;
                case 'image':

                    break;
                case 'voice':

                    break;
                case 'video':

                    break;
                case 'location':

                    break;
                case 'link':

                    break;
                // ... 其它消息
                default:
//                    return '';
                    break;
            }
        });
        $response = $app->server->serve();
        return $response;
    }


}