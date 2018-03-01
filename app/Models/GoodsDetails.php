<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/26
 * Time: 20:51
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsDetails extends Model
{
    use SoftDeletes;//使用软删除
    protected $table = 'yxp_goods_details';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}