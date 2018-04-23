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
use App\Components\DateTool;
use App\Components\InviteCodeRecordManager;
use App\Components\InviteNumManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserTJManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\InviteCodeRecord;
use App\Models\InviteNum;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\VarDumper\Cloner\Data;


class StmtController
{

    //首页
    public function index(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        //相关搜素条件
        $start_time = DateTool::dateAdd('D', -30, DateTool::getToday(), 'Y-m-d');    //搜索条件
        $end_time = DateTool::getToday();
        if (array_key_exists('start_time', $data) && !Utils::isObjNull($data['start_time'])) {
            $start_time = $data['start_time'];
        }
        if (array_key_exists('end_time', $data) && !Utils::isObjNull($data['end_time'])) {
            $end_time = $data['end_time'];
        }
        $con_arr = array(
            'start_time' => $start_time,
            'end_time' => $end_time
        );
//        dd($con_arr);
        //用户统计数据
        $user_total_num = UserManager::getListByCon($con_arr, false)->count();
//        dd($user_total_num);
        $user_increase_trend = UserManager::getTrendByCon($con_arr);
        //top10邀请用户数据
        $top_invite_users_con_arr = array(
            'start_time' => $start_time,
            'end_time' => $end_time,
            'orderby' => 'yq_num',
            'take' => 10
        );
        $top_invite_users = UserManager::getListByCon($top_invite_users_con_arr, false);
        //邀请趋势相关
        $invite_user_total_num = UserTJManager::getListByCon($con_arr, false)->count();
        $invite_user_increase_trend = UserTJManager::getTrendByCon($con_arr);

        //邀请码相关
        $free_invite_code_con_arr = array(
            'start_time' => $start_time,
            'end_time' => $end_time,
            'type' => '0'
        );
        $free_invite_code_num = InviteCodeRecordManager::getListByCon($free_invite_code_con_arr, false)->count();
        $charge_invite_code_con_arr = array(
            'start_time' => $start_time,
            'end_time' => $end_time,
            'type' => '1'
        );
        $charge_invite_code_num = InviteCodeRecordManager::getListByCon($charge_invite_code_con_arr, false)->count();
        $total_income = $charge_invite_code_num * 78;

        $free_invite_code_increase_trend = InviteCodeRecordManager::getTrendByCon($free_invite_code_con_arr);
        $charge_invite_code_increase_trend = InviteCodeRecordManager::getTrendByCon($charge_invite_code_con_arr);

//        dd($top_invite_users);
        return view('admin.stmt.index', ['user_total_num' => $user_total_num, 'user_increase_trend' => json_encode($user_increase_trend)
            , 'top_invite_users' => $top_invite_users, 'free_invite_code_num' => $free_invite_code_num, 'charge_invite_code_num' => $charge_invite_code_num
            , 'total_income' => $total_income, 'invite_user_total_num' => $invite_user_total_num, 'invite_user_increase_trend' => json_encode($invite_user_increase_trend)
            , 'free_invite_code_increase_trend' => json_encode($free_invite_code_increase_trend), 'charge_invite_code_increase_trend' => json_encode($charge_invite_code_increase_trend), 'con_arr' => $con_arr]);
    }
}

















