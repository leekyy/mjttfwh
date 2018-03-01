<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/19
 * Time: 13:17
 */

namespace App\Components;


use App\Models\GoodsDetails;
use App\Models\GoodsImages;
use App\Models\GoodsInfo;
use App\Models\GoodType;

class GoodsInfoManager
{

    /*
     * 根据id获取商品
     *
     * by mtt
     *
     * 2018-01-21
     *
     */
    public static function getGoodsById($id)
    {
        $goodsInfo = GoodsInfo::where('id', $id)->first();
        return $goodsInfo;
    }

    /*
     * 根据状态获取商品列表
     *
     * By mtt
     *
     * 2018-1-21
     *
     */
    public static function getGoodsListByStatus($status,$data)
    {
        $offset=$data["offset"];
        $page=$data["page"];
        $goodsInfos = GoodsInfo::wherein("status", $status)->orderby("seq", "desc")
            ->offset($offset)->limit($page)->get();
        return $goodsInfos;
    }

    /*
     * 根据状态获取商品列表 带分页
     *
     * By mtt
     *
     * 2018-1-21
     *
     */
    public static function getGoodsListByStatusPaginate($status)
    {
        $goodsInfos = GoodsInfo::wherein("status", $status)->orderby("seq", "desc")->paginate(Utils::PAGE_SIZE);
        return $goodsInfos;
    }

    /*
     * 设置商品信息，用于编辑商品信息
     *
     * By mtt
     *
     * 2018-1-21
     *
     */
    public static function setGoodsInfo($goodsInfo, $data)
    {
        if (array_key_exists('title', $data)) {
            $goodsInfo->title = array_get($data, 'title');
        }
        if (array_key_exists('desc', $data)) {
            $goodsInfo->desc = array_get($data, 'desc');
        }
        if (array_key_exists('image', $data)) {
            $goodsInfo->image = array_get($data, 'image');
        }
        if (array_key_exists('show_price', $data)) {
            $goodsInfo->show_price = array_get($data, 'show_price');
        }
        if (array_key_exists('price', $data)) {
            $goodsInfo->price = array_get($data, 'price');
        }
        if (array_key_exists('count', $data)) {
            $goodsInfo->count = array_get($data, 'count');
        }
        if (array_key_exists('type_id', $data)) {
            $goodsInfo->type_id = array_get($data, 'type_id');
        }
        if (array_key_exists('flag', $data)) {
            $goodsInfo->flag = array_get($data, 'flag');
        }
        if (array_key_exists('seq', $data)) {
            $goodsInfo->seq = array_get($data, 'seq');
        }
        if (array_key_exists('sales_status', $data)) {
            $goodsInfo->sales_status = array_get($data, 'sales_status');
        }
        if (array_key_exists('types', $data)) {
            $goodsInfo->types = array_get($data, 'types');
        }
        if (array_key_exists('goods_term', $data)) {
            $goodsInfo->goods_term = array_get($data, 'goods_term');
        }
        return $goodsInfo;
    }

    /*
    * 搜索商品信息
         *
         * By mtt
         *
         * 2018-1-21
         *
         */
    public static function searchGoodsInfo($search_word)
    {
        $GoodsInfos = GoodsInfo::where('title', 'like', '%' . $search_word . '%')->orderby('id', 'desc')->get();
        return $GoodsInfos;
    }

    /*
     * 根据级别获取商品信息
     *
     * by TerryQi
     *
     * 2018-01-23
     */
    public static function getGoodsInfoByLevel($goodsInfo, $level)
    {
        $goodsInfo->type = GoodTypeManager::getGoodTypeById($goodsInfo->type_id);
        return $goodsInfo;
    }


    /*
     * 根据type_id获取商品信息
     *
     * By mtt
     *
     * 2018-01-23
     */
    public static function getGoodsInfoByTypeId($type_id)
    {
        $goodsInfoByTypeId = GoodsInfo::where('type_id', $type_id)->first();
        return $goodsInfoByTypeId;
    }

    /*
     * 根据type_id和状态获取商品列表
     *
     * By mtt
     *
     * 2018-01-23
     */
    public static function getGoodsInfoBTypeIdAndStatus($status, $type_id,$data)
    {
        $offset=$data["offset"];
        $page=$data["page"];
        $goodsInfoByTypeIStatus = GoodsInfo::wherein('status', $status)->where('type_id', $type_id)->orderby("seq", "desc")
            ->offset($offset)->limit($page)->get();
        return $goodsInfoByTypeIStatus;
    }

    /*
     * 根据flag获取推荐商品
     *
     * By mtt
     *
     * 2018-1-25
     */
    public static function getGoodsInfoByFlag($flag,$status,$data)
    {
        $offset=$data["offset"];
        $page=$data["page"];
        $goodsInfos = GoodsInfo::wherein('flag',$flag)->where('status',$status)->orderby('seq','desc')
            ->offset($offset)->limit($page)->get();
        return $goodsInfos;
    }

    /*
     * 根据售卖状态获取热卖特卖普通商品
     *
     * By mtt
     *
     * 2018-1-25
     */
    public static function getGoodsBySalesStatus($status,$sales_status,$data){
        $offset=$data["offset"];
        $page=$data["page"];
        $goods = GoodsInfo::wherein('status',$status)->where('sales_status',$sales_status)->orderby('seq','desc')
            ->offset($offset)->limit($page)->get();
        return $goods;
    }

    /*
     * 搜索显示商品
     *
     * By mtt
     *
     * 2018-1-25
     */
    public static function searchGoodsByStatus($search_word,$status,$data){
        $offset=$data["offset"];
        $page=$data["page"];
        $GoodsInfos = GoodsInfo::where('title', 'like', '%' . $search_word . '%')->where('status',$status)->orderby('id', 'desc')
            ->offset($offset)->limit($page)->get();
        return $GoodsInfos;
    }

    /*
    * 根据type_id获取分类名称显示
    *
    * By mtt
    *
    * 2018-1-31
    */
    public static function getGoodTypeByStatusAndId($status,$type_id){
        $goodType = GoodType::where('status',$status)->where('id',$type_id)->first();
        return $goodType;
    }

    /*
     * 根据id获取生效商品详情信息和商品图片
     *
     * By mtt
     *
     * 2018-1-26
     */
    public static function getGoodsInfosByStatusAndId($status,$data){
        $id = $data['id'];
        //查询商品进本信息
        $goodsInfos = GoodsInfo::wherein('status',$status)->where('id',$id)->first();
        //图文详情信息
        $goodsInfos['details_goods_id'] =GoodsDetails::where('goods_id',$id)->get();
//        商品图片
        $goodsInfos['goods_images'] = GoodsImages::where('goods_id',$id)->orderby('seq','desc')->get();
        return $goodsInfos;
    }

    /*
     * 根据id获取商品详情
     *
     * By mtt
     *
     * 2018-1-31
     */
    public static function getGoodsDetalisById($id){
        $goodsInfo_details = GoodsDetails::where('id',$id)->first();
        return $goodsInfo_details;
    }

    /*
     * 设置商品详情
     *
     * By mtt
     *
     * 2018-1-31
     */
    public static function setGoodsInfoDetails($goodsInfo_details,$data){
        if (array_key_exists('goods_id', $data)) {
            $goodsInfo_details->goods_id = array_get($data, 'goods_id');
        }
        if(array_key_exists('type',$data)){
            $goodsInfo_details->type= array_get($data,'type');
        }
        if(array_key_exists('content',$data)){
            $goodsInfo_details->content= array_get($data,'content');
        }
        if(array_key_exists('seq',$data)){
            $goodsInfo_details->seq= array_get($data,'seq');
        }
        return $goodsInfo_details;
    }

    /*
     * 设置商品图片信息
     *
     * By mtt
     *
     * 2018-2-5
     */
    public static function setImages($goodsImages,$data){
        if (array_key_exists('goods_id', $data)) {
            $goodsImages->goods_id = array_get($data, 'goods_id');
        }
        if(array_key_exists('seq',$data)){
            $goodsImages->seq= array_get($data,'seq');
        }
        if(array_key_exists('image',$data)){
            $goodsImages->image= array_get($data,'image');
        }
        return $goodsImages;
    }

    /*
     * 根据id获取商品图片
     *
     * By mtt
     *
     * 2018-2-5
     */
    public static function getGoodsImagesById($id){
        $goodsImages = GoodsImages::where('id',$id)->first();
        return $goodsImages;
    }









}















