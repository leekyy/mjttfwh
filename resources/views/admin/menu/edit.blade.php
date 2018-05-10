@extends('admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            @if($data->id)
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>菜单id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="id" name="id" type="text" class="input-text "
                           value="{{ isset($data->id) ? $data->id : '' }}" placeholder="菜单id"  readonly
                           style="width: 400px;background-color: rgb(235, 235, 228);">
                </div>
            </div>
            @endif
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>菜单名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="name" name="name" type="text" class="input-text"
                           value="{{ isset($data->name) ? $data->name : '' }}" placeholder="请输入菜单名称"
                           style="width: 400px;">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>父级菜单：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    @if($data->id&&$data->f_id==0)
                        <input type="text" class="input-text" value="无" disabled style="width: 400px;">
                    @else
                        <span class="select-box" style="width: 400px;">
                            <select id="f_id" name="f_id" class="select" onchange="changeFId()">
                                @if(!$data->id||$data->f_id==0)
                                    <option value="0" {{$data->f_id==0||!$data->id?'selected':''}} >无</option>
                                @endif
                                @if(!$data->id||$data->f_id!=0)
                                    @foreach($menus as $menu)
                                        @if($menu->level==1)
                                            <option value="{{$menu->id}}"  {{$data->f_id==$menu->id?'selected':''}}>{{$menu->name}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row cl" id="level_show" hidden>
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>是否有下级菜单：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    @if(!$data->id)
                        <span class="select-box" style="width: 400px;">
                            <select id="level" name="level" class="select" onchange="changeLevel()">
                                <option value="0" >否</option>
                                <option value="1" >是</option>
                            </select>
                        </span>
                    @else
                        @if($data->level)
                            <input type="text" class="input-text" value="是" disabled style="width: 400px;">
                        @else
                            <input type="text" class="input-text" value="否" disabled style="width: 400px;">
                        @endif
                    @endif
                </div>
            </div>
            <div class="row cl" id="url_show" hidden>
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>链接：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="url" name="url" type="text" class="input-text"
                           value="{{ isset($data->url) ? $data->url : '' }}" placeholder="请输入链接"
                           style="width: 400px;">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>排序：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="seq" name="seq" type="number" class="input-text"
                           value="{{ isset($data->seq) ? $data->seq : 0 }}" placeholder="请输入排序,越大越靠前"
                           style="width: 400px;">
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
            var id='{{$data['id']}}'
            if(id){
                var f_id='{{$data['f_id']}}'
                if(f_id==0){
                    $('#level_show').show();
                    var level='{{$data['level']}}'
                    if(level==0){
                        $('#url_show').show()
                    }
                    else{
                        $('#url_show').hide()
                        $('#level').val('');
                    }
                }
                else{
                    $('#level_show').hide();
                    $('#level').val('');
                    $('#url_show').show()
                }
            }
            else{
                $('#level_show').show();
                $('#url_show').show()
            }


            $("#form-edit").validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function (form) {
                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: "{{ URL::asset('/admin/menu/edit')}}",
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

        function changeFId(){
            var f_id=$('#f_id').val()
            if(f_id==0){
                $('#level_show').show();
            }
            else{
                $('#level_show').hide();
                $('#level').val('');
                $('#url_show').show()
            }
        }

        function changeLevel(){
            var level=$('#level').val()
            if(level==0){
                $('#url_show').show()
            }
            else{
                $('#url_show').hide()
                $('#url').val('')
            }
        }

    </script>
@endsection

























