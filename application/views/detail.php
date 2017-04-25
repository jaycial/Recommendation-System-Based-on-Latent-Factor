<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>新闻首页</title>
	<link href="<?php echo base_url();?>front/css/detail.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="<?php echo base_url('front/js/detail.js')?>"></script>
</head>
<body>
	<div id="top">
		<div id="menu">
			<ul>
				<li><img src="<?php echo base_url('front/images/logo.png');?>"></li>
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
			<li>
				<?php echo $news_info->content?>
				<?php if(''!=$user_info):?>
					<?php if('1' == $like_status):?>
						<div class="blog-left">
							<p style="margin-top: 90px"><img id="like_pic" src="<?php echo base_url('front/images/like.png');?>" onclick="quit_like()" onmouseover="like_mouse_on('like_pic','<?php echo base_url();?>')" onmouseout="like_mouse_out('like_pic','<?php echo base_url();?>')"><span id="like_span">感兴趣</span><img id="dislike_pic" src="<?php echo base_url('front/images/per_dislike.png');?>" style="margin-left: 20px" onclick="dislike()" onmouseover="dislike_mouse_on('dislike_pic','<?php echo base_url();?>')" onmouseout="dislike_mouse_out('dislike_pic','<?php echo base_url();?>')"><span id="dislike_span"">无聊</span></p>
						</div>
					<?php elseif('-1' == $like_status):?>
						<div class="blog-left">
							<p style="margin-top: 90px"><img id="like_pic" src="<?php echo base_url('front/images/per_like.png');?>" onclick="like('<?php echo base_url();?>')" onmouseover="like_mouse_on('like_pic','<?php echo base_url();?>')" onmouseout="like_mouse_out('like_pic','<?php echo base_url();?>')"><span id="like_span">感兴趣</span><img id="dislike_pic" src="<?php echo base_url('front/images/dislike.png');?>" style="margin-left: 20px" onclick="quit_dislike()" onmouseover="dislike_mouse_on('dislike_pic','<?php echo base_url();?>')" onmouseout="dislike_mouse_out('dislike_pic','<?php echo base_url();?>')"><span id="dislike_span">无聊</span></p>
						</div>
					<?php else:?>
						<div class="blog-left">
							<p style="margin-top: 90px"><img id="like_pic" src="<?php echo base_url('front/images/per_like.png');?>" onclick="like()" onmouseover="like_mouse_on('like_pic','<?php echo base_url();?>')" onmouseout="like_mouse_out('like_pic','<?php echo base_url();?>')"><span id="like_span">感兴趣</span><img id="dislike_pic" src="<?php echo base_url('front/images/per_dislike.png');?>" style="margin-left: 20px" onclick="dislike()" onmouseover="dislike_mouse_on('dislike_pic','<?php echo base_url();?>')" onmouseout="dislike_mouse_out('dislike_pic','<?php echo base_url();?>')"><span id="dislike_span">无聊</span></p>
						</div>
					<?php endif?>
				<?php else:?>
					<div class="blog-left">
						<p style="margin-top: 90px"><img id="like_pic" src="<?php echo base_url('front/images/per_like.png');?>" onclick="jump_login('<?php echo site_url()?>')" onmouseover="like_mouse_on('like_pic','<?php echo base_url();?>')" onmouseout="like_mouse_out('like_pic','<?php echo base_url();?>')"><span id="like_span">感兴趣</span><img id="dislike_pic" src="<?php echo base_url('front/images/per_dislike.png');?>" style="margin-left: 20px" onclick="jump_login('<?php echo site_url()?>')" onmouseover="dislike_mouse_on('dislike_pic','<?php echo base_url();?>')" onmouseout="dislike_mouse_out('dislike_pic','<?php echo base_url();?>')"><span id="dislike_span">无聊</span></p>
					</div>
				<?php endif?>
			</li>
		</ul>
	</div>
</body>
</html>