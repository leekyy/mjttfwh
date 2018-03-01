<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/25
 * Time: 16:35
 */

namespace App\Components;


use App\Models\News;
use App\Models\NewsDetails;

class NewsManager
{
    /*
     * 查询所有新闻
     *
     * By mtt
     *
     * 2018-1-25
     */
    public static function getNewsByStatus($status)
    {
        $news = News::wherein('status', $status)->orderby('seq', 'desc')->get();
        return $news;
    }

    /*
     * 根据id获取新闻消息
     *
     * By mtt
     *
     * 2018-1-25
     */
    public static function getNewsById($id)
    {
        $new = News::where('id', '=', $id)->first();
        return $new;
    }

    /*
     * 设置新闻信息，用于编辑
     *
     * By mtt
     *
     * 2018-1-25
     */
    public static function setNews($new, $data)
    {
        if (array_key_exists('title', $data)) {
            $new->title = array_get($data, 'title');
        }
        if (array_key_exists('seq', $data)) {
            $new->seq = array_get($data, 'seq');
        }
        if (array_key_exists('intro', $data)) {
            $new->intro = array_get($data, 'intro');
        }
        if (array_key_exists('author', $data)) {
            $new->author = array_get($data, 'author');
        }
        if (array_key_exists('img', $data)) {
            $new->img = array_get($data, 'img');
        }
        if (array_key_exists('flag', $data)) {
            $new->flag = array_get($data, 'flag');
        }
        return $new;
    }

    /*
     * 搜索新闻标题及作者
     *
     * By mtt
     *
     * 2018-1-25
     */
    public static function searchNews($search_word)
    {
        $news = News::where('title', 'like', '%' . $search_word . '%')
            ->orwhere('author', 'like', '%' . $search_word . '%')->orderby('id', 'desc')->get();
        return $news;
    }

    /*
     * 搜索生效的商品
     *
     * By mtt
     *
     * 2018-1-25
     */
    public static function searchNewsByStatus($search_word,$status,$data){
        $offset=$data["offset"];
        $page=$data["page"];
        $news = News::where('title', 'like', '%' . $search_word . '%')
            ->orwhere('author', 'like', '%' . $search_word . '%')->where('status',$status)->orderby('id', 'desc')
            ->offset($offset)->limit($page)->get();
        return $news;
    }

    /*
     * 根据新闻id获取新闻详情
     *
     * By mtt
     *
     * 2018-1-28
     */
    public static function getNewsDetailsByNewsId($status,$data){
        $id = $data['id'];
//        查询新闻基本信息
        $newsDetails = News::where('status',$status)->where('id',$id)->first();
//        新闻详情信息
        $newsDetails['news_details'] = NewsDetails::where('news_id',$id)->get();
        return $newsDetails;
    }

    /*
         * 通过id获得NewsDetail
         *
         * By mtt
         *
         * 2018-01-29
         *
         */
    public static function getNewsDetailById($id){
        $news_detail=NewsDetails::where('id',$id)->first();
        return $news_detail;
    }

    /*
     * 配置NewsDetail的参数
     *
     * By mtt
     *
     * 2018-01-29
     *
     */
    public static function setNewsDetail($news_detail, $data){
        if (array_key_exists('news_id', $data)) {
            $news_detail->news_id = array_get($data, 'news_id');
        }
        if (array_key_exists('content', $data)) {
            $news_detail->content = array_get($data, 'content');
        }
        if (array_key_exists('type', $data)) {
            $news_detail->type = array_get($data, 'type');
        }
        if (array_key_exists('seq', $data)) {
            $news_detail->seq = array_get($data, 'seq');
        }
        return $news_detail;
    }





}















