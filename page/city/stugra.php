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
    <link rel="stylesheet" href="../../common/layui/css/layui.css" media="all">
</head>
<body>
<div class="stuTable layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">查询学生：</label>
        <div class="layui-input-inline" style="width: 150px">
            <select name="major" id="major" lay-filter="major"  lay-search="">
                <option value="">专业</option>
            </select>
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="sid" id="sid" autocomplete="off" placeholder="学号" style="width: 150px">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="name" id="name" autocomplete="off" placeholder="姓名" style="width: 150px">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="idnum" id="idnum" autocomplete="off" placeholder="身份证号" style="width: 150px">
        </div>
        <button class="layui-btn" data-type="reload">搜索</button>
        <div class="layui-btn-group">
            <button class="layui-btn" data-type="gra">同意毕业</button>
        </div>
    </div>
</div>

<table class="layui-hide" id="LAY_table_stu" lay-filter="stu"></table>

<script src="../../common/layui/layui.js"></script>
<script src="../../common/lib/jquery-1.9.0.min.js"></script>
<script>
    layui.use(['table'], function(){
        var table = layui.table;
        loadmjSelect()
        //方法级渲染
        table.render({
            elem: '#LAY_table_stu'
            ,url: '../doAction.php?act=stulist&user=<?php echo $_SESSION['username']?>&reg=2&gra=3'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'sid', title: '学号', width:100, sort: true, fixed: true}
                ,{field:'name', title: '姓名', width:100}
                ,{field:'sex', title: '性别', width:80, sort: true}
                ,{field:'birth', title: '生日', width:100}
                ,{field:'idnum', title: '身份证号', width:177}
                ,{field:'address', title: '地址', width:177}
                ,{field:'phone', title: '电话', width:100}
                ,{field:'schname', title: '学校', width:100}
                ,{field:'mname', title: '专业', width:100}
                ,{field:'regdate', title: '入学年月', width:100}
                ,{field:'gradate', title: '毕业年月', width:100}
                ,{field:'regstate', title: '注册状态', width:100}
                ,{field:'grastate', title: '毕业状态', width:100}
            ]]
            ,id: 'stuList'
            ,page: true
            ,height: 'full-100'
        });

        var $ = layui.$, active = {
            reload: function(){
                var major = $('#major');
                var sid = $('#sid');
                var name = $('#name');
                var idnum = $('#idnum');
                table.reload('stuList', {
                    where: {
                        major: major.val(),
                        sid:sid.val(),
                        name:name.val(),
                        idnum:idnum.val()
                    }
                });
            }
        };
        var $ = layui.$, active = {
            gra:function () {
                var checkStatus = table.checkStatus('stuList')
                    ,data = checkStatus.data;
                layer.confirm('真的要同意毕业么', function(index){
                    $.ajax({
                        url: "../doAction.php?act=gra",
                        data:{data:JSON.stringify(data),user:'<?php echo $_SESSION['username']?>'},
                        type: "POST",
                        dataType:'json',
                        success:function (msg) {
                            console.log(msg)
                            for(var d in msg){
                                if(msg[d]==0){
                                    layer.open({
                                        title: '提示'
                                        ,content: '审批失败！'
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
                                        ,content: '审批成功！'
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
        };
        //监听表格复选框选择
        table.on('checkbox(stu)', function(obj){
            console.log(obj)
        });


        $('.stuTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
    function loadmjSelect() {
        $.ajax({
            url:"../doAction.php?act=mjselect",
            type: "POST",
            data:{user:'<?php echo $_SESSION['username']?>'},
            dataType:'json',
            success:function (data) {
                var root=document.getElementById("major");
                if(root.length>1){
                    root.options.length=1;
                }
                for (var i=0;i<data.length;i++){
                    var option=document.createElement("option");
                    option.setAttribute("value",data[i].mid);
                    option.innerText=data[i].mname;
                    root.appendChild(option);
                    layui.form.render('select');
                }
            }

        });
    }
</script>
</body>
</html>