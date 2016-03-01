<?php
class fx_controller extends base_controller
{
	public function __construct()
	{
		parent::__construct();

        if (!$this->enabled_drp()) {
            redirect('store:index');
        }

        //是否允许设置商品再次分销
        if ((!empty($this->store_session['drp_supplier_id']) && $this->checkDrp(true))) {
            $fx_again = true;
        } else {
            $fx_again = false;
        }
        $is_supplier = false;
        if (((!empty($this->store_session['drp_supplier_id']) && $this->checkDrp(true))) || empty($this->store_session['drp_supplier_id'])) {
            $is_supplier = true;
        }
        $this->assign('fx_again', $fx_again);
        $this->assign('is_supplier', $is_supplier);
	}

    public function load()
    {
        $action = strtolower(trim($_POST['page']));
        $start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '';
        $stop_time = isset($_POST['stop_time']) ? trim($_POST['stop_time']) : '';
        $store_id = isset($_POST['store_id']) ? trim($_POST['store_id']) : $this->store_session['store_id'];
        if (empty($action)) pigcms_tips('非法访问！', 'none');
        switch ($action) {
            case 'index_content':
                $this->_index_content();
                break;
            case 'market_content':
                $this->_market_content();
                break;
            case 'goods_content':
                $this->_goods_content();
                break;
            case 'orders_content':
                $this->_orders_content();
                break;
            case 'order_detail_content':
                $this->_order_detail_content();
                break;
            case 'pay_order_content':
                $this->_pay_order_content();
                break;
            case 'supplier_content':
                $this->_supplier_content();
                break;
            case 'supplier_goods_content':
                $this->_supplier_goods_content();
                break;
            case 'contact_information_content':
                $this->contact_information_content();
                break;
            case 'my_seller_detail_content':
                $this->_seller_detail_content(array('store_id' => $store_id));
                break;
            case 'goods_fx_setting_content':
                $this->_goods_fx_setting_content();
                break;
            case 'supplier_market_content':
                $this->_supplier_market_content();
                break;
            case 'edit_goods_content':
                $this->_edit_goods_content();
                break;
            case 'seller_content':
                $this->_seller_content();
                break;
            case 'next_seller_content':
                $this->_next_seller_content();
                break;
            case 'seller_order_content':
                $this->seller_order_content();
                break;
            case 'distribution_index_content':
                $this->distribution_index_content();
                break;
            case 'distribution_rank_content':
                $this->distribution_rank_content();
                break;
            case 'edit_wholesale_content':
                $this->edit_wholesale_content();
                break;
            case 'goods_wholesale_setting_content':
                $this->_goods_wholesale_setting_content();
                break;
            case 'statistics_content':
                $this->_statistics_content(array('start_time' => $start_time, 'stop_time' => $stop_time, 'store_id' => $store_id));
                break;
            case 'setting_content':
                $this->_setting_content();
                break;
            case 'commission_detail_content':
                $this->_commission_detail_content();
                break;
            case 'my_wholesale_content':
                $this->my_wholesale_content();
                break;
            case 'my_supplier_content':
                $this->my_supplier_content();
                break;
            case 'wholesale_order_content':
                $this->wholesale_order_content();
                break;
            case 'setting_supplier_content':
                $this->_seller_setting_content();
                break;
            case 'agency_content':
                $this->_agency_content();
                break;
        }
        $this->display($_POST['page']);
    }

	public function index()
	{
        $this->display();
	}

    public function seller_setting() {
        $this->display();
    }

    private function _index_content()
    {
        $store   = M('Store');
        $product = M('Product');
        $order   = M('Order');
        $financial_record = M('Financial_record');
        $store_supplier   = M('Store_supplier');

        $supplierId = $this->store_session['store_id'];


        $supplier = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $supplierId))->find();
        if (!empty($supplier['supply_chain'])) {
            $supplier = explode(',', $supplier['supply_chain']);
            if (!empty($supplier[1])) {
                $top_supplier_id = $supplier[1];
            }
        }

        $top_supplier_id = !empty($top_supplier_id) ? $top_supplier_id : $supplierId;

        //获取供货商分销商品
        $fx_product_count = $product->supplierFxProductCount(array('store_id' => $top_supplier_id, 'is_fx' => 1, 'status' => 1));

        //店铺销售额
        $sales = $order->getSales(array('store_id' => $this->store_session['store_id'], 'is_fx' => 1, 'status' => array('in', array(2,3,4,7))));
        $sales = !empty($sales) ? $sales : '0.00';
        $sales = number_format($sales, 2, '.', '');

        //店铺佣金
        $store = $store->getStore($this->store_session['store_id']);
        $profit = !empty($store['income']) ? $store['income'] : 0;
        $profit = number_format($profit, 2, '.', '');

        //七天销售额、佣金
        $days_7_sales   = array();
        $days_7_profits = array();

        $every_day_sellers = array();
        $end_today_sellers = array();
        $days = array();
        $tmp_days = array();
        for($i=6; $i>=0; $i--){
            $day = date("Y-m-d",strtotime('-'.$i.'day'));
            $days[] = $day;
        }
        foreach ($days as $day) {
            //开始时间
            $start_time = strtotime($day . ' 00:00:00');
            //结束时间
            $stop_time  = strtotime($day . ' 23:59:59');
            $where = array();
            $where['store_id'] = $this->store_session['store_id'];
            $where['status']   = array('in', array(2,3,4,7));
            $where['is_fx']    = 1;
            $where['_string']  = "paid_time >= " . $start_time . " AND paid_time < " . $stop_time;
            $tmp_days_7_sales  = $order->getSales($where);
            $days_7_sales[] = !empty($tmp_days_7_sales) ? number_format($tmp_days_7_sales, 2, '.', '') : 0;

            $where = array();
            $where['store_id']  = $this->store_session['store_id'];
            $where['status']    = array('in', array(1, 3));
            $where['_string']   = "add_time >= " . $start_time . " AND add_time < " . $stop_time;
            $tmp_days_7_profits = $financial_record->drpProfit($where);
            $days_7_profits[] = !empty($tmp_days_7_profits) ? number_format($tmp_days_7_profits, 2, '.', '') : 0;


            //每日新增
            $where = array();
            $tmp_every_day_sellers = M('Store')->getSellerCountBySales($where, $start_time, $stop_time);
            $every_day_sellers[] = $tmp_every_day_sellers;

            //总分销商
            $where = array();
            $tmp_end_today_sellers = M('Store')->getSellerCountBySales($where, 0, $stop_time);
            $end_today_sellers[] = $tmp_end_today_sellers;

            $tmp_days[] = "'" . $day . "'";
        }
        $every_day_sellers = '[' . implode(',', $every_day_sellers) . ']';
        $end_today_sellers = '[' . implode(',', $end_today_sellers) . ']';
        $days = '[' . implode(',', $tmp_days) . ']';
        $days_7_sales   = '[' . implode(',', $days_7_sales) . ']';
        $days_7_profits = '[' . implode(',', $days_7_profits) . ']';

        $this->assign('fx_product_count', $fx_product_count);
        $this->assign('sales', $sales);
        $this->assign('profit', $profit);
        $this->assign('days', $days);
        $this->assign('days_7_sales', $days_7_sales);
        $this->assign('days_7_profits', $days_7_profits);
        $this->assign('every_day_sellers', $every_day_sellers);
        $this->assign('end_today_sellers', $end_today_sellers);
    }

    //全网分销商品市场
    public function market()
    {
        $store = M('Store');
        $product = M('Product');
        $product_image = M('Product_image');
        $product_sku = M('Product_sku');
        $product_to_group = M('Product_to_group');
        $product_to_property = M('Product_to_property');
        $product_to_property_value = M('Product_to_property_value');
        $product_qrcode_activity = M('Product_qrcode_activity');
        $product_custom_field = M('Product_custom_field');
        //$seller_fx_product = M('Seller_fx_product');
        $store_supplier = M('Store_supplier');
        //批发处理
        if (IS_POST && strtolower(trim($_POST['type'])) == 'wholesale') {
            $products = isset($_POST['product_ids']) ? $_POST['product_ids'] : array ();
            $address_id = 0;

            foreach ($products as $product_id)
            {
                $product_info = $product->get(array ('product_id' => $product_id, 'is_wholesale' => 1), '*');
                $data = $product_info;
                unset($data['product_id']);
                $data['name']     = mysql_real_escape_string($data['name']);
                $data['uid']      = $this->user_session['uid'];
                $data['store_id'] = $this->store_session['store_id'];
                $data['is_fx'] = 0;
                $data['is_wholesale'] = 0;
                $data['wholesale_product_id'] = $product_id;
                $data['status']               = 0; //仓库中
                $data['date_added']           = time();
                $data['supplier_id']          = $product_info['store_id'];
                $data['pv']                   = 0;
                $data['delivery_address_id']  = $address_id;
                $data['sales']                = 0; //销量清零
                $data['source_product_id']    = 0;
                $data['original_product_id']  = 0;
                $data['is_fx_setting']        = 0;
                $data['price']                = $product_info['sale_min_price'];
                $data['wholesale_price']      = $product_info['wholesale_price'];
                $data['sale_min_price']       = $product_info['sale_min_price'];
                $data['sale_max_price']       = $product_info['sale_max_price'];

                if ($new_product_id = $product->add($data))
                {
                    //商品图片
                    $tmp_images = $product_image->getImages($product_id);
                    $images = array ();
                    foreach ($tmp_images as $tmp_image)
                    {
                        $images[] = $tmp_image['image'];
                    }
                    $product_image->add($new_product_id, $images);
                    //商品自定义字段
                    $tmp_fields = $product_custom_field->getFields($product_id);
                    $fields = array ();
                    if (!empty($tmp_fields))
                    {
                        foreach ($tmp_fields as $tmp_field)
                        {
                            $fields[] = array (
                                'name' => $tmp_field['field_name'],
                                'type' => $tmp_field['field_type'],
                                'multi_rows' => $tmp_field['multi_rows'],
                                'required' => $tmp_field['required']
                            );
                        }
                        $product_custom_field->add($new_product_id, $fields);
                    }

                    //商品属性名
                    $property_names = $product_to_property->getPropertyNames($product_info['store_id'], $product_id);
                    if (!empty($property_names))
                    {
                        foreach ($property_names as $property_name)
                        {
                            $product_to_property->add(array ('store_id' => $this->store_session['store_id'], 'product_id' => $new_product_id, 'pid' => $property_name['pid'], 'order_by' => $property_name['order_by']));
                        }
                    }
                    //商品属性值
                    $property_values = $product_to_property_value->getPropertyValues($product_info['store_id'], $product_id);
                    if (!empty($property_values))
                    {
                        foreach ($property_values as $property_value)
                        {
                            $product_to_property_value->add(array ('store_id' => $this->store_session['store_id'], 'product_id' => $new_product_id, 'pid' => $property_value['pid'], 'vid' => $property_value['vid'], 'order_by' => $property_value['order_by']));
                        }
                    }
                    //扫码活动
                    $qrcode_activities = $product_qrcode_activity->getActivities($product_info['store_id'], $product_id);
                    if (!empty($qrcode_activities))
                    {
                        foreach ($qrcode_activities as $qrcode_activitiy)
                        {
                            $product_qrcode_activity->add(array ('store_id' => $this->store_session['store_id'], 'product_id' => $new_product_id, 'buy_type' => $qrcode_activitiy['buy_type'], 'type' => $qrcode_activitiy['type'], 'discount' => $qrcode_activitiy['discount'], 'price' => $qrcode_activitiy['price']));
                        }
                    }
                    //库存信息
                    $tmp_product_skus = $product_sku->getSkus($product_id);
                    if ($tmp_product_skus)
                    {
                        $skus = array ();

                        foreach ($tmp_product_skus as $tmp_product_sku) {
                            $skus[] = array(
                                'properties'   => $tmp_product_sku['properties'],
                                'quantity'     => $tmp_product_sku['quantity'],
                                'price'        => $tmp_product_sku['sale_min_price'],
                                'code'         => $tmp_product_sku['code'],
                                'sales'        => 0,
                                'wholesale_price' => $tmp_product_sku['wholesale_price'], //批发价清0
                                'sale_min_price'  => $tmp_product_sku['sale_min_price'],
                                'sale_max_price'  => $tmp_product_sku['sale_max_price'],
                            );
                        }


                        $product_sku->add($new_product_id, $skus);
                    }
                    if (!$store_supplier->suppliers(array ('supplier_id' => $product_info['store_id'], 'seller_id' => $this->store_session['store_id'])))
                    {
                        $store_supplier->add(array ('supplier_id' => $product_info['store_id'], 'seller_id' => $this->store_session['store_id']));
                    }
                    else
                    {
                        $current_seller = $store_supplier->getSeller(array ('seller_id' => $this->store_session['store_id'], 'supplier_id' => $product_info['store_id']));

                        $seller = $store_supplier->getSeller(array ('seller_id' => $product_info['store_id'])); //获取上级分销商信息
                        if (empty($seller['type']))
                        { //全网分销的分销商
                            $seller['supply_chain'] = 0;
                            $seller['level'] = 0;
                        }
                        $seller['supply_chain'] = !empty($seller['supply_chain']) ? $seller['supply_chain'] : 0;
                        $seller['level'] = !empty($seller['level']) ? $seller['level'] : 0;
                        $supply_chain = !empty($product_info['store_id']) ? $seller['supply_chain'] . ',' . $product_info['store_id'] : 0;
                        $level = $seller['level'] + 1;
                        if ($current_seller['supplier_id'] != $product_info['store_id'])
                        {
                            $store_supplier->add(array ('supplier_id' => $product_info['store_id'], 'seller_id' => $this->store_session['store_id'], 'supply_chain' => $supply_chain, 'level' => $level, 'type' => 1));//添加分销关联关系
                            $_SESSION['store']['drp_supplier_id'] = $product_info['store_id'];
                            //供货商店铺
                            $supplier_store = $store->getStore($product_info['store_id']);
                            //获取供货商分销级别
                            $drp_level = !empty($supplier_store['drp_level']) ? $supplier_store['drp_level'] : 0;
                            D('Store')->where(array ('store_id' => $this->store_session['store_id']))->data(array ('drp_supplier_id' => $product_info['store_id'], 'drp_level' => ($drp_level + 1)))->save();
                        }
                    }
                }
            }
            json_return(0, '批发成功');
            exit;
            // 分销处理
        }else if(IS_POST && strtolower(trim($_POST['type'])) == 'fx')
        {
            $store = M('Store');
            $product = M('Product');
            $product_image = M('Product_image');
            $product_sku = M('Product_sku');
            $product_to_group = M('Product_to_group');
            $product_to_property = M('Product_to_property');
            $product_to_property_value = M('Product_to_property_value');
            $product_qrcode_activity = M('Product_qrcode_activity');
            $product_custom_field = M('Product_custom_field');
            //$seller_fx_product = M('Seller_fx_product');
            $store_supplier = M('Store_supplier');

            $products = isset($_POST['product_ids']) ? $_POST['product_ids'] : array();
            $address_id = 0;
            foreach ($products as $product_id) {
                $product_info = $product->get(array('product_id' => $product_id, 'is_fx' => 1), '*');
                $data = $product_info;
                unset($data['product_id']);
                $data['name'] = mysql_real_escape_string($data['name']);
                $data['uid'] = $this->user_session['uid'];
                $data['store_id'] = $this->store_session['store_id'];
                $data['price'] = $product_info['min_fx_price'];
                $data['is_wholesale'] = 0;
                $data['is_fx'] = 0;
                $data['source_product_id'] = $product_id;
                $data['status'] = 0; //仓库中
                $data['date_added'] = time();
                $data['supplier_id'] = $product_info['store_id'];
                $data['pv'] = 0;
                $data['delivery_address_id'] = $address_id;
                $data['sales'] = 0; //销量清零
                $data['cost_price']   = 0;
                $data['min_fx_price'] = 0;
                $data['max_fx_price'] = 0;
                $data['drp_level_1_price'] = 0;
                $data['drp_level_2_price'] = 0;
                $data['drp_level_3_price'] = 0;
                $data['drp_level_1_cost_price'] = 0;
                $data['drp_level_2_cost_price'] = 0;
                $data['drp_level_3_cost_price'] = 0;

                if (!empty($product_info['original_product_id'])) {
                    $data['original_product_id'] = $product_info['original_product_id'];
                } else {
                    $data['original_product_id'] = $product_id;
                }
                $data['is_fx_setting'] = 0;
                if ($new_product_id = $product->add($data)) {
                    //商品图片
                    $tmp_images = $product_image->getImages($product_id);
                    $images = array();
                    foreach ($tmp_images as $tmp_image) {
                        $images[] =  $tmp_image['image'];
                    }
                    $product_image->add($new_product_id, $images);
                    //商品自定义字段
                    $tmp_fields = $product_custom_field->getFields($product_id);
                    $fields = array();
                    if (!empty($tmp_fields)) {
                        foreach ($tmp_fields as $tmp_field) {
                            $fields[] = array(
                                'name'       => $tmp_field['field_name'],
                                'type'       => $tmp_field['field_type'],
                                'multi_rows' => $tmp_field['multi_rows'],
                                'required'   => $tmp_field['required']
                            );
                        }
                        $product_custom_field->add($product_id, $fields);
                    }
                    //商品分组
                    /*$groups = $product_to_group->getGroups($product_id);
                    if (!empty($groups)) {
                        foreach ($groups as $group) {
                            $product_to_group->add(array('product_id' => $new_product_id, 'group_id' => $group['group_id']));
                        }
                    }*/
                    //商品属性名
                    $property_names = $product_to_property->getPropertyNames($product_info['store_id'], $product_id);
                    if (!empty($property_names)) {
                        foreach ($property_names as $property_name) {
                            $product_to_property->add(array('store_id' => $this->store_session['store_id'], 'product_id' => $new_product_id, 'pid' => $property_name['pid'], 'order_by' => $property_name['order_by']));
                        }
                    }
                    //商品属性值
                    $property_values = $product_to_property_value->getPropertyValues($product_info['store_id'], $product_id);
                    if (!empty($property_values)) {
                        foreach ($property_values as $property_value) {
                            $product_to_property_value->add(array('store_id' => $this->store_session['store_id'], 'product_id' => $new_product_id, 'pid' => $property_value['pid'], 'vid' => $property_value['vid'], 'order_by' => $property_value['order_by']));
                        }
                    }
                    //扫码活动
                    $qrcode_activities = $product_qrcode_activity->getActivities($product_info['store_id'], $product_id);
                    if (!empty($qrcode_activities)) {
                        foreach ($qrcode_activities as $qrcode_activitiy) {
                            $product_qrcode_activity->add(array('store_id' => $this->store_session['store_id'], 'product_id' => $new_product_id, 'buy_type' => $qrcode_activitiy['buy_type'], 'type' => $qrcode_activitiy['type'], 'discount' => $qrcode_activitiy['discount'], 'price' => $qrcode_activitiy['price']));
                        }
                    }
                    //库存信息
                    $tmp_product_skus = $product_sku->getSkus($product_id);


                    if ($tmp_product_skus) {
                        $skus = array();
                        foreach ($tmp_product_skus as $tmp_product_sku) {
                            $skus[] = array(
                                'properties'   => $tmp_product_sku['properties'],
                                'quantity'     => $tmp_product_sku['quantity'],
                                'price'        => $tmp_product_sku['min_fx_price'],
                                'code'         => $tmp_product_sku['code'],
                                'sales'        => 0,
                                'cost_price'   => 0,
                                'min_fx_price' =>0,
                                'max_fx_price' => 0
                            );
                        }
                        $product_sku->add($new_product_id, $skus);
                    }
                    if (empty($this->user_session['drp_store_id'])) {
                        if (!$store_supplier->suppliers(array('supplier_id' => $product_info['store_id'], 'seller_id' => $this->store_session['store_id']))) {
                            $store_supplier->add(array('supplier_id' => $product_info['store_id'], 'seller_id' => $this->store_session['store_id']));
                        }
                    } else {
                        $current_seller = $store_supplier->getSeller(array('seller_id' => $this->store_session['store_id']));

                        $seller = $store_supplier->getSeller(array('seller_id' => $product_info['store_id'])); //获取上级分销商信息
                        if (empty($seller['type'])) { //全网分销的分销商
                            $seller['supply_chain'] = 0;
                            $seller['level'] = 0;
                        }
                        $seller['supply_chain'] = !empty($seller['supply_chain']) ? $seller['supply_chain'] : 0;
                        $seller['level'] = !empty($seller['level']) ? $seller['level'] : 0;
                        $supply_chain = !empty($product_info['store_id']) ? $seller['supply_chain'] . ',' . $product_info['store_id'] : 0;
                        $level = $seller['level'] + 1;
                        if ($current_seller['supplier_id'] != $product_info['store_id']) {
                            $store_supplier->add(array('supplier_id' => $product_info['store_id'], 'seller_id' => $this->store_session['store_id'], 'supply_chain' => $supply_chain, 'level' => $level, 'type' => 1));//添加分销关联关系
                            $_SESSION['store']['drp_supplier_id'] = $product_info['store_id'];
                            //供货商店铺
                            $supplier_store = $store->getStore($product_info['store_id']);
                            //获取供货商分销级别
                            $drp_level = !empty($supplier_store['drp_level']) ? $supplier_store['drp_level'] : 0;
                            D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('drp_supplier_id' => $product_info['store_id'], 'drp_level' => ($drp_level + 1)))->save();
                        }
                    }
                    json_return(0, '分销成功');
                }
            }
            exit;
        }

            $this->display();
        }

        private function _market_content()
        {
            $product = M('Product');
            $product_group = M('Product_group');
            $product_to_group = M('Product_to_group');
            $store_supplier = M('Store_supplier');
            $is = !empty($_POST['is']) ? intval($_POST['is']) : 1;
            $store = M('Store');
            $order_by_field = 'is_fx';
            $order_by_method = 'DESC';
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';

            $where = array();
            //当前店铺信息
            $store_info = $store->getStore($this->store_session['store_id']);

            $seller = $store_supplier->getSeller(array('seller_id' => $this->store_session['store_id'], 'type' => 1));

       // $where['store_id'] =  $this->store_session['store_id'];
        if($store_info['drp_level'] == 0)
        {
            $type = 'wholesale';
            $where['is_wholesale'] = 1; //设置分销的商品
        }
        else if($store_info['drp_level'] > 0)
        {
            $type = 'fx';
            $where['is_fx'] = 1;
        }

        //$where['wholesale_product_id'] = 0;
        $where['status'] = 1;
        if (!empty($_POST['category_id'])) {
            $where['category_id'] = intval(trim($_POST['category_id']));
        }
        if (!empty($_POST['category_fid'])) {
            $where['category_fid'] = intval(trim($_POST['category_fid']));
        }
        if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        if (empty($this->store_session['drp_diy_store'])) {
           // $where['store_id'] = $this->store_session['top_supplier_id'];
           // $where['unified_price_setting'] = 1;
        }
        //排除当前用户的商品
        //$where['uid'] = array('!=', $this->user_session['uid']);
        $product_total = $product->getSellingTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        $tmp_products = $product->getSelling($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);
        $products = array();
        foreach ($tmp_products as $tmp_product) {
            $supplier = D('Store')->field('name,store_id')->where(array('store_id' => $tmp_product['store_id']))->find();
            $tmp_product['supplier'] = $supplier['name'];
            $tmp_product['supplier_id'] = $supplier['store_id'];

            if (empty($this->store_session['drp_diy_store'])) {
                $drp_level = $this->store_session['drp_level'];
                if ($drp_level > 3) {
                    $drp_level = 3;
                }
                if($store_info['drp_level'] == 0)
                {
                    $tmp_product['wholesale_price']   = !empty($tmp_product['wholesale_price']) ? $tmp_product['wholesale_price'] : '0';
                    $tmp_product['sale_min_price'] = !empty($tmp_product['sale_min_price']) ? $tmp_product['sale_min_price'] : '0';
                    $tmp_product['sale_max_price'] = !empty($tmp_product['sale_max_price']) ? $tmp_product['sale_max_price'] : '0';
                }
                else
                {
                    $tmp_product['cost_price']   = !empty($tmp_product['cost_price']) ? $tmp_product['cost_price'] : '0';
                    $tmp_product['min_fx_price'] = !empty($tmp_product['min_fx_price']) ? $tmp_product['min_fx_price'] : '0';
                    $tmp_product['max_fx_price'] = !empty($tmp_product['max_fx_price']) ? $tmp_product['max_fx_price'] : '0';
                }
            }
            $products[] = $tmp_product;
        }

        //商品分类
        $category = M('Product_category');
        $categories = $category->getCategories(array('cat_status'=>1),'cat_path ASC');

        $tmp_fx_products = $product->fxProducts($this->store_session['store_id']);

        $fx_products = array();
        foreach ($tmp_fx_products as $tmp_fx_product) {
            $fx_products[] = $tmp_fx_product['source_product_id'];
        }

        $wholesale_products = array();
        foreach ($tmp_fx_products as $tmp_fx_product) {
            $wholesale_products[] = $tmp_fx_product['wholesale_product_id'];
        }

        $this->assign('page', $page->show());
        $this->assign('products', $products);
        $this->assign('categories', $categories);
        $this->assign('fx_products', $fx_products);
        $this->assign('wholesale_products', $wholesale_products);
        $this->assign('product_total', $product_total);
        $this->assign('type', $type);

    }

    //已分销商品
    public function goods()
    {
        $this->display();
    }

    private function _goods_content()
    {
        $product = M('Product');
        $store = M('Store');
        $user_address = M('User_address');
        $store_supplier = M('Store_supplier');

        $supplierInfo = $store_supplier->getSeller(array(
            'seller_id' => $this->store_session['store_id']
        ));

        // 顶级供货商的id
        $supplierList = explode(',', $supplierInfo['supply_chain']);
        $supplier_id = $supplierList['1'];

        $product_count = $product->fxProductCount(array('store_id' => $supplier_id, 'is_fx' => 1));

        /*if (!empty($this->store_session['drp_diy_store'])) {
            $product_count = $product->fxProductCount(array('store_id' => $this->store_session['store_id'], 'supplier_id' => array('>', 0), 'status' => array('<', 2)));
        } else {
            $product_count = $product->fxProductCount(array('is_fx' => 1 ,'status' => 1));
        }*/

        import('source.class.user_page');
        $page = new Page($product_count, 15);
        if (!empty($this->store_session['drp_diy_store'])) {
            $tmp_products = $product->fxProducts($supplier_id, $page->firstRow, $page->listRows);
        } else {
            $order_by_field = 'product_id';
            $order_by_method = 'DESC';
            $where = array('status' => 1, 'is_fx' => 1,'store_id' => $supplier_id);
            $tmp_products = $product->getSelling($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);
        }
        $products = array();

        foreach ($tmp_products as $tmp_product) {
            if (!empty($tmp_product['delivery_address_id'])) {
                import('source.class.area');
                $address = $user_address->getAddress($tmp_product['delivery_address_id']);
                $address_obj = new area();
                $province = $address_obj->get_name($address['province']);
                $city = $address_obj->get_name($address['city']);
                $area = $address_obj->get_name($address['area']);
                $delivery_address = $province . ' ' . $city . ' ' . $area . ' ' . $address['address'];
            } else {
                $delivery_address = '使用买家收货地址';
            }
            if (!empty($this->store_session['drp_diy_store'])) {
                $supplier = $store->getStore($tmp_product['supplier_id']);
                $source_product = $product->get(array('product_id' => $tmp_product['source_product_id']), 'cost_price');
                $products[] = array(
                    'store_id'              => $tmp_product['store_id'],
                    'product_id'            => $tmp_product['product_id'],
                    'name'                  => $tmp_product['name'],
                    'image'                 => getAttachmentUrl($tmp_product['image']),
                    'cost_price'            => $source_product['cost_price'],
                    'min_fx_price'          => $tmp_product['min_fx_price'],
                    'max_fx_price'          => $tmp_product['max_fx_price'],
                    'quantity'              => $tmp_product['quantity'],
                    'sales'                 => $tmp_product['sales'],
                    'supplier'              => $supplier['name'],
                    'unified_price_setting' => $tmp_product['unified_price_setting'],
                    'delivery_address'      => $delivery_address,
                    'delivery_address_id'   => $tmp_product['delivery_address_id'],
                    'is_fx_setting'         => $tmp_product['is_fx_setting'],
                    'drp_level_1_price'     => $tmp_product['drp_level_1_price'], // 一级分销商销售价
                    'drp_level_2_price'     => $tmp_product['drp_level_2_price'], // 二级分销商销售价
                    'drp_level_3_price'     => $tmp_product['drp_level_3_price'], // 三级分销商销售价
                    'drp_level_1_cost_price'     => $tmp_product['drp_level_1_cost_price'], // 一级分销商成本价
                    'drp_level_2_cost_price'     => $tmp_product['drp_level_2_cost_price'], // 二级分销商成本价
                    'drp_level_3_cost_price'     => $tmp_product['drp_level_3_cost_price'], // 三级分销商成本价
                );
            } else { //不允许装修店铺
                $supplier = $store->getStore($this->store_session['drp_supplier_id']);
                $drp_level = $this->store_session['drp_level'];
                if ($drp_level > 3) {
                    $drp_level = 3;
                }
                $products[] = array(
                    'store_id'              => $tmp_product['store_id'],
                    'product_id'            => $tmp_product['product_id'],
                    'name'                  => $tmp_product['name'],
                    'image'                 => getAttachmentUrl($tmp_product['image']),
                    'cost_price'            => !empty($tmp_product['drp_level_' . $drp_level . '_cost_price']) ? $tmp_product['drp_level_' . $drp_level . '_cost_price'] : $tmp_product['cost_price'],
                    'min_fx_price'          => $tmp_product['min_fx_price'],
                    'max_fx_price'          => $tmp_product['max_fx_price'],
                    'quantity'              => $tmp_product['quantity'],
                    'sales'                 => $tmp_product['sales'],
                    'supplier'              => $supplier['name'],
                    'unified_price_setting' => $tmp_product['unified_price_setting'],
                    'delivery_address'      => $delivery_address,
                    'delivery_address_id'   => $tmp_product['delivery_address_id'],
                    'is_fx_setting'         => $tmp_product['is_fx_setting'],
                    'drp_level_1_price'     => $tmp_product['drp_level_1_price'], // 一级分销商销售价
                    'drp_level_2_price'     => $tmp_product['drp_level_2_price'], // 二级分销商销售价
                    'drp_level_3_price'     => $tmp_product['drp_level_3_price'], // 三级分销商销售价
                    'drp_level_1_cost_price'     => $tmp_product['drp_level_1_cost_price'], // 一级分销商成本价
                    'drp_level_2_cost_price'     => $tmp_product['drp_level_2_cost_price'], // 二级分销商成本价
                    'drp_level_3_cost_price'     => $tmp_product['drp_level_3_cost_price'], // 三级分销商成本价
                );
            }
        }

        $this->assign('drp_level', $this->store_session['drp_level']);
        $this->assign('products', $products);
        $this->assign('page', $page->show());
    }

    //编辑分销商品
    public function edit_goods()
    {
        if (IS_POST) {
            $product = M('Product');
            $product_sku = M('Product_sku');
            $product_id = !empty($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
            $cost_price = !empty($_POST['cost_price']) ? floatval(trim($_POST['cost_price'])) : 0;
            $min_fx_price = !empty($_POST['min_fx_price']) ? floatval(trim($_POST['min_fx_price'])) : 0;
            $max_fx_price = !empty($_POST['max_fx_price']) ? floatval(trim($_POST['max_fx_price'])) : 0;
            $is_recommend = !empty($_POST['is_recommend']) ? intval(trim($_POST['is_recommend'])) : 0;
            $unified_price_setting = !empty($_POST['unified_price_setting']) ? $_POST['unified_price_setting'] : 0;
            $is_fx_setting = 1;
            $skus = !empty($_POST['skus']) ? $_POST['skus'] : array();
            $fx_type = 0; //分销类型 0全网、1排他
            if (strtolower(trim($_GET['role'])) == 'seller' || !empty($this->store_session['drp_supplier_id'])) {
                $fx_type = 1;
            }
            $data = array(
                'cost_price'    => $cost_price,
                'min_fx_price'  => $min_fx_price,
                'max_fx_price'  => $max_fx_price,
                'is_recommend'  => $is_recommend,
                'is_fx'         => 1, // 1 为已分销商品
                'fx_type'       => $fx_type,
                'is_fx_setting' => $is_fx_setting,
                'unified_price_setting' => $unified_price_setting
            );
            $product_info = M('Product')->get(array('product_id' => $product_id, 'store_id' => $_SESSION['store']['store_id']));

            $data['cost_price'] = !empty($_POST['cost_price']) ? $_POST['cost_price'] : 0;
            $data['min_fx_price'] = !empty($_POST['min_fx_price']) ? $_POST['min_fx_price'] : 0;
            $data['max_fx_price'] = !empty($_POST['max_fx_price']) ? $_POST['max_fx_price'] : 0;
            $data['drp_level_1_cost_price'] = !empty($_POST['drp_level_1_cost_price']) ? $_POST['drp_level_1_cost_price'] : 0;
            $data['drp_level_2_cost_price'] = !empty($_POST['drp_level_2_cost_price']) ? $_POST['drp_level_2_cost_price'] : 0;
            $data['drp_level_3_cost_price'] = !empty($_POST['drp_level_3_cost_price']) ? $_POST['drp_level_3_cost_price'] : 0;
            $data['drp_level_1_price'] = !empty($_POST['drp_level_1_price']) ? $_POST['drp_level_1_price'] : 0;
            $data['drp_level_2_price'] = !empty($_POST['drp_level_2_price']) ? $_POST['drp_level_2_price'] : 0;
            $data['drp_level_3_price'] = !empty($_POST['drp_level_3_price']) ? $_POST['drp_level_3_price'] : 0;

            $result = D('Product')->where(array('product_id' => $product_id))->data($data)->save();
            if (count($skus)>0) {
                $result_sku = $product_sku->fx_Goods_Edit($product_id, $skus, $unified_price_setting);
            }
            
            if ($result || $result_sku) {
                json_return(0, url('supplier_market'));
            } else {
                json_return(1001, '保存失败');
            }
        }
        $this->display();
    }

    private function _edit_goods_content()
    {
        $product = M('Product');
        $category = M('Product_category');
        $product_property = M('Product_property');
        $product_property_value = M('Product_property_value');
        $product_to_property = M('Product_to_property');
        $product_to_property_value = M('Product_to_property_value');
        $product_sku = M('Product_sku');

        $id = isset($_POST['id']) ? intval(trim($_POST['id'])) : 0;

        $product = $product->get(array('product_id' => $id, 'store_id' => $this->store_session['store_id']));
        $category = $category->getCategory($product['category_id']);
        $product['category'] = $category['cat_name'];

        $pids = $product_to_property->getPids($this->store_session['store_id'], $id);
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
                $html .= '        <th class="text-center" width="80">' . $name . '</th>';
                $html .= '        <th class="text-center" width="80">' . $name1 . '</th>';
                $html .= '        <th class="text-center" width="80">' . $name2 . '</th>';
                $html .= '        <th class="th-price" style="width: 70px;text-align: center">成本价（元）</th>';
                $html .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';

                $html2 = '<thead>';
                $html2 .= '    <tr>';
                $html2 .= '        <th class="text-center" width="80">' . $name . '</th>';
                $html2 .= '        <th class="text-center" width="80">' . $name1 . '</th>';
                $html2 .= '        <th class="text-center" width="80">' . $name2 . '</th>';
                $html2 .= '        <th class="th-price" style="width: 70px;text-align: center">成本价（元）</th>';
                $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
                $html2 .= '    </tr>';
                $html2 .= '</thead>';
                $html2 .= '<tbody>';

                $html3 = '<thead>';
                $html3 .= '    <tr>';
                $html3 .= '        <th class="text-center" width="80">' . $name . '</th>';
                $html3 .= '        <th class="text-center" width="80">' . $name1 . '</th>';
                $html3 .= '        <th class="text-center" width="80">' . $name2 . '</th>';
                $html3 .= '        <th class="th-price" style="width: 70px;text-align: center">成本价（元）</th>';
                $html3 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
                $html3 .= '    </tr>';
                $html3 .= '</thead>';
                $html3 .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    foreach ($vids1 as $key1 => $vid1) {
                        $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                        foreach ($vids2 as $key2 => $vid2) {
                            $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'] . ';' . $pid2 . ':' . $vid2['vid'];
                            $sku = $product_sku->getSku($id, $properties);
                            $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                            $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                            $html3 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                            $value2 = $product_property_value->getValue($pid2, $vid2['vid']);
                            if($key1 == 0 && $key2 == 0) {
                                $html .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
                                $html2 .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
                                $html3 .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
                            }
                            if($key2 == 0) {
                                $html .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
                                $html2 .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
                                $html3 .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
                            }
                            $html .= '        <td class="text-center" width="50">' . $value2 . '</td>';
                            $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" value='.$sku['drp_level_1_cost_price'].' ></td>';
                            $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" value='.$sku['drp_level_1_price'].' ></td>';
                            $html .= '    </tr>';

                            $html2 .= '        <td class="text-center" width="50">' . $value2 . '</td>';
                            $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" value='.$sku['drp_level_2_cost_price'].' ></td>';
                            $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" value='.$sku['drp_level_2_price'].' ></td>';
                            $html2 .= '    </tr>';

                            $html3 .= '        <td class="text-center" width="50">' . $value2 . '</td>';
                            $html3 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" value='.$sku['drp_level_3_cost_price'].' ></td>';
                            $html3 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" value='.$sku['drp_level_3_price'].' ></td>';
                            $html3 .= '    </tr>';
                        }
                    }
                }
            } else if (!empty($pids[1]['pid'])) {
                $pid1 = $pids[1]['pid'];
                $name1 = $product_property->getName($pid1);
                $vids1 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid1);
                $html = '<thead>';
                $html .= '    <tr>';
                $html .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html .= '        <th class="text-center" width="50">' . $name1 . '</th>';
                $html .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
                $html .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';

                $html2 = '<thead>';
                $html2 .= '    <tr>';
                $html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html2 .= '        <th class="text-center" width="50">' . $name1 . '</th>';
                $html2 .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
                $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
                $html2 .= '    </tr>';
                $html2 .= '</thead>';
                $html2 .= '<tbody>';

                $html3 = '<thead>';
                $html3 .= '    <tr>';
                $html3 .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html3 .= '        <th class="text-center" width="50">' . $name1 . '</th>';
                $html3 .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
                $html3 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
                $html3 .= '    </tr>';
                $html3 .= '</thead>';
                $html3 .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    foreach ($vids1 as $key1 => $vid1) {
                        $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'];
                        $sku = $product_sku->getSku($id, $properties);
                        $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';

                        $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';

                        $html3 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                        $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                        if($key1 == 0) {
                            $html .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';

                            $html2 .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';

                            $html3 .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
                        }
                        $html .= '        <td class="text-center" width="50">' . $value1 . '</td>';
                        $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini"  maxlength="10" value='.$sku['drp_level_1_cost_price'].' /></td>';
                        $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-price input-mini"  maxlength="10" value='.$sku['drp_level_1_price'].' /></td>';
                        $html .= '    </tr>';

                        $html2 .= '        <td class="text-center" width="50">' . $value1 . '</td>';
                        $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini"  maxlength="10" value='.$sku['drp_level_2_cost_price'].' /></td>';
                        $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-price input-mini"  maxlength="10" value='.$sku['drp_level_2_price'].' /></td>';
                        $html2 .= '    </tr>';

                        $html3 .= '        <td class="text-center" width="50">' . $value1 . '</td>';
                        $html3 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini"  maxlength="10" value='.$sku['drp_level_3_cost_price'].' /></td>';
                        $html3 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-price input-mini"  maxlength="10" value='.$sku['drp_level_3_price'].' /></td>';
                        $html3.= '    </tr>';
                    }
                }
            } else {
                $html = '<thead>';
                $html .= '    <tr>';
                $html .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
                $html .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';

                $html2 = '<thead>';
                $html2 .= '    <tr>';
                $html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html2 .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
                $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
                $html2 .= '    </tr>';
                $html2 .= '</thead>';
                $html2 .= '<tbody>';

                $html3 = '<thead>';
                $html3 .= '    <tr>';
                $html3 .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html3 .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
                $html3 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
                $html3 .= '    </tr>';
                $html3 .= '</thead>';
                $html3 .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    $properties = $pid . ':' . $vid['vid'];
                    $sku = $product_sku->getSku($id, $properties);
                    $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                    $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                    $html3 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    $html .= '        <td class="text-center" width="50">' . $value . '</td>';
                    $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" value='.$sku['drp_level_1_cost_price'].' /></td>';
                    $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-price input-mini" maxlength="10" value='.$sku['drp_level_1_price'].' /></td>';
                    $html .= '    </tr>';

                    $html2 .= '        <td class="text-center" width="50">' . $value . '</td>';
                    $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" value='.$sku['drp_level_2_cost_price'].' /></td>';
                    $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-price input-mini" maxlength="10" value='.$sku['drp_level_2_price'].' /></td>';
                    $html2 .= '    </tr>';

                    $html3 .= '        <td class="text-center" width="50">' . $value . '</td>';
                    $html3 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" value='.$sku['drp_level_3_cost_price'].' /></td>';
                    $html3 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-price input-mini" maxlength="10" value='.$sku['drp_level_3_price'].' /></td>';
                    $html3 .= '    </tr>';
                }
            }

            $html .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-cost" href="javascript:;">成本价</a>&nbsp;&nbsp;<a class="js-batch-price" href="javascript:;">分销价</a></span><span class="js-batch-form" style="display:none;"></span></div></td></tr></tfoot>';
            $html2 .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-cost" href="javascript:;">成本价</a>&nbsp;&nbsp;<a class="js-batch-price" href="javascript:;">分销价</a></span><span class="js-batch-form" style="display:none;"></span></div></td></tr></tfoot>';
            $html3 .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-cost" href="javascript:;">成本价</a>&nbsp;&nbsp;<a class="js-batch-price" href="javascript:;">分销价</a></span><span class="js-batch-form" style="display:none;"></span></div></td></tr></tfoot>';
        }

        $this->assign('sku_content', $html);
        $this->assign('sku_content2', $html2);
        $this->assign('sku_content3', $html3);
        $this->assign('product', $product);
    }

    //分销订单
    public function orders()
    {
        $this->display();
    }

    private function _orders_content()
    {
        $order = M('Order');
        $order_product = M('Order_product');
        $user = M('User');
        $where = array();
        $where['store_id'] = $this->store_session['store_id'];

        if (is_numeric($_POST['order_no'])) {
            $where['order_no'] = $_POST['order_no'];
        }
        if (!empty($_POST['delivery_user'])) {
            $where['address_user'] = $_POST['delivery_user'];
        }
        if (!empty($_POST['delivery_tel'])) {
            $where['address_tel'] = $_POST['delivery_tel'];
        }

        $field = '';
        if (!empty($data['time_type'])) {
            $field = $data['time_type'];
        }
        if (!empty($data['start_time']) && !empty($_POST['stop_time']) && !empty($field)) {
            $where['_string'] = "`" . $field . "` >= " . strtotime($_POST['start_time']) . " AND `" . $field . "` <= " . strtotime($_POST['stop_time']);
        } else if (!empty($_POST['start_time']) && !empty($field)) {
            $where[$field] = array('>=', strtotime($data['start_time']));
        } else if (!empty($_POST['stop_time']) && !empty($field)) {
            $where[$field] = array('<=', strtotime($_POST['stop_time']));
        }
        //排序
        if (!empty($_POST['orderbyfield']) && !empty($_POST['orderbymethod'])) {
            $orderby = "`{$_POST['orderbyfield']}` " . $_POST['orderbymethod'];
        } else {
            $orderby = '`order_id` DESC';
        }

        $order_total = $order->getOrderTotal($where);
        import('source.class.user_page');
        $page = new Page($order_total, 15);
        $tmp_orders = $order->getOrders($where, $orderby, $page->firstRow, $page->listRows);
        $orders = array();
        foreach ($tmp_orders as $tmp_order) {
            $products = $order_product->getProducts($tmp_order['order_id']);
            $tmp_order['products'] = $products;
            if (empty($tmp_order['uid'])) {
                $tmp_order['is_fans'] = false;
                $tmp_order['buyer'] = '';
            } else {
                $tmp_order['is_fans'] = true;
                $user_info = $user->checkUser(array('uid' => $tmp_order['uid']));
                $tmp_order['buyer'] = $user_info['nickname'];
            }

            // 是否有退货未完成的申请，有未完成的申请，暂时不给完成订单
            if ($tmp_order['status'] == 7) {
                $count = D('Return')->where("order_id = '" . $tmp_order['order_id'] . "' AND status IN (1, 3, 4)")->count('id');
                $tmp_order['returning_count'] = $count;
            }


            $is_supplier = false;
            $is_packaged = false;
            $is_assigned = false;
            if (!empty($tmp_order['suppliers'])) { //订单供货商
                $suppliers = explode(',', $tmp_order['suppliers']);
                if (in_array($this->store_session['store_id'], $suppliers)) {
                    $is_supplier = true;
                }
            }
            if (empty($tmp_order['suppliers'])) {
                $is_supplier = true;
            }

            $has_my_product = false;
            foreach ($products as &$product) {
                $product['image'] = getAttachmentUrl($product['image']);
                if (empty($product['is_fx'])) {
                    $has_my_product = true;
                }

                //自营商品
                if (!empty($product['supplier_id']) && $product['store_id'] == $this->store_session['store_id']) {
                    $is_supplier = true;
                }

                //商品来源
                if (empty($product['supplier_id']) && $product['store_id'] == $this->store_session['store_id']) { //本店商品
                    $from = '本店商品';
                } else if (!empty($product['supplier_id']) && $product['store_id'] == $this->store_session['store_id'] && !empty($product['wholesale_product_id'])) { //批发商品
                    $from = '批发商品';
                } else { //分销商品
                    $from = '分销商品';
                }
                $product['from'] = $from;

                //向后兼容利润计算
                $no_profit = false;
                if ($product['profit'] == 0) {
                    $fx_order = D('Fx_order')->field('fx_order_id')->where(array('order_id' => $tmp_order['order_id']))->find();
                    $fx_order_product = D('Fx_order_product')->field('cost_price')->where(array('fx_order_id' => $fx_order['fx_order_id'], 'source_product_id' => $product['product_id']))->find();
                    $product['cost_price'] = $fx_order_product['cost_price'];
                    $product['profit'] = $product['pro_price'] - $product['cost_price'];
                    if ($product['profit'] <= 0) {
                        $product['profit'] = 0;
                        $no_profit = true;
                    }
                }

                $product['cost_price'] = ($product['pro_price'] - $product['profit'] > 0) ? $product['pro_price'] - $product['profit'] : 0;
                if ($product['profit'] == 0 && empty($product['supplier_id']) && empty($no_profit)) {
                    $product['profit']     = $product['pro_price'];
                    $product['cost_price'] = 0;
                }

                $product['profit']     = number_format($product['profit'], 2, '.', '');
                $product['cost_price'] = number_format($product['cost_price'], 2, '.', '');
            }

            if (!empty($tmp_order['user_order_id'])) {
                $order_info = D('Order')->field('store_id')->where(array('order_id' => $tmp_order['user_order_id']))->find();
                $seller = D('Store')->field('name')->where(array('store_id' => $order_info['store_id']))->find();
                $tmp_order['seller'] = $seller['name'];
            } else {
                $tmp_order['seller'] = '本店';
            }

            $un_package_selfsale_products = $order_product->getUnPackageProducts(array('op.order_id' => $tmp_order['order_id'], 'p.store_id' => $this->store_session['store_id'], 'p.supplier_id' => 0));
            if (count($un_package_selfsale_products) == 0) {
                $is_packaged = true;
            }

            // TODO 是否分配完毕
            $un_package_physical_products = $order_product->getUnPackageSkuProducts($tmp_order['order_id']);
            if (count($un_package_physical_products) == 0) {
                $is_assigned = true;
            }

            $profit = M('Financial_record')->getTotal(array('order_id' => $tmp_order['order_id']));
            $cost = M('Financial_record')->getTotal(array('order_id' => $tmp_order['order_id'], 'income' => array('<', 0)));
            $cost = abs($cost);
            if ($cost <= 0) {
                //$cost = $profit;
            }

            $tmp_order['products']       = $products;
            $tmp_order['has_my_product'] = $has_my_product;
            $tmp_order['is_supplier']    = $is_supplier;
            $tmp_order['is_packaged']    = $is_packaged;
            $tmp_order['is_assigned']    = $is_assigned;
            $tmp_order['profit']         = number_format($profit, 2, '.', '');
            $tmp_order['cost']           = number_format($cost, 2, '.', '');
            $orders[] = $tmp_order;
        }

        //订单状态
        $order_status = $order->status();

        //支付方式
        $payment_method = $order->getPaymentMethod();

        $this->assign('order_status', $order_status);
        $this->assign('status', $data['status']);
        $this->assign('payment_method', $payment_method);
        $this->assign('orders', $orders);
        $this->assign('page', $page->show());
    }

    //订单详细
    public function order_detail()
    {
        $this->display();
    }

    private function _order_detail_content()
    {
        $fx_order = M('Fx_order');
        $order = M('Order');
        $order_product = M('Order_product');
        $fx_order_product = M('Fx_order_product');
        $user = M('User');
        $product = M('Product');
        $store = M('Store');
        $package = M('Order_package');

        $store_detail = $store->getDrpLevel($this->store_session['store_id']);
        $type = empty($store_detail) ? '0' : '1';
        $fx_order_id = intval(trim($_POST['order_id']));
        $fx_order_info = $fx_order->getOrder($this->store_session['store_id'], $fx_order_id, $type);
        $order_id = $fx_order_info['order_id'];
        $order_info = $order->getOrder($this->store_session['store_id'], $order_id);
        $fx_order_info['shipping_method'] = $order_info['shipping_method'];
        $fx_order_info['address'] = $order_info['address'];
        $fx_order_info['payment_method'] = $order_info['payment_method']; //买家付款方式
        $fx_order_info['buyer_paid_time'] = $order_info['paid_time']; //买家付款时间
        $fx_order_info['comment'] = $order_info['comment']; //买家留言

        $main_order = D('Order')->field('add_time')->where(array('order_id' => $order_info['user_order_id']))->find();
        $fx_order_info['add_time'] = $main_order['add_time'];
        //分销利润
        $fx_profit = number_format($fx_order_info['total'] - $fx_order_info['cost_total'], 2, '.', '');
        $fx_order_info['fx_profit'] = $fx_profit;
        $tmp_products = $fx_order_product->getFxProducts($fx_order_id);
        $products = array();
        $comment_count = 0;
        $product_count = 0;

        $order_products = $order_product->getProducts($order_id);
        foreach ($tmp_products as $tmp_product) {
            $product_info = $product->get(array('product_id' => $tmp_product['product_id']));
            $products[] = array(
                'product_id'        => $tmp_product['product_id'],
                'name'              => $product_info['name'],
                'cost_price'        => $tmp_product['cost_price'],
                'pro_price'         => $tmp_product['price'],
                'pro_num'           => $tmp_product['quantity'],
                'sku_data'          => $tmp_product['sku_data'],
                'image'             => $product_info['image'],
                'comment'           => $tmp_product['comment'],
                'is_fx'             => $tmp_product['is_fx'],
            );
            if (!empty($tmp_product['comment'])) {
                $comment_count++;
            }
            $product_count++;
        }
        if (empty($order_info['uid'])) {
            $is_fans = false;
        } else {
            $is_fans = $user->isWeixinFans($order_info['uid']);
        }

        //供货商
        $supplier = $store->getStore($fx_order_info['supplier_id']);

        $payment_method = $order->getPaymentMethod();
        $status = $fx_order->status_text();

        //获取分销商给供货商下的订单
        $seller_order_info = $order->getSellerOrder($this->user_session['uid'], $fx_order_id);
        $packages = array();
        if (!empty($seller_order_info)) {
            //包裹
            $packages = $package->getPackages(array('order_id' => $seller_order_info['order_id'], 'store_id' => $fx_order_info['supplier_id']));
        }

        $this->assign('order', $fx_order_info);
        $this->assign('products', $products);
        $this->assign('is_fans', $is_fans);
        $this->assign('payment_method', $payment_method);
        $this->assign('rows', $comment_count + $product_count);
        $this->assign('comment_count', $comment_count);
        $this->assign('status', $status);
        $this->assign('supplier', $supplier);
        $this->assign('packages', $packages);
    }

    //订单付款(分销商)
    public function pay_order()
    {
        $order = M('Order');
        $order_product = M('Order_product');
        $fx_order = M('Fx_order');
        $fx_order_product = M('Fx_order_product');
        $store = M('Store');
        $financial_record = M('Financial_record');
        $store_supplier = M('Store_supplier');
        $product_model = M('Product');
        $product_sku = M('Product_sku');

        if (IS_POST) {
            $data = array();
            $total = isset($_POST['total']) ? floatval($_POST['total']) : 0; //付款总金额
            $data['total'] = intval($total);
            $data['order_id'] = isset($_POST['order_id']) ? $_POST['order_id'] : '';
            $data['supplier_id'] = isset($_POST['supplier_id']) ? intval($_POST['supplier_id']) : 0;
            $data['seller_id'] = isset($_POST['seller_id']) ? intval($_POST['seller_id']) : 0;
            $data['trade_no'] = isset($_POST['trade_no']) ? trim($_POST['trade_no']) : '';
            $data['salt'] = 'pigcms-weidian-fx-order-pay-to-supplier';
            $timestamp = isset($_POST['timestamp']) ? intval($_POST['timestamp']) : 0;
            $hash = isset($_POST['hash']) ? trim($_POST['hash']) : '';
            ksort($data);
            $hash_new = sha1(http_build_query($data));
            $now = time();
            if (($now - $timestamp) > 360) {
                json_return(1001, '请求已过期');
            } else if ($hash != $hash_new) {
                json_return(1002, '参数无效');
            } else {
                //付款给供货商
                $fx_order_id = explode(',', $data['order_id']); //合并支付会出现多个订单ID
                $supplier = $store->getStore($data['supplier_id']); //供货商
                //如果store_supplier中的seller_id字段值中有当前供货商并且type分销类型为1，则表示当前供货商同时也是分销商，则为其供货商添加分销订单
                $seller_info = $store_supplier->getSeller(array('seller_id' => $data['supplier_id'], 'type' => 1));
                if (!empty($seller_info)) {
                    $is_supplier = false;
                } else {
                    $is_supplier = true;
                }
                $seller = $store->getStore($this->store_session['store_id']); //分销商
                if ($total > 0) {
                    //供货商不可用余额和收入加商品成本
                    if ($store->setUnBalanceInc($data['supplier_id'], $total) && $store->setIncomeInc($data['supplier_id'], $total)) {
                        foreach ($fx_order_id as $id) {
                            //修改分销订单状态为等待供货商发货并且关联供货商订单id
                            $fx_order->edit(array('fx_order_id' => $id), array('status' => 2, 'paid_time' => time()));
                            $fx_order_info = $fx_order->getOrder($this->store_session['store_id'], $id); //分销订单详细
                            $order_id = $fx_order_info['order_id']; //主订单ID
                            //主订单分销商品
                            $fx_products = $order_product->getFxProducts($order_id, $id, $is_supplier);
                            $order_info = $order->getOrder($this->store_session['store_id'], $order_id);
                            $order_trade_no = $order_info['trade_no']; //主订单交易号
                            unset($order_info['order_id']);
                            $order_info['order_no']       = date('YmdHis',time()).mt_rand(100000,999999);
                            $order_info['store_id']       = $data['supplier_id'];
                            $order_info['trade_no']       = date('YmdHis',time()).mt_rand(100000,999999);
                            $order_info['third_id']       = '';
                            $order_info['uid']            = $this->user_session['uid']; //下单用户（分销商）
                            $order_info['session_id']     = '';
                            $order_info['postage']        = $fx_order_info['postage'];
                            $order_info['sub_total']      = $fx_order_info['cost_sub_total'];
                            $order_info['total']          = $fx_order_info['cost_total'];
                            $order_info['status']         = 2; //未发货
                            $order_info['pro_count']      = 0; //商品种类数量
                            $order_info['pro_num']        = $fx_order_info['quantity']; //商品件数
                            $order_info['payment_method'] = 'balance';
                            $order_info['type']           = 3; //分销订单
                            $order_info['add_time']       = time();
                            $order_info['paid_time']      = time();
                            $order_info['sent_time']      = 0;
                            $order_info['cancel_time']    = 0;
                            $order_info['complate_time']  = 0;
                            $order_info['refund_time']    = 0;
                            $order_info['star']           = 0;
                            $order_info['pay_money']      = $fx_order_info['cost_total'];
                            $order_info['cancel_method']  = 0;
                            $order_info['float_amount']   = 0;
                            $order_info['is_fx']          = 0;
                            $order_info['fx_order_id']    = $id; //关联分销商订单id（fx_order）
                            $order_info['user_order_id']  = $fx_order_info['user_order_id'];
                            if ($new_order_id = $order->add($order_info)) { //向供货商提交一个新订单
                                $suppliers = array();
                                foreach ($fx_products as $key => $fx_product) {
                                    unset($fx_product['pigcms_id']);
                                    //获取分销商品的来源
                                    $product_info = $product_model->get(array('product_id' => $fx_product['product_id']), 'source_product_id,original_product_id');
                                    if (!empty($product_info['source_product_id'])) {
                                        $fx_product['product_id'] = $product_info['source_product_id'];

                                        $properties = ''; //商品属性字符串
                                        if (!empty($fx_product['sku_data'])) {
                                            $sku_data = unserialize($fx_product['sku_data']);
                                            $skus = array();
                                            foreach($sku_data as $sku) {
                                                $skus[] = $sku['pid'] . ':' . $sku['vid'];
                                            }
                                            $properties = implode(';', $skus);
                                        }
                                        if (!empty($properties)) { //有属性
                                            $sku = $product_sku->getSku($fx_product['product_id'], $properties);
                                            $fx_product['pro_price'] = $sku['cost_price']; //分销来源商品的成本价格
                                            $fx_product['sku_id'] = $sku['sku_id'];
                                        } else { //无属性
                                            $source_product_info = $product_model->get(array('product_id' => $fx_product['product_id']), 'price,cost_price');
                                            $fx_product['pro_price'] = $source_product_info['cost_price']; //分销来源商品的成本价格
                                        }
                                    }

                                    $fx_product['order_id']          = $new_order_id;
                                    $fx_product['pro_price']         = $fx_product['price'];
                                    $fx_product['is_packaged']       = 0;
                                    $fx_product['in_package_status'] = 0;
                                    //判断是否是店铺自有商品
                                    $super_product_info = $product_model->get(array('product_id' => $product_info['source_product_id']), 'source_product_id,original_product_id');
                                    if (empty($seller_info) || empty($super_product_info['source_product_id'])) { //供货商或商品供货商
                                        $fx_product['is_fx']             = 0;
                                    } else {
                                        $fx_product['is_fx']             = 1;
                                    }
                                    unset($fx_product['price']);
                                    $order_product->add($fx_product); //添加新订单商
                                    $fx_products[$key]['pro_price'] = $fx_product['pro_price'];
                                    $fx_products[$key]['source_product_id'] = $fx_product['product_id'];
                                    $suppliers[] = $fx_product['supplier_id'];
                                }
                                //修改订单供货商
                                $suppliers = array_unique($suppliers);
                                $suppliers = implode(',', $suppliers);
                                D('Order')->where(array('order_id' => $new_order_id))->data(array('suppliers' => $suppliers))->save();

                                //添加供货商财务记录（收入）
                                $data_record = array();
                                $data_record['store_id']         = $data['supplier_id'];
                                $data_record['order_id'] 		 = $new_order_id;
                                $data_record['order_no'] 		 = $order_info['order_no'];
                                $data_record['income']  		 = $fx_order_info['cost_total'];
                                $data_record['type']    		 = 5; //分销
                                $data_record['balance']     	 = $supplier['income'];
                                $data_record['payment_method']   = 'balance';
                                $data_record['trade_no']         = $order_info['trade_no'];
                                $data_record['add_time']         = time();
                                $data_record['status']           = 1;
                                $data_record['user_order_id']    = $order_info['user_order_id'];
                                $financial_record_id = D('Financial_record')->data($data_record)->add();

                                //判断供货商，如果上级供货商是分销商，添加分销订单
                                if (!empty($seller_info)) {
                                    $cost_sub_total = 0;
                                    $sub_total = 0;
                                    $tmp_fx_products = array();
                                    foreach ($fx_products as $k => $fx_product) {
                                        $properties = ''; //商品属性字符串
                                        if (!empty($fx_product['sku_data'])) {
                                            $sku_data = unserialize($fx_product['sku_data']);
                                            $skus = array();
                                            foreach($sku_data as $sku) {
                                                $skus[] = $sku['pid'] . ':' . $sku['vid'];
                                            }
                                            $properties = implode(';', $skus);
                                        }
                                        //获取分销商品的来源
                                        $product_info = $product_model->get(array('product_id' => $fx_product['product_id']), 'source_product_id,original_product_id');
                                        $source_product_id = $product_info['source_product_id']; //分销来源商品
                                        $original_product_id = $product_info['original_product_id'];
                                        if (empty($source_product_id) || $original_product_id == $source_product_id) { //商品供货商或商品供货商为上级分销商
                                            unset($fx_products[$k]);
                                            continue;
                                        }
                                        $tmp_fx_product = $fx_product;
                                        if (!empty($seller_info) && !empty($product_info['original_product_id'])) {
                                            $product_info = $product_model->get(array('product_id' => $source_product_id), 'source_product_id,original_product_id');
                                            $source_product_id = $product_info['source_product_id']; //分销来源商品
                                        }
                                        if (!empty($properties)) { //有属性
                                            $sku = $product_sku->getSku($source_product_id, $properties);
                                            //$price = $sku['price'];
                                            $cost_price = $sku['cost_price']; //分销来源商品的成本价格
                                            $sku_id = $sku['sku_id'];
                                        } else { //无属性
                                            $source_product_info = $product_model->get(array('product_id' => $source_product_id), 'price,cost_price');
                                            //$price = $source_product_info['price'];
                                            $cost_price = $source_product_info['cost_price']; //分销来源商品的成本价格
                                            $sku_id = 0;
                                        }
                                        $cost_sub_total += $cost_price;
                                        $sub_total += $fx_product['pro_price'];
                                        $tmp_fx_product['product_id'] = $source_product_id;
                                        $tmp_fx_product['price'] = $fx_product['pro_price'];
                                        $tmp_fx_product['cost_price'] = $cost_price;
                                        $tmp_fx_product['sku_id'] = $sku_id;
                                        $tmp_fx_product['original_product_id'] = $original_product_id;
                                        $tmp_fx_products[] = $tmp_fx_product;
                                    }
                                    if (!empty($fx_products)) {
                                        $fx_order_no = date('YmdHis',$_SERVER['REQUEST_TIME']).mt_rand(100000,999999); //分销订单号
                                        //运费
                                        $fx_postages = array();
                                        if (!empty($order_info['fx_postage'])) {
                                            $fx_postages = unserialize($order_info['fx_postage']);
                                        }
                                        $postage = !empty($fx_postages[$seller_info['supplier_id']]) ? $fx_postages[$seller_info['supplier_id']] : 0;
                                        $data2 = array(
                                            'fx_order_no'      => $fx_order_no,
                                            'uid'              => $order_info['uid'],
                                            'order_id'         => $new_order_id,
                                            'order_no'         => $order_info['order_no'],
                                            'supplier_id'      => $seller_info['supplier_id'],
                                            'store_id'         => $data['supplier_id'],
                                            'quantity'         => $fx_order_info['quantity'],
                                            'sub_total'        => $sub_total,
                                            'cost_sub_total'   => $cost_sub_total,
                                            'postage'          => $postage,
                                            'total'            => ($sub_total + $postage),
                                            'cost_total'       => ($cost_sub_total + $postage),
                                            'delivery_user'    => $order_info['address_user'],
                                            'delivery_tel'     => $order_info['address_tel'],
                                            'delivery_address' => $order_info['address'],
                                            'add_time'         => time(),
                                            'user_order_id'    => $order_info['user_order_id']
                                        );
                                        if ($fx_order_id = $fx_order->add($data2)) { //添加分销商订单
                                            foreach ($tmp_fx_products as $tmp_fx_product) {
                                                if (!empty($tmp_fx_product['product_id'])) {
                                                    $product_info = D('Product')->field('store_id, original_product_id')->where(array('product_id' => $tmp_fx_product['original_product_id']))->find();
                                                    $tmp_supplier_id = $product_info['store_id'];
                                                    $fx_order_product->add(array('fx_order_id' => $fx_order_id, 'product_id' => $tmp_fx_product['product_id'], 'source_product_id' => $tmp_fx_product['source_product_id'], 'price' => $tmp_fx_product['price'], 'cost_price' => $tmp_fx_product['cost_price'], 'quantity' => $tmp_fx_product['pro_num'], 'sku_id' => $tmp_fx_product['sku_id'], 'sku_data' => $tmp_fx_product['sku_data'], 'comment' => $tmp_fx_product['comment']));
                                                }
                                            }
                                            if (!empty($tmp_supplier_id)) { //修改订单，设置分销商
                                                D('Fx_order')->where(array('fx_order_id' => $fx_order_id))->data(array('suppliers' => $tmp_supplier_id))->save();
                                            }
                                        }
                                        //获取分销利润
                                        if (!empty($financial_record_id) && !empty($data2['cost_total'])) {
                                            $profit = $data2['total'] - $data2['cost_total'];
                                            if ($profit > 0) {
                                                D('Financial_record')->where(array('pigcms_id' => $financial_record_id))->data(array('profit' => $profit))->save();
                                            }
                                        }
                                    }
                                }

                                //分销商不可用余额和收入减商品成本
                                if ($store->setUnBalanceDec($this->store_session['store_id'], $fx_order_info['cost_total']) && $store->setIncomeDec($this->store_session['store_id'], $fx_order_info['cost_total'])) {
                                    //添加分销商财务记录（支出）
                                    $order_no = $order_info['order_no'];
                                    $data_record = array();
                                    $data_record['store_id']         = $this->store_session['store_id'];
                                    $data_record['order_id'] 		 = $order_id;
                                    $data_record['order_no'] 		 = $order_no;
                                    $data_record['income']  		 = (0 - $fx_order_info['cost_total']);
                                    $data_record['type']    		 = 5; //分销
                                    $data_record['balance']     	 = $seller['income'];
                                    $data_record['payment_method']   = 'balance';
                                    $data_record['trade_no']         = $order_trade_no;
                                    $data_record['add_time']         = time();
                                    $data_record['status']           = 1;
                                    $data_record['user_order_id']    = $order_info['user_order_id'];
                                    D('Financial_record')->data($data_record)->add();
                                } else { //操作失败，记录日志文件
                                    $supplier_name = $supplier['name'];
                                    $seller_name = $seller['name'];
                                    $dir = './upload/pay/';
                                    if(!is_readable($dir))
                                    {
                                        is_file($dir) or mkdir($dir, 0777);
                                    }
                                    file_put_contents($dir . 'error.txt', '[' . date('Y-m-d H:i:s') . '] 付款给供货商失败，订单类型：分销，订单ID：' . $order_id . '，交易单号：' . $order_trade_no . '，供货商（收款方）：' . $supplier_name . '，分销商（付款方）：' . $seller_name . '，付款金额：' . $fx_order_info['cost_total'] . '元，请手动从 ' . $seller_name . ' 账户余额中减' . $fx_order_info['cost_total'] . '元' . PHP_EOL, FILE_APPEND );
                                    json_return(1005, '付款失败，请联系客服处理，交易单号：' . $order_trade_no);
                                }
                            }
                        }
                        json_return(0, '付款成功，等待供货商发货');
                    } else {
                        json_return(1004, '付款失败');
                    }
                } else {
                    json_return(1003, '付款金额无效');
                }
            }
        }

        $trade_no = isset($_GET['trade_no']) ? trim($_GET['trade_no']) : '';
        if (empty($trade_no)) {
            $html = '<div class="error-wrap"><h1>很抱歉，未找到交易号</h1><div class="description"></div><div class="error-code">错误代码： 10001</div><div class="action"><a href="javascript:window.history.go(-1);" class="ui-btn ui-btn-primary">返回</a></div></div>';
            $this->assign('trade_no_error', $html);
        } else if (!$fx_order->getOrderCount(array('fx_trade_no' => $trade_no))) {
            $html = '<div class="error-wrap"><h1>很抱歉，未找到交易号 ' . $trade_no . ' 关联的支付信息</h1><div class="description"></div><div class="error-code">错误代码： 10002</div><div class="action"><a href="javascript:window.history.go(-1);" class="ui-btn ui-btn-primary">返回</a></div></div>';
            $this->assign('trade_no_error', $html);
        }
        $this->assign('trade_no', $trade_no);
        $this->display();
    }

    private function _pay_order_content()
    {
        $store = M('Store');
        $fx_order = M('Fx_order');
        $order_product = M('Fx_order_product');

        $trade_no = isset($_POST['trade_no']) ? trim($_POST['trade_no']) : '';
        $tmp_orders = $fx_order->getOrders(array('fx_trade_no' => $trade_no));
        $orders = array();
        $total = 0;
        $sub_total = 0;
        $postage = 0;
        $supplier_id = 0;
        $seller_id = 0;
        $supplier_name = '';
        $pay = true;
        foreach ($tmp_orders as $tmp_order) {
            $supplier = $store->getStore($tmp_order['supplier_id']);
            $supplier_id = $tmp_order['supplier_id'];
            $seller_id = $tmp_order['store_id'];
            $supplier_name = $supplier['name'];
            $products = $order_product->getFxProducts($tmp_order['fx_order_id']);
            $orders[] = array(
                'fx_order_id' => $tmp_order['fx_order_id'],
                'fx_order_no' => option('config.orderid_prefix') . $tmp_order['fx_order_no'],
                'postage'     => $tmp_order['postage'],
                'total'       => $tmp_order['total'],
                'cost_total'  => $tmp_order['cost_total'],
                'supplier'    => $supplier_name,
                'products'    => $products
             );
            $total += $tmp_order['cost_total'];
            if ($tmp_order['status'] !=1) {
                $pay = false;
            }
            $postage += $tmp_order['postage'];
        }

        $this->assign('trade_no', $trade_no);
        $this->assign('orders', $orders);
        $this->assign('total', number_format($total, 2, '.', ''));
        $this->assign('supplier', $supplier_name);
        $this->assign('supplier_id', $supplier_id);
        $this->assign('seller_id', $seller_id);
        $this->assign('pay', $pay);
        $this->assign('postage', number_format($postage, 2, '.', ''));
    }

    //我的供货商
    public function supplier()
    {
        //排他分销
        $seller = D('Store_supplier')->where(array('seller_id' => $this->store_session['store_id'], 'type' => 1))->count('pigcms_id');
        if ($seller) {
            $is_seller = true;
        } else {
            $is_seller = false;
        }

        $this->assign('is_seller', $is_seller);

        $this->display();
    }

    private function _supplier_content()
    {
        $store_supplier = M('Store_supplier');
        $store = M('Store');

        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $level = isset($_POST['level']) ? trim($_POST['level']) : 1;    //供货商等级
        $sellerId = $this->store_session['store_id'];    //当前分销商id

        $where = array();

        /* 获取当前分销商信息 */
        $sellerInfo = $store_supplier->getSeller(array(
            'seller_id'=>$sellerId
        ));

        $supplyChain= explode(',', $sellerInfo['supply_chain']);
        $sellerIdList = rtrim(implode(',', $supplyChain), ',');

        $where['store_id'] = array ('in'=> $sellerIdList);

        if (!empty($keyword)) {
            $where['name'] = array('like' => '%' . $keyword . '%');
        }

        $supplier_count = $store_supplier->supplier_count($where);

        import('source.class.user_page');
        $page = new Page($supplier_count, 15);
        $suppliers = $store_supplier->suppliers($where, $page->firstRow, $page->listRows);

        $this->assign('suppliers', $suppliers);
        $this->assign('page', $page->show());
    }

    //我的商品
    public function supplier_goods()
    {
        $this->display();
    }

    private function _supplier_goods_content()
    {
        $product = M('Product');
        $product_group = M('Product_group');
        $product_to_group = M('Product_to_group');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $is = isset($_POST['is']) ? intval($_POST['is']) : 1;

        $where = array();
        //$where['store_id'] = $this->store_session['store_id'];
        if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        $where['buy_way'] = 1; //站内商品

        if($is == 1)
        {
            //$where['is_fx'] = 1; // 可分销
            $where['_string'] = "(`store_id` = " .$this->store_session['store_id']. " and `is_fx` = " . 0 . ") or `wholesale_product_id` > " . '0' . " and `is_fx` = " . '0' . " and `store_id` = " .$this->store_session['store_id'];
        }
        else if($is == 2)
        {
            $where['_string'] = "`store_id` = " .$this->store_session['store_id']. " and`wholesale_product_id` = " .'0'. "  and `is_wholesale` = " . 0;
           // $where['is_wholesale'] = 1 ; // 可批发
        }

        $product_total = $product->getSellingTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        $products = $product->getSelling($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);

        $this->assign('page', $page->show());
        $this->assign('products', $products);
        $this->assign('is', $is);
    }

    //商品市场
    public function supplier_market()
    {
        if (IS_POST) {
            $product = M('Product');

            $products = isset($_POST['products']) ? trim($_POST['products']) : '';
            $is = isset($_POST['is']) ? trim($_POST['is']) : 1;
            if($is == 1)
            {
                if (!empty($products) && $product->fxCancel(array ('store_id' => $this->store_session['store_id'], 'product_id' => array ('in', explode(',', $products)))))
                {
                    $products = explode(',', $products);
                    foreach ($products as $product_id)
                    {
                        $product_info = $product->get(array ('product_id' => $product_id), 'store_id,product_id,source_product_id,original_product_id');
                        if (!empty($product_info) && $this->store_session['store_id'] == $product_info['store_id'] && empty($product_info['original_product_id']))
                        {
                            //$product->edit(array('original_product_id' => $product_info['product_id']), array('status' => 2)); //修改状态为删除
                            $this->_cancel_fx_product($product_info['product_id']);
                        }
                        else if (!empty($product_info) && $this->store_session['store_id'] == $product_info['store_id'] && !empty($product_info['original_product_id']))
                        {
                            //递归处理下级分销商分销的该商品
                            $this->_cancel_fx_product($product_info['product_id']);
                        }
                    }

                    json_return(0, '操作成功');
                }
                else
                {
                    json_return(1001, '操作失败');
                }
            }else if($is == 2){
                if (!empty($products) && $product->wholesaleCancel(array ('store_id' => $this->store_session['store_id'], 'product_id' => array ('in', explode(',', $products)))))
                {
                    $products = explode(',', $products);
                    foreach ($products as $product_id)
                    {
                        $product_info = $product->get(array ('product_id' => $product_id), 'store_id,product_id,source_product_id,original_product_id');
                        if (!empty($product_info) && $this->store_session['store_id'] == $product_info['store_id'] && empty($product_info['original_product_id']))
                        {
                            //$product->edit(array('original_product_id' => $product_info['product_id']), array('status' => 2)); //修改状态为删除
                            $this->_cancel_fx_product($product_info['product_id']);
                        }
                        else if (!empty($product_info) && $this->store_session['store_id'] == $product_info['store_id'] && !empty($product_info['original_product_id']))
                        {
                            //递归处理下级分销商分销的该商品
                            $this->_cancel_fx_product($product_info['product_id']);
                        }
                    }

                    json_return(0, '操作成功');
                }
                else
                {
                    json_return(1001, '操作失败');
                }
            }
        }
        $this->display();
    }

    //同步微页面商品
    private function _sync_wei_page_goods($product_id, $store_id = '')
    {
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
                    foreach($products['goods'] as $product){
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

    //递归取消商品分销
    private function _cancel_fx_product($product_id)
    {
        /*$tmp_product_info = $product->get(array('source_product_id' => $product_id), 'store_id,product_id,source_product_id,original_product_id');
        if (!empty($tmp_product_info)) {
            $product->edit(array('product_id' => $tmp_product_info['product_id']), array('status' => 2)); //修改状态为删除
            $this->_supplier_content($tmp_product_info['product_id'], $tmp_product_info['store_id']);
            $this->_cancel_fx_product($product, $tmp_product_info['product_id']);
        }*/
        $products = D('Product')->where(array('source_product_id' => $product_id))->select();
        if (!empty($products)) {
            foreach ($products as $product) {
                M('Product')->edit(array('product_id' => $product['product_id']), array('status' => 2)); //修改状态为删除
                $this->_sync_wei_page_goods($product['product_id'], $product['store_id']);
                $this->_cancel_fx_product($product['product_id']);
            }
        }
    }

    //分销商品市场
    private function _supplier_market_content()
    {
        $product = M('Product');
        $product_group = M('Product_group');
        $product_to_group = M('Product_to_group');

        $order_by_field = 'is_fx';
        $order_by_method = 'DESC';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';

        $is = isset($_POST['is']) ? intval($_POST['is']) : 3;
        $where = array();
        $where['store_id'] = $this->store_session['store_id'];
        $where['status'] = 1;
        $version = option('config.weidian_version'); // 0 微店 1 v.meihua

        if ($is == 1)
        {
            if ($keyword)
            {
                $where['_string'] = "( `status` = " . '1' . " and `store_id` = " . $this->store_session['store_id'] . " and `is_fx` = " . 1 . " and `is_wholesale` != " . 1 . " and `name` like '" . "%" . $keyword . "%' ) or ( `is_wholesale` = " . '1' . " and `status` = " . '1' . " and `is_fx` = " . '1' . " and `store_id` = " . $this->store_session['store_id'] . " and  `name` like '" . '%' . $keyword . "%')";
            }
            else
            {
                $where['_string'] = "( `status` =" . '1' . " and `store_id` = " . $this->store_session['store_id'] . " and `is_fx` = " . 1 . " and `is_wholesale` != " . 1 . " ) or `is_wholesale` = " . '1' . " and `is_fx` = " . '1' . " and `store_id` = " . $this->store_session['store_id'] . " and `status` =" . '1';
            }
        }
        else if ($is == 2)
        {
            if ($keyword)
            {
                $where['_string'] = "`store_id` = " . $this->store_session['store_id'] . " and`wholesale_product_id` = " . '0' . "  and `is_wholesale` = " . '1' . " and `name` like '%" . $keyword . "%'";
            }
            else
            {
                $where['_string'] = "`store_id` = " . $this->store_session['store_id'] . " and`wholesale_product_id` = " . '0' . "  and `is_wholesale` = " . 1;
            }
        }
        else if ($is == 3)
        {
            if(empty($version)) // 微店
            {
                if ($keyword)
                {
                    //$where['_string'] = "(`status` = " . '1' . " and `name` like '%" . $keyword . "%' and`store_id` = " . $this->store_session['store_id'] . " and  `is_fx` = " . 0 . " and `wholesale_product_id` = " . '0 ) or (' . "`store_id` = " . $this->store_session['store_id'] . " and `status` = " . '1' . " and  `is_wholesale` = " . 0 . " and `name` like '%" . $keyword . "%' and `wholesale_product_id` = " . '0 ) or (' . "`store_id` = " . $this->store_session['store_id'] . " and `name` like '%" . $keyword . "%' and `is_wholesale` = " . '0' . " and `status` = " . '1' . " and  `is_fx` = " . 0 . " and `wholesale_product_id` > " . '0 )';
                    $where['_string'] = "`status` = " . '1' . " and `name` like '%" . $keyword . "%' and`store_id` = " . $this->store_session['store_id'];
                }
                else
                {
                    //$where['_string'] = "(`status` = " . '1' . " and `store_id` = " . $this->store_session['store_id'] . " and  `is_fx` = " . 0 . " and `wholesale_product_id` = " . '0 ) or (' . "`store_id` = " . $this->store_session['store_id'] . " and  `is_wholesale` = " . 0 . " and `status` =" . '1' . " and `wholesale_product_id` = " . '0 ) or (' . "`store_id` = " . $this->store_session['store_id'] . " and `status` =" . '1' . " and `is_wholesale` = " . '0' . " and  `is_fx` = " . 0 . " and `wholesale_product_id` > " . '0 )';
                    $where['_string'] = "`status` = " . '1' . " and `store_id` = " . $this->store_session['store_id'];
                }
            }
            else // v.meihua
            {
                if ($keyword)
                {
                    $where['_string'] = "`status` = " . '1' . " and `name` like '%" . $keyword . "%' and`store_id` = " . $this->store_session['store_id'];
                }
                else
                {
                    $where['_string'] = "`status` = " . '1' . " and `store_id` = " . $this->store_session['store_id'];
                }
            }
        }

        if (!empty($_POST['category_id'])) {
            $where['category_id'] = intval(trim($_POST['category_id']));
        }
        if (!empty($_POST['category_fid'])) {
            $where['category_fid'] = intval(trim($_POST['category_fid']));
        }

        $product_total = $product->getSellingTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        $products = $product->getSelling($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);

        //商品分类
        $category = M('Product_category');
        $categories = $category->getCategories(array('cat_status'=>1),'cat_path ASC');

        $this->assign('page', $page->show());
        $this->assign('products', $products);
        $this->assign('categories', $categories);
        $this->assign('is', $is);
    }

    //我的分销商
    public function seller()
    {
        $this->display();
    }

    private function _seller_content()
    {
        $store_supplier = M('Store_supplier');
        $store = M('Store');
        $order = M('Order');
        $financial_record = M('Financial_record');

        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $approve = isset($_POST['approve']) ? trim($_POST['approve']) : '*';
        $level = intval(isset($_POST['level']) ? trim($_POST['level']) : '1');

        $seller_id = !empty($_POST['seller_id']) ? intval(trim($_POST['seller_id'])) : '';
        $supplierId = $this->store_session['store_id'];

        if (!empty($seller_id))
        {
            list($sellerList) = $store_supplier->sellers(array(
                'seller_id' => $seller_id
            ));
        }

        $where = array();
        $where['ss.supplier_id'] = $this->store_session['store_id'];
      //  $where['s.status'] = 1;
        $where['s.status'] = array('>', 0);


        if ($keyword != '') {
            $where['s.name'] = array('like' => '%' . $keyword . '%');
        }
        if (is_numeric($approve) || $approve != '*') {
            $where['s.drp_approve'] = $approve;
        }
        if (!empty($_SESSION['store_sync'])) {
            $where['ss.type'] = 1;
        }

        // 判断当前登录帐号等级
        $currentLevel = $store_supplier->getSeller(array(
            'supplier_id' => $supplierId
        ));

        if (isset($currentLevel['level']) && $level ==1)
        {
            $where['ss.level'] = $currentLevel['level'];
            $where['ss.supplier_id'] = $supplierId;
           
        }
        else
        {
            $sellerList = $store_supplier->getNextSellers($supplierId, $currentLevel['level'] == 1 ? $level-1 :($currentLevel['level']-($level==2 ? '0' : $level-1)));

            if (count($sellerList)>0)
            {
                foreach ($sellerList as $sellerId)
                {
                    $sellerIdList[] = $sellerId['seller_id'];
                }

                $sellerIdList = rtrim(implode(',', $sellerIdList), ',');

            }
            $where['ss.supplier_id'] = array ('in' => $sellerIdList);
        }

        $seller_count = $store_supplier->seller_count($where);
        import('source.class.user_page');
        $page = new Page($seller_count, 15);
        $tmp_sellers = $store_supplier->sellers($where, $page->firstRow, $page->listRows);
        $sellers = array();
        foreach ($tmp_sellers as $tmp_seller) {
            $sales = $order->getSales(array('store_id' => $tmp_seller['store_id'], 'is_fx' => 1, 'status' => array('in', array(2,3,4,7))));
            $profit = $tmp_seller['income'];
            $sellers[] = array(
                'store_id'       => $tmp_seller['store_id'],
                'name'           => $tmp_seller['name'],
                'service_tel'    => $tmp_seller['service_tel'],
                'service_qq'     => $tmp_seller['service_qq'],
                'service_weixin' => $tmp_seller['service_weixin'],
                'drp_approve'    => $tmp_seller['drp_approve'],
                'date_added'    => $tmp_seller['date_added'],
                'drp_level'    => $tmp_seller['drp_level'],
                'status'         => $tmp_seller['status'],
                'sales'          => !empty($sales) ? number_format($sales, 2, '.', '') : '0.00',
                'profit'         => !empty($profit) ? number_format($profit, 2, '.', '') : '0.00'
            );
        }

        $this->assign('level', $level);
        $this->assign('sellerList', $sellerList);
        $this->assign('sellers', $sellers);
        $this->assign('page', $page->show());
    }

    /* 下两级分销商导航 */
    public function next_seller()
    {
        $store_supplier = M('Store_supplier');

        $supplier = $store_supplier->getSeller(array (
            'seller_id' => $this->store_session['store_id']
        ));

        $this->assign('level', $supplier['level']);
        $this->display();
    }

    /* 下两级分销商列表 */
    private function _next_seller_content()
    {
        $store_supplier = M('Store_supplier');
        $store = M('Store');
        $fx_order = M('Fx_order');
        $financial_record = M('Financial_record');

        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $approve = isset($_POST['approve']) ? trim($_POST['approve']) : '*';
        $supplierId = $this->store_session['store_id'];
        $level = intval(isset($_POST['level']) ? trim($_POST['level']) : '1');

        $where = array();
        $where['s.status'] = array('>', 0);

        if ($keyword != '') {
            $where['s.name'] = array('like' => '%' . $keyword . '%');
        }
        if (is_numeric($approve) || $approve != '*') {
            $where['s.drp_approve'] = $approve;
        }
        if (!empty($_SESSION['store_sync'])) {
            $where['ss.type'] = 1;
        }

        // 判断当前登录帐号等级
        $currentLevel = $store_supplier->getSeller(array(
            'seller_id' => $supplierId
        ));
        if($level == 1)
        {
            $sellerList = $store_supplier->getNextSellers($supplierId, $currentLevel['level']+1);
        }
        else
        {
            $sellerList = $store_supplier->getNextSellers($supplierId,$level);
        }

        if (count($sellerList)>0)
         {
            foreach ($sellerList as $sellerId)
            {
                $sellerIdList[] = $sellerId['seller_id'];
            }

            $sellerIdList = rtrim(implode(',', $sellerIdList), ',');
         }
        $where['ss.seller_id'] = array ('in' => $sellerIdList);

        $seller_count = $store_supplier->seller_count($where);
        import('source.class.user_page');
        $page = new Page($seller_count, 15);
        $tmp_sellers = $store_supplier->sellers($where, $page->firstRow, $page->listRows);
        $sellers = array();
        foreach ($tmp_sellers as $tmp_seller) {
            $sales = $fx_order->getSales(array('store_id' => $tmp_seller['store_id'], 'status' => array('in', array(1,2,3,4))));
            //$profit = $financial_record->drpProfit(array('store_id' => $tmp_seller['store_id']));
            $profit = $tmp_seller['drp_profit'];
            $sellers[] = array(
                'store_id'       => $tmp_seller['store_id'],
                'name'           => $tmp_seller['name'],
                'service_tel'    => $tmp_seller['service_tel'],
                'service_qq'     => $tmp_seller['service_qq'],
                'service_weixin' => $tmp_seller['service_weixin'],
                'drp_approve'    => $tmp_seller['drp_approve'],
                'drp_level'    => $tmp_seller['drp_level'],
                'date_added'    => $tmp_seller['date_added'],
                'status'         => $tmp_seller['status'],
                'sales'          => !empty($sales) ? number_format($sales, 2, '.', '') : '0.00',
                'profit'         => !empty($profit) ? number_format($profit, 2, '.', '') : '0.00'
            );
        }

        $this->assign('sellerList', $sellerList);
        $this->assign('sellers', $sellers);
        $this->assign('page', $page->show());
    }

    /* 分销订单列表 */
    public function seller_order()
    {
        $this->display();
    }

    public function seller_order_content() {
        $order = M('Order');
        $order_product = M('Order_product');
        $user = M('User');
        $where = array();
        $where['store_id'] = $this->store_session['store_id'];
        $where['_string'] = '`type` = 3';

        if (is_numeric($_POST['order_no'])) {
            $where['order_no'] = $_POST['order_no'];
        }

        if (!empty($_POST['delivery_user'])) {
            $where['address_user'] = $_POST['delivery_user'];
        }
        if (!empty($_POST['delivery_tel'])) {
            $where['address_tel'] = $_POST['delivery_tel'];
        }


        $field = '';
        if (!empty($data['time_type'])) {
            $field = $data['time_type'];
        }
        if (!empty($data['start_time']) && !empty($_POST['stop_time']) && !empty($field)) {
            $where['_string'] = "`" . $field . "` >= " . strtotime($_POST['start_time']) . " AND `" . $field . "` <= " . strtotime($_POST['stop_time']);
        } else if (!empty($_POST['start_time']) && !empty($field)) {
            $where[$field] = array('>=', strtotime($data['start_time']));
        } else if (!empty($_POST['stop_time']) && !empty($field)) {
            $where[$field] = array('<=', strtotime($_POST['stop_time']));
        }
        //排序
        if (!empty($_POST['orderbyfield']) && !empty($_POST['orderbymethod'])) {
            $orderby = "`{$_POST['orderbyfield']}` " . $_POST['orderbymethod'];
        } else {
            $orderby = '`order_id` DESC';
        }
        $order_total = $order->getOrderTotal($where);
        import('source.class.user_page');
        $page = new Page($order_total, 15);
        $tmp_orders = $order->getOrders($where, $orderby, $page->firstRow, $page->listRows);
        $orders = array();
        $tmp_order_id = array();
        foreach ($tmp_orders as $tmp_order) {
            $products = $order_product->getProducts($tmp_order['order_id']);
            $tmp_order['products'] = $products;
            if (empty($tmp_order['uid'])) {
                $tmp_order['is_fans'] = false;
                $tmp_order['buyer'] = '';
            } else {
                //$tmp_order['is_fans'] = $user->isWeixinFans($tmp_order['uid']);
                $tmp_order['is_fans'] = true;
                $user_info = $user->checkUser(array('uid' => $tmp_order['uid']));
                $tmp_order['buyer'] = $user_info['nickname'];
            }

            // 是否有退货未完成的申请，有未完成的申请，暂时不给完成订单
            if ($tmp_order['status'] == 7) {
                $count = D('Return')->where("order_id = '" . $tmp_order['order_id'] . "' AND status IN (1, 3, 4)")->count('id');
                $tmp_order['returning_count'] = $count;
            }


            $is_supplier = false;
            $is_packaged = false;
            $is_assigned = false;
            if (!empty($tmp_order['suppliers'])) { //订单供货商
                $suppliers = explode(',', $tmp_order['suppliers']);
                if (in_array($this->store_session['store_id'], $suppliers)) {
                    $is_supplier = true;
                }
            }
            if (empty($tmp_order['suppliers'])) {
                $is_supplier = true;
            }

            $has_my_product = false;
            foreach ($products as &$product) {
                $product['image'] = getAttachmentUrl($product['image']);
                if (empty($product['is_fx'])) {
                    $has_my_product = true;
                }

                //自营商品
                if (!empty($product['supplier_id']) && $product['store_id'] == $this->store_session['store_id']) {
                    $is_supplier = true;
                }

                //商品来源
                if (empty($product['supplier_id'])) { //本店商品
                    $from = '本店商品';
                } else if (!empty($product['supplier_id']) && $product['store_id'] == $this->store_session['store_id'] && !empty($product['wholesale_product_id'])) { //批发商品
                    $from = '批发商品';
                } else { //分销商品
                    $from = '分销商品';
                }
                $product['from'] = $from;
                $product['cost_price'] = ($product['pro_price'] - $product['profit'] > 0) ? $product['pro_price'] - $product['profit'] : 0;
                if ($product['profit'] == 0 && empty($product['supplier_id'])) {
                    $product['profit'] = $product['pro_price'];
                }
                $product['cost_price'] = number_format($product['cost_price'], 2, '.', '');
            }

            if (!empty($tmp_order['user_order_id'])) {
                $order_info = D('Order')->field('store_id')->where(array('order_id' => $tmp_order['user_order_id']))->find();
                $seller = D('Store')->field('name,drp_level')->where(array('store_id' => $order_info['store_id']))->find();
                $tmp_order['seller'] = $seller['name'];
                $tmp_order['drp_level'] = $seller['drp_level'];
            }

            $un_package_selfsale_products = $order_product->getUnPackageProducts(array('op.order_id' => $tmp_order['order_id'], 'p.store_id' => $this->store_session['store_id'], 'p.supplier_id' => 0));
            if (count($un_package_selfsale_products) == 0) {
                $is_packaged = true;
            }

            // TODO 是否分配完毕
            $un_package_physical_products = $order_product->getUnPackageSkuProducts($tmp_order['order_id']);
            if (count($un_package_physical_products) == 0) {
                $is_assigned = true;
            }

            $profit = M('Financial_record')->getTotal(array('order_id' => $tmp_order['order_id']));
            $cost = M('Financial_record')->getTotal(array('order_id' => $tmp_order['order_id'], 'income' => array('<', 0)));
            $cost = abs($cost);
            if ($cost <= 0) {
                $cost = $profit;
            }

            $tmp_order_id[] = $tmp_order['order_id'];
            $tmp_order['products'] = $products;
            $tmp_order['has_my_product'] = $has_my_product;
            $tmp_order['is_supplier'] = $is_supplier;
            $tmp_order['is_packaged'] = $is_packaged;
            $tmp_order['is_assigned'] = $is_assigned;
            $tmp_order['profit'] = number_format($profit, 2, '.', '');
            $tmp_order['cost'] = number_format($cost, 2, '.', '');
            $orders[] = $tmp_order;
        }

        foreach ($tmp_order_id as $detail_order) {
            $order_id = $detail_order;

            $order_profit_info = $order->getOrder($this->store_session['store_id'], $order_id);
            $user_order_id = !empty($order_profit_info['user_order_id']) ? $order_profit_info['user_order_id'] : $order_id;

            $order_detail_info[$user_order_id] = D('Order')->where(array('order_id'=>$user_order_id))->find();

            $where = array();
            $where['_string'] = "(order_id = '" . $user_order_id . "' OR user_order_id = '" . $user_order_id . "')";
            if (!empty($this->store_session['drp_supplier_id'])) {
                if (empty($order_profit_info['user_order_id'])) {
                    $tmp_profit_order = D('Order')->field('order_id')->where(array('order_id' => $order_id, 'order_id' => array('>', $order_id)))->order('order_id ASC')->find();
                } else {
                    $tmp_profit_order = D('Order')->field('order_id')->where(array('user_order_id' => $user_order_id, 'order_id' => array('>', $order_id)))->order('order_id ASC')->find();
                }
                $tmp_order_id = $tmp_profit_order['order_id'];
                $where['_string'] .= " AND order_id <= " . $tmp_order_id;
            }
            $tmp_order_detail = D('Order')->where($where)->order('order_id DESC')->select();

            $filter_postage = array();
            $filter_order = array();
            $filter_products = array();
            foreach ($tmp_order_detail as $key => &$tmp_profit_order) {
                $is_filter = false;
                $store = D('Store')->field('store_id,name,drp_level,drp_supplier_id')->where(array('store_id' => $tmp_profit_order['store_id']))->find();
                $tmp_profit_order['seller'] = $store['name'];
                $tmp_profit_order['seller_drp_level'] = $store['drp_level'];

                if (empty($tmp_profit_order['suppliers']) && empty($order_profit_info['suppliers']) && $tmp_profit_order['store_id'] != $this->store_session['store_id']) {
                    $filter_postage[$tmp_profit_order['store_id']] = $tmp_profit_order['postage'];
                    $filter_order[$tmp_profit_orde['store_id']] = $tmp_profit_order['order_id'];
                    $is_filter = true;
                    unset($tmp_order_detail[$key]); //过滤非当前店铺的订单
                }

                if (!$is_filter && !empty($tmp_profit_order['suppliers'])) {
                    $suppliers = explode(',', $tmp_profit_order['suppliers']);
                    foreach ($filter_postage as $supplier_id => $postage) {
                        if (in_array($supplier_id, $suppliers)) {
                            $tmp_profit_order['postage'] -= $postage;
                            $tmp_profit_order['total'] -= $postage;
                            $tmp_profit_order['profit'] -= $postage;

                            $filter_order_id = $filter_order[$supplier_id];
                            $tmp_filter_products = $order_product->getProducts($filter_order_id);
                            foreach ($tmp_filter_products as $tmp_product) {
                                $filter_products[] = $tmp_product['product_id'];
                            }
                        }
                    }
                }

                $profit = D('Financial_record')->where(array('order_id' => $tmp_profit_order['order_id']))->sum('income');
                $tmp_profit_order['profit'] = number_format($profit, 2, '.', '');
                $tmp_profit_order['seller_store'] = option('config.wap_site_url') . '/home.php?id=' . $tmp_profit_order['store_id'];

                $products = $order_product->getProducts($tmp_profit_order['order_id']);
                $comment_count = 0;
                $product_count = 0;
                foreach ($products as $key2 => &$product) {

                    //过滤商品
                    if (in_array($product['original_product_id'], $filter_products)) {
                        $tmp_profit_order['sub_total'] -= ($product['pro_price'] * $product['pro_num']);
                        $tmp_profit_order['total'] -= ($product['pro_price'] * $product['pro_num']);
                        $tmp_profit_order['profit'] -= ($product['profit'] * $product['pro_num']);
                        unset($products[$key2]);
                    } else {
                        if (!empty($product['comment'])) {
                            $comment_count++;
                        }
                        $product_count++;

                        //商品来源
                        if (empty($product['supplier_id']) && $product['store_id'] == $tmp_profit_order['store_id']) { //本店商品
                            $from = '自营商品';
                        } else if (!empty($product['supplier_id']) && $product['store_id'] == $tmp_profit_order['store_id'] && !empty($product['wholesale_product_id'])) { //批发商品
                            $from = '批发商品';
                        } else { //分销商品
                            $from = '分销商品';
                        }
                        $product['from'] = $from;

                        $product['cost_price'] = ($product['pro_price'] - $product['profit'] > 0) ? $product['pro_price'] - $product['profit'] : 0;
                        if ($product['profit'] == 0 && empty($product['supplier_id'])) {
                            $product['profit'] = $product['pro_price'];
                        }
                        $product['cost_price'] = number_format($product['cost_price'], 2, '.', '');
                        $tmp_profit_order['cost_price'] = $product['cost_price'];
                        $tmp_profit_order['pro_price'] = $product['pro_price'];
                        if (!empty($product['wholesale_product_id']) && $product['store_id'] == $tmp_profit_order['store_id']) {
                            $tmp_profit_order['is_wholesale'] = true;
                        }
                    }
                }
            }
            $tmp_order_detai[$order_id][] = $tmp_order_detail;
        }

        //订单状态
        $order_status = $order->status();

        //支付方式
        $payment_method = $order->getPaymentMethod();
        $this->assign('order_status', $order_status);
        $this->assign('status', $data['status']);
        $this->assign('payment_method', $payment_method);
        $this->assign('orders', $orders);
        $this->assign('orders_detail', $tmp_order_detai);
        $this->assign('page', $page->show());
        $this->assign('suppliers', $suppliers);
        $this->assign('status', $status);
        $this->assign('store_info', $store_info);
        $this->assign('order_detail_info',$order_detail_info);
        $this->assign('order_no_list', $orderNoList);
    }

    //设置分销
    public function goods_fx_setting() {
	if (IS_POST) {
	    $product = M('Product');
	    $product_sku = M('Product_sku');

	    $product_id = !empty($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
	    $cost_price = !empty($_POST['cost_price']) ? floatval(trim($_POST['cost_price'])) : 0;
	    $min_fx_price = !empty($_POST['min_fx_price']) ? floatval(trim($_POST['min_fx_price'])) : 0;
	    $max_fx_price = !empty($_POST['max_fx_price']) ? floatval(trim($_POST['max_fx_price'])) : 0;
	    $is_recommend = !empty($_POST['is_recommend']) ? intval(trim($_POST['is_recommend'])) : 0;
	    $unified_price_setting = !empty($_POST['unified_price_setting']) ? $_POST['unified_price_setting'] : 0;
	    $is_fx_setting = 1;
	    $type = !empty($_GET['type']) ? $_GET['type'] : '';
	    $skus = !empty($_POST['skus']) ? $_POST['skus'] : array();
	    $fx_type = 0; //分销类型 0全网、1排他
	    if (strtolower(trim($_GET['role'])) == 'seller' || !empty($this->store_session['drp_supplier_id'])) {
		$fx_type = 1;
	    }
	    $data = array(
		'cost_price' => $cost_price,
		'min_fx_price' => $min_fx_price,
		'max_fx_price' => $max_fx_price,
		'is_recommend' => $is_recommend,
		'is_fx' => 1, // 1 为已分销商品
		'fx_type' => $fx_type,
		'is_fx_setting' => $is_fx_setting,
		'unified_price_setting' => $unified_price_setting
	    );
	    $product_info = M('Product')->get(array('product_id' => $product_id, 'store_id' => $_SESSION['store']['store_id']));
	    //分销级别
	    if (!empty($_SESSION['store']['drp_level'])) {
		$drp_level = $_SESSION['store']['drp_level'] + 1;
	    } else {
		$drp_level = 1;
	    }
	    if ($drp_level > 3) { //超出三级分销商
		$drp_level = 3;
	    }
	    if (!empty($unified_price_setting) && empty($product_info['source_product_id'])) {
		$data['cost_price'] = !empty($_POST['drp_level_' . $drp_level . '_cost_price']) ? $_POST['drp_level_' . $drp_level . '_cost_price'] : 0;
		$data['min_fx_price'] = !empty($_POST['drp_level_' . $drp_level . '_price']) ? $_POST['drp_level_' . $drp_level . '_price'] : 0;
		$data['max_fx_price'] = !empty($_POST['drp_level_' . $drp_level . '_price']) ? $_POST['drp_level_' . $drp_level . '_price'] : 0;
		$data['drp_level_1_cost_price'] = !empty($_POST['drp_level_1_cost_price']) ? $_POST['drp_level_1_cost_price'] : 0;
		$data['drp_level_2_cost_price'] = !empty($_POST['drp_level_2_cost_price']) ? $_POST['drp_level_2_cost_price'] : 0;
		$data['drp_level_3_cost_price'] = !empty($_POST['drp_level_3_cost_price']) ? $_POST['drp_level_3_cost_price'] : 0;
		$data['drp_level_1_price'] = !empty($_POST['drp_level_1_price']) ? $_POST['drp_level_1_price'] : 0;
		$data['drp_level_2_price'] = !empty($_POST['drp_level_2_price']) ? $_POST['drp_level_2_price'] : 0;
		$data['drp_level_3_price'] = !empty($_POST['drp_level_3_price']) ? $_POST['drp_level_3_price'] : 0;
		$result = $product->fxEdit($product_id, $data);
	    } else if (!empty($product_info['unified_price_setting'])) {
		$result = D('Product')->where(array('product_id' => $product_id))->data($data)->save();
	    } else {
		$result = $product->fxEdit($product_id, $data);
	    }
	    if ($result) {
		if (!empty($skus)) {
		    $product_sku->fxEdit($product_id, $skus, $unified_price_setting);
		}
		if ($type == 1) {
		    json_return(0, $type);
		} else {
		    json_return(0, url('supplier_market'));
		}
	    } else {
		json_return(1001, '保存失败');
	    }
	}
	$this->display();
    }

    private function _goods_fx_setting_content() {
	$product = M('Product');
	$category = M('Product_category');
	$product_property = M('Product_property');
	$product_property_value = M('Product_property_value');
	$product_to_property = M('Product_to_property');
	$product_to_property_value = M('Product_to_property_value');
	$product_sku = M('Product_sku');
	$id = isset($_POST['id']) ? intval(trim($_POST['id'])) : 0;
	$product = $product->get(array('product_id' => $id, 'store_id' => $this->store_session['store_id']));
	if (!empty($product['supplier_id'])) { //分销商
	    $edit_cost_price = false;
	    $readonly = '';
	} else { //供货商
	    $edit_cost_price = true;
	    $readonly = '';
	}
	if (!empty($product['category_id']) && !empty($product['category_fid'])) {
	    $parent_category = $category->getCategory($product['category_fid']);
	    $category = $category->getCategory($product['category_id']);
	    $product['category'] = $parent_category['cat_name'] . ' - ' . $category['cat_name'];
	} else if ($product['category_fid']) {
	    $category = $category->getCategory($product['category_fid']);
	    $product['category'] = $category['cat_name'];
	} else {
	    $category = $category->getCategory($product['category_id']);
	    $product['category'] = !empty($category['cat_name']) ? $category['cat_name'] : '其它';
	}


	$pids = $product_to_property->getPids($this->store_session['store_id'], $id);
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
		$html .= '        <th class="text-center" width="80">' . $name . '</th>';
		$html .= '        <th class="text-center" width="80">' . $name1 . '</th>';
		$html .= '        <th class="text-center" width="80">' . $name2 . '</th>';
		$html .= '        <th class="th-price" style="width: 70px;text-align: center">成本价（元）</th>';
		$html .= '        <th class="th-price" style="width: 105px;text-align: center">建议售价（元）</th>';
		$html .= '    </tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		$html2 = '<thead>';
		$html2 .= '    <tr>';
		$html2 .= '        <th class="text-center" width="80">' . $name . '</th>';
		$html2 .= '        <th class="text-center" width="80">' . $name1 . '</th>';
		$html2 .= '        <th class="text-center" width="80">' . $name2 . '</th>';
		$html2 .= '        <th class="th-price" style="width: 70px;text-align: center">成本价（元）</th>';
		$html2 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
		$html2 .= '    </tr>';
		$html2 .= '</thead>';
		$html2 .= '<tbody>';
		foreach ($vids as $key => $vid) {
		    $value = $product_property_value->getValue($pid, $vid['vid']);
		    foreach ($vids1 as $key1 => $vid1) {
			$value1 = $product_property_value->getValue($pid1, $vid1['vid']);
			foreach ($vids2 as $key2 => $vid2) {
			    $properties = $pid . ':' . $vid['vid'] . ';' . $pid1 . ':' . $vid1['vid'] . ';' . $pid2 . ':' . $vid2['vid'];
			    $sku = $product_sku->getSku($id, $properties);
			    $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
			    $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
			    $value2 = $product_property_value->getValue($pid2, $vid2['vid']);
			    if ($key1 == 0 && $key2 == 0) {
				$html .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
				$html2 .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
			    }
			    if ($key2 == 0) {
				$html .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
				$html2 .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
			    }
			    $html .= '        <td class="text-center" width="50">' . $value2 . '</td>';
			    $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini"  maxlength="10" /></td>';
			    $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini" maxlength="10" /></td>';
			    $html .= '    </tr>';

			    $html2 .= '        <td class="text-center" width="50">' . $value2 . '</td>';
			    $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini"  maxlength="10" /></td>';
			    $html2 .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-price input-mini" maxlength="10" /></td>';
			    $html2 .= '    </tr>';
			}
		    }
		}
	    } else if (!empty($pids[1]['pid'])) {
		$pid1 = $pids[1]['pid'];
		$name1 = $product_property->getName($pid1);
		$vids1 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid1);
		$html = '<thead>';
		$html .= '    <tr>';
		$html .= '        <th class="text-center" width="50">' . $name . '</th>';
		$html .= '        <th class="text-center" width="50">' . $name1 . '</th>';
		$html .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
		$html .= '        <th class="th-price" style="width: 105px;text-align: center">建议售价（元）</th>';
		$html .= '    </tr>';
		$html .= '</thead>';
		$html .= '<tbody>';

		$html2 = '<thead>';
		$html2 .= '    <tr>';
		$html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
		$html2 .= '        <th class="text-center" width="50">' . $name1 . '</th>';
		$html2 .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
		$html2 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
		$html2 .= '    </tr>';
		$html2 .= '</thead>';
		$html2 .= '<tbody>';
		foreach ($vids as $key => $vid) {
		    $value = $product_property_value->getValue($pid, $vid['vid']);
		    foreach ($vids1 as $key1 => $vid1) {
			$properties = $pid . ':' . $vid['vid'] . ';' . $pid1 . ':' . $vid1['vid'];
			$sku = $product_sku->getSku($id, $properties);
			$html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
			$html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
			$value1 = $product_property_value->getValue($pid1, $vid1['vid']);
			if ($key1 == 0) {
			    $html .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
			    $html2 .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
			}
			$html .= '        <td class="text-center" width="50">' . $value1 . '</td>';
			$html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" />';
			$html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini"  maxlength="10" /></td>';
			$html .= '    </tr>';

			$html2 .= '        <td class="text-center" width="50">' . $value1 . '</td>';
			$html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" />';
			$html2 .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-price input-mini" maxlength="10" /></td>';
			$html2 .= '    </tr>';
		    }
		}
	    } else {
		$html = '<thead>';
		$html .= '    <tr>';
		$html .= '        <th class="text-center" width="50">' . $name . '</th>';
		$html .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
		$html .= '        <th class="th-price" style="width: 105px;text-align: center">建议售价（元）</th>';
		$html .= '    </tr>';
		$html .= '</thead>';
		$html .= '<tbody>';

		$html2 = '<thead>';
		$html2 .= '    <tr>';
		$html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
		$html2 .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
		$html2 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
		$html2 .= '    </tr>';
		$html2 .= '</thead>';
		$html2 .= '<tbody>';
		foreach ($vids as $key => $vid) {
		    $value = $product_property_value->getValue($pid, $vid['vid']);
		    $properties = $pid . ':' . $vid['vid'];
		    $sku = $product_sku->getSku($id, $properties);
		    $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
		    $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
		    $value = $product_property_value->getValue($pid, $vid['vid']);
		    $html .= '        <td class="text-center" width="50">' . $value . '</td>';
		    $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" /></td>';
		    $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini" maxlength="10" /></td>';
		    $html .= '    </tr>';

		    $html2 .= '        <td class="text-center" width="50">' . $value . '</td>';
		    $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" /></td>';
		    $html2 .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-price input-mini" maxlength="10" /></td>';
		    $html2 .= '    </tr>';
		}
	    }
	    $html .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-cost" href="javascript:;">成本价</a>&nbsp;&nbsp;<a class="js-batch-price" href="javascript:;">分销价</a></span><span class="js-batch-form" style="display:none;"></span></div></td></tr></tfoot>';
	    $html2 .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts2">批量设置： <span class="js-batch-type2"><a class="js-batch-cost2" href="javascript:;">成本价</a>&nbsp;&nbsp;<a class="js-batch-price2" href="javascript:;">分销价</a></span><span class="js-batch-form2" style="display:none;"></span></div></td></tr></tfoot>';
	}
	/* if ($product['supplier_id']) { //分销商品
	  $product_id = $id;
	  $id = $product['source_product_id'];
	  $pids = $product_to_property->getPids($product['supplier_id'], $id);
	  if (!empty($pids[0]['pid'])) {
	  $pid = $pids[0]['pid'];
	  $name = $product_property->getName($pid);
	  $vids = $product_to_property_value->getVids($product['supplier_id'], $id, $pid);
	  if (!empty($pids[1]['pid']) && !empty($pids[2]['pid'])) {
	  $pid1 = $pids[1]['pid'];
	  $name1 = $product_property->getName($pid1);
	  $vids1 = $product_to_property_value->getVids($product['supplier_id'], $id, $pid1);
	  $pid2 = $pids[2]['pid'];
	  $name2 = $product_property->getName($pid2);
	  $vids2 = $product_to_property_value->getVids($product['supplier_id'], $id, $pid2);
	  $html = '<thead>';
	  $html .= '    <tr>';
	  $html .= '        <th class="text-center" width="80">' . $name . '</th>';
	  $html .= '        <th class="text-center" width="80">' . $name1 . '</th>';
	  $html .= '        <th class="text-center" width="80">' . $name2 . '</th>';
	  $html .= '        <th class="th-price" style="width: 70px;text-align: center">成本价（元）</th>';
	  $html .= '        <th class="th-price" style="width: 105px;text-align: center">建议售价（元）</th>';
	  $html .= '    </tr>';
	  $html .= '</thead>';
	  $html .= '<tbody>';
	  foreach ($vids as $key => $vid) {
	  $value = $product_property_value->getValue($pid, $vid['vid']);
	  foreach ($vids1 as $key1 => $vid1) {
	  $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
	  foreach ($vids2 as $key2 => $vid2) {
	  $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'] . ';' . $pid2 . ':' . $vid2['vid'];
	  $sku = $product_sku->getSku($id, $properties);
	  $sku2 = $product_sku->getSku($product_id, $properties);
	  $html .= '    <tr class="sku" sku-id="' . $sku2['sku_id'] . '" properties="' . $sku2['properties'] . '">';
	  $value2 = $product_property_value->getValue($pid2, $vid2['vid']);
	  if($key1 == 0 && $key2 == 0) {
	  $html .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
	  }
	  if($key2 == 0) {
	  $html .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
	  }
	  $html .= '        <td class="text-center" width="50">' . $value2 . '</td>';
	  if (!empty($product['unified_price_setting'])) { //供货商统一定价
	  if (($_SESSION['store']['drp_level'] + 1) > 3) {
	  $next_drp_level = 3;
	  } else {
	  $next_drp_level = $_SESSION['store']['drp_level'] + 1;
	  }
	  $html .= '        <td style="text-align: center">' . $sku2['drp_level_' . $next_drp_level . '_cost_price'] . '<input type="hidden" name="cost_sku_price" class="js-cost-price input-mini" value="' . $sku2['drp_level_' . $next_drp_level . '_cost_price'] . '" /></td>';
	  $html .= '        <td style="text-align: center">' . $sku2['drp_level_' . $next_drp_level . '_price'] . '<input type="hidden" name="sku_price" class="js-price js-fx-min-price input-mini" value="' . $sku2['drp_level_' . $next_drp_level . '_price'] . '" /><input type="hidden" name="sku_price" class="js-price js-fx-max-price input-mini" value="' . $sku2['drp_level_' . $next_drp_level . '_price'] . '" /></td>';
	  } else {
	  $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" data-min-cost-price="' . $sku['cost_price'] . '" data-max-cost-price="' . $sku['max_fx_price'] . '"  value="' . $sku['cost_price'] . '" maxlength="10" ' . $readonly . ' /></td>';
	  $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" data-min-price="' . ($sku['min_fx_price'] > 0 ? $sku['min_fx_price'] : '') . '" value="' . ($sku['min_fx_price'] > 0 ? $sku['min_fx_price'] : '') . '" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini" maxlength="10" data-max-price="' . ($sku['max_fx_price'] > 0 ? $sku['max_fx_price'] : '') . '" value="' . ($sku['max_fx_price'] > 0 ? $sku['max_fx_price'] : '') . '" /></td>';
	  }
	  $html .= '    </tr>';
	  }
	  }
	  }
	  } else if (!empty($pids[1]['pid'])) {
	  $pid1 = $pids[1]['pid'];
	  $name1 = $product_property->getName($pid1);
	  $vids1 = $product_to_property_value->getVids($product['supplier_id'], $id, $pid1);
	  $html = '<thead>';
	  $html .= '    <tr>';
	  $html .= '        <th class="text-center" width="50">' . $name . '</th>';
	  $html .= '        <th class="text-center" width="50">' . $name1 . '</th>';
	  $html .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
	  $html .= '        <th class="th-price" style="width: 105px;text-align: center">建议售价（元）</th>';
	  $html .= '    </tr>';
	  $html .= '</thead>';
	  $html .= '<tbody>';
	  foreach ($vids as $key => $vid) {
	  $value = $product_property_value->getValue($pid, $vid['vid']);
	  foreach ($vids1 as $key1 => $vid1) {
	  $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'];
	  $sku = $product_sku->getSku($id, $properties);
	  $sku2 = $product_sku->getSku($product_id, $properties);
	  $html .= '    <tr class="sku" sku-id="' . $sku2['sku_id'] . '" properties="' . $sku2['properties'] . '">';
	  $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
	  if($key1 == 0) {
	  $html .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
	  }
	  $html .= '        <td class="text-center" width="50">' . $value1 . '</td>';
	  if (!empty($product['unified_price_setting'])) { //供货商统一定价
	  if (($_SESSION['store']['drp_level'] + 1) > 3) {
	  $next_drp_level = 3;
	  } else {
	  $next_drp_level = $_SESSION['store']['drp_level'] + 1;
	  }
	  $html .= '        <td style="text-align: center">' . $sku2['drp_level_' . $next_drp_level . '_cost_price'] . '<input type="hidden" name="cost_sku_price" class="js-cost-price input-mini" value="' . $sku2['drp_level_' . $next_drp_level . '_cost_price'] . '" /></td>';
	  $html .= '        <td style="text-align: center">' . $sku2['drp_level_' . $next_drp_level . '_price'] . '<input type="hidden" name="sku_price" class="js-price js-fx-min-price input-mini" value="' . $sku2['drp_level_' . $next_drp_level . '_price'] . '" /><input type="hidden" name="sku_price" class="js-price js-fx-max-price input-mini" value="' . $sku2['drp_level_' . $next_drp_level . '_price'] . '" /></td>';
	  } else {
	  $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" data-min-cost-price="' . $sku['cost_price'] . '" data-max-cost-price="' . $sku['max_fx_price'] . '" value="' . $sku['cost_price'] . '" maxlength="10" ' . $readonly . ' /></td>';
	  $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" data-min-price="' . ($sku['min_fx_price'] > 0 ? $sku['min_fx_price'] : '') . '" value="' . ($sku['min_fx_price'] > 0 ? $sku['min_fx_price'] : '') . '" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini"  maxlength="10" data-max-price="' . ($sku['max_fx_price'] > 0 ? $sku['max_fx_price'] : '') . '" value="' . ($sku['max_fx_price'] > 0 ? $sku['max_fx_price'] : '') . '" /></td>';
	  }
	  $html .= '    </tr>';
	  }
	  }
	  } else {
	  $html = '<thead>';
	  $html .= '    <tr>';
	  $html .= '        <th class="text-center" width="50">' . $name . '</th>';
	  $html .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
	  $html .= '        <th class="th-price" style="width: 105px;text-align: center">建议售价（元）</th>';
	  $html .= '    </tr>';
	  $html .= '</thead>';
	  $html .= '<tbody>';
	  foreach ($vids as $key => $vid) {
	  $value = $product_property_value->getValue($pid, $vid['vid']);
	  $properties = $pid . ':' . $vid['vid'];
	  $sku = $product_sku->getSku($id, $properties);
	  $sku2 = $product_sku->getSku($product_id, $properties);
	  $html .= '    <tr class="sku" sku-id="' . $sku2['sku_id'] . '" properties="' . $sku2['properties'] . '">';
	  $value = $product_property_value->getValue($pid, $vid['vid']);
	  $html .= '        <td class="text-center" width="50">' . $value . '</td>';
	  if (!empty($product['unified_price_setting'])) { //供货商统一定价
	  if (($_SESSION['store']['drp_level'] + 1) > 3) {
	  $next_drp_level = 3;
	  } else {
	  $next_drp_level = $_SESSION['store']['drp_level'] + 1;
	  }
	  $html .= '        <td style="text-align: center">' . $sku2['drp_level_' . $next_drp_level . '_cost_price'] . '<input type="hidden" name="cost_sku_price" class="js-cost-price input-mini" value="' . $sku2['drp_level_' . $next_drp_level . '_cost_price'] . '" /></td>';
	  $html .= '        <td style="text-align: center">' . $sku2['drp_level_' . $next_drp_level . '_price'] . '<input type="hidden" name="sku_price" class="js-price js-fx-min-price input-mini" value="' . $sku2['drp_level_' . $next_drp_level . '_price'] . '" /><input type="hidden" name="sku_price" class="js-price js-fx-max-price input-mini" value="' . $sku2['drp_level_' . $next_drp_level . '_price'] . '" /></td>';
	  } else {
	  $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" data-min-cost-price="' . $sku['cost_price'] . '" data-max-cost-price="' . $sku['max_fx_price'] . '" value="' . $sku['cost_price'] . '" maxlength="10" ' . $readonly . ' /></td>';
	  $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" data-min-price="' . ($sku['min_fx_price'] > 0 ? $sku['min_fx_price'] : '') . '" value="' . ($sku['min_fx_price'] > 0 ? $sku['min_fx_price'] : '') . '" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini" maxlength="10" data-max-price="' . ($sku['max_fx_price'] > 0 ? $sku['max_fx_price'] : '') . '" value="' . ($sku['max_fx_price'] > 0 ? $sku['max_fx_price'] : '') . '" /></td>';
	  }
	  $html .= '    </tr>';
	  }
	  }
	  $html .= '</tbody>';
	  if (empty($product['unified_price_setting'])) { //供货商统一定价
	  $html .= '<tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-cost" href="javascript:;">成本价</a>&nbsp;&nbsp;<a class="js-batch-price" href="javascript:;">分销价</a></span><span class="js-batch-form" style="display:none;"></span></div></td></tr></tfoot>';
	  }
	  }
	  } else {
	  $pids = $product_to_property->getPids($this->store_session['store_id'], $id);
	  print_r($pids);
	  if (!empty($pids[0]['pid'])) {
	  echo '1';
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
	  $html .= '        <th class="text-center" width="80">' . $name . '</th>';
	  $html .= '        <th class="text-center" width="80">' . $name1 . '</th>';
	  $html .= '        <th class="text-center" width="80">' . $name2 . '</th>';
	  $html .= '        <th class="th-price" style="width: 70px;text-align: center">成本价（元）</th>';
	  $html .= '        <th class="th-price" style="width: 105px;text-align: center">建议售价（元）</th>';
	  $html .= '    </tr>';
	  $html .= '</thead>';
	  $html .= '<tbody>';
	  $html2 = '<thead>';
	  $html2 .= '    <tr>';
	  $html2 .= '        <th class="text-center" width="80">' . $name . '</th>';
	  $html2 .= '        <th class="text-center" width="80">' . $name1 . '</th>';
	  $html2 .= '        <th class="text-center" width="80">' . $name2 . '</th>';
	  $html2 .= '        <th class="th-price" style="width: 70px;text-align: center">成本价（元）</th>';
	  $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
	  $html2 .= '    </tr>';
	  $html2 .= '</thead>';
	  $html2 .= '<tbody>';
	  foreach ($vids as $key => $vid) {
	  echo '2';
	  $value = $product_property_value->getValue($pid, $vid['vid']);
	  foreach ($vids1 as $key1 => $vid1) {
	  $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
	  foreach ($vids2 as $key2 => $vid2) {
	  $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'] . ';' . $pid2 . ':' . $vid2['vid'];
	  $sku = $product_sku->getSku($id, $properties);
	  $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
	  $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
	  $value2 = $product_property_value->getValue($pid2, $vid2['vid']);
	  if($key1 == 0 && $key2 == 0) {
	  $html .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
	  $html2 .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
	  }
	  if($key2 == 0) {
	  $html .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
	  $html2 .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
	  }
	  $html .= '        <td class="text-center" width="50">' . $value2 . '</td>';
	  $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini"  maxlength="10" /></td>';
	  $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini" maxlength="10" /></td>';
	  $html .= '    </tr>';

	  $html2 .= '        <td class="text-center" width="50">' . $value2 . '</td>';
	  $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini"  maxlength="10" /></td>';
	  $html2 .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-price input-mini" maxlength="10" /></td>';
	  $html2 .= '    </tr>';
	  }
	  }
	  }
	  } else if (!empty($pids[1]['pid'])) {
	  echo '3';
	  $pid1 = $pids[1]['pid'];
	  $name1 = $product_property->getName($pid1);
	  $vids1 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid1);
	  $html = '<thead>';
	  $html .= '    <tr>';
	  $html .= '        <th class="text-center" width="50">' . $name . '</th>';
	  $html .= '        <th class="text-center" width="50">' . $name1 . '</th>';
	  $html .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
	  $html .= '        <th class="th-price" style="width: 105px;text-align: center">建议售价（元）</th>';
	  $html .= '    </tr>';
	  $html .= '</thead>';
	  $html .= '<tbody>';

	  $html2 = '<thead>';
	  $html2 .= '    <tr>';
	  $html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
	  $html2 .= '        <th class="text-center" width="50">' . $name1 . '</th>';
	  $html2 .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
	  $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
	  $html2 .= '    </tr>';
	  $html2 .= '</thead>';
	  $html2 .= '<tbody>';
	  foreach ($vids as $key => $vid) {
	  echo '4';
	  $value = $product_property_value->getValue($pid, $vid['vid']);
	  foreach ($vids1 as $key1 => $vid1) {
	  $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'];
	  $sku = $product_sku->getSku($id, $properties);
	  $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
	  $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
	  $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
	  if($key1 == 0) {
	  $html .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
	  $html2 .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
	  }
	  $html .= '        <td class="text-center" width="50">' . $value1 . '</td>';
	  $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" />';
	  $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini"  maxlength="10" /></td>';
	  $html .= '    </tr>';

	  $html2 .= '        <td class="text-center" width="50">' . $value1 . '</td>';
	  $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" />';
	  $html2 .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-price input-mini" maxlength="10" /></td>';
	  $html2 .= '    </tr>';
	  }
	  }
	  } else {
	  echo '5';
	  $html = '<thead>';
	  $html .= '    <tr>';
	  $html .= '        <th class="text-center" width="50">' . $name . '</th>';
	  $html .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
	  $html .= '        <th class="th-price" style="width: 105px;text-align: center">建议售价（元）</th>';
	  $html .= '    </tr>';
	  $html .= '</thead>';
	  $html .= '<tbody>';

	  $html2 = '<thead>';
	  $html2 .= '    <tr>';
	  $html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
	  $html2 .= '        <th class="th-price" style="text-align: center">成本价（元）</th>';
	  $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">分销价（元）</th>';
	  $html2 .= '    </tr>';
	  $html2 .= '</thead>';
	  $html2 .= '<tbody>';
	  foreach ($vids as $key => $vid) {
	  $value = $product_property_value->getValue($pid, $vid['vid']);
	  $properties = $pid . ':' . $vid['vid'];
	  $sku = $product_sku->getSku($id, $properties);
	  $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
	  $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
	  $value = $product_property_value->getValue($pid, $vid['vid']);
	  $html .= '        <td class="text-center" width="50">' . $value . '</td>';
	  $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" /></td>';
	  $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini" maxlength="10" /></td>';
	  $html .= '    </tr>';

	  $html2 .= '        <td class="text-center" width="50">' . $value . '</td>';
	  $html2 .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" /></td>';
	  $html2 .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-price input-mini" maxlength="10" /></td>';
	  $html2 .= '    </tr>';
	  }
	  }
	  $html .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-cost" href="javascript:;">成本价</a>&nbsp;&nbsp;<a class="js-batch-price" href="javascript:;">分销价</a></span><span class="js-batch-form" style="display:none;"></span></div></td></tr></tfoot>';
	  $html2 .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts2">批量设置： <span class="js-batch-type2"><a class="js-batch-cost2" href="javascript:;">成本价</a>&nbsp;&nbsp;<a class="js-batch-price2" href="javascript:;">分销价</a></span><span class="js-batch-form2" style="display:none;"></span></div></td></tr></tfoot>';
	  }
	  } */
	$this->assign('edit_cost_price', $edit_cost_price);
	$this->assign('sku_content', $html);
	$this->assign('sku_content2', $html2);
	if (!empty($product['source_product_id'])) {
	    $source_product = M('Product')->get(array('product_id' => $product['source_product_id'], 'store_id' => $product['supplier_id']));
	    $min_fx_price = $source_product['min_fx_price'];
	    $max_fx_price = $source_product['max_fx_price'];
	    $cost_price = $source_product['cost_price'];

	    if (!empty($product['unified_price_setting'])) {
		if (($_SESSION['store']['drp_level'] + 1) > 3) {
		    $next_drp_level = 3;
		} else {
		    $next_drp_level = $_SESSION['store']['drp_level'] + 1;
		}
		$cost_price = $source_product['drp_level_' . $next_drp_level . '_cost_price'];
		$min_fx_price = $source_product['drp_level_' . $next_drp_level . '_price'];
		$max_fx_price = $source_product['drp_level_' . $next_drp_level . '_price'];
	    }
	} else {
	    $min_fx_price = $product['min_fx_price'];
	    $max_fx_price = $product['max_fx_price'];
	    $cost_price = $product['cost_price'];
	}
	$this->assign('product', $product);
	$this->assign('min_fx_price', $min_fx_price);
	$this->assign('max_fx_price', $max_fx_price);
	$this->assign('cost_price', $cost_price);
	if (empty($product['supplier_id'])) {
	    $is_supplier = true;
	} else {
	    $is_supplier = false;
	}
	$this->assign('is_supplier', $is_supplier);
	$this->assign('drp_level', $_SESSION['store']['drp_level']);
	$this->assign('open_drp_setting_price', $this->store_session['open_drp_setting_price']);
	$this->assign('unified_price_setting', $this->store_session['unified_price_setting']);
    }

    public function delivery_address() {
	import('source.class.area');
	$user_address = M('User_address');

	if (IS_POST && !empty($_POST['type'])) {
	    $data = array();
	    $data['name'] = isset($_POST['name']) ? trim($_POST['name']) : '';
	    $data['tel'] = isset($_POST['tel']) ? trim($_POST['tel']) : '';
	    $data['province'] = isset($_POST['province']) ? intval(trim($_POST['province'])) : '';
	    $data['city'] = isset($_POST['city']) ? intval(trim($_POST['city'])) : '';
	    $data['area'] = isset($_POST['area']) ? intval(trim($_POST['area'])) : '';
	    $data['address'] = isset($_POST['address']) ? trim($_POST['address']) : '';
	    $data['zipcode'] = isset($_POST['zipcode']) ? intval(trim($_POST['zipcode'])) : '';
	    $data['add_time'] = time();
	    $data['uid'] = $this->user_session['uid'];
	    if ($address_id = $user_address->add($data)) {
		$address = new area();
		$address_detail = array();
		$address_detail['address_id'] = $address_id;
		$address_detail['province'] = $address->get_name($data['province']);
		$address_detail['city'] = $address->get_name($data['city']);
		$address_detail['area'] = $address->get_name($data['area']);
		$address_detail['address'] = $data['address'];
		json_return(0, $address_detail);
	    } else {
		json_return(1001, '收货地址添加失败');
	    }
	}
	//收货地址
	$tmp_addresses = $user_address->getMyAddress($this->user_session['uid']);
	$addresses = array();
	$address = new area();
	foreach ($tmp_addresses as $tmp_address) {
	    $province = $address->get_name($tmp_address['province']);
	    $city = $address->get_name($tmp_address['city']);
	    $area = $address->get_name($tmp_address['area']);
	    $addresses[] = array(
		'address_id' => $tmp_address['address_id'],
		'province' => $province,
		'city' => $city,
		'area' => $area,
		'address' => $tmp_address['address']
	    );
	}
	echo json_encode($addresses);
	exit;
    }

    //分销经营统计
    public function statistics() {
	$this->display();
    }

    private function _statistics_content($data)
    {
        $order = M('Order');
        $financial_record = M('Financial_record');
        $store = M('Store');

        $store = $store->getStore($data['store_id']);

        //总销售额
        $sales = $order->getSales(array('store_id' => $data['store_id'], 'is_fx' => 1, 'status' => array('in', array(2,3,4,7))));
        //总佣金
        $profit = $store['income'];

        $days = array();
        if (empty($data['start_time']) && empty($data['stop_time'])) {
            for($i=7; $i>=1; $i--){
                $day = date("Y-m-d",strtotime('-'.$i.'day'));
                $days[] = $day;
            }
        } else if (!empty($data['start_time']) && !empty($data['stop_time'])) {
            $start_unix_time = strtotime($data['start_time']);
            $stop_unix_time = strtotime($data['stop_time']);
            $tmp_days = round(($stop_unix_time - $start_unix_time) / 3600 / 24);
            $days = array($data['start_time']);
            if ($data['stop_time'] >$data['start_time']) {
                for($i=1; $i<$tmp_days; $i++){
                    $days[] = date("Y-m-d",strtotime($data['start_time'] . ' +'.$i.'day'));
                }
                $days[] = $data['stop_time'];
            }
        } else if (!empty($data['start_time'])) { //开始时间到后6天的数据
            $stop_time = date("Y-m-d",strtotime($data['start_time']. ' +7 day'));
            $days = array($data['start_time']);
            for($i=1; $i<=6; $i++){
                $day = date("Y-m-d",strtotime($data['start_time'] .' +' . $i . 'day'));
                $days[] = $day;
            }
        } else if (!empty($data['stop_time'])) { //结束时间前6天的数据
            $start_time = date("Y-m-d",strtotime($data['stop_time']. ' -6 day'));
            $days = array($start_time);
            for($i=1; $i<=6; $i++){
                $day = date("Y-m-d",strtotime($start_time .' +' . $i . 'day'));
                $days[] = $day;
            }
        }

        //七天下单、付款、发货订单笔数和付款金额
        $tmp_days = array();
        $days_sales = 0;
        $days_profits = 0;
        foreach ($days as $day) {
            //开始时间
            $start_time = strtotime($day . ' 00:00:00');
            //结束时间
            $stop_time = strtotime($day . ' 23:59:59');
            $where = array();
            $where['store_id'] = $data['store_id'];
            $where['is_fx']    = 1;
            $where['status']   = array('in', array(2,3,4,7));
            $where['_string']  = "paid_time >= '" . $start_time . "' AND paid_time <= '" . $stop_time . "'";
            $tmp_days_7_sales  = $order->getSales($where);
            $days_7_sales[] = !empty($tmp_days_7_sales) ? number_format($tmp_days_7_sales, 2, '.', '') : 0;
            $where = array();
            $where['store_id'] = $data['store_id'];
            $where['_string']  = "add_time >= '" . $start_time . "' AND add_time <= '" . $stop_time . "'";
            $tmp_days_7_profits = $financial_record->drpProfit($where);
            $days_7_profits[] = !empty($tmp_days_7_profits) ? number_format($tmp_days_7_profits, 2, '.', '') : 0;

            $tmp_days[] = "'" . $day . "'";
        }

        $days_sales   = array_sum($days_7_sales);
        $days_profits = array_sum($days_7_profits);

        $days = '[' . implode(',', $tmp_days) . ']';
        $days_7_sales   = '[' . implode(',', $days_7_sales) . ']';
        $days_7_profits = '[' . implode(',', $days_7_profits) . ']';

        $this->assign('sales', number_format($sales, 2, '.', ''));
        $this->assign('profit', number_format($profit, 2, '.', ''));
        $this->assign('days_sales', number_format($days_sales, 2, '.', ''));
        $this->assign('days_profits', number_format($days_profits, 2, '.', ''));
        $this->assign('days', $days);
        $this->assign('store', $store);
        $this->assign('days_7_sales', $days_7_sales);
        $this->assign('days_7_profits', $days_7_profits);
    }

    //分销配置
    public function setting()
    {
        $this->display();
    }

    private function _setting_content()
    {
        $this->assign('open_drp_approve', $this->store_session['open_drp_approve']);
        $this->assign('open_drp_guidance', $this->store_session['open_drp_guidance']);
        $this->assign('open_drp_limit', $this->store_session['open_drp_limit']);
        $this->assign('drp_limit_buy', $this->store_session['drp_limit_buy']);
        $this->assign('drp_limit_share', $this->store_session['drp_limit_share']);
        $this->assign('drp_limit_condition', $this->store_session['drp_limit_condition']);
        $this->assign('open_drp_diy_store', $this->store_session['open_drp_diy_store']);
        $this->assign('open_drp_setting_price', $this->store_session['open_drp_setting_price']);
        $this->assign('unified_price_setting', $this->store_session['unified_price_setting']);
        $this->assign('open_drp_subscribe', $this->store_session['open_drp_subscribe']);
        $this->assign('open_drp_subscribe_auto', $this->store_session['open_drp_subscribe_auto']);
        $this->assign('drp_subscribe_tpl', $this->store_session['drp_subscribe_tpl']);
        $this->assign('reg_drp_subscribe_tpl', $this->store_session['reg_drp_subscribe_tpl']);
        $this->assign('reg_drp_subscribe_img', $this->store_session['reg_drp_subscribe_img']);
        $this->assign('drp_subscribe_img', $this->store_session['drp_subscribe_img']);
        $this->assign('update_drp_store_info', $this->store_session['update_drp_store_info']);
        $this->assign('is_fanshare_drp', $this->store_session['is_fanshare_drp']);
        $this->assign('setting_fans_forever', $this->store_session['setting_fans_forever']);
    }

    //保存分销限制
    public function save_drp_limit()
    {
        $drp_limit_buy = !empty($_POST['drp_limit_buy']) ? floatval(trim($_POST['drp_limit_buy'])) : 0;
        $drp_limit_share = !empty($_POST['drp_limit_share']) ? intval(trim($_POST['drp_limit_share'])) : 0;
        $drp_limit_condition = !empty($_POST['drp_limit_condition']) ? intval(trim($_POST['drp_limit_condition'])) : 0;
        if (D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('drp_limit_buy' => $drp_limit_buy, 'drp_limit_share' => $drp_limit_share, 'drp_limit_condition' => $drp_limit_condition))->save()) {
            $_SESSION['store']['drp_limit_buy']       = $drp_limit_buy;
            $_SESSION['store']['drp_limit_share']     = $drp_limit_share;
            $_SESSION['store']['drp_limit_condition'] = $drp_limit_condition;
            json_return(0, '分销限制条件保存成功');
        } else {
            json_return(1001, '分销限制条件保存失败');
        }
    }

    //保存分销定价
    public function save_unified_price_setting()
    {
        $setting  = !empty($_POST['setting']) ? trim($_POST['setting']) : 0;
        if (D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('unified_price_setting' => $setting))->save()) {
            $_SESSION['store']['unified_price_setting'] = $setting;
            json_return(0, '分销定价保存成功');
        } else {
            json_return(1001, '分销定价保存失败');
        }
    }

    //保存分销申请关注公众号模板消息
    public function reg_drp_subscribe_tpl()
    {
        $reg_drp_subscribe_tpl = !empty($_POST['reg_drp_subscribe_tpl']) ? mysql_real_escape_string(trim($_POST['reg_drp_subscribe_tpl'])) : '';
        $reg_drp_subscribe_img = !empty($_POST['reg_drp_subscribe_img']) ? mysql_real_escape_string(trim($_POST['reg_drp_subscribe_img'])) : '';
        if (D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('reg_drp_subscribe_tpl' => $reg_drp_subscribe_tpl, 'reg_drp_subscribe_img' => $reg_drp_subscribe_img))->save()) {
            $_SESSION['store']['reg_drp_subscribe_tpl'] = $reg_drp_subscribe_tpl;
            $_SESSION['store']['reg_drp_subscribe_img'] = $reg_drp_subscribe_img;
            json_return(0, '模板消息保存成功');
        } else {
            json_return(1001, '模板消息保存失败');
        }
    }

    public function canal_qrcode_tpl() {
	$canal_qrcode_tpl = !empty($_POST['canal_qrcode_tpl']) ? mysql_real_escape_string(trim($_POST['canal_qrcode_tpl'])) : '';
	$canal_qrcode_img = !empty($_POST['canal_qrcode_img']) ? mysql_real_escape_string(trim($_POST['canal_qrcode_img'])) : '';
	if (D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('canal_qrcode_tpl' => $canal_qrcode_tpl, 'canal_qrcode_img' => $canal_qrcode_img))->save()) {
	    $_SESSION['store']['canal_qrcode_tpl'] = $canal_qrcode_tpl;
	    $_SESSION['store']['canal_qrcode_img'] = $canal_qrcode_img;
	    json_return(0, '模板消息保存成功');
	} else {
		//echo D('Store')->last_sql;exit;
	    json_return(1001, '模板消息保存失败');
	}
    }

    //保存关注公众号模板消息
    public function drp_subscribe_tpl()
    {
        $drp_subscribe_tpl = !empty($_POST['drp_subscribe_tpl']) ? mysql_real_escape_string(trim($_POST['drp_subscribe_tpl'])) : '';
        $drp_subscribe_img = !empty($_POST['drp_subscribe_img']) ? mysql_real_escape_string(trim($_POST['drp_subscribe_img'])) : '';
        if (D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('drp_subscribe_tpl' => $drp_subscribe_tpl, 'drp_subscribe_img' => $drp_subscribe_img))->save()) {
            $_SESSION['store']['drp_subscribe_tpl'] = $drp_subscribe_tpl;
            $_SESSION['store']['drp_subscribe_img'] = $drp_subscribe_img;
            json_return(0, '模板消息保存成功');
        } else {
            json_return(1001, '模板消息保存失败');
        }
    }

    //是否开启分销商审核/审核状态
    public function drp_approve()
    {
        $seller_id = isset($_POST['seller_id']) ? intval(trim($_POST['seller_id'])) : 0;
        if (!empty($seller_id)) {
            //$result = D('Store')->where(array('store_id' => $seller_id, 'drp_supplier_id' => $this->store_session['store_id']))->data(array('drp_approve' => 1))->save();
        	$result = D('Store')->where(array('store_id' => $seller_id))->data(array('drp_approve' => 1))->save();
            if ($result) {
                $_SESSION['store']['drp_approve'] = 1;
                json_return(0, '审核已通过');
            } else {
                json_return(1001, '审核失败，请重新审核');
            }
        } else {
            $status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
            $result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('open_drp_approve' => $status))->save();
            if ($result) {
                $_SESSION['store']['open_drp_approve'] = $status;
                echo true;
            } else {
                echo false;
            }
        }
        exit;
    }

    //是否开启分销引导
    public function drp_guidance()
    {
        $status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
        $result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('open_drp_guidance' => $status))->save();
        if ($result) {
            $_SESSION['store']['open_drp_guidance'] = $status;
            echo true;
        } else {
            echo false;
        }
    }

    //是否允许分销商修改店铺名称
    public function drp_update_store_info()
    {
        $supplier_store = M('Store_supplier');
        $status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
        $result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('update_drp_store_info' => $status))->save();

        $sellerIdList = $supplier_store->getSellerList($this->store_session['store_id']);

        if(count($sellerIdList) > 0)
        {
            foreach ($sellerIdList as $id)
            {
                $results = D('Store')->where(array ('store_id' => $id['seller_id']))->data(array ('update_drp_store_info' => $status))->save();
            }
        }

        if ($result) {
            $_SESSION['store']['update_drp_store_info'] = $status;
            echo true;
        } else {
            echo false;
        }
    }

    //是否开启分销限制
    public function drp_limit()
    {
        $status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
        $result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('open_drp_limit' => $status))->save();
        if ($result) {
            $_SESSION['store']['open_drp_limit'] = $status;
            echo true;
        } else {
            echo false;
        }
    }

    //是否开启店铺装修
    public function drp_diy_store()
    {
        $status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
        $result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('open_drp_diy_store' => $status))->save();
        if ($result) {
            $_SESSION['store']['open_drp_diy_store'] = $status;
            echo true;
        } else {
            echo false;
        }
    }

    //是否开启分销定价
    public function drp_setting_price()
    {
        $status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
        $result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('open_drp_setting_price' => $status))->save();
        if ($result) {
            $_SESSION['store']['open_drp_setting_price'] = $status;
            echo true;
        } else {
            echo false;
        }
    }

    //禁用/启用当前分销商店铺
    public function drp_status()
    {
        $store = M('Store');
        $store_supplier = M('Store_supplier');
        $seller_id = isset($_POST['seller_id']) ? intval(trim($_POST['seller_id'])) : 0; //分销商id
        $status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0; //状态

        if ($store->setting(array('store_id' => $seller_id), array('status' => $status))) {
            /*$sellers = $store_supplier->getSellers(array('supplier_id' => $seller_id));
            var_dump($sellers);exit;
            foreach ($sellers as $seller) {
                $store->setting(array('store_id' => $seller['seller_id']), array('status' => $status));
                $this->_seller_status($store_supplier, $store, $seller['seller_id'], $status);
            }*/
            json_return(0, '操作成功');
        } else {
            json_return(1001, '操作失败');
        }
    }

    //关注公众号
    public function drp_subscribe()
    {
        $status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
        $result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('open_drp_subscribe' => $status))->save();
        if ($result) {
            $_SESSION['store']['open_drp_subscribe'] = $status;
            echo true;
        } else {
            echo false;
        }
    }

    //自动分销（关注公众号）
    public function drp_subscribe_auto()
    {
        $status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
        $result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('open_drp_subscribe_auto' => $status))->save();
        if ($result) {
            $_SESSION['store']['open_drp_subscribe_auto'] = $status;
            echo true;
        } else {
            echo false;
        }
    }

    //校验密码
    public function check_password()
    {
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        if ($password) {
            $password = md5($password);
        }
        $uid = $this->user_session['uid'];
        if (D('User')->where(array('uid' => $uid, 'password' => $password))->count('uid')) {
            json_return(0, '密码正确');
        } else {
            json_return(1001, '密码错误');
        }
    }

    private function _seller_status($store_supplier, $store, $seller_id, $status)
    {
        $sellers = $store_supplier->getSellers(array('supplier_id' => $seller_id));
        if (!empty($sellers)) {
            foreach ($sellers as $seller) {
                $store->setting(array('store_id' => $seller['seller_id']), array('status' => $status));
                $this->_seller_status($store_supplier, $store, $seller['seller_id'], $status);
            }
        }
    }


    public function distribution_index()
    {
        $this->display();
    }

    private function distribution_index_content()
    {
        $store    = M('Store');
        $product  = M('Product');
        $order    = M('Order');
        $fx_order = M('Fx_order');
        $financial_record = M('Financial_record');
        $store_supplier   = M('Store_supplier');

        $supplierId = $this->store_session['store_id'];

        //分销层级
        $levelList = array();
        $sellerLevelList = $store_supplier->getAllSellerId($supplierId);
        foreach($sellerLevelList as $sellerList) {
            $levelList[] = $sellerList['level'];
        }
        if(count($levelList)>0) {
            $maxLevel = $levelList[array_search(max($levelList), $levelList)];
        } else {
            $maxLevel = 1;
        }


        //分销商数量
        $all_sellers = M('Store')->getSellerCountBySales(array(), 0, 0);

        //七天销售额、佣金
        $days_7_sales   = array();
        $days_7_profits = array();

        $count_num_seller = array();
        $count_num_all_seller = array();
        $days = array();
        $tmp_days = array();
        for($i=6; $i>=0; $i--){
            $day = date("Y-m-d",strtotime('-'.$i.'day'));
            $days[] = $day;
        }
        //七日新增分销商
        $days_7_sellers = array();
        $days_7_product_sales = 0;
        $days_7_sales_total = 0;
        $days_7_orders = array();
        //七日新增分销商统计
        $days_7_new_sellers = 0;
        $days_7_min = min($days);
        $days_7_max = max($days);
        //开始时间
        $start_time = strtotime($days_7_min . ' 00:00:00');
        //结束时间
        $stop_time = strtotime($days_7_max . ' 23:59:59');
        $tmp_days_7_new_sellers = M('Store')->getSellerCountBySales(array(), $start_time, $stop_time);
        $days_7_new_sellers = $tmp_days_7_new_sellers;

        foreach ($days as $day) {
            //开始时间
            $start_time = strtotime($day . ' 00:00:00');
            //结束时间
            $stop_time = strtotime($day . ' 23:59:59');
            $tmp_sellers = M('Store')->getSellerBySales(array(), $start_time, $stop_time);
            $tmp_days_7_sales = 0;
            $tmp_days_7_orders = 0;
            foreach ($tmp_sellers as $tmp_seller) {
                $tmp_arr = explode('-', $tmp_seller['sales']);
                $tmp_days_7_sales += $tmp_arr[0];
                $tmp_days_7_orders += $tmp_arr[1];
            }
            $days_7_sales_total += $tmp_days_7_sales;
            $days_7_sales[] = !empty($tmp_days_7_sales) ? number_format($tmp_days_7_sales, 2, '.', '') : 0;

            $where = array();
            $where['store_id'] = $this->store_session['store_id'];
            $where['_string'] = "add_time >= " . $start_time . " AND add_time < " . $stop_time;
            $tmp_days_7_profits = $financial_record->drpProfit($where);
            $days_7_profits[] = !empty($tmp_days_7_profits) ? number_format($tmp_days_7_profits, 2, '.', '') : 0;

            //分销商总数
            $where = array();
            $tmp_every_day_sellers = M('Store')->getSellerCountBySales($where, 0, $stop_time);
            $days_7_sellers[] = $tmp_every_day_sellers;

            //七日分销量（商品）
            $where = array();
            $where['is_fx']         = 1;
            $where['_string']       = "FIND_IN_SET(" . $this->store_session['store_id'] . ", suppliers) AND paid_time >= " . $start_time . ' AND paid_time <= ' . $stop_time;
            $tmp_sales = D('Order')->where($where)->sum('pro_num');
            $days_7_product_sales += $tmp_sales;

            //七日订单量
            $days_7_orders[] = $tmp_days_7_orders;

            $tmp_days[] = "'" . $day . "'";
        }

        $days_7_sellers = '[' . implode(',', $days_7_sellers) . ']';
        $days_7_orders = '[' . implode(',', $days_7_orders) . ']';
        $days = '[' . implode(',', $tmp_days) . ']';
        $days_7_sales   = '[' . implode(',', $days_7_sales) . ']';
        $days_7_profits = '[' . implode(',', $days_7_profits) . ']';

        $this->assign('days', $days);
        $this->assign('days_7_sales', $days_7_sales);
        $this->assign('days_7_profits', $days_7_profits);
        $this->assign('days_7_new_sellers', $days_7_new_sellers); //七日新增
        $this->assign('days_7_sellers', $days_7_sellers); //七日新增
        $this->assign('maxLevel', $maxLevel);
        $this->assign('all_sellers', $all_sellers);
        $this->assign('days_7_orders', $days_7_orders);//七日订单量
        $this->assign('days_7_product_sales', $days_7_product_sales);
        $this->assign('days_7_sales_total', number_format($days_7_sales_total, 2, '.', ''));//
    }

    public function distribution()
    {
        $this->display();
    }

    private function distribution_rank_content()
    {
        $store_supplier = M('Store_supplier');
        $store          = M('Store');

        $supplierId = $this->store_session['store_id'];

        $where = array();

        if (!empty($_POST['start_time']) && !empty($_POST['end_time'])) {
            $startTime = strtotime($_POST['start_time']);
            $endTime   = strtotime($_POST['end_time']);
        }

        $sellers_count = $store->getSellerCountBySales($where,  $startTime, $endTime);
        import('source.class.user_page');
        $page = new Page($sellers_count, 15);

        $sellerRank = $store->getSellersBySales($where, $page->firstRow, $page->listRows,$startTime, $endTime);
        foreach ($sellerRank as &$seller) {
            $seller['sales'] = number_format($seller['sales'], 2, '.', '');
            $seller['logo']  = getAttachmentUrl($seller['logo']);
        }

        $this->assign('sellerRank', $sellerRank);
        $this->assign('page', $page->show());
    }

    public function my_seller_detail()
    {
        $store_supplier = M('Store_supplier');
        $supplierId = $this->store_session['store_id'];
        $where = array();
        $where['s.status'] = array('>',0);

        // 判断当前登录帐号等级
        $currentLevel = $store_supplier->getSeller(array(
            'supplier_id' => $supplierId
        ));

        $this->assign('currentLevel', $currentLevel['level']);

        $this->display();
    }

    private function _seller_detail_content($data)
    {
        $store_supplier = M('Store_supplier');
        $store = M('Store');
        $order = M('Order');

        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $approve = isset($_POST['approve']) ? trim($_POST['approve']) : '*';
        $level = intval(isset($_POST['level']) ? trim($_POST['level']) : '1');
        $supplierId = empty($data['store_id']) ? $this->store_session['store_id'] : $data['store_id'];
        $nextSellerNum = $this->getNexSellers($supplierId);
        $nextTwoSellerNum = $this->getNexTwoSellers($supplierId);

        $where = array();
        $where['s.status'] = array('>',0);

        if ($keyword != '') {
            $where['s.name'] = array('like' => '%' . $keyword . '%');
        }
        if (is_numeric($approve) || $approve != '*') {
            $where['s.drp_approve'] = $approve;
        }
        if (!empty($_SESSION['store_sync'])) {
            $where['ss.type'] = 1;
        }

        /* 获取当前等级 */
        $currentLevel = $store_supplier->getSeller(array(
            'seller_id' => $supplierId
        ));

        $prevSellerNum = $currentLevel['level']-1;
        $sellerInfo = $store->getStore($supplierId);    //分销商详情

        //判断当前分销商等级
        $currentSellerLevel = $store_supplier->getSeller(array(
            'seller_id' => $supplierId
        ));

        /* 上级所有分销商 */
        if ($level == '1')
        {
            $supplierSpan = $store_supplier->getSeller(array(
                'seller_id' => $supplierId
            ));

            for ($i=0; $i<$currentSellerLevel['level']; $i++)
            {
                $supplierSpan = $store_supplier->getSeller(array(
                    'seller_id' => $supplierId
                ));

                $supplierIdList[] = $supplierSpan['supplier_id'];

                $supplierId =  $supplierSpan['supplier_id'];
            }

        }
        else if ($level == '2')
        {
            $sellerList = $store_supplier->getNextSellers($supplierId, $currentSellerLevel['level']+1);
        }
        else if($level == '3')
        {
            $sellerList = $store_supplier->getNextSellers($supplierId, $currentSellerLevel['level']+2);
        }

        if($level == '1')
        {
            $sellerIdList = rtrim(implode(',', $supplierIdList), ',');
        }
        else
        {
            $sellerIdList = array ();
            if (count($sellerList) > 0)
            {
                foreach ($sellerList as $sellerId)
                {
                    $sellerIdList[] = $sellerId['seller_id'];
                }

                $sellerIdList = rtrim(implode(',', $sellerIdList), ',');
            }
        }

        $where['ss.seller_id'] = array ('in' => $sellerIdList);

        $seller_count = $store_supplier->seller_count($where);
        import('source.class.user_page');
        $page = new Page($seller_count, 15);
        $tmp_sellers = $store_supplier->sellers($where, $page->firstRow, $page->listRows);

        $sellers = array();
        foreach ($tmp_sellers as $tmp_seller) {
            $sales = $order->getSales(array('store_id' => $tmp_seller['store_id'], 'is_fx' => 1, 'status' => array('in', array(2,3,4,7))));
            $profit = $tmp_seller['income'];
            $sellers[] = array(
                'store_id'       => $tmp_seller['store_id'],
                'name'           => $tmp_seller['name'],
                'drp_level'      => $tmp_seller['drp_level'],
                'service_tel'    => $tmp_seller['service_tel'],
                'service_qq'     => $tmp_seller['service_qq'],
                'service_weixin' => $tmp_seller['service_weixin'],
                'drp_approve'    => $tmp_seller['drp_approve'],
                'status'         => $tmp_seller['status'],
                'sales'          => !empty($sales) ? number_format($sales, 2, '.', '') : '0.00',
                'profit'         => !empty($profit) ? number_format($profit, 2, '.', '') : '0.00'
            );
        }

        $this->assign('sellerList', $sellerList);
        $this->assign('sellers', $sellers);
        $this->assign('page', $page->show());
        $this->assign('sellerInfo', $sellerInfo);
        $this->assign('currentLevel', $currentLevel);
        $this->assign('prevSellerNum', $prevSellerNum);
        $this->assign('nextSellerNum', $nextSellerNum);
        $this->assign('level', $level);
        $this->assign('nextTwoSellerNum', $nextTwoSellerNum);
    }

    //获取下级分销商数量
    private function getNexSellers($supplierId)
    {
        $store_supplier = M('Store_supplier');

        $supplierSpanLevel = $store_supplier->getSeller(array(
            'supplier_id' => $supplierId
        ));

        $supplierLevelOne =  explode(',', $supplierSpanLevel['supply_chain']);
        $supplierLevelOne = $supplierLevelOne[1];

        $sellerList = $store_supplier->getNextAllSellers($supplierLevelOne, $supplierSpanLevel['level']);
        $sellerIdList = array();
        if (count($sellerList)>0)
        {
            foreach ($sellerList as $sellerId)
            {
                $sellerIdList[] = $sellerId['seller_id'];
            }
            $sellerIdList = rtrim(implode(',', $sellerIdList), ',');
        }

        $where['seller_id'] = array ('in' => $sellerIdList);
        $nextSellerNum = $store_supplier->getNextSeller($where,$supplierSpanLevel['level']);

        return $nextSellerNum;
    }

    //获取下两级分销商数量
    private function getNexTwoSellers($supplierId)
    {
        $store_supplier = M('Store_supplier');

        $supplierSpanLevel = $store_supplier->getSeller(array(
            'supplier_id' => $supplierId
        ));

        $sellerList = $store_supplier->getNextTwoCount($supplierId, $supplierSpanLevel['level'], $supplierSpanLevel['level']+1);

        return $sellerList;
    }

    //设置批发
    public function goods_wholesale_setting()
    {
        if (IS_POST) {
            $product = M('Product');
            $product_sku = M('Product_sku');

            $product_id = !empty($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
            $cost_price = !empty($_POST['wholesale_price']) ? floatval(trim($_POST['wholesale_price'])) : 0; // 批发价
            $min_fx_price = !empty($_POST['sale_min_price']) ? floatval(trim($_POST['sale_min_price'])) : 0; //最低零售价
            $max_fx_price = !empty($_POST['sale_max_price']) ? floatval(trim($_POST['sale_max_price'])) : 0; //最高零售价
            $is_recommend = !empty($_POST['is_recommend']) ? intval(trim($_POST['is_recommend'])) : 0;

            $skus = !empty($_POST['skus']) ? $_POST['skus'] : array();

            $fx_type = 0; //分销类型 0全网、1排他
            if (strtolower(trim($_GET['role'])) == 'seller' || !empty($this->store_session['drp_supplier_id'])) {
                $fx_type = 1;
            }
            $data = array(
                'wholesale_price'    => $cost_price,
                'sale_min_price'  => $min_fx_price,
                'sale_max_price'  => $max_fx_price,
                'is_recommend'  => $is_recommend,
                'is_wholesale'  => 1,
                'fx_type'       => $fx_type,
            );

            $result = D('Product')->where(array('product_id' => $product_id))->data($data)->save();
            if ($result) {
                if (!empty($skus)) {
                    $product_sku->wholesaleEdit($product_id, $skus);
                }
                if (strtolower(trim($_GET['role'])) == 'seller') {
                    json_return(0, url('goods'));
                } else {
                    json_return(0, url('supplier_market'));
                }
            } else {
                json_return(1001, '保存失败');
            }
        }
        $this->display();
    }

    private function _goods_wholesale_setting_content()
    {
        $product = M('Product');
        $category = M('Product_category');
        $product_property = M('Product_property');
        $product_property_value = M('Product_property_value');
        $product_to_property = M('Product_to_property');
        $product_to_property_value = M('Product_to_property_value');
        $product_sku = M('Product_sku');

        $id = isset($_POST['id']) ? intval(trim($_POST['id'])) : 0;

        $product = $product->get(array('product_id' => $id, 'store_id' => $this->store_session['store_id']));

        if (!empty($product['supplier_id'])) { //分销商
            $edit_cost_price = false;
            $readonly = '';
        } else { //供货商
            $edit_cost_price = true;
            $readonly = '';
        }
        if (!empty($product['category_id']) && !empty($product['category_fid'])) {
            $parent_category = $category->getCategory($product['category_fid']);
            $category = $category->getCategory($product['category_id']);
            $product['category'] = $parent_category['cat_name'] . ' - ' . $category['cat_name'];
        } else if ($product['category_fid']) {
            $category = $category->getCategory($product['category_fid']);
            $product['category'] = $category['cat_name'];
        } else {
            $category = $category->getCategory($product['category_id']);
            $product['category'] = !empty($category['cat_name']) ? $category['cat_name'] : '其它';
        }

        $pids = $product_to_property->getPids($this->store_session['store_id'], $id);

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
                $html .= '        <th class="text-center" width="80">' . $name . '</th>';
                $html .= '        <th class="text-center" width="80">' . $name1 . '</th>';
                $html .= '        <th class="text-center" width="80">' . $name2 . '</th>';
                $html .= '        <th class="th-price" style="width: 70px;text-align: center">批发价（元）</th>';
                $html .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                $html2 = '<thead>';
                $html2 .= '    <tr>';
                $html2 .= '        <th class="text-center" width="80">' . $name . '</th>';
                $html2 .= '        <th class="text-center" width="80">' . $name1 . '</th>';
                $html2 .= '        <th class="text-center" width="80">' . $name2 . '</th>';
                $html2 .= '        <th class="th-price" style="width: 70px;text-align: center">批发价（元）</th>';
                $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html2 .= '    </tr>';
                $html2 .= '</thead>';
                $html2 .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    foreach ($vids1 as $key1 => $vid1) {
                        $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                        foreach ($vids2 as $key2 => $vid2) {
                            $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'] . ';' . $pid2 . ':' . $vid2['vid'];
                            $sku = $product_sku->getSku($id, $properties);
                            $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                            $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                            $value2 = $product_property_value->getValue($pid2, $vid2['vid']);
                            if($key1 == 0 && $key2 == 0) {
                                $html .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
                                $html2 .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
                            }
                            if($key2 == 0) {
                                $html .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
                                $html2 .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
                            }
                            $html .= '        <td class="text-center" width="50">' . $value2 . '</td>';
                            $html .= '        <td style="text-align: center"><input type="text" name="wholesale_price" class="js-cost-price-one input-mini" maxlength="10" />';
                            $html .= '        <td style="text-align: center"><input type="text" name="sale_min_price" class="js-price-two js-fx-price input-mini" maxlength="10" /> - <input type="text" name="sale_max_price" class="js-price-three js-fx-price input-mini" maxlength="10" /></td>';
                            $html .= '    </tr>';

                            $html2 .= '        <td class="text-center" width="50">' . $value2 . '</td>';
                            $html2 .= '        <td style="text-align: center"><input type="text" name="wholesale_price" class="js-cost-price-one input-mini" maxlength="10" />';
                            $html2 .= '        <td style="text-align: center"><input type="text" name="sale_min_price" class="js-price-two js-fx-price input-mini" maxlength="10" /> - <input type="text" name="sale_max_price" class="js-price-three js-fx-price input-mini" maxlength="10" /></td>';
                            $html2 .= '    </tr>';
                        }
                    }
                }
            } else if (!empty($pids[1]['pid'])) {
                $pid1 = $pids[1]['pid'];
                $name1 = $product_property->getName($pid1);
                $vids1 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid1);

                $html2 = '<thead>';
                $html2 .= '    <tr>';
                $html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html2 .= '        <th class="text-center" width="50">' . $name1 . '</th>';
                $html2 .= '        <th class="th-price" style="text-align: center">批发价（元）</th>';
                $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html2 .= '    </tr>';
                $html2 .= '</thead>';
                $html2 .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    foreach ($vids1 as $key1 => $vid1) {
                        $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'];
                        $sku = $product_sku->getSku($id, $properties);
                        $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                        $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                        if($key1 == 0) {
                            $html2 .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
                        }

                        $html2 .= '        <td class="text-center" width="50">' . $value1 . '</td>';
                        $html2 .= '        <td style="text-align: center"><input type="text" name="wholesale_price" class="js-cost-price-one input-mini" maxlength="10" />';
                        $html2 .= '        <td style="text-align: center"><input type="text" name="sale_min_price" class="js-price-two js-fx-price input-mini" maxlength="10" /> - <input type="text" name="sale_max_price" class="js-price-three js-fx-price input-mini" maxlength="10" /></td>';
                        $html2 .= '    </tr>';
                    }
                }
            } else {
                $html = '<thead>';
                $html .= '    <tr>';
                $html .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html .= '        <th class="th-price" style="text-align: center">批发价（元）</th>';
                $html .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';

                $html2 = '<thead>';
                $html2 .= '    <tr>';
                $html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html2 .= '        <th class="th-price" style="text-align: center">批发价（元）</th>';
                $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html2 .= '    </tr>';
                $html2 .= '</thead>';
                $html2 .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    $properties = $pid . ':' . $vid['vid'];
                    $sku = $product_sku->getSku($id, $properties);
                    $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                    $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    $html .= '        <td class="text-center" width="50">' . $value . '</td>';
                    $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" /></td>';
                    $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini" maxlength="10" /></td>';
                    $html .= '    </tr>';

                    $html2 .= '        <td class="text-center" width="50">' . $value . '</td>';
                    $html2 .= '        <td style="text-align: center"><input type="text" name="wholesale_price" class="js-cost-price-one input-mini" maxlength="10" />';
                    $html2 .= '        <td style="text-align: center"><input type="text" name="sale_min_price" class="js-price-two js-fx-price input-mini" maxlength="10" /> - <input type="text" name="sale_max_price" class="js-price-three js-fx-price input-mini" maxlength="10" /></td>';
                    $html2 .= '    </tr>';
                }
            }
            $html .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-cost" href="javascript:;">批发价</a>&nbsp;&nbsp;<a class="js-batch-price" href="javascript:;">零售价</a></span><span class="js-batch-form" style="display:none;"></span></div></td></tr></tfoot>';
            $html2 .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts2">批量设置： <span class="js-batch-type2"><a class="js-batch-cost2" href="javascript:;">批发价</a>&nbsp;&nbsp;<a class="js-batch-price2" href="javascript:;">零售价</a></span><span class="js-batch-form2" style="display:none;"></span></div></td></tr></tfoot>';
        }

        $this->assign('edit_cost_price', $edit_cost_price);
        $this->assign('sku_content', $html);
        $this->assign('sku_content2', $html2);
        if (!empty($product['wholesale_product_id'])) {
            $source_product = M('Product')->get(array('product_id' => $product['wholesale_product_id'], 'store_id' => $product['supplier_id']));
            $sale_min_price = $source_product['sale_min_price'];
            $sale_max_price = $source_product['sale_max_price'];
            $wholesale_price = $source_product['wholesale_price'];

        } else {
            $sale_min_price = $product['sale_min_price'];
            $sale_max_price = $product['sale_max_price'];
            $wholesale_price   = $product['wholesale_price'];
        }

        if (empty($product['wholesale_product_id'])) {
            $is_supplier = true;
        } else {
            $is_supplier = false;
        }

        $this->assign('product', $product);
        $this->assign('sale_min_price', $sale_min_price);
        $this->assign('wholesale_price', $wholesale_price);
        $this->assign('sale_max_price', $sale_max_price);

        $this->assign('is_supplier', $is_supplier);
        $this->assign('drp_level', $_SESSION['store']['drp_level']);
        $this->assign('open_drp_setting_price', $this->store_session['open_drp_setting_price']);
        $this->assign('unified_price_setting', $this->store_session['unified_price_setting']);
    }


    //客服联系方式
    public function service()
    {
        if (IS_POST && strtolower($_POST['type']) == 'check') {
            $store = M('Store');
            $store = $store->getStore($this->store_session['store_id']);
            if (empty($store['service_tel']) && empty($store['service_qq']) && empty($store['service_weixin'])) {
                json_return(1001, '没有填写客服联系方式');
            } else {
                json_return(0, '客服联系方式已填写');
            }
        } else if (IS_POST && strtolower($_POST['type']) == 'add') {
            $store = M('Store');
            $data = array();
            $data['service_tel']    = isset($_POST['tel']) ? trim($_POST['tel']) : '';
            $data['service_qq']     = isset($_POST['qq']) ? trim($_POST['qq']) : '';
            $data['service_weixin'] = isset($_POST['weixin']) ? trim($_POST['weixin']) : '';
            $where = array();
            $where['store_id'] = $this->store_session['store_id'];
            if ($store->setting($where, $data)) {
                json_return(0, '保存成功');
            } else {
                json_return(1001, '保存失败，请重新提交');
            }
        }
    }

    public function contact_information()
    {
          if(IS_POST){
              $store = M('Store');
              $data = array();
              $data['service_tel']    = isset($_POST['tel']) ? trim($_POST['tel']) : '';
              $data['service_qq']     = isset($_POST['qq']) ? trim($_POST['qq']) : '';
              $data['service_weixin'] = isset($_POST['weixin']) ? trim($_POST['weixin']) : '';
              $where = array();
              $where['store_id'] = $this->store_session['store_id'];
              if ($store->setting($where, $data)) {
                  json_return(0, '保存成功');
              } else {
                  json_return(1001, '保存失败，请重新提交');
              }
          }
          $this->display();
    }

    private  function contact_information_content()
    {
          $store = M('Store');
          $information = $store->getStore($this->store_session['store_id']);
          $this->assign('information', $information);
    }


    public function edit_wholesale()
    {
        if (IS_POST) {
            $product = M('Product');
            $product_sku = M('Product_sku');
            $product_id = !empty($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
            $cost_price = !empty($_POST['wholesale_price']) ? floatval(trim($_POST['wholesale_price'])) : 0; // 批发价
            $min_fx_price = !empty($_POST['sale_min_price']) ? floatval(trim($_POST['sale_min_price'])) : 0; //最低零售价
            $max_fx_price = !empty($_POST['sale_max_price']) ? floatval(trim($_POST['sale_max_price'])) : 0; //最高零售价
            $is_recommend = !empty($_POST['is_recommend']) ? intval(trim($_POST['is_recommend'])) : 0;
            $unified_price_setting = !empty($_POST['unified_price_setting']) ? $_POST['unified_price_setting'] : 0;

            $skus = !empty($_POST['skus']) ? $_POST['skus'] : array();

            $fx_type = 0; //分销类型 0全网、1排他
            if (strtolower(trim($_GET['role'])) == 'seller' || !empty($this->store_session['drp_supplier_id'])) {
                $fx_type = 1;
            }
            $data = array(
                'wholesale_price'    => $cost_price,
                'sale_min_price'  => $min_fx_price,
                'sale_max_price'  => $max_fx_price,
                'is_recommend'  => $is_recommend,
                'is_wholesale'  => 1,
                'fx_type'       => $fx_type,
                'unified_price_setting' => $unified_price_setting,
            );

            $result = D('Product')->where(array('product_id' => $product_id))->data($data)->save();
            if ($result) {
                if (count($skus)>0) {
                    $product_sku->wholesaleEdit($product_id, $skus);
                }

               json_return(0, url('supplier_market'));

            } else {
                json_return(1001, '保存失败');
            }
        }
        $this->display();
    }


    private function edit_wholesale_content()
    {
        $product = M('Product');
        $category = M('Product_category');
        $product_property = M('Product_property');
        $product_property_value = M('Product_property_value');
        $product_to_property = M('Product_to_property');
        $product_to_property_value = M('Product_to_property_value');
        $product_sku = M('Product_sku');

        $id = isset($_POST['id']) ? intval(trim($_POST['id'])) : 0;

        $product = $product->get(array('product_id' => $id, 'store_id' => $this->store_session['store_id']));

        if (!empty($product['supplier_id'])) { //分销商
            $edit_cost_price = false;
            $readonly = '';
        } else { //供货商
            $edit_cost_price = true;
            $readonly = '';
        }
        if (!empty($product['category_id']) && !empty($product['category_fid'])) {
            $parent_category = $category->getCategory($product['category_fid']);
            $category = $category->getCategory($product['category_id']);
            $product['category'] = $parent_category['cat_name'] . ' - ' . $category['cat_name'];
        } else if ($product['category_fid']) {
            $category = $category->getCategory($product['category_fid']);
            $product['category'] = $category['cat_name'];
        } else {
            $category = $category->getCategory($product['category_id']);
            $product['category'] = !empty($category['cat_name']) ? $category['cat_name'] : '其它';
        }

        $pids = $product_to_property->getPids($this->store_session['store_id'], $id);

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
                $html .= '        <th class="text-center" width="80">' . $name . '</th>';
                $html .= '        <th class="text-center" width="80">' . $name1 . '</th>';
                $html .= '        <th class="text-center" width="80">' . $name2 . '</th>';
                $html .= '        <th class="th-price" style="width: 70px;text-align: center">批发价（元）</th>';
                $html .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                $html2 = '<thead>';
                $html2 .= '    <tr>';
                $html2 .= '        <th class="text-center" width="80">' . $name . '</th>';
                $html2 .= '        <th class="text-center" width="80">' . $name1 . '</th>';
                $html2 .= '        <th class="text-center" width="80">' . $name2 . '</th>';
                $html2 .= '        <th class="th-price" style="width: 70px;text-align: center">批发价（元）</th>';
                $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html2 .= '    </tr>';
                $html2 .= '</thead>';
                $html2 .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    foreach ($vids1 as $key1 => $vid1) {
                        $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                        foreach ($vids2 as $key2 => $vid2) {
                            $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'] . ';' . $pid2 . ':' . $vid2['vid'];
                            $sku = $product_sku->getSku($id, $properties);
                            $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                            $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                            $value2 = $product_property_value->getValue($pid2, $vid2['vid']);
                            if($key1 == 0 && $key2 == 0) {
                                $html .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
                                $html2 .= '    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' . $value . '</td>';
                            }
                            if($key2 == 0) {
                                $html .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
                                $html2 .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 . '</td>';
                            }
                            $html .= '        <td class="text-center" width="50">' . $value2 . '</td>';
                            $html .= '        <td style="text-align: center"><input type="text" name="wholesale_price" class="js-cost-price-one input-mini" maxlength="10" value='.$sku['wholesale_price'].' /></td>';
                            $html .= '        <td style="text-align: center"><input type="text" name="sale_min_price" class="js-price-two js-fx-price input-mini" maxlength="10" value='.$sku['sale_min_price'].' /> - <input type="text" name="sale_max_price" class="js-price-three js-fx-price input-mini" maxlength="10" value='.$sku['sale_max_price'].' /></td>';
                            $html .= '    </tr>';

                            $html2 .= '        <td class="text-center" width="50">' . $value2 . '</td>';
                            $html2 .= '        <td style="text-align: center"><input type="text" name="wholesale_price" class="js-cost-price-one input-mini" maxlength="10" value='.$sku['wholesale_price'].' /></td>';
                            $html2 .= '        <td style="text-align: center"><input type="text" name="sale_min_price" class="js-price-two js-fx-price input-mini" maxlength="10" value='.$sku['sale_min_price'].' /> - <input type="text" name="sale_max_price" class="js-price-three js-fx-price input-mini" maxlength="10" value='.$sku['sale_max_price'].' /></td>';
                            $html2 .= '    </tr>';
                        }
                    }
                }
            } else if (!empty($pids[1]['pid'])) {
                $pid1 = $pids[1]['pid'];
                $name1 = $product_property->getName($pid1);
                $vids1 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid1);

                $html2 = '<thead>';
                $html2 .= '    <tr>';
                $html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html2 .= '        <th class="text-center" width="50">' . $name1 . '</th>';
                $html2 .= '        <th class="th-price" style="text-align: center">批发价（元）</th>';
                $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html2 .= '    </tr>';
                $html2 .= '</thead>';
                $html2 .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    foreach ($vids1 as $key1 => $vid1) {
                        $properties = $pid . ':' . $vid['vid']. ';' . $pid1 . ':' . $vid1['vid'];
                        $sku = $product_sku->getSku($id, $properties);
                        $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                        $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                        if($key1 == 0) {
                            $html2 .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
                        }

                        $html2 .= '        <td class="text-center" width="50">' . $value1 . '</td>';
                        $html2 .= '        <td style="text-align: center"><input type="text" name="wholesale_price" class="js-cost-price-one input-mini" maxlength="10" value='.$sku['wholesale_price'].' /></td> ';
                        $html2 .= '        <td style="text-align: center"><input type="text" name="sale_min_price" class="js-price-two js-fx-price input-mini" maxlength="10" value='.$sku['sale_min_price'].' /> - <input type="text" name="sale_max_price" class="js-price-three js-fx-price input-mini" maxlength="10" value='.$sku['sale_max_price'].' /></td>';
                        $html2 .= '    </tr>';
                    }
                }
            } else {
                $html = '<thead>';
                $html .= '    <tr>';
                $html .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html .= '        <th class="th-price" style="text-align: center">批发价（元）</th>';
                $html .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html .= '    </tr>';
                $html .= '</thead>';
                $html .= '<tbody>';

                $html2 = '<thead>';
                $html2 .= '    <tr>';
                $html2 .= '        <th class="text-center" width="50">' . $name . '</th>';
                $html2 .= '        <th class="th-price" style="text-align: center">批发价（元）</th>';
                $html2 .= '        <th class="th-price" style="width: 105px;text-align: center">零售价（元）</th>';
                $html2 .= '    </tr>';
                $html2 .= '</thead>';
                $html2 .= '<tbody>';
                foreach ($vids as $key => $vid) {
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    $properties = $pid . ':' . $vid['vid'];
                    $sku = $product_sku->getSku($id, $properties);
                    $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                    $html2 .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] . '">';
                    $value = $product_property_value->getValue($pid, $vid['vid']);
                    $html .= '        <td class="text-center" width="50">' . $value . '</td>';
                    $html .= '        <td style="text-align: center"><input type="text" name="cost_sku_price" class="js-cost-price input-mini" maxlength="10" value='.$sku['wholesale_price'].' /></td>';
                    $html .= '        <td style="text-align: center"><input type="text" name="sku_price" class="js-price js-fx-min-price input-mini" maxlength="10" value='.$sku['sale_min_price'].' /> - <input type="text" name="sku_price" class="js-price js-fx-max-price input-mini" maxlength="10" value='.$sku['sale_max_price'].' /></td>';
                    $html .= '    </tr>';

                    $html2 .= '        <td class="text-center" width="50">' . $value . '</td>';
                    $html2 .= '        <td style="text-align: center"><input type="text" name="wholesale_price" class="js-cost-price-one input-mini" maxlength="10" value='.$sku['wholesale_price'].' />';
                    $html2 .= '        <td style="text-align: center"><input type="text" name="sale_min_price" class="js-price-two js-fx-price input-mini" maxlength="10" value='.$sku['sale_min_price'].' /> - <input type="text" name="sale_max_price" class="js-price-three js-fx-price input-mini" maxlength="10" value='.$sku['sale_max_price'].' /></td>';
                    $html2 .= '    </tr>';
                }
            }
            $html .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-cost" href="javascript:;">批发价</a>&nbsp;&nbsp;<a class="js-batch-price" href="javascript:;">零售价</a></span><span class="js-batch-form" style="display:none;"></span></div></td></tr></tfoot>';
            $html2 .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts2">批量设置： <span class="js-batch-type2"><a class="js-batch-cost2" href="javascript:;">批发价</a>&nbsp;&nbsp;<a class="js-batch-price2" href="javascript:;">零售价</a></span><span class="js-batch-form2" style="display:none;"></span></div></td></tr></tfoot>';
        }

        $this->assign('edit_cost_price', $edit_cost_price);
        $this->assign('sku_content', $html);
        $this->assign('sku_content2', $html2);
        if (!empty($product['wholesale_product_id'])) {
            $source_product = M('Product')->get(array('product_id' => $product['wholesale_product_id'], 'store_id' => $product['supplier_id']));
            $sale_min_price = $source_product['sale_min_price'];
            $sale_max_price = $source_product['sale_max_price'];
            $wholesale_price = $source_product['wholesale_price'];

        } else {
            $sale_min_price = $product['sale_min_price'];
            $sale_max_price = $product['sale_max_price'];
            $wholesale_price   = $product['wholesale_price'];
        }

        if (empty($product['wholesale_product_id'])) {
            $is_supplier = true;
        } else {
            $is_supplier = false;
        }

        $this->assign('product', $product);
        $this->assign('sale_min_price', $sale_min_price);
        $this->assign('wholesale_price', $wholesale_price);
        $this->assign('sale_max_price', $sale_max_price);

        $this->assign('is_supplier', $is_supplier);
        $this->assign('drp_level', $_SESSION['store']['drp_level']);

    }

    public function commission_detail()
    {
        $this->display();
    }

    private function _commission_detail_content()
    {
        $order         = M('Order');
        $order_product = M('Order_product');

        $order_id = intval(trim($_POST['order_id']));

        $order_info = $order->getOrder($this->store_session['store_id'], $order_id);
        $user_order_id = !empty($order_info['user_order_id']) ? $order_info['user_order_id'] : $order_id;

        $where = array();
        $where['_string'] = "(order_id = '" . $user_order_id . "' OR user_order_id = '" . $user_order_id . "')";
        if (!empty($this->store_session['drp_supplier_id'])) {
            if (empty($order_info['user_order_id'])) {
                $tmp_order = D('Order')->field('order_id')->where(array('order_id' => $order_id, 'order_id' => array('>', $order_id)))->order('order_id ASC')->find();
            } else {
                $tmp_order = D('Order')->field('order_id')->where(array('user_order_id' => $user_order_id, 'order_id' => array('>', $order_id)))->order('order_id ASC')->find();
            }
            $tmp_order_id = $tmp_order['order_id'];
            $where['_string'] .= " AND order_id <= " . $tmp_order_id;
        }
        $orders = D('Order')->where($where)->order('order_id DESC')->select();
        $filter_postage = array();
        $filter_order = array();
        $filter_products = array();
        foreach ($orders as $key => &$tmp_order) {
            $is_filter = false;
            $store = D('Store')->field('store_id,name,drp_level,drp_supplier_id')->where(array('store_id' => $tmp_order['store_id']))->find();
            $tmp_order['seller']           = $store['name'];
            $tmp_order['seller_drp_level'] = $store['drp_level'];

            if (empty($tmp_order['suppliers']) && empty($order_info['suppliers']) && $tmp_order['store_id'] != $this->store_session['store_id']) {
                $filter_postage[$tmp_order['store_id']] = $tmp_order['postage'];
                $filter_order[$tmp_order['store_id']] = $tmp_order['order_id'];
                $is_filter = true;
                unset($orders[$key]); //过滤非当前店铺的订单
            }

            if (!$is_filter && !empty($tmp_order['suppliers'])) {
                $suppliers = explode(',', $tmp_order['suppliers']);
                foreach ($filter_postage as $supplier_id => $postage) {
                    if (in_array($supplier_id, $suppliers)) {
                        $tmp_order['postage']   -= $postage;
                        $tmp_order['total']     -= $postage;
                        $tmp_order['profit']    -= $postage;

                        $filter_order_id = $filter_order[$supplier_id];
                        $tmp_filter_products = $order_product->getProducts($filter_order_id);
                        foreach ($tmp_filter_products as $tmp_product) {
                            $filter_products[] = $tmp_product['product_id'];
                        }
                    }
                }
            }

            $profit = D('Financial_record')->where(array('order_id' => $tmp_order['order_id']))->sum('income');
            $tmp_order['profit'] = number_format($profit, 2, '.', '');

            $tmp_order['seller_store'] = option('config.wap_site_url') . '/home.php?id=' . $tmp_order['store_id'];

            $products = $order_product->getProducts($tmp_order['order_id']);
            $comment_count = 0;
            $product_count = 0;
            foreach ($products as $key2 => &$product) {

                //过滤商品
                if (in_array($product['original_product_id'], $filter_products)) {
                    $tmp_order['sub_total'] -= ($product['pro_price'] * $product['pro_num']);
                    $tmp_order['total']     -= ($product['pro_price'] * $product['pro_num']);
                    $tmp_order['profit']    -= ($product['profit'] * $product['pro_num']);
                    unset($products[$key2]);
                } else {
                    if (!empty($product['comment'])) {
                        $comment_count++;
                    }
                    $product_count++;

                    //商品来源
                    if (empty($product['supplier_id']) && $product['store_id'] == $tmp_order['store_id']) { //本店商品
                        $from = '自营商品';
                    } else if (!empty($product['supplier_id']) && $product['store_id'] == $tmp_order['store_id'] && !empty($product['wholesale_product_id'])) { //批发商品
                        $from = '批发商品';
                    } else { //分销商品
                        $from = '分销商品';
                    }
                    $product['from'] = $from;

                    $product['cost_price'] = ($product['pro_price'] - $product['profit'] > 0) ? $product['pro_price'] - $product['profit'] : 0;
                    if ($product['profit'] == 0 && empty($product['supplier_id']) && $product['store_id'] == $this->store_session['store_id']) {
                        $product['profit']     = $product['pro_price'];
                        $product['cost_price'] = 0;
                    }
                    $product['cost_price'] = number_format($product['cost_price'], 2, '.', '');

                    if (!empty($product['wholesale_product_id']) && $product['store_id'] == $tmp_order['store_id']) {
                        $tmp_order['is_wholesale'] = true;
                    }
                }
            }
            $tmp_order['products']      = $products;
            $tmp_order['rows']          = $comment_count + $product_count;
            $tmp_order['comment_count'] = $comment_count;
            $tmp_order['postage']       = number_format($tmp_order['postage'], 2, '.', '');
        }

        $this->assign('orders', $orders);
        $this->assign('order', $order_info);
    }


    /* 我的批发商品 */
    public function my_wholesale()
    {
        $this->display();
    }

    private function my_wholesale_content()
    {
        $product = M('Product');
        $product_group = M('Product_group');
        $product_to_group = M('Product_to_group');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $group_id = isset($_POST['group_id']) ? trim($_POST['group_id']) : '';

        $where = array();
        $where['store_id'] = $this->store_session['store_id'];
        $where['quantity'] = array('>', 0);
        $where['soldout'] = 0;
        $where['wholesale_product_id'] = array('>', 0);
        $where['uid'] = array('=', $this->user_session['uid']);
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
        $tmp_product = $product->getWholesale($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);

        $products = array();
        foreach($tmp_product as $product)
        {
            /* 商品供货商 */
            $supplier_store_id = D('Product')->field('store_id')->where(array('product_id'=>$product['wholesale_product_id']))->find();
            $supplier_name = D('Store')->field('name')->where(array('store_id'=>$supplier_store_id['store_id']))->find();
            $products[] = array(
                'supplier_name' => $supplier_name['name'], // 商品供货商
                'name' => $product['name'],
                'wholesale_price' => $product['wholesale_price'], //批发价
                'quantity' => $product['quantity'], //库存
                'sale_min_price' => $product['sale_min_price'], //最低价
                'sale_max_price' => $product['sale_max_price'], //最低价
                'image' => $product['image'],
                'sales' => $product['sales'],  //销量
                'is_recommend' => $product['is_recommend'],  //是否推荐
                'status' => $product['status'],
                'product_id' => $product['product_id'],
                'supplier_id' => $product['supplier_id']

            );
        }
        $product_groups = $product_group->get_all_list($this->store_session['store_id']);

        $this->assign('product_groups', $product_groups);
        $this->assign('product_groups_json', json_encode($product_groups));
        $this->assign('page', $page->show());
        $this->assign('products', $products);
    }


    /* 我的供货商 */
    public function my_supplier()
    {
        $this->display();
    }


    private function my_supplier_content()
    {
        $store_supplier = M('Store_supplier');
        $store = M('Store');

        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $sellerId = $this->store_session['store_id'];    //当前供货商id

        $where = array();

        /* 获取供货商id */
        $supplierIdList = $store_supplier->getSupplierList($sellerId);
        $idList = array();
        foreach($supplierIdList as $supplierId)
        {
            $idList[] = $supplierId['supplier_id'];
        }

        $supplierIdList = implode(',', $idList);

        $where['store_id'] = array ('in'=> $supplierIdList);

        if (!empty($keyword)) {
            $where['name'] = array('like' => '%' . $keyword . '%');
        }

        $supplier_count = $store_supplier->supplier_count($where);

        import('source.class.user_page');
        $page = new Page($supplier_count, 15);
        $suppliers = $store_supplier->suppliers($where, $page->firstRow, $page->listRows);

        $this->assign('suppliers', $suppliers);
        $this->assign('page', $page->show());

    }

    // 分销订单
    public function wholesale_order()
    {
        if (IS_POST && strtolower($_POST['type'] == 'pay')) {
            $fx_order = M('Fx_order');
            $order_id = isset($_POST['order_id']) ? trim($_POST['order_id']) : 0;
            $trade_no = date('YmdHis',$_SERVER['REQUEST_TIME']).mt_rand(100000,999999);
            $where = array();
            $where['_string'] = 'fx_order_id IN(' . $order_id . ')';
            if ($fx_order->edit($where, array('fx_trade_no' => $trade_no))) {
                json_return(0, url('pay_order', array('trade_no' => $trade_no)));
            } else {
                json_return(1001, '操作失败');
            }
        }
        $this->display();
    }

    private function wholesale_order_content()
    {
        $store = M('Store');
        $store_supplier = M('Store_supplier');
        $order = M('Order');
        $fx_order = M('Fx_order');
        $order_product = M('Fx_order_product');

        $where = array();
        $where['type'] = 4;
        $where['s.status'] = array('>', 0);
        $where['s.store_id'] = $this->store_session['store_id'];
        if (!empty($_POST['order_no'])) {
            $where['ss.order_no'] = $_POST['order_no'];
        }
        if (!empty($_POST['fx_order_no'])) {
            $where['ss.fx_order_no'] = $_POST['fx_order_no'];
        }
        if (!empty($_POST['delivery_user'])) {
            $where['ss.delivery_user'] = $_POST['delivery_user'];
        }
        if (!empty($_POST['supplier_id'])) {
            $where['s.supplier_id'] = $_POST['supplier_id'];
        }
        if (!empty($_POST['status'])) {
            $where['s.status'] = $_POST['status'];
        }
        if (!empty($_POST['delivery_tel'])) {
            $where['ss.delivery_tel'] = $_POST['delivery_tel'];
        }
        if (!empty($_POST['start_time']) && !empty($_POST['stop_time'])) {
            $where['_string'] = "ss.add_time >= " . strtotime($_POST['start_time']) . " AND ss.add_time <= " . strtotime($_POST['stop_time']);
        } else if (!empty($_POST['start_time'])) {
            $where['ss.add_time'] = array('>=', strtotime($_POST['start_time']));
        } else if (!empty($_POST['stop_time'])) {
            $where['ss.add_time'] = array('<=', strtotime($_POST['stop_time']));
        }
        $order_count = $order->getWholealeCount($where);
        import('source.class.user_page');
        $page = new Page($order_count, 15);
        $tmp_orders = $order->getWholeale($where, $orderby, $page->firstRow, $page->listRows);

        $orders = array();
        foreach ($tmp_orders as $tmp_order) {
            $supplier = $store->getStore($tmp_order['supplier_id']); //供货商
            $fx = $store->getStore($tmp_order['store_id']); //供货商

            $store_info[$tmp_order['store_id']] = $store->getStore($tmp_order['store_id']);
            $supplier_name = $supplier['name'];
            $fx_name = $fx['name'];
            $products = $order_product->getFxProducts($tmp_order['fx_order_id']);
            $orders[] = array(
                'fx_order_id'   => $tmp_order['fx_order_id'],
                'fx_order_no'   => $tmp_order['fx_order_no'],
                'order_no'      => $tmp_order['order_no'],
                'total'         => $tmp_order['cost_total'],
                'supplier_id'   => $tmp_order['supplier_id'],
                'store_id'         => $tmp_order['store_id'],
                'drp_level'   => $fx['drp_level'],
                'supplier'      => $supplier_name,
                'fx'      => $fx_name,
                'products'      => $products,
                'add_time'      => date('Y-m-d H:i:s', $tmp_order['add_time']),
                'delivery_user' => $tmp_order['delivery_user'],
                'delivery_tel'  => $tmp_order['delivery_tel'],
                'status'        => $fx_order->status_text($tmp_order['status']),
                'status_id'     => $tmp_order['status']
            );
        }
        $suppliers = $store_supplier->suppliers(array('seller_id' => $this->store_session['store_id']));

        $status = $order->status();
        $this->assign('orders', $orders);
        $this->assign('page', $page->show());
        $this->assign('suppliers', $suppliers);
        $this->assign('status', $status);
        $this->assign('store_info', $store_info);
    }

    public function fans_lifelong() {
	$status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
	$result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('setting_fans_forever' => $status))->save();

	if ($result) {
	    $_SESSION['store']['setting_fans_forever'] = $status;
	    echo true;
	} else {
	    echo false;
	}
    }

    public function fanshare_drp() {
	$status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
	$result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('is_fanshare_drp' => $status))->save();
	if ($result) {
	    $_SESSION['store']['is_fanshare_drp'] = $status;
	    echo true;
	} else {
	    echo false;
	}
    }

    public function drp_supplier_forever() {
	$status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
	$result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('setting_drp_supplier_forever' => $status))->save();
	if ($result) {
	    $_SESSION['store']['setting_drp_supplier_forever'] = $status;
	    echo true;
	} else {
	    echo false;
	}
    }

    private function _seller_setting_content() {
	$this->assign('setting_fans_forever', $this->store_session['setting_fans_forever']);
	$this->assign('is_fanshare_drp', $this->store_session['is_fanshare_drp']);
	$this->assign('setting_drp_supplier_forever', $this->store_session['setting_drp_supplier_forever']);
	$this->assign('setting_canal_qrcode', $this->store_session['setting_canal_qrcode']);
    }
    

    public function setting_canal_qrcode() {
	$status = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0;
	$result = D('Store')->where(array('store_id' => $this->store_session['store_id']))->data(array('setting_canal_qrcode' => $status))->save();
	if ($result) {
	    $_SESSION['store']['setting_canal_qrcode'] = $status;
	    echo true;
	} else {
	    echo false;
	}
    }

    /* 我的经销商 */
    public function agency()
    {
        $this->display();
    }

    private function _agency_content()
    {
        $store_supplier = M('Store_supplier');
        $store = M('Store');

        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $supplierId = $this->store_session['store_id'];    //当前供货商id

        $where = array();

        /* 获取经销商id */
        $sellerIdList = $store_supplier->getAgencyList($supplierId);

        $idList = array();
        foreach($sellerIdList as $supplierId)
        {
            $idList[] = $supplierId['seller_id'];
        }

        $supplierIdList = implode(',', $idList);

        $where['store_id'] = array ('in'=> $supplierIdList);

        if (!empty($keyword)) {
            $where['name'] = array('like' => '%' . $keyword . '%');
        }

        $supplier_count = $store_supplier->supplier_count($where);

        import('source.class.user_page');
        $page = new Page($supplier_count, 15);
        $suppliers = $store_supplier->suppliers($where, $page->firstRow, $page->listRows);

        $this->assign('suppliers', $suppliers);
        $this->assign('page', $page->show());
    }
    
	//分销商导出
	public function checkout() {

		$store_supplier = M('Store_supplier');
		$store = M('Store');
		$order = M('Order');
		$financial_record = M('Financial_record');

		$keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
		$approve = isset($_POST['approve']) ? trim($_POST['approve']) : '*';
		$level = isset($_GET['level']) ? $_GET['level'] : '1';
		$level = intval(isset($_POST['level']) ? trim($_POST['level']) : $level);	
		$level = ($level == 0)? 'all':$level;
		$seller_id = !empty($_POST['seller_id']) ? intval(trim($_POST['seller_id'])) : '';
		$supplierId = $this->store_session['store_id'];
		$type = $_GET['type'];

		if (!empty($seller_id)) {	
			list($sellerList) = $store_supplier->sellers(array(
				'seller_id' => $seller_id
			));
		}

		$where = array();
		$where['ss.supplier_id'] = $this->store_session['store_id'];
		//  $where['s.status'] = 1;
		$where['s.status'] = array('>', 0);


		if ($keyword != '') {
			$where['s.name'] = array('like' => '%' . $keyword . '%');
		}
		if (is_numeric($approve) || $approve != '*') {
			$where['s.drp_approve'] = $approve;
		}
		if (!empty($_SESSION['store_sync'])) {
			$where['ss.type'] = 1;
		}

			// 判断当前登录帐号等级
		$currentLevel = $store_supplier->getSeller(array(
			'supplier_id' => $supplierId
		));
		
		if (isset($currentLevel['level']) && $level ==1) {
			$where['ss.level'] = $currentLevel['level'];
			$where['ss.supplier_id'] = $supplierId;
		} elseif($level == 'all') {
			//导出全部 统计
			$where['ss.level'] = $currentLevel['level'];
			$where['ss.supplier_id'] = $supplierId;
			$count_user1 = $store_supplier->seller_count($where);
			$sellerList = $store_supplier->getNextSellers($supplierId, $level);

			if (count($sellerList)>0)
			{
				foreach ($sellerList as $sellerId)
				{
					$sellerIdList[] = $sellerId['seller_id'];
				}
			
				$sellerIdList = rtrim(implode(',', $sellerIdList), ',');
			
			}
			$where1['ss.supplier_id'] = array ('in' => $sellerIdList);
			$count_user2 = $store_supplier->seller_count($where1);
			
			$count_user = $count_user1 + $count_user2;
			if($type !='do_checkout') {
				$return = array('code'=>'0','msg'=>$count_user);
				echo json_encode($return);exit;
			}
		}else {	
				$sellerList = $store_supplier->getNextSellers($supplierId, $currentLevel['level'] == 1 ? $level-1 :($currentLevel['level']-($level==2 ? '0' : $level-1)));

			if (count($sellerList)>0) {
				foreach ($sellerList as $sellerId) {
					$sellerIdList[] = $sellerId['seller_id'];
			}

				$sellerIdList = rtrim(implode(',', $sellerIdList), ',');

			}
			$where['ss.supplier_id'] = array ('in' => $sellerIdList);
		}
		
		if($type !='do_checkout') {
			$count_user = $store_supplier->seller_count($where);
			$return = array('code'=>'0','msg'=>$count_user,'mmm'=>$a);
			echo json_encode($return);exit;
		}
		
		if($type =='do_checkout') {
			$return = array($count_user,$level);

			$tmp_sellers = $store_supplier->sellers($where);
			$sellers = array();
			foreach ($tmp_sellers as $tmp_seller) {
				$sales = $order->getSales(array('store_id' => $tmp_seller['store_id'], 'is_fx' => 1, 'status' => array('in', array(2,3,4,7))));
				$profit = $tmp_seller['income'];
				$sellers[] = array(
						'store_id'       => $tmp_seller['store_id'],
						'name'           => $tmp_seller['name'],
						'service_tel'    => $tmp_seller['service_tel'],
						'service_qq'     => $tmp_seller['service_qq'],
						'service_weixin' => $tmp_seller['service_weixin'],
						'drp_approve'    => $tmp_seller['drp_approve'],
						'status'         => $tmp_seller['status'],
						'sales'          => !empty($sales) ? number_format($sales, 2, '.', '') : '0.00',
						'profit'         => !empty($profit) ? number_format($profit, 2, '.', '') : '0.00'
				);
				$seller = $sellers;
			}
			
			if($where1) {

				$tmp_sellers1 = $store_supplier->sellers($where1);
				$sellers1 = array();
				foreach ($tmp_sellers1 as $tmp_seller) {
					$sales = $order->getSales(array('store_id' => $tmp_seller['store_id'], 'is_fx' => 1, 'status' => array('in', array(2,3,4,7))));
					$profit = $tmp_seller['income'];
					$sellers1[] = array(
							'store_id'       => $tmp_seller['store_id'],
							'name'           => $tmp_seller['name'],
							'service_tel'    => $tmp_seller['service_tel'],
							'service_qq'     => $tmp_seller['service_qq'],
							'service_weixin' => $tmp_seller['service_weixin'],
							'drp_approve'    => $tmp_seller['drp_approve'],
							'status'         => $tmp_seller['status'],
							'sales'          => !empty($sales) ? number_format($sales, 2, '.', '') : '0.00',
							'profit'         => !empty($profit) ? number_format($profit, 2, '.', '') : '0.00'
					);
				}	
				//
				$seller = array_merge($sellers,$sellers1);			
			}

			
			include 'source/class/execl.class.php';
			$execl = new execl();        	
			if($level == 'all') {
				$level_cn = "全部";
			} else{
				$level_cn = $level."级";
			}
			$filename = date($level_cn."分销商导出_YmdHis",time()).'.xls';
			header ( 'Content-Type: application/vnd.ms-excel' );
			header ( "Content-Disposition: attachment;filename=$filename" );
			header ( 'Cache-Type: charset=gb2312');
			echo "<style>table td{border:1px solid #ccc;}</style>";
			echo "<table>";
			//dump($user_arr);
			echo '	<tr>';
			echo ' 		<th><b> 分销商 </b></th>';
			echo ' 		<th><b> 客服电话 </b></th>';
			echo ' 		<th><b> 客服QQ </b></th>';
			echo ' 		<th><b> 客服微信 </b></th>';
			echo ' 		<th><b> 状态 </b></th>';
			echo ' 		<th><b> 销售额（元） </b></th>';
			echo ' 		<th><b> 佣金（元） </b></th>';
			echo '	</tr>';
			
			foreach ($seller as $k => $v) {
				echo '	<tr>';
				echo ' 		<td align="center">' . $v['name'] . '</td>';
				echo ' 		<td align="center">' . $v['service_tel'] . '</td>';
				echo ' 		<td align="center">' . $v['service_qq'] . '</td>';
				echo ' 		<td align="center">' . $v['service_weixin']. '</td>';
				if ($v['status'] == 5) { 
					$v['zt'] = '<span style="color:gray">已禁用</span>';
				 } else if (!empty($v['drp_approve'])) {
				 	$v['zt'] = '<span style="color:gray">已审核</span>';
				  } 
				 else { 
				 	$v['zt'] = '<span style="color:gray">未审核</span>';
				 }  		 
				echo ' 		<td align="center">' . $v['zt'] . '</td>'; 
				echo ' 		<td align="center">' . $v['sales'] . '</td>';
				echo ' 		<td align="center">' . $v['profit'] . '</td>';
				echo '	</tr>';
			}
			echo '</table>';

		}

	}



}
