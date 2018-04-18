@extends('admin.layouts.app')

@section('content')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户详情管理 <span
                class="c-gray en">&gt;</span> 加盟详情 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace(location.href);" title="刷新"
                                                      onclick="location.replace('{{URL::asset('/admin/user/info')}}?user_id={{$user->id}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">

        <div class="panel panel-primary">
            <div class="panel-header">用户信息</div>
            <div class="panel-body">

                <table class="table table-border table-bordered radius">
                    <tbody>
                    <tr>
                        <td rowspan="3" style="text-align: center;">
                            <img src="{{ $user->avatar ? $user->avatar : URL::asset('/img/default_headicon.png')}}"
                                 style="width: 80px;height: 80px;">
                        </td>
                        <td>昵称</td>
                        <td>{{isset($user->nick_name)?$user->nick_name:'--'}}</td>
                        <td>是否关注</td>
                        <td>{{$user->status=='0'?'取消关注':'已经关注'}}</td>

                        <td>性别</td>
                        <td>
                            @if($user->gender=='0')
                                保密
                            @endif
                            @if($user->gender=='1')
                                男
                            @endif
                            @if($user->gender=='2')
                                女
                            @endif
                        </td>
                        <td>注册时间</td>
                        <td>{{$user->created_at}}</td>
                    </tr>
                    <tr>
                        <td>省份</td>
                        <td>{{isset($user->province)?$user->province:'--'}}</td>
                        <td>城市</td>
                        <td>{{isset($user->city)?$user->city:'--'}}</td>
                        <td>服务号openid</td>
                        <td colspan="3">{{isset($user->fwh_openid)?$user->fwh_openid:'--'}}</td>
                    </tr>
                    <tr>
                        <td>邀请目标</td>
                        <td>{{$user->target_yq_num==0?'还未生成海报':$user->target_yq_num}}</td>
                        <td>现已邀请好友数</td>
                        <td>{{isset($user->yq_num)?$user->yq_num:'--'}}</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-border table-bg mt-10">
                    <thead>
                    <tr>
                        <th width="120">被邀请人</th>
                        <th width="200">新加入时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($userTJs as $userTJ)
                        <tr>
                            <td>{{$userTJ->user->nick_name}}</td>
                            <td>{{$userTJ->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script type="text/javascript">

        $(function () {

        });


    </script>
@endsection