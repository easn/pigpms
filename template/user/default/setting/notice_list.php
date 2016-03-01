<style>
.app-actions {
  position: fixed;
  bottom: 0;
  width: 850px;
  padding-top: 20px;
  clear: both;
  text-align: center;
  z-index: 2;
}
</style>

<div class="widget-list">
	<div class="ui-box">
		<div class="js-list-filter-region clearfix">
			
		</div>
		
			<table class="ui-table ui-table-list physical_list">
				<thead class="js-list-header-region tableFloatingHeaderOriginal">
					<tr class="widget-list-header">
						<th colspan="5" style="vertical-align:middle"><input type="checkbox" class="chekckAll">&#12288;全选&#12288;&#12288;
						<span style="display:inline-block"><font color="#f60">* 以下规则 选择保存后，将只用于本店铺，如果您还有其他店铺，请前往 并对应操作！<br/>
							所有通知均为双向（也就是说会向  操作者 和 被操作做者 各发一条通知）
						</span>	
						</font></th>
					</tr>
				</thead>
				<tbody class="js-list-body-region">
					<?php foreach($notice_manage as $k=>$v) {?>
						<tr class="widget-list-item">
								<td><input class="checkzu check_zu[<?php echo $v['id'];?>]" type="checkbox" value="1" <?php if($store_notice_manage[$v['key']] == '0,1,2') {?>checked="checked"<?php }?>>&#12288;<?php echo $v['description'];?></td>
								<td>&#12288; &#12288;</td>
								<td colspan='3'>
									<input type="checkbox" style="display:none" class="checks0" checked="checked" name="<?php echo $v[key]?>" value="0">&nbsp;
									<?php if(in_array('1',$v['type'])) {?>
										<input type="checkbox" class="checks1"  <?php if(in_array("1",explode(",",$store_notice_manage[$v['key']]))) {?>checked="checked"<?php }?> name="<?php echo $v[key]?>" value="1">&nbsp;短信通知
									<?php }?>
									&#12288;&#12288;
									<?php if(in_array('2',$v['type'])) {?>
										<input type="checkbox" <?php if(in_array("2",explode(",",$store_notice_manage[$v['key']]))) {?>checked="checked"<?php }?> class="checks2" name="<?php echo $v[key]?>" value="2">&nbsp;微信通知
									<?php }?>
								</td>
						</tr>
					<?php }?>

				</tbody>
			</table>
			
			<div class="app-actions" style="bottom: 0px;">
				<div class="form-actions text-center">
					<input class="btn js-btn-quit-store" type="button" value="取 消">
					<input class="btn btn-primary js-btn-save-store" type="submit" value="保 存" data-loading-text="保 存...">
				</div>
			</div>			
			
		<!--  
			<div class="js-list-empty-region">
				<div>
					<div class="no-result widget-list-empty">还没有相关数据。</div>
				</div>
			</div>
		-->
	</div>
	<div class="js-list-footer-region ui-box"></div>
</div>

<script>
$(function(){
	$(".chekckAll").click(function(){
		if($(this).is(":checked") == true) {
			$(".js-list-body-region").find("input[type='checkbox']").attr("checked",true);
		} else {
			$(".js-list-body-region").find("input[type='checkbox']").attr("checked",false);
			$(".js-list-body-region").find(".checks0").attr("checked",true);
		}
	})

	$(".checkzu").click(function(){
		if($(this).is(":checked") == true) {
			$(this).closest("tr").find("input[type='checkbox']").attr("checked",true);	
		} else {
			$(this).closest("tr").find("input[type='checkbox']").attr("checked",false);
			$(this).closest("tr").find(".checks0").attr("checked",true);
		}	
	})


	$(".checks1").click(function(){
		var checks1_index =  $(".checks1").index($(this));
		
		if($(this).is(":checked") == true) {
			if($(".checks2").eq(checks1_index).is(":checked") == true) {
				$(".checkzu").eq(checks1_index).attr("checked",true);
			} else {
				$(".checkzu").eq(checks1_index).attr("checked",false);
			}
		}else{
			$(".checkzu").eq(checks1_index).attr("checked",false);
		}
	})

	$(".checks2").click(function(){
		var checks2_index =  $(".checks2").index($(this));
		
		if($(this).is(":checked") == true) {
			if($(".checks1").eq(checks2_index).is(":checked") == true) {
				$(".checkzu").eq(checks2_index).attr("checked",true);
			} else {
				$(".checkzu").eq(checks2_index).attr("checked",false);
			}
		} else{
			$(".checkzu").eq(checks2_index).attr("checked",false);
		}
	})	
	
})
</script>