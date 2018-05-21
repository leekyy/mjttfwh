@extends('admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            @if($data->id)
                <div class="row cl">
                    <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>自动回复id：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input id="id" name="id" type="text" class="input-text "
                               value="{{ isset($data->id) ? $data->id : '' }}" placeholder="自动回复id" readonly
                               style="width: 400px;background-color: rgb(235, 235, 228);">
                    </div>
                </div>
            @endif
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>关键字：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="keyword" name="keyword" type="text" class="input-text"
                           value="{{ isset($data->keyword) ? $data->keyword : '' }}" placeholder="请输入关键字"
                           style="width: 400px;">
                    多个关键字以“_”分割
                </div>

            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>回复内容：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea id="content" name="content" wrap="\r\n" class="textarea"
                              style="resize:vertical;width: 400px;height:300px;" placeholder="请填写回复内容" dragonfly="true"
                              nullmsg="回复内容！">{{ isset($data['content']) ? $data['content'] : '' }}</textarea>
                    换行的时候直接按“回车键”就可以
                </div>
            </div>
            <div class="row cl c-primary">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>特殊说明</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span>如果加入的是超链接的标签，请参考目前的邀请码_幸运用户_申请幸运用户_免费关键词，a标签要使用"，此处需要注意</span>
                </div>
            </div>
            <div class="row cl mt-20">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存
                    </button>
                    <button onClick="layer_close();" class="btn btn-default radius" type="button">取消</button>
                </div>
            </div>
        </form>
    </div>

    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2"><span class="c-red"></label>
        <div class="formControls col-xs-8 col-sm-9">
            <div class="mt-5">
                <div class="c-red">生产环境相下：幸运用户链接-http://wg.gowithtommy.com/luckUser
                    土豪购买链接-http://wg.gowithtommy.com/richBuy
                </div>
                <div class="mt-5">
                    <div class="c-red">测试环境相下：幸运用户链接-http://mjttfwhtest.isart.me/luckUser
                        土豪购买链接-http://mjttfwhtest.isart.me/richBuy
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(function () {

            $("#form-edit").validate({
                rules: {
                    keyword: {
                        required: true,
                    },
                    content: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function (form) {
                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: "{{ URL::asset('/admin/reply/edit')}}",
                        success: function (ret) {
                            console.log(JSON.stringify(ret));
                            if (ret.result) {
                                layer.msg('保存成功', {icon: 1, time: 1000});
                                setTimeout(function () {
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.$('.btn-refresh').click();
                                    parent.layer.close(index);
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

























