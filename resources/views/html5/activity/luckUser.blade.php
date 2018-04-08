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
    <div class="aui-col-xs-6" onclick="click_create_haibao();"><img src="{{ URL::asset('/img/left_btn.png') }}">
    </div>
    <div class="aui-col-xs-6" onclick="click_buy_now();"><img src="{{ URL::asset('/img/right_btn.png') }}"></div>
</div>

<!--邀请二维码弹出层-->
<div id="yq_code_div" class="yq_div aui-hide">
    <!--遮罩层-->
    <div class="mask_div"></div>
    <!--邀请码部分-->
    <div style="position: absolute;top: 80px;width: 100%;">
        <div class="">
            <img src="{{$user->avatar}}"
                 style="width: 60px;height: 60px;border-radius: 50%;margin: auto;">
        </div>
        <div style="width: 70%;margin: auto;height: 300px;background: white;border-radius: 10px;margin-top: -30px;">
            <div>
                <img src="{{ URL::asset('/img') }}/user{{$user->id}}_yq_code.jpg"
                     style="width: 90%;margin: auto;padding-top: 30px;">
            </div>
            <div class="aui-text-center" style="color: #666666;">我的邀请码</div>
        </div>
        <div style="margin-top: 20px;" onclick="click_hide_yqm();">
            <img src="{{ URL::asset('/img/close_btn.png') }}"
                 style="width: 40px;height: 40px;border-radius: 50%;margin: auto;">
        </div>
    </div>

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
    wx.config({!! $wx_config !!});

    //微信配置成功后
    wx.ready(function () {
        /*
         * 进行页面分享-朋友圈
         *
         * By TerryQi
         *
         */
        wx.onMenuShareTimeline({
            title: "美景听听幸运用户", // 分享标题
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://dsyy.isart.me/app.png', // 分享图标
            success: function (ret) {
                // 用户确认分享后执行的回调函数
                console.log("success ret:" + JSON.stringify(ret))
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

        wx.onMenuShareAppMessage({
            title: "美景听听幸运用户", // 分享标题
            desc: '美景听听中文语音导游，让旅行更有内涵', // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://dsyy.isart.me/app.png', // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });

    //点击申请成为0元幸运用户-展示邀请码
    function click_show_yqm() {
        console.log("click_show_yqm");
        $("#yq_code_div").removeClass('aui-hide');
    }

    //点击生成海报
    function click_create_haibao() {
        console.log("click_create_haibao");
        var param = {};
        createHaibao('{{URL::asset('')}}', param, function (ret) {
            if (ret.result == true) {
                alert("已经生成推广海报，请在服务号中查看");
            } else {
                alert("报错了");
            }
        })

    }


    //关闭展示邀请码页面
    function click_hide_yqm() {
        console.log("click_hide_yqm");
        $("#yq_code_div").addClass('aui-hide');
    }

    //点击78元立柯购买
    function click_buy_now() {

    }

</script>
</html>