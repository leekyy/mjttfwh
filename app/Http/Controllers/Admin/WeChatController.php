<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2018/1/11
 * Time: 9:43
 */

namespace App\Http\Controllers\Admin;


use App\Components\MenuManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\WeChatManager;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Hamcrest\Util;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use Illuminate\Support\Facades\Log;


class WechatController extends Controller
{
    /*
     * 菜单列表
     *
     * By Amy
     *
     * 2018-05-10
     */
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $datas = MenuManager::getListByCon();
        return view('admin.menu.index', ['admin' => $admin, 'datas' => $datas]);
    }

    /*
     * 删除菜单
     *
     * By Amy
     *
     * 2018-05-10
     */
    public function del(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数管理员id$id']);
        }
        $menu = MenuManager::getById($id);
        $result = $menu->delete();
        if ($result) {
            self::setMenu();
            return ApiResponse::makeResponse(true, "删除成功", ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, "删除失败", ApiResponse::UNKNOW_ERROR);
        }
    }

    /*
     * 新建或编辑菜单-get
     *
     * By Amy
     *
     * 2018-05-10
     */
    public function edit(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $menu = new Menu();
        if (array_key_exists('id', $data)) {
            $menu = MenuManager::getById($data['id']);
        }
        $menu_f = MenuManager::getListByCon();
        return view('admin.menu.edit', ['data' => $menu, 'menus' => $menu_f]);
    }

    /*
     * 新建或编辑菜单-post
     *
     * By Amy
     *
     * 2018-05-10
     */
    public function editDo(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $image_media_id = null;     //上传图片后的素材id
        //处理图片类菜单
        if ($data['type'] == "media_id") {
            $file_name = Utils::generateTradeNo() . ".jpg";
            if (array_key_exists("image_file", $data) && !Utils::isObjNull($data["image_file"])) {
                $path = $request->file('image_file')->storeAs(
                    'menu', $file_name
                );
                $image_media_id = WeChatManager::createForeverMediaId('img/menu/', $file_name);
                Log::info("image_media_id:" . $image_media_id);
            }
        }

        if (array_key_exists("id", $data) && !Utils::isObjNull($data["id"])) {
            $menu = MenuManager::getById($data['id']);
        } else {
            $menu = new Menu();
        }
        //处理type
        if (array_key_exists("type", $data) && !Utils::isObjNull($data["type"])) {
            if ($data['type'] == "media_id") {
                if (!Utils::isObjNull($image_media_id)) {       //新上传了素材
                    $data['url'] = $image_media_id;
                }
            }
        } else {
            $data['type'] = 'view';
        }
        $menu = MenuManager::setInfo($menu, $data);
        $result = $menu->save();
        if ($result) {
            $reuslt = self::setMenu();
            return ApiResponse::makeResponse(true, "保存成功", ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(true, "保存失败", ApiResponse::UNKNOW_ERROR);
        }
    }

    /*
     * 编辑菜单
     *
     * By TerryQi
     *
     * 2018-03-30
     */
    public function setMenu()
    {
        $menus = MenuManager::getListByCon();
        if ($menus) {
            foreach ($menus as $k => $menu) {
                $buttons[$k]['name'] = $menu['name'];
                if ($menu['level'] == 0) {
                    if ($menu['type'] == "view") {
                        $buttons[$k]['type'] = $menu['type'];
                        $buttons[$k]['url'] = $menu['url'];
                    }
                    /*
                     * By TerryQi 2018-05-22
                     *
                     * 增加图片类型菜单
                     */
                    if ($menu['type'] == "media_id") {
                        $buttons[$k]['type'] = 'media_id';
                        $buttons[$k]['media_id'] = $menu['url'];
                    }
                } else {
                    unset($buttons[$k]['type']);
                    unset($buttons[$k]['url']);
                    $buttons[$k]['sub_button'] = array();
                    foreach ($menu['children'] as $v => $child) {
                        $buttons[$k]['sub_button'][$v]['name'] = $child['name'];
                        /*
                         * By TerryQi 2018-05-22
                         *
                         * 增加图片类型菜单
                         */
                        if ($child['type'] == "view") {
                            $buttons[$k]['sub_button'][$v]['type'] = $child['type'];
                            $buttons[$k]['sub_button'][$v]['url'] = $child['url'];
                        }
                        if ($child['type'] == "media_id") {
                            $buttons[$k]['sub_button'][$v]['type'] = $child['type'];
                            $buttons[$k]['sub_button'][$v]['media_id'] = $child['url'];
                        }
                    }
                }
            }
        } else {
            $buttons = array();
        }
        Log::info("buttons:" . json_encode($buttons));
        $app = app('wechat.official_account');
        $app->menu->delete(); // 全部
        $result = $app->menu->create($buttons);       //创建搜索项目
        Log::info("result:" . json_encode($result));
    }

}