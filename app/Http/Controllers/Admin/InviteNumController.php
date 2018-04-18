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
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\InviteNum;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class InviteNumController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $inviteNums = InviteNumManager::getListByCon([], true);
        foreach ($inviteNums as $inviteNum) {
            $inviteNum = InviteNumManager::getInfoByLevel($inviteNum, '');
        }
        return view('admin.inviteNum.index', ['admin' => $admin, 'datas' => $inviteNums]);
    }


    //新建或编辑管理员-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $inviteNum = new InviteNum();
        if (array_key_exists('id', $data)) {
            $inviteNum = InviteNumManager::getById($data['id']);
        }
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.inviteNum.edit', ['admin' => $admin, 'data' => $inviteNum, 'upload_token' => $upload_token]);
    }


    //新建或编辑管理员->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        $inviteNum = new InviteNum();
        if (array_key_exists("id", $data) && !Utils::isObjNull($data["id"])) {
            $inviteNum = InviteNumManager::getById($data['id']);
        }
        if (!$inviteNum) {
            $inviteNum = new InviteNum();
        }
        $inviteNum = InviteNumManager::setInfo($inviteNum, $data);
        $inviteNum->admin_id = $admin->id;
        $inviteNum->save();
        return ApiResponse::makeResponse(true, "保存成功", ApiResponse::SUCCESS_CODE);
    }

}

















