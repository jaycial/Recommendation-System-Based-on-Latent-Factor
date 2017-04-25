<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>新闻首页</title>
	<link href="<?php echo base_url();?>front/css/home.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div id="top">
		<div id="menu">
			<ul>
				<li><img src="<?php echo base_url('front/images/logo.png');?>" ></li>
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
		<ul>
			<?php foreach ($news_info as $value): ?>
				<li>
					<div class="blog-left">
						<p ><a href="<?php echo site_url('home/detail?id='.$value->news_id); ?>" class="title"><?php echo $value->title;?></a></p>
						<p style="margin-top: 20px"><?php echo $value->abstract;?></p>
						<p style="margin-top: 90px"><img src="<?php echo base_url('front/images/rep.png');?>" ><?php echo $value->from;?><img src="<?php echo base_url('front/images/time.png');?>" style="margin-left: 20px"><?php echo $value->source_time;?></p>
					</div>
					<div class="blog-right">
						<img src="<?php echo $value->img_url?>">
					</div>
				</li>
			<?php endforeach ?>
		</ul>
		<?php if ($total>$per_page): ?>
			<div class="page">
				<a href="<?php echo site_url('home/index?type='.$type);?>">首页</a> <a href="<?php echo site_url('home/index?type='.$type.'&page='.($page+1))?>">上一页</a> <a href="#"><?php echo $page?></a> <a href="<?php echo site_url('home/index?type='.$type.'&page='.($page+1))?>">下一页</a> <a href="<?php echo site_url('home/index?type='.$type.'&page='.$total_page)?>">尾页</a>
			</div>
		<?php endif ?>
	</div>
</body>
</html>