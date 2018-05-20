@extends('admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            @if($data->id)
                <div class="row cl">
                    <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>业务话术id：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input id="id" name="id" type="text" class="input-text "
                               value="{{ isset($data->id) ? $data->id : '' }}" placeholder="业务话术id" readonly
                               style="width: 400px;background-color: rgb(235, 235, 228);">
                    </div>
                </div>
            @endif
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>模板ID：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="template_id" name="template_id" type="text" class="input-text"
                           value="{{ isset($data->template_id) ? $data->template_id : '' }}" placeholder="模板管理由开发定义"
                           style="width: 400px;">
                </div>

            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>业务场景：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="name" name="name" type="text" class="input-text"
                           value="{{ isset($data->name) ? $data->name : '' }}" placeholder="业务场景"
                           style="width: 400px;">
                    该字段用于描述话术使用场景
                </div>

            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>回复内容：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea id="content" name="content" wrap="\r\n" class="textarea"
                              style="resize:vertical;width: 400px;height:300px;" placeholder="请填写回复内容" dragonfly="true"
                              nullmsg="业务话术">{{ isset($data['content']) ? $data['content'] : '' }}</textarea>
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
                        url: "{{ URL::asset('/admin/busiWord/edit')}}",
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

























