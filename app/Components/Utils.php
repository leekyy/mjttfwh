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

    const  DEBUG_FLAG = true;        //debug标识

    const SERVER_URL = (self::DEBUG_FLAG == false) ? "https://api.gowithtommy.com" : "http://testapi.gowithtommy.com";        //服务器URL
    //幸运用户的URL
    const LUCKUSER_URL = (self::DEBUG_FLAG == false) ? "http://wg.gowithtommy.com/luckUser" : "http://mjttfwhtest.isart.me/luckUser";
    const RICHBUY_URL = (self::DEBUG_FLAG == false) ? "http://wg.gowithtommy.com/richBuy" : "http://mjttfwhtest.isart.me/richBuy";
    //默认的邀请数配置
    const DEFAULT_YQ_NUM = 3;

    /*
     * 以下业务话术配置作废，通过数据库业务话术表来统一管理业务话术
     *
     * By TerryQi
     *
     * 2018-05-19
     */
    //关注+扫描进入回复内容
    const TEXT_SCAN_SUB = "hey，欢迎关注美景听听：全球景点语音讲解\r\n<a href=\"" . Utils::LUCKUSER_URL . "\">点击此处</a>可以获得免费邀请码\r\n\r\n点击“美景”可以看到历史主题原创漫画\r\n点击“听听”可以通过喜马拉雅和小程序听景点讲解\r\n点击“App”可以下载美景听听App";
    //邀请码话术
    const TEXT_INVITE_CODE = "上述为您的邀请码，自使用之日起有效期1个月\r\n\r\n使用流程如下：\r\n1、下载并登录美景听听\r\n2、在【我的】页面，点击【邀请码】，输入上方的【邀请码】后，您的会员等级即可变成【高级会员】。（*如遇到未能解锁的情况，请下拉刷新或重新登录）\r\n4、【高级会员】可以解锁美景听听全部付费音频，畅听100多个国家5万多个景点。\r\n5、会员有效期1个月，如有问题，请联系客服";
    //老用户扫码登录，提示已经助力
    const TEXT_ALREADY_ZHULI = "抱歉，您已经参与过此次活动啦，每人只有一次机会哦";
    //购买78元邀请码话术
    const TEXT_BUY78_CODE = "上述为您的邀请码，自使用之日起有效期1年\r\n\r\n使用流程如下：\r\n1、下载并登录美景听听\r\n2、在【我的】页面，点击【邀请码】，输入上方的【邀请码】后，您的会员等级即可变成【高级会员】。（*如遇到未能解锁的情况，请下拉刷新或重新登录）\r\n4、【高级会员】可以解锁美景听听全部付费音频，畅听100多个国家5万多个景点。\r\n5、会员有效期1年，如有问题，请联系客服";
    //土豪购买的说明
    const RICH_BUY_TEXT = "只需完成以下2步，即可获得邀请码，免费解锁全部景点！\r\n\r\n1.长按保存以下图片分享给好友/朋友圈\r\n2.邀请yq_num_txt位好友扫码并关注美景听听旅行\r\n\r\n（请24小时内完成此任务，逾期活动作废）\r\n\r\n<a href=\"" . Utils::RICHBUY_URL . "\">土豪请戳此购买</a>";
    //扫描自己分享的二维码，返回空串
    const TEXT_SCAN_SELF = "";
    //邀请扫描的用户已经关注过服务号
    const TEXT_CANNOT_ZHULI = "抱歉您不是新用户，无法助力好友";
    //空串
    const TEXT_NULL_STR = "";
    //关键字回复话术
    const KEYWORD_REPLY_TEXT = "<a href=\"" . Utils::LUCKUSER_URL . "\">点击此处</a>可以获得免费邀请码\r\n\r\n点击“美景”可以看到历史主题原创漫画\r\n点击“听听”可以通过喜马拉雅和小程序听景点讲解\r\n点击“App”可以下载美景听听App";

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
        $value = json_encode($text);
        $value = preg_replace("/\\\u[ed][0-9a-f]{3}\\\u[ed][0-9a-f]{3}/", "*", $value);//替换成*
        $value = json_decode($value);
        return $value;
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
        //非测试环境，不需要进行openid的映射
        if (self::DEBUG_FLAG == false) {
            return $test_openid;
        }

        switch ($test_openid) {
            case "oJpZ11DU7GZpoW9W_NB5HwXrlYd8":        //TerryQi测试openid
                return "oIUk2wwZMTe0FggPf_cp0yV1Y6W8";          //TerryQi生产openid
            case "oJpZ11MMkgneo2h77-bJ1O96i18Y":        //解悦测试openid
                return "oIUk2w_1SfcrrM7TxnIn_aC5mzAI";      //解悦生产openid
            case "oJpZ11Pt-oqMxRp0sPDFMT--SW_0":        //Tommy测试openid
                return "oIUk2w7G1b3r_Rbuh3F-cjLScax4";      //Tommy生产openid
            case "oJpZ11IIT0fKncFdoNj89oxXIUss":        //GUoErPi测试openid
                return "oIUk2w6SjIvnUq2_FPQtroK9ovy0";  //GUoErPi生产openid
            default:
                return $test_openid;
        }
    }
}