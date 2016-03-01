var detail_json_url = "user.php?c=order&a=detail_json";
$(function() {
	location_page(location.hash);
	$('a').live('click',function(){
		if($(this).attr('href') && $(this).attr('href').substr(0,1) == '#') {
			location_page($(this).attr('href'));
		}
	});

	function location_page(mark, page) {
		var mark_arr = mark.split('/');
		switch(mark_arr[0]){
			case '#detail':
				load_page('.app__content', load_url , {page : 'order_return_detail', id : mark_arr[1]}, '', function () {
					
				});
				break;
			default :
				load_page('.app__content', load_url, {page : 'order_return_list'}, '');
				break;
		}
	}
	
	$(".js-apply-check").live("click", function () {
		var id = $(this).data("id");
		var order_status = $(this).data("order_status");
		var money = parseFloat($(".js-profit").data("profit"));
		var discount_money = 0;
		var is_edit = $(".js-profit").data("edit");
		
		if (!isNaN($(".js-profit").data("discount_money"))) {
			discount_money = parseFloat($(".js-profit").data("discount_money"));
		}
		
		var readonly = "";
		var txt = "订单产品金额 + 分销商利润 ,请扣除优惠等金额";
		if (discount_money > 0) {
			txt += "，其中折扣金额为：" + discount_money;
		}
		if (is_edit == "0") {
			//readonly = 'readonly="readonly"';
		} else {
			txt = "请扣除优惠等金额";
			if (discount_money > 0) {
				txt += "，其中折扣金额为：" + discount_money;
			}
		}
		
		var detail_html = "";
		if (order_status != 2) {
			detail_html = '<tr>\
								<td>收货人姓名:</td>\
								<td>\
									<input type="text" name="address_user" />\
								</td>\
							</tr>\
							<tr>\
								<td>收货人电话:</td>\
								<td>\
									<input type="text" name="address_tel" />\
								</td>\
							</tr>\
							<tr>\
								<td>地址:</td>\
								<td>\
									<select name="province" id="provinceId_m" data-province="" style="max-width:150px;">\
										<option>省份</option>\
									</select>\
									<select name="city" id="cityId_m" data-city="" style="max-width:150px;">\
										<option>城市</option>\
									</select>\
									<select name="area" id="areaId_m" data-area="" style="max-width:150px;">\
										<option>区县</option>\
									</select>\
								</td>\
							</tr>\
							<tr>\
								<td>所在街道:</td>\
								<td>\
									<input type="text" name="address" placeholder="不需要重复填写省市区，必须大于1个字，小于120个字" style="width:300px;" />\
								</td>\
							</tr>';
		}
		
		
		
		var html_obj = $('<div class="modal hide fade order-price in" style="margin-top: -1000px; display: block;" aria-hidden="false">\
						<div class="modal-header">\
							<a class="close" data-dismiss="modal">×</a>\
							<h3 class="title">退货审核</h3>\
						</div>\
						<div class="modal-body js-detail-container">\
							<div>\
								<table class="table order-price-table">\
									<tr>\
										<td width="100">审核状态:</td>\
										<td>\
											<label for="status_3" style="float:left;"><input type="radio" name="status" value="3" checked="checked" id="status_3" /> 同意退货</label>\
											<label for="status_2" style="float:left; padding-left:10px;"><input type="radio" name="status" value="2" id="status_2" /> 不同意退货</label>\
										</td>\
									</tr>\
									<tbody class="js-cancel" style="display:none;">\
										<tr>\
											<td>不同意退货理由:</td>\
											<td><textarea style="width:400px; height:50px;" name="store_content"></textarea></td>\
										</tr>\
									</tbody>\
									<tbody class="js-setting">\
										<tr>\
											<td>退款产品金额:</td>\
											<td>\
												<input type="text" name="product_money" value="' + money + '" style="width:70px;" ' + readonly + ' />' + txt + '\
											</td>\
										</tr>\
										<tr>\
											<td>退款邮费:</td>\
											<td>\
												<input type="text" name="postage_money" value="0" />\
											</td>\
										</tr>' + detail_html + '</tbody>\
								</table>\
							</div>\
						</div>\
						<div class="modal-footer clearfix">\
							<a href="javascript:;" class="btn btn-primary pull-right js-save-data" data-id="' + id + '" data-order_status="' + order_status + '" data-loading-text="确 定...">确 定</a>\
						</div>\
					</div>');
		
		
		$('body').append(html_obj);
		$('.modal').animate({'margin-top': ($(window).scrollTop() + $(window).height() * 0.05) + 'px'}, "slow");
		
		getProvinces('provinceId_m','','省份');
		$('#provinceId_m').change(function(){
			if($(this).val() != ''){
				getCitys('cityId_m','provinceId_m','','城市');
			}else{
				$('#cityId_m').html('<option value="">城市</option>');
			}
			$('#areaId_m').html('<option value="">区县</option>');
		});
		$('#cityId_m').change(function(){
			if($(this).val() != ''){
				getAreas('areaId_m','cityId_m','','区县');
			}else{
				$('#areaId_m').html('<option value="">区县</option>');
			}
		});
	});
	
	$("input[name='status']").live("change", function () {
		var status = $(this).val();
		if (status == "3") {
			$(".js-setting").show();
			$(".js-cancel").hide();
		} else {
			$(".js-setting").hide();
			$(".js-cancel").show();
			
		}
	});
	
	$(".close").live("click", function () {
		$('.modal').animate({'margin-top': '-1000px'}, "slow", function () {
			$(".modal").remove();
		});
	});
	
	$(".js-save-data").live("click", function () {
		var id = $(this).data("id");
		var order_status = $(this).data("order_status");
		var status = $("input[name='status']:checked").val();
		
		var data = {};
		data.id = id;
		if (status == "2") {
			var store_content = $.trim($("textarea[name='store_content']").val());
			data.store_content = store_content;
			if (store_content.length == 0) {
				$("textarea[name='store_content']").focus();
				layer_tips(1, "请填写不同意退货理由");
				return;
			}
		} else {
			var product_money = $("input[name='product_money']").val();
			var postage_money = $("input[name='postage_money']").val();
			var address_user = $.trim($("input[name='address_user']").val());
			var address_tel = $.trim($("input[name='address_tel']").val());
			var provinceId_m = $("#provinceId_m").val();
			var cityId_m = $("#cityId_m").val();
			var areaId_m = $("#areaId_m").val();
			var address = $.trim($("input[name='address']").val());
			
			if (product_money.length == 0) {
				$("input[name='product_money']").focus();
				layer_tips(1, "请填写退货金额");
				return;
			}
			
			if (isNaN(product_money)) {
				$("input[name='product_money']").focus();
				layer_tips(1, "请正确填写退货金额");
				return;
			}
			
			if (postage_money.length == 0) {
				$("input[name='postage_money']").focus();
				layer_tips(1, "请填写退款邮费");
				return;
			}
			
			if (isNaN(postage_money)) {
				$("input[name='postage_money']").focus();
				layer_tips(1, "请正确填写退款邮费");
				return;
			}
			
			if (order_status != 2) {
				if (address_user.length == 0) {
					$("input[name='address_user']").focus();
					layer_tips(1, "请填写收货人姓名");
					return;
				}
				
				if (address_tel.length == 0) {
					$("input[name='address_tel']").focus();
					layer_tips(1, "请填写收货人电话");
					return;
				}
				
				var tel_re = /^\d{5,12}$/;
				if (!tel_re.test(address_tel)) {
					$("input[name='address_tel']").focus();
					layer_tips(1, "请正确填写收货人电话");
					return;
				}
				
				if (provinceId_m.length == 0) {
					layer_tips(1, "请选择省份");
					return;
				}
				
				if (cityId_m.length == 0) {
					layer_tips(1, "请选择城市");
					return;
				}
				
				if (areaId_m.length == 0) {
					layer_tips(1, "请选择地区");
					return;
				}
				
				if (address.length == 0) {
					$("input[name='address']").focus();
					layer_tips(1, "请填写所在街道");
					return;
				}
				
				if (address.length < 1 || address.length > 120) {
					$("input[name='address']").focus();
					layer_tips(1, "所在街道字数范围为1-120之间");
					return;
				}
			}
			
			data.product_money = product_money;
			data.postage_money = postage_money;
			data.address_user = address_user;
			data.address_tel = address_tel;
			data.provinceId_m = provinceId_m;
			data.cityId_m = cityId_m;
			data.areaId_m = areaId_m;
			data.address = address;
		}
		
		data.status = status;
		$.post(return_save_url, data, function (result) {
			if (result.err_code == "0") {
				layer_tips(0, result.err_msg);
				$('.modal').animate({'margin-top': '-1000px'}, "slow", function () {
					$(".modal").remove();
				});
				location_page(location.hash);
			} else {
				layer_tips(1, result.err_msg);
			}
		})
	});
	
	$(".js-express").live("click", function () {
		if ($(".js-express_detail").data("type") != "default") {
			return;
		}
		
		var express_code = $(this).data("express_code");
		var express_no = $(this).data("express_no");
		var order_no = "user";

		$(".js-express_detail").html("tr><td></td><td>努力查询中,请稍等</td></tr>");
		var url = "index.php?c=order&a=express&type=" + express_code + "&order_no=" + order_no + "&express_no=" + express_no + "&" + Math.random();
		$.getJSON(url, function(data) {
			try {
				if (data.status == true) {
					html = '';
					for(var i in data.data.data) {
						html += '<tr>';
						html += '	<td style="text-align:right; padding-right:10px; height:24px; line-height:24px;">' + data.data.data[i].time + '</td>';
						html += '	<td>' + data.data.data[i].context + '</td>'
						html += '</tr>';
					}
					$(".js-express_detail").html(html);
					$(".js-express_detail").data("type", "data");
				} else {
					html += "tr><td></td><td>查询失败</td></tr>";
					$(".js-express_detail").html(html);
				}
			}catch(e) {
				html += "tr><td></td><td>查询失败</td></tr>";
				$(".js-express_detail").html(html);
			}
		});
	});
	
	$(".js-return-over").live("click", function (e) {
		if ($(this).data("type") != "default") {
			return;
		}
		
		button_box($(this), e, 'right', 'confirm', '确认完成退货？', function(){
			var id = $(".js-return-over").data("id");
			$(".js-return-over").data("type", "submit");
			$(".js-return-over").html("提交中");
			
			$.post(return_over_url, {id : id}, function (data) {
				close_button_box();
				if (data.err_code == "0") {
					layer_tips(0, data.err_msg);
					location_page(location.hash);
				} else {
					layer_tips(1, data.err_msg);
				}
			}, "json");
		});
	});
})