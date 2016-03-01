<style>
.helps {
  position: absolute;
  top: 10px;
  right: 14px;
}
.help a {
  display: inline-block;
  width: 16px;
  height: 16px;
  line-height: 18px;
  border-radius: 8px;
  font-size: 12px;
  text-align: center;
  background: #D5CD2F;
  color: #fff;
}
.help a:after {
  content: "?";
}
</style>

<form class="form-horizontal">
    <fieldset>
        <div class="control-group">
            <label class="control-label">所属公司：</label>
            <div class="controls">
                <span class="sink"><?php echo $company['name']; ?></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">店铺名称：</label>
            <div class="controls">
                <?php if (empty($store['edit_name_count'])) { ?>
                <div class="hide js-team-name-input">
                    <input type="text" name="team_name" value="<?php echo $store_session['name']; ?>" data="<?php echo $store['name']; ?>" maxlength="30" />
                    <p class="help-block error-message">店铺名称只能修改一次，请您谨慎操作</p>
                </div>
                <?php } ?>
                <div class="js-team-name-text">
                    <span class="sink"><?php echo $store['name']; ?></span>
                    <?php if (empty($store['edit_name_count'])) { ?>
                    <a href="javascript:;" class="sink sink-minor js-team-name-edit">修改</a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">主营类目：</label>
            <div class="controls">
                <span class="sink"><?php echo $sale_category; ?></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">创建日期：</label>
            <div class="controls">
                <span class="sink"><?php echo date('Y-m-d H:i:s', $store['date_added']); ?></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">店铺Logo：</label>
            <div class="controls">
                <span class="avatar"><img class="avatar-img" <?php if (!empty($store['logo'])) { ?>src="<?php echo $store['logo']; ?>"<?php } else { ?>src="<?php echo TPL_URL;?>/images/logo.png"<?php } ?>/></span>
                <a href="javascript:;" class="upload-img js-add-picture">修改</a>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">店铺简介：</label>
            <div class="controls">
                <textarea name="intro" class="input-intro" <?php if($store['drp_level'] > 0) {?> disabled<?php }?> cols="30" rows="3" maxlength="100"><?php echo $store['intro']; ?></textarea>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">联系人姓名：</label>
            <div class="controls">
                <input class="contact-name" <?php if($store['drp_level'] > 0) {?> disabled<?php }?> type="text" name="contact_name" placeholder="请填写完整的真实姓名" value="<?php echo $store['linkman']; ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">联系人 QQ：</label>
            <div class="controls">
                <input class="qq" type="text" <?php if($store['drp_level'] > 0) {?> disabled<?php }?> placeholder="填写能联系到您的QQ号码" name="qq" value="<?php echo $store['qq']; ?>" maxlength="15" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">联系人手机/电话：</label>
            <div class="controls">
                <input class="mobiles js-mobile" type="text" name="mobile" placeholder="填写准确的手机/电话号码，便于及时联系" value="<?php echo $store['tel']; ?>" maxlength="14" />
				<font color="#f00">(* 正确的电话格式为：010-66668888)</font>
			</div>
		</div>
		<div class="control-group">
		
			<label class="control-label">
			分销商联系方式：</label>
			<div class="controls">
					<select name="is_show_drp_tel">
						<option value="0" <?php if($store['is_show_drp_tel'] == '0'){ ?>selected="selected"<?php }?>>显示分销商自己的</option>
						<option value="1" <?php if($store['is_show_drp_tel'] == '1'){ ?>selected="selected"<?php }?>>显示本店铺的</option>
						
					</select>
					<font color="#f00">(* 分销店铺商品的详情页是显示本店铺的电话？，如果是分销商自己的电话？)</font>
			</div>
			
			
		</div>
        <?php if (!empty($_SESSION['sync_store'])) { ?>
		<div class="control-group">
                <label class="control-label">启用客服：</label>

                <div class="controls">
                    <input type="radio" value="0" name="open_service" class="open_service" <?php if($store['open_service'] == 0 || $store['open_service'] == ''){echo 'checked=true';} ?> />
					关闭
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" value="1" name="open_service" class="open_service" <?php if($store['open_service'] == 1){echo 'checked=true';} ?> />
					开启
                </div>
            </div>
			<div class="control-group">
                <label class="control-label"></label>
                <div class="controls" style="color:#f00;">
					客服使用对接平台上的“网页客服”功能
                </div>
            </div>
        <?php } ?>
    </fieldset>
    
    <script type="text/javascript">
    var t= '';
    $(function(){
        $('.js-help-notes').hover(function(){
            $('.popover-help-notes').remove();
            var html = '<div class="js-intro-popover popover popover-help-notes bottom" style="display: none; top: ' + ($(this).offset().top + 12) + 'px; left: ' + ($(this).offset().left - 20) + 'px;"><div class="arrow"></div><div class="popover-inner"><div class="popover-content"><p><strong>下单笔数：</strong>所有用户的下单总数。</p><p><strong>付款订单：</strong>已付款的订单总数；</p><p><strong>发货订单：</strong>已发货的订单总数。</p></div></div></div>';
            $('body').append(html);
            $('.popover-help-notes').show();
        }, function(){
            t = setTimeout('hide()', 200);
        })

        $('.popover-help-notes').live('mouseleave', function(){
            clearTimeout(t);
            hide();
        })

        $('.popover-help-notes').live('mouseover', function(){
            clearTimeout(t);
        })

    })

    function hide() {
        $('.popover-help-notes').remove();
    }
</script>
    
    <div class="form-actions">
        <button class="ui-btn ui-btn-primary js-btn-submit" type="button" data-loading-text="正在保存...">保存</button>
    </div>
</form>