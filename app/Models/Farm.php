<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/30
 * Time: 21:05
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use SoftDeletes;
    protected $table = 't_farm_info';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}