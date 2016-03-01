<div class="goods-edit-area">
<ul class="ui-nav-tab">
    <li data-next-step="2" class="js-switch-step js-step-2 active"><a href="javascript:;">分销商品信息修改</a></li>
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
                    <label class="control-label">商品类目：</label>
                    <div class="controls">
                        <?php echo $product['category']; ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">商品名称：</label>

                    <div class="controls">
                        <input class="input-xxlarge" type="text" name="title" value="<?php echo $product['name']; ?>" maxlength="100" <?php if (empty($product['is_edit_name'])) { ?>disabled="true" <?php } ?> />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">商品库存：</label>
                    <div class="controls">
                        <?php echo $product['quantity']; ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">成本价格：</label>
                    <div class="controls">
                        ￥<?php echo $product['cost_price']; ?>
                        <input type="hidden" value="<?php echo $product['price']; ?>" class="cost_price_hidden">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">建议售价：</label>
                    <div class="controls">
                        ￥<?php echo $product['min_fx_price'];?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">商家推荐：</label>
                    <div class="controls">
                        <input type="checkbox" name="is_recommend" value="1" <?php if ($product['is_recommend']) { ?>checked="true"<?php } ?> /> 是 <span class="gray">(全网最低价格、新品、热卖商品)</span>
                    </div>
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
                                <div class="sku-price-1 <?php if (empty($open_drp_setting_price) || empty($unified_price_setting)) { ?><?php } ?>">
                                    <br/>
                                    <p class="tip" style="color: orange">* <b>温馨提示：</b>如果您为各级分销商设置了统一的成本价格或分销价格就可以使用微店的多级分销功能。</p>
                                    <br/>
                                    <b style="color: #07d">一级分销商定价(<b class="tip" style="color: red">温馨提示：成本价是您给分销商的价格,分销价是指分销商卖给消费者的终端价</b>)</b>
                                    <table class="table-sku-stock table-sku-stock-1" data-level="1">
                                        <?php echo $sku_content; ?>
                                    </table>
                                    <br/>
                                    <b style="color: #07d">二级分销商定价(<b class="tip" style="color: red">温馨提示：成本价是您给分销商的价格,分销价是指分销商卖给消费者的终端价</b>)</b>
                                    <table class="table-sku-stock table-sku-stock-2" data-level="2">
                                        <?php echo $sku_content2; ?>
                                    </table>
                                    <br/>
                                    <b style="color: #07d">三级及以上分销商定价(<b class="tip" style="color: red">温馨提示：成本价是您给分销商的价格,分销价是指分销商卖给消费者的终端价</b>)</b>
                                    <table class="table-sku-stock table-sku-stock-3" data-level="3">
                                        <?php echo $sku_content3; ?>
                                    </table>
                                </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="control-group">
                        <p class="tip" style="color: orange">* <b>温馨提示：</b>如果您为各级分销商设置了统一的成本价格或分销价格就可以使用微店的多级分销功能。</p>
                        <label class="control-label"><em class="required">*</em>成本价格：</label>
                        <div class="cost-price-0">
                            <div class="controls">
                                <b style="color: #07d">一级分销商成本价</b>
                                <div class="input-prepend">
                                    <span class="add-on">￥</span><input type="text" maxlength="10" name="cost_price" class="cost-price-1 input-small" value="<?php echo $product['drp_level_1_cost_price']?>" />
                                </div>
                            </div>
                            <div class="controls">
                                <b style="color: #07d">二级分销商成本价</b>
                                <div class="input-prepend">
                                    <span class="add-on">￥</span><input type="text" maxlength="10" name="cost_price" class="cost-price-2 input-small" value="<?php echo $product['drp_level_2_cost_price']?>" />
                                </div>
                            </div>
                            <div class="controls">
                                <b style="color: #07d">三级及以上分销商</b>
                                <div class="input-prepend">
                                    <span class="add-on">￥</span><input type="text" maxlength="10" name="cost_price" class="cost-price-3 input-small" value="<?php echo $product['drp_level_3_cost_price']?>" />
                                </div>
                            </div>
                        </div>
                </div>


                <div class="control-group">
                        <label class="control-label"><em class="required">*</em>建议售价：</label>
                        <div class="price-0">
                            <div class="controls">
                                <b style="color:#07d">一级分销商零售价</b>
                                <div class="input-prepend">
                                    <span class="add-on">￥</span><input type="text" maxlength="10" name="fx-price" class="fx-price-1 input-small" value="<?php echo $product['drp_level_1_price']?>" />
                                </div>
                            </div>
                            <div class="controls">
                                <b style="color:#07d">二级分销商零售价</b>
                                <div class="input-prepend">
                                    <span class="add-on">￥</span><input type="text" maxlength="10" name="fx-price" class="fx-price-2 input-small" value="<?php echo $product['drp_level_2_price']?>" />
                                </div>
                            </div>
                            <div class="controls">
                                <b style="color:#07d">三级及以上分销商</b>
                                <div class="input-prepend">
                                    <span class="add-on">￥</span><input type="text" maxlength="10" name="fx-price" class="fx-price-3 input-small" value="<?php echo $product['drp_level_3_price']?>" />
                                </div>
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