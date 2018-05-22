<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/18
 * Time: 9:11
 */

namespace App\Http\Controllers\Admin;

use App\Components\BusiWordManager;
use App\Components\HaibaoPicManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\BusiWord;
use App\Models\HaibaoPic;
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
        //最新海报
        $filename = HaibaoPicManager::getLatestHaibaoPic();
        return view('admin.haibaoPic.index', ['admin' => $admin, 'haibaoPic' => $filename]);
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
        $admin = $request->session()->get('admin');
        $file_name = Utils::generateTradeNo() . ".jpg";
        $path = $request->file('hb_pic')->storeAs(
            'haibao', $file_name
        );
        $haibaoPic = new HaibaoPic();
        $haibaoPic->admin_id = $admin->id;
        $haibaoPic->name = $file_name;
        $haibaoPic->save();
        return ApiResponse::makeResponse(true, "保存成功", ApiResponse::SUCCESS_CODE);
    }

}