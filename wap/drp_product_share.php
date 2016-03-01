<?php
/**
 * 分销商品分享
 * User: pigcms_21
 * Date: 2015/7/9
 * Time: 13:54
 */

require_once dirname(__FILE__).'/global.php';

$product_id = isset($_GET['id']) ? $_GET['id'] : pigcms_tips('您输入的网址有误','none');
$store_id   = isset($_GET['store_id']) ? intval(trim($_GET['store_id'])) : pigcms_tips('您输入的网址有误','none');

$product = M('Product');
$user    = M('User');

//当前店铺
$nowStore = M('Store')->wap_getStore($store_id);

$nowProduct = $product->get(array('product_id' => $product_id));
//供货商
$store = array();
if (!empty($nowStore['drp_supplier_id'])) {
	$store = D('Store')->field('name')->where(array('store_id' => $nowStore['drp_supplier_id']))->find();
} else {
	$store['name'] = '自己';
}

$nowStore['logo'] = !empty($nowStore['logo']) ? getAttachmentUrl($nowStore['logo']) : getAttachmentUrl('images/avatar.png', false);

if ($nowStore['store_id'] == $nowProduct['store_id']) { //自营商品
	$drp_level = 0;
} else {
	$drp_level = $nowStore['drp_level'];
	if ($drp_level > 3) {
		$drp_level = 3;
	}
	$nowProduct['price'] = ($nowProduct['drp_level_' . $drp_level . '_price'] > 0) ? $nowProduct['drp_level_' . $drp_level . '_price'] : $nowProduct['price'];
}

if (empty($nowProduct)) {
	pigcms_tips('您访问的商品不存在','none');
}

//获取商品分销信息
if (!empty($nowProduct['unified_price_setting']) && !empty($drp_level)) { //分销商的价格
	$cost_price = $nowProduct['drp_level_' . $drp_level . '_cost_price']; //成本
	$min_fx_price = number_format($nowProduct['drp_level_' . $drp_level . '_price'] - $cost_price, 2, '.', ''); //最低分销价
	$max_fx_price = number_format($nowProduct['drp_level_' . $drp_level . '_price'] - $cost_price, 2, '.', ''); //最高分销价
} else {
	$cost_price = ($nowProduct['cost_price'] > 0) ? $nowProduct['cost_price'] : $nowProduct['price']; //成本
	$nowProduct['min_fx_price'] = ($nowProduct['min_fx_price'] > 0) ? $nowProduct['min_fx_price'] : $nowProduct['price'];
	$nowProduct['max_fx_price'] = ($nowProduct['max_fx_price'] > 0) ? $nowProduct['max_fx_price'] : $nowProduct['price'];
	$min_fx_price = number_format($nowProduct['min_fx_price'] - $cost_price, 2, '.', ''); //最低分销价
	$max_fx_price = number_format($nowProduct['max_fx_price'] - $cost_price, 2, '.', ''); //最高分销价http://wx.pushitong.com
	$max_fx_price = max(array($min_fx_price, $max_fx_price));
}

//分销利润
$profit = $max_fx_price;

//分享配置 start
$share_conf 	= array(
	'title' 	=> $nowProduct['name'], // 分享标题
	'desc' 		=> str_replace(array("\r","\n"), array('',''), !empty($nowProduct['intro']) ? $nowProduct['intro'] : $nowProduct['name']), // 分享描述
	'link' 		=> option('config.wap_site_url') . '/good.php?id=' . $product_id . '&store_id=' . $nowStore['store_id'], // 分享链接
	'imgUrl' 	=> getAttachmentUrl($nowProduct['image']), // 分享图片链接
	'type'		=> '', // 分享类型,music、video或link，不填默认为link
	'dataUrl'	=> '', // 如果type是music或video，则要提供数据链接，默认为空
);

import('WechatShare');
$share 		= new WechatShare();
$shareData 	= $share->getSgin($share_conf);
//分享配置 end

include display('drp_product_share');

echo ob_get_clean();