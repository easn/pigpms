			<?php $select_sidebar=isset($select_sidebar)?$select_sidebar:ACTION_NAME;?>
            <?php $version  = option('config.weidian_version');?>
            <aside class="ui-sidebar sidebar" style="min-height: 500px;">
                <nav>
                    <?php if ($_SESSION['store']['drp_level'] >= 1) { //分销商显示?>
                    <ul><li><h4>我是分销商</h4></li></ul>
                    <ul>
                        <li <?php if ($select_sidebar == 'index') { ?>class="active"<?php } ?>><a href="<?php dourl('index'); ?>">分销概况</a></li>
                        <li <?php if ($select_sidebar == 'next_seller') { ?>class="active"<?php } ?>><a href="<?php dourl('next_seller'); ?>">下级分销商</a></li>
                        <li <?php if ($select_sidebar == 'goods') { ?>class="active"<?php } ?>><a href="<?php dourl('goods'); ?>">已分销商品</a></li>
                        <li <?php if ($select_sidebar == 'orders') { ?>class="active"<?php } ?>><a href="<?php dourl('orders'); ?>">分销订单</a></li>
                        <li <?php if ($select_sidebar == 'supplier') { ?>class="active"<?php } ?>><a href="<?php dourl('supplier'); ?>">我的供货商</a></li>
                        <li <?php if ($select_sidebar == 'contact_information') { ?>class="active"<?php } ?>><a href="<?php dourl('contact_information'); ?>">联系我们</a></li>
						<li <?php if ($select_sidebar == 'seller_setting') { ?>class="active"<?php } ?>><a href="<?php dourl('seller_setting'); ?>">分销配置</a></li>
                    </ul>
                    <?php }else if ($_SESSION['store']['drp_level'] == 0) { // 供货商显示?>
                    <ul>
                        <li><h4>我是供货商</h4></li>
                    </ul>
                    <ul>
                        <li <?php if ($select_sidebar == 'distribution_index') { ?>class="active"<?php } ?>><a href="<?php dourl('distribution_index'); ?>">分销统计</a></li>
                        <li <?php if ($select_sidebar == 'distribution') { ?>class="active"<?php } ?>><a href="<?php dourl('distribution'); ?>">分销商排名</a></li>
                        <li <?php if ($select_sidebar == 'seller_order') { ?>class="active"<?php } ?>><a href="<?php dourl('seller_order'); ?>">分销商订单</a></li>
                        <?php if(empty($version)){?>
                        <li <?php if ($select_sidebar == 'wholesale_order') { ?>class="active"<?php } ?>><a href="<?php dourl('wholesale_order'); ?>">经销商订单</a></li>
                        <?php } ?>
                        <li <?php if ($select_sidebar == 'seller') { ?>class="active"<?php } ?>><a href="<?php dourl('seller'); ?>">我的分销商</a></li>
                        <?php if(empty($version)){?>
                        <li <?php if ($select_sidebar == 'agency') { ?>class="active"<?php } ?>><a href="<?php dourl('agency'); ?>">我的经销商</a></li>
                        <?php } ?>
                        <li <?php if ($select_sidebar == 'supplier_market') { ?>class="active"<?php } ?>><a href="<?php dourl('supplier_market'); ?>">本店商品</a></li>
                        <li <?php if ($select_sidebar == 'setting') { ?>class="active"<?php } ?>><a href="<?php dourl('setting'); ?>">分销配置</a></li>
                        <li <?php if ($select_sidebar == 'contact_information') { ?>class="active"<?php } ?>><a href="<?php dourl('contact_information'); ?>">联系我们</a></li>
                    </ul>
                        <?php if(empty($version)){ // v.meihua 不显示 我是经销商?>
                        <ul>
                            <li><h4>我是经销商</h4></li>
                        </ul>
                        <ul>
                            <li <?php if ($select_sidebar == 'market') { ?>class="active"<?php } ?>><a href="<?php dourl('market'); ?>">批发市场</a></li>
                            <li <?php if ($select_sidebar == 'my_wholesale') { ?>class="active"<?php } ?>><a href="<?php dourl('my_wholesale'); ?>">我卖的商品</a></li>
                            <li <?php if ($select_sidebar == 'my_supplier') { ?>class="active"<?php } ?>><a href="<?php dourl('my_supplier'); ?>">我的供货商</a></li>
                        </ul>
                        <?php }?>
                    <?php } ?>
                </nav>
            </aside>