@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 菜单管理 <span
                class="c-gray en">&gt;</span> 菜单列表 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace(location.href);" title="刷新"
                                                      onclick="location.replace('{{URL::asset('/admin/menu/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="panel panel-primary mt-20">
            <div class="panel-header">当前菜单设置</div>
            <div class="panel-body">
                <textarea class="textarea" style="width:98%; height:300px; resize:none">$buttons = [
            [
                "name" => "美景",
                "type" => "view",
                "url" => "http://mp.weixin.qq.com/mp/homepage?__biz=MzI3NTExNDc4NQ==&hid=1&sn=d1ec8e05d887e0d09b7dbb216750417b&scene=18#wechat_redirect"
            ],
            [
                "name" => "听听",
                "sub_button" => [
                    [
                        "name" => "喜马拉雅",
                        "type" => "view",
                        "url" => "http://www.ximalaya.com/zhubo/25616166/"
                    ],
                    [
                        "name" => "美景小程序",
                        "type" => "view",
                        "url" => "http://mp.weixin.qq.com/s/KRW8l2wZ3jsVc74muI9FUA"
                    ]
                ]
            ],
            [
                "name" => "APP",
                "sub_button" => [
                    [
                        "name" => "联系我们",
                        "type" => "click",
                        "key" => "V0301_CONTACT_US"
                    ],
                    [
                        "name" => "幸运用户",
                        "type" => "view",
                        "url" => "http://wechat.gowithtommy.com/activityAuth/"
                    ],
                    [
                        "name" => "下载美景",
                        "type" => "view",
                        "url" => "http://app.gowithtommy.com/"
                    ]
                ]
            ]
        ];</textarea>
                <div class="mt-20 c-999">
                    <input class="btn btn-primary radius" type="submit" value="设置菜单" onclick="set_Menu(this,'')">
                    <span class="ml-20">目前菜单为固定选项，如有需要修改请联系开发人员</span>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">


        function set_Menu(obj, id) {
            layer.confirm('确认要设置菜单么？', function (index) {
                //进行后台删除
                var param = {
                    _token: "{{ csrf_token() }}"
                }
                setMenu('{{URL::asset('')}}', param, function (ret) {
                    if (ret.result == true) {
                        layer.msg('设置成功', {icon: 1, time: 1000});
                    } else {
                        layer.msg('设置失败', {icon: 2, time: 1000})
                    }
                })
            });
        }

    </script>
@endsection