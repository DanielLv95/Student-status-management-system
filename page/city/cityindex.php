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
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="keywords" content="学籍管理系统">
	<meta name="description" content="学籍管理系统">
    <title>首页</title>
	
	<link rel="stylesheet" href="../../common/css/sccl.css">
	<link rel="stylesheet" type="text/css" href="../../common/skin/qingxin/skin.css" id="layout-skin"/>
    
  </head>
  
  <body>
	<div class="layout-admin">
		<header class="layout-header">
			<span class="header-logo">学籍管理系统</span>
			<a class="header-menu-btn" href="javascript:;"><i class="icon-font">&#xe600;</i></a>
			<ul class="header-bar">
                <?php
                if ($_SESSION['role']==1){
                    echo "<li class=\"header-bar-role\"><a href=\"javascript:;\">超级管理员</a></li>";
                }
                if ($_SESSION['role']==2){
                    echo "<li class=\"header-bar-role\"><a href=\"javascript:;\">省级管理员</a></li>";
                }
                if ($_SESSION['role']==3){
                    echo "<li class=\"header-bar-role\"><a href=\"javascript:;\">市级管理员</a></li>";
                }
                if ($_SESSION['role']==4){
                    echo "<li class=\"header-bar-role\"><a href=\"javascript:;\">校级管理员</a></li>";
                }
                ?>
				<li class="header-bar-nav">
					<a href="javascript:;"><?php  echo $_SESSION['username'];?><i class="icon-font" style="margin-left:5px;">&#xe60c;</i></a>
					<ul class="header-dropdown-menu">
						<li><a href="javascript:;">个人信息</a></li>
						<li><a href="javascript:;">切换账户</a></li>
                        <li><a href="javascript:void(0)" onclick="logout()">退出</a></li>
					</ul>
				</li>
				<li class="header-bar-nav"> 
					<a href="javascript:;" title="换肤"><i class="icon-font">&#xe608;</i></a>
					<ul class="header-dropdown-menu right dropdown-skin">
						<li><a href="javascript:;" data-val="qingxin" title="清新">清新</a></li>
						<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
						<li><a href="javascript:;" data-val="molv" title="墨绿">墨绿</a></li>
						
					</ul>
				</li>
			</ul>
		</header>
		<aside class="layout-side">
			<ul class="side-menu">

			</ul>

		</aside>

		<div class="layout-side-arrow"><div class="layout-side-arrow-icon"><i class="icon-font">&#xe60d;</i></div></div>
		
		<section class="layout-main">
			<div class="layout-main-tab">
				<button class="tab-btn btn-left"><i class="icon-font">&#xe60e;</i></button>
                <nav class="tab-nav">
                    <div class="tab-nav-content">
                        <a href="javascript:;" class="content-tab active" data-id="newtg.php">首页</a>
                    </div>
                </nav>
                <button class="tab-btn btn-right"><i class="icon-font">&#xe60f;</i></button>
			</div>
			<div class="layout-main-body">
				<iframe class="body-iframe" name="iframe0" width="100%" height="99%" src="../newtg.php" frameborder="0" data-id="newtg.php" seamless></iframe>
			</div>
		</section>
		<div class="layout-footer">@lvweicheng</div>
	</div>
	<script type="text/javascript" src="../../common/lib/jquery-1.9.0.min.js"></script>
	<script type="text/javascript" src="../../common/js/city.js"></script>
    <script type="text/javascript" src="../../common/layui/layui.js"></script>
    <script>
        layui.use(['layer'], function() {
            var layer = layui.layer;
        })
        function logout(){
            $.ajax({
                url: "../doAction.php?act=logout",
                type: "POST",
                dataType:'json',
                success:function (msg) {
                    if(msg.err==0){
                        layer.open({
                            title: '提示'
                            ,content: '退出成功'
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
                    }
                }
            });
        }

    </script>
  </body>
</html>
