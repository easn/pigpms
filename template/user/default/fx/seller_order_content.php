<style type="text/css">
    .ui-table-order tr {
        border: 1px solid #ccc;
    }

    .ui-table-order tr td {
        border: 1px solid #ddd;
    }

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
    .control-action {
        padding-top: 5px;
    }


    .popover-inner {
        padding: 3px;
        width: 320px;
        overflow: hidden;
        background: #000000;
        background: rgba(0, 0, 0, 0.8);
        border-radius: 4px;
        -webkit-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
        box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
    }
    .popover.bottom .arrow {
        /* left: 50%; */
        margin-left: 105px;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #000000;
    }
    .popover.bottom .arrow:after {
        top: 1px;
        border-bottom-color: #ffffff;
        border-top-width: 0;
    }
    .team-opt-wrapper .block-help>a:hover {
        background: #4b0;
        color:#fff
    }


    .block-help>a {
        display: inline-block;
        width: 16px;
        height: 16px;
        line-height: 18px;
        border-radius: 8px;
        font-size: 12px;
        text-align: center;
        background: #bbb;
        color: #fff;
    }
    .block-help>a:after {
        content: "?";
    }
    .hide{display:none}
    .bgcolor{
        background-color: lightblue;
    }

    .order-no {
        color:#FF6600;
    }
    .cost-price {
        color:red;
    }
    .profit {
        color: green;
    }


    .section-title .sub-title {
        font-weight: bold;
        height: 34px;
        line-height: 34px;
    }
    .order-no {
        color: #FF6600;
        float: left;
    }
    .store-name {
        float: left;
    }
    .profit {
        color:green;
        float: right;
    }
    .align-right {
        text-align: right!important;
    }
    .hide {
        display: none!important;
    }
    .open-close {
        display: inline-block;
        font-weight: normal;
        font-size: 12px;
        margin-left: 20px;
    }
    .tb-total {
        color:green;
        font-weight: bold;
    }
</style>

<div>
<div class="js-list-filter-region clearfix">
    <div>
        <form class="form-horizontal ui-box list-filter-form" onsubmit="return false;">
            <div class="clearfix">
                <div class="filter-groups">
                    <div class="control-group">
                        <label class="control-label">订单号：</label>
                        <div class="controls">
                            <input type="text" name="order_no" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">收货人姓名：</label>
                        <div class="controls">
                            <input type="text" name="delivery_user" />
                        </div>
                    </div>
                </div>
                <div class="pull-left">
                    <div class="time-filter-groups clearfix">
                        <div class="control-group">
                            <label class="control-label">下单时间：</label>
                            <div class="controls">
                                <input type="text" name="start_time" value="" class="js-start-time" id="js-start-time" />
                                <span>至</span>
                                <input type="text" name="end_time" value="" class="js-end-time" id="js-end-time" />
                                <span class="date-quick-pick" data-days="7">最近7天</span>
                                <span class="date-quick-pick" data-days="30">最近30天</span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">收货人手机：</label>
                        <div class="controls">
                            <input type="text" name="delivery_tel" />
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
    </div>
</div>
<div class="ui-box orders">
<?php if (!empty($orders)) { ?>
    <table class="ui-table-order">
    <thead class="js-list-header-region tableFloatingHeaderOriginal"><tr><th class="" colspan="2">商品</th>
        <th class="price-cell">单价/数量</th>
        <th class="aftermarket-cell">售后</th>
        <th class="customer-cell">买家</th>
        <th class="time-cell">
            <a href="javascript:;" class="orderby orderby_add_time" data-orderby="add_time">下单时间<span class="orderby-arrow desc"></span></a>
        </th>
        <th class="state-cell">
            订单状态
        </th>
        <th class="pay-price-cell"><a href="javascript:;" class="orderby orderby_total" data-orderby="total">实付金额</a></th>
    </tr>
    </thead>
    <?php foreach ($orders as $order) { ?>
        <tbody>
        <tr class="separation-row">
            <td colspan="8"></td>
        </tr>
        <tr class="header-row">
            <td colspan="8">
                <div>
                    <b class="order-no">订单号: <?php echo $order['order_no']; ?></b>
                    <?php
                    if ($order['payment_method'] == 'codpay') {
                        ?>
                        <span>支付方式 ：货到付款</span>
                    <?php
                    } else if ($order['payment_method'] == 'peerpay') {
                        ?>
                        <span>支付方式 ：找人代付</span>
                    <?php
                    }
                    ?>

                    <?php if ($order['type'] == 3) { ?>
                        <span class="platform-tag">分销</span>
                        <span class="c-gray">
							来自于<span style="color: red"><?php echo $order['drp_level']?></span>级分销商：<?php echo $order['seller']; ?>
						</span>
                    <?php } else if ($order['type'] == 5) { ?>
                    <span class="platform-tag" style="background-color:#07d">批发</span>
							<span class="c-gray">
							经销商：<?php echo $order['seller']; ?>
                                <?php } ?>
                </div>
                <div class="clearfix">
                    <?php if (!empty($order['trade_no'])) { ?>
                        <div style="margin-top: 4px;margin-right: 20px;" class="pull-left">
                            外部订单号: <span class="c-gray"><?php echo $order['trade_no']; ?></span>
                        </div>
                    <?php } ?>
                    <?php if (!empty($order['third_id'])) { ?>
                        <div style="margin-top: 4px;" class="pull-left">
                            支付流水号: <span class="c-gray"><?php echo $order['third_id']; ?></span>
                        </div>
                    <?php } ?>
                </div>
            </td>

        </tr>

        <?php foreach ($order['products'] as $key => $product) {?>
            <tr class="content-row">
                <td class="image-cell">
                    <img src="<?php echo $product['image']; ?>" />
                </td>
                <td class="title-cell">
                    <p class="goods-title">
                        <a href="<?php echo $config['wap_site_url'];?>/good.php?id=<?php echo $product['product_id'];?>&store_id=<?php echo $order['store_id']; ?>" target="_blank" class="new-window" title="<?php echo $product['name']; ?>">
                            <?php echo $product['name']; ?>
                        </a>
                    </p>
                    <p>
                        <?php $skus = !empty($product['sku_data']) ? unserialize($product['sku_data']) : ''; ?>
                        <?php if ($skus) { ?>
                            <?php foreach ($skus as $sku) { ?>
                                <span class="goods-sku"><?php echo $sku['value']; ?></span>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($product['is_fx']) { ?><span class="platform-tag">分销</span><?php } ?>
                    </p>
                    <p>商品来源：<?php echo $product['from']; ?></p>
                </td>
                <td class="price-cell">
                    <!--<p><?php echo $product['pro_price']; ?></p>-->
                    <p>(<?php echo $product['pro_num']; ?>件)</p>
                    <?php if (!empty($order['is_fx']) || !empty($order['fx_order_id']) || !empty($order['user_order_id'])) { ?>
                        <p class="cost-price">给分销商的价格：<?php echo $product['cost_price']; ?></p>
                        <p class="profit">分销商零售价格：
                        <?php if($order['drp_level'] >= 3 && $order['type'] == 3){?>
                            <?php echo $product["drp_level_3_price"]; ?></p>
                        <?php } else if ($order['drp_level'] < 3 && $order['type'] == 3) {?>
                            <?php echo $product["drp_level_{$order['drp_level']}_price"]; ?></p>
                        <?php } else if ($order['type'] == 5){?></p>
                            <?php echo $product["wholesale_price"]; ?></p>
                        <?php }?>
                    <?php } ?>
                </td>
                <td class="aftermarket-cell">
                </td>
                <?php if (count($order['products']) > 0 && $key == 0) { ?>
                    <td class="customer-cell" rowspan="<?php echo count($order['products']); ?>">
                        <?php if (empty($order['is_fans'])) { ?>
                            <p>非粉丝</p>
                        <?php } else if (!empty($order['address_user'])) { ?>
                            <p class="user-name"><?php echo $order['address_user']; ?></p>
                            <?php echo $order['address_tel']; ?>
                        <?php } else { ?>
                            <p><?php echo $order['buyer']; ?></p>
                        <?php } ?>
                    </td>
                    <td class="time-cell" rowspan="<?php echo count($order['products']); ?>">
                        <div class="td-cont">
                            <?php echo date('Y-m-d H:i:s', $order['add_time']); ?>
                        </div>
                    </td>
                    <td class="state-cell" rowspan="<?php echo count($order['products']); ?>">
                        <div class="td-cont">
                            <p class="js-order-state">
                                <?php
                                if ($order['shipping_method'] == 'selffetch' && $order['status'] <= 2) {
                                    $address = unserialize($order['address']);
                                    echo '门店：' . $address['name'];
                                } else {
                                    echo $order_status[$order['status']];
                                }
                                ?>
                            </p>
                        </div>
                    </td>
                    <td class="pay-price-cell" rowspan="<?php echo count($order['products']); ?>">
                        <div class="td-cont text-center">
                            <div>

                                <p>
                                    <span class="c-gray">(含运费: <span style="color:red;"><?php echo $order['postage']; ?></span>)</span>
                                </p>
                                <?php if (empty($order['is_fx'])) { ?>
                                    <?php  if ($order['status'] == 0 || $order['status'] == 1) { ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php if (!empty($order['is_fx']) || !empty($order['fx_order_id']) || !empty($order['user_order_id'])) { ?>
                                    <p class="cost-price">该订单金额：<?php echo $order_detail_info[$order['user_order_id']]['pay_money'];?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        <tr class="separation-row">
            <td colspan="8"></td>
        </tr>
        <tr class="header-row" style="border-bottom:solid 20px #ddd;">
            <td colspan="8">
                <?php foreach ($orders_detail[$order['order_id']] as $tmp_detail) { ?>
                    <?php $i=1; foreach($tmp_detail as $detail):?>
                        <div class="section-title clearfix">
                            <div class="section section-express">
                                <div class="section-title clearfix">
                                    <div class="sub-title">
                                        <?php if($i == 1){?>
                                            <div class="store-name" style="width:200px;">
                                                <span>本店分润情况</span>
                                            </div>
                                        <?php } else {?>
                                            <div class="store-name" style="width:300px;">
                                                <?php if (!empty($detail['seller_drp_level'])){ ?><span style="color: red"><?php echo $detail['seller_drp_level']; ?></span> 级分销商<?php } else { ?><?php if ($detail['is_wholesale']) { ?>经销商<?php } else { ?>本店商品<?php } ?><?php } ?>：<a href="<?php echo $detail['seller_store']; ?>" target="_blank"><?php echo $detail['seller']; ?></a>&nbsp;&nbsp;<span>分润情况</span>
                                            </div>
                                        <?php }?>
                                        <?php if($i == 1){?>


                                            <div style="margin-left: 90px;" class="order-no">
                                                销售价：<?php echo $detail['pro_price'];?>
                                            </div>
                                        <?php }else {?>
                                            <div style="margin-left: 90px;" class="order-no">
                                                成本：<?php echo $detail['cost_price'];?>
                                            </div>

                                            <div style="margin-left: 90px;" class="order-no">
                                                <?php if ($detail['is_wholesale']) { ?>
                                                    批发价：<?php echo $detail['pro_price'];?>
                                                <?php } else {?>
                                                    分销价：<?php echo $detail['pro_price'];?>
                                                <?php }?>
                                            </div>
                                        <?php } ?>
                                        <div class="profit">利润：+<?php echo $detail['profit']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $i++; endforeach; ?>
                <?php } ?>
            </td>
        </tr>
        </tbody>
    <?php } ?>
    </table>
<?php } ?>
</div>
<div class="js-list-footer-region ui-box">
    <div>
        <div class="pagenavi">
            <?php echo $page; ?>
        </div>
    </div>
</div>
</div>

