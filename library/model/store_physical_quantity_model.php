<?php
/**
 * 
 * User: pigcms_16
 * Date: 2015/9/21
 * Time: 20:28
 */

class store_physical_quantity_model extends base_model{

	public function add ($data) {
		return $this->db->data($data)->add();
	}

	public function edit ($where, $data) {
		return $this->db->where($where)->data($data)->save();
	}

	public function get_list($where = ''){

		if (empty($where)) return array();
		$list = $this->db->where($where)->select();

		$data = array();
		foreach ($list as $key => $val) {
			$data[$val['physical_id']] = $val;
		}

		return $data;
	}

	//根据product_id/sku_id获取门店=>库存s
	public function getQuantityByPid($where = ''){

		if (empty($where)) return array();
		$list = $this->db->where($where)->select();

		$data = array();
		foreach ($list as $key => $val) {
			$data[$val['physical_id']] = $val['quantity'];
		}

		return $data;
	}

	//根据product_id/sku_id获取门店数组
	public function getPhysicalByPid($where = ''){
		if (empty($where)) return array();
		$list = $this->db->where($where)->select();

		$data = array();
		foreach ($list as $key => $val) {
			$data[] = $val['physical_id'];
		}

		return $data;
	}

	public function getProducts($where){
		$products = $this->db->where($where)->select();
		return $products;

	}

	public function getPhysicals($params){

		$where = '';
		foreach ($params as $field => $param) {
			$where .= " AND spq." . $field . " = " . "'" . $param . "'";
		}

		$list = $this->db->query("SELECT sp.name,spq.* FROM " . option('system.DB_PREFIX') . "store_physical_quantity spq, " . option('system.DB_PREFIX') . "store_physical sp WHERE spq.physical_id = sp.pigcms_id AND spq.quantity > 0 " . $where);
        return $list;
	}

	public function getQuantity($params){

		$where = '';
		foreach ($params as $field => $param) {
			$where .= " AND spq." . $field . " = " . "'" . $param . "'";
		}

		$list = $this->db->query("SELECT sp.name,spq.* FROM " . option('system.DB_PREFIX') . "store_physical_quantity spq, " . option('system.DB_PREFIX') . "store_physical sp WHERE spq.physical_id = sp.pigcms_id AND spq.quantity > 0 " . $where);
        return $list;
	}

	public function getPhysicalIds($params){
		$where = '';
		foreach ($params as $field => $param) {
			$where .= " AND spq." . $field . " = " . "'" . $param . "'";
		}
		$ids = array();
		$list = $this->db->query("SELECT sp.name,spq.* FROM " . option('system.DB_PREFIX') . "store_physical_quantity spq, " . option('system.DB_PREFIX') . "store_physical sp WHERE spq.physical_id = sp.pigcms_id AND spq.quantity > 0 " . $where);
		foreach ($list as $val) {
			$ids[] = $val['physical_id'];
		}

        return $ids;
	}

	// 检测是否有门店库存记录，没有则加0库存记录
	public function checkInit($store_id) {

		if (empty($store_id)) return true;

		$where['store_id'] = $store_id;
        $where['quantity'] = array('>', 0);
        $where['soldout'] = 0;
        $where['wholesale_product_id'] = 0;
        $products = M("Product")->getSelling($where, '', '', 0, 999);

        foreach ($products as $key => $value) {
            if (empty($value['has_property'])) {
				$where_spq[] = array(
						'store_id'=>$store_id,
						'product_id'=>$value['product_id'],
						'sku_id'=>0,
					);
                $products[$key]['sku'] = array();
                continue;
            } else {
                $sku_val = M("Product_sku")->getSkus($value['product_id']);
                foreach ($sku_val as $k => $v) {
	            	$where_spq[] = array(
							'store_id'=>$store_id,
							'product_id'=>$value['product_id'],
							'sku_id'=>$v['sku_id'],
						);
                }
            }

        }

		$physicals = M('Store_physical')->getList($store_id);
		if (empty($physicals)) {
			return false;
		}

        foreach ($physicals as $physical) {
        	foreach ($where_spq as $w_spq) {
        		$where_tmp = array_merge($w_spq, array("physical_id"=>$physical['pigcms_id']));
        		$result = D("Store_physical_quantity")->where($where_tmp)->find();
        		if (empty($result)) {
        			// $lack_data[] = $where_tmp;
        			D("Store_physical_quantity")->data($where_tmp)->add();
        		}

        	}
        }


	}

}