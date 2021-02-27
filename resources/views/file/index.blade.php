<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.layuicdn.com/layui/css/layui.css">
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://www.layuicdn.com/layui/layui.js"></script>
</head>
<body>
    <div class="panel panel-default" style="width:70%;margin:0 auto;margin-top:60px;">
        <div class="panel-heading clearfix">
            <span>项目管理</span>
            <button type="button" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#new-project">新建项目</button>

            <div class="modal fade" id="new-project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title" id="myModalLabel">
                                新建项目
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="firstname" class="col-sm-2 control-label">项目</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="name" placeholder="请输入项目名称">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">关闭
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" id="create-project">
                                保存
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            @if(empty($content))
                <div class="alert alert-info">没有任何项目记录，快去新建项目吧。</div>
            @else
                <div class="alert-warning">提示！若预览链接没有更新，清除浏览器缓存后重试。</div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>项目</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($content as $value)
                        <tr>
                            <td>
                                @if($value['count'] > 0)
                                    <a href="/file/{{ urlencode($value['name']) }}" target="_blank">{{ $value['name'] }}</a>
                                    <span class="label label-success">Demo已上传</span>
                                @else
                                    {{ $value['name'] }}
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#upload-demo" data-value="{{ $value['name'] }}">上传Demo</button>
                                <button type="button" class="btn btn-danger btn-sm del-project" data-value="{{ $value['name'] }}">删除</button>
                                @if($value['count'] > 0)
                                    <a class="btn btn-warning btn-sm" href="/file/{{ urlencode($value['name']) }}" target="_blank">预览</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            {{-- 上传demo模板 --}}
            <div class="modal fade" id="upload-demo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title" id="myModalLabel">
                                上传Demo
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="firstname" class="col-sm-2 control-label">项目</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control modal-name" placeholder="项目名称" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="firstname" class="col-sm-2 control-label">Demo</label>
                                    <div class="col-sm-8">
                                        <input type="file" placeholder="Demo" class="modal-demo"><br>
                                        <p class="text-danger small">请上传项目Demo，规定zip格式！</p>
                                        <p class="text-danger small">若当前项目Demo已存在，则会覆盖上传！</p>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">关闭
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" id="do-upload-demo">
                                上传
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function layerAlert(res)
        {
            layui.use('layer', function(){
                layui.layer.alert(res.msg, {
                    skin: 'layui-layer-molv'
                    ,closeBtn: 0
                },function () {
                    if(res.code > 0){
                        window.location.reload()
                    }
                    layui.layer.closeAll();
                });
            });
        }

        function loading(msg)
        {
            layui.use('layer', function(){
                layui.layer.msg(msg, {
                    icon: 16
                    ,shade: 0.01
                    ,time:0
                });
            });
        }

        $('#create-project').on('click',function () {
            var name = $.trim($('#name').val());
            $.post('/file/create', 'name=' + name, function (res) {
                layerAlert(res);
            });
        })

        $('.del-project').on('click',function () {
            var res = confirm("确认删除吗？，删除后不可恢复！");
            if (res) {
                loading('删除中...');
                var name = $(this).data('value');
                $.post('/file/del', 'name=' + name, function (res) {
                    layerAlert(res);
                });
            }
        });

        $('#upload-demo').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var name = button.data('value') // Extract info from data-* attributes

            $(this).find('.modal-name').val(name)
        })

        $('#do-upload-demo').on('click',function () {
            let name = $('.modal-name').val();
            let demo = $('.modal-demo').get(0).files[0];

            let formData = new FormData();
            formData.append('name',name);
            formData.append('demo',demo);

            $.ajax({
                type: 'POST',
                url: '/file/upload_demo',
                data: formData,
                contentType: false,
                processData: false,
                // dataType: "json",
                beforeSend : function(){
                    loading('上传中...');
                },
                success: function (res) {
                    console.log(res)
                    layerAlert(res);
                }
            });
        });
    </script>
</body>
</html>
