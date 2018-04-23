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

        <div class="text-c">
            <form action="{{URL::asset('/admin/inviteCodeRecord/index')}}" method="get" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:250px"
                           placeholder="请输入订单号或邀请码，支持模糊查询"
                           value="{{$con_arr['search_word']==null?'':$con_arr['search_word']}}">
                    <span class="select-box" style="width:150px">
                        <select class="select" name="type" id="type" size="1">
                            <option value="" {{$con_arr['type']==null?'selected':''}}>全部类型</option>
                            <option value="0" {{$con_arr['type']=='0'?'selected':''}}>免费邀请码</option>
                            <option value="1" {{$con_arr['type']=='1'?'selected':''}}>78元邀请码</option>
                        </select>
                    </span>
                    <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索
                    </button>
                </div>
            </form>
        </div>

        <table class="table table-border table-bordered table-bg table-sort mt-20">
            <thead>
            <tr>
                <th scope="col" colspan="11">发送邀请码记录列表</th>
            </tr>
            <tr class="text-c">
                <th width="30">ID</th>
                <th width="100">类型</th>
                <th width="150">邀请码</th>
                <th width="150">订单号</th>
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
                    <td>{{$data->user->nick_name}}</td>
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