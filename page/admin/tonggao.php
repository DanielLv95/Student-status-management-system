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
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea layui-hide" name="content" lay-verify="content" id="LAY_demo_editor"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="submit">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<script src="../../common/layui/layui.js" charset="utf-8"></script>
<script src="../../common/lib/jquery-1.9.0.min.js" charset="utf-8"></script>
<script>
    var element;
    var form;
    layui.use(['form', 'layedit','element'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,element=layui.element
            ,layedit = layui.layedit;
        //创建一个编辑器
        var editIndex = layedit.build('LAY_demo_editor',{
            tool:[
                'strong' //加粗
                ,'italic' //斜体
                ,'underline' //下划线
                ,'del' //删除线

                ,'|' //分割线

                ,'left' //左对齐
                ,'center' //居中对齐
                ,'right' //右对齐\
            ]
        });

        //自定义验证规则
        form.verify({
            title: function(value){
                if(value.length < 5){
                    return '标题至少得5个字符啊';
                }
            }
            ,content: function(value){
                layedit.sync(editIndex);
            }
        });

        //监听提交
        form.on('submit(submit)', function(data){
            console.log(data)
            $.ajax({
                url:"../doAction.php?act=sendtg",
                data:{data:JSON.stringify(data.field)},
                type:"POST",
                dataType:'json',
                success:function (data) {
                    if(data.errcode==1){
                        layer.open({
                            title: '提示'
                            ,content: '发布成功'
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
                    }else {
                        ayer.open({
                            title: '提示'
                            ,content: '发布失败'
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

                }
            });
            return false;
        });
    });
</script>
</body>
</html>