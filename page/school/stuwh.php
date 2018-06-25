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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>学籍管理系统</title>
    <link rel="stylesheet" href="../../common/layui/css/layui.css" media="all">
    <script>
        function load() {
            $('#sidit').hide();
            $('#nameit').hide();
            $('#sexit').hide();
            $('#addressit').hide();
            $('#phoneit').hide();
            $('#photoit').hide();
            $('#tj').hide();
        }
    </script>
</head>
<body onload="load()">
<div class="layui-form-item" name="form">
    <label class="layui-form-label">查询学生：</label>
    <div class="layui-input-inline">
        <input type="text" name="identity" id="identity"  placeholder="请输入身份证号" autocomplete="off" class="layui-input">
    </div>
    <button class="layui-btn" data-type="seach" onclick="seach()">搜索</button>
</div>
<form class="layui-form">
    <div class="layui-form-item" id="sidit">
        <label class="layui-form-label">学号</label>
        <div class="layui-input-inline">
            <input type="text" name="sid" id="sid" lay-verify="sid" autocomplete="off"  class="layui-input" readonly>
        </div>
    </div>
    <div class="layui-form-item" id="nameit" >
        <label class="layui-form-label">姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="name" id="name" lay-verify="name" autocomplete="off"  class="layui-input">
        </div>
    </div>
    <div class="layui-form-item" id="sexit" >
        <label class="layui-form-label">性别</label>
        <div class="layui-input-block">
            <input type="radio" name="sex" id="sex1" value="男" title="男" checked="">
            <input type="radio" name="sex" id="sex2" value="女" title="女">
        </div>
    </div>
    <div class="layui-form-item" id="photoit">
        <label class="layui-form-label">照片</label>
        <div class="layui-input-inline">
            <div class="layui-upload-list layui-input-inline">
                <img class="layui-upload-img" id="prephoto" style="width:  100px;length:100px">
                <p id="demoText"></p>
            </div>
            <button type="button" class="layui-btn" id="up" >上传图片</button>

        </div>
        <input type="hidden" id="photosrc" name="photosrc"  lay-verify="photosrc">
    </div>

    <div class="layui-form-item" id="addressit">
        <label class="layui-form-label">地址</label>
        <div class="layui-input-inline">
            <input type="tel" name="address" id="address" lay-verify="address" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item" id="phoneit">
        <label class="layui-form-label">手机号码</label>
        <div class="layui-input-inline">
            <input type="tel" name="phone" id="phone" lay-verify="required|phone" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item"  id="tj">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="tj">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>

</form>
<script src="../../common/layui/layui.js" charset="utf-8"></script>
<script src="../../common/lib/jquery-1.9.0.min.js" charset="utf-8"></script>
<script>
    layui.use(['upload','form'], function(){
        var $ = layui.jquery
            ,form=layui.form
            ,upload = layui.upload;

        //普通图片上传
        var uploadInst = upload.render({
            elem: '#up'
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
                url: "../doAction.php?act=wh",
                data:{data:JSON.stringify(data.field)},
                type: "POST",
                dataType:'json',
                success:function (msg) {
                    if(msg.err==1){
                        layer.open({
                            title: '提示'
                            ,content: '修改失败'
                            ,yes: function(index, layero){
                                document.getElementById('name').focus();
                                layer.close(index); //如果设定了yes回调，需进行手工关闭
                            }
                            ,cancel: function(index, layero){
                                document.getElementById('name').focus();
                                layer.close(index)
                                return false;
                            }
                        })
                    }
                    if(msg.err==0){
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

                }
            });


//            layer.alert(JSON.stringify(data.field), {
//                title: '最终的提交信息'
//            })
            return false;
        });
    });
    function seach() {
        var idnum=document.getElementById('identity').value;
        if(idnum==''){
            layer.open({
                title: '提示'
                ,content: '请输入身份证号！'
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
        }else {
            $.ajax({
                url:"../doAction.php?act=cxstu",
                data:{idnum:idnum},
                type:'POST',
                dataType:'json',
                success:function (data) {
                    var radio=$('#sex')
                    $('#sidit').show();
                    $('#sid').val(data[0].sid);
                    $('#nameit').show();
                    $('#name').val(data[0].name);
                    $('#sexit').show();
                    if(data[0].sex=='女'){
                        document.getElementById('sex2').checked=true;
                    }else {
                        document.getElementById('sex1').checked=true;
                    }
                    layui.form.render();
//                    $('#name').val(data[0].name);
                    $('#phoneit').show();
                    $('#phone').val(data[0].phone);
                    $('#addressit').show();
                    $('#address').val(data[0].address);
                    $('#photoit').show();
                    document.getElementById('prephoto').src="../"+data[0].photo;
                    $('#tj').show();
                }
            });
        }

    }
</script>
</body>
</html>
