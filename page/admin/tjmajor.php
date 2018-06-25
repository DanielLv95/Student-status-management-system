<?php
session_start();
if (!isset($_SESSION['role'])||$_SESSION['role']!=1){
    echo <<<s
    <script type="text/javascript" src="../../common/layui/layui.js"></script>
      <script>
          layui.use(['layer'], function(){
              var layer = layui.layer;
              
        layer.open({
                                    title: '提示'
                                    ,content: '非法访问'
                                    ,yes: function(index, layero){
                                        location.href="../login.php"
                                        layer.close(index); //如果设定了yes回调，需进行手工关闭
                                    }
                                    ,cancel: function(index, layero){
                                        location.href="../login.php"
                                        layer.close(index)
                                        return false;
                                    }
                                })
                                })
      </script>
s;
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>学籍管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../../common/layui/css/layui.css" media="all">
</head>
<body>
<form class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">专业代码</label>
        <div class="layui-input-inline">
            <input type="text" name="majorid"  id="majorid" lay-verify="majorid" autocomplete="off" placeholder="请输入专业代码" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">专业名称</label>
        <div class="layui-input-inline">
            <input type="text" name="majornm" id="majornm" lay-verify="majornm" placeholder="请输入专业名称" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="tj">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>



<script src="../../common/layui/layui.js" charset="utf-8"></script>
<script src="../../common/lib/jquery-1.9.0.min.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'layedit'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit;

        //自定义验证规则
        form.verify({
            majorid: function(value){
                if(!/^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$/.test(value)){
                    return '专业代码不能有特殊字符';
                }
                if(/(^\_)|(\__)|(\_+$)/.test(value)){
                    return '专业代码首尾不能出现下划线\'_\'';
                }
                if(!/^\d+\d+\d$/.test(value)){
                    return '专业代码应该全为数字';
                }
                if(value.length < 4|| value.length > 10){
                    return '专业代码必须4到10个字符';
                }
            }
            ,majornm:function (value) {
                if(!/^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$/.test(value)){
                    return '专业名称不能有特殊字符';
                }
                if(/(^\_)|(\__)|(\_+$)/.test(value)){
                    return '专业名称首尾不能出现下划线\'_\'';
                }
                if(value.length < 4|| value.length > 50){
                    return '专业名称必须4到50个字符';
                }
            }
        });

        //监听提交
        form.on('submit(tj)', function(data){
            $.ajax({
                url: "../doAction.php?act=tjmajor",
                data:{data:JSON.stringify(data.field)},
                type: "POST",
                dataType:'json',
                success:function (msg) {
                    if(msg.errcode==0){
                        layer.open({
                            title: '提示'
                            ,content: '添加成功'
                            ,yes: function(index, layero){
                                location.reload();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                location.reload();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.errcode==1){
                        layer.open({
                            title: '提示'
                            ,content: '专业代码已存在'
                            ,yes: function(index, layero){
                                document.getElementById('majorid').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('majorid').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.errcode==2){
                        layer.open({
                            title: '提示'
                            ,content: '专业名称已存在'
                            ,yes: function(index, layero){
                                document.getElementById('majornm').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('majornm').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.errcode==3){
                        layer.open({
                            title: '提示'
                            ,content: '天假失败'
                            ,yes: function(index, layero){
                                document.getElementById('majornm').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('majornm').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                }
            });

//            layer.alert(JSON.stringify(data.field), {
//                title: '最终的提交信息'
//            })
            return false;
        });
    });
</script>
</body>
</html>