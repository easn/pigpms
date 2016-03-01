<?php
/**
 * 分销商品
 * User: pigcms_21
 * Date: 2015/4/17
 * Time: 16:32
 */
require_once dirname(__FILE__).'/drp_check.php';

if (strtolower($_GET['a']) == 'index') {
    if (empty($_SESSION['wap_drp_store'])) {
        pigcms_tips('您没有权限访问，<a href="./home.php?id=' . $_COOKIE['wap_store_id'] . '">返回首页</a>','none');
    }
    $store = $_SESSION['wap_drp_store'];

    if (empty($store['drp_diy_store'])) {
        $supplier = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $store['store_id']))->find();
        if (!empty($supplier['supply_chain'])) {
            $supplier = explode(',', $supplier['supply_chain']);
            if (!empty($supplier[1])) {
                $store['top_supplier_id'] = $supplier[1];
            }
        }
    }
    $store['top_supplier_id'] = !empty($store['top_supplier_id']) ? $store['top_supplier_id'] : $store_id;

    //获取供货商分销商品
    $product = M('Product');
    $product_count = $product->supplierFxProductCount(array('store_id' => $store['top_supplier_id'], 'is_fx' => 1, 'status' => 1));

    include display('drp_products');

    echo ob_get_clean();

} else if (IS_POST && $_POST['type'] == 'get') {
    $product = M('Product');
    //已经是分销（修改分销商品）
    if (!empty($_SESSION['wap_drp_store'])) {
        //获取分销商已分销的商品(不含删除状态)
        $tmp_fx_products = $product->availableFxProducts($_SESSION['wap_drp_store']['store_id']);
        $fx_products = array();
        foreach ($tmp_fx_products as $tmp_product) {
            $fx_products[] = $tmp_product['source_product_id'];
        }
        $fx_products = !empty($fx_products) ? array_unique($fx_products) : $fx_products;
    }
    //获取供货商分销商品
    $store_id = isset($_POST['store_id']) ? intval(trim($_POST['store_id'])) : 0;
    $store = D('Store')->where(array('store_id' => $store_id))->find();
    if (empty($store['drp_diy_store'])) {
        $supplier = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $store['store_id']))->find();
        if (!empty($supplier['supply_chain'])) {
            $supplier = explode(',', $supplier['supply_chain']);
            if (!empty($supplier[1])) {
                $store['top_supplier_id'] = $supplier[1];
            }
        }
    }
    $store['top_supplier_id'] = !empty($store['top_supplier_id']) ? $store['top_supplier_id'] : $store_id;
    $where = array();
    $where = array('store_id' => $store['top_supplier_id'], 'is_fx' => 1, 'status' => 1);
    $drp_level = $store['drp_level']; //分销商级别
    if ($drp_level >= 3) { //超出3级分销，只显示供货商统一定价的商品
        $where['unified_price_setting'] = 1;
        $drp_level = 3;
    }
    $product_count = $product->supplierFxProductCount($where);
    import('source.class.user_page');
    $pagesize = !empty($_POST['pagesize']) ? intval($_POST['pagesize']) : 20;
    $page = new Page($product_count, $pagesize);
    $products = $product->supplierFxProducts($where, $page->firstRow, $page->listRows);
    $data = '';
    if ($products) {
        foreach ($products as $product) {
            $cost_price = ($product['drp_level_' . $drp_level . '_cost_price'] > 0) ? $product['drp_level_' . $drp_level . '_cost_price'] : $product['price'];
            $price      = ($product['drp_level_' . $drp_level . '_price'] > 0) ? $product['drp_level_' . $drp_level . '_price'] : $product['price'];
            $profit     = $price - $cost_price;
            $class = ' current';
            $data .= '<div class="item' . $class . '" name="columns" style="margin-bottom: 10px; zoom: 1; opacity: 1;" pid="' . $product['product_id'] .'">';
            $data .= '    <div>';
            $data .= '        <img src="' . $product['image'] . '" />';
            $data .= '        <h5 style="font-size: 12px;height:50px;text-align:left;padding: 0 5px;">' . $product['name'] . '</h5>';
            $data .= '        <ul class="percent" style="width:auto;">';
            $data .= '            <li style="padding: 0 5px;font-size:12px">成本价: ￥' . $cost_price . '</li>';
            $data .= '            <li style="padding: 0 5px;font-size:12px">建议售价: ￥' . $price . '</li>';
            $data .= '            <li style="padding: 0 5px;font-size: 12px">分销利润：￥' . number_format(($profit), 2, '.', '') . '</li>';
            $data .= '        </ul>';
            $data .= '    </div>';
            $data .= '</div>';
        }
    }
    echo $data;
    exit;
} else if (IS_POST && $_POST['type'] == 'set_drp') { //设置分销


} else if (IS_POST && $_POST['type'] == 'cancel_drp') { //取消分销
    $product = M('Product');
    $product_id = intval(trim($_POST['product_id']));
    //判断商品是否已经被分销
    $product_info = $product->get(array('store_id' => $_SESSION['wap_drp_store']['store_id'], 'source_product_id' => $product_id));
    if (!empty($product_info)) {
        $result = D('Product')->where(array('product_id' => $product_info['product_id'], 'store_id' => $_SESSION['wap_drp_store']['store_id']))->data(array('status' => 2))->save(); //设置为删除状态
    } else {
        $result = true;
    }
    json_return(0, '已取消分销');
}