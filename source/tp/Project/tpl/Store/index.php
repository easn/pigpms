<include file="Public:header"/>
<if condition="$withdrawal_count gt 0">
<script type="text/javascript">
    $(function(){
	    $('#nav_12 > dd > #leftmenu_Store_withdraw', parent.document).html('提现记录 <label style="color:red">(' + {pigcms{$withdrawal_count} + ')</label>')
    })
</script>
<else/>
    <script type="text/javascript">
        $(function(){
           // $('#nav_12 > dd:last-child > span', parent.document).html('提现记录');

	        $('#nav_12 > dd > #leftmenu_Store_withdraw', parent.document).html('提现记录');
			
			
        })
    </script>
</if>
<script>
$(function(){
	
		var strs;
			$(".display_edit").live("click",function(){
				strs = "<select>";
				strs += "	<option value='1'>正常展示</option>";
				strs += "	<option value='0'>关闭展示</option>";
				strs += "</select>";				
				$(this).closest("td").find(".diplays").html(strs);
				$(this).hide();
				$(this).closest("td").find(".display_save").show();
			})

			$(".display_save").live("click",function(){
				var indexs = $(".display_save").index($(this))
				strs = "正常展示";
				var is_display = $(this).closest("td").find("select").val();
				var store_id = $(this).closest("td").attr("datas");;


				if(!store_id) {
					alert("系统错误，请联系管理员");
					return ;
				}
				
				$.post("<?php echo U('Store/change_public_display'); ?>",{'is_display': is_display, 'store_id': store_id}, function(data){
					if(data.status == 0) {
						if(data.type=='1') {
							$(".diplays").eq(indexs).html("修改成功：正常展示");
						} else {
							$(".diplays").eq(indexs).html("修改成功：关闭展示");
						}
						
						$(".display_save").eq(indexs).hide();
						$(".display_edit").eq(indexs).show();
						//alert("修改成功");
					} else {
						alert(data.msg);
					}
					//window.location.href = url;
				},
				'json'
				)
				

			})				
	
})
</script>
<style>
.cursor{cursor:pointer;}
.display_edit,.display_save{background:url('./source/tp/Project/tpl/Static/images/glyphicons-halflings.png') no-repeat;}
.display_edit{background-position: -20px -23px;display:inline-block;height:20px;width:20px;}
.display_save{background-position: -283px 0px;display:inline-block;height:20px;width:20px;}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Store/index')}" class="on">店铺列表</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Store/index')}" method="get">
							<input type="hidden" name="c" value="Store"/>
							<input type="hidden" name="a" value="index"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}" />
							<select name="type">
								<option value="store_id" <if condition="$_GET['type'] eq 'store_id'">selected="selected"</if>>店铺编号</option>
                                <option value="user" <if condition="$_GET['type'] eq 'uid'">selected="selected"</if>>商户编号</option>
								<option value="account" <if condition="$_GET['type'] eq 'account'">selected="selected"</if>>商户昵称</option>
								<option value="name" <if condition="$_GET['type'] eq 'name'">selected="selected"</if>>店铺名称</option>
								<option value="tel" <if condition="$_GET['type'] eq 'tel'">selected="selected"</if>>联系电话</option>
							</select>
                            &nbsp;&nbsp;主营类目：
                            <select name="sale_category">
                                <option value="0">主营类目</option>
                                <volist name="sale_categories" id="sale_category">
                                <option value="{pigcms{$sale_category.cat_id}" <if condition="$Think.get.sale_category eq $sale_category['cat_id']">selected="true"</if>>{pigcms{$sale_category.name}</option>
                                </volist>
                            </select>
                            &nbsp;&nbsp;认证：
                            <select name="approve">
                                <option value="*">认证状态</option>
                                <option value="0" <?php if (isset($_GET['approve']) && is_numeric($_GET['approve']) && $_GET['approve'] == 0) { ?>selected<?php } ?>>未认证</option>
                                <option value="1" <if condition="$Think.get.approve eq 1">selected</if>>已认证</option>
								 <option value="2" <if condition="$Think.get.approve eq 2">selected</if>>认证中</option>
								  <option value="3" <if condition="$Think.get.approve eq 3">selected</if>>认证不通过</option>
                            </select>
                            &nbsp;&nbsp;状态：
                            <select name="status">
                                <option value="*">店铺状态</option>
                                <option value="1" <if condition="$Think.get.status eq 1">selected</if>>正常</option>
			            		<option value="2" <if condition="$Think.get.status eq 2">selected</if>>待审核</option>
			            		<option value="3" <if condition="$Think.get.status eq 3">selected</if>>审核未通过</option>
			            		<option value="4" <if condition="$Think.get.status eq 4">selected</if>>店铺关闭</option>
			            		<option value="5" <if condition="$Think.get.status eq 5">selected</if>>供货商关闭</option>
                            </select>
							<input type="submit" value="查询" class="button"/>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup><col> <col> <col> <col><col><col><col><col><col width="240" align="center"> </colgroup>
						<thead>
							<tr>
								<th>编号</th>
                                <th>店铺名称</th>
								<th>商户帐号</th>
								<th>商户名称</th>
								<th>联系电话</th>
								<th>主营类目</th>
								<th>收入</th>
								<th>可提现余额(元)</th>
								<th>待结算余额(元)</th>
								<?php if(in_array($my_version,array(4,8))) {?>
									<th>综合展示<img title="开启后将会在微信综合商城 和 pc综合商城展示" class="tips_img cursor" src="./source/tp/Project/tpl/Static/images/help.gif"></th>
								<?php }?>
                                <th class="textcenter">认证</th>
								<th class="textcenter">状态</th>
                                <th class="textcenter">创建时间</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($stores)">
								<volist name="stores" id="store">
									<tr>
										<td>{pigcms{$store.store_id}</td>
                                        <td>{pigcms{$store.name}</td>
										<td>{pigcms{$store.username}</td>
										<td>{pigcms{$store.nickname}</td>
										<td>{pigcms{$store.tel}</td>
										<td>{pigcms{$store.category}</td>
										<td>{pigcms{$store.income}</td>
                                        <td>{pigcms{$store.balance|number_format=###, 2, '.', ''}</td>
                                        <td>{pigcms{$store.unbalance|number_format=###, 2, '.', ''}</td>
										<?php if(in_array($my_version,array(4,8))) {?>                                       
										   <td datas="{pigcms{$store.store_id}">
												<span class="diplays">
												<if condition="$store['public_display'] eq 1">
													正常展示
												<else/>	
													已经关闭
												</if>
												</span>
												<span class="display_edit cursor" title="点击修改" style="">&nbsp;</span>	
												<span class="display_save cursor" title="点击保存修改" style="display:none">&nbsp;</span>	
											</td>
									   <?php }?>
                                        <td class="textcenter"><if condition="$store['approve'] eq 1"><a style="color:green; cursor:pointer" onclick="window.top.artiframe('{pigcms{:U('Store/certification_detail',array('id'=>$store['store_id'],'frame_show'=>true))}','店铺详细 - {pigcms{$store.name}',650,500,true,false,false,editbtn,'add',true);" href="javascript:void(0)">已认证</a><elseif condition="$store['approve'] eq 2" /><a onclick="window.top.artiframe('{pigcms{:U('Store/certification_detail',array('id'=>$store['store_id'],'frame_show'=>true))}','店铺详细 - {pigcms{$store.name}',650,500,true,false,false,editbtn,'add',true);" style="color:orange; cursor:pointer">认证中</a><elseif condition="$store['approve'] eq 3"/><a onclick="window.top.artiframe('{pigcms{:U('Store/certification_detail',array('id'=>$store['store_id'],'frame_show'=>true))}','店铺详细 - {pigcms{$store.name}',650,500,true,false,false,editbtn,'add',true);" style="color:red; cursor:pointer">认证不通过</a><else/><span style="color:red">未认证</span></if></td>
										<td class="textcenter">
											<if condition="$store['status'] eq 1">
												<span style="color:green">正常</span>
											<elseif condition="$store['status'] eq 2"/>
												<span style="color:red">待审核</span>
											<elseif condition="$store['status'] eq 3"/>
												<span style="color:red">审核未通过</span>
											<elseif condition="$store['status'] eq 4"/>
												<span style="color:red">店铺关闭</span>
											<elseif condition="$store['status'] eq 5"/>
												<span style="color:red">供货商关闭</span>
											</if>
										</td>
										<td class="textcenter">{pigcms{$store.date_added|date='Y-m-d H:i:s', ###}</td>
                                        <td class="textcenter">
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Store/check',array('id' => $store['store_id']))}','店铺对账 - {pigcms{$store.name}',800,600,true,false,false,false,'inoutdetail',true);">店铺对账</a> |
										
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Store/detail',array('id'=>$store['store_id'],'frame_show'=>true))}','店铺详细 - {pigcms{$store.name}',650,500,true,false,false,false,'detail',true);">查看详细</a> | 
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Store/inoutdetail',array('id' => $store['store_id']))}','收支明细 - {pigcms{$store.name}',700,500,true,false,false,false,'inoutdetail',true);">收支明细</a> | 
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Store/withdraw',array('id' => $store['store_id']))}','提现记录 - {pigcms{$store.name}',700,500,true,false,false,false,'inoutdetail',true);">提现记录</a>
										
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" <?php if(in_array($my_version,array(4,8))) {?>colspan="14"  <?php }else{?>colspan="13"<?php }?>  >{pigcms{$page}</td></tr>
							<else/>
								<tr><td class="textcenter red" <?php if(in_array($my_version,array(4,8))) {?>colspan="14"  <?php }else{?>colspan="13"<?php }?> >列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>