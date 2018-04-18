<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: InviteNumistrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\AdminManager;
use App\Components\InviteNumManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserTJManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\InviteNum;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class UserController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $users = UserManager::getListByCon([], true);
        return view('admin.user.index', ['admin' => $admin, 'datas' => $users]);
    }

    /*
     * 获取用户信息页面
     *
     * By TerryQi
     *
     * 2018-04-18
     */
    public function info(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $user = UserManager::getById($data['user_id']);
        $userTJs = UserTJManager::getListByCon(["tj_user_id" => $data['user_id']], false);
        foreach ($userTJs as $userTJ) {
            $userTJ = UserTJManager::getInfoByLevel($userTJ, "");
        }
        return view('admin.user.info', ['admin' => $admin, 'user' => $user, 'userTJs' => $userTJs]);
    }
}

















