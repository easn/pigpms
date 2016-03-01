			<?php $select_sidebar=isset($select_sidebar)?$select_sidebar:ACTION_NAME;?>
			<aside class="ui-sidebar sidebar">
				<nav>
			   		 <h4>会员管理</h4>
					<ul>
						<li <?php if(in_array($select_sidebar,array('tag','statistics'))) echo 'class="active"';?>>
							<a href="<?php dourl('tag');?>">会员等级</a>
						</li>
						<li <?php if($select_sidebar == 'all') echo 'class="active"';?>>
							<a href="<?php dourl('points');?>">积分规则</a>
						</li>
						
				</nav>
			</aside>