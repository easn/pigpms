<?php
/**
 * 退货申请
 */
require_once dirname(__FILE__).'/global.php';

$order_id = $_GET['order_id'];
$pigcms_id = $_GET['pigcms_id'];
$is_ajax = $_POST['is_ajax'];
if (empty($order_id) || empty($pigcms_id)) {
	if ($is_ajax) {
		echo json_encode(array('status' => false, 'msg' => '缺少最基本的参数'));
		exit;
	}
	pigcms_tips('缺少最基本的参数');
}

if (empty($_SESSION['wap_user'])) {
	if ($is_ajax) {
		echo json_encode(array('status' => false, 'data' => array('nexturl' => 'refresh')));
		exit;
	}
	redirect('./login.php?referer=' . urlencode($_SERVER['REQUEST_URI']));
}

// 只能查看自己的订单
$order_model = M('Order');
$order = $order_model->find($order_id);

if (empty($order)) {
	if ($is_ajax) {
		echo json_encode(array('status' => false, 'msg' => '未找到相应的订单'));
		exit;
	}
		
	pigcms_tips('未找到相应的订单');
}

if ($order['uid'] != $_SESSION['wap_user']['uid']) {
	if ($is_ajax) {
		echo json_encode(array('status' => false, 'msg' => '您无权查看此订单'));
		exit;
	}
	pigcms_tips('您无权查看此订单');
}

if ($order['status'] != 7 && $order['status'] != 2) {
	if ($is_ajax) {
		echo json_encode(array('status' => false, 'msg' => '此订单状态不能退货'));
		exit;
	}
	pigcms_tips('此订单状态不能退货');
}

$order_product = D('')->table('Order_product AS op')->join('Product AS p ON p.product_id = op.product_id', 'left')->where("op.pigcms_id = '" . $pigcms_id . "'")->find();

if (empty($order_product)) {
	if ($is_ajax) {
		echo json_encode(array('status' => false, 'msg' => '未找到要退货的产品'));
		exit;
	}
	pigcms_tips('未找到要退货的产品');
}

if ($order_product['return_status'] == 2) {
	if ($is_ajax) {
		echo json_encode(array('status' => false, 'msg' => '此产品已经申请退货了'));
		exit;
	}
	pigcms_tips('此产品已经申请退货了');
}

$type_arr = M('Return')->returnType();
// 根据退货数量，判断是否可以退货
$return_number = M('Return_product')->returnNumber($order['order_id'], $pigcms_id);
if (IS_POST) {
	$type = $_POST['type'];
	$phone = $_POST['phone'];
	$content = trim($_POST['content']);
	$image_list = $_POST['images'];
	$number = max(0, $_POST['number'] + 0);
		
	if (!in_array($type, array(1, 2, 3, 4, 5))) {
		$type = 5;
	}
		
	if (empty($number)) {
		json_return(1001, '请至少退一件商品');
	}
	
	if (strlen($content) == 0) {
		json_return(1001, '请填写退货说明');
	}
	
	if ($order_product['pro_num'] < $return_number + $number) {
		json_return(1001, '退货数量超出购买数量');
	}
	
	import('source.class.ReturnOrder');
	$data = array();
	$data['pigcms_id'] = $pigcms_id;
	$data['type'] = $type;
	$data['phone'] = $phone;
	$data['content'] = $content;
	$data['images'] = $image_list;
	$data['number'] = $number;
		
	$result = ReturnOrder::apply($order, $data);
		
	if ($result) {
		
		
		if(false){
			//获取商家信息
			$store_info=M('Store')->getStoreById($order['store_id'],$order['uid']);
			dump($store_info);exit;
			//发送卖家消息通知start
			$msg='亲，您的订单，'.option('config.orderid_prefix') . $order['order_no'].'退货申请提交成功，请等待商家处理';
			$openid = $main_user_info['openid'];
		
			//发送模板消息
			import('source.class.Factory');
			import('source.class.MessageFactory');
			$template_data = array(
					'wecha_id' => $openid,
					'first'    => '亲，您的订单，退货申请提交成功，请等待商家处理',
					'keyword1' => option('config.orderid_prefix') . $order['order_no'],
					'keyword2' => $order['express_company'],
					'keyword3' => $order['express_no'],
					'remark'   => '状态：' . "退货申请成功"
			);
			$params['template'] = array('template_id' => 'OPENTM200565259', 'template_data' => $template_data);
			$date = date('Y-m-d H:i:s', time());
			$params['sms'] = array('mobile'=>$phone,'token'=>'test','content'=>$msg,'sendType'=>1);
			MessageFactory::method($params, array('smsMessage', 'TemplateMessage'));
			//发送卖家消息通知end
		}
		
		
		
		json_return(0, '退货申请提交成功，请等待商家处理', $result);
	} else {
		json_return(1001, '退货申请失败，请重试');
	}
}


include display('return_apply');

echo ob_get_clean();
?>