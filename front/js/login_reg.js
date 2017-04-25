$(document).ready(function(){
	$("#email").blur(function(){
		var email=$("#email").val();
		// 验证邮箱格式
		var reg = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+.[a-zA-Z0-9_-]+$/; 
		if(!reg.test(email)){
			var mes="请输入正确的邮箱";
			$("#message").val(mes);
			$("#message").css('display','block'); 
			$("#email").css('border','solid 1px red'); 
		}else{
			$.post("ajax_check_email_unique",{email:email},function(is_unique){
				if(!is_unique){
					var mes="该邮箱已被占用";
					$("#message").val(mes);
					$("#message").css('display','block'); 
					$("#email").css('border','solid 1px red'); 
				}else{
					$("#message").css('display','none'); 
					$("#email").css('border','0px'); 
				}
			});
		}
	});

	$("#username").blur(function(){
		var username=$("#username").val();
		$.post("ajax_check_username_unique",{username:username},function(is_unique){
			if(!is_unique){
				var mes="该用户名已被注册";
				$("#message").val(mes);
				$("#message").css('display','block'); 
				$("#username").css('border','solid 1px red');  
			}else{
				$("#message").css('display','none'); 
				$("#username").css('border','0px'); 
			}
		});
	});

	$("#cpswd").blur(function(){
		var password=$("#pswd").val();
		var cpassword=$("#cpswd").val();
		if(password != cpassword){
			var mes="两次输入的密码不一致";
			$("#message").val(mes);
			$("#message").css('display','block'); 
			$("#pswd").css('border','solid 1px red'); 
			$("#cpswd").css('border','solid 1px red'); 
		}else{
			$("#message").css('display','none'); 
			$("#pswd").css('border','0px'); 
			$("#cpswd").css('border','0px'); 
		}
	});
});