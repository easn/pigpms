<?php if(!defined('PIGCMS_PATH')) exit('deny access!');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员等级 - <?php echo $now_store['name'];?></title>
<meta name="keywords" content="<?php echo $config['seo_keywords'];?>" />
<meta name="description" content="<?php echo $config['seo_description'];?>" />
<link rel="icon" href="<?php echo $config['site_url'];?>/favicon.ico" />
<meta name="HandheldFriendly" content="true"/>
<meta name="MobileOptimized" content="320"/>
<meta name="format-detection" content="telephone=no"/>
<meta http-equiv="cleartype" content="on"/>		
<link rel="stylesheet" href="<?php echo TPL_URL;?>css/base.css"/>
<link rel="stylesheet" href="<?php echo TPL_URL;?>css/degree.css"/>
<?php if($is_mobile){ ?>
	<link rel="stylesheet" href="<?php echo TPL_URL;?>css/showcase.css"/>
<?php }else{ ?>
	<link rel="stylesheet" href="<?php echo TPL_URL;?>css/showcase_admin.css"/>
<?php } ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<script src="<?php echo $config['site_url'];?>/static/js/jquery.min.js"></script>
<script src="<?php echo TPL_URL;?>js/base.js"></script>
<script src="<?php echo $config['site_url'];?>/static/js/jquery.waterfall.js"></script>
<script src="<?php echo $config['site_url'];?>/static/js/idangerous.swiper.min.js"></script>
<script>
$(function(){
	 var a=$(window).width();
	 var b=$(".member_content").width();
	 $(".member_content").css("left",(a-b)/2);
	 $(".member_banner").css("height",(a*0.4));

	 $(".grade li").click(function(){
		$(".p_card_div").hide();
		$(this).find(".p_card_div").show();

	})

	$('.content2').css('min-height',$(window).height()-$('.header2').height()-$('.js-footer').height()-6+'px');
})
</script>
<style>
.p_card_div{
	clear:both;
	display:none;
}
 .p_card {
  clear: both;
  padding: 10px;
  font-size: 10px;
  font-weight: 700;
}
.p_card2 {
  clear: both;
  padding: 10px;
  margin: 5px;
  font-size: 10px;
  border-top: 1px solid #ccc;
  line-height: 18px;
}
</style>
</head>

<body>
<div class="container2">

<div class="member">
	
	<div class="header2">
		<div class="member_banner">
			<div class="member_content">
			<!--<div class="member_img"><img src="<?php echo TPL_URL;?>images/degree_logo.png"  class="rotateIn"/></div>-->
				<div class="member_img"><img src="<?php echo $avator;?>"  class="rotateIn"/></div>			
				<p><?php echo $userinfo['nickname'];?></p>
				<div class="member_but"><?php echo $userDegree['name'];?></div>
			</div>
		</div>
		<ul class="tab_txt clearfix">
			<li>积分:<span><?php  if($user_point_info['point']) {echo $user_point_info['point'];}else { echo "0";}?></span>分</li>
			<li>消费(￥<?php  echo (int)$user_point_info['money'];?>)</li>
			<li style="border:0"><a  href="./ucenter.php?id=<?php echo $_GET['id'];?>">返回个人中心</a></li>
		</ul>
	</div>
	
	<ul class="grade content2">
		<?php if(is_array($store_tag_list) && count($store_tag_list)>0) {?>
			<?php foreach($store_tag_list as $k => $v) {?>
			<li class="clearfix">
				<div class="grade_img"><img src="<?php echo $v['new_level_pic'];?>" /></div>
				<div class="grade_txt">
					<p class="title"><?php echo $v['name'];?><span>(<?php echo $v['points_limit'];?>以上积分)</span></p>
					<p><?php if($v['discount']>0 || $v['is_postage_free']){?>可以享受<?php if($v['discount']>0){?>每件商品打<?php echo $v['discount'];?>折的优惠<?php }?><?php if($v['is_postage_free']){?><?php if($v['discount']>0 && $v['discount']<10){?>并<?php }?>包邮<?php }?><?php }else{?>暂无其他优惠，敬请期待！<?php }?></p>
				</div>
				<div class="p_card_div"><p class="p_card">使用须知： </p><p class="p_card2" ><?php if($v['description']) {?><?php echo $v['description'];?><?php }else{?>暂无说明！<?php }?></p></div>
			</li>
			<?php }?>
		<?php } else {
		?>
		<li>
			<div class="empty-list list-finished" style="padding-top:60px;display:;">
						<div>
							<h4>居然还没有设置店铺等级</h4>
							<p class="font-size-12">&nbsp;</p>
							
						</div>
						<div><a href="<?php echo $now_store['url'];?>" class="tag tag-big tag-orange" style="padding:8px 30px;">去逛逛</a></div>
					</div>
		
		</li>
		<?php }?>
	</ul>
</div>
<?php if(!empty($storeNav)){ echo $storeNav;}?>
<?php include display('footer');?>
<?php echo $shareData;?>
</div>
</body>
</html>
<?php Analytics($now_store['store_id'], 'degree', '会员等级', $now_store['store_id']); ?>