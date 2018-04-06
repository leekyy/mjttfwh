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


    /*
     * 生成用户的邀请二维码，并返回邀请码图片名称
     *
     * By TerryQi
     *
     * 2018-04-06
     *
     */
    public static function createUserYQCode($user_id)
    {
        $app = app('wechat.official_account');
        //邀请二维码图片名称
        $filename = 'user' . $user_id . '_yq_code.jpg';
        if (file_exists(public_path('img/') . $filename)) {
            Log::info($filename . " file exists");
        } else {
            //获取用户
            $user = UserManager::getById($user_id);
            $result = $app->qrcode->forever($user->fwh_openid);
            Log::info("app->qrcode->forever result:" . json_encode($result));
            $url = $app->qrcode->url($result['ticket']);
            $content = file_get_contents($url); // 得到二进制图片内容
            file_put_contents(public_path('img/') . $filename, $content); // 写入文件
            //建立素材，暂不实现
//            $result = $app->material->uploadImage(public_path('img/') . $filename);
//            Log::info("app->material->uploadImage file exists result:" . json_encode($result));
//            $user = UserManager::getByIdWithToken($user->id);
//            $user->yq_code_media_id = $result['media_id'];
//            $user->save();
        }
        return $filename;
    }


    /*
     * 生成二维码海报图片，并返回海报名称
     *
     * By TerryQi
     *
     * 2018-04-06
     */
    public static function createUserYQHB($user_id)
    {
        //邀请海报图片名称
        $filename = 'user' . $user_id . '_yq_hb.jpg';
        if (file_exists(public_path('img/') . $filename)) {
            Log::info($filename . " file exists");
        } else {
            //二维码图片名称
            $user_yq_code_filename = self::createUserYQCode($user_id);
            $path_1 = public_path('img/') . 'fxhb_bg.jpg';
            $path_2 = public_path('img/') . $user_yq_code_filename;
            $image_1 = imagecreatefromjpeg($path_1);
            $image_2 = imagecreatefromjpeg($path_2);
            list($width, $height) = getimagesize($path_2);
            //生成缩略图 二维码 200*200
            $ewm_width = 200;
            $ewm_height = 200;
            $image_2_resize = imagecreatetruecolor($ewm_width, $ewm_height);
            imagecopyresized($image_2_resize, $image_2, 0, 0, 0, 0, $ewm_width, $ewm_height, $width, $height);

            $image_3 = imageCreatetruecolor(imagesx($image_1), imagesy($image_1));
            $color = imagecolorallocate($image_3, 255, 255, 255);
            imagefill($image_3, 0, 0, $color);
            imageColorTransparent($image_3, $color);
            imagecopyresampled($image_3, $image_1, 0, 0, 0, 0, imagesx($image_1), imagesy($image_1), imagesx($image_1), imagesy($image_1));
            imagecopymerge($image_3, $image_2_resize, 45, 1100, 0, 0, imagesx($image_2_resize), imagesy($image_2_resize), 100);
            imagejpeg($image_3, public_path('img/') . $filename);
        }
        return $filename;
    }

    /*
     * 建立图片素材
     *
     * By TerryQi
     *
     * 2018-04-06
     */
    public static function createMediaId($filename)
    {
        $app = app('wechat.official_account');
        $result = $app->material->uploadImage(public_path('img/') . $filename);
        Log::info("app->material->uploadImage file exists result:" . json_encode($result));
        return $result['media_id'];
    }
}