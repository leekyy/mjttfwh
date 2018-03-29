<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2018/1/11
 * Time: 9:43
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
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
            Log::info(\GuzzleHttp\json_encode($message));
            //获取基本信息
            $user_openid = $message['FromUserName'];


            switch ($message['MsgType']) {
                case 'event':
                    if ($message['Event'] == 'CLICK') {     //点击事件
                        switch ($message['EventKey']) {
                            case 'V0301_CONTACT_US':
                                return "微信：3011740452";
                        }
                    }
                    break;
                case 'text':

                    $text = "hey，又多了一个粉！\r\n接下来的旅行时光里，美景听听会为你带来最优质的景点讲解服务哦~\r\n\r\n点击此处，可以申请美景听听幸运用户\r\n点击“美景”可以看到历史主题原创漫画\r\n点击“听听”可以通过喜马拉雅和小程序听景点讲解\r\n点击“App”可以下载美景听听中文语音导游\r\n\r\n有任何疑问，欢迎随时骚扰Tommy微信：3011740452";
                    $text = '<a href="http://www.baidu.com">又多了一个粉</a>';
                    return $text;
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });
        $response = $app->server->serve();
        return $response;
    }


}