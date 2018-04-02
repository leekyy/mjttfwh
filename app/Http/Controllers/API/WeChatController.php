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
            //获取基本信息
            $from_user = $message['FromUserName'];  //消息来自于哪个用户
            $user = UserManager::getByFWHOpenId($from_user);
            if ($user == null) {  //若不存在用户，则应该走注册流程
                $wechat_user = WeChatManager::getByFWHOpenId($from_user);
                Log::info("WechatManager getByFWHOpenId:" . json_encode($wechat_user));
                //封装数据
                $data = array(
                    "fwh_openid" => $wechat_user['openid'],
                    "avatar" => $wechat_user['headimgurl'],
                    "nick_name" => $wechat_user['nickname'],
                    "gender" => $wechat_user['sex'],
                    "province" => $wechat_user['province'],
                    "city" => $wechat_user['city'],
                );
                //如果unionid不为空，则也需要将unionid放入data信息，以便进行注册
                if (array_key_exists('unionid', $wechat_user) && !Utils::isObjNull($wechat_user['unionid'])) {
                    array_push($data, ["unionid" => $wechat_user['unionid']]);
                }
                Log::info("WechatManager data:" . json_encode($data));
                $user = UserManager::registerFWH($data);
            }
            //根据消息类型分别进行处理
            switch ($message['MsgType']) {
                case 'event':
                    if ($message['Event'] == 'CLICK') {     //点击事件
                        switch ($message['EventKey']) {

                        }
                    }
                    if ($message['Event'] == 'subscribe') {     //关注事件
                        //如果有EventKey-代表，扫描分享的二维码过来的消息
                        if (array_key_exists('EventKey', $message) && !Utils::isObjNull($message['EventKey'])) {
                            $key_val = explode('_', $message['EventKey'])[1];
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
                                $app = app('wechat.official_account');
                                $app->broadcasting->sendText("你的好友" . $user->nick_name . "帮你扫码了，还差个" . (3 - $tj_user->yq_num) . "好友助力，即可获得邀请码", [$user->fwh_openid, $tj_user->fwh_openid]);
                            }
                        }
                        $text = "hey，欢迎关注美景听听：全球景点中文语音讲解\r\n<a href=\"http://www.baidu.com\">点击此处</a>可以获得免费邀请码\r\n\r\n点击“美景”可以看到历史主题原创漫画\r\n点击“听听”可以通过喜马拉雅和小程序听景点讲解\r\n点击“App”可以下载美景听听中文语音导游";
                        return $text;
                    }
                    break;
                case 'text':
                    //邀请码测试
                    if ($message['Content'] == '邀请码') {
                        $filename = 'user' . $user->id . '_yq_code.jpg';
                        //判断是否已经生成邀请码
                        if (file_exists(public_path('img/') . $filename)) {
                            Log::info($filename . " file exists");
                        } else {
                            $app = app('wechat.official_account');
//                            $result = $app->qrcode->forever($user->fwh_openid);
                            $result = $app->qrcode->forever("oJpZ11IIT0fKncFdoNj89oxXIUss");        //测试用
                            Log::info("app->qrcode->forever result:" . json_encode($result));
                            $url = $app->qrcode->url($result['ticket']);
                            $content = file_get_contents($url); // 得到二进制图片内容
                            file_put_contents(public_path('img/') . $filename, $content); // 写入文件
                            //建立素材
                            $result = $app->material->uploadImage(public_path('img/') . $filename);
                            Log::info("app->material->uploadImage file exists result:" . json_encode($result));
                            $user = UserManager::getByIdWithToken($user->id);
                            $user->yq_code_media_id = $result['media_id'];
                            $user->save();
                        }
                        //测试用返回邀请码
                        $image = new Image($user->yq_code_media_id);
                        return $image;
                    }
                    //邀请码测试
                    if ($message['Content'] == '群发消息') {
                        $app = app('wechat.official_account');
                        $app->broadcasting->sendText("你的好友TerryQi帮你扫码了，还差2个好友助力，即可获得邀请码", ['oJpZ11DU7GZpoW9W_NB5HwXrlYd8', 'oJpZ11Kp7haPT4p0aLXVrF2LgCHY']);
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