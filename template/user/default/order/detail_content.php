<style type="text/css">
	.platform-tag {
		display: inline-block;
		vertical-align: middle;
		padding: 3px 7px 3px 7px;
		background-color: #f60;
		color: #fff;
		font-size: 12px;
		line-height: 14px;
		border-radius: 2px;
	}
	.ui-btn-primary-no {
	  color: #666666;
	  background: #E3E3EB;
	  border-color: #E5E5E9;
	}
</style>
<h1 class="order-title">订单号：<?php echo $order['order_no']; ?></h1>
<ul class="order-process clearfix">
	<?php
	if ($order['status'] == 5) {
	?>
		<li class="active">
			<p class="order-process-state">买家已下单</p>
			<p class="bar"><i class="square">√</i></p>
			<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['add_time']); ?></p>
		</li>
		<li class="active">
			<p class="order-process-state">&nbsp;</p>
			<p class="bar">&nbsp;</p>
			<p class="order-process-time"></p>
		</li>
		<li class="active">
			<p class="order-process-state">&nbsp;</p>
			<p class="bar">&nbsp;</p>
			<p class="order-process-time"></p>
		</li>
		<li class="active">
			<p class="order-process-state"><?php if ($order['cancel_method'] == 1) { ?>卖家取消<?php } else if ($order['cancel_method'] == 2) { ?>买家取消<?php } else { ?>自动过期<?php } ?></p>
			<p class="bar"><i class="square">√</i></p>
			<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['cancel_time']); ?></p>
		</li>
	<?php
	} else {
	?>
		<?php 
		if ($order['shipping_method'] == 'selffetch') {
			if ($order['payment_method'] == 'codpay') {
		?>
				<li class="active" >
					<p class="order-process-state">买家已下单</p>
					<p class="bar"><i class="square">√</i></p>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['add_time']); ?></p>
				</li>
				<li <?php if (in_array($order['status'], array(3, 4, 7))) { ?>class="active"<?php } ?>>
					<p class="order-process-state">买家<?php echo $store_session['buyer_selffetch_name'] ? $store_session['buyer_selffetch_name'] : '上门自提' ?></p>
					<p class="bar"><i class="square">2</i></p>
					<?php
					if (in_array($order['status'], array(3, 4, 7))) {
					?>
						<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['delivery_time']); ?></p>
					<?php
					}
					?>
				</li>
				<li <?php if ($order['status'] == 4) { ?>class="active"<?php } ?>>
					<p class="order-process-state">交易完成</p>
					<p class="bar"><i class="square">3</i></p>
					<?php if ($order['status'] == 4) { ?>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['complate_time']); ?></p>
					<?php } ?>
				</li>
		<?php 
			} else {
		?>
				<li class="active">
					<p class="order-process-state">买家已下单</p>
					<p class="bar"><i class="square">√</i></p>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['add_time']); ?></p>
				</li>
				<li <?php if (in_array($order['status'], array(2, 3, 4, 7))) { ?>class="active"<?php } ?>>
					<p class="order-process-state"><?php if (in_array($order['status'], array(2, 3, 4, 7))) { ?>买家已付款<?php } else { ?>等待买家付款<?php } ?></p>
					<p class="bar"><i class="square">2</i></p>
					<?php if (in_array($order['status'], array(2, 3, 4, 7))) { ?>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['paid_time']); ?></p>
					<?php } ?>
				</li>
				<li <?php if (in_array($order['status'], array(3, 4, 7))) { ?>class="active"<?php } ?>>
					<p class="order-process-state">买家<?php echo $store_session['buyer_selffetch_name'] ? $store_session['buyer_selffetch_name'] : '上门自提' ?></p>
					<p class="bar"><i class="square">3</i></p>
					<?php
					if (in_array($order['status'], array(3, 4, 7))) {
					?>
						<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['delivery_time']); ?></p>
					<?php
					}
					?>
				</li>
				<li <?php if ($order['status'] == 4) { ?>class="active"<?php } ?>>
					<p class="order-process-state">交易完成</p>
					<p class="bar"><i class="square">4</i></p>
					<?php if ($order['status'] == 4) { ?>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['complate_time']); ?></p>
					<?php } ?>
				</li>
		<?php
			}
		} else {
			$width = '20%';
			if ($order['refund_time']) {
				$width = '25%';
			}
		?>
			<li class="active" style="width:<?php echo $width ?>">
				<p class="order-process-state">买家已下单</p>
				<p class="bar"><i class="square">√</i></p>
				<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['add_time']); ?></p>
			</li>
			<?php 
			if ($order['payment_method'] != 'codpay') {
			?>
				<li <?php if (in_array($order['status'], array(2, 3, 4, 6, 7))) { ?>class="active"<?php } ?>  style="width:<?php echo $width ?>">
					<p class="order-process-state"><?php if (in_array($order['status'], array(2, 3, 4, 6, 7))) { ?>买家已付款<?php } else { ?>等待买家付款<?php } ?></p>
					<p class="bar"><i class="square">2</i></p>
					<?php if (in_array($order['status'], array(2, 3, 4, 6, 7))) { ?>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['paid_time']); ?></p>
					<?php } ?>
				</li>
			<?php 
			} else {
			?>
				<li <?php if (in_array($order['status'], array(2, 3, 4, 7))) { ?>class="active"<?php } ?>  style="width:<?php echo $width ?>">
					<p class="order-process-state">等待卖家发货</p>
					<p class="bar"><i class="square">2</i></p>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['add_time']); ?></p>
				</li>
			<?php
			}
			$next_step = 5;
			if ($order['refund_time']) {
				$next_step = 4;
			?>
				<li <?php if (in_array($order['status'], array(4, 6))) { ?>class="active"<?php } ?>  style="width:<?php echo $width ?>">
					<p class="order-process-state">订单取消</p>
					<p class="bar"><i class="square">3</i></p>
					<?php if (in_array($order['status'], array(4, 6))) { ?>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['refund_time']); ?></p>
					<?php } ?>
				</li>
			<?php
			} else {
			?>
				<li <?php if (in_array($order['status'], array(3, 4, 7))) { ?>class="active"<?php } ?>  style="width:<?php echo $width ?>">
					<p class="order-process-state">卖家已发货</p>
					<p class="bar"><i class="square">3</i></p>
					<?php if (in_array($order['status'], array(3, 4, 7))) { ?>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['sent_time']); ?></p>
					<?php } ?>
				</li>
				<li <?php if (in_array($order['status'], array(7,4))) { ?>class="active"<?php } ?>  style="width:<?php echo $width ?>">
					<p class="order-process-state">买家已收货</p>
					<p class="bar"><i class="square">4</i></p>
					<?php if (in_array($order['status'], array(7,4))) { ?>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['delivery_time']); ?></p>
					<?php } ?>
				</li>
			<?php 
			}
			?>
			<li <?php if ($order['status'] == 4) { ?>class="active"<?php } ?>  style="width:<?php echo $width ?>">
				<p class="order-process-state">交易完成</p>
				<p class="bar"><i class="square"><?php echo $next_step ?></i></p>
				<?php if ($order['status'] == 4) { ?>
				<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['complate_time']); ?></p>
				<?php } ?>
			</li>
			
		<?php
		}
	}
	?>
</ul>
<div class="section">
	<h2 class="section-title clearfix">
		订单概况	 <?php echo $order['shipping_method'] == 'friend' ? '<span style="color:red;">送朋友订单</span>' : '' ?>
		<div class="js-memo-star-container memo-star-container pull-right">
			<div class="opts">
				<div class="td-cont message-opts">
					<div class="m-opts">
						<a class="js-memo-it" rel="popover" href="javascript:;" data-bak="<?php echo $order['bak']; ?>" data-id="<?php echo $order['order_id']; ?>">备注</a>
						<span>-</span>
						<?php if (empty($order['star'])) { ?>
							<a class="js-stared-it" href="javascript:;">加星</a>
						<?php } else { ?>
							<span class="js-stared-it stared"><img src="<?php echo TPL_URL; ?>/images/star-on.png"> x <?php echo $order['star']; ?></span>
						<?php } ?>
					</div>
					<div id="raty-action-<?php echo $order['order_id']; ?>" class="raty-action" style="display: none; cursor: pointer;">
						<img src="<?php echo TPL_URL; ?>images/cancel-custom-off.png" alt="x" title="去星" data-id="<?php echo $order['order_id']; ?>" class="raty-cancel" />&nbsp;
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>" class="star" alt="1" title="一星" />
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>" class="star" alt="2" title="二星" />
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>" class="star" alt="3" title="三星" />
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>" class="star" alt="4" title="四星" />
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>" class="star" alt="5" title="五星" />
					</div>
				</div>
			</div>
		</div>
	</h2>
	<div class="section-detail clearfix">
		<div class="pull-left">
			<table>
				<tbody>
					<tr>
						<td>订单状态：</td>
						<td><?php echo $status[$order['status']]; ?> <?php if ($order['status'] == 5) { ?><?php if ($order['cancel_method'] == 1) { ?>(卖家取消)<?php } else if ($order['cancel_method'] == 2) { ?>(买家取消)<?php } else { ?>(自动过期)<?php } ?><?php } ?></td>
					</tr>
					<tr>
						<td>应付金额：</td>
						<td><strong class="ui-money-income">￥<?php echo $order['total']; ?></strong>（含运费 <?php echo $order['postage']; ?> ）</td>
					</tr>
					<tr>
                        <td>购买者：</td>
                        <td><?php echo !empty($order['buyer']) ? $order['buyer']: $order['address_user']; ?></td>
                    </tr>
                    <tr>
                        <td>手机号：</td>
                        <td><?php echo !empty($order['phone']) ? $order['phone'] : $order['address_tel'];?></td>
                    </tr>
                    <tr>
                        <td>订单来源：</td>
                        <td><?php echo $seller; ?></td>
                    </tr>
					<tr>
						<td>付款方式：</td>
						<td><?php if (array_key_exists($order['payment_method'], $payment_method)) {  echo $payment_method[$order['payment_method']];  } else if ($order['payment_method'] == 'codpay') { echo '货到付款'; } else if ($order['payment_method'] == 'peerpay') { echo '找人代付'; }?><?php if($order['useStorePay']){ echo ' <span style="color:#999;">(自有支付)</span>';}?></td>
					</tr>
					<tr>
						<td>物流方式：</td>
						<td>
							<?php
							if ($order['shipping_method'] == 'express' || $order['shipping_method'] == 'friend') {
							?>
								快递配送
							<?php
							} else if ($order['shipping_method'] == 'selffetch') {
								echo $store_session['buyer_selffetch_name'] ? $store_session['buyer_selffetch_name'] : '上门自提';
							}
							?>
						</td>
					</tr>
					<?php $address = !empty($order['address']) ? unserialize($order['address']) : array(); ?>
					<?php if ($order['shipping_method'] == 'express' || $order['shipping_method'] == 'friend') { ?>
					<tr>
						<td>收货信息：</td>
						<td><?php echo $address['province']; ?> <?php echo $address['city']; ?> <?php echo $address['area']; ?> <?php echo $address['address']; ?> <?php echo $order['address_user']; ?> <?php echo $order['address_tel']; ?></td>
					</tr>
					<?php } ?>
					<?php if ($order['shipping_method'] == 'selffetch') { ?>
					<tr>
						<td>门店：</td>
						<td><?php echo $address['name']; ?> <?php echo $address['province']; ?> <?php echo $address['city']; ?> <?php echo $address['area']; ?> <?php echo $address['address']; ?> <?php echo $order['address_tel']; ?></td>
					</tr>
					<tr>
						<td>预约人：</td>
						<td><?php echo $order['address_user']; ?></td>
					</tr>
					<tr>
						<td>联系方式：</td>
						<td><?php echo $order['address_tel']; ?></td>
					</tr>
					<tr>
						<td>预约时间：</td>
						<td><?php echo $address['date']; ?> <?php echo $address['time']; ?></td>
					</tr>
					<?php } ?>
					<tr>
						<td>买家留言：</td>
						<td style="color:red"><?php echo $order['comment']; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="pull-right section-sidebar">
			<p>卖家备注：</p>
			<div class="js-memo-text memo-text"><?php echo $order['bak']; ?></div>
			<div class="order-action-btns">
			<!--已经买家付款的订单 或 货到付款-->
			<?php 
			if (in_array($order['status'], array(2, 3))) {
			?>
				<a href="javascript:;" data-id="<?php echo $order['order_id']; ?>" class="ui-btn js-print-order ui-btn-primary ">打印订单</a>
			<?php 
			}
			if (in_array($order['status'], array(0, 1)) || ($order['status'] == 2 && $order['payment_method'] == 'codpay')) {
			?>
				<a href="javascript:;" data-id="<?php echo $order['order_id']; ?>" class="ui-btn ui-btn-primary js-cancel-order">关闭订单</a>
			<?php 
			}
			?>
			</div>
		</div>
	</div>
</div>
<?php if (!empty($packages)) { ?>
<div class="section section-express">
	<div class="section-title clearfix">
		<h2>物流状态</h2>
		<ul class="js-express-tab">
			<?php foreach ($packages as $key => $package) { ?>
			<li <?php if ($key == 0) { ?>class="active"<?php } ?> data-pack-id="<?php echo $package['package_id']; ?>">包裹<?php echo $key + 1;?></li>
			<?php } ?>
		</ul>
	</div>
	<div class="section-detail">
		<?php if ($packages) { ?>
		<?php foreach ($packages as $key => $package) { ?>
		<div class="js-express-tab-content <?php if ($key > 0) { ?>hide<?php } ?>" data-pack-id="<?php echo $package['page_id']; ?>" data-express-no="<?php echo $package['express_no']; ?>">
			<?php if (!empty($package['express_no']) && !empty($package['express_code'])) { ?>
				<p><?php echo $package['express_company']; ?> 运单号：<?php echo $package['express_no']; ?><a href="javascript:" class="js-express-detail" data-express_company="<?php echo $package['express_company'] ?>" data-type="<?php echo $package['express_code'] ?>" data-express_no="<?php echo $package['express_no']; ?>">查看物流信息</a></p>
			<?php } else if (!empty($package['physical_id']) && !empty($package['courier_id'])) { ?>
				<p>由门店 【<?php echo $package['physical_name'] ?>】<?php echo !empty($package['courier_name']) ? '的配送员【'.$package['courier_name'].'】' : ''; ?> 配送，包裹状态【<?php echo $package['status_txt'] ?>】</p>
			<?php } else { ?>
				<p>无物流</p>
			<?php } ?>
		</div>
		<?php } ?>
		<?php } ?>
	</div>
</div>
<?php } ?>
<table class="table order-goods">
	<thead>
	<tr>
		<th class="tb-thumb"></th>
		<th class="tb-name">商品名称</th>
		<th class="tb-price">单价（元）</th>
		<!--<th class="tb-price">商品优惠</th>-->
		<th class="tb-num">数量</th>
		<th class="tb-total">小计（元）</th>
		<th class="tb-state">状态</th>
		<th class="tb-postage">运费（元）</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$start_package = false; //订单已经有商品开始打包
	// 折扣金额
	$discount_money = 0;
	// 判断是否有自营产品
	$have_self_product = false;
	foreach ($products as $key => $product) {
		if (!$start_package && $product['is_packaged']) {
			$start_package = true;
		}
		$skus = !empty($product['sku_data']) ? unserialize($product['sku_data']) : '';
		$comments = !empty($product['comment']) ? unserialize($product['comment']) : '';
		
		if ($product['wholesale_product_id'] == 0 && $product['store_id'] == $order['store_id']) {
			$have_self_product = true;
		}
	?>
	<tr data-order-id="<?php echo $order['order_id']; ?>">
		<td class="tb-thumb" <?php if (!empty($comments)) { ?>rowspan="2"<?php } ?>>
			<?php if ($product['is_fx']) { ?>
				<?php if (!$product['is_packaged']) { ?><span style="color:red">卖家未发货</span><br/><?php } ?>
			<?php } ?>
			<img src="<?php echo $product['image']; ?>" width="60" height="60" />
		</td>
		<td class="tb-name">
			<a href="<?php echo $config['wap_site_url'];?>/good.php?id=<?php echo $product['product_id'] ?>&store_id=<?php echo $_SESSION['store']['store_id'] ?>" class="new-window" target="_blank"><?php echo $product['name']; ?></a>
			<?php 
			if ($product['is_present']) {
				echo '<span style="color:#f60;">赠品</span>';
			}
			?>
			<?php if ($product['is_fx']) { ?>
				<span class="platform-tag">分销</span>
			<?php } ?>
			<?php if ($skus) { ?>
			<p>
				<span class="goods-sku"><?php foreach ($skus as $sku) { ?><?php echo $sku['name']; ?>: <?php echo $sku['value']; ?>&nbsp;<?php } ?></span>
			</p>
			<?php } ?>
		</td>
		<td class="tb-price">
			<?php echo $product['pro_price']; ?>
			<?php
			$discount = 10;
			if ($product['wholesale_product_id']) {
				$discount = $order_data['order_discount_list'][$product['supplier_id']];
			} else {
				$discount = $order_data['order_discount_list'][$product['store_id']];
			}
			
			if ($discount != 10 && $discount > 0) {
				$discount_money += $product['pro_num'] * $product['pro_price'] * (10 - $discount) / 10;
			?>
				<span style="padding:0px 5px; background:#f60; color:white; border-radius:3px;"><?php echo $discount ?>折</span>
			<?php
			}
			?>
		</td>
		<!--<td class="tb-price"></td>-->
		<td class="tb-num"><?php echo $product['pro_num']; ?></td>
		<td class="tb-total"><?php echo number_format($product['pro_num'] * $product['pro_price'], 2, '.', ''); ?></td>
		<td class="tb-state" <?php if (!empty($comments)) { ?>rowspan="2"<?php } ?>>
			<?php
			if ($product['is_packaged'] || $start_package) {
				if ($product['in_package_status'] == 0) {
			?>
				待发货
			<?php
				} else if ($product['in_package_status'] == 1) {
			?>
					已发货
			<?php
				} else if ($product['in_package_status'] == 2) {
			?>
					已到店
			<?php
				} else if ($product['in_package_status'] == 3) {
			?>
				已签收
			<?php
				}
			} else {
				if ($order['shipping_method'] == 'selffetch' && $order['status'] != 4 && $order['status'] != 7) {
					echo '未' . ($store_session['buyer_selffetch_name'] ? $store_session['buyer_selffetch_name'] : '上门自提');
				} else if ($order['shipping_method'] == 'selffetch' && ($order['status'] == 4 || $order['status'] == 7)) {
					echo '已' . ($store_session['buyer_selffetch_name'] ? $store_session['buyer_selffetch_name'] : '上门自提');
				}else {
			?>
					未打包
			<?php
				}
			}
			?>
		</td>
		<?php  if (count($comment_count) > 0 && $key == 0) { ?>
		<td class="tb-postage" rowspan="<?php echo $rows; ?>">
			<?php echo $order['postage']; ?>
			<?php if (in_array($order['status'], array(0,1))) { ?>
                <?php if($store_session['drp_level'] == 0 ){?>
			<p class="text-center">
				<a href="javascript:;" class="js-change-price" data-id="<?php echo $order['order_id']; ?>">修改价格</a>
			</p>
               <?php }?>
			<?php } ?>
		</td>
		<?php } ?>
	</tr>
	<?php if (!empty($comments)) { ?>
	<?php foreach ($comments as $comment) { ?>
	<tr class="msg-row">
		<td colspan="5"><?php echo $comment['name']; ?>：<?php echo $comment['value']; ?><br></td>
	</tr>
	<?php } ?>
	<?php } ?>
	<?php } ?>
	</tbody>
</table>
<div class="clearfix section-final">
	<div class="pull-right text-right">
		<table>
			<tbody>
			<tr>
				<td>商品小计：</td>
				<td>￥<?php echo $order['sub_total']; ?></td>
			</tr>
			<tr>
				<td>运费：</td>
				<td>￥<span class="order-postage"><?php echo $order['postage']; ?></span></td>
			</tr>
			<?php if (!empty($order['float_amount']) && $order['float_amount'] != '0.00') { ?>
			<tr>
				<td>卖家改价：</td>
				<?php if ($order['float_amount'] > 0) { ?>
				<td>+￥<?php echo $order['float_amount']; ?></td>
				<?php } else { ?>
					<td>-￥<?php echo number_format(abs($order['float_amount']), 2, '.', ''); ?></td>
				<?php } ?>
			</tr>
			<?php } ?>
			<?php
			$money = 0;
			if ($order_data['order_ward_list']) {
				foreach ($order_data['order_ward_list'] as $order_ward_list) {
					foreach ($order_ward_list as $order_ward) {
						$money += $order_ward['content']['cash'];
			?>
						<tr>
							<td>满减：</td>
							<td><?php echo getRewardStr($order_ward['content']) ?></td>
						</tr>
			<?php 
					}
				}
			}
			if ($order_data['order_coupon_list']) {
				foreach ($order_data['order_coupon_list'] as $order_coupon) {
					$money += $order_coupon['money'];
			?>
					<tr>
						<td>优惠券:</td>
						<td>
							<span><i><?php echo $order_coupon['name'] ?></i></span>
							<span>优惠金额:<i><?php echo $order_coupon['money'] ?>元</i></span>
						</td>
					</tr>
			<?php 
				}
			}
			
			if ($order_data['discount_money']) {
				$money += $order_data['discount_money'];
			?>
				<tr>
					<td>折扣:</td>
					<td>
						<span>优惠金额:<i><?php echo $order_data['discount_money'] ?>元</i></span>
					</td>
				</tr>
			<?php 
			}
			?>
			<tr>
				<td>应收款：</td>
				<td><span class="ui-money-income">￥<span class="order-total"><?php echo $order['total']; ?></span></span></td>
			</tr>
			</tbody>
		</table>
		<?php if ($order['is_supplier']) { ?>
		<?php if ($order['status'] == 2 && $order['shipping_method'] != 'selffetch') { //商品未发货 ?>
		<p>
			<a href="javascript:;" class="btn js-express-goods detail-send-goods btn-primary" data-id="<?php echo $order['order_id']; ?>">发 货</a>
		</p>
		<?php } ?>
		<?php } ?>
	</div>
</div>
<?php
if ($order['payment_method'] == 'peerpay') {
?>
	<div class="section">
		<h2 class="section-title clearfix">
			代付列表
		</h2>
	</div>
	<table class="table order-goods">
		<thead>
			<tr>
				<th>代付姓名</th>
				<th>代付留言</th>
				<th>代付金额（元）</th>
				<th>代付时间</th>
				<th>退款金额(元)</th>
				<th>退款时间</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$peerpay_money = 0;
			if ($order_peerpay_list) {
				foreach ($order_peerpay_list as $order_peerpay) {
					$peerpay_money += $order_peerpay['money'];
			?>
					<tr>
						<td><?php echo htmlspecialchars($order_peerpay['name']) ?></td>
						<td><?php echo htmlspecialchars($order_peerpay['content']) ?></td>
						<td><?php echo $order_peerpay['money'] ?></td>
						<td><?php echo date('Y-m-d H:i', $order_peerpay['pay_dateline']) ?></td>
						<td>
							<?php
							if ($order_peerpay['untread_status']) {
								echo $order_peerpay['untread_money'];
								$peerpay_money -= $order_peerpay['untread_money'];
							} else {
								echo '0.00';
							}
							?>
						</td>
						<td>
							<?php
							if ($order_peerpay['untread_status']) {
								echo date('Y-m-d H:i', $order_peerpay['untread_dateline']);
							} else {
								echo '-';
							}
							?>
						</td>
					</tr>
			<?php
				}
			} else {
			?>
				<tr>
					<td height="40" colspan="6" style="height:40px; line-height:40px; text-align:center;">暂无代付</td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<div class="clearfix section-final">
		<div class="pull-right text-right">
			<table>
				<tbody>
					<tr>
						<td>代付总额：</td>
						<td>￥<?php echo sprintf('%.2f', $peerpay_money) ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
<?php
}
if ($have_self_product) {
?>
	<script>
	$(function () {
		$(".js-print-order").show();
	});
	</script>
<?php 
}
?>