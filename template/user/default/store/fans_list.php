<div class="widget-list-filter">
	<form class="form-horizontal ui-box list-filter-form" onsubmit="return false;">
		<div class="clearfix">
			<div class="filter-groups">
				<div class="control-group">
					<label class="control-label">粉丝名称：</label>
					<div class="controls">
						<input type="text" name="nickname" value="">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">浏览状态：</label>
					<div class="controls">
						<select class="state-select js-state-select" name="status">
							<option value="0" selected="">全部</option>
							<option value="1">浏览店铺</option>
							<option value="2">关注店铺</option>
						</select>
					</div>
				</div>
			</div>
			<div class="pull-left">
				<div class="time-filter-groups clearfix">
					<div class="control-group">
						<label class="control-label">关注时间：</label>
						<div class="controls">
							<input type="text" name="start_time" id="js-start-time" class="js-start-time" value="">
							<span>至</span>
							<input type="text" name="end_time" id="js-end-time" class="js-end-time" value="">
							<span class="date-quick-pick" data-days="7">最近7天</span> <span class="date-quick-pick" data-days="30">最近30天</span> </div>
					</div>
				</div>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<div class="ui-btn-group"> <a href="javascript:;" class="ui-btn ui-btn-primary js-filter" data-loading-text="正在筛选...">筛选</a> </div>
			</div>
		</div>
	</form>
</div>

<div class="ui-nav">
            <ul>
                <li class="active"><a href="javascript:;" class="all" data="0">全部</a></li>
                <li><a href="javascript:;" class="wait-paid status-1" data="1">浏览店铺</a></li>
                <li><a href="javascript:;" class="wait-send status-2" data="2">关注店铺</a></li>
            </ul>
        </div>
<div class="app-preview">
	<div class="ui-box">
		<?php if($fans_list){ ?>
		<table class="ui-table ui-table-list" style="padding:0px;">
			<thead class="js-list-header-region tableFloatingHeaderOriginal">
				<tr>
					<th class="cell-40"><a href="javascript:;" data-orderby="feature_count">粉丝名称</a></th>
					<th class="text-left"><a href="javascript:;" data-orderby="feature_count">关注时间</a></th>
					<th class="text-left"><a href="javascript:;" data-orderby="feature_count">来　　源</a></th>
				</tr>
			</thead>
			<tbody class="js-list-body-region">
				<?php foreach($fans_list as $v){?>
				<tr cat-id="<?php echo $v['id']?>">
					<td><?php echo $user_list[$v['uid']]['nickname'];?></td>
					<td class="text-left"><?php echo $v['subscribe_time']?date('Y-m-d H:i:s',$v['subscribe_time']):''?></td>
					<td class="text-left"><?php echo $v['openid']?'关注店铺':'浏览店铺'?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php }else{ ?>
		<div class="js-list-empty-region"></div>
		<?php } ?>
	</div>
	<div class="js-list-footer-region ui-box"><div><div class="pagenavi"><?php echo $page; ?></div></div></div>
</div>
