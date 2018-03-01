<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/2/2
 * Time: 15:47
 */

namespace App\Components;


use App\Http\Controllers\ApiResponse;
use App\Models\GoodsInfo;
use App\Models\Member;
use App\Models\User;

class MemberManager
{

    /*
     * 获取会员信息
     *
     * By mtt
     *
     * 2018-2-2
     */
    public static function memberList(){
        //    查询会员卡表中数据
        $memberList = Member::orderby('id','desc')->get();
        if($memberList){
//            遍历会员卡表中数据取用户名字和商品名字
            foreach ($memberList as $member){
                $member['user'] = User::where('id',$member['user_id'])->first();
                $member['goods'] = GoodsInfo::where('id',$member['goods_id'])->first();
            }
            return $memberList;
        }else{
            return '暂无会员列表';
        }

    }

    /*
     * 根据id获取会员信息
     *
     * By mtt
     *
     * 2018-2-2
     */
    public static function getMemberById($id){
        $member = Member::where('id',$id)->first();
        return $member;
    }

    /*
     * 设置会员信息，用于编辑或新建
     *
     * By mtt
     *
     * 2018-2-2
     */
    public static function setMember($member,$data){
        if (array_key_exists('num', $data)) {
            $member->num = array_get($data, 'num');
        }
        if (array_key_exists('expiration_date', $data)) {
            $member->expiration_date = array_get($data, 'expiration_date');
        }
        return $member;
    }

    /*
     * 根据user_id获取未使用过的会员卡信息
     *
     * By mtt
     *
     * 2018-2-3
     */
    public static function getMemberByUserId($user_id,$status){
//        查询会员卡表中数据
        $members = Member::where('user_id',$user_id)->where('status',$status)->orderby('id','desc')->get();
        if($members) {

//            遍历获取用户信息和商品信息
            foreach ($members as $member){
                $member['user'] = User::where('id',$user_id)->first();
                $member['goods'] = GoodsInfo::where('id',$member['goods_id'])->first();
            }
            return $members;
        }else{
            return '暂无会员信息';
        }
    }







}



















