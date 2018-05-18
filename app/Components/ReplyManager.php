<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/18
 * Time: 8:59
 */

namespace App\Components;

use App\Models\Reply;

class ReplyManager
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
        $reply = Reply::where('id', '=', $id)->first();
        return $reply;
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
        if (array_key_exists('keyword', $data)) {
            $info->keyword = array_get($data, 'keyword');
        }
        if (array_key_exists('content', $data)) {
            $info->content = array_get($data, 'content');
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
    public static function getListByCon($con_arr, $is_paginate){
        $replies=new Reply();
        //相关条件
        if($con_arr){
            if (array_key_exists('keyword', $con_arr) && !Utils::isObjNull($con_arr['keyword'])) {
                $replies = $replies->where('keyword', 'like', "%" . $con_arr['keyword'] . "%");
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