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
use App\Models\GoodsInfo;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\User;
use Qiniu\Auth;

class OrderManager
{

    /*
     * 根据用户信息获取总订单信息
     *
     * By TerryQi
     *
     * 2018-01-24
     *
     */
    public static function getOrderById($id)
    {
        $order = Order::where('id', '=', $id)->first();
        return $order;
    }

    /*
     * 根据level获取订单详细信息
     *
     * By TerryQi
     *
     * 2018-02-05
     *
     */
    public static function getInfoByLevel($subOrder, $level)
    {
        $subOrder->user = UserManager::getUserInfoById($subOrder->user_id);
//        $subOrder->address = AddressManager::getAddsById($subOrder->address_id);
        $subOrder->goods = GoodsInfoManager::getGoodsById($subOrder->goods_id);
        return $subOrder;
    }


    /*
     * 根据trade_no获取总订单信息
     * By mtt
     * 2018/1/24
     */
    public static function getOrderByTradeNo($trade_no)
    {
        $order = Order::where('trade_no', '=', $trade_no)->first();
        return $order;
    }


    /*
     * 设置订单信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setOrder($order, $data)
    {
        if (array_key_exists('trade_no', $data)) {
            $order->trade_no = array_get($data, 'trade_no');
        }
        if (array_key_exists('prepay_id', $data)) {
            $order->prepay_id = array_get($data, 'prepay_id');
        }
        if (array_key_exists('user_id', $data)) {
            $order->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('total_fee', $data)) {
            $order->total_fee = array_get($data, 'total_fee');
        }
        if (array_key_exists('content', $data)) {
            $order->content = array_get($data, 'content');
        }
        if (array_key_exists('status', $data)) {
            $order->status = array_get($data, 'status');
        }
        if (array_key_exists('pay_at', $data)) {
            $order->pay_at = array_get($data, 'pay_at');
        }
        if (array_key_exists('address_id', $data)) {
            $order->address_id = array_get($data, 'address_id');
        }
        if (array_key_exists('pay_at', $data)) {
            $order->pay_at = array_get($data, 'pay_at');
        }
        if (array_key_exists('invoice_id', $data)) {
            $order->invoice_id = array_get($data, 'invoice_id');
        }
        return $order;
    }


}




















