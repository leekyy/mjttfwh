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