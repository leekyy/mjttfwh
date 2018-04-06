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
use Illuminate\Support\Facades\Log;

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

        return view('html5.activity.luckUser', ['user' => $user, 'wx_config' => $wx_config]);
    }

}