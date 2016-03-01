<div class="goods-list">
    <div class="js-list-filter-region clearfix">
        <div class="widget-list-filter">
            <form class="form-horizontal ui-box list-filter-form" onsubmit="return false;">
                <div class="clearfix">
                    <div class="pull-left">
                        <div class="time-filter-groups clearfix">
                            <div class="control-group">
                                <label class="control-label">时间：</label>
                                <div class="controls">
                                    <input type="text" name="start_time" id="js-start-time" class="js-start-time" value="<?php echo !empty($_POST['start_time']) ? date('Y-m-d H:i:s', $_POST['start_time']) : ''?>" />
                                    <span>至</span>
                                    <input type="text" name="end_time" id="js-end-time" class="js-end-time" value="<?php echo !empty($_POST['stop_time']) ? date('Y-m-d H:i:s', $_POST['stop_time']) : ''?>" />
                                    <span class="date-quick-pick" data-days="7">最近7天</span>
                                    <span class="date-quick-pick" data-days="30">最近30天</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="ui-btn-group">
                            <a href="javascript:;" class="ui-btn ui-btn-primary js-filter" data-loading-text="正在筛选...">筛选</a>
                        </div>
                    </div>
                </div>
            </form>
        </div></div>
    <div class="ui-box">
        <table class="ui-table ui-table-list" style="padding: 0px;">
            <?php if (!empty($sellerRank)) { ?>
                <thead class="js-list-header-region tableFloatingHeaderOriginal">
                <tr class="widget-list-header">
                    <th colspan="2">分销商</th>
                    <th>客服电话</th>
                    <th>客服 QQ</th>
                    <th>客服微信</th>
                    <th>状态</th>
                    <th style="text-align: right">销售额(元)</th>
                    <th style="text-align: center">注册时间</th>
                    <th style="text-align: center">操作</th>
                </tr>
                </thead>
                <tbody class="js-list-body-region">
                <?php foreach ($sellerRank as $seller) { ?>
                    <tr class="widget-list-item">
                        <td class="goods-image-td">
                            <div class="goods-image">
                                <a href="<?php echo option('config.wap_site_url'); ?>/home.php?id=<?php echo $seller['store_id']; ?>" target="_blank"><img src="<?php if ($seller['logo'] == '' || $seller['logo'] == './upload/images/') { ?><?php echo TPL_URL; ?>/images/logo.png<?php } else { ?><?php echo $seller['logo']; ?><?php } ?>" /></a>
                            </div>
                        </td>
                        <td class="goods-meta">
                            <a class="new-window" href="<?php echo option('config.wap_site_url'); ?>/home.php?id=<?php echo $seller['store_id']; ?>" target="_blank">
                                <?php if (isset($_POST['keyword']) && $_POST['keyword'] != '') { ?>
                                    <?php echo str_replace($_POST['keyword'], '<span class="red">' . $_POST['keyword'] . '</span>', $seller['name']); ?>
                                <?php } else { ?>
                                    <?php echo $seller['name']; ?>
                                <?php } ?>
                            </a>
                            <br /><span style="color: orange"><?php echo $seller['drp_level']; ?>级分销商</span>
                        </td>
                        <td>
                            <?php echo $seller['service_tel']; ?>
                        </td>
                        <td>
                            <?php if (!empty($seller['service_qq'])) { ?>
                                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $seller['service_qq']; ?>&amp;site=qq&amp;menu=yes"><img src="<?php echo TPL_URL; ?>/images/qq.png" /></a>
                            <?php } else { ?>
                                <img src="<?php echo TPL_URL; ?>/images/unqq.png" />
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo $seller['service_weixin']; ?>
                        </td>
                        <td>
                            <?php if ($seller['status'] == 5) { ?><span style="color:gray">已禁用</span><?php } else if (!empty($seller['drp_approve'])) { ?><span style="color:green">已审核</span><?php } else { ?><span style="color:red">未审核</span><?php } ?>
                        </td>
                        <td style="text-align: right">
                            <?php if ($seller['sales'] > 0) { ?>
                            <a href="<?php dourl('statistics', array('store_id' => $seller['store_id']));?>"><?php echo $seller['sales']; ?></a>
                            <?php } else { ?>
                            <?php echo $seller['sales']; ?>
                            <?php } ?>
                        </td>
                        <td style="text-align: center">
                            <?php echo date('Y-m-d H:i:s', $seller['date_added']); ?>
                        </td>
                        <td style="text-align: center">
                            <a href="javascript:;" class="<?php if ($seller['status'] == 1) { ?>js-drp-disabled<?php } else if ($seller['status'] == 5) { ?>js-drp-enabled<?php } ?>" data-id="<?php echo $seller['store_id']; ?>"><?php if ($seller['status'] == 1) { ?>禁用<?php } else if ($seller['status'] == 5) { ?>启用<?php } ?></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            <?php } ?>
        </table>
        <div class="js-list-empty-region">
            <?php if (empty($sellerRank)) { ?>
                <div>
                    <div class="no-result widget-list-empty">还没有相关数据。</div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="js-list-footer-region ui-box">
        <?php if (!empty($sellerRank)) { ?>
            <div class="widget-list-footer">
                <div class="pagenavi"><?php echo $page; ?></div>
            </div>
        <?php } ?>
    </div>
</div>