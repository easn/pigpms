<?php

/**
 *  店铺主页
 */
require_once dirname(__FILE__) . '/global.php';
$store_id = isset($_GET['id']) ? $_GET['id'] : pigcms_tips('您输入的网址有误', 'none');

// 预览切换
if (!$is_mobile && $_SESSION['user'] && option('config.synthesize_store')) {
    if (isset($_GET['ps']) && $_GET['ps'] == '800') {
		$config = option('config');

		$url = $config['site_url'] . '/index.php?c=store&a=index&id=' . $store_id . '&is_preview=1';
		echo redirect($url);
		exit;
    }
}

//店铺资料
$now_store = M('Store')->wap_getStore($store_id);

$fans_forever_uid = $_SESSION['wap_user']['uid'] ? $_SESSION['wap_user']['uid'] : $_SESSION['user']['uid'];
$_where['uid'] = $fans_forever_uid;
$sub_info = D('Subscribe_store')->where($_where)->order('subscribe_time asc')->find();

//粉丝终身制
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
					$product_info = D('Product')->get($_GET['id']);
					$data['store_id'] = $product_info['store_id'];
				}

				$data['subscribe_time'] = time();
				D('Subscribe_store')->data($data)->add();
			}
		} else {
			if (strpos($_SERVER['PHP_SELF'], 'home.php') !== false) {
				$temp_store = M('Store')->getStore($sub_info['store_id']);
				if($temp_store){
					if ($_GET['id'] != $sub_info['store_id']) {
						$url = $config['site_url'] . '/wap/home.php?id=' . $sub_info['store_id'];
						echo redirect($url);
						exit;
					
					} else if (strpos($_SERVER['PHP_SELF'], 'good.php') !== false) {
						if ($_GET['store_id'] != $sub_info['store_id']) {
							$url = $config['site_url'] . '/wap/home.php?id=' . $sub_info['store_id'];
							echo redirect($url);
							exit;
						}
					}
				}
			}
		}
    }
	

	
// 	if (strpos($_SERVER['PHP_SELF'], 'home.php') !== false) {
// 		if($_GET['new_status']){
// 			echo 111;exit;
// 		}
// 		if (($_GET['id'] != $sub_info['store_id']) && $sub_info) {
// 			$url = $config['site_url'] . '/wap/home.php?id=' . $sub_info['store_id'];
// 			echo redirect($url);
// 			exit;
// 		}
// 	} else if (strpos($_SERVER['PHP_SELF'], 'good.php') !== false) {
// 		if($_GET['new_status']){
// 			echo 222;exit;
// 		}
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


if (empty($now_store)) {
    pigcms_tips('您访问的店铺不存在', 'none');
}


if (!empty($now_store['top_supplier_id']) && empty($now_store['drp_diy_store'])) {

    $tmp_store_id = $now_store['top_supplier_id'];
} else {

    $tmp_store_id = $store_id;
}


//首页的微杂志
$homePage = D('Wei_page')->where(array('is_home' => 1, 'store_id' => $tmp_store_id))->find();

if (empty($homePage)) {
    pigcms_tips('您访问的店铺没有首页', 'none');
}

setcookie('wap_store_id', $now_store['store_id'], $_SERVER['REQUEST_TIME'] + 10000000, '/');

//当前页面的地址
$now_url = $config['wap_site_url'] . '/home.php?id=' . $now_store['store_id'];

//微杂志的自定义字段
if ($homePage['has_custom']) {
    $homeCustomField = M('Custom_field')->getParseFields($tmp_store_id, 'page', $homePage['page_id'], $store_id, $now_store['drp_level'], $now_store['drp_diy_store']);
}

//公共广告判断
$pageHasAd = false;
if ($now_store['open_ad'] && !empty($now_store['use_ad_pages'])) {
    $useAdPagesArr = explode(',', $now_store['use_ad_pages']);
    if (in_array('5', $useAdPagesArr)) {
	    $pageAdFieldArr = M('Custom_field')->getParseFields($tmp_store_id, 'common_ad', $tmp_store_id, $store_id, $now_store['drp_level'], $now_store['drp_diy_store']);
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

//店铺导航
if ($now_store['open_nav'] && !empty($now_store['use_nav_pages'])) {
    $useNavPagesArr = explode(',', $now_store['use_nav_pages']);
    if (in_array('1', $useNavPagesArr)) {
		$storeNav = M('Store_nav')->getParseNav($tmp_store_id, $store_id, $now_store['drp_diy_store']);
    }
}

//会员头像
$drp_notice          = false;
$is_seller           = false; //是否是分销商或供货商
$drp_register_url    = '';
$seller_name         = ''; //分销商
$seller_disabled     = false; //分销商禁用
//判断是否开启分销
$allow_drp           = option('config.open_store_drp');
$drp_level           = $now_store['drp_level']; //当前分销级别
$user_stores         = array();
$max_store_drp_level = option('config.max_store_drp_level'); //最大分销级别

if ($allow_drp && !empty($now_store['open_drp_guidance'])) { //开启排他分销和分销引导
	$drp_notice = true;
	if (!empty($_SESSION['wap_user']['uid'])) { //用户登录
		$drp_register_url = './drp_register.php?id=' . $store_id;

		$user = M('User');
		$avatar = $user->getAvatarById($_SESSION['wap_user']['uid']);
		if ($now_store['uid'] == $_SESSION['wap_user']['uid']) { //自己店铺
			if (!empty($now_store['drp_supplier_id'])) { //分销商
				$is_seller        = true;
				$seller_name      = $now_store['name'];
				$drp_notice       = true;
				$drp_register_url = './drp_register.php?id=' . $store_id;
			} else { //自营店铺（不显示分销引导）

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
						$is_seller        = true;
						$seller_name      = $tmp_store['name'];
						$drp_notice       = true;
						$drp_register_url = './drp_register.php?id=' . $tmp_store['store_id'];
						break;
					} else if (!empty($tmp_store['drp_supplier_id'])) { //分销商
						//获取店铺分销链（上级分销商）
						$store_supply_chain = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $tmp_store['store_id'], 'type' => 1))->find();
						$store_supply_chain = explode(',', $store_supply_chain['supply_chain']);
						array_shift($store_supply_chain);
						//当前访问的店铺的上级（当前访问的店铺是登陆用户的下级分销商）
						if (in_array($tmp_store['store_id'], $supply_chain)) {
							$is_seller        = true;
							$seller_name      = $tmp_store['name'];
							$drp_notice       = true;
							$drp_register_url = './drp_register.php?id=' . $tmp_store['store_id'];
							break;
						} else if ($store_supply_chain == $supply_chain) { //同级分销商
							$is_seller        = true;
							$seller_name      = $tmp_store['name'];
							$drp_notice       = true;
							$drp_register_url = './drp_register.php?id=' . $tmp_store['store_id'];
							break;
						} else if (array_intersect($supply_chain, $store_supply_chain)) { //有交集的分销商
							$is_seller        = true;
							$seller_name      = $tmp_store['name'];
							$drp_notice       = true;
							$drp_register_url = './drp_register.php?id=' . $tmp_store['store_id'];
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
							$is_seller        = true;
							$seller_name      = $tmp_store['name'];
							$drp_notice       = true;
							$drp_register_url = './drp_register.php?id=' . $tmp_seller_id;
							break;
						}
					}
				}
			}
		}
	}

	//访问的店铺不是当前登录用户的分销商或供货商（可以申请分销，需判断可分销级别，超最大分销级别 0为无限级分销）
	if (empty($is_seller) && $max_store_drp_level <= $drp_level && !empty($max_store_drp_level)) {
		$allow_drp        = false;
		$drp_notice       = false;
		$drp_register_url = '';
	}
}


if ($allow_drp&&!$now_store['is_official_shop']) {

	if (!empty($now_store['open_drp_limit']) && !empty($now_store['drp_limit_buy'])) {
		$msg = '亲爱的 <span style="color:#26CB40">' . $_SESSION['wap_user']['nickname'] . '</span>，在本店消费满 <span style="color:red">' . $now_store['drp_limit_buy'] . '</span> 元，即可申请分销！';
	} else {
		$msg = '亲爱的 <span style="color:#26CB40">' . $_SESSION['wap_user']['nickname'] . '</span>，申请分销即可分销赚佣金！';
	}

	if ($now_store['setting_canal_qrcode']) {
		$msg = '由分销商' . $now_store['name'] . '推荐，' . $_SESSION['wap_user']['nickname'] . '成为粉丝';
	}

}



//分享配置 start
$share_conf = array(
		'title'    => $now_store['name'], // 分享标题
		'desc'     => str_replace(array("\r", "\n"), array('', ''), !empty($now_store['intro']) ? $now_store['intro'] : $now_store['name']), // 分享描述
		'link'     => $now_store['url'], // 分享链接
		'imgUrl'   => $now_store['logo'], // 分享图片链接
		'type'     => '', // 分享类型,music、video或link，不填默认为link
		'dataUrl'  => '', // 如果type是music或video，则要提供数据链接，默认为空
);
//分享配置 end




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
			if (in_array($_GET['id'], $chain)) { //是当前店铺的下级分销商
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

	
if($seller_id2){
	$_where['store_id']=$seller_id;
	$store_info=D('Store')->where(array('store_id'=>$store_id))->find();
	$tmp_now_store = D('Store')->where(array('store_id'=>$store_info['store_id']))->find();
	$share_conf = array(
			'title'    => $tmp_now_store['name'], // 分享标题
			'desc'     => str_replace(array("\r", "\n"), array('', ''), !empty($tmp_now_store['intro']) ? $tmp_now_store['intro'] : $tmp_now_store['name']), // 分享描述
			'link'     => $tmp_now_store['url'], // 分享链接
			'imgUrl'   => $tmp_now_store['logo'], // 分享图片链接
			'type'     => '', // 分享类型,music、video或link，不填默认为link
			'dataUrl'  => '', // 如果type是music或video，则要提供数据链接，默认为空
			'store_id' => '',
			'uid'      => ''
	);
}else if($flag){
	$share_conf['store_id'] = $store_id;
	$share_conf['uid']      = $_SESSION['wap_user']['uid'];
}


import('WechatShare');
$share = new WechatShare();
$shareData = $share->getSgin($share_conf);
include display('home');

echo ob_get_clean();
?>