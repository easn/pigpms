<?php

class goods_controller extends base_controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->store_session))
            redirect(url('index:index'));

        $controller = MODULE_NAME;
        $action = ACTION_NAME;
        $rbac_action = M('Rbac_action');
        $exclude = array(
            'goods_load', 'comments_load', 'load', 'delivery_load', 'physical_stock', 'stock_load','checkoutProduct'
        ); //排除验证方法列表
        /* 判断用户是否有权限操作 type==1 是门店管理员的身份 */
        if ($this->user_session['type'] == 1) {
            $method = $rbac_action->getMethod($this->user_session['uid'], $controller, $action);
            if (!$method && !in_array($action, $exclude)) {
                header("Content-type: text/html;charset=utf-8");
                if (IS_AJAX) {
                    if (IS_GET) {
                        json_return(0, '您没有操作权限');
                    } elseif (IS_POST) {
                        json_return(0, '您没有操作权限');
                    }
                } else if (IS_POST) {
                    exit('您没有操作权限！');
                } else {
                    exit('您没有操作权限！');
                }
            }
        }
        
    }

    // 门店管理rbac权限报错
    private function rbacError () {
        header("Content-type: text/html;charset=utf-8");
        if (IS_AJAX) {
            if (IS_GET) {
                json_return(0, '您没有操作权限');
            } elseif (IS_POST) {
                json_return(0, '您没有操作权限');
            }
        } else if (IS_POST) {
            pigcms_tips('您没有操作权限！', 'none');
        } else {
            pigcms_tips('您没有操作权限！', 'none');
        }
        exit;
    }

    public function index() {
        if (IS_POST) {
            $product = M('Product');
            $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();
            $result = $product->putaway($this->store_session['store_id'], $product_id);
            if ($result) {
                json_return(0, '商品上架成功');
            } else {
                json_return(1001, '商品上架失败');
            }
            exit;
        } else {
            $weixin_bind = M('Weixin_bind')->get_account_type($this->store_session['store_id']);
            if ($weixin_bind['errcode'] == 0 && $weixin_bind['code'] == 3) {
                
            }
        }
        $this->display();
    }

    //商品分类列表
    public function category() {
        $this->display();
    }

    public function goods_category_add() {
        if (IS_POST) {
            $database_product_group = D('Product_group');
            $data_product_group['store_id'] = $this->store_session['store_id'];
            $data_product_group['group_name'] = mysql_real_escape_string($_POST['cat_name']);
            $data_product_group['is_show_name'] = $_POST['show_tag_title'];
            $data_product_group['first_sort'] = $_POST['first_sort'];
            $data_product_group['second_sort'] = $_POST['second_sort'];
            $data_product_group['list_style_size'] = $_POST['size'];
            $data_product_group['list_style_type'] = $_POST['size_type'];
            $data_product_group['is_show_price'] = $_POST['price'];
            $data_product_group['is_show_product_name'] = $_POST['show_title'];
            $data_product_group['is_show_buy_button'] = $_POST['buy_btn'];
            $data_product_group['buy_button_style'] = $_POST['buy_btn_type'];
            $data_product_group['group_label'] = mysql_real_escape_string($_POST['cat_desc']);
            $data_product_group['has_custom'] = !empty($_POST['custom']) ? 1 : 0;
            $data_product_group['add_time'] = $_SERVER['REQUEST_TIME'];
            $group_id = $database_product_group->data($data_product_group)->add();
            if (empty($group_id)) {
                json_return(1000, '分组添加失败');
            }

            if ($_POST['custom']) {
                $field_result = M('Custom_field')->add_field($this->store_session['store_id'], $_POST['custom'], 'good_cat', $group_id);
                if (empty($field_result)) {
                    json_return(1001, '分组添加成功！自定义内容添加失败。请刷新页面重试');
                } else {
                    json_return(0, '添加成功');
                }
            } else {
                json_return(0, '添加成功');
            }
        } else {
            pigcms_tips('非法访问');
        }
    }

    public function goods_category_edit() {
        if (IS_POST) {
            $database_product_group = D('Product_group');
            $condition_product_group['group_id'] = $_POST['cat_id'];
            $condition_product_group['store_id'] = $this->store_session['store_id'];
            $data_product_group['group_name'] = $_POST['cat_name'];
            $data_product_group['is_show_name'] = $_POST['show_tag_title'];
            $data_product_group['first_sort'] = $_POST['first_sort'];
            $data_product_group['second_sort'] = $_POST['second_sort'];
            $data_product_group['list_style_size'] = $_POST['size'];
            $data_product_group['list_style_type'] = $_POST['size_type'];
            $data_product_group['is_show_price'] = $_POST['price'];
            $data_product_group['is_show_product_name'] = $_POST['show_title'];
            $data_product_group['is_show_buy_button'] = $_POST['buy_btn'];
            $data_product_group['buy_button_style'] = $_POST['buy_btn_type'];
            $data_product_group['group_label'] = $_POST['cat_desc'];
            $data_product_group['has_custom'] = !empty($_POST['custom']) ? 1 : 0;
            $data_product_group['add_time'] = $_SERVER['REQUEST_TIME'];
            if (!$database_product_group->where($condition_product_group)->data($data_product_group)->save()) {
                json_return(1000, '分类编辑失败，请重试');
            }

            M('Custom_field')->delete_field($this->store_session['store_id'], 'good_cat', $_POST['cat_id']);
            if ($_POST['custom']) {
                $field_result = M('Custom_field')->add_field($this->store_session['store_id'], $_POST['custom'], 'good_cat', $_POST['cat_id']);
                if (empty($field_result)) {
                    json_return(1001, '分组编辑成功！自定义内容添加失败。请刷新页面重试');
                } else {
                    json_return(0, '编辑成功');
                }
            } else {
                json_return(0, '编辑成功');
            }
        } else {
            pigcms_tips('非法访问');
        }
    }

    //商品分类删除
    public function goods_category_delete() {
        $database_product_group = D('Product_group');
        $condition_product_group['store_id'] = $this->store_session['store_id'];
        $condition_product_group['group_id'] = $_POST['group_id'];
        $now_group = $database_product_group->field('`group_id`,`store_id`,`has_custom`')->where($condition_product_group)->find();
        if (empty($now_group)) {
            json_return(1004, '页面不存在');
        }

        if ($now_group['has_custom']) {
            M('Custom_field')->delete_field($now_group['store_id'], 'good_cat', $now_group['group_id']);
        }
        if ($database_product_group->where($condition_product_group)->delete()) {
            json_return(0, '删除成功');
        } else {
            json_return(1005, '删除失败，请重试');
        }
    }

    //ajax得到商品分组
    public function get_goodsCategory() {
        $group_list = M('Product_group')->get_all_list($this->store_session['store_id']);
        if (empty($group_list)) {
            json_return(1000, '没有分类');
        } else {
            json_return(0, $group_list);
        }
    }

    //添加商品
    public function create() {
        if (IS_POST) {
            if ($_POST['group_ids']) {
                $tmp_arr = explode(',', $_POST['group_ids']);
                $group_ids_arr = array();
                foreach ($tmp_arr as $value) {
                    if (!empty($value)) {
                        $group_ids_arr[] = $value;
                    }
                }
            }

            $product = M('Product');
            $product_category = M('Product_category');
            $product_image = M('Product_image');
            $product_sku = M('Product_sku');
            $product_to_property = M('Product_to_property');
            $product_to_property_value = M('Product_to_property_value');
            $system_product_to_property_value = M('System_product_to_property_value');
            $product_custom_field = M('Product_custom_field');
            $common_data = M('Common_data');

            $data = array();
            $data['uid'] = $this->user_session['uid'];
            $data['store_id'] = $this->store_session['store_id'];
            $data['category_id'] = isset($_POST['category_id']) ? intval(trim($_POST['category_id'])) : 0; //分类
            $data['buy_way'] = isset($_POST['buy_way']) ? intval(trim($_POST['buy_way'])) : 0; //购买方式
            $data['buy_url'] = isset($_POST['buy_url']) ? trim($_POST['buy_url']) : ''; //购买地址
            $data['quantity'] = isset($_POST['quantity']) ? intval(trim($_POST['quantity'])) : 0; //数量
            $data['show_sku'] = isset($_POST['show_stock']) ? intval(trim($_POST['show_stock'])) : 0; //是否显示库存数量
            $data['code'] = isset($_POST['code']) ? trim($_POST['code']) : ''; //商家编码
            $data['name'] = isset($_POST['name']) ? (trim($_POST['name'])) : ''; //名称
            $data['price'] = isset($_POST['price']) ? floatval(trim($_POST['price'])) : 0; //价格
            $data['original_price'] = isset($_POST['original_price']) ? floatval(trim($_POST['original_price'])) : 0; //原价
            $data['weight'] = isset($_POST['weight']) ? floatval(trim($_POST['weight'])) : 0; // 重量
            $data['postage_type'] = isset($_POST['postage_type']) ? intval(trim($_POST['postage_type'])) : 0; //邮费类型
            $data['postage'] = isset($_POST['postage']) ? floatval(trim($_POST['postage'])) : 0; //固定邮费
            $data['postage_template_id'] = isset($_POST['postage_tpl_id']) ? intval(trim($_POST['postage_tpl_id'])) : 0; //邮费模板
            $data['buyer_quota'] = isset($_POST['buyer_quota']) ? intval(trim($_POST['buyer_quota'])) : 0; //每人限购
            $data['sold_time'] = isset($_POST['sold_time']) ? trim($_POST['sold_time']) : 0; //开售时间
            $data['allow_discount'] = isset($_POST['discount']) ? intval(trim($_POST['discount'])) : 0; //会员折扣
            $data['invoice'] = isset($_POST['invoice']) ? intval(trim($_POST['invoice'])) : 0; //发票
            $data['warranty'] = isset($_POST['warranty']) ? intval(trim($_POST['warranty'])) : 0; //保修
            $data['date_added'] = time();
            $data['status'] = $_POST['status']; //销售状态 下架 上架
            $data['intro'] = isset($_POST['intro']) ? (trim($_POST['intro'])) : ''; //商品简介
            $data['info'] = isset($_POST['info']) ? (trim($_POST['info'])) : ''; //商品描述
            $data['has_custom'] = !empty($_POST['custom']) ? 1 : 0;
            $data['has_category'] = !empty($group_ids_arr) ? 1 : 0;
            $data['is_recommend'] = !empty($_POST['is_recommend']) ? 1 : 0;
            $data['is_fx'] = !empty($_POST['is_fx']) ? intval($_POST['is_fx']) : 0; // 商品可分销
            $data['is_wholesale'] = !empty($_POST['is_wholesale']) ? intval($_POST['is_wholesale']) : 0;  // 商品可批发

            if (empty($data['buy_way'])) {
                $data['quantity'] = 1;
            }

            $images = isset($_POST['images']) ? $_POST['images'] : array(); //图片
            $preview = isset($_POST['preview']) ? $_POST['preview'] : 0; //是否跳转到前台预览
            $tmp_skus = isset($_POST['skus']) ? $_POST['skus'] : array(); //商品库存信息
            $fields = isset($_POST['fields']) ? $_POST['fields'] : array(); //商品自定义字段
            $sys_fields2 = isset($_POST['sys_fields']) ? $_POST['sys_fields'] : array(); //商品栏目筛选属性
            if (!empty($tmp_skus)) {
                $data['has_property'] = 1;
            }


            if (!empty($images)) {
                foreach ($images as &$image) {
                    $image = getAttachment($image);
                }
                $data['image'] = $images[0]; //商品主图
            }

            $category = $product_category->getCategory($data['category_id']);
            if (!empty($category['cat_fid'])) {
                $data['category_fid'] = $category['cat_fid'];
            } else {
                $data['category_fid'] = $data['category_id'];
                $data['category_id'] = 0;
            }
            //商品添加
            $product_id = $product->add($data);
            if ($product_id) {
                $common_data->setProductQty();

                if (!empty($images)) {
                    $images = $product_image->add($product_id, $images);
                }


                //添加栏目商品性值关联
                foreach ($sys_fields2 as $v) {
                    $system_product_to_property_value->add(array('product_id' => $product_id, 'pid' => $v['sys_property_id'], 'vid' => $v['sys_property_value_id']));
                }

                if (!empty($tmp_skus)) {
                    $skus = array();
                    $props_vid = array();
                    $props_pid = array();
                    foreach ($tmp_skus as $sku) {
                        $props_arr = explode(';', $sku['props_str']);
                        foreach ($props_arr as $prop) {
                            $prop_arr = explode(':', $prop);
                            $pid = $prop_arr[0];
                            $vid = $prop_arr[1];
                            $props_vid[$pid . ';' . $vid] = array(
                                'pid' => $pid,
                                'vid' => $vid
                            );
                            $props_pid[$pid] = $pid;
                        }
                        $skus[] = array(
                            'properties' => $sku['props_str'],
                            'price' => $sku['price'],
                        	'weight' => $sku['weight'],
                            'quantity' => $sku['quantity'],
                            'code' => $sku['code']
                        );
                    }

                    $skus = $product_sku->add($product_id, $skus);

                    //添加商品属性关联
                    $i = 1;
                    foreach ($props_pid as $prop_pid) {
                        $product_to_property->add(array('store_id' => $this->store_session['store_id'], 'product_id' => $product_id, 'pid' => $prop_pid, 'order_by' => $i));
                        $i++;
                    }
                    //添加商品性值关联
                    $i = 1;
                    foreach ($props_vid as $prop_vid) {
                        $product_to_property_value->add(array('store_id' => $this->store_session['store_id'], 'product_id' => $product_id, 'pid' => $prop_vid['pid'], 'vid' => $prop_vid['vid'], 'order_by' => $i));
                        $i++;
                    }
                }

                //修改自定义字段
                if ($product_custom_field->delete($product_id)) {
                    $product_custom_field->add($product_id, $fields);
                }

                //商品分组
                if (!empty($group_ids_arr)) {
                    $database_product_group = D('Product_group');
                    $database_product_to_group = D('Product_to_group');
                    $data_product_to_group['product_id'] = $product_id;
                    foreach ($group_ids_arr as $value) {
                        $data_product_to_group['group_id'] = $value;
                        if ($database_product_to_group->data($data_product_to_group)->add()) {
                            $database_product_group->where(array('group_id' => $value))->setInc('product_count');
                        }
                    }
                }
                if ($_POST['custom']) {
                    $field_result = M('Custom_field')->add_field($this->store_session['store_id'], $_POST['custom'], 'good', $product_id);
                }

                if ($preview) {
                    echo json_return(0, option('config.wap_site_url') . '/good.php?id=' . $product_id); //预览
                } else {
                    echo json_return(0, $product_id);
                }
            } else {
                json_return('1010', '商品添加失败');
            }
            exit;
        }
        $this->display();
    }

	//修改商品
	public function edit() {
		if (IS_POST) {
			if ($_POST['group_ids']) {
				$tmp_arr = explode(',', $_POST['group_ids']);
				$group_ids_arr = array();
				foreach ($tmp_arr as $value) {
					if (!empty($value)) {
						$group_ids_arr[] = $value;
					}
				}
			}

			$product = M('Product');
			$product_category = M('Product_category');
			$product_image = M('Product_image');
			$product_custom_field = M('Product_custom_field');
			$product_sku = M('Product_sku');
			$system_product_to_property_value = M('System_product_to_property_value');
			$product_to_property = M('Product_to_property');
			$product_to_property_value = M('Product_to_property_value');

			$store_id = $this->store_session['store_id'];
			$product_id = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
			
			if (empty($product_id)) {
				json_return(1000, '缺少最基本的参数');
			}
			
			if (empty($store_id)) {
				json_return(1000, '请刷新页后，重新操作');
			}
			
			$product_detail = $product->get(array('product_id' => $product_id));
			if (empty($product_detail)) {
				json_return(1000, '未找到要修改的商品');
			}
			
			if ($product_detail['store_id'] != $store_id) {
				json_return(1000, '请操作自己店铺的商品');
			}

			$data['uid'] = $this->user_session['uid'];
			$data['is_fx'] = $_POST['is_fx'];
			$data['is_wholesale'] = $_POST['is_wholesale'];
			$data['category_id'] = isset($_POST['category_id']) ? intval(trim($_POST['category_id'])) : 0; //分类
			$data['buy_way'] = isset($_POST['buy_way']) ? intval(trim($_POST['buy_way'])) : 0; //购买方式
			$data['buy_url'] = isset($_POST['buy_url']) ? trim($_POST['buy_url']) : ''; //购买地址
			$data['quantity'] = isset($_POST['quantity']) ? intval(trim($_POST['quantity'])) : 0; //数量
			$data['show_sku'] = isset($_POST['show_stock']) ? intval(trim($_POST['show_stock'])) : 0; //是否显示库存数量
			$data['code'] = isset($_POST['code']) ? trim($_POST['code']) : ''; //商家编码
			$data['name'] = isset($_POST['name']) ? (trim($_POST['name'])) : ''; //名称
			$data['price'] = isset($_POST['price']) ? floatval(trim($_POST['price'])) : 0; //价格
			$data['original_price'] = isset($_POST['original_price']) ? floatval(trim($_POST['original_price'])) : 0; //原价
			$data['weight'] = isset($_POST['weight']) ? floatval(trim($_POST['weight'])) : 0;
			if (isset($_POST['postage_type'])) {
				$data['postage_type'] = !empty($_POST['postage_type']) ? intval(trim($_POST['postage_type'])) : 0; //邮费类型
			}
			if (isset($_POST['postage'])) {
				$data['postage'] = !empty($_POST['postage']) ? floatval(trim($_POST['postage'])) : 0; //固定邮费
			}
			if (isset($_POST['postage_tpl_id'])) {
				$data['postage_template_id'] = !empty($_POST['postage_tpl_id']) ? intval(trim($_POST['postage_tpl_id'])) : 0; //邮费模板
			}
			$data['buyer_quota'] = isset($_POST['buyer_quota']) ? intval(trim($_POST['buyer_quota'])) : 0; //每人限购
			$data['sold_time'] = isset($_POST['sold_time']) ? strtotime(trim($_POST['sold_time'])) : 0; //开售时间
			$data['allow_discount'] = isset($_POST['discount']) ? intval(trim($_POST['discount'])) : 0; //会员折扣
			$data['invoice'] = isset($_POST['invoice']) ? intval(trim($_POST['invoice'])) : 0; //发票
			$data['warranty'] = isset($_POST['warranty']) ? intval(trim($_POST['warranty'])) : 0; //保修
			$data['status'] = $_POST['status']; //销售状态 下架 上架
			$data['intro'] = isset($_POST['intro']) ? (trim($_POST['intro'])) : ''; //商品简介
			$data['info'] = isset($_POST['info']) ? (trim($_POST['info'])) : ''; //商品描述
			$data['has_custom'] = !empty($_POST['custom']) ? 1 : 0;
			$data['has_category'] = !empty($group_ids_arr) ? 1 : 0;
			$data['is_recommend'] = !empty($_POST['is_recommend']) ? 1 : 0;
			if (empty($data['buy_way'])) {
				$data['quantity'] = 1;
			}
			$preview = isset($_POST['preview']) ? $_POST['preview'] : 0; //是否跳转到前台预览
			$images = isset($_POST['images']) ? $_POST['images'] : array(); //图片
			$sys_fields2 = isset($_POST['sys_fields']) ? $_POST['sys_fields'] : array(); //商品栏目筛选属性
			$skus = isset($_POST['skus']) ? $_POST['skus'] : array(); //商品库存信息
			
			// 对数据进行处理和判断
			if ($data['price'] <= 0) {
				json_return(1000, '请正确填写商品的价格');
			}
			
			if (!empty($images)) {
				foreach ($images as &$image) {
					$image = getAttachment($image);
				}
				$data['image'] = $images[0]; //商品主图
			}
			$fields = isset($_POST['fields']) ? $_POST['fields'] : array(); //自定义字段
			$skus = isset($_POST['skus']) ? $_POST['skus'] : array(); //库存信息

			$category = $product_category->getCategory($data['category_id']);
			if (!empty($category['cat_fid'])) {
				$data['category_fid'] = $category['cat_fid'];
			} else {
				$data['category_fid'] = $data['category_id'];
				$data['category_id'] = 0;
			}
			
			// 重新修改是否有库存属性
			if (empty($skus)) {
				$data['has_property'] = 0;
			} else {
				$data['has_property'] = 1;
			}

			//修改商品
			$product->edit(array('store_id' => $store_id, 'product_id' => $product_id), $data);

			$wholesaleProductList = D('Product')->field('product_id, store_id')->where(array('wholesale_product_id' => $product_id))->select();

			if(count($wholesaleProductList) > 0)
			{
				foreach($wholesaleProductList as $productInfo)
				{
					$where['price'] = isset($_POST['price']) ? floatval(trim($_POST['price'])) : 0; //价格
					$where['quantity'] = isset($_POST['quantity']) ? intval(trim($_POST['quantity'])) : 0; //数量
					$product->edit(array('store_id' => $productInfo['store_id'], 'product_id' => $productInfo['product_id']), $where);
				}
			}

			if ($product_detail['supplier_id'] == 0) {
				$system_product_to_property_value->delete(array('product_id' => $product_id));
				foreach ($sys_fields2 as $v) {
					$system_product_to_property_value->add(array('product_id' => $product_id, 'pid' => $v['sys_property_id'], 'vid' => $v['sys_property_value_id']));
				}
			}

			//修改库存信息
			if (!empty($skus)) {
				// 没有添加和删除商品属性
				if (!$_POST['is_edit_sku']) {
					$product_sku->edit($product_id, $skus);
					// 修改已批发商品库存信息
					if(count($wholesaleProductList) > 0) {
						foreach($wholesaleProductList as $productInfo) {
							$wholesaleSkus = D('Product_sku')->field('sku_id')->where(array('product_id'=>$productInfo['product_id']))->select();
	
							$skuId = array();
							foreach($wholesaleSkus as $sku) {
								$skuId[] = $sku['sku_id'];
							}
							$product_sku->wholesaleUpdate($productInfo['product_id'], $skuId, $skus);
						}
					}
				} else {
					/**
					 * 有库存ID的，直接更改相应库存，没有库存ID，需要添加，会删除以在这些库存ID以外的库存
					 * 表product_to_property和表product_to_property_value需要先删除，再添加新的
					 */
					$sku_id_list = array();
					$props_vid = array();
					$props_pid = array();
					$sku_exists_data = array();
					$sku_not_exists_data = array();
					foreach ($skus as $sku) {
						$props_arr = explode(';', $sku['properties']);
						foreach ($props_arr as $prop) {
							$prop_arr = explode(':', $prop);
							$pid = $prop_arr[0];
							$vid = $prop_arr[1];
							$props_vid[$pid . ';' . $vid] = array('pid' => $pid, 'vid' => $vid);
							$props_pid[$pid] = $pid;
						}
						
						if (!empty($sku['sku_id'])) {
							$sku_id_list[] = $sku['sku_id'];
							$sku_exists_data[] = $sku;
						} else {
							$sku_not_exists_data[] = $sku;
						}
					}
					
					if (!empty($sku_exists_data)) {
						$product_sku->edit($product_id, $sku_exists_data);
						// 删除以外的库存
						D('Product_sku')->where(array('product_id' => $product_id, 'sku_id' => array('not in', $sku_id_list)))->delete();
					} else {
						// 删除以前所有的库存
						D('Product_sku')->where(array('product_id' => $product_id))->delete();
					}
					
					if (!empty($sku_not_exists_data)) {
						$product_sku->add($product_id, $sku_not_exists_data);
					}
					
					// 删除以前的相关库存关联表信息
					$where = array('product_id' => $product_id, 'store_id' => $this->store_session['store_id']);
					D('Product_to_property')->where($where)->delete();
					D('Product_to_property_value')->where($where)->delete();
					
					//添加商品属性关联
					$i = 1;
					foreach ($props_pid as $prop_pid) {
						$product_to_property->add(array('store_id' => $this->store_session['store_id'], 'product_id' => $product_id, 'pid' => $prop_pid, 'order_by' => $i));
						$i++;
					}
					//添加商品性值关联
					$i = 1;
					foreach ($props_vid as $prop_vid) {
						$product_to_property_value->add(array('store_id' => $this->store_session['store_id'], 'product_id' => $product_id, 'pid' => $prop_vid['pid'], 'vid' => $prop_vid['vid'], 'order_by' => $i));
						$i++;
					}
				}
			} else {
				// 删除以前的相关库存信息
				$where = array('product_id' => $product_id, 'store_id' => $this->store_session['store_id']);
				D('Product_to_property')->where($where)->delete();
				D('Product_to_property_value')->where($where)->delete();
				// 暂时不删除库存表里的数据
				// D('Product_sku')->where(array('product_id' => $product_id))->delete();
			}

			//修改图片，不删除源文件
			if ($product_image->delete($product_id)) {
				$images = $product_image->add($product_id, $images);
			}
			//修改自定义字段
			if ($product_custom_field->delete($product_id)) {
				$product_custom_field->add($product_id, $fields);
			}

			//商品分组
			$database_product_group = D('Product_group');
			$database_product_to_group = D('Product_to_group');
			$condition_product_to_group['product_id'] = $product_id;
			$tmp_group_ids = $database_product_to_group->field('`group_id`')->where($condition_product_to_group)->select();
			$group_ids = array();
			foreach ($tmp_group_ids as $key => $value) {
				$group_ids[] = $value['group_id'];
			}

			//删除以前的关系
			if ($group_ids) {
				$database_product_to_group->where($condition_product_to_group)->delete();
				$condition_product_group['group_id'] = array('in', $group_ids);
				$database_product_group->where($condition_product_group)->setDec('product_count');
			}

			if (!empty($group_ids_arr)) {
				$data_product_to_group['product_id'] = $product_id;
				foreach ($group_ids_arr as $value) {
					$data_product_to_group['group_id'] = $value;
					if ($database_product_to_group->data($data_product_to_group)->add()) {
						$database_product_group->where(array('group_id' => $value))->setInc('product_count');
					}
				}
			}
			M('Custom_field')->delete_field($this->store_session['store_id'], 'good', $product_id);
			if ($_POST['custom']) {
				$field_result = M('Custom_field')->add_field($this->store_session['store_id'], $_POST['custom'], 'good', $product_id);
			}
			//同步微页面商品
			$fields = D('Custom_field')->where(array('store_id' => $this->store_session['store_id'], 'field_type' => 'goods'))->select();
			if ($fields) {
				foreach ($fields as $field) {
					$products = unserialize($field['content']);
					if (!empty($products) && !empty($products['goods'])) {
						$new_products = array();
						foreach ($products['goods'] as $product) {
							if ($product['id'] == $product_id) {
								$product['title'] = htmlspecialchars($data['name'], ENT_QUOTES);
								$product['price'] = $data['price'];
							}
							$new_products[] = $product;
						}
						$products['goods'] = $new_products;
						$content = serialize($products);
						D('Custom_field')->where(array('field_id' => $field['field_id']))->data(array('content' => $content))->save();
					}
				}
			}
			if (!empty($_POST['referer']) && strtolower($_POST['referer']) == 'is_wholesale') {
				echo json_return(0, url('fx:my_wholesale'));
			}
			if ($preview) {
				echo json_return(0, option('config.wap_site_url') . '/good.php?id=' . $product_id); //预览
			} else {
				echo json_return(0, url('index'));
			}
		}
		$this->display();
	}

    public function goods_load() {

        if (empty($_POST['page']))
            pigcms_tips('非法访问！', 'none');
        if ($_POST['page'] == 'create_content') {
            $cat_list = M('Product_category')->getAllCategory();
            $this->assign('cat_list', $cat_list);
        }
        if ($_POST['page'] == 'edit_content') {
            $this->_edit_content($_GET['id']);
        }
        //商品分组列表
        if ($_POST['page'] == 'category_content') {
            $group_list = M('Product_group')->get_list($this->store_session['store_id']);
            $this->assign('group_list', $group_list);
        }
        //商品分组编辑
        if ($_POST['page'] == 'category_edit') {
            $now_group = M('Product_group')->get_group($this->store_session['store_id'], $_POST['group_id']);
            if (!empty($now_group)) {
                if ($now_group['has_custom']) {
                    $customField = M('Custom_field')->get_field($this->store_session['store_id'], 'good_cat', $now_group['group_id']);
                    $this->assign('customField', json_encode($customField));
                }
                $this->assign('now_group', $now_group);
            } else {
                exit('当前分组不存在！');
            }
        }
        if ($_POST['page'] == 'selling_content') {
            $this->_selling_goods_list();
        }
        if ($_POST['page'] == 'stockout_content') {
            $this->_stockout_goods_list();
        }
        if ($_POST['page'] == 'soldout_content') {
            $this->_soldout_goods_list();
        }
        $this->display($_POST['page']);
    }

    public function get_product_property_list() {
        $list = M('Product_property')->get_list();
        if (empty($list))
            json_return(999, '管理员没有添加规格项目');
        else
            json_return(0, $list);
    }

    public function get_system_property_list() {

        // 加载商品所属类别的属性
        $catid_str = $_GET['catid'];
        if (empty($catid_str))
            return false;


        //判断是否为二级菜单
        $cat_num = count(explode("-", $catid_str));
        if ($cat_num == 1) {
            $catid = $catid_str;
            $cat_arr = M('Product_category')->getCategory($catid);
            if (empty($cat_arr['filter_attr'])) {
                json_return(998, '该商品栏目缺少筛选属性，请联系系统管理员添加该属性后再操作！');
            }

            $filter_attr = explode(',', $cat_arr['filter_attr']);
            $return_arr = M('System_product_property')->get_list_to_value($filter_attr);
        } else if ($cat_num == 2) {
            $catidArr = explode('-', $catid_str);
            $parent_cat = M('Product_category')->getCategory(reset($catidArr));


            $parent_filter_attr = explode(',', $parent_cat['filter_attr']);
            $parent_cat_arr = M('System_product_property')->get_list_to_value($parent_filter_attr);
            if (empty($parent_cat['filter_attr'])) {
                $parent_cat_arr['error_code'] = 998;
            }

            //$parent_cat_arr['cat_name'] = $parent_cat['cat_name'];

            $son_cat = M('Product_category')->getCategory(end($catidArr));

            $son_filter_attr = explode(',', $son_cat['filter_attr']);
            $son_cat_arr = M('System_product_property')->get_list_to_value($son_filter_attr);
            if (empty($son_cat['filter_attr'])) {
                $son_cat_arr['error_code'] = 998;
            }
            //$son_cat_arr['cat_name'] = $son_cat['cat_name'];

            $data = array_merge($parent_cat_arr, $son_cat_arr);
            // $return_arr[] = $parent_cat_arr;
            // $return_arr[] = $son_cat_arr;
        }
        //json_return(0, $return_arr);
        json_return(0, $data);
    }

    //读取该商品的栏目属性 edit专用
//    public function get_system_product_property_list() {
//
//        // 加载商品所属类别的属性
//        $catid_str = $_GET['catid'];
//        $pid = $_GET['pid'];
//        if (empty($catid_str))
//            return false;
//
//        $catid = end(explode("-", $catid_str));
//        $cat_arr = M('Product_category')->getCategory($catid);
//        if (empty($cat_arr['filter_attr'])) {
//            json_return(998, '该商品栏目缺少筛选属性，请联系系统管理员添加该属性后再操作！');
//        }
//        $filter_attr = explode(',', $cat_arr['filter_attr']);
//        $arr1 = M('System_product_property')->get_list_to_value($filter_attr);
//        $arr2 = D('System_product_to_property_value')->where(array('product_id' => $pid))->field('vid')->select();
//        foreach ($arr2 as $k => $v) {
//            $arr3[$k] = $v['vid'];
//        }
//
//        foreach ($arr1 as $k => $v) {
//            foreach ($v['property_value'] as $k1 => $v1) {
//                if (in_array($v1['vid'], $arr3)) {
//                    $v1['selected'] = 'selected';
//                }
//                $v['property_value'][$k1] = $v1;
//            }
//            $return_arr[$k] = $v;
//        }
//
//        json_return(0, $return_arr);
//    }

    public function get_system_product_property_list() {
        // 加载商品所属类别的属性
        $catid = $_GET['catid'];
        $pid = $_GET['pid'];
        if (empty($catid))
            return false;
        $catid_str = end(explode("-", $catid));

        $res = D('pigcms_product_category')->where(array('cat_id' => $catid))->find();
        if ($res['cat_fid']) {
            $pinfo = D('pigcms_product_category')->where(array('cat_id' => $res['cat_fid']))->find();
            $catid_str = $pinfo['cat_id'] . "-" . $catid;
        } else {
            $catid_str = $catid;
        }

        //判断是否为二级菜单
        $cat_num = count(explode("-", $catid_str));
        $return_arr = array();
        if ($cat_num == 1) {
            $catid = $catid_str;
            $cat_arr = M('Product_category')->getCategory($catid);
            if (empty($cat_arr['filter_attr'])) {
                json_return(998, '该商品栏目缺少筛选属性，请联系系统管理员添加该属性后再操作！');
            }

            $filter_attr = explode(',', $cat_arr['filter_attr']);
            $return_arr = M('System_product_property')->get_list_to_value($filter_attr);
        } else if ($cat_num == 2) {
            $catidArr = explode('-', $catid_str);
            $parent_cat = M('Product_category')->getCategory(reset($catidArr));


            $parent_filter_attr = explode(',', $parent_cat['filter_attr']);
            $parent_cat_arr = M('System_product_property')->get_list_to_value($parent_filter_attr);
            if (empty($parent_cat['filter_attr'])) {
                $parent_cat_arr['error_code'] = 998;
            }

            $son_cat = M('Product_category')->getCategory(end($catidArr));

            $son_filter_attr = explode(',', $son_cat['filter_attr']);
            $son_cat_arr = M('System_product_property')->get_list_to_value($son_filter_attr);
            if (empty($son_cat['filter_attr'])) {
                $son_cat_arr['error_code'] = 998;
            }

            $arr1 = array_merge($parent_cat_arr, $son_cat_arr);
        }

        foreach ($arr1 as $k => $v) {
            foreach ($v['property_value'] as $k1 => $v1) {
                $arr2 = D('System_product_to_property_value')->where(array('pid' => $v1['pid'], 'vid' => $v1['vid'], 'product_id' => $pid))->field('vid')->find();
                if ($arr2) {
                    $v1['selected'] = 'selected';
                }
                $v['property_value'][$k1] = $v1;
            }
            $return_arr[$k] = $v;
        }

        json_return(0, $return_arr);
        /*
          // 加载商品所属类别的属性
          $catid_str = $_GET['catid'];
          $pid = $_GET['pid'];
          if (empty($catid_str))
          return false;
          $catid = end(explode("-", $catid_str));

          $cat_arr = $this->searchParent($catid);
          foreach ($cat_arr as $k => $v) {
          if (empty($k)) {
          unset($cat_arr[$k]);
          }
          }

          $filter_attr = array_keys($cat_arr);
          $arr1 = M('System_product_property')->get_list_to_value($filter_attr);
          //$arr2 = D('System_product_to_property_value')->where(array('product_id' => $pid))->field('vid')->select();
          //foreach ($arr2 as $k => $v) {
          // $arr3[$k] = $v['vid'];
          //}
          //$arr1  = array_reverse($arr1);
          foreach ($arr1 as $k => $v) {
          foreach ($v['property_value'] as $k1 => $v1) {
          $arr2 = D('System_product_to_property_value')->where(array('pid'=>$v1['pid'],'vid'=>$v1['vid'],'product_id' => $pid))->field('vid')->find();
          //if (in_array($v1['vid'], $arr3)) {
          if ($arr2) {
          $v1['selected'] = 'selected';
          }
          $v['property_value'][$k1] = $v1;
          }
          if (!($cat_arr[$v['pid'] - 1] == $cat_arr[$v['pid']]) && ($v['pid'] - 1 > 0)) {
          //$v['cat_name'] = $cat_arr[$v['pid']];
          }
          $return_arr[$k] = $v;
          }

          json_return(0, $return_arr);
         */
    }

    public function get_property_value() {
        $database = D('Product_property_value');
        $condition['pid'] = $_POST['pid'];
        $condition['value'] = $_POST['txt'];
        $value = $database->field('`svid`')->where($condition)->find();
        if (empty($value)) {
            $data['pid'] = $_POST['pid'];
            $data['value'] = $_POST['txt'];
            $vid = $database->data($condition)->add();
            if (empty($vid)) {
                json_return(1001, '添加规格属性失败，请重试');
            }
            $value['vid'] = $vid;
        }
        json_return(0, $value['vid']);
    }

    public function get_trade_delivery() {
        $tpl_list = M('Postage_template')->get_all_list($this->store_session['store_id']);
        if (!empty($tpl_list)) {
            return json_return(0, $tpl_list);
        } else {
            return json_return(1002, '没有运费模板');
        }
    }

    public function attachment() {
        $this->display();
    }

    public function attchment_amend_name() {
        $condition_attachment['pigcms_id'] = $_POST['pigcms_id'];
        $condition_attachment['store_id'] = $this->store_session['store_id'];
        $data_attachment['name'] = $_POST['name'];
        if (D('Attachment')->where($condition_attachment)->data($data_attachment)->save()) {
            json_return(0, '保存成功');
        } else {
            json_return(1001, '保存文件名失败');
        }
    }

    public function attchment_delete() {
        $condition_attachment['pigcms_id'] = $_POST['pigcms_id'];
        $condition_attachment['store_id'] = $this->store_session['store_id'];
        $data_attachment['status'] = 0;
        if (D('Attachment')->where($condition_attachment)->data($data_attachment)->save()) {
            json_return(0, '删除成功');
        } else {
            json_return(1002, '删除失败');
        }
    }

    public function attchment_delete_more() {
        if (empty($_POST['pigcms_id']))
            json_return(1003, '请选中一些值');

        $condition_attachment['pigcms_id'] = array('in', $_POST['pigcms_id']);
        $condition_attachment['store_id'] = $this->store_session['store_id'];
        $data_attachment['status'] = 0;
        if (D('Attachment')->where($condition_attachment)->data($data_attachment)->save()) {
            json_return(0, '批量删除成功');
        } else {
            json_return(1004, '批量删除失败');
        }
    }

    /**
     * 商品下架
     */
    public function soldout() {
        if (IS_POST) {
            $product = M('Product');
            $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';

            if (!empty($product_id)) {//批量下架商品
                if(is_array($product_id))
                {
                    foreach ($product_id as $id) {
                        $product_info = D('Product')->field('product_id,is_fx,fx_type,source_product_id,wholesale_product_id')->where(array('product_id' => $id))->find();
                        if ($product->soldout($this->store_session['store_id'], array($id))) {
                            if ($product_info['wholesale_product_id'] == 0) {
                                $this->_soldoutFxProduct($product_info['product_id']);
                            }
                        }
                    }
                }
                else //下架单个商品
                {
                    $product_info = $product->getOne($product_id);
                    if ($product->soldout($this->store_session['store_id'], array($product_id))) {
                        if ($product_info['wholesale_product_id'] = 0) {
                            $this->_soldoutFxProduct($product_info['product_id']);
                        }
                    }
                }

                $this->_sync_wei_page_goods($product_id); //同步微页面商品
                json_return(0, '商品下架成功');
            } else {
                json_return(1001, '商品下架失败');
            }
            exit;
        }

        $this->display();
    }

    /**
     * 商品售完
     */
    public function stockout() {
        $this->display();
    }

    /**
     * 商品删除
     */
    public function remove() {
        $product = M('Product');

        $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();
        //参数兼容数组和数字
        if (!is_array($product_id)) {
            $product_id = array(intval(trim($product_id)));
        }
    }

    /**
     * 参与会员折扣
     */
    public function allow_discount() {
        $product = M('Product');

        $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();
        $discount = isset($_REQUEST['discount']) ? intval(trim($_REQUEST['discount'])) : 0;
        $result = $product->allowDiscount($this->store_session['store_id'], $discount, $product_id);
        if ($result) {
            json_return(0, '商品参与会员折扣成功');
        } else {
            json_return(1001, '商品参与会员折扣失败');
        }
        exit;
    }

    /**
     * 商品分组
     */
    public function edit_group() {
        $product_to_group = M('Product_to_group');

        $data = isset($_POST['data']) ? $_POST['data'] : array();
        if (!empty($data)) {
            foreach ($data as $value) {
                $product_id = $value['product_id'];
                $product_to_group->delete($product_id);
                $group_ids = explode(',', $value['group_id']);
                $flag = false;
                foreach ($group_ids as $group_id) {
                    if ($product_to_group->add(array('product_id' => $product_id, 'group_id' => $group_id))) {
                        $flag = true;
                        D('Product_group')->where(array('group_id' => $group_id))->setInc('product_count', 1); //商品分组的商品数量
                    }
                }
                if ($flag) {
                    D('Product')->where(array('product_id' => $product_id))->data(array('has_category' => 1))->save();
                }
            }
            json_return(0, '修改成功');
        } else {
            json_return(1001, '修改失败');
        }
        return false;
    }

    /**
     * 出售中的商品列表
     */
    private function _selling_goods_list() {
        $product = M('Product');
        $product_group = M('Product_group');
        $product_to_group = M('Product_to_group');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $group_id = isset($_POST['group_id']) ? trim($_POST['group_id']) : '';
		$type = isset($_POST['type']) ? trim($_POST['type']) : '';
		
        $where = array();
        $where['store_id'] = $this->store_session['store_id'];
        $where['quantity'] = array('>', 0);
        $where['soldout'] = 0;
        if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        if ($group_id) {
            $products = $product_to_group->getProducts($group_id);
            $product_ids = array();
            if (!empty($products)) {
                foreach ($products as $item) {
                    $product_ids[] = $item['product_id'];
                }
            }
            $where['product_id'] = array('in', $product_ids);
        }
        $product_total = $product->getSellingTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        
		if($type == 'checkout') {
			if($_POST['check_count']) {
				$return = array('code'=>'0','msg'=>$product_total);
				echo json_encode($return);exit;
			}
			$products = $product->getSelling($where, $order_by_field, $order_by_method);
			$this->_CheckOutToXls($products,"出售中");
			exit;
		} else{
			$products = $product->getSelling($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);
		}
        
        $product_groups = $product_group->get_all_list($this->store_session['store_id']);
        $this->assign('product_groups', $product_groups);
        $this->assign('product_groups_json', json_encode($product_groups));
        $this->assign('page', $page->show());
        $this->assign('products', $products);
    }

    /**
     * 已售罄的商品列表
     */
    private function _stockout_goods_list() {
        $product = M('Product');
        $product_group = M('Product_group');
        $product_to_group = M('Product_to_group');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $group_id = isset($_POST['group_id']) ? trim($_POST['group_id']) : '';
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        
        $where = array();
        $where['store_id'] = $this->store_session['store_id'];
        if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        if ($group_id) {
            $products = $product_to_group->getProducts($group_id);
            $product_ids = array();
            if (!empty($products)) {
                foreach ($products as $item) {
                    $product_ids[] = $item['product_id'];
                }
            }
            $where['product_id'] = array('in', $product_ids);
        }
        $product_total = $product->getStockoutTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        
        if($type == 'checkout') {
        	$products = $product->getStockout($where, $order_by_field, $order_by_method);
        	$this->_CheckOutToXls($products,"已售罄");
        	exit;
        } else{
        	$products = $product->getStockout($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);
        }
        
        $product_groups = $product_group->get_all_list($this->store_session['store_id']);

        $this->assign('product_groups', $product_groups);
        $this->assign('product_groups_json', json_encode($product_groups));
        $this->assign('page', $page->show());
        $this->assign('products', $products);
    }

    /**
     * 仓库中的商品
     */
    private function _soldout_goods_list() {
        $product = M('Product');
        $product_group = M('Product_group');
        $product_to_group = M('Product_to_group');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $group_id = isset($_POST['group_id']) ? trim($_POST['group_id']) : '';
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        
        $where = array();
        $where['store_id'] = $this->store_session['store_id'];
        if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        if ($group_id) {
            $products = $product_to_group->getProducts($group_id);
            $product_ids = array();
            if (!empty($products)) {
                foreach ($products as $item) {
                    $product_ids[] = $item['product_id'];
                }
            }
            $where['product_id'] = array('in', $product_ids);
        }
        $product_total = $product->getSoldoutTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        
        if($type == 'checkout') {
        	$products = $product->getSoldout($where, $order_by_field, $order_by_method);
        	$this->_CheckOutToXls($products,"仓库中");
        	exit;
        } else{
        	$products = $product->getSoldout($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);
        }
        
        $product_groups = $product_group->get_all_list($this->store_session['store_id']);

        $this->assign('product_groups', $product_groups);
        $this->assign('product_groups_json', json_encode($product_groups));
        $this->assign('page', $page->show());
        $this->assign('products', $products);
    }

    /**
     * 修改商品详细
     */
    private function _edit_content($id) {
        $cat_list = M('Product_category')->getAllCategory();
        $this->assign('cat_list', $cat_list);

        $product = M('Product');
        $product_image = M('Product_image');
        $product_sku = M('Product_sku');
        $product_property = M('Product_property');
        $product_property_value = M('Product_property_value');
        $product_to_property = M('Product_to_property');
        $product_to_property_value = M('Product_to_property_value');
        $postage_template = M('Postage_template');
        $product_custom_field = M('Product_custom_field');
        $product_category = M('Product_category');

        $product = $product->get(array('product_id' => $id, 'store_id' => $this->store_session['store_id']));
        $this->assign('product', $product);
        if (empty($product)) {
            exit('当前商品不存在！');
        }

        //商品分类
        if (!empty($product['category_id'])) {
            $category = $product_category->getCategory($product['category_id']);
            if ($category['cat_fid']) {
                $parent_category = $product_category->getCategory($category['cat_fid']);
            } else {
                $parent_category = array();
            }
            if (!empty($parent_category)) {
                $this->assign('category_name', $parent_category['cat_name'] . ' - ' . $category['cat_name']);
            } else {
                $this->assign('category_name', $category['cat_name']);
            }
        } else if (!empty($product['category_fid'])) {
            $parent_category = $product_category->getCategory($product['category_fid']);
            $this->assign('category_name', $parent_category['cat_name']);
            $category = $parent_category;
        }
        if (!empty($product['category_id'])) {
            $this->assign('category_id', $product['category_id']);
        } else {
            $this->assign('category_id', $product['category_fid']);
        }
        $this->assign('category', $category);
        $this->assign('parent_category', $parent_category);

        //商品分组
        if ($product['has_category']) {
            $group_groups = D('')->field('`pg`.`group_id`,`pg`.`group_name`')->table(array('Product_group' => 'pg', 'Product_to_group' => 'ptg'))->where("`pg`.`group_id`=`ptg`.`group_id` AND `product_id`='$id'")->select();
            $this->assign('group_groups', $group_groups);
        }

        if ($product['has_custom']) {
            $customField = M('Custom_field')->get_field($this->store_session['store_id'], 'good', $product['product_id']);
            $this->assign('customField', json_encode($customField));
        }

        //商品图片
        $tmp_images = $product_image->getImages($id);
        $images = array();
        foreach ($tmp_images as $tmp_image) {
            $images[] = array(
                'image_id' => $tmp_image['image_id'],
                'image' => $tmp_image['image'],
            );
        }
        $this->assign('images', $images);

        //运费模板
        if (!empty($product['postage_template_id'])) {
            $postage_template = $postage_template->get_tpl($product['postage_template_id'], $this->store_session['store_id']);
            $this->assign('postage_template', $postage_template);
        }

        //自定义字段
        $fields = $product_custom_field->getFields($id);
        $this->assign('fields', $fields);

		//商品库存信息
		$skus = $product_sku->getSkus($id);
		$sku_data = array();
		if (!empty($skus)) {
			foreach ($skus as $tmp_sku) {
				$tmp = array();
				$tmp['sku_id'] = $tmp_sku['sku_id'];
				$tmp['properties'] = $tmp_sku['properties'];
				$tmp['quantity'] = $tmp_sku['quantity'];
				$tmp['price'] = $tmp_sku['price'];
				$tmp['weight'] = $tmp_sku['weight'];
				$tmp['code'] = $tmp_sku['code'];
				$tmp['sales'] = $tmp_sku['sales'];
				
				$tmp_str = str_replace(array(';', ':'), '_', $tmp['properties']);
				$sku_data[$tmp_str] = $tmp;
			}
		}
		
		$this->assign('sku_data', $sku_data);

		$pids = $product_to_property->getPids($this->store_session['store_id'], $id);
		
		// 商品规格主分类
		$property_list = array();
		if (!empty($pids)) {
			foreach ($pids as $tmp_pid) {
				$name = $product_property->getName($tmp_pid['pid']);
				$value_list = D('')->table('Product_property_value AS ppv')->join('Product_to_property_value AS ptpv ON ppv.vid = ptpv.vid')->where("ptpv.pid = '" . $tmp_pid['pid'] . "' AND ptpv.product_id = '" . $id . "' AND ptpv.store_id = '" . $this->store_session['store_id'] . "'")->field('ppv.*')->order('ptpv.order_by ASC')->select();
				
				foreach ($value_list as &$tmp_v) {
					if (!empty($tmp_v['image'])) {
						$tmp_v['image'] = getAttachmentUrl($tmp_v['image']);
					}
				}
				
				$tmp = array();
				$tmp['pid'] = $tmp_pid['pid'];
				$tmp['name'] = $name;
				$tmp['value_list'] = $value_list;
				
				$property_list[] = $tmp;
			}
		}
		
		$this->assign('property_list', $property_list);
		
		
        if (!empty($pids[0]['pid'])) {
            $pid = $pids[0]['pid'];
            $name = $product_property->getName($pid);
            $vids = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid);
            if (!empty($pids[1]['pid']) && !empty($pids[2]['pid'])) {
                $pid1 = $pids[1]['pid'];
                $name1 = $product_property->getName($pid1);
                $vids1 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid1);
                $pid2 = $pids[2]['pid'];
                $name2 = $product_property->getName($pid2);
                $vids2 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid2);
                $html = '<thead>';
                $html .= '    <tr>';
                $html .= '        <th class="text-center">' . $name . '</th>';
                $html .= '        <th class="text-center">' . $name1 . '</th>';
                $html .= '        <th class="text-center">' . $name2 . '</th>';
                $html .= '        <th class="th-price text-right">价格（元）</th>';
                $html .= '        <th class="th-stock text-right">库存</th>';
                $html .= '        <th class="th-stock text-right">重量(克)</th>';
                $html .= '        <th class="th-code">商家编码</th>';
                if(empty($product['wholesale_product_id']))
                {
                    $html .= '        <th class="text-right">销量</th>';
                }else
                {
                    $html .= '        <th class="text-right">价格区间</th>';
                }
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    foreach ($vids1 as $key1 => $vid1) {
                        $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                        foreach ($vids2 as $key2 => $vid2) {
                            $properties = $pid . ':' . $vid['vid'] . ';' . $pid1 . ':' . $vid1['vid'] . ';' . $pid2 . ':' . $vid2['vid'];
                            $sku_ids = $vid['vid'] . '-' . $vid1['vid'] . '-' . $vid2['vid'];
                            $sku = $product_sku->getSku($id, $properties);
                            $html .= '	<tr class="sku" sku-ids="' . $sku_ids . '" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                            $value2 = $product_property_value->getValue($pid2, $vid2['vid']);
                            if ($key1 == 0 && $key2 == 0) {
                                $html .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
                            }
                            if ($key2 == 0) {
                                $html .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
                            }
                            $html .= '        <td class="text-center">' . $value2 . '</td>';
                            $html .= '        <td class="text-right"><input type="text" name="sku_price" class="js-price input-mini" value="' . $sku['price'] . '" maxlength="10"></td>';
                            if(empty($product['wholesale_product_id']))
                            {
                                $html .= '        <td class="text-right"><input type="text" name="stock_num" class="js-stock-num input-mini" value="' . $sku['quantity'] . '" maxlength="9"></td>';
                                $html .= '        <td class="text-right"><input type="text" name="sku_weight" class="js-sku_weight input-mini" value="' . $sku['weight'] . '" maxlength="9"></td>';
                            }else
                            {
                                $html .= '        <td class="text-right"><input type="text" name="stock_num" disabled class="js-stock-num input-mini" value="' . $sku['quantity'] . '" maxlength="9"></td>';
                                $html .= '        <td class="text-right"><input type="text" name="sku_weight" disabled  class="js-sku_weight input-mini" value="' . $sku['weight'] . '" maxlength="9"></td>';
                            }
                            $html .= '        <td><input type="text" name="code" class="js-code input-small" value="' . $sku['code'] . '"></td>';
                            if(empty($product['wholesale_product_id']))
                            {
                                $html .= '        <td class="text-right">' . $sku['sales'] . '</td>';
                            }else
                            {
                                $html .= '        <td class="text-right">'.$sku['sale_min_price'].'-'.$sku['sale_max_price'].'</td>';
                            }
                            $html .= '    </tr>';
                        }
                    }
                }
               // $html .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-price" href="javascript:;">价格</a>&nbsp;&nbsp;<a class="js-batch-stock" href="javascript:;">库存</a></span><span class="js-batch-form" style="display:none;"><input type="text" class="js-batch-txt input-mini" placeholder=""><a class="js-batch-save" href="javascript:;">保存</a> <a class="js-batch-cancel" href="javascript:;">取消</a><p class="help-desc"></p></span></div></td></tr></tfoot>';
            } else if (!empty($pids[1]['pid'])) {
                $pid1 = $pids[1]['pid'];
                $name1 = $product_property->getName($pid1);
                $vids1 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid1);
                $html = '<thead>';
                $html .= '    <tr>';
                $html .= '        <th class="text-center">' . $name . '</th>';
                $html .= '        <th class="text-center">' . $name1 . '</th>';
                $html .= '        <th class="th-price text-right">价格（元）</th>';
                $html .= '        <th class="th-stock text-right">库存</th>';
                $html .= '        <th class="th-price text-right">重量(克)</th>';
                $html .= '        <th class="th-code">商家编码</th>';
                if(empty($product['wholesale_product_id']))
                {
                    $html .= '        <th class="text-right">销量</th>';
                }else
                {
                    $html .= '        <th class="text-right">价格区间</th>';
                }
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    foreach ($vids1 as $key1 => $vid1) {
                        $properties = $pid . ':' . $vid['vid'] . ';' . $pid1 . ':' . $vid1['vid'];
                        $sku_ids = $vid['vid'] . '-' . $vid1['vid'];
                        $sku = $product_sku->getSku($id, $properties);
                        $html .= '    <tr class="sku" sku-ids="' . $sku_ids . '" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                        $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                        if ($key1 == 0) {
                            $html .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
                        }
                        $html .= '        <td class="text-center">' . $value1 . '</td>';
                        $html .= '        <td class="text-right"><input type="text" name="sku_price" class="js-price input-mini" value="' . $sku['price'] . '" maxlength="10"></td>';
                        if(empty($product['wholesale_product_id']))
                        {
                            $html .= '        <td class="text-right"><input type="text" name="stock_num" class="js-stock-num input-mini" value="' . $sku['quantity'] . '" maxlength="9"></td>';
                            $html .= '        <td class="text-right"><input type="text" name="sku_weight" class="js-sku_weight input-mini" value="' . $sku['weight'] . '" maxlength="9"></td>';
                        }else
                        {
                            $html .= '        <td class="text-right"><input type="text" name="stock_num" disabled class="js-stock-num input-mini" value="' . $sku['quantity'] . '" maxlength="9"></td>';
                            $html .= '        <td class="text-right"><input type="text" name="sku_weight" disabled class="js-sku_weight input-mini" value="' . $sku['weight'] . '" maxlength="9"></td>';
                        }
                        $html .= '        <td><input type="text" name="code" class="js-code input-small" value="' . $sku['code'] . '"></td>';
                        if(empty($product['wholesale_product_id']))
                        {
                            $html .= '        <td class="text-right">' . $sku['sales'] . '</td>';
                        }else
                        {
                            $html .= '        <td class="text-right">'.$sku['sale_min_price'].'-'.$sku['sale_max_price'].'</td>';
                        }
                        $html .= '    </tr>';
                    }
                }
            } else {
                $html = '<thead>';
                $html .= '    <tr>';
                $html .= '        <th class="text-center">' . $name . '</th>';
                $html .= '        <th class="th-price text-right">价格（元）</th>';
                $html .= '        <th class="th-stock text-right">库存</th>';
                $html .= '        <th class="th-stock text-right">重量(克)</th>';
                $html .= '        <th class="th-code">商家编码</th>';
                if(empty($product['wholesale_product_id']))
                {
                    $html .= '        <th class="text-right">销量</th>';
                }else
                {
                    $html .= '        <th class="text-right">价格区间</th>';
                }
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    $properties = $pid . ':' . $vid['vid'];
                    $sku = $product_sku->getSku($id, $properties);
                    $html .= '    <tr class="sku" sku-ids="' . $vid['vid'] . '" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    $html .= '        <td class="text-center">' . $value . '</td>';
                    $html .= '        <td text-right><input type="text" name="sku_price" class="js-price input-mini" value="' . $sku['price'] . '" maxlength="10"></td>';
                    if(empty($product['wholesale_product_id']))
                    {
                        $html .= '        <td class="text-right"><input type="text" name="stock_num" class="js-stock-num input-mini" value="' . $sku['quantity'] . '" maxlength="9"></td>';
                        $html .= '        <td class="text-right"><input type="text" name="sku_weight" class="js-sku_weight input-mini" value="' . $sku['weight'] . '" maxlength="9"></td>';
                    }else
                    {
                        $html .= '        <td class="text-right"><input type="text" name="stock_num" disabled class="js-stock-num input-mini" value="' . $sku['quantity'] . '" maxlength="9"></td>';
                        $html .= '        <td class="text-right"><input type="text" name="sku_weight" disabled class="js-sku_weight input-mini" value="' . $sku['weight'] . '" maxlength="9"></td>';
                    }
                    $html .= '        <td><input type="text" name="code" class="js-code input-small" value="' . $sku['code'] . '"></td>';
                    if(empty($product['wholesale_product_id']))
                    {
                        $html .= '        <td class="text-right">' . $sku['sales'] . '</td>';
                    }else
                    {
                        $html .= '        <td class="text-right">'.$sku['sale_min_price'].'-'.$sku['sale_max_price'].'</td>';
                    }
                    $html .= '    </tr>';
                }
            }
            if(empty($product['wholesale_product_id']))
            {
                $html .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-price" href="javascript:;">价格</a>&nbsp;&nbsp;<a class="js-batch-stock" href="javascript:;">库存</a>&nbsp;&nbsp;<a class="js-batch-weight" href="javascript:;">重量</a></span><span class="js-batch-form" style="display:none;"><input type="text" class="js-batch-txt input-mini" placeholder=""> <a class="js-batch-save" href="javascript:;">保存</a> <a class="js-batch-cancel" href="javascript:;">取消</a><p class="help-desc"></p></span></div></td></tr></tfoot>';
            }else
            {
                $html .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-price" href="javascript:;">价格</a>&nbsp;&nbsp;</span><span class="js-batch-form" style="display:none;"><input type="text" class="js-batch-txt input-mini" placeholder=""> <a class="js-batch-save" href="javascript:;">保存</a> <a class="js-batch-cancel" href="javascript:;">取消</a><p class="help-desc"></p></span></div></td></tr></tfoot>';
            }
        }

        $this->assign('sku_content', $html);

        //获取商品规格
        $spec_list = $product_to_property_value->get_product_property_value($product['product_id']);
        if ($spec_list) {
            $system_product_property_arr = array();
            foreach ($spec_list as $k => $v) {
                $system_product_property_arr[] = $k;
            }

            $condition['pid'] = array('in', $system_product_property_arr);
            $product_property_list = D('Product_property')->where($condition)->select();
            $new_product_property_list = array();
            foreach ($product_property_list as $v) {
                $new_product_property_list[$v['pid']] = $v;
            }
            unset($product_property_list);
            $this->assign('product_property_list', $new_product_property_list);
            $this->assign('spec_list', $spec_list);
        }
    }

    //保存扫码活动
    public function save_qrcode_activity() {
        $activity = M('Product_qrcode_activity');

        $data = array();
        $data['buy_type'] = isset($_POST['buy_type']) ? intval($_POST['buy_type']) : 0;
        $data['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
        $data['discount'] = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
        $data['price'] = isset($_POST['price']) ? floatval($_POST['price']) : 0;
        if (isset($_POST['activity_id'])) {
            $where = array();
            $where['store_id'] = $this->store_session['store_id'];
            $where['product_id'] = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
            $where['pigcms_id'] = isset($_POST['activity_id']) ? intval(trim($_POST['activity_id'])) : 0;
            if ($activity->save($where, $data)) {
                $activity = $activity->getActivity($where['store_id'], $where['product_id'], $where['pigcms_id']);
                echo json_encode(array('code' => 1, 'type' => 'edit', 'msg' => '保存成功', 'data' => $activity));
            } else {
                echo json_encode(array('code' => 0, 'type' => 'edit', 'msg' => '保存失败', 'data' => array()));
            }
        } else {
            $data['store_id'] = $this->store_session['store_id'];
            $data['product_id'] = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
            if ($activity_id = $activity->add($data)) {
                $activity = $activity->getActivity($data['store_id'], $data['product_id'], $activity_id);
                echo json_encode(array('code' => 1, 'type' => 'add', 'msg' => '保存成功', 'data' => $activity));
            } else {
                echo json_encode(array('code' => 0, 'type' => 'add', 'msg' => '保存失败', 'data' => array()));
            }
        }
        exit;
    }

    //获取扫码活动
    public function get_qrcode_activity() {
        $activity = M('Product_qrcode_activity');

        $product_id = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;

        $activities = $activity->getActivities($this->store_session['store_id'], $product_id);
        if (!empty($activities)) {
            echo json_encode($activities);
        } else {
            echo false;
        }
        exit;
    }

    //删除扫码活动
    public function del_qrcode_activity() {
        $activity = M('Product_qrcode_activity');

        $product_id = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
        $activity_id = isset($_POST['activity_id']) ? intval(trim($_POST['activity_id'])) : 0;

        if ($activity->delete($this->store_session['store_id'], $product_id, $activity_id)) {
            json_return(0, '删除成功');
        } else {
            json_return(1001, '删除失败');
        }
    }

    //删除商品
    public function del_product() {
        $product = M('Product');

        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
        if (!empty($product_id) && !is_array($product_id)) {
            $product_id = array($product_id);
        }
        if (!empty($product_id)) {
            foreach ($product_id as $id) {
                $product_info = D('Product')->field('product_id,is_wholesale,is_fx,fx_type,source_product_id,original_product_id')->where(array('product_id' => $id))->find();
                if ($product->delete($this->store_session['store_id'], $product_id)) {
                    $database_product_group = D('Product_group');
                    $database_product_to_group = D('Product_to_group');
                    $database_product_to_group_info = $database_product_to_group->where(array('product_id' => $id))->find();
                    $database_product_group->where(array('group_id' => $database_product_to_group_info['group_id']))->setDec('product_count');

                    if (!empty($product_info['is_wholesale'])) {
                        $this->_delFxProduct($product_info['product_id']);
                    }
                }
            }
            $this->_sync_wei_page_goods($product_id); //同步微页面商品
            json_return(0, '删除成功');
        } else {
            json_return(1001, '删除失败');
        }
    }

    //设置商品排序
    public function set_sort() {
        if (IS_POST) {
            $id = isset($_POST['id']) ? intval(trim($_POST['id'])) : 0;
            $sort = isset($_POST['sort']) ? intval(trim($_POST['sort'])) : 0;
            if (D('Product')->where(array('product_id' => $id, 'store_id' => $this->store_session['store_id']))->data(array('sort' => $sort))->save()) {
                json_return(0, '排序成功');
            } else {
                json_return(1001, '排序失败');
            }
        }
    }

    //同步微页面商品
    private function _sync_wei_page_goods($product_id, $store_id = '') {
        $product_id = !is_array($product_id) ? array($product_id) : $product_id;
        //删除微页面的商品
        if (empty($store_id)) {
            $store_id = $this->store_session['store_id'];
        }
        $fields = D('Custom_field')->where(array('store_id' => $store_id, 'field_type' => 'goods'))->select();
        if ($fields) {
            foreach ($fields as $field) {
                $products = unserialize($field['content']);
                if (!empty($products) && !empty($products['goods'])) {
                    $new_products = array();
                    foreach ($products['goods'] as $product) {
                        if (!in_array($product['id'], $product_id)) {
                            $new_products[] = $product;
                        }
                    }
                    $products['goods'] = $new_products;
                    $content = serialize($products);
                    D('Custom_field')->where(array('field_id' => $field['field_id']))->data(array('content' => $content))->save();
                }
            }
        }
    }

    //删除商品
    private function _delFxProduct($product_id) {
        $products = D('Product')->where(array('wholesale_product_id' => $product_id))->select();

        if (!empty($products)) {
            foreach ($products as $product) {
                D('Product')->where(array('product_id' => $product['product_id']))->data(array('status' => 2))->save();
                $this->_sync_wei_page_goods($product['product_id'], $product['store_id']);
                $this->_delFxProduct($product['product_id']);
            }
        }
    }

    //下架商品
    private function _soldoutFxProduct($product_id) {
        $products = D('Product')->where(array('wholesale_product_id' => $product_id))->select();
        if (!empty($products)) {
            foreach ($products as $product){
                D('Product')->where(array('product_id' => $product['product_id']))->data(array('status' => 0))->save();
                $this->_sync_wei_page_goods($product['product_id'], $product['store_id']);
            }
        }
    }

    //递归处理查找父元素
    private function searchParent($cat_id) {
        static $arr = array();
        $res = D('pigcms_product_category')->where(array('cat_id' => $cat_id))->find();

        if (!empty($res['filter_attr'])) {
            $arr[$res['filter_attr']] = $res['cat_name'];
        }

        if (stripos($res['filter_attr'], ',') !== false) {
            foreach (explode(',', $res['filter_attr']) as $v) {
                $arr[$v] = $res['cat_name'];
            }
            unset($arr[$res['filter_attr']]);
        }

        if ($res['cat_fid'] != 0) {
            $this->searchParent($res['cat_fid']);
        }

        return $arr;
    }

    // 属性值上传图片
    public function property_value_img() {
        $vid = $_GET['vid'];
        $image = $_GET['image'];

        if (empty($vid)) {
            json_return(1001, '缺少参数');
        }

        if (empty($image)) {
            json_return(1001, '请上传图片');
        }

        $image = getAttachment($image);

        $product_property_value = D('Product_property_value')->where(array('vid' => $vid))->find();
        if (empty($product_property_value)) {
            json_return(1001, '未找到相应的属性值');
        }

        D('Product_property_value')->where(array('vid' => $vid))->data(array('image' => $image))->save();

        json_return(0, '上传完成');
    }

    /*
      if (IS_POST) {
      $product = M('Product');
      $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();

      if (!empty($product_id)) {
      foreach ($product_id as $id) {
      $product_info = D('Product')->field('product_id,is_fx,fx_type,source_product_id,original_product_id')->where(array('product_id' => $id))->find();
      if ($product->soldout($this->store_session['store_id'], array($id))) {
      if (!empty($product_info['is_fx']) && empty($product_info['original_product_id'])) { //供货商
      $this->_soldoutFxProduct($product_info['product_id']);
      } else if (!empty($product_info['source_product_id'])) { //分销商
      $this->_soldoutFxProduct($product_info['product_id']);
      }
      }
      }
      $this->_sync_wei_page_goods($product_id); //同步微页面商品
      json_return(0, '商品下架成功');
      } else {
      json_return(1001, '商品下架失败');
      }
      exit;
      }

     */

    public function comments_load() {
        if (empty($_POST['page']))
            pigcms_tips('非法访问！', 'none');
        if ($_POST['page'] == 'create_content') {
            $cat_list = M('Product_category')->getAllCategory();
            $this->assign('cat_list', $cat_list);
        }
        if ($_POST['page'] == 'edit_content') {
            $this->_edit_content($_GET['id']);
        }
        //商品分组列表
        if ($_POST['page'] == 'category_content') {
            $group_list = M('Product_group')->get_list($this->store_session['store_id']);
            $this->assign('group_list', $group_list);
        }
        //商品分组编辑
        if ($_POST['page'] == 'category_edit') {
            $now_group = M('Product_group')->get_group($this->store_session['store_id'], $_POST['group_id']);
            if (!empty($now_group)) {
                if ($now_group['has_custom']) {
                    $customField = M('Custom_field')->get_field($this->store_session['store_id'], 'good_cat', $now_group['group_id']);
                    $this->assign('customField', json_encode($customField));
                }
                $this->assign('now_group', $now_group);
            } else {
                exit('当前分组不存在！');
            }
        }


        if ($_POST['page'] == 'comment_goods_content') {
            $this->_comment_goods_list();
        }
        if ($_POST['page'] == 'comment_store_content') {
            $this->_comment_store_list();
        }
        $this->display($_POST['page']);
    }

    //商品评论
    public function product_comment() {
        if (IS_POST) {
            $product = M('Product');
            $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();

            if (!empty($product_id)) {
                foreach ($product_id as $id) {
                    $product_info = D('Product')->field('product_id,is_fx,fx_type,source_product_id,original_product_id')->where(array('product_id' => $id))->find();
                    if ($product->soldout($this->store_session['store_id'], array($id))) {
                        if (!empty($product_info['is_fx']) && empty($product_info['original_product_id'])) { //供货商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        } else if (!empty($product_info['source_product_id'])) { //分销商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        }
                    }
                }
                $this->_sync_wei_page_goods($product_id); //同步微页面商品
                json_return(0, '商品下架成功');
            } else {
                json_return(1001, '商品下架失败');
            }
            exit;
        }


        $this->display();
    }

    //店铺评论
    public function store_comment() {
        if (IS_POST) {
            $product = M('Product');
            $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();

            if (!empty($product_id)) {
                foreach ($product_id as $id) {
                    $product_info = D('Product')->field('product_id,is_fx,fx_type,source_product_id,original_product_id')->where(array('product_id' => $id))->find();
                    if ($product->soldout($this->store_session['store_id'], array($id))) {
                        if (!empty($product_info['is_fx']) && empty($product_info['original_product_id'])) { //供货商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        } else if (!empty($product_info['source_product_id'])) { //分销商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        }
                    }
                }
                $this->_sync_wei_page_goods($product_id); //同步微页面商品
                json_return(0, '商品下架成功');
            } else {
                json_return(1001, '商品下架失败');
            }
            exit;
        }


        $this->display();
    }

    /**
     * 已评论的商品列表
     */
    private function _comment_goods_list() {
        $where['store_id'] = $this->store_session['store_id'];
        $where['delete_flg'] = 0;
        $where['type'] = 'PRODUCT';
        $comment_model = M('Comment');
        $count = $comment_model->getCount($where);

        if ($count > 0) {
            $limit = 15;
            import('source.class.user_page');
            $page = new Page($count, $limit);

            $comment_list = $comment_model->getList($where, 'id desc', $page->listRows, $page->firstRow, true);

            foreach ($comment_list['comment_list'] as $k => $v) {
                $product_new_arr[] = $v['relation_id'];
            }

            $in_array = $product_new_arr;

            $produdcts = D('Product')->where(array('product_id' => array('in', $in_array)))->select();
            if (is_array($produdcts)) {
                foreach ($produdcts as $k => $v) {
                    $product_arr[$v['product_id']] = $v;
                }
            }
            $this->assign('page', $page->show());
            $this->assign('comments', $comment_list);
            $this->assign('product_arr', $product_arr);
        }
    }

    /**
     * 已评论的店铺列表
     */
    private function _comment_store_list() {
        $where['store_id'] = $this->store_session['store_id'];
        $where['delete_flg'] = 0;
        $where['type'] = 'STORE';
        $comment_model = M('Comment');
        $count = $comment_model->getCount($where);

        if ($count > 0) {
            $limit = 15;
            import('source.class.user_page');
            $page = new Page($count, $limit);

            $comment_list = $comment_model->getList($where, 'id desc', $page->listRows, $page->firstRow, true);

            foreach ($comment_list['comment_list'] as $k => $v) {
                $product_new_arr[] = $v['relation_id'];
            }

            $in_array = $product_new_arr;

            $stores = D('Store')->where(array('Store_id' => array('in', $in_array)))->select();
            if (is_array($stores)) {
                foreach ($stores as $k => $v) {
                    $store_arr[$v['store_id']] = $v;
                }
            }
            $this->assign('page', $page->show());
            $this->assign('comments', $comment_list);
            $this->assign('store_arr', $store_arr);
        }
    }

    //删除评论
    public function del_comment() {
        $comment = M('Comment');
        $comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : 0;
        if (!empty($comment_id) && !is_array($comment_id)) {
            $comment_id = array($comment_id);
        }
        if (!empty($comment_id)) {

            foreach ($comment_id as $id) {
                $comment->delete(array('id' => $id));
            }
            json_return(0, '删除成功');
        } else {
            json_return(1001, '删除失败');
        }
    }

    //修改审核状态
    public function set_comment_status() {
        $comment = M('Comment');
        $comment_id = isset($_POST['id']) ? $_POST['id'] : 0;
        $status = $_POST['status'];


        if (empty($status) || empty($comment_id)) {
            json_return(1001, '修改失败');
        }

        $data = array('status' => $status);
        $where = array(
            'id' => $comment_id,
            'store_id' => $this->store_session['store_id']
        );

        $comment->save($data, $where);

        json_return(0, '修改成功');
    }

    public function get_propertyvaluebyid() {
        echo $_POST['propertypid'];
    }
    

    //导出商品查询
    public function checkoutProduct() {
    
    	$product = M('Product');
    	$product_group = M('Product_group');
    	$product_to_group = M('Product_to_group');
    	
    	$order_by_field = isset($_REQUEST['orderbyfield']) ? $_REQUEST['orderbyfield'] : '';
    	$order_by_method = isset($_REQUEST['orderbymethod']) ? $_REQUEST['orderbymethod'] : '';
    	$keyword = isset($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
    	$group_id = isset($_REQUEST['group_id']) ? trim($_REQUEST['group_id']) : '';
    	$type = isset($_REQUEST['type']) ? trim($_REQUEST['type']) : '';
		$where = array();       
	   if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }  	
		
    	switch($type) {
    		///出售中
    		case 'selling':
	    			
	    			$where['store_id'] = $this->store_session['store_id'];
	    			$where['quantity'] = array('>', 0);
	    			$where['soldout'] = 0;
	    			if ($group_id) {
	    				$products = $product_to_group->getProducts($group_id);
	    				$product_ids = array();
	    				if (!empty($products)) {
	    					foreach ($products as $item) {
	    						$product_ids[] = $item['product_id'];
	    					}
	    				}
	    				if($product_ids) $where['product_id'] = array('in', $product_ids);
	    			}
	    			$product_total = $product->getSellingTotal($where);


					
	    			if($_POST['check_count']) {
	    				json_return(0, $product_total);
	    			}

	    			$products = $product->getSelling($where, $order_by_field, $order_by_method);

	    			$this->_CheckOutToXls($products,"出售中");
	    			exit;
	    					
    			break;
    		
    		//仓库中	
    		case 'soldout':

	    			$where = array();
	    			$where['store_id'] = $this->store_session['store_id'];
	    			if ($keyword) {
	    				$where['name'] = array('like', '%' . $keyword . '%');
	    			}
	    			if ($group_id) {
	    				$products = $product_to_group->getProducts($group_id);
	    				$product_ids = array();
	    				if (!empty($products)) {
	    					foreach ($products as $item) {
	    						$product_ids[] = $item['product_id'];
	    					}
	    				}
	    				if($product_ids) $where['product_id'] = array('in', $product_ids);
	    			}
	    			$product_total = $product->getSoldoutTotal($where);
	    			
	    			if($_POST['check_count']) {
	    				json_return(0, $product_total);
	    			}

	    			$products = $product->getSoldout($where, $order_by_field, $order_by_method);
	    			$this->_CheckOutToXls($products,"仓库中");
	    			exit;
	    			
    			break;
    		
    		//已售罄	
    		case 'stockout':

    				$where = array();
    				$where['store_id'] = $this->store_session['store_id'];
    				if ($keyword) {
    					$where['name'] = array('like', '%' . $keyword . '%');
    				}
    				if ($group_id) {
    					$products = $product_to_group->getProducts($group_id);
    					$product_ids = array();
    					if (!empty($products)) {
    						foreach ($products as $item) {
    							$product_ids[] = $item['product_id'];
    						}
    					}
    					if($prodcuts_ids) $where['product_id'] = array('in', $product_ids);
    				}
    				$product_total = $product->getStockoutTotal($where);
    				
    				if($_POST['check_count']) {
    					json_return(0, $product_total);
    				}
    				
    				
    				$products = $product->getStockout($where, $order_by_field, $order_by_method);
					
    				$this->_CheckOutToXls($products,"已售罄");
    				exit;
    				
    			break;
    			
    		//导出全部	
    		case 'all':	
	    			$where = array();
	    			$where['store_id'] = $this->store_session['store_id'];
	    			//$where['quantity'] = array('>', 0);
	    			$where['soldout'] = 0;
	    			
	    			$where['status'] = array('in', array(0,1));
	    			
	    			$product_total = $product->getProductTotal($where);
	    			 
	    			
	    			if($_POST['check_count']) {
	    				json_return(0, $product_total);
	    			}
	    			$products = $product->getProduct($where, $order_by_field, $order_by_method);
	    			$this->_CheckOutToXls($products,"全部");
	    			exit;
    			    		
    			break;
    	}
    	
    }
	
    //doing导出指定类别的商品
    private function _CheckOutToXls($products, $typename) {
    	
    	
    	include 'source/class/execl.class.php';
    	$execl = new execl();

    	$filename = date($typename."的商品导出_YmdHis",time()).'.xls';
    	header ( 'Content-Type: application/vnd.ms-excel' );
    	header ( "Content-Disposition: attachment;filename=$filename" );
    	header ( 'Cache-Type: charset=gb2312');
    	echo "<style>table td{border:1px solid #ccc;}</style>";
    	echo "<table>";
    	//dump($user_arr);
    	echo '	<tr>';
    	echo ' 		<th><b> 商品id </b></th>';
    	echo ' 		<th><b> 商品名称 </b></th>';
    	echo ' 		<th><b> 商品价格 </b></th>';
    	echo ' 		<th><b> 商品原价 </b></th>';
    	echo ' 		<th><b> 邮费 </b></th>';
    	echo ' 		<th><b> 商品来源 </b></th>';
    	echo ' 		<th><b> 商品访问量(pv) </b></th>';
    	echo ' 		<th><b> 库存 </b></th>';
    	echo ' 		<th><b> 收藏数 </b></th>';
    	echo ' 		<th><b> 关注数 </b></th>';
    	echo ' 		<th><b> 是否开启批发 </b></th>';
    	echo ' 		<th><b> 批发价格 </b></th>';
    	echo ' 		<th><b> 是否开启分销 </b></th>';
    	echo ' 		<th><b> 分销商数量(被分销次数)</b></th>';
    	echo ' 		<th><b> 分销商品数量</b></th>';
    	
    	echo ' 		<th><b> 一级分销商商品成本价格 </b></th>';
    	echo ' 		<th><b> 一级分销商商品价格 </b></th>';
    	echo ' 		<th><b> 二级分销商商品成本价格 </b></th>';
    	echo ' 		<th><b> 二级分销商商品价格 </b></th>';
    	echo ' 		<th><b> 三级分销商商品成本价格 </b></th>';
    	echo ' 		<th><b> 三级分销商商品价格</b></th>';
    	
    	echo ' 		<th><b> 商品状态 </b></th>';
    	echo ' 		<th><b> 总销量 </b></th>';
    	echo ' 		<th><b> 创建时间 </b></th>';
    	echo ' 		<th><b> 最后修改时间 </b></th>';
    	
    	echo '	</tr>';

 
    	foreach ($products as $k => $v) {
    		 if (!empty($v['wholesale_product_id'])) { 
    			$source = "批发";
    		  } else if (!empty($v['supplier_id'])) { 
    		 	$source = " 分销";
    		  } else{
    		  	$source = "";
    		  }	
    		if($v['status'] == '1'){ if($v['quantity']>'0') {$status="上架中";}else{$status='已售罄';}}else{$status="仓库中";}
    		if($v['is_fx'] == '1'){$is_open_fx="已开启分销";}else{$is_open_fx="未开启分销";}
    		if($v['is_wholesale'] == '1'){$is_open_pifa="已开启批发";}else{$is_open_pifa="未开启批发";}
    		echo '	<tr>';
    		echo ' 		<td align="center">' . $v['product_id'] . '</td>';
    		echo ' 		<td align="center">' . $v['name'] . '</td>';
    		echo ' 		<td align="center">' . $v['price'] . '</td>';
    		echo ' 		<td align="center">' . $v['original_price'] . '</td>';
    		echo ' 		<td align="center">' . $v['postage'] . '</td>';
    		echo ' 		<td align="center">' . $source. '</td>';
    		echo ' 		<td align="center">' . $v['uv'] . '</td>';
    		echo ' 		<td align="center">' . $v['pv'] . '</td>';
    		echo ' 		<td align="center">' . $v['quantity'] . '</td>';	
    		echo ' 		<td align="center">' . $v['collect'] . '</td>';
    		echo ' 		<td align="center">' . $v['attention_num'] . '</td>';
    		echo ' 		<td align="center">' . $is_open_pifa . '</td>';
    		echo ' 		<td align="center">' . $v['wholesale_price'] . '</td>';
    		echo ' 		<td align="center">' . $is_open_fx . '</td>';
    		echo ' 		<td align="center">' . $v['drp_seller_qty'] . '</td>';
    		echo ' 		<td align="center">' . $v['drp_sale_qty'] . '</td>';
    		echo ' 		<td align="center">' . $v['drp_level_1_cost_price'] . '</td>';
    		echo ' 		<td align="center">' . $v['drp_level_1_price'] . '</td>';
    		echo ' 		<td align="center">' . $v['drp_level_2_cost_price'] . '</td>';
    		echo ' 		<td align="center">' . $v['drp_level_2_price'] . '</td>';
    		echo ' 		<td align="center">' . $v['drp_level_3_cost_price'] . '</td>';
    		echo ' 		<td align="center">' . $v['drp_level_3_price'] . '</td>';	
    		echo ' 		<td align="center">' . $status . '</td>';
    		echo ' 		<td align="center">' . $v['sales'] . '</td>';
    		echo ' 		<td align="center">' .  date('Y-m-d', $v['date_added']) . '</td>';
    		echo ' 		<td align="center">' .  date('Y-m-d', $v['last_edit_time']) . '</td>';
    		
    		echo '	</tr>';
    	}
    	echo '</table>';
    	
    	}
 

    // 门店load
    public function stock_load() {
        if (empty($_POST['page']))
            pigcms_tips('非法访问！', 'none');

        if ($_POST['page'] == 'stock_content') {
            $this->_stock_goods_list();
        }
        $this->display($_POST['page']);
    }

    // 门店库存
    private function _stock_goods_list() {
        $product = M('Product');
        $product_sku = M('Product_sku');
        $product_group = M('Product_group');
        $product_to_group = M('Product_to_group');
        $store_physical_quantity = M('Store_physical_quantity');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';

        $item_store_id = $this->user_session['item_store_id'];

        $where = array();
        $where['store_id'] = $this->store_session['store_id'];
        $where['quantity'] = array('>', 0);
        $where['soldout'] = 0;
        if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        // 门店有库存
        if ($item_store_id) {
            $products = $store_physical_quantity->getProducts(array('physical_id' => $item_store_id));
            $product_ids = array();
            if (!empty($products)) {
                foreach ($products as $item) {
                    $product_ids[] = $item['product_id'];
                }
            }
            $where['product_id'] = array('in', $product_ids);
        }

        $product_total = $product->getSellingTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        $products = $product->getSelling($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);
        $product_groups = $product_group->get_all_list($this->store_session['store_id']);

        //库存
        foreach ($products as $key => $val) {

            if (empty($val['has_property'])) {
                $products[$key]['sku'] = array();

                //单产品门店库存
                $stock_where = array(
                    'product_id' => $val['product_id'],
                    'physical_id' => $item_store_id,
                    'sku_id' => 0
                );
                $stock = D('Store_physical_quantity')->where($stock_where)->find();
                $products[$key]['_physical_quantity'] = !empty($stock) ? $stock['quantity'] : 0;

                continue;
            }

            $val_sku = $product_sku->getSkus($val['product_id']);

            foreach ($val_sku as $k => $v) {

                //判断是否有库存
                $stock_where = array(
                    'product_id' => $v['product_id'],
                    'physical_id' => $item_store_id,
                    'sku_id' => $v['sku_id'],
                        // 'quantity'=>array('gt', 0)
                );
                $stock = D('Store_physical_quantity')->where($stock_where)->find();
                if (!$stock) {
                    unset($val_sku[$k]);
                    continue;
                } else {
                    $val_sku[$k]['_physical_quantity'] = !empty($stock) ? $stock['quantity'] : 0;
                }

                $tmpPropertiesArr = explode(';', $v['properties']);
                $properties = $propertiesValue = $productProperties = array();
                foreach ($tmpPropertiesArr as $v) {
                    $tmpPro = explode(':', $v);
                    $properties[] = $tmpPro[0];
                    $propertiesValue[] = $tmpPro[1];
                }
                if (count($properties) == 1) {
                    $findPropertiesArr = D('Product_property')->field('`pid`,`name`')->where(array('pid' => $properties[0]))->select();
                    $findPropertiesValueArr = D('Product_property_value')->field('`vid`,`value`,`image`')->where(array('vid' => $propertiesValue[0]))->select();
                } else {
                    $findPropertiesArr = D('Product_property')->field('`pid`,`name`')->where(array('pid' => array('in', $properties)))->select();
                    $findPropertiesValueArr = D('Product_property_value')->field('`vid`,`value`,`image`')->where(array('vid' => array('in', $propertiesValue)))->select();
                }
                foreach ($findPropertiesArr as $v) {
                    $propertiesArr[$v['pid']] = $v['name'];
                }
                foreach ($findPropertiesValueArr as $v) {
                    $propertiesValueArr[$v['vid']] = $v['value'];
                }
                foreach ($properties as $kk => $v) {
                    $productProperties[] = array('pid' => $v, 'name' => $propertiesArr[$v], 'vid' => $propertiesValue[$kk], 'value' => $propertiesValueArr[$propertiesValue[$kk]], 'image' => getAttachmentUrl($findPropertiesValueArr[$kk]['image']));
                }

                $val_sku[$k]['_property'] = $productProperties;
            }

            $products[$key]['sku'] = $val_sku;
        }

        $this->assign('product_groups', $product_groups);
        $this->assign('product_groups_json', json_encode($product_groups));
        $this->assign('page', $page->show());
        $this->assign('products', $products);
    }

    // 库存列表[门店管理员]
    public function physical_stock() {
        $this->display();
    }

}

?>