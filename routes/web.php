<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*
 * 管理后台部分
 *
 */

Route::get('/admin/login', 'Admin\LoginController@login');        //登录
Route::post('/admin/login', 'Admin\LoginController@loginPost');   //post登录请求
Route::get('/admin/loginout', 'Admin\LoginController@loginout');  //注销

Route::group(['prefix' => 'admin', 'middleware' => ['admin.login']], function () {

    //首页
    Route::get('/', 'Admin\IndexController@index');       //首页
    Route::get('/index', 'Admin\IndexController@index');  //首页

    //概览页面
    Route::get('/overview/index', 'Admin\OverViewController@index');       //首页

    //错误页面
    Route::get('/error/500', 'Admin\IndexController@error');  //错误页面

    //管理员管理
    Route::get('/admin/index', 'Admin\AdminController@index');  //管理员管理首页
    Route::get('/admin/setStatus/{id}', 'Admin\AdminController@setStatus');  //设置管理员状态
    Route::get('/admin/del/{id}', 'Admin\AdminController@del');  //删除管理员
    Route::get('/admin/edit', 'Admin\AdminController@edit');  //新建或编辑管理员
    Route::post('/admin/edit', 'Admin\AdminController@editPost');  //新建或编辑管理员
    Route::post('/admin/search', 'Admin\AdminController@search');  //搜索管理员
    Route::get('/admin/editMySelf', ['as' => 'editMySelf', 'uses' => 'Admin\AdminController@editMySelf']);  //修改个人资料get
    Route::post('/admin/editMySelf', 'Admin\AdminController@editMySelfPost');  //修改个人资料post

    //用户管理
    Route::get('/user/index', 'Admin\UserController@index');  //用户管理首页

    //菜单管理
    Route::get('/menu/index', 'Admin\WechatController@editMenu');  //菜单管理首页
    Route::get('/menu/set', 'Admin\WechatController@setMenu');  //设置菜单

    // 用户邀请码达标设置
    Route::get('/inviteNum/index', 'Admin\InviteNumController@index');  //用户邀请码达标管理首页
    Route::get('/inviteNum/edit', 'Admin\InviteNumController@edit');  //新建或编辑用户邀请码达标get
    Route::post('/inviteNum/edit', 'Admin\InviteNumController@editPost');  //新建或编辑用户邀请码达标post

    // 用户邀请码记录列表
    Route::get('/inviteCodeRecord/index', 'Admin\InviteCodeRecordController@index');  //用户邀请码记录列表

});


/*
 * H5页面部分
 *
 * By TerryQi
 *
 */

Route::group(['middleware' => ['wechat.oauth']], function () {

    Route::get('/luckUser', 'Html5\LuckUserController@index');        //幸运用户部分
    Route::get('/richBuy', 'Html5\LuckUserController@richBuy');        //点击购买78元
    Route::get('/testPay', 'Html5\LuckUserController@index');        //幸运用户部分-暂时用于测试
    Route::get('/createHaibao', 'Html5\LuckUserController@createHaibao');        //幸运用户部分，提供海报
    Route::get('/buy78', 'Html5\LuckUserController@buy78');        //点击购买78元
    Route::get('/send78InviteCode', 'Html5\LuckUserController@send78InviteCode');       //发送78元邀请码
});

//Route::get('/testPay', 'Html5\LuckUserController@testPay');        //测试支付

Route::get('/MP_verify_2sDZDXfjL7dyX57v.txt', function () {
    return response()->download(realpath(base_path('app')) . '/files/MP_verify_2sDZDXfjL7dyX57v.txt', 'MP_verify_2sDZDXfjL7dyX57v.txt');
});








