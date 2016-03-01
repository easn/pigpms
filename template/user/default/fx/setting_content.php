<style type="text/css">
    .dash-bar .info-group {
        float: left;
        width: 25.33%;
        padding-top: 18px;
    }
    .widget .chart-body {
        height: 345px;
    }
    .form-horizontal input {
        width: 206px!important;
    }
    .form-actions input {
        width: auto!important;
        height: auto;
    }
    input[type="radio"] {
        margin: 0;
        vertical-align: middle;
    }
    input[type="button"] {
        width: auto!important;
    }
</style>
<?php $version = option('config.weidian_version');?>
<?php if(empty($version)){?>
<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>关注公众号</h3>
        <div>
            <p>店铺绑定公众号（认证服务号）后，若开启此项，粉丝需要关注公众号才能申请分销。</p>
            <div style="float: left;margin-top: 10px">
                <b style="color: #07d">封面图片</b> <a href="javascript:void(0);" class="upload-img1">上传图片</a> | <a href="javascript:void(0);" class="default-img1" data-img="<?php echo getAttachmentUrl('images/drp_ad_01.png', false); ?>">默认图片</a><br/><br/>
                <b style="color: #07d">消息模板</b>
                <textarea class="reg-drp-subscribe-tpl" cols="100" rows="3"><?php if (!empty($reg_drp_subscribe_tpl)) { ?><?php echo $reg_drp_subscribe_tpl; ?><?php } else { ?>尊敬的 {$nickname}, 感谢您关注 {$store} 公众号，点击申请分销。<?php } ?></textarea><br/>
            </div>
            <div style="border-right: 1px dashed lightgray;float: left;height: 97px;margin-left: 20px;margin-top: 20px">&nbsp;</div>
            <div style="float: left;padding-top: 0px">
                <input type="button" class="ui-btn ui-btn-primary save-btn1" value="保 存" style="margin-left: 30px;margin-top: 65px" />
                预览：<img class="preview1" src="<?php if (!empty($reg_drp_subscribe_img)) { ?><?php echo $reg_drp_subscribe_img; ?><?php } else { ?><?php echo getAttachmentUrl('images/drp_ad_01.png', false); ?><?php } ?>" width="220" />
            </div>
        </div>
        <div style="clear: both;color:orange;">温馨提示：若需要自定义消息模板，请保留{$xxx}，封面图片建议尺寸 900x600。</div>
    </div>
    <div class="widget-app-board-control drp-subscribe">
        <label class="js-switch ui-switch pull-right <?php if ($open_drp_subscribe) { ?>ui-switch-on<?php } else { ?>ui-switch-off<?php } ?>"></label>
    </div>
</div>

<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>自动分销（关注公众号）</h3>
        <div>
            <p>店铺绑定公众号（认证服务号）后，若开启此项，粉丝关注公众号即可成为分销商。</p>
            <div style="float: left;margin-top: 10px">
                <b style="color: #07d">封面图片</b> <a href="javascript:void(0);" class="upload-img2">上传图片</a> | <a href="javascript:void(0);" class="default-img2" data-img="<?php echo getAttachmentUrl('images/drp_ad_02.png', false); ?>">默认图片</a><br/><br/>
                <b style="color: #07d">消息模板</b>
                <textarea class="drp-subscribe-tpl" cols="100" rows="3"><?php if (!empty($drp_subscribe_tpl)) { ?><?php echo $drp_subscribe_tpl; ?><?php } else { ?>尊敬的 {$nickname}, 您已成为 {$store} 第 {$num} 位分销商，点击管理店铺。<?php } ?></textarea><br/>
            </div>
            <div style="border-right: 1px dashed lightgray;float: left;height: 97px;margin-left: 20px;margin-top: 20px">&nbsp;</div>
            <div style="float: left;padding-top: 0px">
                <input type="button" class="ui-btn ui-btn-primary save-btn3" value="保 存" style="margin-left: 30px;margin-top: 65px" />
                预览：<img class="preview2" src="<?php if (!empty($drp_subscribe_img)) { ?><?php echo $drp_subscribe_img; ?><?php } else { ?><?php echo getAttachmentUrl('images/drp_ad_02.png', false); ?><?php } ?>" width="220" />
            </div>
        </div>
        <div style="clear: both;color:orange;">温馨提示：若需要自定义消息模板，请保留{$xxx}, {$num=100}支持默认值，封面图片建议尺寸 900x600。</div>
    </div>
    <div class="widget-app-board-control drp-subscribe-auto">
        <label class="js-switch ui-switch pull-right <?php if ($open_drp_subscribe_auto) { ?>ui-switch-on<?php } else { ?>ui-switch-off<?php } ?>"></label>
    </div>
</div>
<?php } ?>
<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>分销审核</h3>
        <div>
            <p>店铺粉丝申请成为分销商是否需要通过审核，如果需要请开启审核，默认为未启用。</p>
        </div>
    </div>
    <div class="widget-app-board-control approve">
        <label class="js-switch ui-switch pull-right <?php if ($open_drp_approve) { ?>ui-switch-on<?php } else { ?>ui-switch-off<?php } ?>"></label>
    </div>
</div>

<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>引导分销</h3>
        <div>
            <p>在店铺首页添加引导分销提醒，在商品页面显示分销利润，引导粉丝注册成为分销商</p>
        </div>
    </div>
    <div class="widget-app-board-control guidance">
        <label class="js-switch ui-switch pull-right <?php if ($open_drp_guidance) { ?>ui-switch-on<?php } else { ?>ui-switch-off<?php } ?>"></label>
    </div>
</div>

<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>分销限制</h3>
        <div>
            <p>设置粉丝申请分销商的门槛，为商家筛选优质分销商</p><br/>
            <div style="float: left">
                <b style="color: #07d">消费满</b> <input type="text" style="width: 80px" name="drp_limit_buy" class="drp-limit-buy" value="<?php echo $drp_limit_buy; ?>" /> <b style="color: #07d">元</b><br/>
                <!--<input type="radio" name="drp_limit_condition" class="drp-limit-condition" value="0" <?php /*if (empty($drp_limit_condition)) { */?>checked="true"<?php /*} */?> /> 或 / <input type="radio" name="drp_limit_condition" class="drp-limit-condition" value="1" <?php /*if (!empty($drp_limit_condition)) { */?>checked="true"<?php /*} */?> /> 和<br/>-->
                <!--<b style="color: #07d;display:inline-block;margin-top:20px">分享满</b> <input type="text" name="drp_limit_share" class="drp-limit-share" style="width: 80px" value="<?php /*echo $drp_limit_share; */?>" /> <b style="color: #07d">次</b>-->
            </div>
            <div style="border-right: 1px dashed lightgray;float: left;height: 30px;margin-left: 20px">&nbsp;</div>
            <div style="float: left;padding-top: 0px">
                <input type="button" class="ui-btn ui-btn-primary save-btn" value="保 存" style="margin-left: 30px" />
            </div>
        </div>
    </div>
    <div class="widget-app-board-control limit">
        <label class="js-switch ui-switch pull-right <?php if ($open_drp_limit) { ?>ui-switch-on<?php } else { ?>ui-switch-off<?php } ?>"></label>
    </div>
</div>

<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>允许分销修改店名LOGO</h3>
        <div>
            <p>设置分销商是否能修改店铺名称和LOGO</p><br/>
        </div>
    </div>
    <div class="widget-app-board-control update_name">
        <label class="js-switch ui-switch pull-right <?php if ($update_drp_store_info) { ?>ui-switch-on<?php } else { ?>ui-switch-off<?php } ?>"></label>
    </div>
</div>

<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>粉丝终身制</h3>
        <div>
            <p>店铺开启粉丝终身制，访问的用户成为该店铺的粉丝后，再访问其它人的店铺，还是会跳到之前绑定的店铺</p>
        </div>
    </div>
    <div class="widget-app-board-control fans_lifelong">
        <label class="js-switch ui-switch pull-right <?php if ($setting_fans_forever) { ?>ui-switch-on<?php } else { ?>ui-switch-off<?php } ?>"></label>
    </div>
</div>


<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>粉丝分享自动成为分销商</h3>
        <div>
            <p>粉丝分享自动成为分销商</p>
        </div>
    </div>
    <div class="widget-app-board-control is_fanshare_drp">
        <label class="js-switch ui-switch pull-right <?php if ($is_fanshare_drp) { ?>ui-switch-on<?php } else { ?>ui-switch-off<?php } ?>"></label>
    </div>
</div>
<?php if (!empty($_SESSION['store']['drp_diy_store'])) { ?>
<!--<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>允许分销商装修店铺</h3>
        <div>
            <p>启用此项分销商可自行装修店铺</p>
        </div>
    </div>
    <div class="widget-app-board-control drp-diy-store">
        <label class="js-switch ui-switch pull-right <?php /*if ($open_drp_diy_store) { */?>ui-switch-on<?php /*} else { */?>ui-switch-off<?php /*} */?>"></label>
    </div>
</div>-->

<!--<div class="widget-app-board ui-box" style="border: none;">
    <div class="widget-app-board-info">
        <h3>分销定价</h3>
        <div>
            <p>启用此配置在设置分销商品时会自动选择以下选中的定价方式，否则设置分销商品时手动选择定价方式</p>
            <div style="float: left;margin-top: 5px">
                <input type="radio" name="unified_price_setting" id="set-price-1" class="unified-price-setting" value="1" <?php /*if ($unified_price_setting) { */?>checked="1"<?php /*} */?> /> <label for="set-price-1" style="display: inline-block">统一定价（<span style="color:red">支持多级分销</span>）</label><br/>
                <input type="radio" name="unified_price_setting" id="set-price-0" class="unified-price-setting" value="0" <?php /*if (!$unified_price_setting) { */?>checked="1"<?php /*} */?> /> <label for="set-price-0" style="display: inline-block">自由定价（<span style="color:red">仅支持三级分销</span>）</label>
            </div>
            <div style="border-right: 1px dashed lightgray;float: left;height: 30px;margin-left: 20px;margin-top: 10px">&nbsp;</div>
            <div style="float: left;padding-top: 0px;margin-top: 10px">
                <input type="button" class="ui-btn ui-btn-primary save-btn2" value="保 存" style="margin-left: 30px" />
            </div>
        </div>
    </div>
    <div class="widget-app-board-control drp-setting-price">
        <label class="js-switch ui-switch pull-right <?php /*if ($open_drp_setting_price) { */?>ui-switch-on<?php /*} else { */?>ui-switch-off<?php /*} */?>"></label>
    </div>
</div>-->
<?php } ?>