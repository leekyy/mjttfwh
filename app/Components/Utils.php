<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/12/4
 * Time: 9:23
 */

namespace App\Components;


class Utils
{
    const PAGE_SIZE = 15;

    const ORDER_WAITFORPAY = "0";   //待支付
    const ORDER_PAYSUCCESS = "1";    //支付成功
    const ORDER_CLOSED = "2";    //已关闭
    const ORDER_REFUNDING = "3";    //退款中
    const ORDER_REFUNDSUCCESS = "4";    //退款成功
    const ORDER_REFUNDFAILED = "5";    //退款失败

    const SERVER_URL = (false == true) ? "https://api.gowithtommy.com" : "http://testapi.gowithtommy.com";        //服务器URL

    //关注+扫描进入回复内容
    const TEXT_SCAN_SUB = "hey，欢迎关注美景听听：全球景点语音讲解\r\n<a href=\"http://mjttfwh.isart.me/luckUser\">点击此处</a>可以获得免费邀请码\r\n\r\n点击“美景”可以看到历史主题原创漫画\r\n点击“听听”可以通过喜马拉雅和小程序听景点讲解\r\n点击“App”可以下载美景听听App";
    //邀请码话术
    const TEXT_INVITE_CODE = "上述为您的邀请码，自使用之日起有效期1个月\r\n\r\n使用流程如下：\r\n1、下载并登录美景听听\r\n2、在【我的】页面，点击【邀请码】，输入上方的【邀请码】后，您的会员等级即可变成【高级会员】。（*如遇到未能解锁的情况，请下拉刷新或重新登录）\r\n4、【高级会员】可以解锁美景听听全部付费音频，畅听100多个国家5万多个景点。\r\n5、会员有效期1个月，如有问题，请联系客服";
    //老用户扫码登录，提示已经助力
    const TEXT_ALREADY_ZHULI = "抱歉，您已经参与过此次活动啦，每人只有一次机会哦";
    //购买78元邀请码话术
    const TEXT_BUY78_CODE = "上述为您的邀请码，自使用之日起有效期1年\r\n\r\n使用流程如下：\r\n1、下载并登录美景听听\r\n2、在【我的】页面，点击【邀请码】，输入上方的【邀请码】后，您的会员等级即可变成【高级会员】。（*如遇到未能解锁的情况，请下拉刷新或重新登录）\r\n4、【高级会员】可以解锁美景听听全部付费音频，畅听100多个国家5万多个景点。\r\n5、会员有效期1年，如有问题，请联系客服";

    /*
     * 判断一个对象是不是空
     *
     * By TerryQi
     *
     * 2017-12-23
     *
     */
    public static function isObjNull($obj)
    {
        if ($obj == null || $obj == "") {
            return true;
        }
        return false;
    }

    /*
     * 生成订单号
     *
     * By TerryQi
     *
     * 2017-01-12
     *
     */
    public static function generateTradeNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);;
    }

    /**
     * @param $url 请求网址
     * @param bool $params 请求参数
     * @param int $ispost 请求方式
     * @param int $https https协议
     * @return bool|mixed
     */
    public static function curl($url, $params = false, $ispost = 0, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
            $postData = http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }


    /*
     * 去除emoj符号
     *
     * By TerryQi
     *
     * 2018-04-08
     */
    public static function removeEmoji($text)
    {
        $clean_text = "";
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);
        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);
        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);
        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);
        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);
        return $clean_text;
    }


    /*
     * 此处用于测试openid到生产openid的转换
     *
     * By TerryQi
     *
     * 2018-04-11
     */
    public static function convertOpenid($test_openid)
    {
        switch ($test_openid) {
            case "oJpZ11DU7GZpoW9W_NB5HwXrlYd8":        //TerryQi生产openid
                return "oIUk2wwZMTe0FggPf_cp0yV1Y6W8";          //测试openid
            case "oIUk2w_1SfcrrM7TxnIn_aC5mzAI":        //解悦生产openid
                return "oJpZ11MMkgneo2h77-bJ1O96i18Y";      //解悦测试openid
            case "oIUk2w7G1b3r_Rbuh3F-cjLScax4":        //Tommy生产openid
                return "oJpZ11Pt-oqMxRp0sPDFMT--SW_0";      //Tommy测试openid
            default:
                return $test_openid;
        }
    }
}