<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/18
 * Time: 8:59
 */

namespace App\Components;

use App\Models\HaibaoPic;

class HaibaoPicManager
{
    /*
     * 根据id获取信息
     *
     * By Amy
     *
     * 2018-05-09
     */
    public static function getById($id)
    {
        $haibaoPic = HaibaoPic::where('id', '=', $id)->first();
        return $haibaoPic;
    }

    /*
     * 获取最新的海报
     *
     * By TerryQi
     *
     * 2018-05-22
     */
    public static function getLatestHaibaoPic()
    {
        $haibaos = self::getListByCon([], false);
        if ($haibaos->count() > 0) {
            return $haibaos->first()->name;
        } else {
            return 'fxhb_bg.jpg';
        }
    }

    /*
     * 根据template_id获取业务话术
     *
     * By TerryQi
     *
     * 2018-05-20
     */
    public static function getByTemplateID($template_id)
    {
        $haibaoPic = HaibaoPic::where('template_id', '=', $template_id)->first();
        return $haibaoPic->content;
    }


    /*
     * 设置自动回复信息，用于编辑
     *
     * By Amy
     *
     * 2018-05-09
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        return $info;
    }

    /*
     * 获取自动回复列表
     *
     * By Amy
     *
     * 2018-05-10
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $haibaoPics = new HaibaoPic();
        //相关条件
        $haibaoPics = $haibaoPics->orderby('id', 'desc');
        if ($is_paginate) {
            $haibaoPics = $haibaoPics->paginate(Utils::PAGE_SIZE);
        } else {
            $haibaoPics = $haibaoPics->get();
        }
        return $haibaoPics;
    }
}