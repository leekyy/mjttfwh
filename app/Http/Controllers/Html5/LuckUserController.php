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
        //从session获取用户信息
        $user = $request->session()->get('user');
        //获取app信息
        $app = app('wechat.official_account');
        if (!$user) {
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect('/user/luck_user');
            return $response;
        }

//        $response = $app->oauth->scopes(['snsapi_userinfo'])
//            ->redirect('/user/luck_user');
////        dd($response);
        return view('html5.luck_user.index', ['msg' => '']);
    }

}