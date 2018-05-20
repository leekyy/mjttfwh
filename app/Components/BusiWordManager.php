<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/18
 * Time: 8:59
 */

namespace App\Components;

use App\Models\BusiWord;

class BusiWordManager
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
        $busiWord = BusiWord::where('id', '=', $id)->first();
        return $busiWord;
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
        $busiWord = BusiWord::where('template_id', '=', $template_id)->first();
        return $busiWord->content;
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
        if (array_key_exists('template_id', $data)) {
            $info->template_id = array_get($data, 'template_id');
        }
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('content', $data)) {
            $info->content = array_get($data, 'content');
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
        $replies = new BusiWord();
        //相关条件
        if ($con_arr) {
            if (array_key_exists('keyword', $con_arr) && !Utils::isObjNull($con_arr['keyword'])) {
                $replies = $replies->where('content', 'like', "%" . $con_arr['keyword'] . "%")->orwhere('name', 'like', "%" . $con_arr['keyword'] . "%");
            }
        }
        if ($is_paginate) {
            $replies = $replies->paginate(Utils::PAGE_SIZE);
        } else {
            $replies = $replies->get();
        }
        return $replies;
    }
}