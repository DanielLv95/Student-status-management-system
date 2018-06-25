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
        <label class="layui-form-label">学校代码</label>
        <div class="layui-input-inline">
            <input type="text" name="schid"  id="schid" lay-verify="schid" autocomplete="off" placeholder="请输入学校代码" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">学校名称</label>
        <div class="layui-input-inline">
            <input type="text" name="schnm" id="schnm" lay-verify="schnm" placeholder="请输入学校名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item" id="city" lay-filter="cityd">
        <label class="layui-form-label">城市</label>
        <div class="layui-input-inline">
            <select name="cselect" id="cselect" lay-filter="cselect" lay-verify="city" lay-search="">
                <option value="">直接选择或搜索选择</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item" id="xuezhi">
        <label class="layui-form-label">学制</label>
        <div class="layui-input-inline">
            <select name="xuezhi" id="xuezhi" lay-filter="xuezhi" lay-verify="xuezhi" lay-search="">
                <option value="">直接选择或搜索选择</option>
                <option value="1">3年</option>
                <option value="2">5年</option>
                <option value="3">3年、5年</option>
            </select>
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
        loadcSelect();
        //自定义验证规则
        form.verify({
            schid: function(value){
                if(!/^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$/.test(value)){
                    return '学校代码不能有特殊字符';
                }
                if(/(^\_)|(\__)|(\_+$)/.test(value)){
                    return '学校代码首尾不能出现下划线\'_\'';
                }
                if(!/^\d+\d+\d$/.test(value)){
                    return '学校代码应该全为数字';
                }
                if(value.length < 4|| value.length > 10){
                    return '学校代码必须4到10个字符';
                }
            }
            ,schnm:function (value) {
                if(!/^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$/.test(value)){
                    return '学校名称不能有特殊字符';
                }
                if(/(^\_)|(\__)|(\_+$)/.test(value)){
                    return '学校名称首尾不能出现下划线\'_\'';
                }
                if(value.length < 4|| value.length > 50){
                    return '学校名称必须4到50个字符';
                }
            }
            ,city:function (value) {
                if(value.length ==0){
                    return '城市未选择';
                }
            }
            ,xuezhi:function (value) {
                if(value.length ==0){
                    return '学制未选择';
                }
            }
        });

        //监听提交
        form.on('submit(tj)', function(data){
            $.ajax({
                url: "../doAction.php?act=tjsch",
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
                            ,content: '学校代码已存在'
                            ,yes: function(index, layero){
                                document.getElementById('schid').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('schid').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.errcode==2){
                        layer.open({
                            title: '提示'
                            ,content: '学校名称已存在'
                            ,yes: function(index, layero){
                                document.getElementById('schnm').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('schnm').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.errcode==3){
                        layer.open({
                            title: '提示'
                            ,content: '添加失败'
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
    function loadcSelect() {
        $.ajax({
            url:"../doAction.php?act=cselect",
            type: "POST",
            dataType:'json',
            success:function (data) {
                var root=document.getElementById("cselect");
                for (var i=0;i<data.length;i++){
                    var option=document.createElement("option");
                    option.setAttribute("value",data[i].id);
                    option.innerText=data[i].cityname;
                    root.appendChild(option);
                    layui.form.render('select');
                }
            }

        });
    }
</script>
</body>
</html>