<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/28
 * Time: 16:11
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class NewsDetails extends Model
{
    use SoftDeletes;//使用软删除
    protected $table = 'yxp_news_details';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}