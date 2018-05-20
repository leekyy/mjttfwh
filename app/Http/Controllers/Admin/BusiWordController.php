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

class BusiWordController
{

    /*
     * 业务话术首页
     *
     * By Amy
     *
     * 2018-05-18
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        $busiWords = BusiWordManager::getListByCon([], false);

        return view('admin.busiWord.index', ['admin' => $admin, 'datas' => $busiWords]);
    }

    /*
     * 新建或编辑业务话术-get
     *
     * By Amy
     *
     * 2018-05-18
     */
    public function edit(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $busiWord = new BusiWord();
        if (array_key_exists('id', $data)) {
            $busiWord = BusiWordManager::getById($data['id']);
        }
        return view('admin.busiWord.edit', ['data' => $busiWord]);
    }

    /*
     * 新建或编辑业务话术-post
     *
     * By Amy
     *
     * 2018-05-18
     */
    public function editDo(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (array_key_exists("id", $data) && !Utils::isObjNull($data["id"])) {
            $busiWord = BusiWordManager::getById($data['id']);
        } else {
            $busiWord = new BusiWord();
        }
        $busiWord = BusiWordManager::setInfo($busiWord, $data);
        $result = $busiWord->save();
        if ($result) {
            return ApiResponse::makeResponse(true, "保存成功", ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(true, "保存失败", ApiResponse::UNKNOW_ERROR);
        }
    }
}