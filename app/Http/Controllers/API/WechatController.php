<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2018/1/11
 * Time: 9:43
 */

namespace App\Http\Controllers\API;


use App\Components\DateTool;
use App\Components\GoodsInfoManager;
use App\Components\MemberManager;
use App\Components\OrderManager;
use App\Components\SubOrderManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use App\Models\GoodsInfo;
use App\Models\Member;
use App\Models\MemberOrder;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Log;
use Yansongda\Pay\Pay;


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
            switch ($message['MsgType']) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
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