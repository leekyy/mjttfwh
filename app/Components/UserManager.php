<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\User;
use App\Models\Vertify;
use Illuminate\Support\Facades\Log;

class UserManager
{

    /*
   * 根据id获取用户信息，带token
   *
   * By TerryQi
   *
   * 2017-09-28
   */
    public static function getByIdWithToken($user_id)
    {
        $user = User::find($user_id);
        return $user;
    }

    /*
     * 根据id获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getById($id)
    {
        $user = User::where('id', '=', $id)->first();
        if ($user) {
            $user->token = null;
            $user->xcx_openid = null;
        }
        return $user;
    }

    /*
     * 根据user_code和token校验合法性，全部插入、更新、删除类操作需要使用中间件
     *
     * By TerryQi
     *
     * 2017-09-14
     *
     * 返回值
     *
     */
    public static function ckeckToken($id, $token)
    {
        //根据id、token获取用户信息
        $count = User::where('id', '=', $id)->where('token', '=', $token)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 用户登录
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function login($data)
    {
        //获取account_type，后续进行登录类型判断
        $account_type = $data['account_type'];
        // 判断小程序，按照类型查询
        if ($account_type === 'xcx') {
            $user = self::getByXCXOpenId($data['xcx_openid']);
            //存在用户即返回用户信息
            if ($user != null) {
                return $user;
            }
        }
        //不存在即新建用户
        return self::register($data);
    }


    /*
     * 增加邀请数
     *
     * By TerryQi
     *
     * 2018-04-02
     */
    public static function addYQNum($user_id)
    {
        $user = UserManager::getByIdWithToken($user_id);
        $user->yq_num = $user->yq_num + 1;
        $user->save();
    }

    /*
     * 配置用户信息，用于更新用户信息和新建用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('nick_name', $data)) {
            $info->nick_name = Utils::removeEmoji(array_get($data, 'nick_name'));
        }
        if (array_key_exists('real_name', $data)) {
            $info->real_name = array_get($data, 'real_name');
        }
        if (array_key_exists('avatar', $data)) {
            $info->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $info->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('xcx_openid', $data)) {
            $info->xcx_openid = array_get($data, 'xcx_openid');
        }
        if (array_key_exists('fwh_openid', $data)) {
            $info->fwh_openid = array_get($data, 'fwh_openid');
        }
        if (array_key_exists('app_openid', $data)) {
            $info->app_openid = array_get($data, 'app_openid');
        }
        if (array_key_exists('unionid', $data)) {
            $info->unionid = array_get($data, 'unionid');
        }
        if (array_key_exists('gender', $data)) {
            $info->gender = array_get($data, 'gender');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        if (array_key_exists('token', $data)) {
            $info->token = array_get($data, 'token');
        }
        if (array_key_exists('province', $data)) {
            $info->province = array_get($data, 'province');
        }
        if (array_key_exists('city', $data)) {
            $info->city = array_get($data, 'city');
        }
        if (array_key_exists('is_subscribe', $data)) {
            $info->is_subscribe = array_get($data, 'is_subscribe');
        }
        if (array_key_exists('yq_code_media_id', $data)) {
            $info->yq_code_media_id = array_get($data, 'yq_code_media_id');
        }
        if (array_key_exists('yq_hb_media_id', $data)) {
            $info->yq_hb_media_id = array_get($data, 'yq_hb_media_id');
        }
        if (array_key_exists('yq_num', $data)) {
            $info->yq_num = array_get($data, 'yq_num');
        }
        if (array_key_exists('target_yq_num', $data)) {
            $info->target_yq_num = array_get($data, 'target_yq_num');
        }
        return $info;
    }

    /*
     * 注册用户
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function register($data)
    {
        //创建用户信息
        $user = new User();
        $user = self::setInfo($user, $data);
        $user->token = self::getGUID();
        $user->save();
        $user = self::getByIdWithToken($user->id);
        return $user;
    }

    /*
     * 更新用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function updateUser($data)
    {
        //配置用户信息
        $user = self::getByIdWithToken($data['user_id']);
        $user = self::setInfo($user, $data);
        $user->save();
        return $user;
    }


    /*
     * 根据用户xcx_openid获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getByXCXOpenid($openid)
    {
        $user = User::where('xcx_openid', '=', $openid)->first();
        return $user;
    }


    /*
     * 根据用户unionid获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getByUnionid($unoinid)
    {
        $user = User::where('unionid', '=', $unoinid)->first();
        return $user;
    }

    /*
     * 根据服务号openid获取用户信息
     *
     * By TerryQi
     *
     * 2018-02-22
     */
    public static function getByFWHOpenid($openid)
    {
        $user = User::where('fwh_openid', '=', $openid)->first();
        return $user;
    }

    /*
    * 服务号注册用户流程
    *
    * By TerryQi
    *
    * 2018-01-17
    */
    public static function registerFWH($data)
    {
        Log::info("registerFWH data:" . json_encode($data));
        $user = null;
        //如果存在unionid，需要协查一下
        if (array_key_exists('unionid', $data)) {
            $unionid = $data['unionid'];
            $user = self::getByUnionid($unionid);
            //如果存在用户，则说明已经关注服务号，只需赋值xcx_openid即可
            if ($user) {
                $user->fwh_openid = $data['openid'];
                $user->save();
            } else {
                //创建用户信息
                $user = new User();
                $user = self::setInfo($user, $data);
                $user->token = self::getGUID();
                $user->save();
            }
        } else {        //不存在unionid，就直接新建用户
            //创建用户信息
            $user = new User();
            $user = self::setInfo($user, $data);
            $user->token = self::getGUID();
            $user->save();
        }
        $user = self::getByIdWithToken($user->id);
        return $user;
    }


    // 生成guid
    /*
     * 生成uuid全部用户相同，uuid即为token
     *
     */
    public static function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));

            $uuid = substr($charid, 0, 8)
                . substr($charid, 8, 4)
                . substr($charid, 12, 4)
                . substr($charid, 16, 4)
                . substr($charid, 20, 12);
            return $uuid;
        }
    }


    /*
   * 生成验证码
   *
   * By TerryQi
   */
    public static function sendVertify($phonenum)
    {
        $vertify_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);  //生成4位验证码
        $vertify = new Vertify();
        $vertify->phonenum = $phonenum;
        $vertify->code = $vertify_code;
        $vertify->save();
        /*
         * 预留，需要触发短信端口进行验证码下发
         */
        if ($vertify) {
            SMSManager::sendSMSVerification($phonenum, $vertify_code);
            return true;
        }
        return false;
    }

    /*
     * 校验验证码
     *
     * By TerryQi
     *
     * 2017-11-28
     */
    public static function judgeVertifyCode($phonenum, $vertify_code)
    {
        $vertify = Vertify::where('phonenum', '=', $phonenum)
            ->where('code', '=', $vertify_code)->where('status', '=', '0')->first();
        if ($vertify) {
            //验证码置为失效
            $vertify->status = '1';
            $vertify->save();
            return true;
        } else {
            return false;
        }
    }

}