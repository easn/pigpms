<link href="<?php echo TPL_URL;?>css/public.css" type="text/css" rel="stylesheet">
<script type="text/javascript">
//延迟加载图片
		$(function(){
			
			$.get("/index.php?c=index&a=user", function (data) {
				try {
					if (data.status == true) {

						var login_info = '<li>Hi，<a href="<?php echo url('account:index') ?>" class="header_login_cur"><em>' + data.data.nickname + '</em></a></li>';
						login_info += '<li><a target="_top" href="index.php?c=account&a=logout" class="sn-register">退出</a></li>';


						$("#login-info").html(login_info);

						$("#header_cart_number").html(data.data.cart_number);
						$(".mui-mbar-tab-sup-bd").html(data.data.cart_number);
					}
				} catch (e) {

				}
			}, "json");
		})
</script>
<style>
.mui-mbar-tab-sup-bg { background-color: #c40000; border-radius: 10px; margin: -59px 37px; position: absolute; z-index: 1111111; }
.mui-mbar-tab-sup-bd, .mui-yuan { border-radius: 10px; font-size: 12px; height: 20px; line-height: 20px; min-width: 14px; padding: 0 3px; color: #fff; }
.right-red-radius { background-color: #cc0000; border-radius: 10px; }
#leftsead { /*display: none;*/ height: 290px; position: fixed; right: 0; top: 350px; width: 62px; z-index: 100; }
.orangeBtn { padding: 0 }
</style>



<style>

</style>

<script>
$(function(){
	$("#shortcut-2014 .fr li").hover(function(){
		if($(this).find(".cw-icon")) {
			$(this).removeClass("dorpdown").addClass("hover");
		}
	},function(){
		if($(this).find(".cw-icon")) {
			$(this).removeClass("hover").addClass("dorpdown");;
		}
	})
	
})
</script>
<div class="header">
			<div id="shortcut-2014">
				<div class="ws">
					<ul  class="fl">
						<li  id="ttbar-login" class=" dorpdowns">
							<div class="dt cw-icon"><i class="ci-left"></i></div>
							<?php if(empty($user_session)){?>
								
									Hi，欢迎来 <?php echo option('config.site_name');?>&nbsp;<a class="link-login style-red" target="_top" href="<?php echo url('account:login') ?>">请登录</a>&nbsp;&nbsp;
									<a class="link-regist style-red"  target="_top" href="<?php echo url('account:register') ?>" >免费注册</a>	
							<?php }else{?>	
									你好，<a class="link-login" href="<?php echo url('account:index') ?>" ><?php echo $user_session['nickname'];?>&nbsp;&nbsp;
									<a class="link-regist style-red" target="_top" href="<?php echo url('account:logout') ?>">退出</a>							
							<?php }?>
						</li>	
					</ul>
					<ul class="fr">
						<li  id="ttbar-gwc" class="fore2">
							<div class="dt cw-icon">
								<i class="ci-left"></i>
								<a href="<?php echo url('cart:one') ?>" target="_blank">购物车
								<span class="mc-count mc-pt3" id="header_cart_number">0</span> 件</a>
							</div>
						</li>
						<li class="spacer"></li>
						
						<li  class="fore3 dorpdown" >
							<div class="dt cw-icon">
									<i class="ci-right"></i>
									<a href="<?php echo url('account:order') ?>" target="_blank">我的订单</a>
								</div>
						</li>
						<li class="spacer"></li>
						
						<li id="ttbar-servs2"  class="fore4 dorpdown">
							<div class="dt cw-icon">
								<i class="ci-right"><s>◇</s></i>
								<a href="<?php echo url('account:index') ?>" target="_blank">我的账户</a>
							</div>
							<div class="dd dorpdown-layer">
								<div class="dd-spacer"></div>
								<div class="item"><a target="_blank" href="<?php echo url('account:index') ?>">个人设置</a></div>
								<div class="item"><a target="_blank" href="<?php echo url('account:password') ?>">修改密码</a></div>
								<div class="item"><a target="_blank" href="<?php echo url('account:address') ?>">收货地址</a></div>
							</div>
						</li>
						<li class="spacer"></li>
						
						<li id="ttbar-servs"  class="fore5 dorpdown">
							<div class="dt cw-icon">
								<i class="ci-right"><s>◇</s></i>我的收藏
							</div>
							<div class="dd dorpdown-layer">
								<div class="dd-spacer"></div>
								<div class="item"><a target="_blank" href="<?php echo url('account:collect_goods') ?>">收藏的宝贝</a></div>
								<div class="item"><a target="_blank" href="<?php echo url('account:collect_store') ?>">收藏的店铺</a></div>
							</div>
						</li>
						<li class="spacer"></li>
						
						<li  id="ttbar-apps" class="fore6 dorpdown"  data-load="1">
								<div class="dt cw-icon">
									<i class="ci-left"></i>
									<i class="ci-right"><s>◇</s></i><!--扫一扫，定制我的微店！-->
									<a href="javascript:void(0)" target="_blank">&nbsp;&nbsp;微信版&nbsp;&nbsp;</a>
								</div>
								<div style="" class="dd dorpdown-layer">				
									<div class="dd-spacer"></div>				
									<div id="ttbar-apps-main" class="dd-inner" >
										<img src="<?php echo option('config.wechat_qrcode');?>" width="150px" height="150px">
										<p><b>扫一扫，定制我的微店！</b></p>
									</div>			
								</div>		
						</li>
						<li class="spacer"></li>

						
						<li  id="ttbar-serv" class="fore8 dorpdown" data-load="1">
							<div class="dt cw-icon">
								<i class="ci-right"><s>◇</s></i>卖家中心
							</div>
							<div class="dd dorpdown-layer">
								<div class="dd-spacer"></div>
								<div class="item"><a target="_blank" href="<?php echo url('user:store:select') ?>">我的店铺</a></div>
								<div class="item"><a target="_blank" href="<?php echo url('user:store:index') ?>">管理店铺</a></div>
							</div>
						</li>

					</ul>
					<span class="clr"></span>
				</div>
			</div>
	
	
	
	
	
	
	
	<div class="header_nav">
		<div class="header_logo cursor"
			onclick="javascript:location.href='<?php echo option('config.site_url');?>'"> <img src="<?php echo $config['site_logo'] ?>"> </div>
		<div class="header_search">
			<form class="pigSearch-form clearfix" onsubmit="return false"
				name="searchTop" action="" target="_top">
				<input type="hidden" name="st" id="searchType" value="product" />
				<div class="header_search_left"> <font>商品</font><span></span>
					<div class="header_search_left_list">
						<ul>
							<li listfor="product"
								<?php if(MODULE_NAME != 'search' && ACTION_NAME != 'store'){echo 'selected="selected"';}?>><a
								href="javascript:">商品</a></li>
							<li listfor="shops"
								<?php if(MODULE_NAME == 'search' && ACTION_NAME == 'store'){echo 'selected="selected"';}?>><a
								href="javascript:void(0)">店铺</a></li>
						</ul>
					</div>
				</div>
				<div class="header_search_input">
					<input class="combobox-input" name="" class="input" type="text"
						placeholder="请输入商品名、称地址等" value="<?php echo $searchKeyword;?>">
				</div>
				<div class="header_search_button sub_search">
					<button> <span></span> 搜索 </button>
				</div>
				<div style="clear: both"></div>
			</form>
			<ul class="header_search_list">
				<?php
			  if(count($search_hot)) {?>
				<?php foreach($search_hot as $k=>$v) {?>
				<li <?php if($v['type']){echo 'class="hotKeyword"';}?>><a
					href="<?php echo $v['url']?>"><?php echo $v['name'];?></a></li>
				<?php }?>
				<?php }?>
			</ul>
		</div>
		<div class="header_shop">
			<?php if(!empty($public_top_ad)){?>
			<a href="<?php echo $public_top_ad['url'];?>"><img
				src="<?php echo $public_top_ad['pic'];?>"></a>
			<?php } ?>
		</div>
	</div>
</div>
<div class="nav">
	<div class="nav_top">
		<div class="nav_nav">
			<div class="nav_nav_mian"> <span></span>所有商品分类<span class="xianshi"></span> </div>
			<ul class="nav_nav_mian_list">
				<?php foreach($categoryList as $v) {?>
				<li><a href="<?php echo url_rewrite('category:index',array('id'=>$v['cat_id']))?>">
						<span class="woman" style="background:url('<?php echo $v[cat_pc_pic]?>')"></span>
						<?php echo $v['cat_name']?>
					</a>
					<div class="nav_nav_subnav">
						<div class="nav_nav_mian_list_left">
							<dl>
								<dt> <a
										href="<?php echo url_rewrite('category:index',array('id'=>$v['cat_id']))?>"><?php echo $v['cat_name']; ?></a> </dt>
								<?php if($v['larray']){ ?>
								<?php foreach($v['larray'] as $k1=>$v1) { ?>
								<dd> <a
										href="<?php echo url_rewrite('category:index',array('id'=>$v1['cat_id']))?>"><?php echo $v1['cat_name']?></a> </dd>
								<?php } ?>
								<?php }?>
							</dl>
						</div>
					</div>
				</li>
				<?php } ?>
			</ul>
		</div>
		<ul class="nav_list">
			<li><a href="/">首页</a></li>
			<?php
			foreach($navList as $k => $v) {
				$class = '';
				$param = explode('/', $v['url']);
				
				if ($_GET['id'] == $param[count($param) - 1]) {
					$class = 'nav_list_curn';
				}
			?>
			<li>
				<a href="<?php echo $v['url'];?>" class="<?php echo $class ?>"><?php echo $v['name'] ?></a></li>
			<?php
			}
			?>
		</ul>
	</div>
</div>
