<?php
class store_user_data_model extends base_model{
	/*得到一个用户的店铺数据*/
	public function getUserData($store_id,$uid){
		if(empty($store_id) || empty($uid)) return array();
		$storeUserData = $this->db->where(array('store_id'=>$store_id,'uid'=>$uid))->find();
		if(empty($storeUserData)){
			$data_store_user_data['store_id'] = $store_id;
			$data_store_user_data['uid'] 	  = $uid;
			if($this->db->data($data_store_user_data)->add()){
				return $this->getUserData($store_id,$uid);
			}else{
				return array();
			}
		}else{
			return $storeUserData;
		}
	}
	/*统计用户对应店铺的数据，并更新到表中*/
	public function updateData($store_id,$uid){
		$userData = $this->getUserData($store_id,$uid);
		if(empty($userData)){
			return array('err_code'=>1000,'err_msg'=>'没得到数据');
		}
		$database_order = D('Order');
		$condition_order['store_id'] = $condition_save_order['store_id'] = $store_id;
		//未付款
		$condition_order['status'] = '1';
		$data_save_order['order_unpay'] = $database_order->where($condition_order)->count('`order_id`');
		//未发货
		$condition_order['status'] = '2';
		$data_save_order['order_unsend'] = $database_order->where($condition_order)->count('`order_id`');
		//已发货 
		$condition_order['status'] = '3';
		$data_save_order['order_send'] = $database_order->where($condition_order)->count('`order_id`');
		//已完成
		$condition_order['status'] = '4';
		$data_save_order['order_complete'] = $database_order->where($condition_order)->count('`order_id`');
		
		$data_save_order['last_time'] = $_SERVER['REQUEST_TIME'];
		if($database_order->where($condition_order)->data($data_save_order)->save()){
			return array('err_code'=>0,'err_msg'=>'更新成功');
		}else{
			return array('err_code'=>0,'err_msg'=>'更新失败');
		}
	}

    public function updateData2($store_id,$uid){
        $userData = $this->getUserData($store_id,$uid);
        if(empty($userData)){
            return array('err_code'=>1000,'err_msg'=>'没得到数据');
        }
        $database_order = D('Order');
        $condition_order['store_id'] = $store_id;
        $condition_order['uid'] = $uid;
        //未付款
        $condition_order['status'] = '1';
        $data_save_order['order_unpay'] = $database_order->where($condition_order)->count('`order_id`');
        //未发货
        $condition_order['status'] = '2';
        $data_save_order['order_unsend'] = $database_order->where($condition_order)->count('`order_id`');
        //已发货
        $condition_order['status'] = '3';
        $data_save_order['order_send'] = $database_order->where($condition_order)->count('`order_id`');
        //已完成
        // $condition_order['status'] = '4';
		$condition_order['status'] = array('in',array('4','7'));
        $data_save_order['order_complete'] = $database_order->where($condition_order)->count('`order_id`');
        $condition_order = array();
        $condition_order['store_id'] = $store_id;
        $condition_order['uid'] = $uid;
        $data_save_order['last_time'] = $_SERVER['REQUEST_TIME'];
        if(D('Store_user_data')->where($condition_order)->data($data_save_order)->save()){
            return array('err_code'=>0,'err_msg'=>'更新成功');
        }else{
            return array('err_code'=>0,'err_msg'=>'更新失败');
        }
    }

	public function upUserData($store_id,$uid,$type){
		$userData = $this->getUserData($store_id,$uid);
		if(empty($userData)){
			return array('err_code'=>1000,'err_msg'=>'没得到数据');
		}
		$condition['pigcms_id'] = $userData['pigcms_id'];
		switch($type){
			case 'unpay':
				$data['order_unpay'] 	= $userData['order_unpay']+1;
				break;
			case 'unsend':
				if($userData['order_unpay']>0) $data['order_unpay']   = $userData['order_unpay']-1;
				$data['order_unsend'] 	= $userData['order_unsend']+1;
				break;
			case 'send':
				if($userData['order_unsend']>0) $data['order_unsend'] = $userData['order_unsend']-1;
				$data['order_send'] 	= $userData['order_send']+1;
				break;
			case 'complete':
				if($userData['order_send']>0) $data['order_send']     = $userData['order_send']-1;
				$data['order_complete'] = $userData['order_complete']+1;
				break;
			default:
				return array('err_code'=>1001,'err_msg'=>'非法数据');
		}
		
		$data['last_time'] = $_SERVER['REQUEST_TIME'];
		if($this->db->where($condition)->data($data)->save()){
			return array('err_code'=>0,'err_msg'=>'保存成功');
		}else{
			return array('err_code'=>1002,'err_msg'=>'保存失败');
		}
	}
	public function editUserData($store_id,$uid,$mintype,$plustype=''){
		$userData = $this->getUserData($store_id,$uid);
		if(empty($userData)){
			return array('err_code'=>1000,'err_msg'=>'没得到数据');
		}
		$condition['pigcms_id'] = $userData['pigcms_id'];
		switch($mintype){
			case 'unpay':
				if($userData['order_unpay']>0) $data['order_unpay'] 	= $userData['order_unpay']-1;
				break;
			case 'unsend':
				if($userData['order_unsend']>0) $data['order_unsend']   = $userData['order_unsend']-1;
				break;
			case 'send':
				if($userData['order_send']>0) $data['order_send']   = $userData['order_send']-1;
				break;
			case 'complete':
				if($userData['order_complete']>0) $data['order_complete']   = $userData['order_complete']-1;
				break;
			default:
				return array('err_code'=>1001,'err_msg'=>'非法数据');
		}
		switch($plustype){
			case 'unpay':
				$data['order_unpay'] 	= $userData['order_unpay']+1;
				break;
			case 'unsend':
				$data['order_unsend'] 	= $userData['order_unsend']+1;
				break;
			case 'send':
				$data['order_send'] 	= $userData['order_send']+1;
				break;
			case 'complete':
				$data['order_complete'] = $userData['order_complete']+1;
				break;
		}
		if($this->db->where($condition)->data($data)->save()){
			return array('err_code'=>0,'err_msg'=>'保存成功');
		}else{
			return array('err_code'=>1002,'err_msg'=>'保存失败');
		}
	}

	// 更改用户积分
	public function changePoint($store_id, $uid, $point) {
		if (empty($point)) {
			return;
		}

		$userData = $this->getUserData($store_id, $uid);
		if(empty($userData)){
			return array('err_code'=>1000,'err_msg'=>'没得到数据');
		}

		$condition['pigcms_id'] = $userData['pigcms_id'];
		$data = array();
		$data['point'] = $userData['point'] + $point;

		if($this->db->where($condition)->data($data)->save()){
			return array('err_code'=>0,'err_msg'=>'保存成功');
		}else{
			return array('err_code'=>1002,'err_msg'=>'保存失败');
		}
	}
	
	
	//获取用户 在 店铺的积分数
	public function getpoints_by_storeid($uid,$store_id) {
		//获取的店铺积分 为： 供货商店铺积分
		$now_store = M('Store')->getStore($store_id);
		if($now_store['drp_supplier_id']!='0') {
			//顶级供货商店铺id
			$store_supplier = M('Store_supplier')->getSeller(array( 'seller_id'=> $store_id, 'type'=>'1' ));
			
			if($store_supplier['supply_chain']){
				$seller_store_id_arr = explode(',',$store_supplier['supply_chain']);
				$store_id = $seller_store_id_arr[1];
				//$now_store = M('Store')->wap_getStore($store_id);
			}
		}		
		
		$where = array(
			'uid' => $uid,			
			'store_id' => $store_id		//理论上是顶级供货商id
		);
		
		
		$return = $this->db->where($where)->find();
		return $return;
		
	}	
	
	
	//更新买家 在店铺的积分
	public function updatePoints($uid, $store_id,$order_id) {
		$return = false;
		//统计该订单全部可用的积分
		$points = M('User_points_record')->getUserPointByOneOrderAvailable($order_id, $uid);

		$where = array(
			'uid' => $uid,
			'store_id' => $store_id,
		);
		
		$user_point_old = $this->db->where($where)->find();
		if($user_point_old) {
			if($this->db->where('uid='.$uid.' and store_id='.$store_id)->setInc( 'point', $points )) {
				$return = true;
			}	
		} else {
			$data = array(
				'uid' => $uid,
				'store_id' => $store_id,
				'point' => $points			
			);
			if($this->db->data($data)->add()){
				$return = true;
			}
		}

		if($return) {
			return array('err_code' => 0,'err_msg' => '用户积分更新成功');
		} else {
			return array('err_code' => 1,'err_msg' => '用户积分更新失败');
		}		
		
	} 


	
	
}
?>