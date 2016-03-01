<?php if(!defined('PIGCMS_PATH')) exit('deny access!');?>
<html class="no-js" lang="zh-CN">
<head>
    <meta charset="utf-8"/>
	<title>登录</title>
	<meta name="keywords" content="<?php echo $config['seo_keywords'];?>" />
	<meta name="description" content="<?php echo $config['seo_description'];?>" />
	<meta name="HandheldFriendly" content="true"/>
	<meta name="MobileOptimized" content="320"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta http-equiv="cleartype" content="on"/>
	<link rel="icon" href="<?php echo $config['site_url'];?>/favicon.ico" />	
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link rel="stylesheet" href="<?php echo TPL_URL;?>css/base.css?time='<?php echo time();?>'"/>
	<link rel="stylesheet" href="<?php echo TPL_URL;?>css/login.css?time='<?php echo time();?>'"/>
	<script>var is_open_sms = "<?php echo $is_used_sms;?>"</script>
	<script src="<?php echo $config['site_url'];?>/static/js/jquery.min.js"></script>	
	<script src="<?php echo TPL_URL;?>js/base.js"></script>
	<script src="<?php echo TPL_URL;?>js/login.js?time='<?php echo time();?>'"></script>
	<style>
		#login{margin: 0.5rem 0.2rem;}
		.btn-wrapper{margin:.28rem 0;}
		dl.list{border-bottom:0;border:1px solid #ddd8ce;}
		dl.list:first-child{border-top:1px solid #ddd8ce;}
		dl.list dd dl{padding-right:0.2rem;}
		dl.list dd dl>.dd-padding, dl.list dd dl dd>.react, dl.list dd dl>dt{padding-right:0;}
	    .nav{text-align: center;}
	    .subline p{text-align:center}
	    .captcha img{margin-left:.2rem;}
	    .captcha .btn{margin-top:-.15rem;margin-bottom:-.15rem;margin-left:.2rem;}
		.subline p a{color:#00a0f8 !important}
	</style>
	
	
</head>
<body id="index" data-com="pagecommon">
        <div id="container">
        	<div id="tips" style="-webkit-transform-origin:0px 0px;opacity:1;-webkit-transform:scale(1, 1);"></div>
			<div id="login">
			    <form class="js-login-form " name="form_register_login" method="GET" action="<?php echo $redirect_uri;?>">
			        <dl class="list list-in block block-form margin-bottom-normal">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<input id="phone" class="input-weak js-login-phone" type="tel" placeholder="请输入您的手机号" name="phone" value="" required="">
			            		</dd>
			            		<dd class="dd-padding">
			            			<input class="input-weak js-login-pwd" id="password" type="password" name="password" placeholder="请填写您的密码" name="phone" value="" required="">
			            		</dd>
<!--								
								<?php if($is_used_sms == '1') {?>		
			            		<dd class="kv-line-r dd-padding dd-sms" style="display:none">
			            			<input id="pwd_password" class="input-weak kv-k js-register-code" type="text"  maxlength="4" placeholder="输入短信验证码"/>
			            			<input type="hidden" id="password_type" value="0"/>
			            			<button id="changeWord" type="button" class="btn btn-weak kv-v sendToPhone get-code">发送短信</button>
			            		</dd>
								<?php }?>
								-->
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
						<button type="submit" class="btn btn-larger btn-block js-submit btn btn-green">登录</button>
			        </div>
			    </form>
			</div>
			<div class="subline">
			    <p><a class="js-login-mode c-blue" href="javascript:;">注册账号</a></p>
			</div>
		</div>


		<div style="height:10px"></div>

		
<script type="text/javascript">

var check_register_url = "./login3.php?action=checkuser";
var returns;

$(function(){

	//获取短信验证码
	var validCode3 = true;
	//刷新页面 获取时间
		//从sms_register 获取的时间戳
		var time1 = "<?php echo $sms_register['timestamp']?$sms_register['timestamp']:0;?>";
		var time2 = "<?php echo time();?>";
		var sysj = parseInt(time1)-parseInt(time2)+120; 	//剩余时间
		if(sysj<0) {

		} else {
			$(".get-code").html(sysj+"秒后重新获取");
			var code1=$(".get-code");
			if (validCode3) {
				validCode3=false;
				code1.addClass("msgs1");
				var t1=setInterval(function  () {
					sysj--;
				code1.html(sysj+"秒后重新获取");
				if (sysj==0) {
					clearInterval(t1);
					code1.html("获取短信验证码");
					validCode3=true;
					//validCode1=true;
				code1.removeClass("msgs1");

				}
			},1000)
			}
			
		}
		
})




$(function  () {
	//防刷新 验证码重获
	//getCookie("register_mobile_code");


/////////////////	
	//获取短信验证码
	var validCode=true;
	//标识是否已经点击
	var validCode1=true;

	$(".get-code").click(function(event) {
		
		event.stopPropagation();

		var time=120;
		var code=$(this);
		if(validCode1) {
			
			//检测是否已经注册
			var mobile = $("#phone").val();
			if(!mobile) {
				motify.log("请正确填写手机号,再获取验证码");
				return true;
			}
			if(document.form_register_login.phone.value.match(/^(1)[0-9]{10}$/ig)==null) {
				document.form_register_login.phone.focus();
				motify.log("手机号码输入错误，请返回重新输入");
				return false;
			}
		
			if(!validCode) return false;
			$.post(check_register_url,{'is_ajax':'1','mobile':mobile},function(data){

				
					if(data.status>0) {
						motify.log(data.msg);
						returns = '0';
					} else {
						//可以注册
						//alert('可以注册')
						returns = '1';
						validCode1 = false;

							if (validCode) {
								try{
									clearInterval(t1);
								}catch(e){

								}
								validCode=false;
								code.addClass("msgs1");
								var t=setInterval(function  () {
								time--;
								code.html(time+"秒后重新获取");
								code.attr("disabled",true);
								if (time==0) {
									clearInterval(t);
								code.html("获取短信验证码");
								code.attr("disabled",false);
									validCode=true;
									validCode1=true;
								code.removeClass("msgs1");
				
								}
							},1000)
							}
					}
				},
				'json'
			)










			

		}	
	})




//检测是否已经注册
function checkregister(mobile) {
	returns = 0;
	$.post(check_register_url,{'is_ajax':'1','mobile':mobile},function(data){

			if(data.status>0) {
				layer.tips(data.msg);
				 returns = '0';
			} else {
				//可以注册
				//alert('可以注册')
				 returns = '1';
				 validCode1 = false;
				alert('可以注册');
				alert(returns)
				return true;
				
			}
	},
	'json'
	)
	return true;
}




})
</script>
	</body>
</html>