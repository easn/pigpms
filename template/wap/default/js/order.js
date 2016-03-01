$(function(){
	if($('li.block-order').size() == 0){
		$('.empty-list').show();
	}
	$('.js-cancel-order').click(function(){
		var nowDom = $(this);
		$.post('./order.php?del_id='+$(this).data('id'),function(result){
			motify.log(result.err_msg);
			if(result.err_code == 0){
				nowDom.closest('li').remove();
				if($('li.block-order').size() == 0){
					$('.empty-list').show();
				}
			}
		});
	});
	
	$(".js-delivery").click(function () {
		var delivery_obj = $(this);
		if (delivery_obj.attr("disabled") == "disabled") {
			return;
		}
		
		if (!confirm("确认已经收到货了？")) {
			return;
		}
		
		var order_no = delivery_obj.data("order_no");
		delivery_obj.attr("disabled", "disabled");
		var url = "order_delivery.php?order_no=" + order_no;
		$.getJSON(url, function (result) {
			try {
				motify.log(result.err_msg);
				if (result.err_code == "0") {
					location.reload();
				} else {
					delivery_obj.removeAttr("disabled");
				}
			} catch(e) {
				motify.log("网络错误");
				delivery_obj.removeAttr("disabled");
			}
		});
	});
});