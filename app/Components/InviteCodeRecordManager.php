<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\Admin;
use App\Models\InviteCodeRecord;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class InviteCodeRecordManager
{

    /*
     * 根据id获取信息
     *
     * By TerryQi
     *
     * 2018-04-18
     */
    public static function getById($id)
    {
        $inviteNumCodeRecord = InviteCodeRecord::where('id', '=', $id)->first();
        return $inviteNumCodeRecord;
    }

    /*
     * 系统发送邀请码信息
     *
     * By TerryQi
     *
     * 2018-01-06
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $inviteNumCodeRecords = new InviteCodeRecord();
        //相关条件
        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $inviteNumCodeRecords = $inviteNumCodeRecords->where('user_id', '=', $con_arr['user_id']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $inviteNumCodeRecords = $inviteNumCodeRecords->where('out_trade_no', 'like', '%' . $con_arr['search_word'] . '%')->
            orwhere('invite_code', 'like', '%' . $con_arr['search_word'] . '%');
        }
        if (array_key_exists('type', $con_arr) && !Utils::isObjNull($con_arr['type'])) {
            $inviteNumCodeRecords = $inviteNumCodeRecords->where('type', '=', $con_arr['type']);
        }
        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $inviteNumCodeRecords = $inviteNumCodeRecords->where('user_id', '=', $con_arr['user_id']);
        }
        $inviteNumCodeRecords = $inviteNumCodeRecords->orderby('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $inviteNumCodeRecords = $inviteNumCodeRecords->paginate(Utils::PAGE_SIZE);
        } else {
            $inviteNumCodeRecords = $inviteNumCodeRecords->get();
        }
        return $inviteNumCodeRecords;
    }


    /*
    * 根据条件获取趋势
    *
    * By TerryQi
    *
    * 2018-04-23
    */
    public static function getTrendByCon($con_arr)
    {
        //基本sql
        $type_con_str = "";
        if (array_key_exists('type', $con_arr) && !Utils::isObjNull($con_arr['type'])) {
            $type_con_str = " where type = '" . $con_arr['type'] . "'";
        }
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , COUNT(source_table.id) as nums FROM mjttfwhdb.t_date_list date_list left join (select * from mjttfwhdb.t_invite_code_record " . $type_con_str . " ) source_table on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(source_table.created_at,'%Y-%m-%d')";
        //条件sql
        $con_sql_arr = [];
        foreach ($con_arr as $key => $value) {
            //封装条件
            if ($key == 'start_time' && !Utils::isObjNull($con_arr['start_time'])) {
                array_push($con_sql_arr, 'date_list.date >= ' . '"' . $con_arr['start_time'] . '"');
            }
            if ($key == 'end_time' && !Utils::isObjNull($con_arr['end_time'])) {
                array_push($con_sql_arr, 'date_list.date <= ' . '"' . $con_arr['end_time'] . '"');
            }
        }
        //组装sql
        for ($i = 0; $i < sizeof($con_sql_arr); $i++) {
            if ($i == 0) {
                $sql_str = $sql_str . ' where ' . $con_sql_arr[$i];
            } else {
                $sql_str = $sql_str . ' and ' . $con_sql_arr[$i];
            }
        }

        //最后的group_by
        $sql_str = $sql_str . " GROUP BY tjdate";
//        dd($sql_str);
        $data = DB::select($sql_str);
        return $data;
    }

    /*
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2018-04-02
     */
    public static function getInfoByLevel($info, $level)
    {
        $info->user = UserManager::getById($info->user_id);
        return $info;
    }


    /*
     * 设置推荐关系信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $info->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('invite_code', $data)) {
            $info->invite_code = array_get($data, 'invite_code');
        }
        if (array_key_exists('type', $data)) {
            $info->type = array_get($data, 'type');
        }
        if (array_key_exists('out_trade_no', $data)) {
            $info->out_trade_no = array_get($data, 'out_trade_no');
        }
        if (array_key_exists('pay_status', $data)) {
            $info->pay_status = array_get($data, 'pay_status');
        }
        if (array_key_exists('pay_at', $data)) {
            $info->pay_at = array_get($data, 'pay_at');
        }
        return $info;
    }
}