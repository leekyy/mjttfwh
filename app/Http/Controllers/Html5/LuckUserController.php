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
use App\Libs\CommonUtils;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use App\Models\Admin;

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
        //以上已经完成用户注册，为每个用户申请小程序邀请码
        $filename = 'user' . $user->id . '_yq_code.jpg';
        //判断是否已经生成邀请码
        if (file_exists(public_path('img/') . $filename)) {
            Log::info($filename . " file exists");
        } else {
            $app = app('wechat.official_account');
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

        return view('html5.activity.luckUser', ['user' => $user]);
    }

}