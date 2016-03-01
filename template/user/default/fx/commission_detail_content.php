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
    .section-title .sub-title {
        font-size: 14px;
        font-weight: bold;
        height: 34px;
        line-height: 34px;
    }
    .order-no {
        color: #FF6600;
        float: left;
    }
    .store-name {
        width: 300px;
        float: left;
    }
    .profit {
        width: 200px;
        color:green;
        float: right;
        text-align: right;
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
    .tb-cost-price {
        color:red;
        font-weight: bold;
    }
    .desc {
        padding:10px 0 0 10px;
        color:gray;
        border: 1px dashed #E4E4E4;
    }
</style>
<h1 class="order-title">订单号：<?php echo $order['order_no']; ?></h1>
<?php foreach ($orders as $order) { ?>
<div class="section section-express">
    <div class="section-title clearfix">
        <div class="sub-title">
            <div class="store-name"><?php if (!empty($order['seller_drp_level'])){ ?><span style="color: red"><?php echo $order['seller_drp_level']; ?></span> 级分销商<?php } else { ?><?php if ($order['is_wholesale']) { ?>经销商<?php } else { ?>供货商<?php } ?><?php } ?>：<a href="<?php echo $order['seller_store']; ?>" target="_blank"><?php echo $order['seller']; ?></a></div>
            <div class="order-no"><?php if (empty($order['user_order_id'])) { ?>主订单号<?php } else { ?>订单号<?php } ?>：<?php echo $order['order_no']; ?></div>
            <div class="profit">利润：+<?php echo $order['profit']; ?><span class="open-close"><a href="javascript:void(0);">+ 展开</a></span></div>
        </div>
    </div>
    <div class="section-detail" style="display: none">
        <table class="table order-goods">
            <thead>
            <tr>
                <th class="tb-thumb"></th>
                <th class="tb-name">商品名称</th>
                <th class="tb-pric align-right">成本价（元）</th>
                <?php if (!empty($order['is_fx'])) { ?>
                <th class="tb-price align-right">零售价（元）</th>
                <?php } else if ($order['type'] == 5) { ?>
                <th class="tb-price align-right">批发价（元）</th>
                <?php } else { ?>
                <th class="tb-price align-right">分销价（元）</th>
                <?php } ?>
                <th class="tb-num align-right">数量</th>
                <th class="tb-total align-right">利润（元）</th>
                <th class="tb-postage align-right">运费（元）</th>
            </tr>
            </thead>
            <tbody>
            <?php
                $key = 0;
                foreach ($order['products'] as $product) { ?>
                <?php $skus = !empty($product['sku_data']) ? unserialize($product['sku_data']) : ''; ?>
                <?php $comments = !empty($product['comment']) ? unserialize($product['comment']) : ''; ?>
                <tr data-order-id="<?php echo $order['order_id']; ?>">
                    <td class="tb-thumb" <?php if (!empty($comments)) { ?>rowspan="2"<?php } ?>><img src="<?php echo $product['image']; ?>" width="60" height="60" /></td>
                    <td class="tb-name">
                        <a href="<?php echo $config['wap_site_url'];?>/good.php?id=<?php echo $product['product_id'];?>&store_id=<?php echo $order['store_id']; ?>" class="new-window" target="_blank"><?php echo $product['name']; ?></a>
                        <?php if ($skus) { ?>
                            <p>
                                <span class="goods-sku"><?php foreach ($skus as $sku) { ?><?php echo $sku['name']; ?>: <?php echo $sku['value']; ?>&nbsp;<?php } ?></span>
                            </p>
                        <?php } ?>
                        <p><a href="javascript:void(0);">商品来源：<?php echo $product['from']; ?></a></p>
                    </td>
                    <td class="tb-price tb-cost-price align-right"><?php echo $product['cost_price']; ?></td>
                    <td class="tb-price align-right"><?php echo $product['pro_price']; ?></td>
                    <td class="tb-num align-right"><?php echo $product['pro_num']; ?></td>
                    <td class="tb-total align-right">+<?php echo $product['profit']; ?></td>
                    <?php if (count($order['comment_count']) > 0 && $key == 0) { ?>
                        <td class="tb-postage align-right" rowspan="<?php echo $order['rows']; ?>">
                            <?php echo $order['postage']; ?>
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
            <?php $key++; } ?>
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
                    <tr>
                        <td>应收款：</td>
                        <td><span class="ui-money-income">￥<span class="order-total"><?php echo $order['total']; ?></span></span></td>
                    </tr>
                    <tr>
                        <td><b><?php if (!empty($supplier['drp_supplier_id'])) { ?>分销利润：<?php } else { ?>利润：<?php } ?></b></td>
                        <td><span class="ui-money-income">￥<span class="order-total"><b><?php echo $order['profit']; ?></b></span></span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="desc">
    <p>* <b>主订单号：</b>消费者订单的订单号</p>
    <p>* <b>订单号：</b>上级分销商及供货商订单的订单号</p>
    <p>* <b>零售价：</b>终端销售价格</p>
    <p>* <b>批发价：</b>经销商批发供货商商品的批发价格</p>
    <p>* <b>分销价：</b>下级分销商分销上级分销商或供货商的商品的分销价格</p>
</div>
