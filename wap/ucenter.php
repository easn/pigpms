<?php

/**
 *  订单信息
 */
require_once dirname(__FILE__) . '/global.php';
//setcookie('pigcms_sessionid','',$_SERVER['REQUEST_TIME']-10000000,'/');
$store_id = isset($_GET['id']) ? $_GET['id'] : pigcms_tips('您输入的网址有误', 'none');

if ($_SESSION['wap_user']) {
    $wap_user = $_SESSION['wap_user'];
} else {
    $now_store = M('Store')->wap_getStore($store_id);
    $wap_user = $_SESSION['wap_user'];
}
if (empty($wap_user) && empty($now_store)) {
    redirect('./login.php?referer=' . urlencode($_SERVER['REQUEST_URI']));
}
//店铺资料
if (empty($now_store)) {
    $now_store = M('Store')->wap_getStore($store_id);
}
if (empty($now_store))
    pigcms_tips('您访问的店铺不存在', 'none');

setcookie('wap_store_id', $now_store['store_id'], $_SERVER['REQUEST_TIME'] + 10000000, '/');

//当前页面的地址
$now_url = $config['wap_site_url'] . '/ucenter.php?id=' . $now_store['store_id'];

//会员中心配置
$now_ucenter = D('Ucenter')->where(array('store_id' => $now_store['store_id']))->find();
if (empty($now_ucenter)) {
    $now_ucenter['page_title'] = $config['ucenter_page_title'];
    $now_ucenter['bg_pic'] = $config['site_url'] . '/upload/images/' . $config['ucenter_bg_pic'];
    $now_ucenter['show_level'] = $config['ucenter_show_level'];
    $now_ucenter['show_point'] = $config['ucenter_show_point'];
} else {
    $now_ucenter['bg_pic'] = trim($now_ucenter['bg_pic'], '.');
}

//会员中心的自定义字段
if ($now_ucenter['has_custom']) {
    $homeCustomField = M('Custom_field')->getParseFields($store_id, 'ucenter', $store_id);
}

//公共广告判断
$pageHasAd = false;
if ($now_store['open_ad'] && !empty($now_store['use_ad_pages'])) {
    $useAdPagesArr = explode(',', $now_store['use_ad_pages']);
    if (in_array('4', $useAdPagesArr)) {
        $pageAdFieldArr = M('Custom_field')->getParseFields($store_id, 'common_ad', $store_id);
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

//会员对应店铺的数据
M('Store_user_data')->updateData2($store_id, $wap_user['uid']);
$storeUserData = M('Store_user_data')->getUserData($now_store['store_id'], $wap_user['uid']);

//店铺导航
if ($now_store['open_nav'] && !empty($now_store['use_nav_pages'])) {
    $useNavPagesArr = explode(',', $now_store['use_nav_pages']);
    if (in_array('2', $useNavPagesArr)) {
        $storeNav = M('Store_nav')->getParseNav($now_store['store_id']);
    }
}


$store          = M('Store');
$store_supplier = M('Store_supplier');
$drp_link            = false;
$store_info          = $store->getStore($store_id);
$seller_disabled     = false; //分销商禁用
//判断是否开启分销
$allow_drp           = option('config.open_store_drp');
$drp_level           = $now_store['drp_level']; //当前分销级别
$user_stores         = array();
$max_store_drp_level = option('config.max_store_drp_level'); //最大分销级别

if ($allow_drp) { //开启排他分销和分销引导
    if (!empty($_SESSION['wap_user']['uid'])) { //用户登录
        if ($now_store['uid'] == $_SESSION['wap_user']['uid']) { //自己店铺
            if (!empty($now_store['drp_supplier_id'])) { //分销商
                $drp_link     = true;
                $drp_link_url = './drp_register.php?id=' . $store_id;
            } else { //自营店铺（不显示分销引导）

            }
        } else {  //他人店铺
            //获取当前店铺分销链（上级分销商）
            $supply_chain = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $store_id, 'type' => 1))->find();
            $supply_chain = explode(',', $supply_chain['supply_chain']);
            array_shift($supply_chain);
            //获取当前用户的店铺
            $stores = D('Store')->field('store_id,name,drp_supplier_id,status')->where(array('uid' => $_SESSION['wap_user']['uid']))->select();
            $user_supply_chains = array();
            if (!empty($stores)) {
                foreach ($stores as $tmp_store) {
                    if (in_array($tmp_store['status'], array(4,5))) { //店铺被禁用或删除
                        continue;
                    }
                    if (!empty($tmp_store['drp_supplier_id']) && $tmp_store['drp_supplier_id'] == $store_id) { //当前店铺的下级分销商（当前访问的店铺是登陆用户的上级分销商或供货商）
                        $drp_link     = true;
                        $drp_link_url = './drp_register.php?id=' . $tmp_store['store_id'];
                        break;
                    } else if (!empty($tmp_store['drp_supplier_id'])) { //分销商
                        //获取店铺分销链（上级分销商）
                        $store_supply_chain = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $tmp_store['store_id'], 'type' => 1))->find();
                        $store_supply_chain = explode(',', $store_supply_chain['supply_chain']);
                        array_shift($store_supply_chain);
                        //当前访问的店铺的上级（当前访问的店铺是登陆用户的下级分销商）
                        if (in_array($tmp_store['store_id'], $supply_chain)) {
                            $drp_link     = true;
                            $drp_link_url = './drp_register.php?id=' . $tmp_store['store_id'];
                            break;
                        } else if ($store_supply_chain == $supply_chain) { //同级分销商
                            $drp_link     = true;
                            $drp_link_url = './drp_register.php?id=' . $tmp_store['store_id'];
                            break;
                        } else if (array_intersect($supply_chain, $store_supply_chain)) { //有交集的分销商
                            $drp_link     = true;
                            $drp_link_url = './drp_register.php?id=' . $tmp_store['store_id'];
                            break;
                        } else {  //可能存在当前访问店铺的非直属下级
                            $user_supply_chains[$tmp_store['store_id']] = $store_supply_chain;
                            $user_stores[$tmp_store['store_id']]        = $tmp_store['name'];
                        }
                    }
                }
                if (!empty($user_supply_chains)) {
                    foreach ($user_supply_chains as $tmp_seller_id => $user_supply_chain) {
                        if (in_array($store_id, $user_supply_chain)) { //当前访问店铺的非直属下级
                            $drp_link     = true;
                            $drp_link_url = './drp_register.php?id=' . $tmp_seller_id;
                            break;
                        }
                    }
                }
            }
        }
    }

    //访问的店铺不是当前登录用户的分销商或供货商（可以申请分销，需判断可分销级别，超最大分销级别 0为无限级分销）
    if (empty($is_seller) && $max_store_drp_level <= $drp_level && !empty($max_store_drp_level)) {
        $drp_link = false;
    }
}

include display('ucenter');
echo ob_get_clean();
?>