<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Address;
use App\Models\Order;
use Qiniu\Auth;

class AddressManager
{

    /*
     * 根据用户id获取地址信息列表
     *
     * By TerryQi
     *
     * 2018-01-24
     *
     */
    public static function getAddressesByUserId($user_id)
    {
        $addresses = Address::where('user_id', '=', $user_id)->where('del_flag', '=', 0)->orderby('def_flag', 'desc')->orderby('id', 'desc')->get();
        return $addresses;
    }

    /*
     * 根据user_id获取默认地址
     *
     * By mtt
     *
     * 2018-1-30
     */
    public static function getDefaultAddsByUserId($user_id){
        $addses = Address::where('user_id', '=',$user_id)->where('del_flag', '=',0)->where("def_flag", '=',1)->first();
        return $addses;
    }

    /*
     * 添加收货地址
     *
     * By mtt
     *
     * 2018-1-30
     */
    public static function addAddress($data){
        $address = new Address();
        $address = self::setAddress($address,$data);
        $address -> save();
        return $address;
    }

    /*
     * 根据id查询收获地址
     *
     * By mtt
     *
     * 2018-1-30
     */
    public static function getAddsById($id){
        $adds = Address::where('id',$id)->first();
        return $adds;
    }

    /*
     * 设置地址信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setAddress($address, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $address->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('rec_name', $data)) {
            $address->rec_name = array_get($data, 'rec_name');
        }
        if (array_key_exists('rec_tel', $data)) {
            $address->rec_tel = array_get($data, 'rec_tel');
        }
        if (array_key_exists('province', $data)) {
            $address->province = array_get($data, 'province');
        }
        if (array_key_exists('city', $data)) {
            $address->city = array_get($data, 'city');
        }
        if (array_key_exists('detail', $data)) {
            $address->detail = array_get($data, 'detail');
        }
        if (array_key_exists('zip_code', $data)) {
            $address->zip_code = array_get($data, 'zip_code');
        }
        if (array_key_exists('def_flag', $data)) {
            $address->def_flag = array_get($data, 'def_flag');
        }
        if (array_key_exists('del_flag', $data)) {
            $address->del_flag = array_get($data, 'del_flag');
        }
        return $address;
    }

    /*
     * 根据订单表里的address_id查询收货地址表里的用户收货地址
     *
     * By mtt
     *
     * 2018-2-2
     */
    public static function getAddressByAddressId($status,$del_flag){
        $order_address = Order::where('status',$status)->get();
        foreach ($order_address as $address){
            $address['address'] = Address::where('del_flag',$del_flag)->where('id',$address['address_id'])->first();
        }
        return $order_address;
    }







}







