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

<form class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">学号</label>
        <div class="layui-input-inline">
            <input type="text" name="sid" id="sid" lay-verify="sid" autocomplete="off" placeholder="请输入学号" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="name" id="name" lay-verify="name" autocomplete="off" placeholder="请输入姓名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">性别</label>
        <div class="layui-input-block">
            <input type="radio" name="sex" value="男" title="男" checked="">
            <input type="radio" name="sex" value="女" title="女">
        </div>
    </div>
    <div class="layui-form-item">
            <label class="layui-form-label">照片</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="photo">上传图片</button>
            <div class="layui-upload-list layui-input-inline">
                <img class="layui-upload-img" id="prephoto" style="width:  100px;length:100px">
                <p id="demoText"></p>
            </div>
        </div>
        <input type="hidden" id="photosrc" name="photosrc"  lay-verify="photosrc">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">身份证号</label>
        <div class="layui-input-inline">
            <input type="text" name="identity" id="identity" lay-verify="identity" placeholder="请输入身份证号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">地址</label>
        <div class="layui-input-inline">
            <input type="tel" name="address" lay-verify="address" autocomplete="off" placeholder="请输入地址" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机号码</label>
        <div class="layui-input-inline">
            <input type="tel" name="phone" id="phone" lay-verify="required|phone" autocomplete="off" placeholder="请输入手机号" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">专业</label>
        <div class="layui-input-inline">
            <select name="major" id="major" lay-filter="major" lay-verify="major" lay-search="">
                <option value="">直接选择或搜索选择</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">学制</label>
        <div class="layui-input-inline">
            <select name="xuezhi" id="xuezhi" lay-filter="xuezhi" lay-verify="xuezhi" lay-search="">
                <option value="">学制</option>
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
    layui.use(['form','upload'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,upload=layui.upload;
        loadSelect();
        loadxzSelect();
        //自定义验证规则
        form.verify({
            sid: [/^\d{10}$/, '请输入10位学号']
            ,name: [/^[\u4e00-\u9fa5]{2,3}$/, '姓名为两到三个汉字']
            ,identity: [/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/, '身份证号格式不正确']
            ,address: function (value) {
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
            ,major:function (value) {
                if(value.length<=0){
                    return '专业未选择';
                }
            }
            ,xuezhi:function (value) {
                if(value.length<=0){
                    return '学制未选择';
                }
            }
            ,photosrc:function (value) {
                if(value.length<=0){
                    return '请上传照片';
                }
            }
        });
        //普通图片上传
        var uploadInst = upload.render({
            elem: '#photo'
            ,url: '../doAction.php?act=upload'
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#prephoto').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code > 0){
                    return layer.msg('上传失败');
                }
                //上传成功
                document.getElementById('photosrc').value=res.data.src;
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });
        //监听提交
        form.on('submit(tj)', function(data){
            console.log(JSON.stringify(data.field))
            $.ajax({
                url: "../doAction.php?act=tjstu",
                data:{data:JSON.stringify(data.field),user:"<?php echo $_SESSION['username']?>"},
                type: "POST",
                dataType:'json',
                success:function (msg) {
                    if(msg.siderr==1){
                        layer.open({
                            title: '提示'
                            ,content: '学号已存在'
                            ,yes: function(index, layero){
                                document.getElementById('sid').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('sid').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.idnumerr==1){
                        layer.open({
                            title: '提示'
                            ,content: '身份证号已存在'
                            ,yes: function(index, layero){
                                document.getElementById('identity').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('identity').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.phoneerr==1){
                        layer.open({
                            title: '提示'
                            ,content: '手机号已存在'
                            ,yes: function(index, layero){
                                document.getElementById('phone').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('phone').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.err==1){
                        layer.open({
                            title: '提示'
                            ,content: '添加失败'
                            ,yes: function(index, layero){
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.err==0){
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
                }
            });
            return false;
        });
    });
    function loadSelect() {
        $.ajax({
            url:"../doAction.php?act=mjselect",
            type: "POST",
            data:{user:'<?php echo $_SESSION['username']?>'},
            dataType:'json',
            success:function (data) {

                var root=document.getElementById("major");
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
    function loadxzSelect() {
        $.ajax({
            url:"../doAction.php?act=xzselect",
            type: "POST",
            data:{user:'<?php echo $_SESSION['username']?>'},
            dataType:'json',
            success:function (data) {
                var root=document.getElementById("xuezhi");
                for (var i=0;i<data.length;i++){
                    var option=document.createElement("option");
                    option.setAttribute("value",data[i].value);
                    option.innerText=data[i].xuezhi;
                    root.appendChild(option);
                    layui.form.render('select');
                }
            }

        });
    }
</script>
</body>
</html>