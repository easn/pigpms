<?php
/**
 *  用户登录
 */
require_once dirname(__FILE__).'/global.php';


  //判定wap站是否开启了 短信功能
	if(!$_G['config']['sms_topdomain'] || !$_G['config']['sms_price'] || !$_G['config']['sms_sign'] || !$_G['config']['sms_open'] || $_G['config']['is_open_wap_login_sms_check']=='0') {	
            $is_used_sms = '0'; //关闭使用
    } else {
            $is_used_sms = '1'; //开启使用
    }
$is_used_sms = '0';

if(IS_POST){
	$action = isset($_GET['action']) ? $_GET['action'] : 'login';
	switch($action){
		case 'login':
			if(empty($_POST['phone'])) json_return(1000,'请填写您的手机号码');
			if(empty($_POST['pwd'])) json_return(1001,'请填写您的密码');
			
			$database_user = M('User');
			$get_result = $database_user->get_user('phone',$_POST['phone']);
			if($get_result['err_code'] != 0) json_return($get_result['err_code'],$get_result['err_msg']);
			if(empty($get_result['user'])) json_return(1022,'用户不存在');
			if($get_result['user']['password'] != md5($_POST['pwd'])) json_return(1023,'密码不正确');
			
			$save_user_data = array('login_count'=>$get_result['user']['login_count']+1,'last_time'=>$_SERVER['REQUEST_TIME'],'last_ip'=>ip2long($_SERVER['REMOTE_ADDR']));
			if(!empty($_SESSION['openid'])){
				array_push($save_user_data,array('openid'=>$_SESSION['openid']));
			}
			$save_result = $database_user->save_user(array('uid'=>$get_result['user']['uid']),$save_user_data);
			if($save_result['err_code'] < 0) json_return(1009,'系统内部错误，请重试');
			if($save_result['err_code'] > 0) json_return($save_result['err_code'],$save_result['err_msg']);
			$_SESSION['wap_user'] = $get_result['user'];		
			mergeSessionUserInfo(session_id(),$get_result['user']['uid']);
			json_return(0,'登录成功');
			break;
		case 'reg':
			if(empty($_POST['phone'])) json_return(1010,'请填写您的手机号码');
			if(empty($_POST['pwd'])) json_return(1011,'请填写您的密码');

			$database_user = D('User');
			if($database_user->field('`uid`')->where(array('phone'=>$_POST['phone']))->find()) json_return(1014,'手机号码已存在');
			
			if($is_used_sms == '1') {
				if(!preg_match('/[0-9]{4}/',$_POST['code'])) {
					json_return(1012,'请正确填写短信验证码！');
				}
				
				//检测验证码是否正确 300秒
				$record_sms = D('Sms_by_code')->where(array('mobile'=>$_POST['phone'],'timestamp'=>array('>',time()-300)))->order("id desc")->limit(1)->find();
				if (trim($_POST['code']) != $record_sms['code']) {			
					json_return(1013,'手机验证码错误或过期！');
				}
			}	
		
            $data = array();
            $data['phone']       = trim($_POST['phone']);
            $data['nickname']    = '';
            $data['password']    = md5(trim($_POST['pwd']));
            $data['check_phone'] = 1;
            $data['login_count'] = 1;
			if(!empty($_SESSION['openid'])){
				$data['openid'] = $_SESSION['openid'];
			}
			$add_result = M('User')->add_user($data);
			if($add_result['err_code'] == 0){
				$_SESSION['wap_user'] = $add_result['err_msg'];
				mergeSessionUserInfo(session_id(),$add_result['err_msg']['uid']);
				json_return(0,'注册成功');
			}else{
				json_return(1,$add_result['err_msg']);
			}
				
			break;
			
		case 'checkuser':
				if($is_used_sms == '1') {
				if($_POST['is_ajax'] == 1) {
					
					$mobile = trim($_POST['mobile']);
					
					if(empty($mobile)) {
						echo json_encode(array('status' => '3', 'msg' => '手机号为空！'));
						exit;
					}
					
					if (!preg_match("/^1[3|5|7|8|9]{1}[0-9]{9}$/i", $mobile)) {
						echo json_encode(array('status' => '2', 'msg' => '该手机号不正确！'));
						exit;
					}
					
					$user = D('User')->where(array('phone'=>$mobile))->find();
					if($user) {
						echo json_encode(array('status' => '1', 'msg' => '对不起该手机号已存在！'));
						exit;
					}
					
					$record_sms = D('Sms_by_code')->where(array('mobile' => $mobile, 'type' => 'reg'))->order("id desc")->limit(1)->find();
					if(time() - $record_sms['timestamp']<=300) {
						
						echo json_encode(array('status' => '4','code'=>$record_sms['code'] , 'msg' => '短信验证码已发送至手机，请及时操作！'));exit;
					} 
				}
					
					//发送验证码
					$return = M('Sms_by_code')->send($mobile,'reg');
					if($return['code_return']=='0') {
						echo json_encode(array('status' => '0','code'=>$record_sms['code'] , 'msg' => '该手机号可以注册'));exit;
					} else {
						switch($return['code_return']) {
							case '4085':
									echo json_encode(array('status' => '4085','msg' => '该手机号验证码短信每天只能发五个！'));exit;
								break;
							case '4084':
									echo json_encode(array('status' => '4084','msg' => '该手机号验证码短信每天只能发四个！'));exit;
								break;
							case '4030':
									echo json_encode(array('status' => '4030','msg' => ' 手机号码已被列入黑名单 ！'));exit;
								break;
							case '408':
									echo json_encode(array('status' => '408','msg' => '您的帐户疑被恶意利用，已被自动冻结，如有疑问请与客服联系！'));exit;
								break;
							default:	echo json_encode(array('status' => '0','msg' => '该手机号操作异常！'));exit;
									echo json_encode(array('status' => '9999','msg' => '该手机号操作异常！'));exit;
								break;
									
						}
					
					}
				} else {
					echo json_encode(array('status' => '9998','msg' => '系统未开启短信功能'));exit;
				}			
		
			break;
	}
}else{
	//回调地址
	$redirect_uri = $_GET['referer'] ? $_GET['referer'] : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : ($_COOKIE['wap_store_id'] ? './home.php?id='.$_COOKIE['wap_store_id'] : $config['site_url']));

    if (!empty($_SESSION['wap_user'])) {
        redirect($redirect_uri);
    }

	include display('login3');
	
	echo ob_get_clean();
}
?>