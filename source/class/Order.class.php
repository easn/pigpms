<?php
/**
 * 订单数据处理
 */
class Order {
	var $product_list;
	var $store_id_list;
	var $uid;
	var $reward_list;
	var $user_coupon_list;
	var $discount_list;
	var $postage_free_list;
	var $points_list;
	
	public function __construct($product_list = array(), $config = array()) {
		if (!empty($product_list)) {
			$this->productByStore($product_list);
		}
		
		if (is_array($config)) {
			foreach ($config as $key => $val) {
				$this->$key = $val;
			}
		} else {
			$this->config = $config;
		}
		
		if (!isset($config['uid'])) {
			if (isset($_SESSION['wap_user']['uid'])) {
				$this->uid = $_SESSION['wap_user']['uid'];
			} else if (isset($_SESSION['user']['uid'])) {
				$this->uid = $_SESSION['user']['uid'];
			} else {
				$this->uid = 0;
			}
		}
	}
	
	/**
	 * 返回所有信息
	 */
	public function all() {
		if (empty($this->reward_list)) {
			$this->reward();
		}
		
		if (empty($this->user_coupon_list)) {
			$this->coupon();
		}
		
		if (empty($this->discount_list)) {
			$this->discount();
		}
		
		$return = array();
		$return['reward_list'] = $this->reward_list;
		$return['user_coupon_list'] = $this->user_coupon_list;
		$return['discount_list'] = $this->discount_list;
		$return['postage_free_list'] = $this->postage_free_list;
		
		return $return;
	}
	
	/**
	 * 用户订单，对购买的产品进行分组，按不同供货商进行分组
	 * param $product_list 订单里的产品列表
	 */
	public function productByStore($product_list = array()) {
		if (empty($product_list)) {
			return array();
		}
		
		if (!is_array($product_list)) {
			return array();
		}
		
		$data = array();
		foreach ($product_list as $product) {
			$store_id = $product['store_id'];
			
			if ($product['wholesale_product_id']) {
				$store_id = $product['wholesale_supplier_id'];
			}
			$this->store_id_list[$store_id] = $store_id;
			$data[$store_id][] = $product;
		}
		
		$this->product_list = $data;
		
		return $data;
	}
	
	/**
	 * 积分
	 * 积分不考虑优惠、折扣、满减
	 */
	public function score($uid = 0) {
		if (!empty($uid)) {
			$this->uid = $uid;
		}
		
		if (empty($this->uid)) {
			return false;
		}
		
		if (empty($this->product_list)) {
			return array();
		}
		
		if (!empty($this->point_list)) {
			return $this->point_list;
		}
		
		$point_list = array();
		foreach ($this->product_list as $store_id => $product_list) {
			$money = 0;
			foreach ($product_list as $product) {
				$money += $product['pro_price'] * $product['pro_num'];
			}
			
			$points = D('Points')->where("store_id = '" . $store_id . "' AND type = '3' AND trade_or_amount <= " . $money)->field('points')->find();
			$points_list[$store_id]['points'] = $points['points'] + 0;
			$points_list[$store_id]['money'] = $money + 0;
		}
		
		$this->points_list = $points_list;
		return $points_list;
	}
	
	/**
	 * 满减/送数据处理
	 * 查找顶级供货商的相关满减/送活动
	 */
	public function reward() {
		if (empty($this->product_list) || empty($this->uid)) {
			return array();
		}
		
		// 满减/送处理类
		import('source.class.Appmarket');
		
		$aeward_list = array();
		foreach ($this->product_list as $store_id => $product_list) {
			$is_self_product = true;
			$uid = $product_list[0]['uid'];
			// 查找商品是否是批发商品，批发商品找到原商品
			if ($store_id != $product_list[0]['store_id']) {
				$is_self_product = false;
				$store = D('Store')->where("store_id = '" . $store_id . "'")->find();
				$uid = $store['uid'];
			}
			
			$product_id_arr = array();
			$product_price_arr = array();
			$total_price = 0;
			foreach ($product_list as $product) {
				if ($is_self_product) {
					$product_id_arr[] = $product['product_id'];
					
					$product_price_arr[$product['product_id']]['price'] = $product['pro_price'];
					// 每个商品购买数量
					$product_price_arr[$product['product_id']]['pro_num'] = $product['pro_num'];
					// 所有商品价格
					$total_price += $product['pro_price'] * $product['pro_num'];
				} else {
					$product_id_arr[] = $product['wholesale_product_id'];
						
					$product_price_arr[$product['wholesale_product_id']]['price'] = $product['pro_price'];
					// 每个商品购买数量
					$product_price_arr[$product['wholesale_product_id']]['pro_num'] = $product['pro_num'];
					// 所有商品价格
					$total_price += $product['pro_price'] * $product['pro_num'];
				}
			}
			
			
			
			$reward_arr = array();
			$reward_arr['product_id_arr'] = $product_id_arr;
			$reward_arr['store_id'] = $store_id;
			$reward_arr['uid'] = $uid;
			
			$product_arr = array();
			$product_arr['product_price_arr'] = $product_price_arr;
			$product_arr['total_price'] = $total_price;
			$reward_list[$store_id] = Appmarket::getAeward($reward_arr, $product_arr);
		}
		
		$this->reward_list = $reward_list;
		unset($reward_list);
		return $this->reward_list;
	}
	
	
	/**
	 * 优惠券数据处理
	 */
	public function coupon() {
		if (empty($this->uid)) {
			return array();
		}
		
		if (empty($this->reward_list)) {
			$this->reward();
		}
		
		if (empty($this->reward_list)) {
			return array();
		}
		
		$user_coupon_list = array();
		foreach ($this->reward_list as $store_id => $reward_list) {
			// 第一步抽出用户购买的产品有哪些优惠券
			$user_coupon_detail_list = M('User_coupon')->getListByProductId($reward_list['product_price_list'], $store_id, $this->uid);
			//print_r($user_coupon_list);
			// 第二步计算出用户购买原产品可以使用哪些优惠券
			$tmp = Appmarket::getCoupon($reward_list, $user_coupon_detail_list);
			if (!empty($tmp)) {
				$user_coupon_list[$store_id] = $tmp;
			} 
		}
		
		$this->user_coupon_list = $user_coupon_list;
		unset($user_coupon_list);
		return $this->user_coupon_list;
	}
	
	/**
	 * 每个供货商所享受的折扣率和是否包邮
	 * 外部可以直接调用此方法，需要传两个参数
	 * 在不传参数的情况下，需要在初始化时，传订单的产品列表，否则将没有折扣,没有包邮
	 */
	public function discount($uid = 0, $store_id_list = array()) {
		if (!empty($uid)) {
			$this->uid = $uid;
		}
		
		if (empty($this->uid)) {
			return array();
		}
		
		if (empty($this->store_id_list)) {
			$this->store_id_list = $store_id_list;
		}
		
		if (empty($this->store_id_list)) {
			return array();
		}
		
		$discount_list = array();
		$postage_free_list = array();
		foreach ($this->store_id_list as $store_id) {
			$user_degree = M('User_degree')->getUserDegree($this->uid, $store_id);
			
			$discount_list[$store_id] = 10;
			$postage_free_list[$store_id] = 0;
			if ($user_degree['discount']) {
				$discount_list[$store_id] = $user_degree['discount'];
			}
			
			if ($user_degree['is_postage_free']) {
				$postage_free_list[$store_id] = $user_degree['is_postage_free'];
			}
		}
		
		$this->postage_free_list = $postage_free_list;
		$this->discount_list = $discount_list;
		return $discount_list;
	}

	/**
	 * 根据订单，返回订单的折扣、优惠券、满减信息
	 * 返回类型由$is_return_money控制
	 */
	static function orderDiscount($order, $order_product_list = array(), $is_return_money = false) {
		if (empty($order)) {
			return array();
		}
		
		// 查找此订单的产品
		if (empty($order_product_list)) {
			$order_product_list = M('Order_product')->getProducts($order['order_id']);
		}
		
		if (empty($order_product_list)) {
			return array();
		}
		
		$order_id = $order['order_id'];
		if ($order['user_order_id']) {
			$order_id = $order['user_order_id'];
		}
		
		// 查看满减送
		$order_ward_list = M('Order_reward')->getByOrderId($order_id);
		// 使用优惠券
		$order_coupon_list = M('Order_coupon')->getList($order_id);
		
		$tmp_order_coupon_list = array();
		foreach ($order_coupon_list as $tmp) {
			$tmp_order_coupon_list[$tmp['store_id']] = $tmp;
		}
		$order_coupon_list = $tmp_order_coupon_list;
		unset($tmp_order_coupon_list);
		
		// 查看使用的折扣
		$order_discount_list = M('Order_discount')->getByOrderId($order_id);
		foreach ($order_discount_list as $order_discount) {
			$order_discount_list[$order_discount['store_id']] = $order_discount['discount'];
		}
		
		$discount_money = 0;
		$discount_money_list = array();
		// 对分销订单进行处理
		if ($order_id != $order['order_id']) {
			$tmp_order_ward_list = array();
			$tmp_order_coupon_list = array();
			foreach ($order_product_list as $order_product) {
				if ($order_product['wholesale_product_id']) {
					if (isset($order_ward_list[$order_product['supplier_id']]) && !empty($order_ward_list[$order_product['supplier_id']])) {
						$tmp_order_ward_list[$order_product['supplier_id']] = $order_ward_list[$order_product['supplier_id']];
					}
					
					if (isset($order_coupon_list[$order_product['supplier_id']]) && !empty($order_coupon_list[$order_product['supplier_id']])) {
						$tmp_order_coupon_list[$order_product['supplier_id']] = $order_coupon_list[$order_product['supplier_id']];
					}
				} else {
					if (isset($order_ward_list[$order_product['store_id']]) && !empty($order_ward_list[$order_product['store_id']])) {
						$tmp_order_ward_list[$order_product['store_id']] = $order_ward_list[$order_product['store_id']];
					}
					
					if (isset($order_coupon_list[$order_product['store_id']]) && !empty($order_coupon_list[$order_product['store_id']])) {
						$tmp_order_coupon_list[$order_product['store_id']] = $order_coupon_list[$order_product['store_id']];
					}
				}
				
				// 查找主订单此商品价格，折扣以此价格为基础
				$master_order_product = D('')->field('p.*, op.pro_num, op.pro_price')->table('Order_product AS op')->join('Product AS p ON p.product_id = op.product_id', 'LEFT')->where("`op`.`order_id` = '" . $order_id . "' AND (`op`.`product_id` = '" . $order_product['product_id'] . "' OR `op`.`original_product_id` = '" . $order_product['product_id'] . "') AND `op`.`sku_data` = '" . addslashes($order_product['sku_data']) . "'")->find();
				$discount = 10;
				$store_id = $master_order_product['store_id'];
				if ($master_order_product['wholesale_product_id'] && isset($order_discount_list[$master_order_product['supplier_id']])) {
					$discount = $order_discount_list[$master_order_product['supplier_id']];
					$store_id = $master_order_product['supplier_id'];
				} else if (empty($master_order_product['wholesale_product_id']) && isset($order_discount_list[$master_order_product['store_id']])) {
					$discount = $order_discount_list[$master_order_product['store_id']];
					$store_id = $master_order_product['store_id'];
				}
				
				$discount_money += $order_product['pro_num'] * $master_order_product['pro_price'] * (10 - $discount) / 10;
				$discount_money_list[$store_id] += $order_product['pro_num'] * $master_order_product['pro_price'] * (10 - $discount) / 10;
			}
			$order_ward_list = $tmp_order_ward_list;
			$order_coupon_list = $tmp_order_coupon_list;
			unset($tmp_order_ward_list);
			unset($tmp_order_coupon_list);
		} else {
			foreach ($order_product_list as $order_product) {
				$discount = 10;
				$store_id = $order_product['store_id'];
				if ($order_product['wholesale_product_id'] && isset($order_discount_list[$order_product['supplier_id']])) {
					$discount = $order_discount_list[$order_product['supplier_id']];
					$store_id = $order_product['supplier_id'];
				} else if (empty($order_product['wholesale_product_id']) && isset($order_discount_list[$order_product['store_id']])) {
					$discount = $order_discount_list[$order_product['store_id']];
					$store_id = $order_product['store_id'];
				}
				
				$discount_money += $order_product['pro_num'] * $order_product['pro_price'] * (10 - $discount) / 10;
				$discount_money_list[$store_id] += $order_product['pro_num'] * $order_product['pro_price'] * (10 - $discount) / 10;
			}
		}
		
		// 当为true时，直接返回总的优惠金额
		if ($is_return_money) {
			$return = 0;
			if ($order_ward_list) {
				foreach ($order_ward_list as $order_ward) {
					if (empty($order_ward)) {
						continue;
					}
					foreach ($order_ward as $tmp) {
						$return += $tmp['content']['cash'];
					}
				}
			}
			
			if ($order_coupon_list) {
				foreach ($order_coupon_list as $order_coupon) {
					$return += $order_coupon['money'];
				}
			}
			
			$return += $discount_money;
		} else {
			$return = array();
			$return['order_ward_list'] = $order_ward_list;
			$return['order_coupon_list'] = $order_coupon_list;
			$return['order_discount_list'] = $order_discount_list;
			$return['discount_money'] = $discount_money;
			$return['discount_money_list'] = $discount_money_list;
		}
		
		return $return;
	}
}