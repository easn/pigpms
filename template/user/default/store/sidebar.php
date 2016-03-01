			<?php $select_sidebar=isset($select_sidebar)?$select_sidebar:ACTION_NAME;?>
            <?php $version  = option('config.weidian_version');?>
            <?php if (empty($store_session['drp_level'])) { ?>
            <aside class="ui-sidebar sidebar" style="min-height: 500px;">
                <nav>
                    <ul>
                        <li <?php if ($select_sidebar == 'index') { ?>class="active"<?php } ?>><a href="<?php dourl('store:index'); ?>">微店铺概况</a></li>
                    </ul>
                    <?php if(empty($user_session['type'])){?>
                    <h4>页面管理</h4>
                    <ul>
                        <li <?php if ($select_sidebar == 'wei_page') { ?>class="active"<?php } ?>><a href="<?php dourl('store:wei_page'); ?>"><?php if($_SESSION['user']['admin_id']){?>微页面模板<?php }else{?>微页面/杂志<?php } ?></a></li>
                        <li <?php if ($select_sidebar == 'wei_page_category') { ?>class="active"<?php } ?>><a href="<?php dourl('store:wei_page_category'); ?>"><?php if($_SESSION['user']['admin_id']){?>行业分类<?php }else{?>微页面分类<?php } ?></a></li>
                        <li <?php if ($select_sidebar == 'ucenter') { ?>class="active"<?php } ?>><a href="<?php dourl('store:ucenter'); ?>">会员主页</a></li>
                        <li <?php if ($select_sidebar == 'storenav') { ?>class="active"<?php } ?>><a href="<?php dourl('store:storenav'); ?>">店铺导航</a></li>
                    </ul>
                    <h4>通用模块</h4>
                    <ul>
                        <li><a href="<?php dourl('case:ad'); ?>">公共广告设置</a></li>
                        <li><a href="<?php dourl('case:page'); ?>">自定义页面模块</a></li>
                        <li><a href="<?php dourl('case:attachment'); ?>">我的文件</a></li>
                    </ul>

                    <?php if (empty($_SESSION['sync_store'])) { ?>
                    <h4>店铺服务</h4>
                    <ul>
                        <li <?php if ($select_sidebar == 'service') { ?>class="active"<?php } ?>><a href="<?php dourl('store:service'); ?>">客服列表</a></li>
						<li <?php if ($select_sidebar == 'business_hours') { ?>class="active"<?php } ?>><a href="<?php dourl('store:business_hours'); ?>">营业时间</a></li>
						<li <?php if ($select_sidebar == 'certification') { ?>class="active"<?php } ?>><a href="<?php dourl('store:certification'); ?>">店铺认证</a></li>
                    </ul>
                        <?php } ?>

					<h4>店铺设置</h4>
                    <ul class="dianpu_left">
                        <li class="<?php if ($select_sidebar == 'info') { ?>active<?php } ?> info"><a href="<?php dourl('setting:store'); ?>#info">店铺信息</a></li>
						<li class="<?php if ($select_sidebar == 'contact') { ?>active<?php } ?> contact"><a href="<?php dourl('setting:store'); ?>#contact">联系我们</a></li>
						<li class="demoFunc <?php if ($select_sidebar == 'list') { ?>active<?php } ?> list"><a href="<?php dourl('setting:store'); ?>#list">门店管理</a></li>
                        <?php if (!empty($_SESSION['drp_diy_store'])) { ?>
						<li <?php if ($select_sidebar == 'config') { ?>class="active"<?php } ?>><a href="<?php dourl('setting:config'); ?>">物流配置</a></li>
                        <?php } ?>
                        <?php if (empty($_SESSION['sync_store'])) { ?>
                        <li class="demoFunc <?php if ($select_sidebar == 'set_admin') { ?>active<?php } ?> list "><a href="<?php dourl('setting:set_admin'); ?>#list">设置门店管理员</a></li>
                        <li class="testFunc <?php if ($select_sidebar == 'set_stock') { ?>active<?php } ?> product"><a href="<?php dourl('setting:set_stock'); ?>#product">分配门店库存</a></li>
                        <?php } ?>
                    </ul>
                    <?php }?>
                </nav>
            </aside>
            <?php }?>
