<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>收货地址</title>
		<meta content="app-id=518966501" name="apple-itunes-app" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0"/>
		<meta content="yes" name="apple-mobile-web-app-capable" />
		<meta content="black" name="apple-mobile-web-app-status-bar-style" />
		<meta content="telephone=no" name="format-detection" />
		<link href="http://demo.pigcms.cn/tpl/static/unitary/css/comm.css" rel="stylesheet" type="text/css" />
		<link href="http://demo.pigcms.cn/tpl/static/unitary/css/login.css" rel="stylesheet" type="text/css" />
		<style>
		.registerCon li textarea {
			padding: 15px;
			width: 100%;
			height: 90px;
			color: #333;
			border: 1px solid #dedede;
			border-radius: 5px;
			margin-top: -1px;
			font-size: 16px;
			-webkit-box-sizing: border-box;
		}
		.registerCon li input {
			padding: 15px;
			width: 100%;
			height: 45px;
			color: #333;
			border: 1px solid #dedede;
			border-radius: 5px;
			margin-top: -1px;
			font-size: 16px;
			-webkit-box-sizing: border-box;
		}
		</style>
	</head>
	<body>
		<script src="http://demo.pigcms.cn/tpl/static/unitary/js/jquery-2.1.3.min.js" language="javascript" type="text/javascript"></script>
		
		<div class="wrapper">
			<div class="registerCon">
				<ul>
					<li class="accAndPwd">
						<dl>
							昵称：
							<input id="name" maxlength="11" type="text" placeholder="请输入您的昵称" value="<?php echo $user_address['name'];?>"/>
						</dl>
					</li>
					<li class="accAndPwd">
						<dl>
							手机号码：
							<input id="phone" maxlength="11" type="tel" placeholder="请输入您的手机号码" value="<?php echo $user_address['phone'];?>"/>
						</dl>
					</li>
					<li class="accAndPwd">
						<dl>
							确认收货地址：
							<textarea id="address" placeholder="请填写您的收货地址"><?php echo $user_address['address'];?></textarea>
						</dl>
					</li>
					<input type="hidden" id="address_id" value="<?php echo $user_address['id'];?>">
					<li><a id="btnNext" class="orangeBtn loginBtn">下一步</a></li>
				</ul>
			</div>
		</div>
		<script type="text/javascript">
			$(function(){
				/*delorder();*/
				$("#btnNext").click(function(){
					var address = $("#address").val();
					var phone = $("#phone").val();
					var phone_length = phone.length;
					var phone_str = phone.substr(0,1);
					var name = $("#name").val();
					var id = $("#address_id").val();
					if(address == ""){
						alert("请填写您的收货地址");
					}else if(phone == ""){
						alert("请填写您的手机号码");
					}else if(phone_length != 11 || phone_str != '1'){
						alert("请填写正确的手机号码");
					}else{
						//更新
						$.ajax({
							type:"POST",
							url:"unitary.php?t=indexajax",
							dataType:"json",
							data:{
								type:'doaddress',
								id:id,
								address:address,
								phone:phone,
								name:name,
							},
							success:function(data){
								if(data.ok == 1){
									window.location.href="unitary.php?t=dobuy";
								}
							}
						});
					}
				})
			})
			
			function delorder(){
				$.ajax({
					type:"POST",
					url:"{pigcms::U('Unitary/cartajax',array('token'=>$token))}",
					dataType:"json",
					data:{
						type:'delorder',
						token:"{pigcms:$token}",
						wecha_id:"{pigcms:$wecha_id}"
					},
					success:function(data){
						if(data.error == 1){
							//alert("支付失败");
							window.location.href="{pigcms::U('Unitary/cart',array('token'=>$token))}";
						}
					}
				});
				//setTimeout("delorder()",3000);
			}
		</script>

<if condition="$unitary eq ''">
<script type="text/javascript">
window.shareData = {  
            "moduleName":"Unitary",
            "moduleID":"0",
            "imgUrl": "{pigcms:$staticPath}/tpl/static/unitary/images/wxnewspic.jpg", 
            "sendFriendLink": "{pigcms:$f_siteUrl}{pigcms::U('Unitary/index',array('token'=>$token))}",
            "tTitle": "一元夺宝",
            "tContent": ""
        };
</script>
<else />
<script type="text/javascript">
window.shareData = {  
            "moduleName":"Unitary",
            "moduleID":"0",
            "imgUrl": "{pigcms:$unitary['wxpic']}", 
            "sendFriendLink": "{pigcms:$f_siteUrl}{pigcms::U('Unitary/goodswhere',array('token'=>$token,'unitaryid'=>$_GET['unitaryid']))}",
            "tTitle": "{pigcms:$unitary['name']}",
            "tContent": "{pigcms:$unitary['wxinfo']}"
        };
</script>
</if>
	</body>
</html>