<?php
/**
 * File_Name:TestController.php
 * Author: leek
 * Date: 2017/9/26
 * Time: 11:19
 */

namespace App\Http\Controllers\API;

use App\Components\MapManager;
use App\Components\TestManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\RequestValidator;


class TestController extends Controller
{
    public function test(Request $request)
    {
        //此处封装参数，参数应该来自请求，此处仅为示例
        $param = array(
            'openid' => 'oJpZ11DU7GZpoW9W_NB5HwXrlYd8',       //项目pro_code应该统一管理，建议在Utils中定义一个通用变量
        );
        $result = Utils::curl('http://testapi.gowithtommy.com/rest/pay/js_pre_order/', $param, true);   //访问接口
        $result = json_decode($result, true);   //因为返回的已经是json数据，为了适配makeResponse方法，所以进行json转数组操作

        return ApiResponse::makeResponse(true, $result, ApiResponse::SUCCESS_CODE);
    }


    public function testYQM(Request $request)
    {
        //此处封装参数，参数应该来自请求，此处仅为示例
        $param = array();
        $result = Utils::curl('http://testapi.gowithtommy.com/rest/user/public_number/invi_code/', $param, false);   //访问接口
        $result = json_decode($result, true);   //因为返回的已经是json数据，为了适配makeResponse方法，所以进行json转数组操作
        return ApiResponse::makeResponse(true, $result, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据经纬度获取地址信息
     *
     * By TerryQi
     *
     * 2018-01-09
     *
     */
    public function getLocation(Request $request)
    {
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'lat' => 'required',
            'lon' => 'required',
        ]);
        $data = $request->all();
        $result = MapManager::getLocationByLatLon($data['lat'], $data['lon']);
        return ApiResponse::makeResponse(true, $result, ApiResponse::SUCCESS_CODE);
    }
}