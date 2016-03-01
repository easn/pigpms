<style type="text/css">
    .red {
        color:red;
    }

    .ui-nav {
        border: none;
        background: none;
        position: relative;
        border-bottom: 1px solid #e5e5e5;
        margin-bottom: 15px;
        margin-top: 23px;
    }
    .pull-left {
        float: left;
    }
    .ui-nav ul {
        zoom: 1;
        margin-bottom: -1px;
        margin-left: 1px;
    }
    .ui-nav li {
        float: left;
        margin-left: -1px;
    }
    .ui-nav li a {
        display: inline-block;
        padding: 0 12px;
        line-height: 32px;
        color: #333;
        border: 1px solid #e5e5e5;
        background: #f8f8f8;
        min-width: 80px;
        text-align: center;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .ui-nav li.active a {
        font-size: 100%;
        border-bottom-color: #fff;
        background: #fff;
        margin:0px;
        line-height: 32px;
    }
</style>
<?php $version = option('config.weidian_version');?>
<div class="goods-list">
<div class="js-list-filter-region clearfix ui-box" style="position: relative;">
    <div class="widget-list-filter">
        <nav class="ui-nav clearfix">
            <ul class="pull-left">
              <li class="<?php echo $is == 3 ? 'active' : ''?>">
                    <a href="#3" data-is="3">所有的商品</a>
                </li>
                <li class="<?php echo $is == 1 ? 'active' : ''?>">
                    <a href="#1" data-is="1">已设置分销的商品</a>
                </li>
                <?php if(empty($version)){?>
                <li class="<?php echo $is == 2 ? 'active' : ''?>">
                    <a href="#2" data-is="2">已设置批发的商品</a>
                </li>
                <?php } ?>
            </ul>
        </nav>
        <div class="market-filter-container">

            <div class="js-list-tag-filter ui-chosen market-filter">
                <div class="chosen-container chosen-container-single" style="width: 160px!important;" title=""><a
                        class="chosen-single" tabindex="-1"><span>所有类目</span>
                        <div><b></b></div>
                    </a>

                    <div class="chosen-drop">
                        <ul class="chosen-results">
                            <li class="active-result result-selected highlighted" data-option-array-index="0">所有类目</li>
                            <?php foreach ($categories as $category) { ?>
                            <li class="active-result" style="" data-option-array-index="<?php echo $category['cat_fid']; ?>|<?php echo $category['cat_id']; ?>"><?php if ($category['cat_level'] > 1) { ?><?php echo str_repeat('&nbsp;&nbsp;&nbsp;', $category['cat_level']) . '|-- ' . $category['cat_name']; ?><?php } else { ?><b><?php echo $category['cat_name'];?></b><?php } ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="js-list-search">
                <input class="js-keyword txt market-serach-input" type="text" placeholder="商品名称" value="" style="left: 185px!important;">
                <input type="button" class="market-search-btn ui-btn ui-btn-primary" value="搜索" style="left: 408px" />
            </div>
        </div>
    </div>
</div>
<div class="ui-box">
<table class="ui-table ui-table-list" style="padding: 0px;">
<?php if (!empty($products)) { ?>
<thead class="js-list-header-region tableFloatingHeaderOriginal">
<tr class="widget-list-header">
    <th class="checkbox cell-35" colspan="3">
        <label class="checkbox inline">
            <input type="checkbox" class="js-check-all">
            商品
        </label>
    </th>
    <?php if($is == 3) {?>
    <th class="cell-10 text-right"><a href="javascript:;" data-orderby="fx_price">本店售价</a></th>
    <?php }else{?>
        <th class="cell-10 text-right"><a href="javascript:;" data-orderby="fx_price">成本价</a></th>
    <?php }?>
    <?php if($is != 3) {?>
    <th class="cell-10 text-right">建议零售价</th>
    <th class="cell-10 text-right">利润</th>
    <?php }?>
    <th class="cell-8 text-right"><a href="javascript:;" data-orderby="stock_num">库存</a></th>
    <th class="cell-10 text-right"><a href="javascript:;" data-orderby="sold_num">销量</a></th>
    <th class="cell-10 text-right"><a href="javascript:;" data-orderby="fx_count">人气</a></th>
    <th class="cell-15 text-right">操作</th>
</tr>
</thead>
<tbody class="js-list-body-region">
<?php foreach($products as $product) { ?>
<tr class="widget-list-item">
    <td class="checkbox">
        <input type="checkbox" class="js-check-toggle" value="<?php echo $product['product_id']; ?>" />
    </td>
    <td class="goods-image-td">
        <div class="goods-image js-goods-image">
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
        <?php if ($product['is_recommend']) { ?>
        <img class="js-help-notes" src="<?php echo TPL_URL; ?>/images/jian.png" alt="推荐" width="19" height="19" />
        <?php } ?>
    </td>

    <?php if($is == 3 && !$product['wholesale_product_id'] > 0){?>
        <td class="text-right">
            <p>￥<?php echo $product['price']; ?></p>
        </td>
    <?php } else if($is == 3 && $product['wholesale_product_id'] > 0){?>
        <td class="text-right">
            <p>￥<?php echo $product['price']; ?></p>
        </td>
    <?php }?>

    <?php if($is == 1){?>
        <td class="text-right">
            <p>￥<?php echo $product['cost_price']; ?></p>
        </td>
    <?php } else if($is == 2 ){?>
        <td class="text-right">
            <p>￥<?php echo $product['wholesale_price']; ?></p>
        </td>
    <?php }?>

    <?php if($is == 1){?>
        <td class="text-right">
            <p>￥<?php echo $product['min_fx_price']; ?></p>
        </td>
    <?php } else if($is == 2){?>
        <td class="text-right">
            <p>￥<?php echo $product['sale_min_price']; ?></p>
            <p>-￥<?php echo $product['sale_max_price']; ?></p>
        </td>
    <?php }?>

   <?php if($is != 3){?>
    <td class="text-right">
    <?php if ($is == 1){?>
        <p>￥<?php echo number_format($product['drp_level_1_price'] - $product['drp_level_1_cost_price'], 2, '.', ''); ?></p>
    <?php }else if($is==2){?>
        <p>￥<?php echo number_format($product['sale_min_price'] - $product['wholesale_price'], 2, '.', ''); ?></p>

        <p>- ￥<?php echo number_format($product['sale_max_price'] - $product['wholesale_price'], 2, '.', ''); ?></p>
    <?php }?>
    </td>
   <?php }?>
    <td class="text-right">
        <p><?php echo $product['quantity']; ?></p>
    </td>
    <td class="text-right">
        <?php echo $product['sales']; ?>
    </td>
    <td class="text-right">
        <?php echo $product['pv']; ?>
    </td>

    <td class="text-right">
        <p class="js-opts">
            <?php if($is ==1){?>
            <a href="<?php echo dourl('edit_goods', array('id' => $product['product_id'])); ?>">修改分销</a><br/>
            <a href="javascript:;" data-id="<?php echo $product['product_id']; ?>" data-is="<?php echo $is; ?>" class="js-cancel-to-fx">取消分销</a>
            <?php } else if($is ==2){?>
            <a href="<?php echo dourl('edit_wholesale', array('id' => $product['product_id'])); ?>">修改批发</a><br/>
            <a href="javascript:;" data-id="<?php echo $product['product_id']; ?>" data-is="<?php echo $is; ?>" class="js-cancel-to-wholesale">取消批发</a>
            <?php } else if($is == 3){?>
               <?php if($product['wholesale_product_id'] > 0 && empty($product['is_fx'])){?>
            <a href="<?php echo dourl('goods_fx_setting', array('id' => $product['product_id'], 'role' => 'supplier')); ?>">设置分销</a>
               <?php } else if($product['wholesale_product_id'] > 0 && $product['is_fx'] == 1){?>
                    已分销
               <?php }?>
            <?php if(!$product['wholesale_product_id'] > 0 && empty($product['is_fx'])){?>
                <a href="<?php echo dourl('goods_fx_setting', array('id' => $product['product_id'], 'role' => 'supplier')); ?>">设置分销</a><br/>
            <?php } else if (!$product['wholesale_product_id'] > 0 && !empty($product['is_fx'])){?>
                    已分销<br/>
            <?php }?>
            <?php if(empty($version)){?>
            <?php if(!$product['wholesale_product_id'] > 0 && empty($product['is_wholesale'])){?>
            <a href="<?php echo dourl('goods_wholesale_setting', array('id' => $product['product_id'], 'role' => 'supplier')); ?>">设置批发</a>
            <?php } else if (!$product['wholesale_product_id'] > 0 && !empty($product['is_wholesale'])){?>
            已批发
            <?php }?>
            <?php }?>
            <?php }?>
        </p>
    </td>
</tr>
<?php } ?>
</tbody>
<?php } ?>
</table>
    <div class="js-list-empty-region">
        <?php if (empty($products)) { ?>
        <div>
            <div class="no-result widget-list-empty">还没有相关数据。</div>
        </div>
        <?php } ?>
    </div>
</div>
<div class="js-list-footer-region ui-box">
    <?php if (!empty($products)) { ?>
    <div class="widget-list-footer">
        <div class="pull-left">
            <!--<a href="javascript:;" class="ui-btn js-batch-cancel-to-fx">批量取消分销</a>-->
        </div>
        <input type="hidden" data-is="<?php echo $is;?>" class="page-is">
        <div class="pagenavi ui-box"><?php echo $page; ?></div>
    </div>
    <?php } ?>
</div>
</div>