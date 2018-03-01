<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/30
 * Time: 21:04
 */

namespace App\Components;


use App\Models\Farm;
use App\Models\FarmDetail;

class FarmManager
{
    /*
     * 根据农场id查询农场详情
     *
     * By mtt
     *
     * 2018-1-30
     */
    public static function getFarmDetailsById($status, $data)
    {
        $id = $data['id'];
//        查询农场信息
        $farmsDetails = Farm::where('status', $status)->where('id', $id)->first();
//        农场详情信息
        $farmsDetails['farm_detail'] = FarmDetail::where('farm_id', $id)->get();
        return $farmsDetails;
    }

    /*
     * 根据农场id获取生效的农场信息
     *
     * By mtt
     *
     * 2018-1-31
     */
    public static function getFarmById($status, $id)
    {
        $farm = Farm::where('status', $status)->where('id', $id)->orderby('id', 'desc')->first();
        return $farm;
    }

    /*
     * 根据状态获取农场信息
     *
     * By mtt
     *
     * 2018-1-31
     */
    public static function getFarmListByStatus($status)
    {
        $farmList = Farm::wherein('status', $status)->orderby('id', 'desc')->get();
        return $farmList;
    }

    /*
     * 搜索农场名字
     *
     * By mtt
     *
     * 2018-2-1
     */
    public static function searchFarm($search_word){
        $farm = Farm::where('name', 'like', '%' . $search_word . '%')->orderby('id','desc')->get();
        return $farm;
    }

    /*
     * 根据id查询农场信息
     *
     * By mtt
     *
     * 2018-2-1
     */
    public static function getFarmsById($id){
        $farm = Farm::where('id',$id)->first();
        return $farm;
    }

    /*
     * 设置农场信息，用于编辑
     *
     * By mtt
     *
     * 2018-2-1
     */
    public static function setFarm($farm, $data)
    {
        if (array_key_exists('province', $data)) {
            $farm->province = array_get($data, 'province');
        }
        if (array_key_exists('city', $data)) {
            $farm->city = array_get($data, 'city');
        }
        if (array_key_exists('address', $data)) {
            $farm->address = array_get($data, 'address');
        }
        if (array_key_exists('name', $data)) {
            $farm->name = array_get($data, 'name');
        }
        if (array_key_exists('lon', $data)) {
            $farm->lon = array_get($data, 'lon');
        }
        if (array_key_exists('lat', $data)) {
            $farm->lat = array_get($data, 'lat');
        }
        return $farm;
    }

    /*
     * 根据id获取农场详情
     *
     * By mtt
     *
     * 2018-2-1
     */
    public static function getFarmDetailById($id){
        $farm = FarmDetail::where('id',$id)->first();
        return $farm;
    }

    /*
     * 设置农场详情信息
     *
     * By mtt
     *
     * 2018-2-1
     */
    public static function setFarmDetails($farm_details,$data){
        if (array_key_exists('farm_id', $data)) {
            $farm_details->farm_id = array_get($data, 'farm_id');
        }
        if (array_key_exists('content', $data)) {
            $farm_details->content = array_get($data, 'content');
        }
        if (array_key_exists('type', $data)) {
            $farm_details->type = array_get($data, 'type');
        }
        if (array_key_exists('seq', $data)) {
            $farm_details->seq = array_get($data, 'seq');
        }
        return $farm_details;
    }







}

















