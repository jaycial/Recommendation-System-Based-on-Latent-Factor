function like_mouse_on(id,base_url){
	var me = document.getElementById(id);
	me.src=base_url+"/front/images/like.png";
}

function like_mouse_out(id,base_url){
	var me = document.getElementById(id);
	me.src=base_url+"/front/images/per_like.png";
}

function dislike_mouse_on(id,base_url){
	var me = document.getElementById(id);
	me.src=base_url+"/front/images/dislike.png";
}

function dislike_mouse_out(id,base_url){
	var me = document.getElementById(id);
	me.src=base_url+"/front/images/per_dislike.png";
}

function jump_login(site_url){
	window.open(site_url+'home/login');
}

function like(news_id,user_id,site_url) {
	$.post(site_url+"home/ajax_add_like",{news_id:news_id,user_id:user_id},function(html){
		$("#user_interest").html(html);
	});
}

function dislike(news_id,user_id,site_url) {
	$.post(site_url+"home/ajax_add_dislike",{news_id:news_id,user_id:user_id},function(html){
		$("#user_interest").html(html);
	});
}



