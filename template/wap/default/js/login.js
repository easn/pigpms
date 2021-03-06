$(function(){
	var nowType = 'login';
	$('.js-login-phone,.js-login-pwd,.js-register-code').bind('input',function(){
	
		if(nowType == 'login'){
			checkShowLoginButton();
		}else{
			checkShowRegButton();
		}
	});
	$('.js-login-mode').click(function(){
		if(nowType == 'login'){
			nowType = 'reg';
			checkShowRegButton();
			$('.js-submit').html('注册');
			$(".dd-sms").show();
			$('.js-login-pwd').attr('placeholder','请设置至少6位的密码');
			$(this).html('已有账号');
			$('.js-login-title span').html('请先注册账号');
		}else{
			nowType = 'login';
			checkShowLoginButton();
			$('.js-submit').html('登录');
			$(".dd-sms").hide();
			$('.js-login-pwd').attr('placeholder','请填写您的密码');
			$(this).html('注册账号');
			$('.js-login-title span').html('请先登录账号');
		}
	});
	//发送短信
	
	
	$('.js-login-form').submit(function(){
		var dom = $(this);
		var phone = $('.js-login-phone').val();
		if(phone.length == 0){
			motify.log("请填写您的手机号码");
			return false;
		}else if(!/^[0-9]{11}$/.test(phone)){
			motify.log("请填写正确手机号码");
			return false;
		}
		var pwd = $('.js-login-pwd').val();
		if(pwd.length < 6){
			motify.log("亲，密码至少6位");
			return false;
		}

		if(nowType == 'login') {
			$.post('./login3.php',{phone:phone,pwd:pwd},function(result){
				if(result.err_code == 0){
					window.location.href = dom.attr('action');
				}else{
					motify.log(result.err_msg);
				}
			});
		}else{
		var sms_code = '';
		if(is_open_sms == '1') {
			var sms_code = $('.js-register-code').val();
			if(sms_code.length < 4){
				motify.log("亲，请输入短信验证码");
				return false;
			}
		}
			$.post('./login3.php?action=reg',{phone:phone,pwd:pwd,code:sms_code},function(result){
				if(result.err_code == 0){
					window.location.href = dom.attr('action');
				}else{
					motify.log(result.err_msg);
				}
			});
		}
		return false;
	});
});

function checkShowLoginButton(){
	if($.trim($('.js-login-phone').val()).length > 0 && $.trim($('.js-login-pwd').val()).length > 0){
		$('.js-submit').prop('disabled',false);
		return false;
	}else{
		$('.js-submit').prop('disabled',true);
		return true;
	}
}
function checkShowRegButton(){
	if($.trim($('.js-login-phone').val()).length > 0 && $.trim($('.js-login-pwd').val()).length > 0   ){
		
		if(is_open_sms == '1') {
			var js_register_code = $.trim($(".js-register-code").val());
			if(/^[0-9]{4}$/.test(js_register_code)) {
				$('.js-submit').prop('disabled',false);
				
			} else {
				$('.js-submit').prop('disabled',true);
			}
		} else {
			$('.js-submit').prop('disabled',false);
		}
		
		return false;
		
		
	}else{
		$('.js-submit').prop('disabled',true);
		return true;
	}
}