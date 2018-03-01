<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/25
 * Time: 16:36
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;//使用软删除
    protected $table = 'ysx_news_info';
    public $timestamps = true;
    protected $datas = ['deleted_at'];//软删除
}