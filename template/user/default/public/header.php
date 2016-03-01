<?php $select_nav   = isset($select_nav)?$select_nav:MODULE_NAME;?>
<?php $version      = option('config.weidian_version');?>
<?php $wd_version   = option('config.weidian_version_type');?>
<?php 
    if($_SERVER['SERVER_NAME'] != 'www.weidian.com'){
?>
<style>
    /*新功能，菜单添加class，外网隐藏*/
    .testFunc{display:none !important;}
</style>
<?php
    }else{
?>
<style>
    /*外网需要显示，本地隐藏菜单时添加class*/
    .demoFunc{display:none !important;}
</style>
<?php
}
?>
<div id="hd" class="wrap rel">
    <div class="wrap_1000 clearfix">
        <h1 id="hd_logo" class="abs" title="<?php echo $config['site_name'];?>">
            <?php if($config['pc_shopercenter_logo'] != ''){?>
                <a href="<?php dourl('store:select');?>">
                    <img src="<?php echo $config['pc_shopercenter_logo'];?>" height="35" alt="<?php echo $config['site_name'];?>" style="height:35px;width:auto;max-width:none;"/>
                </a>
            <?php }?>
        </h1>

        <nav class="ui-header-nav">
            <ul class="clearfix">
                <?php if($user_session['type']!=1):?>
                    <li <?php if(in_array($select_nav,array('case','store', 'setting'))) echo 'class="active"';?>>
                        <a href="<?php dourl('store:index');?>">店铺</a>
                    </li>   
                <?php endif;?>
                <?php if (!empty($_SESSION['drp_diy_store'])) { ?>
                    <li class="divide">|</li>
                    <li <?php if($select_nav == 'goods') echo 'class="active"';?>>
                        <a href="<?php dourl('goods:index');?>">商品</a>
                    </li> 
                <?php } ?>
                <li class="divide">|</li>
                <li <?php if(in_array($select_nav,array('order','trade'))) echo 'class="active"';?> >
                    <a href="<?php echo dourl('order:dashboard'); ?>">订单</a>
                </li>
                <?php if (!empty($_SESSION['drp_diy_store'])) { ?>
                <?php if(empty($version) && empty($_SESSION['sync_store'])){?>
                    <li class="divide">|</li>
                    <li class="<?php if(in_array($select_nav,array('fans'))) echo 'active';?>" >
                        <a href="<?php echo dourl('fans:tag'); ?>">会员管理</a>
                    </li>
                <?php }?>
                <?php }?>
                <?php if($user_session['type']!=1):?>
                    <?php if (!empty($_SESSION['drp_diy_store'])) { ?>
                        <?php if(empty($version) && empty($_SESSION['sync_store'])){?>
                            <li class="divide">|</li>
                            <li class="<?php if(in_array($select_nav,array('appmarket','reward','preferential','wxapp'))) echo 'active';?>" >
                                <a href="<?php echo dourl('appmarket:dashboard'); ?>">应用营销</a>
                            </li>
                            <li class="divide">|</li>
                            <li class="js-weixin-notify <?php if($select_nav == 'weixin') echo 'active';?>">
                                <a href="<?php echo dourl('weixin:info'); ?>">微信</a>
                            </li>
                            
                        <?php } ?>
                    <?php } ?>
                    <?php if ($enabled_drp) { ?>
                        <li class="divide">|</li>
                        <li <?php if($select_nav == 'fx') echo 'class="active"';?>>
                            <?php if(empty($version)) { ?>
                            <a href="<?php echo $_SESSION['store']['drp_level']==0 ? dourl('fx:distribution_index') : dourl('fx:index'); ?>">分销/批发</a>
                            <?php } else {?>
                            <a href="<?php echo $_SESSION['store']['drp_level']==0 ? dourl('fx:distribution_index') : dourl('fx:index'); ?>">分销</a>
                            <?php }?>
                        </li>
                    <?php } ?>
                <?php endif;?>
				
                
                <?php if($user_session['type']!=1): ?>
                    <li class="testFunc divide">|</li>
                    <li class="testFunc js-weixin-notify <?php if($select_nav == 'substore') echo 'active';?>">
                    <a href="<?php echo dourl('substore:store_list'); ?>">门店仓库物流</a>
                    </li>
                <?php else: ?>   
                    <li class="testFunc divide">|</li>
                    <li class="testFunc js-weixin-notify <?php if($select_nav == 'substore') echo 'active';?>">
                    <a href="<?php echo dourl('substore:store_config'); ?>">门店仓库物流</a>
                    </li>
                <?php endif; ?>
                
                
                <?php if($user_session['type']!=1):?>
                    <?php if($config['bbs_url']){ ?>
                        <li class="divide">|</li>
                        <li><a href="<?php echo $config['bbs_url'];?>" target="_blank">交流社区</a></li>
                    <?php } ?>
                <?php endif;?>
                <li class="usertips">
                    <a href="javascript:void(0)" class="mycenter"><?php echo $store_session['name']; ?> - 设置</a>
                    <div class="downmenu1">
                        <ul class="userlinks">
                            <li><a href="<?php echo dourl('store:select'); ?>" class="links1">切换店铺</a></li>
                            <?php if (!empty($store_session['update_drp_store_info']) && $store_session['drp_level'] >0 || $store_session['drp_level'] == 0){?>
                            <li><a href="<?php echo dourl('setting:store'); ?>" class="links2">店铺设置</a></li>
                            <li><a href="<?php echo dourl('account:company'); ?>" class="links3">公司设置</a></li>
                            <?php } ?>
                            <li><a href="<?php echo dourl('account:personal'); ?>" class="links4">帐号设置</a></li>
                            <?php if(empty($version) && empty($_SESSION['sync_store'])){?>
                                <li class="divide"></li>
                                <li><a href="<?php echo dourl('user:logout'); ?>">退出登录</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        if ("<?php echo !empty($open_store) ? $open_store : ''; ?>" == '') {
            window.location.href = '<?php echo $store_select; ?>';
        }
    })
</script>