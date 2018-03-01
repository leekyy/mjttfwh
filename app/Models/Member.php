<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/2/2
 * Time: 15:46
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;//软删除
    protected $table = 't_member_info';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}