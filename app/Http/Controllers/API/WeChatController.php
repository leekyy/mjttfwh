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
            //根据消息类型分别进行处理
            switch ($message['MsgType']) {
                case 'event':
                    if ($message['Event'] == 'CLICK') {     //点击事件
                        switch ($message['EventKey']) {

                        }
                    }
                    if ($message['Event'] == 'subscribe') {     //关注事件
                        $fwh_openid = $message['FromUserName'];  //服务号openid
                        /*
                         * 如果该用户不存在，说明没有关注过，需要
                         * 1）注册该用户
                         * 2）判断是否为扫描进入的用户，如果为扫描进入的用户，则需要处理邀请逻辑
                         */
                        if (!UserManager::getByFWHOpenid($fwh_openid)) {
                            $user = WeChatManager::register($fwh_openid);
                            //如果有EventKey-代表，扫描分享的二维码过来的消息
                            if (array_key_exists('EventKey', $message) && !Utils::isObjNull($message['EventKey'])) {
                                $key_val = explode('_', $message['EventKey'])[1];       //key_val为键值信息，这里为用户openid
                                $tj_user = UserManager::getByFWHOpenid($key_val);    //找到推荐用户
                                //自己不能推荐自己
                                if ($user->id == $tj_user->id) {
                                    $text = "小贴士：自己扫描推荐二维码不算做推荐数~";
                                    return $text;
                                }
                            }
                        }
                        //如果有EventKey-代表，扫描分享的二维码过来的消息
                        if (array_key_exists('EventKey', $message) && !Utils::isObjNull($message['EventKey'])) {
                            $key_val = explode('_', $message['EventKey'])[1];       //key_val为键值信息，这里为用户openid
                            $tj_user = UserManager::getByFWHOpenid($key_val);    //找到推荐用户
                            //判断推荐关系是否存在
                            if (!UserTJManager::isUserHasBennTJ($tj_user->id, $user->id)) {
                                //新建推荐关系
                                $userTJ = new UserTJ();
                                $userTJ->user_id = $user->id;
                                $userTJ->tj_user_id = $tj_user->id;
                                $userTJ->save();
                                //增加用户的推荐数
                                UserManager::addYQNum($tj_user->id);
                                //同时发送群发消息


                            }
                        }
                        $text = "hey，欢迎关注美景听听：全球景点中文语音讲解\r\n<a href=\"http://www.baidu.com\">点击此处</a>可以获得免费邀请码\r\n\r\n点击“美景”可以看到历史主题原创漫画\r\n点击“听听”可以通过喜马拉雅和小程序听景点讲解\r\n点击“App”可以下载美景听听中文语音导游";
                        return $text;
                    }
                    break;
                case 'text':
                    switch ($message['Content']) {
                        case '客服消息':
                            $app->customer_service->message('hello')
                                ->to("oJpZ11DU7GZpoW9W_NB5HwXrlYd8")
                                ->send();
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