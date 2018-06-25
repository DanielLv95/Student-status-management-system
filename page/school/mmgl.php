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
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="../../common/layui/css/layui.css" media="all">
    <title>首页</title>

</head>

<body>
<form class="layui-form">
<div class="layui-form-item">
    <label class="layui-form-label">用户名</label>
    <div class="layui-input-inline">
        <input type="text" name="username" id="username" lay-verify="username" autocomplete="off"class="layui-input" readonly="readonly">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">原密码</label>
    <div class="layui-input-inline">
        <input type="opassword" name="opassword" id="opassword" lay-verify="opass" placeholder="请输入原密码" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">请填写原密码</div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">新密码</label>
    <div class="layui-input-inline">
        <input type="password" name="password" id="password" lay-verify="pass" placeholder="请输入新密码" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">请填写6到12位新密码</div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">重复密码</label>
    <div class="layui-input-inline">
        <input type="password" name="repassword" id="repassword" lay-verify="repass" placeholder="请重复新密码" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">请重复新密码</div>
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
    document.getElementById('username').value='<?php echo $_SESSION['username']?>'
    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer;


        //自定义验证规则
        form.verify({
            pass: [/^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格']
            ,repass: function () {
                var password=document.getElementById("password").value;
                var repassword=document.getElementById("repassword").value;
                if(repassword!=password){
                    return '密码不一致，请检查';
                }
            }
        });

        //监听提交
        form.on('submit(tj)', function(data){
            console.log(JSON.stringify(data.field))
            $.ajax({
                url: "../doAction.php?act=xgmm",
                data:{data:JSON.stringify(data.field)},
                type: "POST",
                dataType:'json',
                success:function (msg) {
                    if(msg.errcode==3){
                        layer.open({
                            title: '提示'
                            ,content: '原密码不正确'
                            ,yes: function(index, layero){
                                document.getElementById('opassword').focus;
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('opassword').focus;
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.errcode==1){
                        layer.open({
                            title: '提示'
                            ,content: '修改成功'
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
                    if(msg.errcode==0){
                        layer.open({
                            title: '提示'
                            ,content: '修改失败'
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
