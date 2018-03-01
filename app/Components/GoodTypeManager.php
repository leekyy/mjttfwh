<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/18
 * Time: 18:30
 */

namespace App\Components;


use App\Models\GoodType;

class GoodTypeManager
{
    /*
     * 根据id获取商品类别名称
     * By mtt
     * 2018-1-18
     */
    public static function getGoodTypeById($id)
    {
        $goodType = GoodType::where('id', $id)->first();
        return $goodType;
    }

    /*
     * 根据状态获取商品类别列表
     *
     * By TerryQi
     *
     * 2018-01-20
     *
     */
    public static function getListByStatus($status)
    {
        $goodTypes = GoodType::wherein('status', $status)->orderby('seq', 'desc')->orderby('id', 'desc')->get();
        return $goodTypes;
    }


    /*
    * 设置分类信息，用于编辑、
    *
    * By mtt
    *
     * 2018-01-21
    */
    public static function setGoodType($goodType, $data)
    {
        if (array_key_exists('name', $data)) {
            $goodType->name = array_get($data, 'name');
        }
        if (array_key_exists('img', $data)) {
            $goodType->img = array_get($data, 'img');
        }
        if (array_key_exists('seq', $data)) {
            $goodType->seq = array_get($data, 'seq');
        }
        return $goodType;
    }

    /*
     * 搜索分类信息
     *
     * By mtt
     *
     * 2018-2-1
     */
    public static function searchGoodType($search_word){
        $goodType = GoodType::where('name', 'like', '%' . $search_word . '%')->orderby('seq','desc')->get();
        return $goodType;
    }










}














