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
    <script>
        function load() {
            $("#city").hide();
            $("#school").hide();
            $("#xuezhi").hide();
        }

    </script>
</head>
<body onload="load()">

<form class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-inline">
            <input type="text" name="username" id="username" lay-verify="username" autocomplete="off" placeholder="请输入用户名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password" id="password" lay-verify="pass" placeholder="请输入密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请填写6到12位密码</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">重复密码</label>
        <div class="layui-input-inline">
            <input type="password" name="repassword" id="repassword" lay-verify="repass" placeholder="请重复密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请重复密码</div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-inline">
                <input type="tel" name="phone" lay-verify="required|phone" autocomplete="off" placeholder="请输入手机号" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">地址</label>
            <div class="layui-input-inline">
                <input type="tel" name="address" lay-verify="address" autocomplete="off" placeholder="请输入地址" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-inline">
            <select id='role' name="role" lay-filter="role" lay-verify="required">
                <option value=""></option>
                <option value="3">市级管理员</option>
                <option value="4">校级管理员</option>
            </select>
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
    <div class="layui-form-item" id="school" lay-filter="schoold">
        <label class="layui-form-label">学校</label>
        <div class="layui-input-inline">
            <select name="sselect" id="sselect" lay-filter="sselect" lay-verify="school" lay-search="">
                <option value="">直接选择或搜索选择</option>
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
    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer;


        //自定义验证规则
        form.verify({
            username: function(value){
                if(!/^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$/.test(value)){
                    return '用户名不能有特殊字符';
                }
                if(/(^\_)|(\__)|(\_+$)/.test(value)){
                    return '用户名首尾不能出现下划线\'_\'';
                }
                if(/^\d+\d+\d$/.test(value)){
                    return '用户名不能全为数字';
                }
                if(value.length < 5 || value.length > 15){
                    return '用户名必须5到15个字符';
                }
            }
            ,pass: [/^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格']
            ,repass: function () {
                var password=document.getElementById("password").value;
                var repassword=document.getElementById("repassword").value;
                if(repassword!=password){
                    return '密码不一致，请检查';
                }
            }
            ,address:function (value) {
                if(!/^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$/.test(value)){
                    return '地址不能有特殊字符';
                }
                if(/(^\_)|(\__)|(\_+$)/.test(value)){
                    return '地址首尾不能出现下划线\'_\'';
                }
                if(/^\d+\d+\d$/.test(value)){
                    return '地址不能全为数字';
                }
                if(value.length > 100){
                    return '地址不能超过100个字符';
                }
            }
            ,city:function (value) {
                var role=document.getElementById("role").value;
                if(role==3){
                    if(value==''){
                        return 'city未选择';
                    }
                }
            }
            ,school:function (value) {
                var role=document.getElementById("role").value;
                var city=document.getElementById("cselect").value;
                if(role==3){
                    if(city==''){
                        return 'city未选择';
                    }
                }
                if(role==4){
                    if(value==''){
                        return 'school未选择';
                    }else if (city==''){
                        return 'city未选择';
                    }
                }
            }
        });

        //监听提交
        form.on('submit(tj)', function(data){
            if(document.getElementById("role").value==3){
                document.getElementById("sselect").value='';
                document.getElementById("xuezhi").value='';
            }
            $.ajax({
                url: "../doAction.php?act=tj",
                data:{data:JSON.stringify(data.field)},
                type: "POST",
                dataType:'json',
                success:function (msg) {
                    if(msg.errcode==0){
                        layer.open({
                            title: '提示'
                            ,content: '用户名已存在'
                            ,yes: function(index, layero){
                                document.getElementById('username').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('username').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.errcode==1){
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
                    if(msg.errcode==2){
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


//            layer.alert(JSON.stringify(data.field), {
//                title: '最终的提交信息'
//            })
            return false;
        });
        form.on('select(role)', function(data){
            if(data.value==3){
                $("#city").show();
                $("#school").hide();
                $("#xuezhi").hide();
                loadcSelect();
            }else if(data.value==4){
                loadcSelect();
                $("#school").show();
                $("#city").show();
                $("#xuezhi").show();
            }else {
                $("#city").hide();
                $("#school").hide();
                $("#xuezhi").hide();
            }
        });
        form.on('select(cselect)', function(data){
            switch (data.value) {
            <?php
                for ($i = 1; $i <= 17; $i++) {
                    echo "case '$i':
                    loadsSelect($i);break;";
                }
                ?>
                default:
                    break;
            }

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
    function loadsSelect(cid) {
        $.ajax({
            url:"../doAction.php?act=sselect",
            type: "POST",
            data:{cityid:cid},
            dataType:'json',
            success:function (data) {
                var root=document.getElementById("sselect");
                if(root.length>1){
                    root.options.length=1;
                }
                for (var i=0;i<data.length;i++){
                    var option=document.createElement("option");
                    option.setAttribute("value",data[i].id);
                    option.innerText=data[i].schname;
                    root.appendChild(option);
                    layui.form.render('select');
                }
            }

        });
    }
</script>

</body>
</html>