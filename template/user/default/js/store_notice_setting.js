/**
 * Created by pigcms-s on 2015/10.26
 */

$(function(){
	
	
	//保存已经选择的 通知选项
	$(".js-btn-save-store").live("click",function(){
		var fields_seria = $(".js-list-body-region input[type='checkbox']").serializeArray();
		
		$.post(
			load_url, 
			{"page":'store_notice_setting',"fields_seria":fields_seria}, 
			function(data){
				if(data.status == '0') {
					alert("保存成功！");
				} else {
					alert("保存失败！")
				}
			},
			'json'
		
		)
		
		
		
		
		
		
		
		
		
		
		
		
	})
})