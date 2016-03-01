<style type="text/css">
    input[type="radio"], input[type="checkbox"] {
        margin:0
    }
    .controls {
        margin-top: 5px;
    }
    .platform-tag {
        display: inline-block;
        vertical-align: middle;
        padding: 2px 7px 2px 7px;
        background-color: #f60;
        color: #fff;
        font-size: 12px;
        line-height: 14px;
        border-radius: 2px;
        margin: 0;
        border: 0;
        font: inherit;
    }
</style>
<div class="goods-edit-area">
<ul class="ui-nav-tab">
    <li data-next-step="2" class="js-switch-step js-step-2 active"><a href="javascript:;">分销商品设置</a></li>
</ul>
<div id="step-content-region">
<form class="form-horizontal fm-goods-info">



<div id="step-2" class="js-step">
<div id="base-info-region" class="goods-info-group">
    <div class="goods-info-group-inner">
        <div class="info-group-title vbox">
            <div class="group-inner">商品信息</div>
        </div>
        <div class="info-group-cont vbox">
            <div class="group-inner">
                <div class="control-group">
                    <label class="control-label">商品图片：</label>
                    <div class="controls">
                        <img src="<?php echo $product['image']; ?>" width="100" height="100" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">商品类目：</label>
                    <div class="controls">
                        <?php echo $product['category']; ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">商品名称：</label>

                    <div class="controls">
                        <?php echo $product['name']; ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">商品类型：</label>
                    <div class="controls">
                        <?php if (!empty($product['wholesale_product_id'])) { ?>
                        <i class="platform-tag" style="background-color: #07d">批发商品</i>
                        <?php } else { ?>
                        <i class="platform-tag" style="background-color: green">自营商品</i>
                        <?php } ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">商品库存：</label>
                    <div class="controls">
                        <?php echo $product['quantity']; ?>
                    </div>
                </div>
                <?php if (!empty($product['wholesale_product_id'])) { ?>
                <div class="control-group">
                    <label class="control-label">批发成本：</label>
                    <div class="controls">
                        ￥<?php echo $product['wholesale_price']; ?>
                    </div>
                </div>
                <?php } ?>
                <div class="control-group">
                    <label class="control-label">本店售价：</label>
                    <div class="controls">
                        ￥<?php echo $product['price']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="goods-info-region" class="goods-info-group">
    <div class="goods-info-group-inner">
        <div class="info-group-title vbox">
            <div class="group-inner">分销设置</div>
        </div>
        <div class="info-group-cont vbox">
            <div class="group-inner">
                <div class="control-group">
                    <label class="control-label">商家推荐：</label>
                    <div class="controls">
                        <input type="checkbox" name="is_recommend" value="1" <?php if ($product['is_recommend']) { ?>checked="true"<?php } ?> /> 是 <span class="gray">(全网最低价格、新品、热卖商品)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="sku-info-region" class="goods-info-group"><div class="goods-info-group-inner"><div class="info-group-title vbox">
                <div class="group-inner">库存/规格</div>
            </div>
            <div class="info-group-cont vbox">
                <div class="group-inner">
                    <?php if (!empty($sku_content)) { ?>
                    <div class="js-goods-stock control-group">
                        <div id="stock-region" class="controls sku-stock" style="margin-left: 0;">
                            <div class="sku-price-1">
                                <br/>
                                <p class="tip" style="color: orange">* <b>温馨提示：</b>如果您为各级分销商设置了统一的成本价格或分销价格就可以使用微店的多级分销功能。</p>
                                <?php if (empty($drp_level)) { ?>
                                <br/>
                                <b style="color: #07d">一级分销商定价(<b class="tip" style="color: red">温馨提示：成本价是您给分销商的价格,分销价是指分销商卖给消费者的终端价</b>)</b>
                                <table class="table-sku-stock table-sku-stock-1" data-level="1">
                                    <?php echo $sku_content2; ?>
                                </table>
                                <br/>
                                <b style="color: #07d">二级分销商定价(<b class="tip" style="color: red">温馨提示：成本价是您给分销商的价格,分销价是指分销商卖给消费者的终端价</b>)</b>
                                <table class="table-sku-stock table-sku-stock-2" data-level="2">
                                    <?php echo $sku_content2; ?>
                                </table>
                                <br/>
                                <b style="color: #07d">三级及以上分销商定价(<b class="tip" style="color: red">温馨提示：成本价是您给分销商的价格,分销价是指分销商卖给消费者的终端价</b>)</b>
                                <table class="table-sku-stock table-sku-stock-3" data-level="3">
                                    <?php echo $sku_content2; ?>
                                </table>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="control-group">
                        <p class="tip" style="color: orange">* <b>温馨提示：</b>各级分销商的<b style = 'color:red;'>成本价格</b>是您给分销商的价格,各级分销商的<b style="color:red;">建议售价</b>是分销商卖给消费者的终端价</p>
                        <label class="control-label"><em class="required">*</em>成本价格：</label>
                            <div class="cost-price-1">
                                <?php if (empty($drp_level)) { ?>
                                    <div class="controls">
                                        <b style="color: #07d">一级分销商成本价</b>
                                        <div class="input-prepend">
                                            <span class="add-on">￥</span><input type="text" maxlength="10" name="cost_price" class="cost-price-1 input-small" />
                                        </div>
                                    </div>
                                    <div class="controls">
                                        <b style="color: #07d">二级分销商成本价</b>
                                        <div class="input-prepend">
                                            <span class="add-on">￥</span><input type="text" maxlength="10" name="cost_price" class="cost-price-2 input-small" />
                                        </div>
                                    </div>
                                    <div class="controls">
                                        <b style="color: #07d">三级及以上分销商</b>
                                        <div class="input-prepend">
                                            <span class="add-on">￥</span><input type="text" maxlength="10" name="cost_price" class="cost-price-3 input-small" />
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label"><em class="required">*</em>建议售价：</label>
                        <div class="price-1">
                            <?php if (empty($drp_level)) { ?>
                            <div class="controls">
                                <b style="color:#07d">一级分销商零售价</b>
                                <div class="input-prepend">
                                    <span class="add-on">￥</span><input type="text" maxlength="10" name="fx-price" class="fx-price-1 input-small" />
                                </div>
                            </div>
                            <div class="controls">
                                <b style="color:#07d">二级分销商零售价</b>
                                <div class="input-prepend">
                                    <span class="add-on">￥</span><input type="text" maxlength="10" name="fx-price" class="fx-price-2 input-small" />
                                </div>
                            </div>
                            <div class="controls">
                                <b style="color:#07d">三级及以上分销商</b>
                                <div class="input-prepend">
                                    <span class="add-on">￥</span><input type="text" maxlength="10" name="fx-price" class="fx-price-3 input-small" />
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</form>
</div>
</div>
<style type="text/css">
    .app-actions {
        position: fixed;
        bottom: 0;
        width: 850px;
        padding-top: 20px;
        clear: both;
        text-align: center;
        z-index: 2;
    }
    .app-actions .form-actions {
        padding: 10px;
        margin: 0;
    }
</style>
<div class="app-actions" style="bottom: 0px;">
    <div class="form-actions text-center">
        <input class="btn btn-primary js-btn-load js-btn-save" type="button" value="保存" data-product-id="<?php echo $product['product_id']; ?>" data-loading-text="保存...">
        <input class="btn js-btn-unload js-btn-cancel" type="button" value="取消" data-product-id="<?php echo $product['product_id']; ?>" data-loading-text="取消...">
    </div>
</div>