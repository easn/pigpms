<?php
/**
 *  评论
 */
require_once dirname(__FILE__).'/global.php';

$action = $_GET['action'];
$id = $_GET['id'];
$type = $_GET['type'];
$store_id =$_GET['store_id'];

if ($action == 'attention') {
	if (empty($_SESSION['wap_user']['uid'])) {
		echo json_encode(array('status' => false, 'msg' => '请先登录', 'data' => array('error' => 'login')));
		exit;
	}
	
	$data_id = $_GET['id'];
	$data_type = $_GET['type'];
	
	if (empty($data_id)) {
		echo json_encode(array('status' => false, 'msg' => '缺少最基本的参数'));
		exit;
	}
	
	if (!in_array($data_type, array(1, 2))) {
		echo json_encode(array('status' => false, 'msg' => '关注类型错误'));
		exit;
	}
	
	if ($data_type == 1) {
		$data = D('Product')->where(array('product_id' => $data_id, 'status' => 1))->find();
		if (empty($data)) {
			echo json_encode(array('status' => false, 'msg' => '未找到要关注的产品'));
			exit;
		}
	} else {
		$data = D('Store')->where(array('store_id' => $data_id, 'status' => 1))->find();
		if (empty($data)) {
			echo json_encode(array('status' => false, 'msg' => '未找到要关注的店铺'));
			exit;
		}
	}
	
	$attention_info = D('User_attention')->where(array('user_id' => $_SESSION['wap_user']['uid'], 'data_type' => $data_type, 'data_id' => $data_id,'store_id'=>$store_id))->find();
	if (!empty($attention_info)) {
		echo json_encode(array('status' => false, 'msg' => '已经关注过了'));
		exit;
	} else {
		M('User_attention')->add($_SESSION['wap_user']['uid'], $data_id, $data_type,$_GET['store_id']);
		echo json_encode(array('status' => true, 'msg' => '关注成功', 'data' => array('nexturl' => '')));
		exit;
	}
} else if ($action == 'add') {
	if (empty($_SESSION['wap_user']['uid'])) {
		echo json_encode(array('status' => false, 'msg' => '请先登录', 'data' => array('error' => 'login')));
		exit;
	}
	
	$dataid = $_GET['id'];
	$type = $_GET['type'];
	
	
	if (empty($dataid)) {
		echo json_encode(array('status' => false, 'msg' => '缺少最基本的参数'));
		exit;
	}
	
	if (!in_array($type, array(1, 2))) {
		echo json_encode(array('status' => false, 'msg' => '收藏类型错误'));
		exit;
	}
	
	if ($type == 1) {
		$data = D('Product')->where(array('product_id' => $dataid, 'status' => 1))->find();
		if (empty($data)) {
			echo json_encode(array('status' => false, 'msg' => '未找到要收藏的产品'));
			exit;
		}
	} else {
		$data = D('Store')->where(array('store_id' => $dataid, 'status' => 1))->find();
		if (empty($data)) {
			echo json_encode(array('status' => false, 'msg' => '未找到要收藏的店铺'));
			exit;
		}
	}
	
	// 查看是否已经收藏过
	$user_collect = D('User_collect')->where(array('user_id' => $_SESSION['wap_user']['uid'], 'type' => $type, 'dataid' => $dataid,'store_id'=>$store_id))->find();
	if (!empty($user_collect)) {
		echo json_encode(array('status' => false, 'msg' => '已经收藏过了'));
		exit;
	}
	
	M('User_collect')->add($_SESSION['wap_user']['uid'], $dataid, $type,$_GET['store_id']);
	echo json_encode(array('status' => true, 'msg' => '收藏成功', 'data' => array('nexturl' => '')));
	exit;
} else {
	echo json_encode(array('status' => false, 'msg' => '输入的网址有误'));
	exit;
}

echo ob_get_clean();
?>