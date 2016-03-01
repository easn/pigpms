<nav class="ui-nav-table clearfix">
	<ul class="pull-left js-list-filter-region">
		<li id="js-list-nav-all"  class="active">
			<a href="<?php dourl('tag');?>">所有标签</a>
		</li>
	</ul>
</nav>
<div class="app-design-wrap">
<div class="app-design clearfix without-add-region"><div class="page-tradeincard">



<style>
.app-fans-points-edit .checkbox {
	color: #999;
	margin-left: 16px;
}
button, input, label, select, textarea {
	font-size: 14px;
	font-weight: 400;
	line-height: 20px;
	font-family:Helvetica,STHeiti,"Microsoft YaHei",Verdana,Arial,Tahoma,sans-serif;
}
.input-append .add-on, .input-append .btn, .input-append .btn-group{margin-left:-3px;}
 .avatar img{width:50px;height:50px}

.hyname {width:90%;height:auto;display:inline-block}
.hyname li{float:left;width:30%;height:25px;line-height:25px;}
.control-group.error .input-append .add-on, .control-group.error .input-prepend .add-on {
  color: #b94a48;
  background-color: #f2dede;
  border-color: #b94a48;
}
</style>


	
<div class="app__content">
		<form class="js-page-form form-horizontal ui-form" method="POST" novalidate="novalidate">
			<div class="control-group">
				<label class="control-label"><em class="required">*</em>
					会员标签名称</label>
				<div class="controls">
					<!--<input type="text" class="for-post input-medium" maxlength="30" name="rule_type" value="<?php echo $tag['name'];?>">
					-->

					<ul class="hyname">
						<li><input type="radio" name="rule_type" <?php if($tag['rule_type']=='1'){?> checked="checked" <?php }?>  value="1">&nbsp;普通会员</li>
						<li><input type="radio" name="rule_type" <?php if($tag['rule_type']=='4'){?> checked="checked" <?php }?>   value="4">&nbsp;铜牌会员</li>
						<li><input type="radio" name="rule_type" <?php if($tag['rule_type']=='3'){?> checked="checked" <?php }?>  value="3">&nbsp;银牌会员</li>
						<li><input type="radio" name="rule_type" <?php if($tag['rule_type']=='2'){?> checked="checked" <?php }?> value="2">&nbsp;金牌会员</li>
						
						
						
						<li><input type="radio" name="rule_type" <?php if($tag['rule_type']=='5'){?> checked="checked" <?php }?> value="5">&nbsp;<input style="display:inline-block;width:70px;" type="text" class="for-post" maxlength="10" name="rule_name" value="<?php echo $tag['name'];?>"></li>
					</ul>	
					
				</div>
			</div>
			
			
			<div class="control-group">
				<!--  <label class="control-label">或</label>-->
				<label class="control-label"><em class="required">*</em>等级值</label>
				<div class="controls">
					<input type="text" id="level_num" name="level_num" value="100" placeholder="请输入数字" style="width:100px;">
					<p class="help-desc">值越大等级越高。</p>
				</div>
			</div>

			
			<!--  
			<div class="control-group">
				<label class="control-label" for="J_pub_time">自动打标签条件</label>
				<div class="controls">
					<span class="help-inline">累计成功交易</span> <input type="text" name="trade_limit" class="input-mini for-post" value="<?php echo $tag['trade_limit'];?>">
					笔
					<p class="js-trade-limit-error"></p>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label">或</label>
				<div class="controls">
					<span class="help-inline">累计购买金额</span> <input type="text" name="amount_limit" class="input-mini for-post" value="<?php echo $tag['amount_limit'];?>">
					元
					<p class="js-amount-limit-error"></p>
				</div>
			</div>
			-->
	<style>
	<!--
	 .selected-style {
		  position: absolute;
	  left: 0;
	  top: 0;
	  border: 2px solid #09F;
	  -webkit-box-sizing: border-box;
	  -moz-box-sizing: border-box;
	   box-sizing: border-box; 
	   display:inline-block;
	}
	 .selected-style:after {
	  position: absolute;
	  display: block;
	  content: ' ';
	  right: 0px;
	  bottom: 0px;
	  border: 14px solid #09f;
	  border-left-color: transparent;
	  border-top-color: transparent;
	}
	.selected-style i {
   position: absolute; 
   right: 1px; 
   bottom: 1px; 
  z-index: 2; 
}
	.selected-styles{position:relative;float:left;width:51px;height:41px;}
	-->
	
	.controls .help-desc{clear:both;}
	.controls .help-desc a{color:#07d}
	.controls .ico li{float:left;width:54px;display:inline-block;height:55px;cursor:pointer}
	.controls .ico li .avatar{height:50px;width:50px;display:inline-block}
	.ico .spans{position:relative}
	
	.no-selected-style i{display:none;}
	.app-image-list .other_li{background:none;border:0px;}
	</style>		
	
	
	
			<div class="control-group">
				<!--  <label class="control-label">或</label>-->
				<label class="control-label"><em class="required">*</em>等级图标</label>
				<div class="controls">


					<ul class="ico app-image-list js-ico-list">
						<li class="other_li sort">
							<div class="spans">
								<span class="checkico no-selected-style"><i class="icon-ok icon-white"></i>
								<img class="avatar" src="./static/images/huiyuan/1_01.png">
								</span>
							</div>
						</li>
						<li class="other_li sort">
							<div class="spans">
								<span class="checkico no-selected-style"><i class="icon-ok icon-white"></i>
								<img class="avatar" src="./static/images/huiyuan/1_02.png">
								</span>
							</div>
						</li>
						<li class="other_li sort">
							<div class="spans">
								<span class="checkico no-selected-style"><i class="icon-ok icon-white"></i>
								<img class="avatar" src="./static/images/huiyuan/1_03.png">
								</span>
							</div>
						</li>
						<li class="other_li sort">
							<div class="spans">
								<span class="checkico no-selected-style"><i class="icon-ok icon-white"></i>
								<img class="avatar" src="./static/images/huiyuan/1_04.png">
								</span>
							</div>
						</li>						
						<li>  <a href="javascript:;" class="add-goods js-add-picture">+加图</a>  </li>
					</ul>
					<br/>	
					
					
					<p class="help-desc">如需增加可选的等级图标，请 <b><a href="javascript:" class="js-add-picture">点击</a></b> 更新。</p>
				</div>
			</div>
			
			<div class="control-group">
				<!--  <label class="control-label">或</label>-->
				<label class="control-label">会员折扣</label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="discount" name="discount" value="9.9" placeholder="请输入折扣" style="width:73px;"><span class="add-on">折</span>
					</div>

					<p class="help-desc">留空或10.0，为不打折。</p>
				</div>
			</div>
			<div class="control-group">
				<!--  <label class="control-label">或</label>-->
				<label class="control-label"><em class="required">*</em>自动升级条件</label>
				<div class="controls">
					<span class="help-inline">累计积分达到</span> <input type="text" name="points_limit" class="input-mini for-post" value="<?php echo $tag['points_limit'];?>">
				</div>
			</div>
			<div class="control-group">
				<!--  <label class="control-label">或</label>-->
				<label class="control-label"><em class="required"></em>包邮特权</label>
				<div class="controls">
					<label class="checkbox">
						<input type="checkbox" id="is_postage_free" name="is_postage_free" value="1" >
						<span>包邮</span>
					</label>
				</div>
			</div>				
			<div class="control-group">
				<label class="control-label"><b>会员等级详情</b></label>
			</div>	

			<div class="control-group">
				<!--  <label class="control-label">或</label>-->
				<label class="control-label"><em class="required">*</em>使用须知</label>
				<div class="controls">
					<textarea id="description" class="description"  style="width:65%" name="description" cols="55" rows="4" placeholder="最多可输入100个字符，简述相关会员卡信息，以便会员知晓。" maxlength="300"></textarea>
				</div>
			</div>			
			<div class="form-actions">
				<input class="btn btn-primary js-btn-add-save" type="button" value="保 存" data-loading-text="保 存...">
				<input type="button" class="btn btn-defaults js-btn-quit" value="预览" >
				<input type="button" class="btn btn-defaults js-btn-quit" value="返回" >
			</div>
		</form>
	</div>






</div>




</div>


			</form>
		</div>



	</div>
</div>
