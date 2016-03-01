<?php
/**
 * 订单
 * User: pigcms_21
 * Date: 2015/3/18
 * Time: 10:32
 */

class OrderAction extends BaseAction
{
	
	public function _initialize() 
	{
		parent::_initialize();
		
		$this->check = array('1' => '未对账', '2' => '已对账');
	}
	
	
	//所有订单（不含临时订单）
	public function index ()
	{
		$this->_orders();
	}

	//到店自提订单（不含临时订单）
	public function selffetch()
	{
		$this->_orders(array('StoreOrder.shipping_method' => 'selffetch'));
	}

	//货到付款订单（不含临时订单）
	public function codpay()
	{
		$this->_orders(array('StoreOrder.payment_method' => 'codpay'));
	}

	//代付的订单（不含临时订单）
	public function payagent()
	{
		$this->_orders(array('StoreOrder.type' => 1));
	}

	// 退款
	public function refund_peerpay() {
		$order_id = $this->_post('order_id');
		$id = $this->_post('id');
		$money = $this->_post('money');
		
		$order = M('Order')->where(array('order_id' => $order_id))->find();
		if (empty($order)) {
			$data = array();
			$data['status'] = false;
			$data['message'] = '未找到相应的订单';
			$this->ajaxReturn($data);
		}
		
		if ($order['status'] != '5') {
			$data = array();
			$data['status'] = false;
			$data['message'] = '此订单暂时不能退款';
			$this->ajaxReturn($data);
		}
		
		$order_peerpay = M('Order_peerpay')->where(array('order_id' => $order_id, 'id' => $id))->find();
		if (empty($order_peerpay)) {
			$data = array();
			$data['status'] = false;
			$data['message'] = '未找到相应的代付';
			$this->ajaxReturn($data);
		}
		
		if ($order_peerpay['status'] != 1) {
			$data = array();
			$data['status'] = false;
			$data['message'] = '未找到相应的代付';
			$this->ajaxReturn($data);
		}
		
		$peerpay_money = $order_peerpay['money'];
		if ($order_peerpay['untread_status'] == 1) {
			$peerpay_money -= $order_peerpay['untread_money'];
		}
		
		if ($peerpay_money < $money) {
			$data = array();
			$data['status'] = false;
			$data['message'] = '退款金额大于所剩金额';
			$this->ajaxReturn($data);
		}
		
		if ($money == 0) {
			$data = array();
			$data['status'] = false;
			$data['message'] = '没有退款金额';
			$this->ajaxReturn($data);
		}
		
		$third_data = unserialize($order_peerpay['third_data']);
		if (empty($third_data['transaction_id']) || empty($third_data['out_trade_no']) || empty($third_data['openid'])) {
			$data = array();
			$data['status'] = false;
			$data['message'] = '代付订单异常，不能退款';
			$this->ajaxReturn($data);
		}
		
		import('Weixin', './source/class/pay/');
		$pay_type = 'weixin';
		
		$pay_method_list = D('Config')->get_pay_method();
		if (empty($pay_method_list[$pay_type])) {
			$data = array();
			$data['status'] = false;
			$data['message'] = '请配置微信支付';
			$this->ajaxReturn($data);
		}
		
		$order_info = array();
		$order_info['transaction_id'] = $third_data['transaction_id'];
		$order_info['out_trade_no'] = $third_data['out_trade_no'];
		$order_info['out_refund_no'] = date('YmdHis') . mt_rand(1000000, 9999999);
		$order_info['total_fee'] = $order_peerpay['money'];
		$order_info['refund_fee'] = $money;
		
		$openid = $third_data['openid'];
		$payClass = new Weixin($order_info, $pay_method_list[$pay_type]['config'], '', $openid);
		$refund_peerpay = $payClass->refund(true);
		
		if ($refund_peerpay['err_code']) {
			$data = array();
			$data['status'] = false;
			$data['message'] = $refund_peerpay['err_msg'];
			$this->ajaxReturn($data);
		} else {
			$pay_data = $refund_peerpay['pay_data'];
			if ($pay_data['result_code'] == 'SUCCESS') {
				$order_peerpay_data = array();
				$order_peerpay_data['untread_money'] = $order_peerpay['untread_money'] + $money;
				$order_peerpay_data['untread_content'] = '代付过期，退款';
				$order_peerpay_data['untread_dateline'] = time();
				$order_peerpay_data['untread_status'] = 1;
				
				M('Order_peerpay')->where(array('id' => $id))->data($order_peerpay_data)->save();
				
				$data = array();
				$data['status'] = true;
				$data['date'] = date('Y-m-d H:i', $order_peerpay_data['untread_dateline']);
				$data['money'] = $order_peerpay_data['untread_money'];
				$this->ajaxReturn($data);
			} else {
				$data = array();
				$data['status'] = false;
				$data['message'] = $pay_data['err_code_des'];
				$this->ajaxReturn($data);
			}
		}
		
	}
	
	
	//短信订单列表
	public function smspay() {
		$sms_order = D('order_sms');
		$condition = array();
		
		$type = $this->_get('type');
		$keyword = $this->_get('keyword');
		$keyword = $keyword ? trim($keyword):"";
		$status = $this->_get('status');		
		$start_time = $this->_get('start_time');
		$end_time = $this->_get('end_time');
						
		if(in_array($type,array('type_nickname','type_mobile'))) {
			
			if($keyword) {
				
				if($type == 'type_nickname') {
					$condition['u.nickname'] = array("eq",$keyword);
				} elseif($type == 'type_mobile') {
				if (!preg_match("/^[1-9]{1}[0-9]{6,10}/", $keyword)) {
					$this->error('请输入正确的查询手机号');
				}
				$condition['u.phone'] = array("eq",$keyword);
				}				
			}
		}
		
		if(in_array($status,array('0','1'))) {
			$condition['s.status'] = array("eq",$status);
		}

		if(!empty($start_time) && !empty($end_time)) {
			
			$starttime = strtotime($start_time);
			$endtime = strtotime($end_time);
			$condition['s.pay_dateline'] =  array('between',$starttime.",".$endtime);
		}		

		$order_count = $sms_order->where($condition)->count();
		//echo $sms_order->getlastsql();
		import('@.ORG.system_page');
		$page = new Page($order_count, 10);
		//$orders = $sms_order->where($condition)->order("sms_order_id desc")->limit($page->firstRow . ',' . $page->listRows)->select();
		
		$orders = $sms_order->alias("s")->field("s.*,u.nickname,u.phone,u.uid")->join(C('DB_PREFIX')."user as u on s.uid=u.uid")->where($condition)->order("s.sms_order_id desc")->limit($page->firstRow . ',' . $page->listRows)->select();
	
		
		//订单状态

		
		$this->assign('page', $page->show());
		$this->assign('sms_order', $orders);
		$this->assign('status', $status);
				
		$this->display();
	}

	
	//订单详细
	public function detail()
	{
		$order = D('OrderView');
		$order_product = D('OrderProductView');
		$package = D('OrderPackage');
		$user = D('User');

		//订单状态
		$status = $order->status();
		//支付方式
		$payment_method = $order->getPaymentMethod();

		$order_id = $this->_get('id');
		$order = $order->where(array('StoreOrder.order_id' => $order_id))->find();

		$products = $order_product->getProducts($order_id);
		$comment_count = 0;
		$product_count = 0;
		foreach ($products as &$product) {
			if (!empty($product['comment'])) {
				$comment_count++;
			}
			$product_count++;

			$product['image'] = getAttachmentUrl($product['image']);
		}

		if (empty($order['uid'])) {
			$is_fans = false;
		} else {
			$is_fans = $user->isWeixinFans($order['uid']);
		}

		if (empty($order['address'])) {
			$status[0] = '未填收货地址';
		} else {
			$status[1] = '已填收货地址';
		}
		if (!empty($order['user_order_id'])) {
			$user_order_id = $order['user_order_id'];
		} else {
			$user_order_id = $order['order_id'];
		}
		//订单包裹
		$where = array();
		$where['user_order_id'] = $user_order_id;
		$tmp_packages = $package->getPackages($where);
		$packages = array();
		foreach ($tmp_packages as $package) {
			$package_products = explode(',', $package['products']);
			if (array_intersect($package_products, $tmp_products)) {
				$packages[] = $package;
			}
		}
		
		if ($order['payment_method'] == 'peerpay') {
			$order_peerpay_list = M('Order_peerpay')->where(array('order_id' => $order['order_id'], 'status' => 1))->select();
			$this->assign('order_peerpay_list', $order_peerpay_list);
		}
		
		$this->assign('is_fans', $is_fans);
		$this->assign('order', $order);
		$this->assign('products', $products);
		$this->assign('rows', $comment_count + $product_count);
		$this->assign('comment_count', $comment_count);
		$this->assign('status', $status);
		$this->assign('payment_method', $payment_method);
		$this->assign('packages', $packages);
		$this->display();
	}

	//订单数据
	private function _orders($where = array())
	{
		$order = D('OrderView');
		//搜索
		$condition = array();
		if(!empty($_GET['type']) && !empty($_GET['keyword'])){
			if($_GET['type'] == 'order_no'){
				$condition['StoreOrder.order_no'] = $_GET['keyword'];
			}else if($_GET['type'] == 'name'){
				$condition['Store.name'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['type'] == 'linkman'){
				$condition['Store.linkman'] = array('like','%'.$_GET['keyword'].'%');
			}
		}
		if (!empty($_GET['status'])) {
			$condition['StoreOrder.status'] = $_GET['status'];
		}
		if (!empty($_GET['is_check'])) {
			$condition['StoreOrder.is_check'] = $_GET['is_check'];
		}		

		//自定义查询条件
		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$condition[$key] = $value;
			}
		}
		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition['_string'] = "StoreOrder.add_time >= '" . strtotime($this->_get('start_time', 'trim')) . "' AND StoreOrder.add_time <= '" . strtotime($this->_get('end_time')) . "'";
		} else if ($this->_get('start_time', 'trim')) {
			$condition['StoreOrder.add_time'] = array('egt', strtotime($this->_get('start_time', 'trim')));
		} else if ($this->_get('end_time', 'trim')) {
			$condition['StoreOrder.add_time'] = array('elt', strtotime($this->_get('end_time', 'trim')));
		}
		
		//不含临时订单
		$order_count = $order->where($condition)->count();
		import('@.ORG.system_page');
		$page = new Page($order_count, 10);
		$orders = $order->where($condition)->order("StoreOrder.order_id DESC")->limit($page->firstRow . ',' . $page->listRows)->select();
		
		//订单状态
		$status = $order->status();
		//unset($status[0]);
		$this->assign('page', $page->show());
		$this->assign('orders', $orders);
		$this->assign('status', $status);
	 
		$this->assign('check', $this->check);
		$this->display();
	}

	
	
	public function check() {
		
		$order = D('OrderView');
		//搜索
		$condition = array();
		if(!empty($_GET['type']) && !empty($_GET['keyword'])){
			if($_GET['type'] == 'order_no'){
				$condition['StoreOrder.order_no'] = $_GET['keyword'];
			}else if($_GET['type'] == 'name'){
				$condition['Store.name'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['type'] == 'linkman'){
				$condition['Store.linkman'] = array('like','%'.$_GET['keyword'].'%');
			}
		}
		if (!empty($_GET['status'])) {
			$condition['StoreOrder.status'] = $_GET['status'];
		} else {
			$condition['StoreOrder.status'] = array('gt', 0);
		}
		if (!empty($_GET['is_check'])) {
			$condition['StoreOrder.is_check'] = $_GET['is_check'];
		} else {
			$condition['StoreOrder.is_check'] = array('gt', 0);
		}
		
		//自定义查询条件
		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$condition[$key] = $value;
			}
		}
		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition['_string'] = "StoreOrder.add_time >= '" . strtotime($this->_get('start_time', 'trim')) . "' AND StoreOrder.add_time <= '" . strtotime($this->_get('end_time')) . "'";
		} else if ($this->_get('start_time', 'trim')) {
			$condition['StoreOrder.add_time'] = array('egt', strtotime($this->_get('start_time', 'trim')));
		} else if ($this->_get('end_time', 'trim')) {
			$condition['StoreOrder.add_time'] = array('elt', strtotime($this->_get('end_time', 'trim')));
		}
		//不含临时订单
		$order_count = $order->where($condition)->count();
		import('@.ORG.system_page');
		$page = new Page($order_count, 10);
		$orders = $order->where($condition)->order("StoreOrder.order_id DESC")->limit($page->firstRow . ',' . $page->listRows)->select();
		
		//订单状态
		$status = $order->status();
		unset($status[0]);
		$this->assign('page', $page->show());
		$this->assign('orders', $orders);
		$this->assign('status', $status);
		
		$this->assign('check', $this->check);
		$this->display();
	}
	
	
	//对账日志
	public function checklog() {
		$order_check_log = D('OrderCheckLog');
		$condition = array();
		
		
		
		if(!empty($_GET['type']) && !empty($_GET['keyword'])){
			if($_GET['type'] == 'realname'){
				$condition['a.realname'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['type'] == 'account'){
				$condition['a.account'] = array('like','%'.$_GET['keyword'].'%');
			}
		}		
		
		
		
		
		
		
		$order_check_count = $order_check_log->alias("logs")->join(C('DB_PREFIX')."admin a ON a.id = logs.admin_uid")->where($condition)->count('logs.id');
		import('@.ORG.system_page');
		$page = new Page($order_check_count, 20);
		//$OrderCheckList = $order_check_log->where($condition)->limit($page->firstRow, $page->listRows)->order("id desc,timestamp desc")->select();
		$OrderCheckList = $order_check_log->alias("logs")->join(C('DB_PREFIX')."admin a ON a.id = logs.admin_uid")->field("logs.*,a.account,a.realname,a.last_ip")->where($condition)->limit($page->firstRow, $page->listRows)->order("logs.id desc,logs.timestamp desc")->select();
		//echo $order_check_log->getLastSql();

		
		$this->assign('page', $page->show());
		$this->assign('array',$OrderCheckList);
		$this->display();		
		
	}
	
	
	//详细对账抽成比例
	public function alert_check(){
		
		$order = D('OrderView');
		$order_product = D('OrderProductView');
		$package = D('OrderPackage');
		$user = D('User');
		
		//订单状态
		$status = $order->status();
		//支付方式
		$payment_method = $order->getPaymentMethod();
		
		$order_id = $this->_get('id');
		$order = $order->where(array('StoreOrder.order_id' => $order_id))->find();
		
		$products = $order_product->getProducts($order_id);
		$comment_count = 0;
		$product_count = 0;
		foreach ($products as &$product) {
			if (!empty($product['comment'])) {
				$comment_count++;
			}
			$product_count++;
		
			$product['image'] = getAttachmentUrl($product['image']);
		}
		
		if (empty($order['uid'])) {
			$is_fans = false;
		} else {
			$is_fans = $user->isWeixinFans($order['uid']);
		}
		
		if (empty($order['address'])) {
			$status[0] = '未填收货地址';
		} else {
			$status[1] = '已填收货地址';
		}
		if (!empty($order['user_order_id'])) {
			$user_order_id = $order['user_order_id'];
		} else {
			$user_order_id = $order['order_id'];
		}
		//订单包裹
		$where = array();
		$where['user_order_id'] = $user_order_id;
		$tmp_packages = $package->getPackages($where);
		$packages = array();
		foreach ($tmp_packages as $package) {
			$package_products = explode(',', $package['products']);
			if (array_intersect($package_products, $tmp_products)) {
				$packages[] = $package;
			}
		}
		$this->assign('is_fans', $is_fans);
		$this->assign('order', $order);
		$this->assign('products', $products);
		$this->assign('rows', $comment_count + $product_count);
		$this->assign('comment_count', $comment_count);
		$this->assign('status', $status);
		$this->assign('payment_method', $payment_method);
		$this->assign('packages', $packages);
		$this->display();
		
	} 
	
	//更改：出账状态
	public function check_status() {
		$order_id = $this->_post('order_id');
		$order_no = $this->_post('order_no');
		$is_check = $this->_post('is_check');
		$store_id = $this->_post('store_id');
		$order = D('Order');
		
		if(empty($order_id) || empty($order_no) || empty($is_check)){
			exit(json_encode(array('error' => 1,'message' =>'缺少必要参数')));
		}
		
		$where = array(
			'order_id' => $order_id,
			'order_no' => $order_no,				
		);
		$order->where($where)->save(array('is_check'=>$is_check));	
		
		$log_where = $where;
		$log_where['store_id'] = $store_id;
		
		
		$this->set_check_log($log_where);
		exit(json_encode(array('error' => 0,'message' =>'已出账')));
	}
	
	
	/*description:记录出账日志
	 * 
	 * @arr : 必须包含： order_id,order_no
	 */
	public function set_check_log($arr) {
		
		$check_log = D('OrderCheckLog');
		
		$thisUser = $this->system_session;

		if(empty($arr['order_id']) || empty($arr['order_no']) || empty($thisUser['id'])) {
			
			return false;
		}
		
		$description = "";
		
		$data = array(
			'timestamp' => time(),
			'admin_uid' => 	$thisUser['id'],
			'order_id' => $arr['order_id'],
			'order_no' => $arr['order_no'],
			'ip' => ip2long($_SERVER['REMOTE_ADDR']),
			'description' => $description
		);
		
		if($check_log->add($data)){
			return true;
		}else{
			return false;
		}
		
	}
	
	/**
	 * 退货列表
	 */
	public function return_order() {
		$order_no = $this->_get('order_no');
		$type = $this->_get('type');
		$status = $this->_get('status');
		$start_time = $this->_get('start_time');
		$end_time = $this->_get('end_time');
	
		if (!empty($start_time)) {
			$start_time = strtotime($start_time);
		}
	
		if (!empty($end_time)) {
			$end_time = strtotime($end_time);
		}
	
		$where_count = "user_return_id = '0'";
		$where_list = "r.user_return_id = '0'";
		if ($order_no) {
			$where_count .= " AND order_no like '%" . $order_no . "%'";
			$where_list .= " AND r.order_no like '%" . $order_no . "%'";
		}
	
		if ($type) {
			$where_count .= " AND type = '" . $type . "'";
			$where_list .= " AND r.type = '" . $type . "'";
		}
	
		if ($status) {
			$where_count .= " AND status = '" . $status . "'";
			$where_list .= " AND r.status = '" . $status . "'";
		}
	
		if ($start_time) {
			$where_count .= " AND dateline >= '" . $start_time . "'";
			$where_list .= " AND r.dateline >= '" . $start_time . "'";
		}
	
		if ($end_time) {
			$where_count .= " AND dateline >= '" . $end_time . "'";
			$where_list .= " AND r.dateline <= '" . $end_time . "'";
		}
	
		$return_model = D('Return');
		
		$type_arr = $return_model->returnType();
		$status_arr = $return_model->returnStatus();
	
		$count = $return_model->getCount($where_count);
	
		$return_list = array();
		if ($count > 0) {
			import('@.ORG.system_page');
			$page = new Page($count, 10);
				
			$return_list = $return_model->getList($where_list, $page->listRows, $page->firstRow);
			$this->assign('page', $page->show());
		}
	
		$this->assign('type', $type);
		$this->assign('status', $status);
		$this->assign('type_arr', $type_arr);
		$this->assign('status_arr', $status_arr);
		$this->assign('return_list', $return_list);
		$this->display();
	}
	
	public function return_detail() {
		$id = $this->_get('id');
		if (empty($id)) {
			echo '缺少参数';
			exit;
		}
		
		$return = D('Return')->getById($id);
		$store_list = D('Return')->getProfit($return);
		
		$this->assign('return', $return);
		$this->assign('store_list', $store_list);
		$this->display();
	}
	
	public function rights() {
		$order_no = $this->_get('order_no');
		$type = $this->_get('type');
		$status = $this->_get('status');
		$start_time = $this->_get('start_time');
		$end_time = $this->_get('end_time');
		
		if (!empty($start_time)) {
			$start_time = strtotime($start_time);
		}
		
		if (!empty($end_time)) {
			$end_time = strtotime($end_time);
		}
		
		$where_count = "user_rights_id = '0'";
		$where_list = "r.user_rights_id = '0'";
		if ($order_no) {
			$where_count .= " AND order_no like '%" . $order_no . "%'";
			$where_list .= " AND r.order_no like '%" . $order_no . "%'";
		}
		
		if ($type) {
			$where_count .= " AND type = '" . $type . "'";
			$where_list .= " AND r.type = '" . $type . "'";
		}
		
		if ($status) {
			$where_count .= " AND status = '" . $status . "'";
			$where_list .= " AND r.status = '" . $status . "'";
		}
		
		if ($start_time) {
			$where_count .= " AND dateline >= '" . $start_time . "'";
			$where_list .= " AND r.dateline >= '" . $start_time . "'";
		}
		
		if ($end_time) {
			$where_count .= " AND dateline >= '" . $end_time . "'";
			$where_list .= " AND r.dateline <= '" . $end_time . "'";
		}
		
		$rights_model = D('Rights');
		
		$type_arr = $rights_model->rightsType();
		$status_arr = $rights_model->rightsStatus();
		
		$count = $rights_model->getCount($where_count);
		
		$rights_list = array();
		if ($count > 0) {
			import('@.ORG.system_page');
			$page = new Page($count, 10);
			
			$rights_list = $rights_model->getList($where_list, $page->listRows, $page->firstRow);
			$this->assign('page', $page->show());
		}
		
		$this->assign('type', $type);
		$this->assign('status', $status);
		$this->assign('type_arr', $type_arr);
		$this->assign('status_arr', $status_arr);
		$this->assign('rights_list', $rights_list);
		$this->display();
	}
	
	public function rights_detail() {
		$id = $this->_get('id');
		if (empty($id)) {
			echo '缺少参数';
			exit;
		}
		
		$rights = D('Rights')->getById($id);
		$store_list = D('Rights')->getProfit($rights);
		
		$this->assign('rights', $rights);
		$this->assign('store_list', $store_list);
		$this->display();
	}
	
	public function rights_status() {
		$id = $this->_get('id');
		$status = $this->_get('status');
		$content = $this->_post('content');
		if (empty($id) || empty($status)) {
			echo json_encode(array('status' => false, 'msg' => '缺少参数'));
			exit;
		}
		
		if ($status != 2 && $status != 3) {
			echo json_encode(array('status' => false, 'msg' => '参数值错误'));
			exit;
		}
		
		if ($status == 3 && empty($content)) {
			echo json_encode(array('status' => false, 'msg' => '请填写处理结果'));
			exit;
		}
		
		$rights = D('Rights')->getById($id);
		
		if (empty($rights)) {
			echo json_encode(array('status' => false, 'msg' => '未找到要处理的维权'));
			exit;
		}
		
		if ($status == 2 && $rights['status'] == 2) {
			echo json_encode(array('status' => false, 'msg' => '此维权正在处理中'));
			exit;
		}
		
		if ($rights['status'] == 3) {
			echo json_encode(array('status' => false, 'msg' => '此维权已处理结束'));
			exit;
		}
		
		if ($status == 2) {
			$result = M('Rights')->where("id = '" . $id . "' or user_rights_id = '" . $id . "'")->data(array('status' => 2))->save();
			if ($result) {
				echo json_encode(array('status' => true, 'msg' => '操作完成'));
				exit;
			} else {
				echo json_encode(array('status' => false, 'msg' => '操作失败'));
				exit;
			}
		} else {
			$result = M('Rights')->where("id = '" . $id . "' or user_rights_id = '" . $id . "'")->data(array('complete_dateline' => time(), 'status' => 3, 'platform_content' => $content))->save();
			if ($result) {
				echo json_encode(array('status' => true, 'msg' => '操作完成'));
				exit;
			} else {
				echo json_encode(array('status' => false, 'msg' => '操作失败'));
				exit;
			}
		}
	}
} 