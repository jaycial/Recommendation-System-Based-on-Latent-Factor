<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>新闻首页</title>
	<link href="<?php echo base_url();?>front/css/detail.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div id="top">
		<div id="menu">
			<ul>
				<li><img src=http://img.php.cn/upload/course/000/000/004/58171021ac1f3460.png ></li>
				<li><a href="<?php echo site_url('home/index?type=1');?>">军事</a></li>
				<li><a href="<?php echo site_url('home/index?type=2');?>">旅游</a></li>
				<li><a href="<?php echo site_url('home/index?type=3');?>">科技</a></li>
				<li><a href="<?php echo site_url('home/index?type=4');?>">教育</a></li>
				<li><a href="<?php echo site_url('home/index?type=5');?>">娱乐</a></li>
				<li><a href="<?php echo site_url('home/index?type=6');?>">财经</a></li>
				<?php if(''!=$user_info):?>
					<li class="mi" ><a href="#"><?php echo $user_info->username;?></a>/<a href="<?php echo site_url('home/logout'); ?>">登出</a></li>
				<?php else:?>
					<li class="mi" ><a href="<?php echo site_url('home/login'); ?>">登陆</a>/<a href="<?php echo site_url('home/register'); ?>">注册</a></li>
				<?php endif?>
			</ul>
		</div>
	</div>

	<div id="blog">
		<?php echo $news_info->content?>
	</div>
</body>
</html>