<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/18
 * Time: 9:11
 */

namespace App\Http\Controllers\Admin;

use App\Components\ReplyManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\Reply;
use Illuminate\Http\Request;

class ReplyController
{

    /*
     * 自动回复首页
     *
     * By Amy
     *
     * 2018-05-18
     */
    public function index(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //相关搜素条件
        $search_word = null;    //搜索条件
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'search_word' => $search_word,
        );
        $replies=ReplyManager::getListByCon($con_arr,false);

        return view('admin.reply.index', ['admin' => $admin, 'datas' => $replies, 'con_arr' => $con_arr]);
    }

    /*
     * 删除自动回复
     *
     * By Amy
     *
     * 2018-05-18
     */
    public function del(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数管理员id$id']);
        }
        $reply = ReplyManager::getById($id);
        $result=$reply->delete();
        if ($result) {
            return ApiResponse::makeResponse(true, "删除成功", ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, "删除失败", ApiResponse::UNKNOW_ERROR);
        }
    }

    /*
     * 新建或编辑自动回复-get
     *
     * By Amy
     *
     * 2018-05-18
     */
    public function edit(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $reply = new Reply();
        if (array_key_exists('id', $data)) {
            $reply = ReplyManager::getById($data['id']);
        }
        return view('admin.reply.edit', [ 'data' => $reply]);
    }

    /*
     * 新建或编辑自动回复-post
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
            $reply = ReplyManager::getById($data['id']);
        }
        else{
            $reply = new Reply();
        }
        $reply = ReplyManager::setInfo($reply, $data);
        $result=$reply->save();
        if($result){
            return ApiResponse::makeResponse(true, "保存成功", ApiResponse::SUCCESS_CODE);
        }
        else{
            return ApiResponse::makeResponse(true, "保存失败", ApiResponse::UNKNOW_ERROR);
        }
    }
}