<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use Illuminate\Support\Facades\Log;
use Qiniu\Auth;

class WeChatManager
{
    //服务号，根据openid获取用户信息
    public static function getByFWHOpenId($fwh_openid)
    {
        Log::info("getUserInfoByFWHOpenId fwh_openid:" . $fwh_openid);
        $app = app('wechat.official_account');
        $userInfo = $app->user->get($fwh_openid);
        return $userInfo;
    }

    //根据服务号获取的用户信息进行用户注册
    /*
     * By TerryQi
     *
     * 2018-04-05
     */
    public static function register($fwh_openid)
    {
        $wechat_user = self::getByFWHOpenId($fwh_openid);
        Log::info("WechatManager getByFWHOpenId:" . json_encode($wechat_user));
        //封装数据
        $data = array(
            "avatar" => $wechat_user['headimgurl'],
            "nick_name" => $wechat_user['nickname'],
            "gender" => $wechat_user['sex'],
            "province" => $wechat_user['province'],
            "city" => $wechat_user['city'],
            "fwh_openid" => $wechat_user['openid']
        );
        //如果unionid不为空，则也需要将unionid放入data信息，以便进行注册
        if (array_key_exists('unionid', $wechat_user) && !Utils::isObjNull($wechat_user['unionid'])) {
            $data['unionid'] = $wechat_user['unionid'];
        }
        Log::info("WechatManager data:" . json_encode($data));
        $user = UserManager::registerFWH($data);
        return $user;
    }


    //发送模板消息
    /*
     *  {{first.DATA}}
        审核类型：{{keyword1.DATA}}
        审核结果：{{keyword2.DATA}}
        审核时间：{{keyword3.DATA}}
        {{remark.DATA}}
        在发送时，需要将内容中的参数（{{.DATA}}内为参数）赋值替换为需要的信息
     *
     *
     */
    public static function sendTemplateMessage($fwh_openid, $template_id, $data)
    {
        $app = app('wechat.official_account');
        $app->template_message->send([
            'touser' => $fwh_openid,
            'template_id' => $template_id,
            'url' => '',
            'data' => $data,
        ]);
        $response = $app->server->serve();
        return $response;
    }

    /*
     * 自动回复匹配
     *
     * By TerryQi
     *
     * 2018-04-05
     */
    public static function matchKeyWords($key_word)
    {
        //基本关键字组
        $group1 = ['邀请码', '幸运用户', '申请幸运用户', '免费'];
        //如果在组1里面
        if (in_array($key_word, $group1)) {
            return "group1";
        }
        return null;
    }
}