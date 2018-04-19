<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2018/1/11
 * Time: 9:43
 */

namespace App\Http\Controllers\Admin;


use App\Components\UserManager;
use App\Components\Utils;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use Illuminate\Support\Facades\Log;


class WechatController extends Controller
{


    /*
     * 编辑菜单-页面
     *
     * By TerryQi
     *
     * 2018-03-30
     */
    public function editMenu(Request $request)
    {
        $admin = $request->session()->get('admin');
        return view('admin.menu.index', ['admin' => $admin]);
    }

    /*
     * 编辑菜单
     *
     * By TerryQi
     *
     * 2018-03-30
     */
    public function setMenu(Request $request)
    {
        $buttons = [
            [
                "name" => "美景",
                "type" => "view",
                "url" => "http://mp.weixin.qq.com/mp/homepage?__biz=MzI3NTExNDc4NQ==&hid=1&sn=d1ec8e05d887e0d09b7dbb216750417b&scene=18#wechat_redirect"
            ],
            [
                "name" => "听听",
                "sub_button" => [
                    [
                        "name" => "喜马拉雅",
                        "type" => "view",
                        "url" => "http://www.ximalaya.com/zhubo/25616166/"
                    ],
                    [
                        "name" => "美景小程序",
                        "type" => "view",
                        "url" => "http://mp.weixin.qq.com/s/KRW8l2wZ3jsVc74muI9FUA"
                    ]
                ]
            ],
            [
                "name" => "APP",
                "sub_button" => [
                    [
                        "name" => "免费邀请码",
                        "type" => "view",
                        "url" => Utils::LUCKUSER_URL         //http://mjttfwh.isart.me/luckUser
                    ],
                    [
                        "name" => "下载APP",
                        "type" => "view",
                        "url" => "http://app.gowithtommy.com/"
                    ]
                ]
            ]
        ];
        $app = app('wechat.official_account');
        $app->menu->delete(); // 全部
        $result = $app->menu->create($buttons);       //创建搜索项目
        Log::info("result:" . json_encode($result));
        return ApiResponse::makeResponse(true, "设置成功", ApiResponse::SUCCESS_CODE);
    }

}