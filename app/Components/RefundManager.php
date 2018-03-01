<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/2/5
 * Time: 14:25
 */

namespace App\Components;


use App\Models\Admin;
use App\Models\Refund;
use App\Models\User;

class RefundManager
{

    /*
     * 查询退款信息
     *
     * By mtt
     *
     * 2018-2-5
     */
    public static function getRefundInfo(){
        $refundInfos = Refund::orderby('id','desc')->get();
        if($refundInfos){
            foreach ($refundInfos as $refundInfo){
                $refundInfo['admin'] = Admin::where('id',$refundInfo['admin_id'])->first();
                $refundInfo['user'] = User::where('id',$refundInfo['user_id'])->first();
            }
            return $refundInfos;
        }
        return '暂无退货信息';
    }

    /*
     * 根据id获取退款信息
     *
     * By mtt
     *
     * 2018-2-25
     */
    public static function getRefundById($id){
        $refund = Refund::where('id','=', $id)->first();
        return $refund;
    }

    /*
     * 设置退款信息
     *
     * By mtt
     *
     * 2018-2-6
     */
    public static function setRefund($refund,$data){
        if (array_key_exists('trade_no', $data)) {
            $refund->trade_no = array_get($data, 'trade_no');
        }
        if (array_key_exists('user_id', $data)) {
            $refund->user_id = array_get($data, 'user_id');
        }
        return $refund;
    }





}











