<html>
<head>
<title>用户注册</title>
<link href="<?php echo base_url();?>front/css/login_reg.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="login">
	<h2>新闻推荐系统</h2>
	<div class="login-top">
		<h1>用户注册</h1>
		<form method="post" action="<?php echo site_url('home/register')?>">
			<input type="text" name="email" placeholder="Email">
			<input type="text" name="username" placeholder="User Name">
			<input type="password" name="password" placeholder="Pass Word">
			<input type="password" name="cpassword" placeholder="Confirm Your Pass Word">
		    <div class="forgot">
		    	<a href="#">用户协议</a>
		    	<input type="submit" value="注册" >
		    </div>
	    </form>
	</div>
	<div class="login-bottom">
		<h3>已有账号 &nbsp;<a href="<?php echo site_url('home/login');?>">点此</a>&nbsp 登录</h3>
	</div>
</div>	
<div class="copyright">
	<p>Copyright &copy; 2015.Company name All rights reserved
</div>


</body>
</html>