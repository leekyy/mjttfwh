<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\SubOrder;
use Qiniu\Auth;

class SubOrderManager
{

    /*
     * 根据用户信息获取子订单信息
     *
     * By TerryQi
     *
     * 2018-01-24
     *
     */
    public static function getSubOrderById($id)
    {
        $suborder = SubOrder::where('id', '=', $id)->first();
        return $suborder;
    }

    /*
     * 根据trade_no获取子订单信息
     * By mtt
     * 2018/1/24
     */
    public static function getSubOrderByTradeNo($trade_no)
    {
        $suborder = SubOrder::where('trade_no', '=', $trade_no)->get();
        return $suborder;
    }


    /*
     * 设置订单信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setSubOrder($suborder, $data)
    {
        if (array_key_exists('sub_trade_no', $data)) {
            $suborder->sub_trade_no = array_get($data, 'sub_trade_no');
        }
        if (array_key_exists('trade_no', $data)) {
            $suborder->trade_no = array_get($data, 'trade_no');
        }
        if (array_key_exists('user_id', $data)) {
            $suborder->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('goods_id', $data)) {
            $suborder->goods_id = array_get($data, 'goods_id');
        }
        if (array_key_exists('total_fee', $data)) {
            $suborder->total_fee = array_get($data, 'total_fee');
        }
        if (array_key_exists('count', $data)) {
            $suborder->count = array_get($data, 'count');
        }
        if (array_key_exists('content', $data)) {
            $suborder->content = array_get($data, 'content');
        }
        if (array_key_exists('wl_np', $data)) {
            $suborder->wl_np = array_get($data, 'wl_np');
        }
        if (array_key_exists('wl_status', $data)) {
            $suborder->wl_status = array_get($data, 'wl_status');
        }
        if (array_key_exists('status', $data)) {
            $suborder->status = array_get($data, 'status');
        }
        return $suborder;
    }


    /*
     * 获取子订单信息
     *
     * By mtt
     *
     * 2018-2-6
     */
    public static function getSuborderInfos(){
        $subOrders = SubOrder::orderby('id','desc')->get();
        return $subOrders;
    }

    /*
     * 根据level获取订单详细信息
     *
     * By TerryQi
     *
     * 2018-02-05
     *
     */
    public static function getInfoByLevel($subOrder,$level){
        $subOrder->user = UserManager::getUserInfoById($subOrder->user_id);
        $subOrder -> goods = GoodsInfoManager::getGoodsById($subOrder->goods_id);
        $subOrder -> order = OrderManager::getOrderByTradeNo($subOrder->trade_no);
        $address_id = $subOrder->order->address_id;
        $subOrder->address = AddressManager::getAddsById($address_id);
        $invoice_id = $subOrder->order->invoice_id;
        $subOrder -> invoice = InvoiceManager::getInvoiceById($invoice_id);
        return $subOrder;
    }

    /*
     * 根据状态获取订单信息
     *
     * By mtt
     *
     * 2018-2-6
     */
    public static function getPayOrderByStatus($status){
        $paySuccessOrder = SubOrder::where('status',$status)->orderby('id','desc')->get();
        return $paySuccessOrder;
    }

    /*
     * 根据物流状态获取订单信息
     *
     * By mtt
     *
     * 2018-2-16
     */
    public static function getPayOrderByWlStatus($wlStatus){
        $payDeliveredOrder = SubOrder::where('wl_status',$wlStatus)->orderby('id','desc')->get();
        return $payDeliveredOrder;
    }

    /*
     * 查看未发货订单（备货中）及支付成功的订单
     *
     * By mtt
     *
     * 2018-2-18
     */
    public static function getPayOrderByWlStatusAndOrderStatus($wlStatus,$status){
        $payUnshippedDeliveryOrder = SubOrder::where('wl_status',$wlStatus)->where('status',$status)->get();
        return $payUnshippedDeliveryOrder;
    }











}























