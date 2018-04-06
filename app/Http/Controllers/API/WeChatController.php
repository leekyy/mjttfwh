<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2018/1/11
 * Time: 9:43
 */

namespace App\Http\Controllers\API;


use App\Components\UserManager;
use App\Components\UserTJManager;
use App\Components\Utils;
use App\Components\WeChatManager;
use App\Http\Controllers\Controller;
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
            Log::info(json_encode($message));
            $app = app('wechat.official_account');
            $fwh_openid = $message['FromUserName'];  //服务号openid
            /*
             * 如果该用户不存在，说明没有关注过，需要
             * 1）注册该用户
             * 2）判断是否为扫描进入的用户，如果为扫描进入的用户，则需要处理邀请逻辑
             */
            $new_user_flag = false;
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
                        Log::info("message event == subscribe");
                        Log::info("array_key_exists EventKey in message:" . array_key_exists('EventKey', $message));
                        Log::info("EventKey in message is not null:" . Utils::isObjNull($message['EventKey']));
                        Log::info("new_user_flag:" . $new_user_flag);
                        //如果有EventKey-代表，扫描分享的二维码过来的消息，并且为新注册的用户
                        if (array_key_exists('EventKey', $message) && !Utils::isObjNull($message['EventKey']) && $new_user_flag) {
                            Log::info("message EventKey:" . $message['EventKey']);
                            $key_val = str_replace_array('qrscene_', '', $message['EventKey']);       //key_val为键值信息，这里为用户openid
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
                                //同时发送群发消息
                                $tj_user = UserManager::getById($tj_user->id);
                                if ($tj_user->yq_num < 3) {     //小于3个
                                    //发送消息
                                    $text = "你的好友" . $user->nick_name . "帮你扫码了，还差" . (3 - $tj_user->yq_num) . "个好友助力，即可获得邀请码";
                                    $app->customer_service->message($text)
                                        ->to($tj_user->fwh_openid)
                                        ->send();
                                } elseif ($tj_user->yq_num == 3) {    //等于3个
                                    //获取邀请码
                                    $param = array();
                                    $result = Utils::curl('http://testapi.gowithtommy.com/rest/user/public_number/invi_code/', $param, false);   //访问接口
                                    $result = json_decode($result, true);   //因为返回的已经是json数据，为了适配makeResponse方法，所以进行json转数组操作
                                    $inviCode = $result['data']['inviCode'];    //邀请码
                                    $text0 = "你的好友" . $user->nick_name . "帮你扫码啦，恭喜您获得价值78元的邀请码";
                                    $app->customer_service->message($text0)
                                        ->to($tj_user->fwh_openid)
                                        ->send();
                                    $text1 = $inviCode;
                                    $app->customer_service->message($text1)
                                        ->to($tj_user->fwh_openid)
                                        ->send();
                                    $text2 = "上述为您的邀请码，自使用之日起有效期1个月\r\n\r\n使用流程如下：\r\n1、下载并登录美景听听\r\n2、在【我的】页面，点击【邀请码】，输入我方提供的【邀请码】后，您的会员等级即可变成【高级会员】。（*如遇到未能解锁的情况，请重新登录）\r\n4、【高级会员】可以解锁美景听听全部付费音频，畅听100多个国家5万多个景点。\r\n5、会员有效期一个月，如有问题，请联系客服";
                                    $app->customer_service->message($text2)
                                        ->to($tj_user->fwh_openid)
                                        ->send();
                                }
                            }
                        }
                    }
                    $text = "hey，欢迎关注美景听听：全球景点中文语音讲解\r\n<a href=\"http://mjttfwh.isart.me/luckUser\">点击此处</a>可以获得免费邀请码\r\n\r\n点击“美景”可以看到历史主题原创漫画\r\n点击“听听”可以通过喜马拉雅和小程序听景点讲解\r\n点击“App”可以下载美景听听中文语音导游";
                    return $text;
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
                    switch (WeChatManager::matchKeyWords($message['Content'])) {
                        case 'group1':
                            $text = "只需完成以下2步，即可获得邀请码，免费解锁全部景点！\r\n\r\n1.长按保存以下图片分享给好友/朋友圈\r\n2.邀请3位好友扫码并关注美景听听旅行\r\n\r\n（请24小时内完成此任务，逾期活动作废）\r\n\r\n<a href=\"http://mjttfwh.isart.me/luckUser\">土豪请戳此购买</a>";
                            $app->customer_service->message($text)
                                ->to($user->fwh_openid)
                                ->send();
                            //生成图片

                            break;
                    }
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
                    return '';
                    break;
            }
        });
        $response = $app->server->serve();
        return $response;
    }


}