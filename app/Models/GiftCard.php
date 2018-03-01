<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/2/2
 * Time: 21:02
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftCard extends Model
{
    use SoftDeletes;
    protected $table = 't_gift_card';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}