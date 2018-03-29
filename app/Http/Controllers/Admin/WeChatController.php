<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2018/1/11
 * Time: 9:43
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;


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
        $button = [
            [
                "name" => "美景",
                "type" => "view",
                "url" => "http=>//mp.weixin.qq.com/mp/homepage?__biz=MzI3NTExNDc4NQ==&hid=1&sn=d1ec8e05d887e0d09b7dbb216750417b&scene=18#wechat_redirect"
            ],
            [
                "name" => "听听",
                "sub_button" => [
                    [
                        "name" => "喜马拉雅",
                        "type" => "view",
                        "url" => "http=>//www.ximalaya.com/zhubo/25616166/"
                    ],
                    [
                        "name" => "美景小程序",
                        "type" => "media_id",
                        "media_id" => "Ym2MVoS36cImkadjaZrPIAggY8p7k4CisaSpUPodJwQ"
                    ]
                ]
            ],
            [
                "name" => "APP",
                "sub_button" => [
                    [
                        "name" => "联系我们",
                        "type" => "click",
                        "key" => "text_微信=>3011740452"
                    ],
                    [
                        "name" => "幸运用户",
                        "type" => "view",
                        "url" => "http=>//wechat.gowithtommy.com/activityAuth/"
                    ],
                    [
                        "name" => "APP",
                        "type" => "view",
                        "url" => "http=>//app.gowithtommy.com/"
                    ]
                ]
            ]
        ];

        return ApiResponse::makeResponse(true, "设置成功", ApiResponse::SUCCESS_CODE);
    }
}