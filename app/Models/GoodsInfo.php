<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/19
 * Time: 13:15
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class GoodsInfo extends Model
{
    use SoftDeletes;    //使用软删除
    protected $table = 't_goods_info';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
//    protected $tables = 't_good_type';

}