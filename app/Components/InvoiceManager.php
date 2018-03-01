<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Invoice;
use Qiniu\Auth;

class InvoiceManager
{

    /*
     * 根据用户id获取发票列表
     *
     * By TerryQi
     *
     * 2018-01-24
     *
     */
    public static function getInvoicesByUserId($user_id)
    {
        $invoices = Invoice::where('user_id', '=', $user_id)->orderby('def_flag', 'desc')->orderby('id', 'desc')->get();
        return $invoices;
    }

    /*
     * 根据id获取发票信息详情
     *
     * By mtt
     * 2018/1/24
     */
    public static function getInvoiceById($id)
    {
        $invoice = Invoice::where('id', '=', $id)->first();
        return $invoice;
    }

    /*
     * 设置发票信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setInvoice($invoice, $data)
    {
        if (array_key_exists('cu_name', $data)) {
            $invoice->cu_name = array_get($data, 'cu_name');
        }
        if (array_key_exists('rec_tel', $data)) {
            $invoice->rec_tel = array_get($data, 'rec_tel');
        }
        if (array_key_exists('province', $data)) {
            $invoice->province = array_get($data, 'province');
        }
        if (array_key_exists('city', $data)) {
            $invoice->city = array_get($data, 'city');
        }
        if (array_key_exists('detail', $data)) {
            $invoice->detail = array_get($data, 'detail');
        }
        if (array_key_exists('zip_code', $data)) {
            $invoice->zip_code = array_get($data, 'zip_code');
        }
        if (array_key_exists('type', $data)) {
            $invoice->type = array_get($data, 'type');
        }
        if (array_key_exists('def_flag', $data)) {
            $invoice->def_flag = array_get($data, 'def_flag');
        }
        if (array_key_exists('del_flag', $data)) {
            $invoice->del_flag = array_get($data, 'del_flag');
        }
        return $invoice;
    }

    /*
     * 根据type和user_id获取发票信息
     *
     * By mtt
     *
     * 2018-2-8
     */
    public static function getInvoiceByTypeAndUserId($type,$user_id){
        $invoice = Invoice::where('type',$type)->where('user_id',$user_id)->get();
        return $invoice;
    }

    /*
     * 设置专票发票信息，用于保存
     *
     * By mtt
     *
     * 2018-2-8
     */
    public static function setsSpecialInvoice($invoice,$data){
        if (array_key_exists('cu_name', $data)) {
            $invoice->cu_name = array_get($data, 'cu_name');
        }
        if (array_key_exists('tax_code', $data)) {
            $invoice->tax_code = array_get($data, 'tax_code');
        }
        if (array_key_exists('register_tel', $data)) {
            $invoice->register_tel = array_get($data, 'register_tel');
        }
        if (array_key_exists('back_name', $data)) {
            $invoice->back_name = array_get($data, 'back_name');
        }
        if (array_key_exists('account_no', $data)) {
            $invoice->account_no = array_get($data, 'account_no');
        }
        if (array_key_exists('register_addr', $data)) {
            $invoice->register_addr = array_get($data, 'register_addr');
        }
        if (array_key_exists('user_id', $data)) {
            $invoice->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('type', $data)) {
            $invoice->type = array_get($data, 'type');
        }
        if (array_key_exists('def_flag', $data)) {
            $invoice->def_flag = array_get($data, 'def_flag');
        }
        return $invoice;
    }
    /*
     * 设置普票发票信息
     *
     * By mtt
     *
     * 2018-2-8
     */
    public static function setsGeneralInvoice($invoice,$data){
        if (array_key_exists('cu_name', $data)) {
            $invoice->cu_name = array_get($data, 'cu_name');
        }
        if (array_key_exists('user_id', $data)) {
            $invoice->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('type', $data)) {
            $invoice->type = array_get($data, 'type');
        }
        if (array_key_exists('def_flag', $data)) {
            $invoice->def_flag = array_get($data, 'def_flag');
        }
        return $invoice;
    }





}




















