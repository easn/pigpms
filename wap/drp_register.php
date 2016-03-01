<?php
/**
 *  分销商注册
 */
require_once dirname(__FILE__).'/drp_check.php';

$store = M('Store');
if (IS_POST && $_POST['type'] == 'check_store') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    if ($store->checkStoreExist(array('name' => $name, 'status' => 1))) {
        echo false;
    } else {
        echo true;
    }
    exit;
} else if (IS_POST && $_POST['type'] == 'check_phone') {
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $user = M('User');
    if ($user->checkUser(array('phone' => trim($_POST['phone']), 'uid' => array('!=', $_SESSION['wap_user']['uid'])))) {
        echo false;
    } else {
        echo true;
    }
    exit;
}


$store_id = isset($_GET['id']) ? intval(trim($_GET['id'])) : '';

if (empty($now_store)) {
    $now_store = $store->wap_getStore($store_id);
}

$seller_disabled     = false; //分销商禁用
//判断是否开启分销
$allow_drp           = option('config.open_store_drp');
$max_store_drp_level = option('config.max_store_drp_level'); //最大分销级别

if ($allow_drp) { //开启排他分销

    if (!empty($_SESSION['wap_user']['uid'])) { //用户登录
        if ($now_store['uid'] == $_SESSION['wap_user']['uid']) { //自己店铺
            if (!empty($now_store['drp_supplier_id'])) { //分销商
                $my_store_id = $store_id;
            } else {  //自营店铺

            }
        } else { //他人店铺
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
                        $my_store_id = $tmp_store['name'];
                        break;
                    } else if (!empty($tmp_store['drp_supplier_id'])) { //分销商
                        //获取店铺分销链（上级分销商）
                        $store_supply_chain = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $tmp_store['store_id'], 'type' => 1))->find();
                        $store_supply_chain = explode(',', $store_supply_chain['supply_chain']);
                        array_shift($store_supply_chain);
                        //当前访问的店铺的上级（当前访问的店铺是登陆用户的下级分销商）
                        if (in_array($tmp_store['store_id'], $supply_chain)) {
                            $my_store_id = $tmp_store['name'];
                            break;
                        } else if ($store_supply_chain == $supply_chain) { //同级分销商
                            $my_store_id = $tmp_store['name'];
                            break;
                        } else if (array_intersect($supply_chain, $store_supply_chain)) { //有交集的分销商
                            $my_store_id = $tmp_store['name'];
                            break;
                        } else { //可能存在当前访问店铺的非直属下级
                            $user_supply_chains[$tmp_store['store_id']] = $store_supply_chain;
                        }
                    }
                }
                if (!empty($user_supply_chains)) {
                    foreach ($user_supply_chains as $tmp_seller_id => $user_supply_chain) {
                        if (in_array($store_id, $user_supply_chain)) { //当前访问店铺的非直属下级
                            $my_store_id = $tmp_seller_id;
                            break;
                        }
                    }
                }
            }
        }
    }

    //访问的店铺不是当前登录用户的分销商或供货商（可以申请分销，需判断可分销级别，超最大分销级别 0为无限级分销）
    if (empty($is_seller) && !empty($max_store_drp_level) && $max_store_drp_level <= $drp_level) {
        redirect('./ucenter.php?id=' . $store_id);
    }
}

if (!empty($my_store_id)) {
    $my_store = D('Store')->where(array('store_id' => $my_store_id))->find();
    if (!empty($my_store['drp_supplier_id'])) {
        $_SESSION['wap_drp_store'] = $my_store;
    }
    $_SESSION['wap_user']['store_id'] = $my_store_id; //切换店铺
    redirect('./drp_ucenter.php');
}

//分销协议
$agreement = option('config.readme_content');

//判断用户是否设置密码
$user = M('User');
$userinfo = $user->getUserById($_SESSION['wap_user']['uid']);
$has_password = true;
if (empty($userinfo['password'])) {
    $has_password = false;
}
//分销商登陆地址
if (!empty($now_store['source_site_url']) && !empty($_SESSION['sync_user'])) {
    $admin_url = $now_store['source_site_url'] . '/api/weidian.php';
} else {
    $admin_url = option('config.site_url') . '/account.php';
}
//是否审核
$open_drp_approve = false;
if (!empty($now_store['open_drp_approve'])) {
    $open_drp_approve = true;
}

$nickname = !empty($userinfo['nickname']) ? $userinfo['nickname'] : '';

//是否允许分销商装修店铺(不允许 )
$open_drp_diy_store      = 0;
$open_drp_subscribe      = 0;
$open_drp_subscribe_auto = 0;

if (empty($now_store['drp_supplier_id'])) {

    $open_drp_subscribe      = $now_store['open_drp_subscribe'];
    $open_drp_subscribe_auto = $now_store['open_drp_subscribe_auto'];

    $weixin_bind = M('Weixin_bind')->get_access_token($store_id);
    if (!empty($weixin_bind)) {
        $is_subscribed = D('Subscribe_store')->where(array('uid' => $_SESSION['wap_user']['uid']))->count('sub_id');
        if ($is_subscribed <= 0) { //未关注
            if (!empty($open_drp_subscribe) || !empty($open_drp_subscribe_auto)) {
                $qrcode = M('Recognition')->get_drp_tmp_qrcode(200000000 + $_SESSION['wap_user']['uid'], $store_id);
            }
        }
    }
}

include display('drp_register');

echo ob_get_clean();