<?php if(!defined('PIGCMS_PATH')) exit('deny access!');?>
<!DOCTYPE html>
<html class="no-js" lang="zh-CN">
	<head>
		<meta charset="utf-8"/>
		<meta name="keywords" content="<?php echo $config['seo_keywords'];?>" />
		<meta name="description" content="<?php echo $config['seo_description'];?>" />
		<meta name="HandheldFriendly" content="true"/>
		<meta name="MobileOptimized" content="320"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta http-equiv="cleartype" content="on"/>
		<link rel="icon" href="<?php echo $config['site_url'];?>/favicon.ico" />
		<title><?php echo $nowOrder['status_txt'];?>的订单</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="<?php echo TPL_URL;?>css/base.css"/>
		<link rel="stylesheet" href="<?php echo TPL_URL;?>css/trade.css"/>
		<script src="<?php echo $config['site_url'];?>/static/js/jquery.min.js"></script>
		<script src="<?php echo TPL_URL;?>js/base.js"></script>
		<script>var orderNo='<?php echo $nowOrder['order_no_txt'];?>';</script>
		<script src="<?php echo TPL_URL;?>js/order_paid.js"></script>
	</head>
	<body>
		<div class="container js-page-content wap-page-order">
			<div class="content confirm-container">
				<div class="app app-order">
					<div class="app-inner inner-order" id="js-page-content">
						<!-- 订单状态 -->
						<div class="important-message">
							<!-- 客户看 -->
							<h3>
								订单状态：
								<?php 
								if ($nowOrder['shipping_method'] == 'selffetch') {
									if ($nowOrder['status'] <= 1) {
										echo '临时订单';
									} else if ($nowOrder['status'] == 2) {
										echo '未' . ($now_store['buyer_selffetch_name'] ? $now_store['buyer_selffetch_name'] : '到店消费');
									} else {
										echo $nowOrder['status_txt'];
									}
								} else {
									echo $nowOrder['status_txt'];
								}
								if($nowOrder['payment_method'] == 'codpay') {
								?>
									支付方式：货到付款
								<?php
								} else if ($nowOrder['payment_method'] == 'peerpay') {
								?>
									支付方式：找人代付
								<?php
								}
								?>
								<a href="javascript:;" class="js-open-share c-blue pull-right hide">分享</a>
							</h3>
							<hr/>
							<p class="c-orange">请收藏该页面地址，方便查询订单状态。</p>
						</div>
                        <!-- 商品列表 -->
						<div class="block block-order block-border-top-none">
							<div class="header">
								<span>店铺：<?php echo $now_store['name'];?></span>
							</div>
							<hr class="margin-0 left-10"/>
							<style>
							.return_btn {margin-top:16px; margin-left:5px; border:1px solid #f60; padding:0px 2px;border-radius:3px; color:#fff; background:#f60}
							.detail .return_btn {color:#FFF;}
							</style>
							<div class="block block-list block-border-top-none block-border-bottom-none">
								<?php
								$config_order_return_date = option('config.order_return_date');
								$config_order_complete_date = option('config.order_complete_date');
								foreach($nowOrder['proList'] as $value){
									$is_return = false;
									if ($nowOrder['status'] == '7') {
										if ($nowOrder['delivery_time'] + $config_order_return_date * 24 * 3600 >= time()) {
											$is_return = true;
										}
									} else if ($nowOrder['status'] == '3') {
										if ($nowOrder['send_time'] + $config_order_complete_date * 24 * 3600 >= time()) {
											$is_return = true;
										}
									} else if ($nowOrder['status'] == 2) {
										$is_return = true;
									}
								?>
									<div class="block-item name-card name-card-3col clearfix js-product-detail">
										<a href="./good.php?id=<?php echo $value['product_id'];?>&store_id=<?php echo $nowOrder['store_id'] ?>" class="thumb">
											<img class="js-view-image" src="<?php echo $value['image'];?>" alt="<?php echo $value['name'];?>"/>
										</a>
										<div class="detail">
											<a href="./good.php?id=<?php echo $value['product_id'];?>&store_id=<?php echo $nowOrder['store_id'] ?>"><h3><?php echo $value['name'];?></h3></a>
											<?php
												if($value['sku_data_arr']){
													foreach($value['sku_data_arr'] as $v){
											?>
														<p class="c-gray ellipsis">
															<?php echo $v['name'];?>：<?php echo $v['value'];?>
														</p>
											<?php 
													}
												}
											?>
											
											<?php 
											if ($is_return && !$value['is_present'] && $value['return_status'] != '2') {
											?>
												<a class="link pull-right return_btn" href="return_apply.php?order_id=<?php echo option('config.orderid_prefix') . $nowOrder['order_no'] ?>&pigcms_id=<?php echo $value['pigcms_id'] ?>">退货</a>
											<?php
											}
											if ($value['return_status'] != '0') {
											?>
												<a class="link pull-right return_btn" href="return_detail.php?order_no=<?php echo option('config.orderid_prefix') . $nowOrder['order_no'] ?>&pigcms_id=<?php echo $value['pigcms_id'] ?>">查看退货</a>
											<?php
											}
											
											if (!$value['is_present'] && in_array($nowOrder['status'], array(2, 3, 4, 7)) && $value['rights_status'] == '0' && ($nowOrder['add_time'] + 5 * 24 * 3600 < time() || $value['return_status'] > 0)) {
											?>
												<a class="link pull-right return_btn" href="rights_apply.php?order_id=<?php echo option('config.orderid_prefix') . $nowOrder['order_no'] ?>&pigcms_id=<?php echo $value['pigcms_id'] ?>">维权</a>
											<?php 
											}
											if ($value['rights_status'] != '0') {
											?>
												<a class="link pull-right return_btn" href="rights_detail.php?order_no=<?php echo option('config.orderid_prefix') . $nowOrder['order_no'] ?>&pigcms_id=<?php echo $value['pigcms_id'] ?>">查看维权</a>
											<?php
											}
											?>
										</div>
										<div class="right-col">
											<div class="price">￥<span><?php echo number_format($value['pro_num']*$value['pro_price'],2);?></span><?php echo $value['is_present'] == 1 ? '<span style="color:#f60;">赠品</span>' : '' ?></div>
											<div class="num">
												<?php
												$discount = 10; 
												if ($value['wholesale_supplier_id']) {
													$discount = $order_data['discount_list'][$value['wholesale_supplier_id']];
												} else {
													$discount = $order_data['discount_list'][$value['store_id']];
												}
												
												if ($discount != 10 && $discount > 0) {
													$discount_money += $value['pro_num'] * $value['pro_price'] * (10 - $discount) / 10;
												?>
													<span style="padding:0px 5px; background:#f60; color:white; border-radius:3px;"><?php echo $discount ?>折</span>
												<?php
												}
												?>
												×<span class="num-txt"><?php echo $value['pro_num'];?></span>
											</div>
											<?php if($value['comment_arr']){ ?>
												<a class="link pull-right message js-show-message" data-comment='<?php echo json_encode($value['comment_arr']) ?>' href="javascript:;">查看留言</a>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							</div>
							<hr class="margin-0 left-10"/>
							<?php if($nowOrder['status'] == 0){ ?>
								<div class="order-message clearfix" id="js-order-message">
									<textarea class="js-msg-container font-size-12" placeholder="给卖家留言..."></textarea>
								</div>
							<?php }else{ ?>
								<div class="order-message">
									<span class="font-size-12">买家留言：</span><p class="message-content font-size-12"><?php echo $nowOrder['comment'] ? $nowOrder['comment'] : '无'?></p>
								</div>
								<hr class="margin-0 left-10"/>
							<?php } ?>
							<div class="bottom">总价<span class="c-orange pull-right">￥<?php echo $nowOrder['sub_total']?></span></div>
						</div>
						<!-- 物流信息 -->
						<div class="block block-form">
							<!-- 快递 -->
							<div class="block-item " style="padding:5px 0;">
								<?php
								if($nowOrder['address_arr']){
									if ($nowOrder['shipping_method'] == 'selffetch') {
								?>
										<ul>
											<li><?php echo $now_store['buyer_selffetch_name'] ? $now_store['buyer_selffetch_name'] : '上门自提' ?></li>
											<li>
												门店电话：<a style="display:inline-block; overflow:visible; padding:0px; margin:0px;" href="tel:<?php echo $nowOrder['address_arr']['address']['tel'] ?>"><?php echo $nowOrder['address_arr']['address']['tel'] ?></a>
												<?php 
												if ($nowOrder['address_arr']['address']['physical_id']) {
												?>
													<a style="display:inline-block; overflow:visible; padding:0px; margin:0px;" href="./physical_detail.php?id=<?php echo $nowOrder['address_arr']['address']['physical_id'] ?>">查看地图</a>
												<?php
												} else {
												?>
													<a style="display:inline-block; overflow:visible; padding:0px; margin:0px;" href="./physical_detail.php?store_id=<?php echo $now_store['store_id'] ?>">查看地图</a>
												<?php
												}
												?>
											</li>
											<li><?php echo $nowOrder['address_arr']['address']['province'];?> <?php echo $nowOrder['address_arr']['address']['city'];?> <?php echo $nowOrder['address_arr']['address']['area'];?></li>
											<li><?php echo $nowOrder['address_arr']['address']['address'];?></li>
											<li>联系人：<?php echo $nowOrder['address_arr']['user'];?>，<?php echo $nowOrder['address_arr']['tel'];?></li>
											<li>预约时间：<?php echo $nowOrder['address_arr']['address']['date'];?> <?php echo $nowOrder['address_arr']['address']['time'];?></li>
										</ul>
								<?php
									} else {
								?>
										<ul>
											<li><?php echo $nowOrder['address_arr']['user'];?>，<?php echo $nowOrder['address_arr']['tel'];?></li>
											<li><?php echo $nowOrder['address_arr']['address']['province'];?> <?php echo $nowOrder['address_arr']['address']['city'];?> <?php echo $nowOrder['address_arr']['address']['area'];?></li>
											<li><?php echo $nowOrder['address_arr']['address']['address'];?></li>
										</ul>
								<?php
									}
								}
								?>
							</div>
						</div>
						<!-- 满减送优惠信息 -->
						<?php
						$money = 0;
						if ($order_data['order_ward_list'] || $order_data['order_coupon_list']) {
						?>
							<div class="block block-bottom-0">
								<div class="js-order-total block-item order-total" style="text-align:left;">
									<?php
									foreach ($order_data['order_ward_list'] as $order_ward_list) {
										foreach ($order_ward_list as $order_ward) {
											$money += $order_ward['content']['cash'];
									?>
											<p><span style="padding:0px 5px; background:#f60; color:white; border-radius:3px;">满减</span><?php echo getRewardStr($order_ward['content']) ?></p>
									<?php
										}
									}
									if ($order_data['order_coupon_list']) {
										foreach ($order_data['order_coupon_list'] as $order_coupon) {
											$money += $order_coupon['money'];
									?>
											<p><span style="padding:0px 5px; background:#f60; color:white; border-radius:3px;">优惠券</span><a href="<?php echo url('coupon:detail', array('id' => $order_coupon['coupon_id'])) ?>"><?php echo $order_coupon['name'] ?></a>,优惠金额：<?php echo $order_coupon['money'] ?>元</p>
									<?php
										}
									}
									?>
								</div>
							</div>
						<?php
						}
						?>
						<!-- 支付 -->
						<div class="block block-bottom-0">
							<div class="js-order-total block-item order-total">
								<p>
									￥<?php echo number_format($nowOrder['sub_total'], 2, '.', '') ?>元 + ￥<?php echo number_format($nowOrder['postage'], 2, '.', '') ?>运费<?php echo $money ? ' - ￥' . number_format($money, 2, '.', '') . '优惠金额' : '' ?>
									<?php echo $discount_money ? ' - ￥' . number_format($discount_money, 2, '.', '') . '元折扣' : '' ?>
								</p>
								<strong class="js-real-pay c-orange js-real-pay-temp">实付：￥<?php echo number_format($nowOrder['total'], 2, '.', '') ?></strong>
								<?php 
								if ($nowOrder['status'] == 3) {
								?>
									<a class="btn btn-block btn-green js-delivery" data-order_no="<?php echo option('config.orderid_prefix') . $nowOrder['order_no'] ?>">确认收货</a>
								<?php
								}
								?>
							</div>
							<?php if($nowOrder['status'] > 1 && $nowOrder['status'] < 5){ ?>
								<div class="block-item paid-time">
									<div class="paid-time-inner">
										<p>订单号：<?php echo $nowOrder['order_no_txt'];?></p>
										<?php 
										if($nowOrder['payment_method'] != 'codpay') {
										?>
											<p class="c-gray"><?php echo date('Y-m-d H:i:s',$nowOrder['paid_time']);?><br/>完成付款</p>
										<?php 
										}
										?>
									</div>
								</div>
							<?php } ?>
							<?php 
							if ($nowOrder['status'] < 2 && $nowOrder['payment_method'] == 'peerpay') {
							?>
								<div class="action-container" id="confirm-pay-way-opts" style="margin-top:20px;margin-bottom:20px;">
									<a href="./order_share_pay.php?orderid=<?php echo $nowOrder['order_no_txt'] ?>" class="btn btn-block btn-large btn-green">去付款</a>
								</div>
							<?php
							}
							?>
						</div>
						
						<?php 
						if ($nowOrder['payment_method'] == 'peerpay') {
						?>
							<style>
							.peerpay_list td {border-bottom:1px dashed #d9d9d9; height:24px; line-height:24px; }
							</style>
							<div class="block block-form" style="margin-top:5px; margin-bottom:5px;">
								<!-- 快递 -->
								<div class="block-item peerpay_list" style="padding:20px 0;">
									<table style="width:100%;">
										<tr>
											<td>代付人</td>
											<td>代付时间</td>
											<td>代付金额</td>
										</tr>
										<?php 
										if ($order_peerpay_list) {
											foreach ($order_peerpay_list as $order_peerpay) {
										?>
												<tr>
													<td><?php echo htmlspecialchars($order_peerpay['name']) ?></td>
													<td><?php echo date('Y-m-d H:i', $order_peerpay['pay_dateline']) ?></td>
													<td><?php echo $order_peerpay['money'] ?></td>
												</tr>
										<?php
											}
										} else {
										?>
											<tr style="height:40px; text-align:center;">
												<td colspan="3">暂无代付</td>
											</tr>
										<?php
										}
										?>
									</table>
								</div>
							</div>
						<?php 
						}
						
						$delivery_time = $config_order_return_date * 3600 * 24 + $nowOrder['delivery_time'];
						$sent_time_over = $config_order_complete_date * 36000 * 24 + $nowOrder['sent_time'];
						$current_time = time();
						
						if (in_array($nowOrder['status'], array(3, 4, 7)) && ($delivery_time < $current_time || $sent_time_over < $current_time)) {
							$seconds = 0;
							if ($nowOrder['delivery_time'] > 0) {
								$seconds = $current_time - $delivery_time;
								
								if ($delivery_time > $sent_time_over) {
									$seconds = $current_time - $sent_time_over;
								}
							} else {
								$seconds = $current_time - $sent_time_over;
							}
							
							$days = getHumanTime($seconds);
							if ($seconds > 0) {
						?>
								<div class="block block-top-0 block-border-top-none center">
									<div class="center action-tip js-pay-tip">已过退货周期<?php echo $days ?></div>
								</div>
						<!-- 物流 -->
						<?php 
							}
						}
						if($nowOrder['package_list']){
						?>
							<div class="js-express-msg block block-list express-info append-message">
								<?php 
								foreach($nowOrder['package_list'] as $value) {
								?>
									<div class="block-item font-size-12">
										<p class="express-title">
											<?php echo $value['express_company'];?>&nbsp;&nbsp;&nbsp;<span class="c-gray">运单编号：</span><?php echo $value['express_no'];?>
										</p>
										<p class="express-context">
											<a href="javascript:" data-type="<?php echo $value['express_code'] ?>" data-order_no="<?php echo $nowOrder['order_no_txt'] ?>" data-express_no="<?php echo $value['express_no'] ?>" style="color:#1B9C46;">查看物流信息</a> <span class="js-express_close" style="display:none;">关闭物流信息</span>
										</p>
										<div class="express_detail" data-is_has="0" style="display:none;"></div>
									</div>
								<?php
								}
								?>
							</div>
						<?php 
						}
						?>
					</div>
				</div>
			</div>
			<?php include display('footer');?>
		</div>

		<?php echo $shareData;?>
	</body>
</html>