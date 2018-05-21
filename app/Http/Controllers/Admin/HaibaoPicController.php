<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/18
 * Time: 9:11
 */

namespace App\Http\Controllers\Admin;

use App\Components\BusiWordManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\BusiWord;
use Illuminate\Http\Request;

class HaibaoPicController
{

    /*
     * 海报图片管理首页
     *
     * 2018-05-18
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        return view('admin.haibaoPic.index', ['admin' => $admin]);
    }

    /*
     * 上传图片管理
     *
     * 2018-05-21
     *
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $path = $request->file('hb_pic')->storeAs(
            'haibao', 'fxhb_bg.jpg'
        );
    }

}