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
use App\Components\InviteCodeRecordManager;
use App\Components\InviteNumManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\InviteCodeRecord;
use App\Models\InviteNum;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class InviteCodeRecordController
{

    //首页
    public function index(Request $request)
    {
        $inviteCodeRecord = $request->session()->get('admin');
        $inviteCodeRecords = InviteCodeRecordManager::getListByCon([], true);
        foreach ($inviteCodeRecords as $inviteCodeRecord) {
            $inviteCodeRecord = InviteCodeRecordManager::getInfoByLevel($inviteCodeRecord, '');
        }
//        dd($inviteCodeRecords);
        return view('admin.inviteCodeRecord.index', ['datas' => $inviteCodeRecords]);
    }
}

















