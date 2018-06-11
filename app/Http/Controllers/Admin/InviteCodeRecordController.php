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
        $data = $request->all();
        $admin = $request->session()->get('admin');

        //相关搜素条件
        $search_word = null;    //搜索条件
        $type = null;
        $user_id = null;
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('type', $data) && !Utils::isObjNull($data['type'])) {
            $type = $data['type'];
        }
        if (array_key_exists('user_id', $data) && !Utils::isObjNull($data['user_id'])) {
            $user_id = $data['user_id'];
        }
        $con_arr = array(
            'search_word' => $search_word,
            'type' => $type,
            'user_id' => $user_id
        );

        $inviteCodeRecords = InviteCodeRecordManager::getListByCon($con_arr, true);
        foreach ($inviteCodeRecords as $inviteCodeRecord) {
            $inviteCodeRecord = InviteCodeRecordManager::getInfoByLevel($inviteCodeRecord, '');
        }
//        dd($inviteCodeRecords);
        return view('admin.inviteCodeRecord.index', ['datas' => $inviteCodeRecords, 'con_arr' => $con_arr]);
    }
}

















