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

function like(parame) {
	alert(parame);
}
