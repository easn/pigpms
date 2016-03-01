<!-- ▼ Main container -->
<nav class="ui-nav-table clearfix">
	<ul class="pull-left js-list-filter-region">
		<li id="js-list-nav-all" <?php echo $type == 'all' ? 'class="active"' : '' ?>>
			<a href="#all">所有标签</a>
		</li>
	</ul>
</nav>

<div class="widget-list">
  <div style="position:relative;" class="js-list-filter-region clearfix ui-box">
	  <div>
		  <a class="ui-btn ui-btn-primary" href="#create">新建标签</a>
		  <!--  <a class="ui-btn ui-btn-primary2 downloadtag" target="_blank" href="<?php echo dourl("tag_download_csv");?>"></a>-->
		  <div class="js-list-search ui-search-box">
			  <input type="text" value="<?php echo $keyword;?>" placeholder="搜索" class="txt js-coupon-keyword">
		  </div>
	  </div>
  </div>
</div>
<style>
.ui-table-list .fans-box .fans-avatar {float: left;width: 60px;height: 60px;background: #eee;overflow: hidden;}
.ui-table-list .fans-box .fans-avatar img {width: 60px;height: auto;}
.ui-table-list .fans-box .fans-msg {float: left;}
.ui-table-list .fans-box .fans-msg p {padding: 0 5px;text-align: left;}
</style>
<div class="ui-box">
	<?php
	if($tag_list) {
		?>
		<table class="ui-table ui-table-list" style="padding:0px;">
			<thead class="js-list-header-region tableFloatingHeaderOriginal">
			<tr class="widget-list-header"><th class="cell-20 text-left">
					等级标签名
				</th>
				<!--  
				<th class="cell-10">
					微信会员
				</th>
				<th class="cell-10">
					手机会员
				</th>-->
				<th class="cell-10">
					自动加标签条件
				</th>
				<th class="cell-10">
					等级值
				</th>
				<th class="cell-10">
					等级图标
				</th>
				<th class="cell-10">
					会员折扣
				</th>				
				<th class="cell-10">
					会员等级须知
				</th>
				<th class="cell-10"  align="right">
					操作
				</th>

			</tr>
			</thead>
			<tbody class="js-list-body-region">
			<?php foreach($tag_list as $tag){ ?>
				<tr class="widget-list-item">

					<td><?php echo $tag['name'];?></td>
					<!--  
						<td>0</td>
						<td>0</td>
					-->
					<td>
						<?php if($tag['trade_limit'] ){echo "&#12288;";?>
							累计成功交易 <?php echo $tag['trade_limit'];?> 笔
						<?php }?>
						
						<?php if($tag['amount_limit'] ){?>
							<?php if($tag['trade_limit']){?>
							<br/>或
							<?php }?>
							累计购买金额 <?php echo "&yen".(float)$tag['amount_limit']." 元"; ?>
						<?php }?>
						<?php if($tag['points_limit'] ){?>
							<?php if($tag['trade_limit'] || $tag['amount_limit']){?>
							<br/>或
							<?php }?>
							累计积分达到 <?php echo $tag['points_limit'];?> 分
						<?php }?>												
					</td>
					
					<td><?php echo $tag['level_num'];?></td>
					<td>
						<img width="50" height="45" src="<?php echo $tag['new_level_pic'];?>" >
					</td>
					
					
				
					
					<td><?php echo $tag['discount'];?>折</td>
					<td><?php echo msubstr($tag['description'],0,10);?> <br/>【<a href="javascript:void(0)" class="show_more" data="<?php echo $tag['description'];?>">点击查看更多</a>】</td>
					
					<td align="right">
						
						<a href="#edit/<?php echo $tag['id']?>" class="js-edit">编辑</a>
						<span>-</span>
						<a href="javascript:void(0);" data="<?php echo $tag['id']?>" class="js-delete">删除</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>


		<div class="js-list-footer-region ui-box">
			<div>
				<div class="pagenavi js-page-list"><?php echo $pages;?></div>
			</div>
		</div>
	<?php
	}else{
		?>
		<div class="js-list-empty-region">
			<div>
				<div class="no-result widget-list-empty">还没有相关数据。</div>
			</div>
		</div>
	<?php
	}
	?>
</div>

<div class="js-list-footer-region ui-box"></div>