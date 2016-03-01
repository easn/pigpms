<?php
/**
 * 商品库存信息数据模型
 * User: pigcms_21
 * Date: 2015/2/9
 * Time: 16:47
 */
	class product_sku_model extends base_model {

		//添加商品库存信息
		public function add($product_id, $skus) {
			$tmp_skus = array();
			foreach ($skus as $sku) {
				$data = array(
					'product_id' => $product_id,
					'properties' => $sku['properties'],
					'quantity' => $sku['quantity'],
					'price' => $sku['price'],
					'weight' => $sku['weight'],
					'code' => $sku['code'],
					'drp_level_1_cost_price' => $sku['drp_level_1_cost_price'],
					'drp_level_2_cost_price' => $sku['drp_level_2_cost_price'],
					'drp_level_3_cost_price' => $sku['drp_level_3_cost_price'],
					'drp_level_1_price' => $sku['drp_level_1_price'],
					'drp_level_2_price' => $sku['drp_level_2_price'],
					'drp_level_3_price' => $sku['drp_level_3_price']
				);
				if (!empty($sku['cost_price'])) {
					$data['cost_price'] = $sku['cost_price'];
				}
				if (!empty($sku['min_fx_price'])) {
					$data['min_fx_price'] = $sku['min_fx_price'];
				}
				if (!empty($sku['max_fx_price'])) {
					$data['max_fx_price'] = $sku['max_fx_price'];
				}

                if (!empty($sku['wholesale_price'])) {
                    $data['wholesale_price'] = $sku['wholesale_price'];
                }
                if (!empty($sku['sale_min_price'])) {
                    $data['sale_min_price'] = $sku['sale_min_price'];
                }
                if (!empty($sku['sale_max_price'])) {
                    $data['sale_max_price'] = $sku['sale_max_price'];
                }

				$sku_id = $this->db->data($data)->add();
				$tmp_skus[] = array(
					'sku_id'	 => $sku_id,
					'properties' => $sku['properties'],
					'price'	  => $sku['price'],
					'weight' => $sku['weight'],
					'quantity'   => $sku['quantity'],
					'code'	   => $sku['code']
				);
			}
			return $tmp_skus;
		}

		//修改商品库存信息
		public function edit($product_id, $skus) {
			$tmp_skus = array();
			foreach ($skus as $sku) {
				$this->db->where(array('sku_id' => $sku['sku_id'], 'product_id' => $product_id))->data(array('quantity' => $sku['quantity'], 'price' => $sku['price'], 'weight' => $sku['weight'], 'code' => $sku['code']))->save();
				$tmp_skus[] = array(
					'sku_id'	 => $sku['sku_id'],
					'properties' => $sku['properties'],
					'price'	  => $sku['price'],
					'quantity'   => $sku['quantity'],
					'weight' => $sku['weight'],
					'code'	   => $sku['code'],
                    'product_id'	   => $product_id
				);
			}
			return $tmp_skus;
		}

        //修改批发商品库存信息
        public function wholesaleUpdate($product_id, $skuId, $skus) {
            $tmp_skus = array();
            foreach ($skus as $key=>$sku) {
                $sku['sku_id'] = $skuId[$key];
                $this->db->where(array('sku_id' =>$sku['sku_id'] , 'product_id' => $product_id))->data(array('quantity' => $sku['quantity'], 'price' => $sku['price'], 'weight' => $sku['weight'], 'code' => $sku['code']))->save();
                $tmp_skus[] = array(
                    'sku_id'	 => $skuId[$key],
                    'properties' => $sku['properties'],
                    'price'	  => $sku['price'],
                    'quantity'   => $sku['quantity'],
                	'weight' => $sku['weight'],
                    'code'	   => $sku['code'],
                    'product_id'	   => $product_id
                );
            }
            return $tmp_skus;
        }

		//获取商品库存信息
		public function getSkus($product_id) {
			$skus = $this->db->where(array('product_id' => $product_id))->select();
			return $skus;
		}

		//获取商品库存信息
		public function getSku($product_id, $properties) {
			return $sku = $this->db->where(array('product_id' => $product_id, 'properties' => $properties))->find();
		}

		public function fxEdit($product_id, $skus, $unified_price_setting = 0) {
			$tmp_skus = array();
            //分销级别
            if (!empty($_SESSION['store']['drp_level'])) {
                $drp_level = $_SESSION['store']['drp_level'] + 1;
            } else {
                $drp_level = 1;
            }
            $product_info = M('Product')->get(array('product_id' => $product_id, 'store_id' => $this->store_session['store_id']), 'source_product_id');
			foreach ($skus as $sku) {
                if (empty($product_info['source_product_id']) && !empty($unified_price_setting)) {
                    $data = array(
                        'drp_level_1_cost_price' => !empty($sku['drp_level_1_cost_price']) ? $sku['drp_level_1_cost_price'] : 0,
                        'drp_level_2_cost_price' => !empty($sku['drp_level_2_cost_price']) ? $sku['drp_level_2_cost_price'] : 0,
                        'drp_level_3_cost_price' => !empty($sku['drp_level_3_cost_price']) ? $sku['drp_level_3_cost_price'] : 0,
                        'drp_level_1_price' => !empty($sku['drp_level_1_price']) ? $sku['drp_level_1_price'] : 0,
                        'drp_level_2_price' => !empty($sku['drp_level_2_price']) ? $sku['drp_level_2_price'] : 0,
                        'drp_level_3_price' => !empty($sku['drp_level_3_price']) ? $sku['drp_level_3_price'] : 0,
                    );
                    if (!empty($sku['drp_level_' . $drp_level . '_price'])) {
                        $data['min_fx_price'] = $sku['drp_level_' . $drp_level . '_price'];
                        $data['max_fx_price'] = $sku['drp_level_' . $drp_level . '_price'];
                    } else {
                        $data['min_fx_price'] = !empty($sku['min_fx_price']) ? $sku['min_fx_price'] : 0;
                        $data['max_fx_price'] = !empty($sku['max_fx_price']) ? $sku['max_fx_price'] : 0;
                    }
                    if (!empty($sku['drp_level_' . $drp_level . '_cost_price'])) {
                        $data['cost_price'] = $sku['drp_level_' . $drp_level . '_cost_price'];
                    } else {
                        $data['cost_price'] = !empty($sku['cost_price']) ? $sku['cost_price'] : 0;
                    }
                } else {
                    $data = array(
                        'min_fx_price' => !empty($sku['min_fx_price']) ? $sku['min_fx_price'] : 0,
                        'max_fx_price' => !empty($sku['max_fx_price']) ? $sku['max_fx_price'] : 0,
                        'cost_price'   => !empty($sku['cost_price']) ? $sku['cost_price'] : 0
                    );
                }
                $this->db->where(array('sku_id' => $sku['sku_id'], 'product_id' => $product_id))->data($data)->save();
			}
		}


        public function fx_Goods_Edit($product_id, $skus, $unified_price_setting = 0)
        {
            $tmp_skus = array();
            //分销级别
            if (!empty($_SESSION['store']['drp_level'])) {
                $drp_level = $_SESSION['store']['drp_level'] + 1;
            } else {
                $drp_level = 1;
            }
            $product_info = M('Product')->get(array('product_id' => $product_id, 'store_id' => $this->store_session['store_id']), 'source_product_id');

            $result = array();
            foreach ($skus as $sku) {
                    $data = array(
                        'drp_level_1_cost_price' => !empty($sku['drp_level_1_cost_price']) ? $sku['drp_level_1_cost_price'] : 0,
                        'drp_level_2_cost_price' => !empty($sku['drp_level_2_cost_price']) ? $sku['drp_level_2_cost_price'] : 0,
                        'drp_level_3_cost_price' => !empty($sku['drp_level_3_cost_price']) ? $sku['drp_level_3_cost_price'] : 0,
                        'drp_level_1_price' => !empty($sku['drp_level_1_price']) ? $sku['drp_level_1_price'] : 0,
                        'drp_level_2_price' => !empty($sku['drp_level_2_price']) ? $sku['drp_level_2_price'] : 0,
                        'drp_level_3_price' => !empty($sku['drp_level_3_price']) ? $sku['drp_level_3_price'] : 0,
                    );
                $result[] = $this->db->where(array('sku_id' => $sku['sku_id'], 'product_id' => $product_id))->data($data)->save();
            }

            return count($result)>0 ? 1 : 0;
        }

        // 批量添加批发商品的属性规格
        public function wholesaleEdit($product_id, $skus) {
            //$product_info = M('Product')->get(array('product_id' => $product_id, 'store_id' => $this->store_session['store_id']), 'source_product_id');
            foreach ($skus as $sku) {
                    $data = array(
                        'sale_min_price' => !empty($sku['sale_min_price']) ? $sku['sale_min_price'] : 0,
                        'sale_max_price' => !empty($sku['sale_max_price']) ? $sku['sale_max_price'] : 0,
                        'wholesale_price'   => !empty($sku['wholesale_price']) ? $sku['wholesale_price'] : 0
                    );
                $this->db->where(array('sku_id' => $sku['sku_id'], 'product_id' => $product_id))->data($data)->save();
            }
        }
		
		/**
		 * 用于赠品有库存信息，随机产生一个
		 * 随机产生一个产品的库存
		 * 返回库存ID，以及库存信息的序列化数组
		 */
		public function getRandSku($product_id) {
			$sku = $this->db->where(array('product_id' => $product_id, 'quantity' => array('>', '0')))->find();
			
			if (empty($sku)) {
				return '';
			}
			
			$tmpPropertiesArr = explode(';', $sku['properties']);
			$properties = $propertiesValue = $productProperties = array();
			foreach($tmpPropertiesArr as $value) {
				$tmpPro = explode(':',$value);
				$properties[] = $tmpPro[0];
				$propertiesValue[] = $tmpPro[1];
			}
			if(count($properties) == 1) {
				$findPropertiesArr = D('Product_property')->field('`pid`,`name`')->where(array('pid'=>$properties[0]))->select();
				$findPropertiesValueArr = D('Product_property_value')->field('`vid`,`value`')->where(array('vid'=>$propertiesValue[0]))->select();
			}else{
				$findPropertiesArr = D('Product_property')->field('`pid`,`name`')->where(array('pid'=>array('in',$properties)))->select();
				$findPropertiesValueArr = D('Product_property_value')->field('`vid`,`value`')->where(array('vid'=>array('in',$propertiesValue)))->select();
			}
			foreach($findPropertiesArr as $value) {
				$propertiesArr[$value['pid']] = $value['name'];
			}
			foreach($findPropertiesValueArr as $value) {
				$propertiesValueArr[$value['vid']] = $value['value'];
			}
			foreach($properties as $key => $value) {
				$productProperties[] = array('pid' => $value, 'name' => $propertiesArr[$value], 'vid' => $propertiesValue[$key], 'value' => $propertiesValueArr[$propertiesValue[$key]]);
			}
			$propertiey = serialize($productProperties);
			
			$return['sku_id'] = $sku['sku_id'];
			$return['propertiey'] = $propertiey;
			
			return $return;
		}

		// 获取一个sku_id的规格信息
		public function decodeSkuInfo($sku_id) {


				if (empty($sku_id) || !$sku_info = $this->db->where(array("sku_id"=>$sku_id))->find()) {
					return array();
				}

                $tmpPropertiesArr = explode(';', $sku_info['properties']);
                $properties = $propertiesValue = $productProperties = array();
                foreach($tmpPropertiesArr as $v){
                    $tmpPro = explode(':',$v);
                    $properties[] = $tmpPro[0];
                    $propertiesValue[] = $tmpPro[1];
                }
                if(count($properties) == 1){
                    $findPropertiesArr = D('Product_property')->field('`pid`,`name`')->where(array('pid'=>$properties[0]))->select();
                    $findPropertiesValueArr = D('Product_property_value')->field('`vid`,`value`,`image`')->where(array('vid'=>$propertiesValue[0]))->select();
                }else{
                    $findPropertiesArr = D('Product_property')->field('`pid`,`name`')->where(array('pid'=>array('in',$properties)))->select();
                    $findPropertiesValueArr = D('Product_property_value')->field('`vid`,`value`,`image`')->where(array('vid'=>array('in',$propertiesValue)))->select();
                }
                foreach($findPropertiesArr as $v){
                    $propertiesArr[$v['pid']] = $v['name'];
                }
                foreach($findPropertiesValueArr as $v){
                    $propertiesValueArr[$v['vid']] = $v['value'];
                }
                foreach($properties as $kk=>$v){
                    $productProperties[] = array('pid'=>$v,'name'=>$propertiesArr[$v],'vid'=>$propertiesValue[$kk],'value'=>$propertiesValueArr[$propertiesValue[$kk]], 'image'=>getAttachmentUrl($findPropertiesValueArr[$kk]['image']));
                }

                $sku_info['_property'] = $productProperties;
                return $sku_info;

		}
	}