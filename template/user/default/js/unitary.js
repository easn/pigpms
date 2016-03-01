/**
 * Created by pigcms_21 on 2015/2/6.
 */
$(function(){
	load_page('.app__content', load_url, {page:'index_content'}, '');
	
	$(".js-list-filter-region a").live('click', function () {
		var action = $(this).attr("href");
		location_page(action, 1)
	});
	
	
	$(".js-page-list a").live("click", function () {
		var page = $(this).data("page-num");
		location_page(window.location.hash, page);
	});

	function location_page(mark, page){
		var mark_arr = mark.split('/');
		
		switch(mark_arr[0]){
			case '#create':
				load_page('.app__content', load_url, {page : page_present_create}, '');
				break;
			case "#edit":
				if(mark_arr[1]){
					load_page('.app__content', load_url,{page:'present_edit', id : mark_arr[1]},'',function(){
						
					});
				}else{
					layer.alert('非法访问！');
					location.hash = '#list';
					location_page('');
				}
				break;
			case "#future" :
				action = "future";
				load_page('.app__content', load_url, {page : page_content, "type" : action, "p" : page}, '');
				break;
			case "#on" :
				action = "on";
				load_page('.app__content', load_url, {page : page_content, "type" : action, "p" : page}, '');
				break;
			case "#end" :
				action = "end";
				load_page('.app__content', load_url, {page : page_content, "type" : action, "p" : page}, '');
				break;
			default :
				action = "all";
				load_page('.app__content', load_url, {page : page_content, "type" : action, "p" : page}, '');
		}
	}
	
	
})