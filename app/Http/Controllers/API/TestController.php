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

    //图片合并
    public function testMergePic(Request $request)
    {
        $path_1 = public_path('img/') . 'fxhb_bg.jpg';
        $path_2 = public_path('img/') . 'user18_yq_code.jpg';
        $image_1 = imagecreatefromjpeg($path_1);
        $image_2 = imagecreatefromjpeg($path_2);
        list($width, $height) = getimagesize($path_2);
        //生成缩略图 二维码 200*200
        $ewm_width = 200;
        $ewm_height = 200;
        $image_2_resize = imagecreatetruecolor($ewm_width, $ewm_height);
        imagecopyresized($image_2_resize, $image_2, 0, 0, 0, 0, $ewm_width, $ewm_height, $width, $height);

        $image_3 = imageCreatetruecolor(imagesx($image_1), imagesy($image_1));
        $color = imagecolorallocate($image_3, 255, 255, 255);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);
        imagecopyresampled($image_3, $image_1, 0, 0, 0, 0, imagesx($image_1), imagesy($image_1), imagesx($image_1), imagesy($image_1));
        imagecopymerge($image_3, $image_2_resize, 45, 1100, 0, 0, imagesx($image_2_resize), imagesy($image_2_resize), 100);
        imagejpeg($image_3, public_path('img/') . 'merge.jpg');

        return ApiResponse::makeResponse(true, public_path('img/') . 'merge.jpg', ApiResponse::SUCCESS_CODE);
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