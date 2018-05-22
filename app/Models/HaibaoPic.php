<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/18
 * Time: 8:58
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HaibaoPic extends Model
{
    use SoftDeletes;    //使用软删除
    protected $table = 't_haibao_pic';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}