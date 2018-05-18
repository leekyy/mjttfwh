<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9
 * Time: 17:25
 */

namespace App\Components;


use App\Models\Menu;

class MenuManager
{
    /*
     * 根据id获取信息
     *
     * By Amy
     *
     * 2018-05-09
     */
    public static function getById($id)
    {
        $menu = Menu::where('id', '=', $id)->first();
        return $menu;
    }


    /*
     * 设置菜单信息，用于编辑
     *
     * By Amy
     *
     * 2018-05-09
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('level', $data)) {
            $info->level = array_get($data, 'level');
        }
        if (array_key_exists('f_id', $data)) {
            $info->f_id = array_get($data, 'f_id');
        }
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('style', $data)) {
            $info->style = array_get($data, 'style');
        }
        if (array_key_exists('url', $data)) {
            $info->url = array_get($data, 'url');
        }
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('seq', $data)) {
            $info->seq = array_get($data, 'seq');
        }
        return $info;
    }

    /*
     * 获取菜单列表
     *
     * By Amy
     *
     * 2018-05-10
     */
    public static function getListByCon(){
        $menus=Menu::where('f_id',0)->orderBy('seq','desc')->get();
        foreach ($menus as $menu){
            $f_id=$menu['id'];
            $menu['children']=Menu::where('f_id',$f_id)->orderBy('seq','desc')->get();
        }
        return $menus;
    }
}