<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: InviteNumistrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\AdminManager;
use App\Components\BusiWordManager;
use App\Components\InviteNumManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserTJManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\InviteCodeRecord;
use App\Models\InviteNum;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;


class UserController
{

    //首页
    public function index(Request $request)
    {
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
        $users = UserManager::getListByCon($con_arr, true);
        return view('admin.user.index', ['admin' => $admin, 'datas' => $users, 'con_arr' => $con_arr]);
    }

    /*
     * 获取用户信息页面
     *
     * By TerryQi
     *
     * 2018-04-18
     */
    public function info(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $user = UserManager::getById($data['user_id']);
        $userTJs = UserTJManager::getListByCon(["tj_user_id" => $data['user_id']], false);
        foreach ($userTJs as $userTJ) {
            $userTJ = UserTJManager::getInfoByLevel($userTJ, "");
        }
        return view('admin.user.info', ['admin' => $admin, 'user' => $user, 'userTJs' => $userTJs]);
    }

    /*
     * 获取用户信息页面
     *
     * By TerryQi
     *
     * 2018-04-18
     */
    public function createInviteCode(Request $request)
    {
        $data = $request->all();
        $user = UserManager::getById($data['user_id']);
        $param = array(
            'openId' => $user->fwh_openid,
            'sign' => md5(base64_encode("openId|" . $user->fwh_openid . "|Free|Edition"))
        );
        $result = Utils::curl(Utils::SERVER_URL . '/rest/user/public_number/invi_code/', $param, true);   //访问接口
        Log::info("invi_code:" . json_encode($result));
        $result = json_decode($result, true);   //因为返回的已经是json数据，为了适配makeResponse方法，所以进行json转数组操作
        if ($result['code'] == '0') {
            $app = app('wechat.official_account');
            $inviCode = $result['data']['inviCode'];    //邀请码
            $text1 = $inviCode;
            $app->customer_service->message($text1)
                ->to($user->fwh_openid)
                ->send();
            $text2 = BusiWordManager::getByTemplateID("TEMPLATED_FREE_INVITECODE");
            $app->customer_service->message($text2)
                ->to($user->fwh_openid)
                ->send();
            //记录邀请码发送信息
            $inviteCodeRecord = new InviteCodeRecord();
            $inviteCodeRecord->user_id = $user->id;
            $inviteCodeRecord->invite_code = $inviCode;
            $inviteCodeRecord->type = '0';
            $inviteCodeRecord->save();
            return ApiResponse::makeResponse(true, $result, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, $result, ApiResponse::INNER_ERROR);
        }

    }
}

















