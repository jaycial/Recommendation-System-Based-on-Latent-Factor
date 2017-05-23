function never_show(news_id,user_id,site_url){
	var con = confirm('确定不再显示此条新闻？');
	if(con){
		// 隐藏
		var class_name=".news_info"+news_id;
		$(class_name).hide(1000);

		// 改变Item评分
		$.post(site_url+"/home/ajax_never_show",{news_id:news_id,user_id:user_id},function(status){
			if(status){
				// TODO  可用于记录错误
				return;
			}
		});
	}else{
		return;
	}
}