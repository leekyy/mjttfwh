<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use Qiniu\Auth;

class ADManager
{

    /*
     * 获取首页轮播图
     *
     * By TerryQi
     *
     * 2018-01-09
     *
     *
     */
    public static function getADs($status, $position)
    {
        $ads = AD::wherein('status', $status)->where('position', $position)->orderby('seq', 'desc')->orderby('id', 'desc')->get();
        return $ads;
    }

    /*
     * 根据id获取轮播图
     * By mtt
     * 2018/1/19
     */
    public static function getById($id)
    {
        $ad = AD::where('id', '=', $id)->first();
        return $ad;
    }


    /*
     * 设置广告信息，用于编辑、
     *
     * By TerryQi
     *
     */
    public static function setAD($ad, $data)
    {
        if (array_key_exists('title', $data)) {
            $ad->title = array_get($data, 'title');
        }
        if (array_key_exists('image', $data)) {
            $ad->image = array_get($data, 'image');
        }
        if (array_key_exists('position', $data)) {
            $ad->position = array_get($data, 'position');
        }
        if (array_key_exists('seq', $data)) {
            $ad->seq = array_get($data, 'seq');
        }
        if (array_key_exists('status', $data)) {
            $ad->status = array_get($data, 'status');
        }
        return $ad;
    }

    /*
     * 广告图搜索
     *
     * By mtt
     *
     * 2018-2-1
     */
    public static function searchAD($search_word){
        $ad = AD::where('title', 'like', '%' . $search_word . '%')->orderby('seq','desc')->get();
        return $ad;
    }











}















