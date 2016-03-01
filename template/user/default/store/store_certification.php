<nav class="ui-nav">
	<ul>
		<li id="js-nav-list-index" class="active"> <a href="#list">店铺认证信息
			<?php if($store_info['approve']==2){?>
			(<span style="color:orange">认证中</span>)
			<?php }else if($store_info['approve']==3){ ?>
			(<span style="color:red">认证不通过，请重新上传认证</span>)
			<?php }else if($store_info['approve']==1){ ?>
			(<span style="color:green">认证通过</span>)
			<?php }else{ ?>
			(<span style="color:red">未认证</span>)
			<?php } ?>
			</a> </li>
	</ul>
</nav>

<?php if(($store_info['approve']==0) || ($store_info['approve']==3) || empty($certification_info)) {?>
<form id="myform" refresh="true" action="<?php dourl('certification');?>" method="post">
	<?php echo $config_html; ?>
	<div style="margin-top:20px;" class="btn">
		<input type="submit" class="button" value="提交" style=" background:none; color:#fff">
	</div>
</form>
<?php }else{ ?>
<table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_0">
	<tbody>
		<?php if($certification_info){ foreach($certification_info as $k=>$v){?>
		<tr>
			<th width="60" class="center"><?php echo $k;?></th>
			<td><div class="show"><?php if(strpos($v,'images')!==false){?><img src="<?php echo getAttachmentUrl($v); ?> " width="120" height="120" /><?php }else{ ?><?php echo $v;}?></div></td>
		</tr>
		<?php } } ?>
	</tbody>
</table>
<script type="text/javascript">
$('input,select').attr('disabled','disabled');
</script>
<?php } ?>

<script type="text/javascript">
$(function() {
 	$("#myform").validate();
});

			KindEditor.ready(function(K){
				var site_url = "<?php echo option('config.site_url');?>";
				var editor = K.editor({
					allowFileManager : true
				});
				$('.config_upload_image_btn').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "/user.php?c=Store&a=ajax_upload_pic";
					editor.loadPlugin('image', function(){
						editor.plugin.imageDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								upload_file_btn.siblings('.input-image').val(site_url+url);
								editor.hideDialog();
							}
						});
					});
				});
				$('.config_upload_file_btn').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "/user.php?c=Store&a=ajax_upload_file&name="+upload_file_btn.siblings('.input-file').attr('name');
					editor.loadPlugin('insertfile', function(){
						editor.plugin.fileDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								upload_file_btn.siblings('.input-file').val(url);
								editor.hideDialog();
							}
						});
					});
				});
			});
		</script>