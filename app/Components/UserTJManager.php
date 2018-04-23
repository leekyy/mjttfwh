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
use Illuminate\Support\Facades\DB;
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
        if (array_key_exists('start_time', $con_arr) && !Utils::isObjNull($con_arr['start_time'])) {
            $userTJs = $userTJs->where('created_at', '>=', $con_arr['start_time']);
        }
        if (array_key_exists('end_time', $con_arr) && !Utils::isObjNull($con_arr['end_time'])) {
            $userTJs = $userTJs->where('created_at', '<=', $con_arr['end_time']);
        }

        if ($is_paginate) {
            $userTJs = $userTJs->paginate(Utils::PAGE_SIZE);
        } else {
            $userTJs = $userTJs->get();
        }
        return $userTJs;
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
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , COUNT(source_table.id) as nums FROM mjttfwhdb.t_date_list date_list left join mjttfwhdb.t_user_tj source_table on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(source_table.created_at,'%Y-%m-%d')";
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