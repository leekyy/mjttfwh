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
use App\Http\Controllers\Controller;
use App\Models\Menu;
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
        $datas=MenuManager::getListByCon();
        return view('admin.menu.index', ['admin' => $admin,'datas'=>$datas]);
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
        $result=$menu->delete();
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
        $menu_f=MenuManager::getListByCon();
        return view('admin.menu.edit', [ 'data' => $menu,'menus'=>$menu_f]);
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
        if (array_key_exists("id", $data) && !Utils::isObjNull($data["id"])) {
            $menu = MenuManager::getById($data['id']);
        }
        else{
            $menu = new Menu();
        }
        $data['type']='view';
        $menu = MenuManager::setInfo($menu, $data);
        $result=$menu->save();
        if($result){
            self::setMenu();
            return ApiResponse::makeResponse(true, "保存成功", ApiResponse::SUCCESS_CODE);
        }
        else{
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
    public function setMenu(Request $request)
    {
        $menus=MenuManager::getListByCon();
        $buttons=array();
        $menus=MenuManager::getListByCon();
        $buttons=array();
        foreach ($menus as $menu){
            $button['name']=$menu['name'];
            if($menu['level']==0){
                $button['type']=$menu['type'];
                $button['url']=$menu['url'];
            }
            else{
                unset($button['type']);
                unset($button['url']);
                $button['sub_button']=array();
                foreach ($menu['children'] as $child){
                    $sub_button['name']=$child['name'];
                    $sub_button['type']=$child['type'];
                    $sub_button['url']=$child['url'];
                    array_push($button['sub_button'],$sub_button);
                }
            }
            array_push($buttons,$button);
        }
        Log::info("buttons:" . json_encode($buttons));
        $app = app('wechat.official_account');
        $app->menu->delete(); // 全部
        $result = $app->menu->create($buttons);       //创建搜索项目
        Log::info("result:" . json_encode($result));
    }

}