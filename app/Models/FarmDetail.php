<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/1/30
 * Time: 21:50
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmDetail extends Model
{
    use SoftDeletes;
    protected $table = 'yxp_farm_details';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}