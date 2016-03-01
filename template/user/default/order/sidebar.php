			<?php $select_sidebar=isset($select_sidebar)?$select_sidebar:ACTION_NAME;?>
            <aside class="ui-sidebar sidebar">
                <nav>
                    <ul>
                        <li <?php if(in_array($select_sidebar,array('dashboard','statistics'))) echo 'class="active"';?>>
                            <a href="<?php dourl('dashboard');?>">订单概况</a>
                        </li>
                        <li <?php if($select_sidebar == 'all') echo 'class="active"';?>>
                            <a href="<?php dourl('all');?>">所有订单</a>
                        </li>
                        <?php if (!empty($_SESSION['drp_diy_store'])) { ?>
                        <li <?php if($select_sidebar == 'selffetch') echo 'class="active"';?>>
                            <a href="<?php dourl('selffetch'); ?>"><?php echo $store_session['buyer_selffetch_name'] ? $store_session['buyer_selffetch_name'] : '到店自提' ?>订单</a>
                        </li>
                        <li <?php if($select_sidebar == 'codpay') echo 'class="active"';?>>
                            <a href="<?php dourl('codpay'); ?>">货到付款订单</a>
                        </li>
                        <?php } ?>
						<li <?php if($select_sidebar == 'order_return') echo 'class="active"';?>>
							<a href="<?php dourl('order_return'); ?>">退货列表</a>
						</li>
						<li <?php if($select_sidebar == 'order_rights') echo 'class="active"';?>>
							<a href="<?php dourl('order_rights'); ?>">维权列表</a>
						</li>
                        <li <?php if($select_sidebar == 'star') echo 'class="active"';?>>
                            <a href="<?php dourl('star'); ?>">加星订单</a>
                        </li>
                    </ul>
                    <ul>
                        <?php if (!empty($_SESSION['drp_diy_store'])) { ?>
                            <li><a href="<?php dourl('trade:delivery');?>">物流工具</a></li>
                        <?php } ?>
                        <li><a href="<?php dourl('trade:income'); ?>">收入/提现</a></li>
                    </ul>
                    <h4>对账信息</h4>
                    <ul>
                        <li><a href="<?php dourl('order:check');?>">未对帐/已对账</a></li>
                    </ul>
                </nav>
            </aside>