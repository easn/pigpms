<script type="text/javascript">
    var product_groups_json = '<?php echo $product_groups_json; ?>';
</script>
<style type="text/css">
    .red {
        color: red;
    }
</style>
<div class="goods-list">
    <div class="js-list-filter-region clearfix ui-box" style="position: relative;">
        <div>
            <h3 class="list-title js-goods-list-title">批发过来的商品</h3>
            <div class="js-list-tag-filter ui-chosen" style="width: 200px;">
                <div class="chosen-container chosen-container-single" style="width: 0px;" title="">
                    <a class="chosen-single" tabindex="-1"><span>所有分组</span>
                        <div><b></b></div>
                    </a>
                    <div class="chosen-drop">
                        <div class="chosen-search"><input type="text" autocomplete="off"></div>
                        <ul class="chosen-results">
                            <li class="active-result result-selected highlighted" style="" data-option-array-index="0">所有分组</li>
                            <?php foreach ($product_groups as $product_group) { ?>
                                <li class="active-result" style="" data-option-array-index="<?php echo $product_group['group_id']; ?>"><?php echo $product_group['group_name']; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="js-list-search ui-search-box">
                <input class="txt" type="text" placeholder="搜索" value="">
            </div>
        </div>
    </div>
    <div class="ui-box">
        <table class="ui-table ui-table-list" style="padding: 0px;">
            <thead class="js-list-header-region tableFloatingHeaderOriginal" style="position: static; top: 0px; margin-top: 0px; left: 601.5px; z-index: 1; width: 850px;">
            <?php if (!empty($products)) { ?>
                <tr>
                    <th class="checkbox cell-35" colspan="3" style="min-width: 332px; max-width: 332px;">
                        <label class="checkbox inline">
                            <!--<input type="checkbox" class="js-check-all">-->
                            商品
                        </label>
                    </th>
                    <th class="cell-10" style="min-width: 85px; max-width: 85px;">批发价</th>
                    <th class="cell-10" style="min-width: 85px; max-width: 85px;">建议零售价</th>
                    <th class="cell-10" style="min-width: 85px; max-width: 85px;">利润</th>
                    <th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;">
                        <a href="javascript:;" class="orderby" data-orderby="quantity">库存</a>
                    </th>
                    <th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;">
                        <a href="javascript:;" class="orderby" data-orderby="sales">总销量</a>
                    </th>
                    <th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;">
                        状态
                    </th>
                    <th class="cell-8 text-right" style="min-width: 127px; max-width: 127px;">操作</th>
                </tr>
            <?php } ?>
            </thead>
            <tbody class="js-list-body-region">
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td class="checkbox">
                        <!--<input type="checkbox" class="js-check-toggle" value="<?php echo $product['product_id']; ?>" />-->
                    </td>
                    <td class="goods-image-td">
                        <div class="goods-image js-goods-image ">
                            <img src="<?php echo $product['image']; ?>" />
                        </div>
                    </td>
                    <td class="goods-meta">
                        <p class="goods-title">
                            <a href="<?php echo $config['wap_site_url']; ?>/good.php?id=<?php echo $product['product_id']; ?>" target="_blank" class="new-window" title="<?php echo $product['name']; ?>">
                                <?php if (!empty($_POST['keyword'])) { ?>
                                    <?php echo str_replace($_POST['keyword'], '<span class="red">' . $_POST['keyword'] . '</span>', $product['name']); ?>
                                <?php } else { ?>
                                    <?php echo $product['name']; ?>
                                <?php } ?>
                            </a>
                        </p>
                        <p>
                            <span class="goods-price" ><span style="color:#2A2727;">供货商</span>：<?php echo $product['supplier_name']; ?></span>
                        </p>
                        <?php if ($product['is_recommend']) { ?>
                            <img class="js-help-notes" src="<?php echo TPL_URL; ?>/images/jian.png" alt="推荐" width="19" height="19" /><br/>
                        <?php } ?>
                    </td>
                    <td>
                        <div>￥<?php echo $product['wholesale_price']; ?></div>
                    </td>
                    <td>
                        <span>￥<?php echo $product['sale_min_price']; ?></span>
                        <span>- ￥<?php echo $product['sale_max_price']; ?></span>
                    </td>
                    <td>
                        <span>￥<?php echo number_format($product['sale_min_price'] - $product['wholesale_price'], 2, '.', ''); ?></span>

                        <span>- ￥<?php echo number_format($product['sale_max_price'] - $product['wholesale_price'], 2, '.', ''); ?></span>
                    </td>
                    <td class="text-right"><?php echo $product['quantity']; ?></td>
                    <td class="text-right"><?php echo $product['sales']; ?></td>
                    <td class="text-right">
                        <?php if (empty($product['status'])){?>
                        仓库中
                        <?php } else if ($product['status']==1){?>
                        <span class="red">热销中</span>
                        <?php }?>
                    </td>
                    <td class="text-right">
                        <p>
                            <?php if (empty($product['status'])){?>
                            <a href="<?php echo dourl('goods:edit', array('id' => $product['product_id'], 'referer' => 'is_wholesale')); ?>">编辑</a><span>-</span>
                            <?php } else if ($product['status']==1){?>
                            <a href="javascript:void(0);" class="js-unload" data="<?php echo $product['product_id']; ?>">下架</a><span>-</span>
                            <?php }?>
                            <a href="javascript:void(0);" class="js-delete" data="<?php echo $product['product_id']; ?>">删除</a><span>-</span>
                            <a href="javascript:void(0);" class="js-promotion-btn" <?php if (!empty($product['supplier_id'])) { ?>data-fx="true"<?php } else { ?>data-fx="false"<?php } ?> data="<?php echo $product['product_id']; ?>">推广商品</a>
                        </p>
                        <p><a href="javascript:;" class="js-copy hover-show">复制</a></p>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php if (empty($products)) { ?>
            <div class="js-list-empty-region"><div><div class="no-result">还没有相关数据。</div></div></div>
        <?php } ?>
    </div>
    <div class="js-list-footer-region ui-box">
        <?php if (!empty($products)) { ?>
            <div>
                <!--<div class="pull-left">
                    <a href="javascript:;" class="ui-btn js-batch-tag" data-loading-text="加载...">改分组</a>
                    <a href="javascript:;" class="ui-btn js-batch-unload">下架</a>
                    <a href="javascript:;" class="ui-btn js-batch-delete">删除</a>
                    <a href="javascript:;" class="ui-btn js-batch-discount">会员折扣</a>
                </div>-->
                <div class="js-page-list ui-box pagenavi"><?php echo $page;?></div>
            </div>
        <?php } ?>
    </div>
</div>