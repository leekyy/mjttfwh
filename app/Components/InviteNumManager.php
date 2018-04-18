<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\Admin;
use App\Models\InviteNum;
use Qiniu\Auth;

class InviteNumManager
{

    /*
     * 获取当前的邀请数配置
     *
     * By TerryQi
     *
     * 2018-04-18
     */
    public static function getCurrYQNum()
    {
        $inviteNum = InviteNum::orderby('id', 'desc')->first();
        if (!$inviteNum) {
            return Utils::DEFAULT_YQ_NUM;
        } else {
            return $inviteNum->yq_num;
        }
    }

    /*
     * 根据id获取信息
     *
     * By TerryQi
     *
     * 2018-04-18
     */
    public static function getById($id)
    {
        $inviteNum = InviteNum::where('id', '=', $id)->first();
        return $inviteNum;
    }

    /*
     * 系统邀请数信息
     *
     * By TerryQi
     *
     * 2018-01-06
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $inviteNums = new InviteNum();
        $inviteNums = $inviteNums->orderby('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $inviteNums = $inviteNums->paginate(Utils::PAGE_SIZE);
        } else {
            $inviteNums = $inviteNums->get();
        }
        return $inviteNums;
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
        $info->admin = AdminManager::getAdminById($info->admin_id);
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
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('yq_num', $data)) {
            $info->yq_num = array_get($data, 'yq_num');
        }
        return $info;
    }
}