<?php
//session_start();
//if (!isset($_SESSION['role'])||$_SESSION['role']!=1||$_SESSION['role']!=3||$_SESSION['role']!=4){
//    echo <<<s
//    <script type="text/javascript" src="../common/layui/layui.js"></script>
//      <script>
//          layui.use(['layer'], function(){
//              var layer = layui.layer;
//
//        layer.open({
//                                    title: '提示'
//                                    ,content: '非法访问'
//                                    ,yes: function(index, layero){
//                                        location.href="../login.php"
//                                        layer.close(index); //如果设定了yes回调，需进行手工关闭
//                                    }
//                                    ,cancel: function(index, layero){
//                                        location.href="../login.php"
//                                        layer.close(index)
//                                        return false;
//                                    }
//                                })
//                                })
//      </script>
//s;
//    exit();
//}
//?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>学籍管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../common/layui/css/layui.css" media="all">
</head>
<body>
<div class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-inline">
            <input type="text" name="title" id="title" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">时间</label>
        <div class="layui-input-inline">
            <input type="text" name="time" id="time" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
            <textarea name="content" id="content" class="layui-textarea"></textarea>
        </div>
    </div>
</div>
<script src="../common/layui/layui.js"></script>
<script src="../common/lib/jquery-1.9.0.min.js"></script>
<script>
    //Demo
    $.ajax({
        url: "./doAction.php?act=newtg",
        type: "POST",
        dataType:'json',
        success:function (msg) {
            $("#title").val(msg[0].title)
            $("#time").val(msg[0].time)
            $("#content").val(msg[0].content)
        }
    });
    layui.use('form', function(){
        var form = layui.form;


    });
</script>
</body>
</html>
