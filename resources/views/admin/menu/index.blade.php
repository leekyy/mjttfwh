@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 菜单管理
        <span class="c-gray en">&gt;</span> 菜单列表 <a class="btn btn-success radius r btn-refresh"
                                                    style="line-height:1.6em;margin-top:3px"
                                                    href="javascript:location.replace(location.href);"
                                                    title="刷新"
                                                    onclick="location.replace('{{URL::asset('/admin/menu/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    {{--/{{$datas->type_id}}--}}
    <div class="page-container">

        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;" onclick="menu_add('添加菜单','{{URL::asset('/admin/menu/edit')}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加菜单
                 </a>
            </span>
            {{--<span class="r">共有数据：<strong>{{$datas->count()}}</strong> 条</span>--}}
        </div>
        <table class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr>
                <th scope="col" colspan="11">菜单列表</th>
            </tr>
            <tr class="text-c">
                <th width="50">ID</th>
                <th width="50">名称</th>
                <th width="50">父级菜单</th>
                <th width="50">类型</th>
                <th width="200">链接</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->id}}</td>
                    <td>{{$data->name}}</td>
                    <td>-</td>
                    <td>{{$data->type?$data->type:'-'}}</td>
                    <td>{{$data->url?$data->url:'-'}}</td>
                    <td>
                        <a title="编辑" href="javascript:;"
                           onclick="menu_edit('菜单编辑','{{URL::asset('/admin/menu/edit')}}?id={{$data->id}})',{{$data->id}})"
                           class="ml-5" style="text-decoration:none">
                            <i class="Hui-iconfont">&#xe6df;</i>
                        </a>
                        <a title="删除" href="javascript:;" onclick="menu_del(this,'{{$data->id}}')" class="ml-5"
                           style="text-decoration:none">
                            <i class="Hui-iconfont">&#xe6e2;</i>
                        </a>
                    </td>
                </tr>
                @foreach($data->children as $child)
                    <tr class="text-c">
                        <td>{{$child->id}}</td>
                        <td>{{$child->name}}</td>
                        <td>{{$data->name}}</td>
                        <td>{{$child->type?$child->type:'-'}}</td>
                        <td>{{$child->url?$child->url:'-'}}</td>
                        <td>
                            <a title="编辑" href="javascript:;"
                               onclick="menu_edit('菜单编辑','{{URL::asset('/admin/menu/edit')}}?id={{$child->id}})',{{$child->id}})"
                               class="ml-5" style="text-decoration:none">
                                <i class="Hui-iconfont">&#xe6df;</i>
                            </a>
                            <a title="删除" href="javascript:;" onclick="menu_del(this,'{{$child->id}}')" class="ml-5"
                               style="text-decoration:none">
                                <i class="Hui-iconfont">&#xe6e2;</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
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
        /*菜单分类-增加*/
        /*菜单-增加*/
        function menu_add(title, url) {
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }
        /*菜单-删除*/
        function menu_del(obj, id) {
            layer.confirm('确认要删除吗？', function (index) {
                //进行后台删除
                var param = {
                    id: id,
                    _token: "{{ csrf_token() }}"
                }
                delMenu('{{URL::asset('')}}', param, function (ret) {
                    if (ret.result == true) {
                        $(obj).parents("tr").remove();
                        layer.msg('已删除', {icon: 1, time: 1000});
                    } else {
                        layer.msg('删除失败', {icon: 2, time: 1000})
                    }
                })
            });
        }

        /*菜单-编辑*/
        function menu_edit(title, url, id) {
            console.log("admin_edit url:" + url);
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }

    </script>
@endsection