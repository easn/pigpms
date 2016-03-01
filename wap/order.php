<?php
/**
 *  订单信息
 */
require_once dirname(__FILE__).'/global.php';

if(!empty($_GET['orderno'])){
	
	if (strpos(trim($_GET['orderno']), 'SMSPAY') !== FALSE) {
		$order_no = $_GET['orderno'];
		$nowOrder = M('Order_sms')->getOne(array('smspay_no'=>$order_no));
		if(empty($nowOrder)) {
				pigcms_tips('该订单不存在','none');
		} else {
			if($nowOrder['status']!=1) {
				redirect('./pay_by_sms.php?id='.$nowOrder['smspay_no']);
			} else {
				$nowOrder['order_no_txt'] = $nowOrder['smspay_no'];
				$nowOrder['pay_type_txt'] = "微信支付";
				include display('order_sms_paid');exit;
			}
		}
	}
	
	$nowOrder = M('Order')->findSimple($_GET['orderno']);
	if(empty($nowOrder)) pigcms_tips('该订单不存在','none');
	// dump($nowOrder);
	
	//店铺资料
	$now_store = M('Store')->wap_getStore($nowOrder['store_id']);
	if(empty($now_store)) pigcms_tips('您访问的店铺不存在','none');
	
	if((!empty($nowOrder['session_id']) && $nowOrder['session_id']!=session_id()) || (!empty($nowOrder['uid']) && $nowOrder['uid'] != $wap_user['uid'])) redirect($now_store['url']);
	
	include display('order_paid');
}else if(!empty($_GET['orderid'])){
	if(empty($wap_user)) redirect('./login.php');
	
	if (strpos(trim($_GET['orderid']), 'SMSPAY') !== FALSE) {
		$orderid = strtolower($_GET['orderid']);
		$order_id = str_replace("smspay_","",$orderid);
		
		$nowOrder = M('Order_sms')->getOne(array('sms_order_id'=>$order_id));
		if($nowOrder) {
			include display('order_sms_detail');
		}
	}
	
	$nowOrder = M('Order')->findOrderById($_GET['orderid']);
	if(empty($nowOrder)) pigcms_tips('该订单不存在','none');
	//print_r($nowOrder);exit;
	//店铺资料
	$now_store = M('Store')->wap_getStore($nowOrder['store_id']);
	if(empty($now_store)) pigcms_tips('您访问的店铺不存在','none');
	
	// 查看满减送
	$order_data['order_ward_list'] = M('Order_reward')->getByOrderId($nowOrder['order_id']);
	// 使用优惠券
	$order_data['order_coupon_list'] = M('Order_coupon')->getList($nowOrder['order_id']);
	// 查看使用的折扣
	$order_discount_list = M('Order_discount')->getByOrderId($nowOrder['order_id']);
	
	foreach ($order_discount_list as $order_discount) {
		$order_data['discount_list'][$order_discount['store_id']] = $order_discount['discount'];
	}
	
	$order_peerpay_list = array();
	if ($nowOrder['payment_method'] == 'peerpay') {
		$order_peerpay_list = D('Order_peerpay')->where(array('order_id' => $nowOrder['order_id'], 'status' => 1))->select();
	}

	include display('order_detail');
}else if(!empty($_GET['del_id'])){
	if(empty($wap_user)) json_return(1000,'您尚未登录');
	$condition_order['order_id'] = intval($_GET['del_id']);
	$condition_order['uid'] = $wap_user['uid'];
	$condition_order['status'] = array('<','2');
	$database_order = D('Order');
	$nowOrder = $database_order->field('`order_id`,`store_id`,`status`')->where($condition_order)->find();
	if(empty($nowOrder)) json_return(1001,'该订单不存在或已关闭');
	$condition_save_order['order_id'] = $nowOrder['order_id'];
	$data_save_order['status'] = '5';
	$data_save_order['cancel_time'] = $_SERVER['REQUEST_TIME'];
	$data_save_order['cancel_method'] = '2';
	if($database_order->where($condition_save_order)->data($data_save_order)->save()){
		if($nowOrder['status'] == 1 && !empty($wap_user['uid'])){
			M('Store_user_data')->editUserData($nowOrder['store_id'],$wap_user['uid'],'unpay','');
		}
		json_return(0,'关闭订单成功');
	}else{
		json_return(1001,'关闭订单失败');
	}
}else{
	if(empty($wap_user)) redirect('./login.php');
	$store_id = isset($_GET['id']) ? $_GET['id'] : pigcms_tips('您输入的网址有误','none');
	$page = max(1, $_REQUEST['page']);
	$limit = 5;
	//店铺资料
	$now_store = M('Store')->wap_getStore($store_id);
	if(empty($now_store)) pigcms_tips('您访问的店铺不存在','none');
	$action = isset($_GET['action']) ? $_GET['action'] : 'all';
	$uid = $wap_user['uid'];
	$where = array();
	$where['uid'] = $uid;
	$where['store_id'] = $store_id;
	$page_url = 'order.php?id=' . $store_id . '&action=' . $action;
	switch($action){
		case 'unpay':
			$pageTitle = '待付款的订单';
			$where['status'] = 1;
			break;
		case 'unsend':
			$pageTitle = '待发货的订单';
			$where['status'] = 2;
			break;
		case 'send':
			$pageTitle = '已发货的订单';
			$where['status'] = 3;
			break;
		case 'complete':
			$pageTitle = '已完成的订单';
			$where['status'] = 4;
			break;
		default:
			$where['status'] = array('!=', 5);
			$pageTitle = '全部订单';
	}
	
	$count = D('Order')->where($where)->count('order_id');

	$orderList = array();
	$pages = '';
	if ($count > 0) {
		$page = min($page, ceil($count / $limit));
		$offset = ($page - 1) * $limit;
		
		$orderList = D('Order')->where($where)->order('order_id desc')->limit($offset . ', ' . $limit)->select();
		$order_id_arr = array();
		$store_id_arr = array();
		$physical_id_arr = array();
		
		$config_order_return_date = option('config.order_return_date');
		$config_order_complete_date = option('config.order_complete_date');
		
		foreach ($orderList as &$value) {
			if ($value['comment']) {
				$value['comment_arr'] = unserialize($value['comment']);
			}
			
			$value['address'] = unserialize($value['address']);
			$value['order_no_txt'] = option('config.orderid_prefix').$value['order_no'];
			
			if ($value['status'] < 2) {
				$value['url'] = './pay.php?id='.$value['order_no_txt'];
			} else {
				$value['url'] = './order.php?orderid='.$value['order_id'];
			}
			
			$order_id_arr[$value['order_id']] = $value['order_id'];
			
			if ($value['shipping_method'] == 'selffetch') {
				if ($value['address']['physical_id']) {
					$physical_id_arr[$value['address']['physical_id']] = $value['address']['physical_id'];
				} else if ($value['address']['store_id']) {
					$store_id_arr[$value['address']['store_id']] = $value['address']['store_id'];
				}
			}
			
			$is_return = false;
			if ($value['status'] == '7') {
				if ($value['delivery_time'] + $config_order_return_date * 24 * 3600 >= time()) {
					$is_return = true;
				}
			} else if ($value['status'] == '3') {
				if ($value['send_time'] + $config_order_complete_date * 24 * 3600 >= time()) {
					$is_return = true;
				}
			} else if ($value['status'] == 2) {
				$is_return = true;
			}
			
			$is_rights = false;
			if (in_array($value['status'], array(2, 3, 4, 7)) && $value['add_time'] + 5 * 24 * 3600 < time()) {
				$is_rights = true;
			}
			
			$value['is_return'] = $is_return;
			$value['is_rights'] = $is_rights;
			$value['order_product_list'] = M('Order_product')->orderProduct($value['order_id']);
		}
		
		$physical_list = array();
		$store_contact_list = array();
		if (!empty($store_id_arr)) {
			$store_contact_list = M('Store_contact')->storeContactList($store_id_arr);
		}
		
		if (!empty($physical_id_arr)) {
			$physical_list = M('Store_physical')->getListByIDList($physical_id_arr);
		}
		
		// 分页
		import('source.class.user_page');
		$user_page = new Page($count, $limit, $page);
		$pages = $user_page->show();
	}
	/*$orderList = D('')->field('`o`.`order_id`,`o`.`order_no`,`o`.`total`,`o`.`sub_total`,`o`.`pro_count`,`o`.`status`,`o`.`float_amount`,`op`.`pro_num`,`op`.`pro_price`,`op`.`sku_data`,`p`.`image`,`p`.`name`')->table(array('Order'=>'o','Order_product'=>'op','Product'=>'p'))->where($condition_order)->order('`o`.`order_id` DESC')->select();

	foreach($orderList as &$value){
		if($value['sku_data']){
			$value['sku_data_arr'] = unserialize($value['sku_data']);
		}
		if($value['comment']){
			$value['comment_arr'] = unserialize($value['comment']);
		}
		$value['order_no_txt'] = option('config.orderid_prefix').$value['order_no'];
		$value['image'] = getAttachmentUrl($value['image']);
		if($value['status'] < 2){
			$value['url'] = './pay.php?id='.$value['order_no_txt'];
		}else{
			$value['url'] = './order.php?orderid='.$value['order_id'];
		}
	}*/

	
	if($_REQUEST['ajax'] == '1') {
		$json_return['count'] = $count;	
		$json_return['list'] = $orderList;	
		$comment_url = 'comment.php?action=comment_by_order&id=' . $store_id;
		
		$json_return['url'] = array(
			'comment_url' => $comment_url,
		);
		
		$json_return['physical_list'] = $physical_list;
		$json_return['store_contact_list'] = $store_contact_list;
		if(count($orderList) < $limit){
			$json_return['noNextPage'] = true;
		}
		$json_return['max_page'] = ceil($count / $limit);
		json_return(0, $json_return);
	}

	//分享配置 start
	$share_conf 	= array(
		'title' 	=> $now_store['name'].'-店铺订单', // 分享标题
		'desc' 		=> str_replace(array("\r","\n"), array('',''),  $now_store['intro']), // 分享描述
		'link' 		=> 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], // 分享链接
		'imgUrl' 	=> $now_store['logo'], // 分享图片链接
		'type'		=> '', // 分享类型,music、video或link，不填默认为link
		'dataUrl'	=> '', // 如果type是music或video，则要提供数据链接，默认为空
	);
	import('WechatShare');
	$share 		= new WechatShare();
	$shareData 	= $share->getSgin($share_conf);
	//分享配置 end

	 
	include display('order');
}
	
echo ob_get_clean();
?>