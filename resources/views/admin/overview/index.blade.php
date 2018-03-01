@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 业务概览
        <a class="btn btn-success radius r"
           style="line-height:1.6em;margin-top:3px"
           href="javascript:location.replace(location.href);" title="刷新">
            <i class="Hui-iconfont">&#xe68f;</i>
        </a>
    </nav>
    <div class="page-container">
        <div class="text-c">
            <input type="text" class="input-text" style="width:450px" placeholder="输入管理员名称" id="" name="">
            <button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜用户
            </button>
        </div>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;" onclick="admin_add('添加管理员','admin-add.html','800','500')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加管理员
                 </a>
            </span>
            <span class="r">共有数据：<strong>{{$datas->count()}}</strong> 条</span>
        </div>
        <table class="table table-border table-bordered table-bg">
            <thead>
            <tr>
                <th scope="col" colspan="9">管理员列表</th>
            </tr>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="40">ID</th>
                <th width="50">头像</th>
                <th width="150">登录名</th>
                <th width="90">手机</th>
                <th width="50">角色</th>
                <th width="130">加入时间</th>
                <th width="100">是否已启用</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td><input type="checkbox" value="1" name=""></td>
                    <td>{{$data->id}}</td>
                    <td>
                        <img src="{{ $data->avatar ? $data->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                             class="img-rect-30 radius-5">
                    </td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->phonenum}}</td>
                    <td>{{$data->role == "0" ? "根级管理员" : "普通管理员"}}</td>
                    <td>{{$data->created_at}}</td>
                    <td class="td-status">
                        @if($data->status=="1")
                            <span class="label label-success radius">已启用</span>
                        @else
                            <span class="label radius">已停用</span>
                        @endif
                    </td>
                    <td class="td-manage">
                        @if($data->status=="1")
                            <a style="text-decoration:none" onClick="admin_stop(this,'{{$data->id}}')"
                               href="javascript:;"
                               title="停用">
                                <i class="Hui-iconfont">&#xe631;</i>
                            </a>
                        @else
                            <a style="text-decoration:none" onClick="admin_start(this,'{{$data->id}}')"
                               href="javascript:;"
                               title="启用">
                                <i class="Hui-iconfont">&#xe615;</i>
                            </a>
                        @endif
                        <a title="编辑" href="javascript:;"
                           onclick="admin_edit('管理员编辑','{{URL::asset('/admin/admin/edit')}}',{{$data->id}})"
                           class="ml-5" style="text-decoration:none">
                            <i class="Hui-iconfont">&#xe6df;</i>
                        </a>
                        <a title="删除" href="javascript:;" onclick="admin_del(this,'{{$data->id}}')"
                           class="ml-5"
                           style="text-decoration:none">
                            <i class="Hui-iconfont">&#xe6e2;</i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(function () {

        });

        /*
         参数解释：
         title	标题
         url		请求的url
         id		需要操作的数据id
         w		弹出层宽度（缺省调默认值）
         h		弹出层高度（缺省调默认值）
         */
        /*管理员-增加*/
        function admin_add(title, url) {
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }
        /*管理员-删除*/
        function admin_del(obj, id) {
            layer.confirm('确认要删除吗？', function (index) {
                //进行后台删除
                var param = {
                    id: id,
                    _token: "{{ csrf_token() }}"
                }
                delAdmin('{{URL::asset('')}}', param, function (ret) {
                    if (ret.status == true) {
                        $(obj).parents("tr").remove();
                        layer.msg('已删除!', {icon: 1, time: 1000});
                    } else {
                        layer.msg('删除失败', {icon: 2, time: 1000})
                    }
                })
            });
        }

        /*管理员-编辑*/
        function admin_edit(title, url, id) {
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }
        /*管理员-停用*/
        function admin_stop(obj, id) {
            console.log("admin_stop id:" + id);
            layer.confirm('确认要停用吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 0,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置管理员状态
                setAdminStatus('{{URL::asset('')}}', param, function (ret) {
                    if (ret.status == true) {

                    }
                })
                $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_start(this,' + id + ')" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
                $(obj).remove();
                layer.msg('已停用', {icon: 5, time: 1000});
            });
        }

        /*管理员-启用*/
        function admin_start(obj, id) {
            layer.confirm('确认要启用吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置管理员状态
                setAdminStatus('{{URL::asset('')}}', param, function (ret) {
                    if (ret.status == true) {

                    }
                })
                $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_stop(this,' + id + ')" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
                $(obj).remove();
                layer.msg('已启用', {icon: 6, time: 1000});
            });
        }

    </script>
@endsection