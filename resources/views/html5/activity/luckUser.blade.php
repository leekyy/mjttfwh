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
    <div class="aui-col-xs-6" onclick="click_applyFor_luckuser();"><img src="{{ URL::asset('/img/left_btn.png') }}">
    </div>
    <div class="aui-col-xs-6" onclick="click_buy_now();"><img src="{{ URL::asset('/img/right_btn.png') }}"></div>
</div>
</body>
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery/1.9.1/jquery.min.js') }}"></script>
<script type="text/javascript">

    //点击申请成为0元幸运用户
    function click_applyFor_luckuser() {

    }

    //点击78元立柯购买
    function click_buy_now() {

    }

</script>
</html>