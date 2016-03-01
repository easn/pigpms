<?php

/**
 * 订单数据模型
 */
class order_model extends base_model{
	/*得到一个订单信息,包含订单里的商品*/
	public function find($order_no){
		$nowOrder = $this->findSimple($order_no);
		if(!empty($nowOrder)){
			$nowOrder['proList'] = M('Order_product')->orderProduct($nowOrder['order_id']);
			return $nowOrder;
		}else{
			return array();
		}
	}
	/*得到一个订单信息*/
	public function findSimple($order_no){
		$order_no = preg_replace('#'.option('config.orderid_prefix').'#','',$order_no,1,$count);
		if($count == 0) return array();
		$nowOrder = $this->db->where(array('order_no'=>$order_no))->find();
		if(!empty($nowOrder)){
			$nowOrder['order_no_txt'] = option('config.orderid_prefix').$nowOrder['order_no'];
			if($nowOrder['payment_method']) $nowOrder['pay_type_txt'] = $this->get_pay_name($nowOrder['payment_method']);
			return $nowOrder;
		}else{
			return array();
		}
	}
	public function get_pay_name($pay_type){
		switch($pay_type){
			case 'alipay':
				$pay_type_txt = '支付宝';
				break;
			case 'tenpay':
				$pay_type_txt = '财付通';
				break;
			case 'yeepay':
				$pay_type_txt = '易宝支付';
				break;
			case 'allinpay':
				$pay_type_txt = '通联支付';
				break;
			case 'chinabank':
				$pay_type_txt = '网银在线';
				break;
			case 'weixin':
				$pay_type_txt = '微信支付';
				break;
			case 'offline':
				$pay_type_txt = '货到付款';
				break;
            case 'CardPay':
                $pay_type_txt = '会员卡支付';
			default:
				$pay_type_txt = '余额支付';
		}
		return $pay_type_txt;
	}

    public function getPaymentMethod()
    {
        return $payment_method = array(
            'alipay'    => '支付宝',
            'tenpay'    => '财付通',
            'yeepay'    => '易宝支付',
            'allinpay'  => '通联支付',
            'chinabank' => '网银在线',
            'weixin'    => '微信支付',
            'offline'   => '货到付款',
            'balance'   => '余额支付',
            'CardPay'   => '会员卡支付'
        );
    }

    public function status($status = -1)
    {
		$order_status = array(
			0 => '临时订单',
			1 => '等待买家付款',
			2 => '等待卖家发货',
			3 => '卖家已发货',
			4 => '交易完成',
			5 => '订单关闭',
			6 => '退款中',
			7 => '确认收货'
		);
		if($status == -1){
			return $order_status;
		}else{
			return $order_status[$status];
		}
    }

    public function getOrders($where, $orderby, $offset, $limit)
    {
        $orders = $this->db->where($where)->order($orderby)->limit($offset . ',' . $limit)->select();
        return $orders;
    }

    /* 批发订单 */
    public function getWholeale($where, $orderby, $offset, $limit)
    {
        $sql = "SELECT * FROM " . option('system.DB_PREFIX') . "order s, " . option('system.DB_PREFIX') . 'fx_order ss WHERE s.fx_order_id = ss.fx_order_id';
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    if (array_key_exists('like', $value)) {
                        $sql .= " AND " . $key . " like '" . $value['like'] . "'";
                    } else if (array_key_exists('in', $value)) {
                        $sql .= " AND " . $key . " in (" . $value['in'] . ")";
                    } else{
                            $sql .=" AND " .$key . "$value[0]" . "'" . $value[1] . "'";
                    }
                }else if($key == '_string'){
                    $sql .= "AND " . $value;
                }else if($key != '_string'){
                    $sql .= " AND " . $key . "=" . "'".$value."'";
                }
            }
        }

        $sql .= ' ORDER BY s.fx_order_id DESC';
        if ($limit) {
            $sql .= ' LIMIT ' . $offset . ',' . $limit;
        }

        if ($orderby) {
            $sql .= ' ORDER BY ' . $orderby;
        }

        $ordersList = $this->db->query($sql);

        return $ordersList;
    }

    public function getWholealeCount($where)
    {
        $sql = "SELECT count('s.fx_order_id') as fxOrderId FROM " . option('system.DB_PREFIX') . "order s, " . option('system.DB_PREFIX') . 'fx_order ss WHERE s.fx_order_id = ss.fx_order_id';
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    if (array_key_exists('like', $value)) {
                        $sql .= " AND " . $key . " like '" . $value['like'] . "'";
                    } else if (array_key_exists('in', $value)) {
                        $sql .= " AND " . $key . " in (" . $value['in'] . ")";
                    } else{
                        $sql .=" AND " .$key . "$value[0]" . "'" . $value[1] . "'";
                    }
                }else if($key == '_string'){
                    $sql .= "AND " . $value;
                }else if($key != '_string'){
                    $sql .= " AND " . $key . "=" . "'".$value."'";
                }
            }
        }
        $ordersCount = $this->db->query($sql);
        return !empty($ordersCount[0]['fxOrderId']) ? $ordersCount[0]['fxOrderId'] : 0;
    }

    public function getOrder($store_id, $order_id)
    {
        $order = $this->db->where(array('order_id' => $order_id, 'store_id' => $store_id))->find();
        return $order;
    }

    public function getOrderTotal($where)
    {
        $order_count = $this->db->where($where)->count('order_id');
        return $order_count;
    }

    //添加备注
    public function setBak($order_id, $bak)
    {
        return $this->db->where(array('order_id' => $order_id))->data(array('bak' => $bak))->save();
    }

    //加星
    public function addStar($order_id, $star)
    {
        return $this->db->where(array('order_id' => $order_id))->data(array('star' => $star))->save();
    }

    //设置订单状态
    public function setOrderStatus($store_id, $order_id, $data)
    {
        return $this->db->where(array('order_id' => $order_id, 'store_id' => $store_id))->data($data)->save();
    }

    public function setFields($store_id, $order_id, $data)
    {
        return $this->db->where(array('store_id' => $store_id, 'order_id' => $order_id))->data($data)->save();
    }

    public function getOrderCount($where)
    {
        return $this->db->where($where)->count('order_id');
    }

    public function getOrderAmount($where)
    {
        return $this->db->where($where)->sum('total');
    }


    //标识为分销订单（订单中包含分销商品）
    public function setFxOrder($store_id, $order_id)
    {
        return $this->db->where(array('order_id' => $order_id, 'store_id' => $store_id))->data(array('is_fx' => 1))->save();
    }

    public function add($data)
    {
        return $this->db->data($data)->add();
    }

    public function setStatus($store_id, $order_id, $status)
    {
        return $this->db->where(array('order_id' => $order_id, 'store_id' => $store_id))->data(array('status' => $status))->save();
    }

    public function editStatus($where, $data)
    {
        return $this->db->where($where)->data($data)->save();
    }
	
	public function findOrderById($orderid){
		$nowOrder = $this->db->where(array('order_id'=>$orderid))->find();
		if(!empty($nowOrder)){
			$nowOrder['status_txt'] = $this->status($nowOrder['status']);
			$nowOrder['order_no_txt'] = option('config.orderid_prefix').$nowOrder['order_no'];
			if($nowOrder['payment_method']) $nowOrder['pay_type_txt'] = $this->get_pay_name($nowOrder['payment_method']);
			//地址
			if($nowOrder['address']){
				$nowOrder['address_arr'] = array(
					'address' => unserialize($nowOrder['address']),
					'user'    => $nowOrder['address_user'],
					'tel'    => $nowOrder['address_tel'],
				);
			}
			//包裹
			if($nowOrder['sent_time']){
				$nowOrder['package_list'] = M('Order_package')->getPackages(array('user_order_id' => $nowOrder['order_id']));
			}
			$nowOrder['proList'] = M('Order_product')->orderProduct($nowOrder['order_id']);
			return $nowOrder;
		}else{
			return array();
		}
	}

    //获取分销商订单
    public function getSellerOrder($seller_uid, $fx_order_id)
    {
        $order = $this->db->where(array('uid' => $seller_uid, 'fx_order_id' => $fx_order_id))->find();
        return $order;
    }

    public function getOrdersByStatus($where, $offset = 0, $limit = 0, $order = 'order_id DESC')
    {
        $orders = $this->db->where($where)->order($order)->limit($offset . ',' . $limit)->select();
        return $orders;
    }

    public function getOrderCountByStatus($where)
    {
        return $this->db->where($where)->count('order_id');
    }

    public function get($where)
    {
        $order = $this->db->where($where)->find();
        return $order;
    }

    public function getAllOrders($where, $order_by = '')
    {
        if (!empty($order_by)) {
			$orders = $this->db->where($where)->order($order_by)->select();
		} else {
			$orders = $this->db->where($where)->select();
		}
        return $orders;
    }

	/**
	 * 根据订单取消订单
	 * 删除送给用户的积分和优惠券
	 * $cancel_mothod 订单取消方式 0过期自动取消 1卖家手动取消 2买家手动取消
	 */
	public function cancelOrder($order, $cancel_mothod = 2) {
		// 查看满减送
		$order_ward_list = M('Order_reward')->getByOrderId($order['order_id']);

		$score = 0;
		$uid = $order['uid'];
		foreach ($order_ward_list as $order_ward) {
			$score += $order_ward['content']['score'];
		}

		// 用户减相应的积分
		if ($score) {
			$score = -1 * $score;
			M('Store_user_data')->changePoint($order['store_id'], $uid, $score);
			//修改积分记录
			M('User_points_record')->changePointStatus($order['order_id'],$order['store_id'], $uid, '0','0');
		}

		// 退还使用过的优惠券
		$order_coupon = M('Order_coupon')->getByOrderId($order['order_id']);
		if (!empty($order_coupon)) {
			$where = array();
			$where['id'] = $order_coupon['user_coupon_id'];
			$data = array();
			$data['is_use'] = 0;
			$data['use_time'] = 0;
			$data['use_order_id'] = 0;
			$data['is_valid'] = 1;
			$data['delete_flg'] = 0;

			M('User_coupon')->save($data, $where);
		}

		// 更改此订单获得的优惠券,手机端游客下订单，是没有积分
		if (!empty($uid)) {
			M('User_coupon')->invaild(array('store_id' => $order['store_id'], 'uid' => $uid, 'give_order_id' => $order['order_id']));
		}
		
		// 货到付款时，订单状态为已发货，更改库存与销量
		if ($order['payment_method'] == 'codpay' && $order['status'] == 3) {
			$order_product_list = M('Order_product')->orderProduct($order['order_id'], false);
			foreach ($order_product_list as $key => $order_product) {
				//退货数量
				$return_quantity = M('Return_product')->returnNumber($order['order_id'], $order_product['pigcms_id'], true);
				//实际购买数量
				$quantity = $order_product['pro_num'] - $return_quantity;
				
				if ($quantity <= 0) {
					continue;
				}
				
				$properties = getPropertyToStr($order_product['sku_data']);
				$tmp_product_id = $order_product['product_id'];
				
				//更新库存
				D('Product')->where(array('product_id' => $tmp_product_id))->setInc('quantity', $quantity);
				if (!empty($properties)) { //更新商品属性库存
					D('Product_sku')->where(array('product_id' => $tmp_product_id, 'properties' => $properties))->setInc('quantity', $quantity);
				}
				//更新销量
				D('Product')->where(array('product_id' => $tmp_product_id))->setDec('sales', $quantity); //更新销量
				if (!empty($properties)) { //更新商品属性销量
					D('Product_sku')->where(array('product_id' => $tmp_product_id, 'properties' => $properties))->setDec('sales', $quantity);
				}
				//同步批发商品库存、销量
				$wholesale_products = D('Product')->field('product_id')->where(array('wholesale_product_id' => $tmp_product_id))->select();
				if (!empty($wholesale_products)) {
					foreach ($wholesale_products as $wholesale_product) {
						//更新库存
						D('Product')->where(array('product_id' => $wholesale_product['product_id']))->setInc('quantity', $quantity);
						if (!empty($properties)) { //更新商品属性库存
							D('Product_sku')->where(array('product_id' => $wholesale_product['product_id'], 'properties' => $properties))->setInc('quantity', $quantity);
						}
						//更新销量
						D('Product')->where(array('product_id' => $wholesale_product['product_id']))->setDec('sales', $quantity); //更新销量
						if (!empty($properties)) { //更新商品属性销量
							D('Product_sku')->where(array('product_id' => $wholesale_product['product_id'], 'properties' => $properties))->setDec('sales', $quantity);
						}
					}
				}
			}
		}

		// 更改订单状态
		return $this->editStatus(array('order_id' => $order['order_id']), array('status' => 5, 'cancel_time' => time(), 'cancel_method' => $cancel_mothod));
	}

	//计算订单总额
	public function getSales($where)
	{
		$sales = $this->db->where($where)->sum('total');
		return !empty($sales) ? $sales : 0;
	}

    //分配订单到最近门店
    public function assignOrderToPhysical($order_id) {
        if (empty($order_id)) {
            return false;
        }

        $order_product = M('Order_product');
        $store_physical = M('Store_physical');
        $store_physical_quantity = M('Store_physical_quantity');

        $order_info = D('Order')->where(array('order_id'=>$order_id))->find();
        if (empty($order_info) || $order_info['status'] != 2) {
            return false;
        }

        // 判断是否为 自提订单/货到付款订单
        if ($order_info['shipping_method'] == 'selffetch' || $order_info['payment_method'] == 'codpay') {
            return false;
        }

        $store_id = $order_info['store_id'];
        $store = M('Store')->getStore($store_id);
        // 是否使用门店物流 || 开启自动分配
        if ($store['open_local_logistics'] == 0 || $store['open_autoassign'] == 0 ) {
            return false;
        }

        // 过滤已经分配的订单商品
        $tmp_products = $order_product->getUnPackageSkuProducts($order_id);
        if (empty($tmp_products)) {
            return false;
        }


        // //获取收货地址 坐标 baiduapi搜索 
        $address = unserialize($order_info['address']);
        $address = str_replace(' ', '', $address);
        import('Http');
        $http_class = new Http();
        $url = "http://api.map.baidu.com/place/v2/search?q=".$address['address']."&region=".$address['city']."&output=json&ak=4c1bb2055e24296bbaef36574877b4e2";
        $map_json = $http_class->curlGet($url);
        $address_map = json_decode($map_json, true);
        $near_physical = array();
        if ($map_json && !empty($address_map['results'])) {
            reset($address_map['results']) & $first = current($address_map['results']);
            $store_list = $store_physical->nearshops($first['location']['lng'],$first['location']['lat'],$store_id);
            foreach ($store_list as $val) {
                $near_physical[] = $val['physical_id'];
            }
        }

        $products = array();
        foreach ($tmp_products as $tmp_product) {

            $physical_where =
            ($tmp_product != 0) ?
            array('product_id'=>$tmp_product['product_id'], 'sku_id'=>$tmp_product['sku_id']) :
            array('product_id'=>$tmp_product['product_id'], 'sku_id'=>0);

            //获取该商品库库存足够的 && 有一样缺货，则不分配订单
            $physical_ids = $store_physical_quantity->getPhysicalIds($physical_where, $tmp_product['pro_num']);
            if (empty($physical_ids)) {
                // 缺货报警 TODO
                // continue;
                return false;
            }

            //获取距离最近的门店id
            $nears = (!empty($near_physical) && !empty($physical_ids)) ? array_intersect($near_physical, $physical_ids) : $physical_ids;
            $nears = array_values($nears);

            $products[] = array(
                // 'name' => $tmp_product['name'],
                'product_id' => $tmp_product['product_id'],
                'sku_id' => $tmp_product['sku_id'],
                'pro_num' => $tmp_product['pro_num'],
                'physical_id' => $nears[0],
                'order_product_id' => $tmp_product['order_product_id'],
            );

        }

        //分包到门店 + 消减库存
        foreach ($products as $key => $val) {

            $has_save = D('Order_product')->where(array('pigcms_id'=>$val['order_product_id']))->find();
            if (empty($has_save['sp_id'])) {
                D('Order_product')->where(array('pigcms_id'=>$val['order_product_id']))->data(array('sp_id'=>$val['physical_id']))->save();
                //消减库存
                D('Store_physical_quantity')->where(array('product_id'=>$val['product_id'], 'sku_id'=>$val['sku_id'], 'physical_id'=>$val['physical_id']))->setDec('quantity', $val['pro_num']);

                // 检测该商品消减库存后，是否触发库存不足报警
                $this->checkQuantity($store_id, $val['product_id'], $val['sku_id'], $val['physical_id']);
            }

        }

        // 修改订单分配状态
        $ops = M('Order_product')->getUnPackageSkuProducts($order_id);
        if (count($ops) == 0) {     
            M('Order')->editStatus(array("order_id"=>$order_id), array("is_assigned"=>2));
        } else if (count($ops) > 0 && count($ops) < count($op_all)) {
            M('Order')->editStatus(array("order_id"=>$order_id), array("is_assigned"=>1));
        }

        return true;

    }

    // 检测门店中某产品减少后是否库存报警
    public function checkQuantity ($store_id, $product_id, $sku_id=0, $physical_id) {

        if (empty($store_id) || empty($product_id) || empty($physical_id)) return false;

        // 库存
        // $store_physical_quantity = M("Store_physical_quantity")->;

        // 获取所有商品
        $where = array();
        $where['soldout'] = 0;
        $where['wholesale_product_id'] = 0;
        $where['store_id'] = $this->store_session['store_id'];
        $products = $product->getSelling($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);

        // 检测尚未分配的商品

        // 门店
        $store_physical = D('Store_physical')->where(array('store_id'=>$this->store_session['store_id']))->select();

        // //发送买家消息通知start
        // $msg='亲，您的宝贝已发货，'.$data['express_company'].':'.$data['express_no'].'请注意查收，有问题请与本店联系！';
        // $openid = $main_user_info['open_id'];
        // //发送模板消息
        // import('source.class.Factory');
        // import('source.class.MessageFactory');
        // $template_data = array(
        //         'wecha_id' => 'oRiG1wNOKFH2W-wUyeUxhE0fzzeI',
        //         'first'    => '短信 和 通知 成功，恭喜您成为 李标 的实验对象。',
        //         'keyword1' => "这是一个name",
        //         'keyword2' => "13856905308",
        //         'keyword3' => date('Y-m-d H:i:s', time()),
        //         'remark'   => '状态：' . "啥状态？"
        // );
        // $params['template'] = array('template_id' => 'OPENTM200565259', 'template_data' => $template_data);
        // $mobile = $main_user_info['phone'];
        // $date = date('Y-m-d H:i:s', time());
        // $params['sms'] = array('mobile'=>$mobile,'token'=>'test','content'=>$msg,'sendType'=>1);
        // MessageFactory::method($params, array('smsMessage', 'TemplateMessage'));
    }

}
?>