<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Admin;
use App\Models\UserTJ;
use Qiniu\Auth;

class UserTJManager
{

    /*
     * 用户是否已经被推荐
     *
     * By TerryQi
     *
     * 2018-04-02
     */
    public static function isUserHasBennTJ($tj_user_id, $user_id)
    {
        $con_arr = [
            'user_id' => $user_id,
            'tj_user_id' => $tj_user_id
        ];
        //如果推荐列表不为0
        if (self::getListByCon($con_arr, false)->count() != 0) {
            return true;
        } else {
            return false;
        }

    }


    /*
     * 用户推荐信息
     *
     * By TerryQi
     *
     * 2018-01-06
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $userTJs = new UserTJ();
        //配置规则
        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $userTJs = $userTJs->where('user_id', '=', $con_arr['user_id']);
        }
        if (array_key_exists('tj_user_id', $con_arr) && !Utils::isObjNull($con_arr['tj_user_id'])) {
            $userTJs = $userTJs->where('tj_user_id', '=', $con_arr['tj_user_id']);
        }
        if ($is_paginate) {
            $userTJs = $userTJs->paginate(Utils::PAGE_SIZE);
        } else {
            $userTJs = $userTJs->get();
        }
        return $userTJs;
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
        $info->tj_user = UserManager::getById($info->tj_user_id);
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
        if (array_key_exists('tj_user_id', $data)) {
            $info->tj_user_id = array_get($data, 'tj_user_id');
        }
        return $info;
    }
}