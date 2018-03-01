<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/18
 * Time: 18:27
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class GoodType extends Model
{
    use SoftDeletes;    //使用软删除
    protected $table = 't_good_type';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}