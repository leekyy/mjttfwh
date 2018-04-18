@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span>用户管理
        <span class="c-gray en">&gt;</span>用户列表 <a class="btn btn-success radius r btn-refresh"
                                                   style="line-height:1.6em;margin-top:3px"
                                                   href="javascript:location.replace(location.href);"
                                                   title="刷新"
                                                   onclick="location.replace('{{URL::asset('/admin/user/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    {{--/{{$datas->type_id}}--}}
    <div class="page-container">

        <table class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr>
                <th scope="col" colspan="11">用户列表</th>
            </tr>
            <tr class="text-c">
                <th width="30">ID</th>
                <th width="80">头像</th>
                <th width="100">昵称</th>
                <th width="100">是否关注</th>
                <th width="80">当前邀请数</th>
                <th width="80">目标邀请数</th>
                <th width="120">注册时间</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->id}}</td>
                    <td>
                        <img src="{{ $data->avatar ? $data->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                             class="img-rect-30 radius-5">
                    </td>
                    <td>{{$data->nick_name}}</td>
                    <td>{{$data->is_subscribe=="1"?"已经关注":"已经取消"}}</td>
                    <td>{{$data->yq_num}}</td>
                    <td>{{$data->target_yq_num}}</td>
                    <td>{{$data->created_at}}</td>
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


    </script>
@endsection