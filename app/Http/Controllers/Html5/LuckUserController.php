<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/10/3
 * Time: 0:38
 */

namespace App\Http\Controllers\Html5;

use App\Components\AdminManager;
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
        $user = session('wechat.oauth_user'); // 拿到授权用户资料
        dd($user['default']);

        return view('html5.activity.luckUser', ['user' => $user]);
    }

}