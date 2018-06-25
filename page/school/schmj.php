<?php
session_start();
if (!isset($_SESSION['role'])||$_SESSION['role']!=4){
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


<div id="detail" style="display: none" >

    <table class="layui-table" lay-even lay-skin="line row" lay-size="sm" >
        <colgroup>
            <col width="150">
            <col width="250">
        </colgroup>
        <tbody>
        <tr id="id">
        </tr>
        <tr id="name">
        </tr>
        <tr id="time">
        </tr>
        </tbody>
    </table>
</div>
<table class="layui-hide" id="LAY_table_mj" lay-filter="mj"></table>
<script type="text/html" id="toolBar">
    <a class="layui-btn layui-btn-primary layui-btn-mini" lay-event="detail">查看</a>
</script>

<script src="../../common/layui/layui.js" charset="utf-8"></script>
<script src="../../common/lib/jquery-1.9.0.min.js" charset="utf-8"></script>
<script>
    layui.use(['table','layedit'], function(){
        var table = layui.table
            ,layedit=layui.layedit;

        //方法级渲染
        table.render({
            elem: '#LAY_table_mj'
            ,url: '../doAction.php?act=schmjlist&user=<?php echo $_SESSION['username']?>'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'mid', title: '专业代码', width:180, sort: true, fixed: true}
                ,{field:'mname', title: '专业名称', width:180}
                ,{field:'credate', title: '添加时间', width:180,sort: true}
                ,{fixed: 'right', width:200, align:'center', toolbar: '#toolBar'}
            ]]
            ,id: 'mjList'
            ,page: true
            ,height: 315
        });
        //监听工具条
        table.on('tool(mj)', function(obj){
            var data = obj.data;
            if(obj.event === 'detail'){
                document.getElementById("id").innerHTML="<td><p style='font-size: large'>专业代码：</p></td>"
                    +"<td><p style='font-size: large'>"+data.mid+"</p></td>"
                document.getElementById("time").innerHTML="<td><p style='font-size: large'>添加时间：</p></td>"
                    +"<td><p style='font-size: large'>"+data.credate+"</p></td>"
                document.getElementById("name").innerHTML="<td><p style='font-size: large'>专业名称：</p></td>"
                    +"<td><p style='font-size: large'>"+data.mname+"</p></td>"

                layer.open({
                    type: 1 //Page层类型
                    ,area: ['400px', '400px']
                    ,title: '详细信息'
                    ,shade: 0.6 //遮罩透明度
                    ,maxmin: true //允许全屏最小化
                    ,anim: 3 //0-6的动画形式，-1不开启
                    ,content: $('#detail')
                });
            }
        });

    });
</script>
</body>

</html>
