<?php

/**
 *  支付订单
 */
require_once dirname(__FILE__) . '/global.php';

if (strtolower($_GET['action']) == 'checkamount') {
    $order_id = intval(trim($_POST['order_id']));
    $float_amount = floatval(trim($_POST['float_amount']));
    $postage = floatval(trim($_POST['postage']));
    $tmpOrder = D('Order')->field('float_amount,postage')->where(array('order_id' => $order_id))->find();
    if ($tmpOrder['float_amount'] != $float_amount) {
        $_SESSION['float_amount'] = true;
        echo true;
    } else if ($postage != $tmpOrder['postage']) {
        $_SESSION['float_postage'] = true;
        echo true;
    } else {
        echo false;
    }
    exit;
}

$nowOrder = M('Order')->find($_GET['id']);
if (empty($nowOrder))
    pigcms_tips('该订单号不存在', 'none');

if ($nowOrder['status'] > 1 && $nowOrder['payment_method'] != 'codpay')
    redirect('./order.php?orderno=' . $_GET['id']);
if ($nowOrder['status'] > 1 && $nowOrder['payment_method'] == 'codpay')
    redirect('./order.php?orderid=' . $nowOrder['order_id']);
if ($nowOrder['status'] >= 1 && $nowOrder['payment_method'] == 'peerpay') {
    redirect('./order_share.php?orderid=' . option('config.orderid_prefix') . $nowOrder['order_no']);
}

//店铺资料
$now_store = M('Store')->wap_getStore($nowOrder['store_id']);
if ($nowOrder['uid'] && empty($_SESSION['wap_user']))
    redirect('./login.php');

if (empty($now_store))
    pigcms_tips('您访问的店铺不存在', 'none');
$tmp_store_id = $now_store['store_id'];
setcookie('wap_store_id', $now_store['store_id'], $_SERVER['REQUEST_TIME'] + 10000000, '/');

// 货到付款
$offline_payment = false;
if ($now_store['offline_payment']) {
    $offline_payment = true;
}
$is_all_selfproduct = true;
$is_all_supplierproduct = true;

if ($nowOrder['status'] < 1) {
    //用户地址
    $userAddress = M('User_address')->find(session_id(), $wap_user['uid']);
    //上门自提
    if ($now_store['buyer_selffetch']) {
        $selffetch_list = array(); // M('Trade_selffetch')->getListNoPage($now_store['store_id']);

        $store_contact = M('Store_contact')->get($now_store['store_id']);
        $store_physical = M('Store_physical')->getList($now_store['store_id']);
        if ($store_contact) {
            $data = array();
            $data['pigcms_id'] = '99999999_store';
            $data['name'] = $now_store['name'] . '';
            $data['tel'] = ($store_contact['phone1'] ? $store_contact['phone1'] . '-' : '') . $store_contact['phone2'];
            $data['province_txt'] = $store_contact['province_txt'] . '';
            $data['city_txt'] = $store_contact['city_txt'] . '';
            $data['county_txt'] = $store_contact['area_txt'] . '';
            $data['address'] = $store_contact['address'] . '';
            $data['business_hours'] = '';
            $data['logo'] = $now_store['logo'];
            $data['description'] = '';
            $data['store_id'] = $now_store['store_id'];
            $data['long'] = $store_contact['long'];
            $data['lat'] = $store_contact['lat'];

            $selffetch_list[] = $data;
        }

        if ($store_physical) {
            foreach ($store_physical as $physical) {
                $data = array();
                $data['pigcms_id'] = $physical['pigcms_id'];
                $data['name'] = $physical['name'] . '';
                $data['tel'] = ($physical['phone1'] ? $physical['phone1'] . '-' : '') . $physical['phone2'];
                $data['province_txt'] = $physical['province_txt'] . '';
                $data['city_txt'] = $physical['city_txt'] . '';
                $data['county_txt'] = $physical['county_txt'] . '';
                $data['address'] = $physical['address'] . '';
                $data['business_hours'] = $physical['business_hours'] . '';
                $data['logo'] = $physical['images_arr'][0];
                $data['description'] = $physical['description'];
                $data['long'] = $physical['long'];
                $data['lat'] = $physical['lat'];

                $selffetch_list[] = $data;
            }
        }
    }

	// 抽出可以享受的优惠信息与优惠券
	import('source.class.Order');
	$order_data = new Order($nowOrder['proList']);
	// 不同供货商的优惠、满减、折扣、包邮等信息
	$order_data = $order_data->all();


    foreach ($nowOrder['proList'] as $product) {
        // 分销商品不参与满赠和使用优惠券
    	if ($product['supplier_id'] != '0' || $product['wholesale_product_id'] != 0) {
            $offline_payment = false;
            $is_all_selfproduct = false;
            continue;
        } else {
            $is_all_supplierproduct = false;
        }
    }
} else {
    $nowOrder['address'] = unserialize($nowOrder['address']);
    $selffetch_list = true;
    // 查看满减送
    $order_data['reward_list'] = M('Order_reward')->getByOrderId($nowOrder['order_id']);
    // 使用优惠券
    $user_coupon_list = M('Order_coupon')->getList($nowOrder['order_id']);
    // 查看使用的折扣
	$order_discount_list = M('Order_discount')->getByOrderId($nowOrder['order_id']);
	foreach ($order_discount_list as $order_discount) {
		$order_data['discount_list'][$order_discount['store_id']] = $order_discount['discount'];
	}

    foreach ($nowOrder['proList'] as $product) {
        // 分销商品不参与满赠和使用优惠券
        if ($product['supplier_id'] != '0' || $product['wholesale_product_id'] != 0) {
        	$offline_payment = false;
            $is_all_selfproduct = false;
        } else {
            $is_all_supplierproduct = false;
        }
    }
}

if (!empty($nowOrder['float_amount']) && $nowOrder['float_amount'] > 0) {
    $nowOrder['sub_total'] += $nowOrder['float_amount'];
    $nowOrder['sub_total'] = number_format($nowOrder['sub_total'], 2, '.', '');
}

// dump($nowOrder);
//付款方式
$payMethodList = M('Config')->get_pay_method();

$payList = array();
$useStorePay = false;
$storeOpenid = '';

//if($is_weixin && $_SESSION['openid']){
//	// dump($nowOrder);
//	if($now_store['wxpay'] && (empty($nowOrder['suppliers']) || $nowOrder['suppliers'] == $now_store['store_id'])){
//		// dump($_SESSION);
//		$weixin_bind_info = D('Weixin_bind')->where(array('store_id'=>$now_store['store_id']))->find();
//		// dump($weixin_bind_info);
//		if($weixin_bind_info && $weixin_bind_info['wxpay_mchid'] && $weixin_bind_info['wxpay_key']){
//			if(empty($_GET['code'])){
//				$_SESSION['store_weixin_state']   = md5(uniqid());
//				//代店铺发起获取openid
//				redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$weixin_bind_info['authorizer_appid'].'&redirect_uri='.urlencode($config['site_url'].$_SERVER['REQUEST_URI']).'&response_type=code&scope=snsapi_base&state='.$_SESSION['store_weixin_state'].'&component_appid='.$config['wx_appid'].'#wechat_redirect');
//			}else if(isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['store_weixin_state'])){
//				import('Http');
//				$component_access_token_arr = M('Weixin_bind')->get_access_token($now_store['store_id'],true);
//				if($component_access_token_arr['errcode']){
//					pigcms_tips('与微信通信失败，请重试。');
//				}
//				$result = Http::curlGet('https://api.weixin.qq.com/sns/oauth2/component/access_token?appid='.$weixin_bind_info['authorizer_appid'].'&code='.$_GET['code'].'&grant_type=authorization_code&component_appid='.$config['wx_appid'].'&component_access_token='.$component_access_token_arr['component_access_token']);
//				$result = json_decode($result,true);
//				if($result['errcode']){
//					pigcms_tips('微信返回系统繁忙，请稍候再试。微信错误信息：'.$result['errmsg']);
//				}
//				$storeOpenid = $result['openid'];
//				if(!D('Order')->where(array('order_id'=>$nowOrder['order_id']))->data(array('useStorePay'=>'1','storeOpenid'=>$storeOpenid,'trade_no'=>date('YmdHis',$_SERVER['REQUEST_TIME']).mt_rand(100000,999999)))->save()){
//					pigcms_tips('订单信息保存失败，请重试。');
//				}
//				$payMethodList['weixin']['name'] = '微信安全支付';
//				$payList[0] = $payMethodList['weixin'];
//				$useStorePay = true;
//			}
//		}
//	}else{
//		if(!D('Order')->where(array('order_id'=>$nowOrder['order_id']))->data(array('useStorePay'=>'0','storeOpenid'=>'0','trade_no'=>date('YmdHis',$_SERVER['REQUEST_TIME']).mt_rand(100000,999999)))->save()){
//			pigcms_tips('订单信息保存失败，请重试。');
//		}
//	}
//	if($payMethodList['weixin']){
//		$payMethodList['weixin']['name'] = '微信安全支付';
//		$payList[0] = $payMethodList['weixin'];
//	}
//}else if($payMethodList['alipay']){
//	$payList[0] = $payMethodList['alipay'];
//}



if ($now_store['wxpay'] && (empty($nowOrder['suppliers']) || $nowOrder['suppliers'] == $now_store['store_id'])) {
    // dump($_SESSION);
    $weixin_bind_info = D('Weixin_bind')->where(array('store_id' => $now_store['store_id']))->find();
    // dump($weixin_bind_info);
    if ($weixin_bind_info && $weixin_bind_info['wxpay_mchid'] && $weixin_bind_info['wxpay_key']) {
        if (empty($_GET['code'])) {
            $_SESSION['store_weixin_state'] = md5(uniqid());
            //代店铺发起获取openid
            redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $weixin_bind_info['authorizer_appid'] . '&redirect_uri=' . urlencode($config['site_url'] . $_SERVER['REQUEST_URI']) . '&response_type=code&scope=snsapi_base&state=' . $_SESSION['store_weixin_state'] . '&component_appid=' . $config['wx_appid'] . '#wechat_redirect');
        } else if (isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['store_weixin_state'])) {
            import('Http');
            $component_access_token_arr = M('Weixin_bind')->get_access_token($now_store['store_id'], true);
            if ($component_access_token_arr['errcode']) {
                pigcms_tips('与微信通信失败，请重试。');
            }
            $result = Http::curlGet('https://api.weixin.qq.com/sns/oauth2/component/access_token?appid=' . $weixin_bind_info['authorizer_appid'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code&component_appid=' . $config['wx_appid'] . '&component_access_token=' . $component_access_token_arr['component_access_token']);
            $result = json_decode($result, true);
            if ($result['errcode']) {
                pigcms_tips('微信返回系统繁忙，请稍候再试。微信错误信息：' . $result['errmsg']);
            }
            $storeOpenid = $result['openid'];
            if (!D('Order')->where(array('order_id' => $nowOrder['order_id']))->data(array('useStorePay' => '1', 'storeOpenid' => $storeOpenid, 'trade_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999)))->save()) {
                pigcms_tips('订单信息保存失败，请重试。');
            }
            $payMethodList['weixin']['name'] = '微信安全支付';
            $payList[0] = $payMethodList['weixin'];
            $useStorePay = true;
        }
    }
} else {
    if (!D('Order')->where(array('order_id' => $nowOrder['order_id']))->data(array('useStorePay' => '0', 'storeOpenid' => '0', 'trade_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999)))->save()) {
        pigcms_tips('订单信息保存失败，请重试。');
    }
}

if ($payMethodList['weixin']) {
    $payMethodList['weixin']['name'] = '微信安全支付';
    $payList[0] = $payMethodList['weixin'];
}

if ($payMethodList['alipay']) {
    $payMethodList['aplipay']['name'] = '支付宝支付';
    $payList[1] = $payMethodList['alipay'];
}



/* if (empty($useStorePay)) {
  if ($payMethodList['tenpay']) {
  $payList[1] = $payMethodList['tenpay'];
  }
  if ($payMethodList['yeepay']) {
  $payList[2] = $payMethodList['yeepay'];
  } else if ($payMethodList['allinpay']) {
  $payList[2] = $payMethodList['allinpay'];
  }
  if ($payList[2])
  $payList[2]['name'] = '银行卡支付';

  if ($now_store['pay_agent']) {
  $payList[] = array('name' => '找人代付', 'type' => 'peerpay');
  }
  } */


if (empty($useStorePay)) {
    if ($payMethodList['tenpay']) {
        $payList[2] = $payMethodList['tenpay'];
    }
    if ($payMethodList['yeepay']) {
        $payList[3] = $payMethodList['yeepay'];
    } else if ($payMethodList['allinpay']) {
        $payList[3] = $payMethodList['allinpay'];
    }
    if ($payList[3])
        $payList[3]['name'] = '银行卡支付';


    if ($now_store['pay_agent']) {
        $payList[] = array('name' => '找人代付', 'type' => 'peerpay');
    }
}

if ($offline_payment) {
    $payList[] = array('name' => '货到付款', 'type' => 'offline');
}

//if (stripos($_SERVER['REMOTE_ADDR'], '183.161.232.') !== false) {
	//本地测试使用(危险代码，正式上线时需删除)
	//$payList[] = array('name'=>'测试支付','type'=>'test');
//}
//同步到微店的用户
if (!empty($_SESSION['sync_user'])) {
    $sync_user = true;
}

include display('pay');
echo ob_get_clean();
?>
