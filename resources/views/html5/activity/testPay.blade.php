<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>美景听听幸运用户</title>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/common.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/aui.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/style.css') }}"/>
    <style type="text/css">

        .yq_div {
            width: 100%;
            height: 100%;
            position: fixed;
            left: 0px;
            top: 0px;
        }

        .mask_div {
            width: 100%;
            height: 100%;
            background-color: #000;
            filter: alpha(opacity=65);
            -moz-opacity: 0.65;
            opacity: 0.65;
            /*position: fixed;*/
            /*left: 0px;*/
            /*top: 0px;*/
        }
    </style>
</head>
<body>
<div class="aui-row">
    <img src="{{ URL::asset('/img/page_img1.png') }}">
</div>
<div class="aui-row">
    <img src="{{ URL::asset('/img/page_img2.png') }}">
</div>
<div class="aui-row aui-margin-t-20">
    <img src="{{ URL::asset('/img/page_img3.png') }}">
</div>
<div class="aui-row aui-margin-t-20">
    <img src="{{ URL::asset('/img/page_img4.png') }}">
</div>
<div style="height: 80px;"></div>
<div class="aui-row aui-margin-t-20" style="position: fixed;bottom: 0px;width: 100%">
    <div class="aui-col-xs-6"><img
                src="{{ URL::asset('/img/left_btn.png') }}">
    </div>
    <div class="aui-col-xs-6" onclick="click_buy_now();"><img src="{{ URL::asset('/img/right_btn.png') }}"></div>
</div>

</body>
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery/1.9.1/jquery.min.js') }}"></script>
{{--common.js--}}
<script type="text/javascript" src="{{ URL::asset('/js/common.js') }}"></script>
<!--2018-02-12-->
<!--TerryQi-->
<!--增加服务号分享功能-->
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">

    //微信配置文件
    wx.config({!! $wxConfig !!});


    //点击78元立柯购买
    function click_buy_now() {
        //微信配置成功后
        wx.ready(function () {
            wx.chooseWXPay({
                timestamp: '{{$wxPay['timeStamp']}}', // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                nonceStr: '{{$wxPay['nonceStr']}}', // 支付签名随机串，不长于 32 位
                package: '{{$wxPay['package']}}', // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=\*\*\*）
                signType: '{{$wxPay['signType']}}', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                paySign: '{{$wxPay['paySign']}}', // 支付签名
                success: function (res) {
// 支付成功后的回调函数
                }
            });
        });
    }

</script>
</html>