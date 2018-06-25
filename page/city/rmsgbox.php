<?php
session_start();
if (!isset($_SESSION['role'])||$_SESSION['role']!=3){
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
<div class="msgTable">

    <div class="layui-btn-group msgTable">
        <button class="layui-btn" data-type="markRead">标记为已读</button>
        <button class="layui-btn" data-type="markNoRead">标记为未读</button>
        <button class="layui-btn" data-type="showRead">显示已读</button>
        <button class="layui-btn" data-type="showNoRead">显示未读</button>
    </div>
</div>

<div id="detail" style="display: none" >

    <table class="layui-table" lay-even lay-skin="line row" lay-size="sm" >
        <colgroup>
            <col width="150">
            <col width="250">
        </colgroup>
        <tbody>
        <tr id="title">
        </tr>
        <tr id="senter">
        </tr>
        <tr id="time">
        </tr>
        <tr id="content">
        </tr>
        </tbody>
    </table>
</div>
<table class="layui-hide" id="LAY_table_msg" lay-filter="msg"></table>
<script type="text/html" id="toolBar">
    <a class="layui-btn layui-btn-primary layui-btn-mini" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-danger layui-btn-mini" lay-event="del">删除</a>
</script>

<script src="../../common/layui/layui.js" charset="utf-8"></script>
<script src="../../common/lib/jquery-1.9.0.min.js" charset="utf-8"></script>
<script>
    layui.use(['table','layedit'], function(){
        var table = layui.table
            ,layedit=layui.layedit;

        //方法级渲染
        table.render({
            elem: '#LAY_table_msg'
            ,url: '../doAction.php?act=msglist&user=admin'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id', title: 'ID', width:80, sort: true, fixed: true}
                ,{field:'title', title: '标题', width:100}
                ,{field:'time', title: '时间', width:180,sort: true}
                ,{field:'content', title: '内容', width:120}
                ,{field:'state', title: '状态', width:80, sort: true}
                ,{fixed: 'right', width:200, align:'center', toolbar: '#toolBar'}
            ]]
            ,id: 'msgList'
            ,page: true
            ,height: 315
        });
        //监听工具条
        table.on('tool(msg)', function(obj){
            var data = obj.data;
            console.log(data)
            if(obj.event === 'detail'){
                document.getElementById("title").innerHTML="<td><p style='font-size: large'>标题：</p></td>"
                    +"<td><p style='font-size: large'>"+data.title+"</p></td>"
                document.getElementById("senter").innerHTML="<td><p style='font-size: large'>发件人：</p></td>"
                    +"<td><p style='font-size: large'>"+data.senter+"</p></td>"
                document.getElementById("time").innerHTML="<td><p style='font-size: large'>时间：</p></td>"
                    +"<td><p style='font-size: large'>"+data.time+"</p></td>"
                document.getElementById("content").innerHTML="<td><p style='font-size: large'>内容：</p></td>"
                                    +"<td><div class=\"layui-input-inline\">\n" +
                    "        <textarea style='font-size: large' class=\"layui-textarea\" readonly rows='10'>"+data.content+"</textarea>\n" +
                    "      </div></td>"
                $.ajax({
                    url:"../doAction.php?act=readmsg",
                    data:{data:JSON.stringify(data)},
                    type:"POST",
                    dataType:"json"
                });
                layer.open({
                    type: 1 //Page层类型
                    ,area: ['400px', '400px']
                    ,title: '详细信息'
                    ,shade: 0.6 //遮罩透明度
                    ,maxmin: true //允许全屏最小化
                    ,anim: 3 //0-6的动画形式，-1不开启
                    ,content: $('#detail')
                    ,yes: function(index, layero){
                        location.reload();
                        layer.close(index); //如果设定了yes回调，需进行手工关闭
                    }
                    ,cancel: function(index, layero){
                        location.reload();
                        layer.close(index)
                        return false;
                    }
                });
            } else if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        url:"../doAction.php?act=delmsg",
                        data:{data:JSON.stringify(data)},
                        type:"POST",
                        dataType:"json",
                        success:function (data) {
                            if(data.errcode==0){
                                layer.open({
                                    title: '提示'
                                    ,content: '删除成功'
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
                                    ,content: '删除失败'
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
                    layer.close(index);
                });
            }
        });
        var $ = layui.$, active = {
            showRead:function () {
                console.log("sadasd")
                table.reload('msgList', {
                    where: {
                        state:2
                    }
                });
            }
            ,showNoRead:function () {
                table.reload('msgList', {
                    where: {
                        state:1
                    }
                });
            }
            ,markRead:function () {
                var checkStatus = table.checkStatus('msgList')
                    ,data = checkStatus.data;
                if(data.length==0){
                    layer.msg("请选择数据")
                }else if(data.length==1){
                    if(eval(JSON.stringify(data))[0].state=="已读"){
                        layer.msg("所选数据已为已读状态，重新选择")
                    }else{
                        ajaxpost(data,2);
                    }
                }
                else{
                    ajaxpost(data,2);
                }

            }
            ,markNoRead:function () {
                var checkStatus = table.checkStatus('msgList')
                    ,data = checkStatus.data;
                if(data.length==0){
                    layer.msg("请选择数据")
                }else if(data.length==1){
                    if(eval(JSON.stringify(data))[0].state=="未读"){
                        layer.msg("所选数据已为未读状态，重新选择")
                    }else{
                        ajaxpost(data,1);
                    }
                }
                else{
                    ajaxpost(data,1);
                }

            }
        };
        function ajaxpost(data,state) {
            $.ajax({
                url:"../doAction.php?act=mkread",
                data:{data:JSON.stringify(data),state:state},
                type:"POST",
                dataType:"json",
                success:function (msg) {
                    console.log(msg)
                    for(var d in msg){
                        if(msg[d]==0){
                            layer.open({
                                title: '提示'
                                ,content: '标记失败！'
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
                            break;
                        }else if(msg[d]==1&&d==msg.length-1){
                            layer.open({
                                title: '提示'
                                ,content: '标记成功！'
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
                }
            })
        }

        $('.msgTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>
</body>

</html>
