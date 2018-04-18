@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 发送邀请码记录管理
        <span class="c-gray en">&gt;</span> 发送邀请码记录列表 <a class="btn btn-success radius r btn-refresh"
                                                         style="line-height:1.6em;margin-top:3px"
                                                         href="javascript:location.replace(location.href);"
                                                         title="刷新"
                                                         onclick="location.replace('{{URL::asset('/admin/inviteCodeRecord/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    {{--/{{$datas->type_id}}--}}
    <div class="page-container">

        <table class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr>
                <th scope="col" colspan="11">发送邀请码记录列表</th>
            </tr>
            <tr class="text-c">
                <th width="30">ID</th>
                <th width="100">类型</th>
                <th width="150">邀请码</th>
                <th width="150">订单号</th>
                <th width="150">订单状态</th>
                <th width="150">用户名</th>
                <th width="100">发送时间</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->id}}</td>
                    <td>{{$data->type == 0?'免费':'78元购买'}}</td>
                    <td>{{$data->invite_code}}</td>
                    <td>{{$data->out_trade_no == null?'--':$data->out_trade_no}}</td>
                    <td>{{$data->user->name}}({{$data->user->id}})</td>
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