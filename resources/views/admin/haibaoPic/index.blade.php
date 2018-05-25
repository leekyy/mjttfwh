@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 海报背景图管理
        <span class="c-gray en">&gt;</span> 海报背景图列表 <a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace(location.href);"
                                                       title="刷新"
                                                       onclick="location.replace('{{URL::asset('/admin/haibaoPic/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    {{--/{{$datas->type_id}}--}}
    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>海报背景：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="hb_pic" name="hb_pic" type="file" class="input-text"
                           value="" placeholder="请选择海报图片"
                           style="width: 400px;">
                </div>
            </div>
            <div class="row cl c-primary">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>特殊说明</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span><span class="c-danger">一定要上传jpg文件格式</span> 要注意海报图片的尺寸，目前海报尺寸为750*1334，目前二维码放置坐标为（47,1100）</span>
                </div>
            </div>
            <div class="row cl mt-20">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 替换海报图
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2"><span class="c-red"></label>
        <div class="formControls col-xs-8 col-sm-9">
            <img src="{{ URL::asset('/img/haibao/').'/'.$haibaoPic }}" style="width: 600px;">
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

        $(function () {

            $("#form-edit").validate({
                rules: {
                    hb_pic: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function (form) {
                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: "{{ URL::asset('/admin/haibaoPic/edit')}}",
                        success: function (ret) {
                            console.log(JSON.stringify(ret));
                            if (ret.result) {
                                layer.msg('保存成功', {icon: 1, time: 1000});
                                setTimeout(function () {
                                    $('.btn-refresh').click();
                                }, 500)
                            } else {
                                layer.msg(ret.message, {icon: 2, time: 1000});
                            }
                        },
                        error: function (XmlHttpRequest, textStatus, errorThrown) {
                            layer.msg('保存失败', {icon: 2, time: 1000});
                            console.log("XmlHttpRequest:" + JSON.stringify(XmlHttpRequest));
                            console.log("textStatus:" + textStatus);
                            console.log("errorThrown:" + errorThrown);
                        }
                    });
                }

            });
        });


    </script>
@endsection