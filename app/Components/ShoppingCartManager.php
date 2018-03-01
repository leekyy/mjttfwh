<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/28
 * Time: 16:49
 */

namespace App\Components;


use App\Models\ShoppingCart;

class ShoppingCartManager
{
    /*
     * 根据user_id查询购物车商品
     *
     * By mtt
     *
     * 2018-1-28
     */
    public static function getShoppingCartListByUserId($user_id){
        $goodses = ShoppingCart::where('user_id',$user_id)->orderby('id','desc')->get();
        foreach ($goodses as $goods){
            $goods['goods_id'] = GoodsInfoManager::getGoodsById($goods['good_id']);
        }
        return $goodses;
    }

    /*
    * 添加购物车
    *
    * By mtt
    *
    * 2018-1-28
    */
    public static function addShoppingCart($data){
        $shoppingCart = new ShoppingCart();
        $shoppingCart = self::setShoppingCart($shoppingCart,$data);
        $shoppingCart = $shoppingCart->save();
        return $shoppingCart;
    }

    /*
    * 删除购物车商品
    *
    * By mtt
    *
    * 2018-1-28
    */
    public static function deletedShoppingCartGoods($data){
        foreach ($data['id'] as $id){
            $result=ShoppingCart::where('id',$id)->delete();
        }
        return $result?true:false;
    }







    /*
     * 配置添加购物车参数
     *
     * By mtt
     *
     * 2018-1-28
     */
    public static function setShoppingCart($shoppingCart,$data){
        if (array_key_exists('user_id', $data)) {
            $shoppingCart->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('good_id', $data)) {
            $shoppingCart->good_id = array_get($data, 'good_id');
        }
        return $shoppingCart;
    }








}













