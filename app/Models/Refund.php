<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/2/3
 * Time: 12:16
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use SoftDeletes;
    protected $table = 't_refund_info';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}