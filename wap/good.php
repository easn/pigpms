<?php

/**
 *  店铺主页
 */
require_once dirname(__FILE__) . '/global.php';

$product_id = isset($_GET['id']) ? $_GET['id'] : pigcms_tips('您输入的网址有误', 'none');

// 预览切换
if (!$is_mobile && $_SESSION['user'] && option('config.synthesize_store')) {
    if (isset($_GET['ps']) && $_GET['ps'] == '800') {
        $config = option('config');

        $url = $config['site_url'] . '/index.php?c=goods&a=index&id=' . $product_id . '&is_preview=1';
        echo redirect($url);
        exit;
    }
}

//商品默认展示
$nowProduct = D('Product')->where(array('product_id' => $product_id))->find();
if (empty($nowProduct))
    pigcms_tips('您访问的商品不存在', 'none');

if ($nowProduct['status'] != '1') {
    pigcms_tips('您访问的商品未上架或已删除', 'none');
}

$store_id = $nowProduct['store_id'];
//获取供货商信息
$top_store = array();
if (!empty($_GET['store_id'])) {
    $tmp_store_id = intval(trim($_GET['store_id']));
	$top_store = D('Store')->where(array('store_id'=>$store_id))->find();
} else {
    $tmp_store_id = $store_id;
}
//店铺资料
$now_store = M('Store')->wap_getStore($tmp_store_id);
if($top_store['tel']){
	if($top_store['is_show_drp_tel']==1){
		$now_store['tel'] = $top_store['tel'];
	}
}


if (empty($now_store))
    pigcms_tips('您访问的店铺不存在', 'none');
setcookie('wap_store_id', $tmp_store_id, $_SERVER['REQUEST_TIME'] + 10000000, '/');


$fans_forever_uid = $_SESSION['wap_user']['uid'] ? $_SESSION['wap_user']['uid'] : $_SESSION['user']['uid'];
$_where['uid'] = $fans_forever_uid;
$sub_info = D('Subscribe_store')->where($_where)->order('subscribe_time asc')->find();

//粉丝终身制start
if ($now_store['setting_fans_forever']) {
    if ($fans_forever_uid != $now_store['uid']) {
		if (!$sub_info) {
			$_condition['uid'] = $fans_forever_uid;
			$result = D('Subscribe_store')->where($_condition)->find();
			$store_list = D('Store')->where($_condition)->select();
			$new_storeId_arr = array();
			foreach ($store_list as $v) {
			$new_storeId_arr[] = $v['store_id'];
			}
			if (!$result) {
			$data['uid'] = $fans_forever_uid;
			if (strpos($_SERVER['PHP_SELF'], 'home.php') !== false) {
				if (!in_array($_GET['id'], $new_storeId_arr)) {
				$data['store_id'] = $_GET['id'];
				}
			} else if (strpos($_SERVER['PHP_SELF'], 'good.php') !== false) {
				$product_info = M('Product')->get($_GET['id']);
				$data['store_id'] = $product_info['store_id'];
			}

			$data['subscribe_time'] = time();
			D('Subscribe_store')->data($data)->add();
			}
		} else {
			if (strpos($_SERVER['PHP_SELF'], 'home.php') !== false) {
				if ($_GET['id'] != $sub_info['store_id']) {
					$url = $config['site_url'] . '/wap/home.php?id=' . $sub_info['store_id'];
					echo redirect($url);
					exit;
				}
				} else if (strpos($_SERVER['PHP_SELF'], 'good.php') !== false) {
				if ($_GET['store_id'] != $sub_info['store_id']) {
					$url = $config['site_url'] . '/wap/home.php?id=' . $sub_info['store_id'];
					echo redirect($url);
					exit;
				}
			}
		}
    }
// 	if (strpos($_SERVER['PHP_SELF'], 'home.php') !== false) {
// 		if (($_GET['id'] != $sub_info['store_id']) && $sub_info) {
// 			$url = $config['site_url'] . '/wap/home.php?id=' . $sub_info['store_id'];
// 			echo redirect($url);
// 			exit;
// 		}
// 	} else if (strpos($_SERVER['PHP_SELF'], 'good.php') !== false) {
// 		if (($_GET['store_id'] != $sub_info['store_id']) && $sub_info) {
// 			$url = $config['site_url'] . '/wap/home.php?id=' . $sub_info['store_id'];
// 			echo redirect($url);
// 			exit;
// 		}
// 	}
}


if (strpos($_SERVER['PHP_SELF'], 'home.php') !== false) {
		if (($_GET['id'] != $sub_info['store_id']) && $sub_info) {
			$setting_fans_forever=D('Store')->where(array('store_id'=>$sub_info['store_id']))->field('setting_fans_forever')->find();
			if($setting_fans_forever['setting_fans_forever']){
				$url = $config['site_url'] . '/wap/home.php?id=' . $sub_info['store_id'];
				echo redirect($url);
				exit;
			}
		}
	} else if (strpos($_SERVER['PHP_SELF'], 'good.php') !== false) {
		if (($_GET['store_id'] != $sub_info['store_id']) && $sub_info) {
			$setting_fans_forever=D('Store')->where(array('store_id'=>$sub_info['store_id']))->field('setting_fans_forever')->find();
			if($setting_fans_forever['setting_fans_forever']){
				$url = $config['site_url'] . '/wap/home.php?id=' . $sub_info['store_id'];
				echo redirect($url);
				exit;
			}
		}
	}
//粉丝终身制end

if ($nowProduct['image_size'] == '0') {
    $nowProduct['image_size'] = array();
} else if ($nowProduct['image_size']) {
    $nowProduct['image_size'] = unserialize($nowProduct['image_size']);
} else {
    $nowProduct['image_size'] = D('Attachment')->field('`width`,`height`')->where(array('file' => $nowProduct['image']))->find();
    D('Product')->where(array('product_id' => $product_id))->data(array('image_size' => serialize($nowProduct['image_size'])))->save();
}
$nowProduct['image'] = getAttachmentUrl($nowProduct['image']);
$nowProduct['images'] = M('Product_image')->getImages($product_id, true);
$nowProduct['images_num'] = count($nowProduct['images']);
if ($nowProduct['has_property']) {
    //库存信息
    $skuList = D('Product_sku')->field('`sku_id`,`properties`,`quantity`,`price`,`drp_level_1_price`,`drp_level_2_price`,`drp_level_3_price`')->where(array('product_id' => $product_id, 'quantity' => array('!=', '0')))->order('`sku_id` ASC')->select();
    //如果有库存信息并且有库存，则查库存关系表
    if (!empty($skuList)) {
        $skuPriceArr = $skuPropertyArr = array();
        foreach ($skuList as $value) {
            if (!empty($nowProduct['unified_price_setting']) && empty($now_store['drp_diy_store'])) { //分销商的价格
                $value['price'] = ($value['drp_level_' . ($now_store['drp_level'] <= 3 ? $now_store['drp_level'] : 3) . '_price'] > 0) ? $value['drp_level_' . ($now_store['drp_level'] <= 3 ? $now_store['drp_level'] : 3) . '_price'] : $value['price'];
            }
            $skuPriceArr[] = $value['price'];
            $skuPropertyArr[$value['properties']] = true;
        }
        if (!empty($skuPriceArr)) {
            $minPrice = min($skuPriceArr);
            $maxPrice = max($skuPriceArr);
        } else {
            $nowProduct['quantity'] = 0;
        }
        $tmpPropertyList = D('')->field('`pp`.`pid`,`pp`.`name`')->table(array('Product_to_property' => 'ptp', 'Product_property' => 'pp'))->where("`ptp`.`product_id`='$product_id' AND `pp`.`pid`=`ptp`.`pid`")->order('`ptp`.`pigcms_id` ASC')->select();
        if (!empty($tmpPropertyList)) {
            $tmpPropertyValueList = D('')->field('`ppv`.`vid`,`ppv`.`value`,`ppv`.`pid`')->table(array('Product_to_property_value' => 'ptpv', 'Product_property_value' => 'ppv'))->where("`ptpv`.`product_id`='$product_id' AND `ppv`.`vid`=`ptpv`.`vid`")->order('`ptpv`.`pigcms_id` ASC')->select();
            if (!empty($tmpPropertyValueList)) {
                foreach ($tmpPropertyValueList as $value) {
                    $propertyValueList[$value['pid']][] = array(
                        'vid' => $value['vid'],
                        'value' => $value['value'],
                    );
                }
                foreach ($tmpPropertyList as $value) {
                    $newPropertyList[] = array(
                        'pid' => $value['pid'],
                        'name' => $value['name'],
                        'values' => $propertyValueList[$value['pid']],
                    );
                }
                if (count($newPropertyList) == 1) {
                    foreach ($newPropertyList[0]['values'] as $key => $value) {
                        $tmpKey = $newPropertyList[0]['pid'] . ':' . $value['vid'];
                        if (empty($skuPropertyArr[$tmpKey])) {
                            unset($newPropertyList[0]['values'][$key]);
                        }
                    }
                }
            }
        }
    }
} else {
    $maxPrice = 0;
    $minPrice = $nowProduct['price'];
    if (!empty($nowProduct['unified_price_setting']) && empty($now_store['drp_diy_store'])) { //分销商的价格
        $minPrice = ($nowProduct['drp_level_' . ($now_store['drp_level'] <= 3 ? $now_store['drp_level'] : 3) . '_price'] > 0) ? $nowProduct['drp_level_' . ($now_store['drp_level'] <= 3 ? $now_store['drp_level'] : 3) . '_price'] : $nowProduct['price'];
    }
}

if ($nowProduct['postage_type']) {
    if (!empty($nowProduct['original_product_id'])) { //分销商品
        $tmp_product_info = D('Product')->field('store_id')->where(array('product_id' => $nowProduct['original_product_id']))->find();
        $supplier_id = $tmp_product_info['store_id'];
    } else {
        $supplier_id = $nowProduct['store_id'];
    }
    $postage_template = M('Postage_template')->get_tpl($nowProduct['postage_template_id'], $supplier_id);
    if ($postage_template['area_list']) {
        foreach ($postage_template['area_list'] as $value) {
            if (!isset($min_postage)) {
                $min_postage = $max_postage = $value[2];
            } else if ($value[2] < $min_postage) {
                $min_postage = $value[2];
            } else if ($value[2] > $max_postage) {
                $max_postage = $value[2];
            }
        }
    }
    if ($min_postage == $max_postage) {
        $nowProduct['postage'] = $min_postage;
    } else {
        $nowProduct['postage_tpl'] = array('min' => $min_postage, 'max' => $max_postage);
    }
}

//扫码优惠
if (!empty($_GET['activity'])) {
    $nowActivity = M('Product_qrcode_activity')->getActivityById($_GET['activity']);
    if (empty($nowActivity) || $nowActivity['product_id'] != $nowProduct['product_id']) {
        unset($nowActivity);
    }
}

//当前页面的地址
$now_url = $config['wap_site_url'] . '/good.php?id=' . $nowProduct['product_id'] . '&store_id=' . $tmp_store_id;

//商品的自定义字段
if ($nowProduct['has_custom']) {
    $homeCustomField = M('Custom_field')->getParseFields($nowProduct['store_id'], 'good', $nowProduct['product_id'], $tmp_store_id, $now_store['drp_level'], $now_store['drp_diy_store']);
}

//公共广告判断
$pageHasAd = false;
if ($now_store['open_ad'] && !empty($now_store['use_ad_pages'])) {
    $useAdPagesArr = explode(',', $now_store['use_ad_pages']);
    if (in_array('2', $useAdPagesArr)) {
        $pageAdFieldArr = M('Custom_field')->getParseFields($nowProduct['store_id'], 'common_ad', $nowProduct['store_id'], $tmp_store_id, $now_store['drp_level'], $now_store['drp_diy_store']);
        if (!empty($pageAdFieldArr)) {
            $pageAdFieldCon = '';
            foreach ($pageAdFieldArr as $value) {
                $pageAdFieldCon .= $value['html'];
            }
            $pageHasAd = true;
        }
        $pageAdPosition = $now_store['ad_position'];
    }
}

$good_history = $_COOKIE['good_history'];
if (empty($good_history)) {
    $new_history = true;
} else {
    $good_history = json_decode($good_history, true);
    if (!is_array($good_history)) {
        $new_history = true;
    } else {
        $new_good_history = array();
        foreach ($good_history as &$history_value) {
            if ($history_value['id'] != $nowProduct['product_id']) {
                $new_good_history[] = $history_value;
            }
        }
        if (!empty($new_good_history)) {
            array_push($new_good_history, array('id' => $nowProduct['product_id'], 'name' => $nowProduct['name'], 'image' => $nowProduct['image'], 'price' => $nowProduct['price'], 'url' => $now_url, 'time' => $_SERVER['REQUEST_TIME']));
        } else {
            $new_history = true;
        }
    }
}
if ($new_history) {
    $new_good_history[] = array(
        'id' => $nowProduct['product_id'],
        'name' => $nowProduct['name'],
        'image' => $nowProduct['image'],
        'price' => $nowProduct['price'],
        'url' => $now_url,
        'time' => $_SERVER['REQUEST_TIME']
    );
}
setcookie('good_history', json_encode($new_good_history), $_SERVER['REQUEST_TIME'] + 86400 * 365, '/');

//限购
$buyer_quota = false;
if (!empty($nowProduct['buyer_quota'])) {
	$buy_quantity = 0;
	$user_type = 'uid';
	$uid = $_SESSION['wap_user']['uid'];
	if (empty($_SESSION['wap_user'])) { //游客购买
		$session_id = session_id();
		$uid = $session_id;
		$user_type = 'session';
		//购物车
		$cart_number = D('User_cart')->field('sum(pro_num) as pro_num')->where(array('product_id' => $nowProduct['product_id'], 'session_id' => $session_id))->find();
		if (!empty($cart_number)) {
			$buy_quantity += $cart_number['pro_num'];
		}
	} else {
		//购物车
		$cart_number = D('User_cart')->field('sum(pro_num) as pro_num')->where(array('product_id' => $nowProduct['product_id'], 'uid' => $uid))->find();
		if (!empty($cart_number)) {
			$buy_quantity += $cart_number['pro_num'];
		}
	}
	
	// 再加上订单里已经购买的商品
	$buy_quantity += M('Order_product')->getBuyNumber($uid, $nowProduct['product_id'], $user_type);
	if ($buy_quantity >= $nowProduct['buyer_quota']) {
		$buyer_quota = true;
	}
}

// 查看本产品是否参与活动
$reward = '';
if (empty($nowProduct['supplier'])) {
    $reward = M('Reward')->getRewardByProduct($nowProduct);
}

$allow_drp           = false;
$open_drp            = option('config.open_store_drp'); //是否开启分销
$store_supplier      = M('Store_supplier');
$is_fx               = 0; //是否已经分销该商品，如果已经分销返回分销商品id
$is_new_seller       = false; //是否是新分销商
$seller_id           = 0; //分销商id
$drp_level           = $now_store['drp_level']; //当前分销级别
$max_store_drp_level = option('config.max_store_drp_level'); //最大分销级别,0或空为无限级分销
$seller_drp_level    = $drp_level + 1; //下级分销商级别

if ($nowProduct['is_fx'] && $open_drp && !empty($now_store['open_drp_guidance'])) {
    $allow_drp = true;
    $drp_register_url = './drp_register.php?id=' . $tmp_store_id;
    if (!empty($_SESSION['wap_user']['uid'])) { //用户登录
        $user = M('User');
        $avatar = $user->getAvatarById($_SESSION['wap_user']['uid']);
        if ($now_store['uid'] == $_SESSION['wap_user']['uid']) { //自己店铺
            if (!empty($now_store['drp_supplier_id'])) { //分销商
                $seller_id        = $tmp_store_id;
                $drp_register_url = './drp_product_share.php?id=' . $nowProduct['product_id'] . '&store_id=' . $seller_id;
            } else { //自营店铺（不显示分销引导）

            }
        } else { //他人店铺
            //获取当前店铺分销链（上级分销商）
            $supply_chain = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $tmp_store_id, 'type' => 1))->find();
            $supply_chain = explode(',', $supply_chain['supply_chain']);
            array_shift($supply_chain);
            //获取当前用户的店铺
            $stores = D('Store')->field('store_id,name,drp_supplier_id,status')->where(array('uid' => $_SESSION['wap_user']['uid'], 'status' => 1))->select();
            $user_supply_chains = array();
            if (!empty($stores)) {
                foreach ($stores as $tmp_store) {
                    if (in_array($tmp_store['status'], array(4,5))) { //店铺被禁用或删除
                        continue;
                    }
                    if (!empty($tmp_store['drp_supplier_id']) && $tmp_store['drp_supplier_id'] == $tmp_store_id) { //当前店铺的下级分销商（当前访问的店铺是登陆用户的上级分销商或供货商）
                        $seller_id        = $tmp_store['store_id'];
                        $drp_register_url = './drp_product_share.php?id=' . $nowProduct['product_id'] . '&store_id=' . $seller_id;
                        break;
                    } else if (!empty($tmp_store['drp_supplier_id'])) { //分销商
                        //获取店铺分销链（上级分销商）
                        $store_supply_chain = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $tmp_store['store_id'], 'type' => 1))->find();
                        $store_supply_chain = explode(',', $store_supply_chain['supply_chain']);
                        array_shift($store_supply_chain);
                        //当前访问的店铺的上级（当前访问的店铺是登陆用户的下级分销商）
                        if (in_array($tmp_store['store_id'], $supply_chain)) {
                            $seller_id        = $tmp_store['store_id'];
                            $drp_register_url = './drp_product_share.php?id=' . $nowProduct['product_id'] . '&store_id=' . $seller_id;
                            break;
                        } else if ($store_supply_chain == $supply_chain && !empty($supply_chain)) { //同级分销商
                            $seller_id        = $tmp_store['store_id'];
                            $drp_register_url = './drp_product_share.php?id=' . $nowProduct['product_id'] . '&store_id=' . $seller_id;
                            break;
                        } else if (array_intersect($supply_chain, $store_supply_chain)) { //有交集的分销商
                            $seller_id        = $tmp_store['store_id'];
                            $drp_register_url = './drp_product_share.php?id=' . $nowProduct['product_id'] . '&store_id=' . $seller_id;
                            break;
                        } else { //可能存在当前访问店铺的非直属下级
                            $user_supply_chains[$tmp_store['store_id']] = $store_supply_chain;
                            $user_stores[$tmp_store['store_id']]        = $tmp_store['name'];
                        }
                    }
                }
                if (!empty($user_supply_chains)) {
                    foreach ($user_supply_chains as $tmp_seller_id => $user_supply_chain) {
                        if (in_array($store_id, $user_supply_chain)) { //当前访问店铺的非直属下级
                            $seller_id        = $tmp_seller_id;
                            $drp_register_url = './drp_product_share.php?id=' . $nowProduct['product_id'] . '&store_id=' . $seller_id;
                            break;
                        }
                    }
                }
            }
        }
    }

    //访问的店铺不是当前登录用户的分销商或供货商（可以申请分销，需判断可分销级别，超最大分销级别 0为无限级分销）
    if (empty($seller_id) && $max_store_drp_level < $seller_drp_level && !empty($max_store_drp_level)) {
        $allow_drp        = false;
        $drp_register_url = '';
    }

    //未注册分销商
    if (empty($seller_id)) {
        $msg = '亲爱的 <span style="color:#26CB40">' . $_SESSION['wap_user']['nickname'] . '</span>，申请分销即可分销赚佣金！';
    }

    //三级分润
    if ($seller_drp_level > 3) {
        $seller_drp_level = 3;
    }

    //获取商品分销信息
    if (!empty($nowProduct['unified_price_setting'])) { //分销商的价格
        $cost_price = $nowProduct['drp_level_' . $seller_drp_level . '_cost_price']; //成本
        $min_fx_price = number_format($nowProduct['drp_level_' . $seller_drp_level . '_price'] - $cost_price, 2, '.', ''); //最低分销价
        $max_fx_price = number_format($nowProduct['drp_level_' . $seller_drp_level . '_price'] - $cost_price, 2, '.', ''); //最高分销价
    } else {
        $cost_price = $nowProduct['cost_price']; //成本
        $min_fx_price = number_format($nowProduct['min_fx_price'] - $cost_price, 2, '.', ''); //最低分销价
        $max_fx_price = number_format($nowProduct['max_fx_price'] - $cost_price, 2, '.', ''); //最高分销价
        $max_fx_price = max(array($min_fx_price, $max_fx_price));
    }
}

$_condition['user_id'] = $_SESSION['wap_user']['uid'];
$_condition['store_id'] = $_GET['store_id'];
$self_collect_goods_num = D('User_collect')->where($_condition)->select();
$self_attention_goods_num = D('User_attention')->where($_condition)->select();

//判断是否显示评价按钮
$is_comment = M('Order_product')->isComment($product_id, false);

$imUrl = getImUrl($_SESSION['wap_user']['uid'], $tmp_store_id);
//分享配置 start
$share_conf = array(
    'title' => $nowProduct['name'], // 分享标题
    'desc' => str_replace(array("\r", "\n"), array('', ''), !empty($nowProduct['intro']) ? $nowProduct['intro'] : $nowProduct['name']), // 分享描述
    'link' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // 分享链接
    'imgUrl' => $nowProduct['image'], // 分享图片链接
    'type' => '', // 分享类型,music、video或link，不填默认为link
    'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
);

//查看是否已经是当前店铺的分销商
$fx_uid=$_GET['uid']?$_GET['uid']:$_SESSION['wap_user']['uid'];
$seller_stores = D('Store')->field('store_id,drp_level')->where(array('uid' => $fx_uid))->select();

//有分销商
$seller_id = $store_id;
if (!empty($seller_stores)) {
	foreach ($seller_stores as $seller_store) {
		$supply_chain = D('Store_supplier')->field('supply_chain,level')->where(array('seller_id' => $seller_store['store_id'], 'type' => 1))->find();
		if (!empty($supply_chain)) {
			$chain = explode(',', $supply_chain['supply_chain']);
			if (in_array($_GET['store_id'], $chain)) { //是当前店铺的下级分销商
				$seller_id2        = $seller_store['store_id']; //当前分销商id
				break;
			}
		}
	}
}

//计算消费是否满足才可申请分销start
$array = array();
$array['store_id'] = intval($_GET['id']);
$array['status']   = array('in', array(2,3,4,7));
if (!empty($_SESSION['wap_user']['uid'])) {
	$array['uid'] = $_SESSION['wap_user']['uid'];
} else if (!empty($_COOKIE['uid'])) {
	$array['uid'] = $_COOKIE['uid'];
} else if (session_id()) {
	$array['session_id'] = session_id();
}
$total = D('Order')->where($array)->sum('total');
//计算消费是否满足才可申请分销end
$flag=true;
if(($total < $now_store['drp_limit_buy'])||(!$now_store['is_fanshare_drp'])||(!$now_store['open_drp_limit'])){
	$flag = false;
}

//if($seller_id2){
//	$_where['store_id']=$seller_id;
//	$store_info=D('Store')->where(array('store_id'=>$store_id))->find();
//	$now_store = D('Store')->where(array('store_id'=>$store_info['store_id']))->find();
//	$share_conf = array(
//			'title'    => $now_store['name'], // 分享标题
//			'desc'     => str_replace(array("\r", "\n"), array('', ''), !empty($now_store['intro']) ? $now_store['intro'] : $now_store['name']), // 分享描述
//			'link'     => $now_store['url'], // 分享链接
//			'imgUrl'   => $now_store['logo'], // 分享图片链接
//			'type'     => '', // 分享类型,music、video或link，不填默认为link
//			'dataUrl'  => '', // 如果type是music或video，则要提供数据链接，默认为空
//			'store_id' => '',
//			'uid'      => ''
//	);
//}else if($flag){
//	$share_conf['store_id'] = $store_id?$_GET['store_id']:$store_id;
//	$share_conf['uid']      = $_SESSION['wap_user']['uid'];
//}



if($seller_id2){
	$store_info=M('Store')->getStore($seller_id2);
	$share_conf = array(
			'title'    => $store_info['name'], // 分享标题
			'desc'     => str_replace(array("\r", "\n"), array('', ''), !empty($tmp_now_store['intro']) ? $tmp_now_store['intro'] : $tmp_now_store['name']), // 分享描述
			'link'     => $store_info['url'], // 分享链接
			'imgUrl'   => $store_info['logo'], // 分享图片链接
			'type'     => '', // 分享类型,music、video或link，不填默认为link
			'dataUrl'  => '', // 如果type是music或video，则要提供数据链接，默认为空
			'store_id' => '',
			'uid'      => ''
	);
}else if($flag){
	$share_conf['store_id'] = $store_id?$_GET['store_id']:$store_id;
	$share_conf['uid']      = $_SESSION['wap_user']['uid'];
}


import('WechatShare');
$share = new WechatShare();
$shareData = $share->getSgin($share_conf);
//分享配置 end


include display('good');

echo ob_get_clean();
?>