<html>
<head>
<title>用户登录</title>
<link href="<?php echo base_url();?>front/css/login_reg.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="login">
	<h2>新闻推荐系统</h2>
	<div class="login-top">
		<h1>用户登录</h1>
		<form method="post" action="<?php echo site_url('home/login')?>">
			<input type="text" name='email' placeholder="Email">
			<input type="password" name='password' placeholder="password">
		    <div class="forgot">
		    	<a href="#">忘记密码</a>
		    	<input type="submit" value="登录" >
		    </div>
	    </form>
	</div>
	<div class="login-bottom">
		<h3>新用户 &nbsp;<a href="<?php echo site_url('home/register');?>">点此</a>&nbsp 注册</h3>
	</div>
</div>	
<div class="copyright">
	<p>Copyright &copy; 2015.Company name All rights reserved
</div>


</body>
</html>