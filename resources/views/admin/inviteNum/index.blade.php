@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户达标邀请数配置管理
        <span class="c-gray en">&gt;</span> 用户达标邀请数配置列表 <a class="btn btn-success radius r btn-refresh"
                                                           style="line-height:1.6em;margin-top:3px"
                                                           href="javascript:location.replace(location.href);"
                                                           title="刷新"
                                                           onclick="location.replace('{{URL::asset('/admin/inviteNum/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    {{--/{{$datas->type_id}}--}}
    <div class="page-container">

        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;" onclick="inviteNum_add('添加用户达标邀请数配置','{{URL::asset('/admin/inviteNum/edit')}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加用户达标邀请数配置
                 </a>
            </span>
            {{--<span class="r">共有数据：<strong>{{$datas->count()}}</strong> 条</span>--}}
        </div>
        <table class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr>
                <th scope="col" colspan="11">用户达标邀请数配置列表</th>
            </tr>
            <tr class="text-c">
                <th width="30">ID</th>
                <th width="150">配置邀请数值</th>
                <th width="150">配置时间</th>
                <th width="100">配置人</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->id}}</td>
                    <td>{{$data->yq_num}}</td>
                    <td>{{$data->created_at}}</td>
                    <td>{{$data->admin->name}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="margin-top-10">
            {{ $datas->links() }}
        </div>
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
        /*用户达标邀请数配置分类-增加*/
        function inviteNum_add(title, url) {
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }

    </script>
@endsection