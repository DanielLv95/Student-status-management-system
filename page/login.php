
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="keywords" content="学籍管理系统">
    <meta name="description" content="学籍管理系统">
    <title>首页</title>


    <link rel="stylesheet" href="../common/layui/css/layui.css">
    <link rel="stylesheet" href="../common/css/sccl.css">

</head>

<body class="login-bg">
<div class="login-box">
    <header>
        <h1>学籍管理系统</h1>
    </header>
    <div class="login-main">
        <form action="" class="layui-form" id="form">
            <div class="layui-form-item">
                <label class="login-icon">
                    <i class="layui-icon"></i>
                </label>
                <input type="text" name="username" lay-verify="userName" autocomplete="off" placeholder="这里输入登录名" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="login-icon">
                    <i class="layui-icon"></i>
                </label>
                <input type="password" name="password" lay-verify="password" autocomplete="off" placeholder="这里输入密码" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="pull-left login-remember">
                    <label>记住帐号？</label>

                    <input type="checkbox" name="rememberMe" value="true" lay-skin="switch" title="记住帐号"><div class="layui-unselect layui-form-switch"><i></i></div>
                </div>
                <div class="pull-right">
                    <button class="layui-btn layui-btn-primary" lay-submit="" lay-filter="login">
                        <i class="layui-icon"></i> 登录
                    </button>
                </div>
                <div class="clear"></div>
            </div>
        </form>
    </div>
    <footer>
        <p>@lvweicheng</p>
    </footer>
</div>
<script src="../common/layui/layui.js"></script>
<script src="../common/lib/jquery-1.9.0.min.js"></script>
<script>

    layui.use(['layer', 'form'], function () {
        var layer = layui.layer,
            form = layui.form;

        form.verify({
            userName: function (value) {
                if (value === '')
                    return '请输入用户名';
            },
            password: function (value) {
                if (value === '')
                    return '请输入密码';
            }
        });
        //监听提交
        form.on('submit(login)', function(data){
            $.ajax({
                url: "./doAction.php?act=login",
                data:{data:JSON.stringify(data.field)},
                type: "POST",
                dataType:'json',
                success:function (msg) {
                    console.log(msg)
                    if(msg.role==1){
                        window.location.href="./admin/adindex.php"
                    }
                    if(msg.role==2){
                        window.location.href="./province/proindex.php"
                    }
                    if(msg.role==3){
                        window.location.href="./city/cityindex.php"
                    }
                    if(msg.role==4){
                        window.location.href="./school/schindex.php"
                    }
                    if(msg.errcode==1){
                        layer.open({
                            title: '提示'
                            ,content: '用户名或密码输入错误请重新输入！'
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
