<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/2/2
 * Time: 21:04
 */

namespace App\Components;


use App\Models\GiftCard;
use App\Models\GoodsInfo;
use App\Models\User;

class GiftCardManager
{
    /*
        * 获取礼品卡信息
        *
        * By mtt
        *
        * 2018-2-2
        */
    public static function giftCardList(){
        //    查询会员卡表中数据
        $giftCardList = GiftCard::orderby('id','desc')->get();
        if($giftCardList){
//            遍历会员卡表中数据取用户名字和商品名字
            foreach ($giftCardList as $giftCard){
                $giftCard['user'] = User::where('id',$giftCard['user_id'])->first();
                $giftCard['goods'] = GoodsInfo::where('id',$giftCard['goods_id'])->first();
            }
            return $giftCardList;
        }else{
            return '暂无会员列表';
        }
    }

    /*
     * 根据id获取礼品卡信息
     *
     * By mtt
     *
     * 2018-2-2
     */
    public static function getGiftCardById($id){
        $giftCard = GiftCard::where('id',$id)->first();
        return $giftCard;
    }

    /*
     * 根据user_id获取未用过的礼品卡信息
     *
     * By mtt
     *
     * 2018-2-3
     */
    public static function getGiftCardByUserId($user_id,$status){
//        查询礼品卡中数据
        $giftCards = GiftCard::where('user_id',$user_id)->where('status',$status)->orderby('id','desc')->get();
        if($giftCards){
            foreach ($giftCards as $giftCard){
                $giftCard['user'] = User::where('id',$user_id)->first();
                $giftCard['goods'] = GoodsInfo::where('id',$giftCard['goods_id'])->first();
            }
            return $giftCards;
        }else{
            return '暂无礼品卡信息';
        }
    }








}
















