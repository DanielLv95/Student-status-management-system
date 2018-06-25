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
    <link rel="stylesheet" href="../../common/layui/css/layui.css" media="all">
</head>
<body>
<div class="userTable">
    搜索用户名：
    <div class="layui-inline">
        <input class="layui-input" name="username" id="search" autocomplete="off">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
    <div class="layui-btn-group">
        <button class="layui-btn" data-type="deleteData">删除选中行数据</button>
        <button class="layui-btn" data-type="getCheckLength">获取选中数目</button>
    </div>
</div>

<table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
<script type="text/html" id="toolBar">
    <a class="layui-btn layui-btn-primary layui-btn-mini" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-danger layui-btn-mini" lay-event="del">删除</a>
</script>


<script src="../../common/layui/layui.js"></script>
<script src="../../common/lib/jquery-1.9.0.min.js"></script>
<script>
    layui.use(['table'], function(){
        var table = layui.table;

        //方法级渲染
        table.render({
            elem: '#LAY_table_user'
            ,url: '../doAction.php?act=ulist'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'uid', title: 'ID', width:80, sort: true, fixed: true}
                ,{field:'username', title: '用户名', width:100}
                ,{field:'role', title: '角色', width:120, sort: true}
                ,{field:'address', title: '地址', width:177}
                ,{field:'phone', title: '电话', width:100}
                ,{field:'cityname', title: '管理城市', width:100}
                ,{field:'schname', title: '管理学校', width:100}
                ,{fixed: 'right', width:130, align:'center', toolbar: '#toolBar'}
            ]]
            ,id: 'userList'
            ,page: true
            ,height: 'full-100'
        });

        var $ = layui.$, active = {
            reload: function(){
                var search = $('#search');
                table.reload('userList', {
                    where: {
                        username: search.val()
                    }
                });
            }
            ,deleteData: function(){ //删除所选数据
                var checkStatus = table.checkStatus('userList')
                    ,data = checkStatus.data;
                console.log(JSON.stringify(data));
                layer.confirm('真的删除所选数据么', function(index){
                    $.ajax({
                        url: "../doAction.php?act=del",
                        data:{data:JSON.stringify(data)},
                        type: "POST",
                        dataType:'json',
                        success:function (msg) {
                            console.log(msg)
                            for(var d in msg){
                                if(msg[d]==0){
                                    layer.open({
                                        title: '提示'
                                        ,content: '删除失败！'
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
                                        ,content: '删除成功！'
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
                    });
                    layer.close(index);
                });
            }
            ,getCheckLength: function(){ //获取选中数目
                var checkStatus = table.checkStatus('userList')
                    ,data = checkStatus.data;
                layer.msg('选中了：'+ data.length + ' 个');
            }
        };
        //监听表格复选框选择
        table.on('checkbox(user)', function(obj){
            console.log(obj)
        });
        //监听工具条
        table.on('tool(user)', function(obj){
            var data = obj.data;
            if(obj.event === 'detail'){
                layer.msg('UID：'+ data.uid + ' 的查看操作');
            } else if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        url: "../doAction.php?act=del",
                        data:{data:JSON.stringify(data)},
                        type: "POST",
                        dataType:'json',
                        success:function (msg) {
                            console.log(msg)
                            for(var d in msg){
                                if(msg[d]==0){
                                    layer.open({
                                        title: '提示'
                                        ,content: '删除失败！'
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
                                }else {
                                    layer.open({
                                        title: '提示'
                                        ,content: '删除成功！'
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
                    layer.close(index);
                });
            } else if(obj.event === 'edit'){
                layer.alert('编辑行：<br>'+ JSON.stringify(data))
            }
        });

        $('.userTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>
</body>
</html>