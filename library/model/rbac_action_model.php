<?php

/**
 * 用户权限模型
 * User: pigcms_21
 * Date: 2015/2/11
 * Time: 16:37
 */
class rbac_action_model extends base_model {

    function add_rbac_goods($data)
    {
        $data_rbac['uid'] = $data['uid'];
        $data_rbac['add_time'] = time();
        $data_rbac['update_time'] = time();
        $data_rbac['controller_id'] = $data['goods_control'];
        $data_rbac['action_id'] = $data['goods_action'];
        $rbac = $this->db->data($data_rbac)->add();
    }

    function add_rbac_order($data)
    {
        $data_rbac['uid'] = $data['uid'];
        $data_rbac['add_time'] = time();
        $data_rbac['update_time'] = time();
        $data_rbac['controller_id'] = $data['order_control'];
        $data_rbac['action_id'] = $data['order_action'];
        $rbac = $this->db->data($data_rbac)->add();
        unset($data_rbac);
    }


    function add_rbac_trade($data)
    {
        $data_rbac['uid'] = $data['uid'];
        $data_rbac['add_time'] = time();
        $data_rbac['update_time'] = time();
        $data_rbac['controller_id'] = $data['trade_control'];
        $data_rbac['action_id'] = $data['trade_action'];
        $rbac = $this->db->data($data_rbac)->add();
    }

    public function delete_action($data)
    {
        $goods_condition['uid'] =  $data['uid'];
        $goods_condition['controller_id'] = $data['controller_id'];
        $rbac_model = $this->db->where($goods_condition)->delete();
    }


    public function getMethod($uid, $controller, $action)
    {
        $sql = "select * from pigcms_rbac_action where uid='$uid' and controller_id='$controller' and action_id='$action'";
        $method = $this->db->query($sql);

        return count($method)>0 ? true : false;
    }
	
	public function getMethodArr($uid,$controller) {
        $sql = "select action_id from pigcms_rbac_action where uid='$uid' and controller_id='$controller'";
        $method = $this->db->query($sql);		
		
		foreach($method as $k=>$v) {
			$m[]=$v['action_id'];
		}
		
		return $m;
	}

}