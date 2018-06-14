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
        <div class="text-c">
            <form action="{{URL::asset('/admin/user/index')}}" method="get" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:250px"
                           value="{{$con_arr['search_word']==null?'':$con_arr['search_word']}}"
                           placeholder="请输入用户昵称或者openid">
                    <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索
                    </button>
                </div>
            </form>
        </div>

        <table class="table table-border table-bordered table-bg table-sort mt-20">
            <thead>
            <tr>
                <th scope="col" colspan="11">用户列表</th>
            </tr>
            <tr class="text-c">
                <th width="30">ID</th>
                <th width="80">头像</th>
                <th width="100">昵称</th>
                <th width="100">openid</th>
                <th width="100">yq_hb_media_id</th>
                {{--<th width="100">是否关注</th>--}}
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
                    <td>
                        <a style="text-decoration:none"
                           onClick="show_user('用户详情','{{URL::asset('/admin/user/info')}}?user_id={{$data->id}})',{{$data->id}})"
                           href="javascript:;"
                           title="用户详情" class="c-primary">
                            {{$data->nick_name}}
                        </a>
                    </td>
                    <td>{{$data->fwh_openid}}</td>
                    <td>{{$data->yq_hb_media_id}}</td>
                    {{--<td>{{$data->is_subscribe=="1"?"已经关注":"已经取消"}}</td>--}}
                    <td>{{$data->yq_num}}</td>
                    <td>{{$data->target_yq_num==0?'暂未生成海报':$data->target_yq_num}}</td>
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

        //展示用户详情
        function show_user(title, url, id) {
            console.log("show_user url:" + url);
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }


    </script>
@endsection