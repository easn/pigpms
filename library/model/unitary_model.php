<?php
/**
 * 一元夺宝
 * User: pigcms_93
 * Date: 2015/10/17
 * Time: 17:13
 */

class unitary_model extends base_model
{
	//一元夺宝总数
   public function getSellingTotal($where) {
		$where ['state'] = array('<', 2);
		return $this->db->where ( $where )->order ( 'product_id DESC' )->count ( 'product_id' );		
  }

  //商品列表
  public function getSelling($where, $order_by_field, $order_by_method, $offset, $limit, $is_show_distance = "") {
		if (! empty ( $order_by_field ) && ! empty ( $order_by_method )) {
			$order = $order_by_field . ' ' . strtoupper ( $order_by_method );
			if ($order_by_field == 'sort') {
				$order .= ', product_id DESC';
			}
		} else { // 默认排序
			$order = 'addtime DESC, product_id DESC';
		}
		$products = $this->db->field ( '*' )->where ( $where )->order ( $order )->limit ( $offset . ',' . $limit )->select ();
		foreach ( $products as &$tmp ) {
			$tmp ['image'] = getAttachmentUrl ( $tmp ['image'] );
			$tmp ['link'] = url_rewrite ( 'goods:index', array (
					'id' => $tmp ['product_id'] 
			) );
		}

		return $products;
	}
} 