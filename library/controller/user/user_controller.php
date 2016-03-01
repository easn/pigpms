<?php
/**
 * 账号
 * User: pigcms-s
 * Date: 2015/09/07
 * Time: 13:41
 */
class user_controller extends controller{

		
	public function __construct(){      //显示声明一个构造方法且带参数
		parent::__construct();

		
	}

	
	/**
	 * 检测用户是否已经注册
	 */
	public function checkuser() {
		if($this->is_used_sms == '1') {
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
					default:
						echo json_encode(array('status' => '9999','msg' => '该手机号操作异常！'));exit;
						break;		
				}		
			}
		} else {
			echo json_encode(array('status' => '9998','msg' => '系统未开启短信功能'));exit;
		}
	}

	
	
	//登陆
	public function login() {
		if (!empty($this->user_session)) {
			$referer = $_GET['referer'] ? urldecode($_GET['referer']) : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $this->config['site_url']);
			redirect($referer);
		}

		// 登录处理
		if (IS_POST) {
			$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
			$password = isset($_POST['password']) ? trim($_POST['password']) : '';
			$referer = isset($_REQUEST['referer']) ? trim($_REQUEST['referer']) : '';
		
			if (empty($phone) || empty($password)) {
				echo json_encode(array('status' => false, 'msg' => '手机号或密码不能为空'));
				exit;
			}
		
			$data = array();
			$data['phone'] = $phone;
			$data['password'] = md5($password);
		
			$user = D('User')->where($data)->find();
			
			if (empty($user)) {
				echo json_encode(array('status' => false, 'msg' => '手机号或密码错误'));
				exit;
			}
			if($user['status']!=1) {
				echo json_encode(array('status' => false, 'msg' => '该账户已被禁止登陆！'));
				exit;
			}
			// 设置登录成功session
			$_SESSION['user'] = $user;

			$database_user = M('User');
			$save_result = $database_user->save_user(array('uid' => $user['uid']), array('login_count' => $user['login_count'] + 1, 'last_time' => $_SERVER['REQUEST_TIME'], 'last_ip' => ip2long(get_client_ip())));
			if ($save_result['err_code'] < 0) {
				json_encode(array('status' => false, 'msg' => '系统内部错误！请重试'));
			}
			if ($save_result['err_code'] > 0) {
				json_encode(array('status' => false, 'msg' => $save_result['err_msg']));
			}
			
			//$referer = option('config.site_url');
			$referer = url('store:select');
			// 门店管理员直接跳到门店管理 
			if ($user['type'] == '1' && $user['item_store_id']) {
				$store_physical = D('Store_physical')->where(array('pigcms_id' => $user['item_store_id']))->find();
				$store = M('Store')->getStore($store_physical['store_id']);
				
				if (!empty($store)) {
					$_SESSION['tmp_store_id'] = $store['store_id'];
					$_SESSION['store'] = $store;
					
					$referer = url('store:index');
				}
			}
			unset($_SESSION['forget_info']);
			echo json_encode(array('status' => true, 'msg' => '登录成功', 'data' => array('nexturl' => $referer)));
			exit;
		}
		
		$referer = $_GET['referer'] ? urldecode($_GET['referer']) : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $this->config['site_url']);
		$this->assign('referer', $referer);		
		// 分配变量
		$adver = M('adver');
		$ad = $adver->get_adver_by_key('pc_login_pic', 1);
		$this->assign('ad', $ad['0']);
					
		
		$this->display();exit;
	}
	
	
	/**
	 * 注册页面
	 */
	public function register() {
		if (!empty($this->user_session)) {
			$referer = $_GET['referer'] ? urldecode($_GET['referer']) : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $this->config['site_url']);
			redirect($referer);
		}
	
	
	
		// 提交注册
		if (IS_POST) {
			// 实例化user_model
			$user_model = M('User');
			$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
			$nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';
			$password = isset($_POST['password']) ? $_POST['password'] : '';
			$code = isset($_POST['code']) ? $_POST['code'] : '';
	
			if (empty($phone) || empty($nickname) || empty($password)) {
				echo json_encode(array('status' => false, 'msg' => '请完整填写注册信息'));
				exit;
			}
	
			if ($user_model->checkUser(array('phone' => $phone))) {
				echo json_encode(array('status' => false, 'msg' => '此手机号已经注册了'));
				exit;
			}
					
			/*  注册暂时不使用注册码
				if($this->is_used_sms == '1') {
				//检测验证码是否正确 300秒
				$record_sms = D('Sms_by_code')->where(array('mobile'=>$phone,'timestamp'=>array('>',time()-300)))->order("id desc")->limit(1)->find();
				if(empty($code)) {
				echo json_encode(array('status' => false, 'msg' => '请正确填写手机验证码！'));
				exit;
				}
	
				if (trim($code) != $record_sms['code']) {
				echo json_encode(array('status' => false, 'msg' =>  '手机验证码错误或过期！'));
				exit;
				}
				}
				*/
	
	 	
	
			/* if ($user_model->checkUser(array('nickname' => $nickname))) {
			 echo json_encode(array('status' => false, 'msg' => '此昵称已经注册了'));
			 exit;
			 } */
	
			$data = array();
			$data['nickname'] = $nickname;
			$data['phone'] = $phone;
			$data['password'] = md5($password);
	
			$user = $user_model->add_user($data);
	
			if ($user['err_code'] != 0) {
				echo json_encode(array('status' => false, 'msg' => '注册失败'));
				exit;
			}
	
			$user = $user_model->getUserById($user['err_msg']['uid']);
			$_SESSION['user'] = $user;
			unset($_SESSION['forget_info']);
			//$referer =  option('config.site_url');
			$referer = url('store:select');
			
			echo json_encode(array('status' => true, 'msg' => '注册成功', 'data' => array('nexturl' => $referer)));
			exit;
		}
	
		$referer = $_GET['referer'] ? urldecode($_GET['referer']) : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $this->config['site_url']);
		$this->assign('referer', $referer);
	
		// 判断是否已经登录，登录就进入主页
		/*
		 $adver = M('adver');
		 $ad = $adver->get_adver_by_key('pc_login_pic', 1);
		 // 分配变量
		 $this->assign('ad', $ad['0']);
		*/
		$mobile = $_COOKIE['reg_mobile'] ? $_COOKIE['reg_mobile'] : '';
	
		$record_sms = D('Sms_by_code')->where(array('mobile'=>$mobile))->order("id desc")->limit(1)->find();
	
		$this->assign('type', 'register');
		$this->assign('sms_register', $record_sms);
		$this->display('login');
	}
	
	
	public function logout() {
		
		unset($_SESSION['user']);
		session_destroy();
		$referer = url('user:login');
		redirect($referer);
	}

	
	public function sycn_timeout() {
		$this->display();
	}
	
	// 店铺后台添加订单时，用户检测
	public function order_check_user() {
		$phone = $_POST['phone'];
		
		if(!preg_match("/\d{5,12}$/", $phone)){
			json_return(1000, '请正确填写手机号');
		}
		
		$user = D('User')->where(array('phone' => $phone))->find();
		$is_weixin = false;
		$weixin_qr_image = '';
		$user_address_list = array();
		$uid = 0;
		if (empty($user)) {
			$data = array();
			$data['phone'] = $phone;
			$data['password'] = md5($phone);
			$data['reg_time'] = time();
			$data['reg_ip'] = get_client_ip(1);
			
			$user_model = D('User');
			$result = $user_model->data($data)->add();
			
			if ($result) {
				$uid = $user_model->lastInsID;
			} else {
				json_return(1000, '操作失败请重试');
			}
		} else {
			if ($user['openid']) {
				$is_weixin = true;
			}
			$uid = $user['uid'];
			$user_address_list = M('User_address')->select('', $user['uid']);
		}
		
		if (!$is_weixin) {
			$appid = option('config.wechat_appid');
			$appsecret = option('config.wechat_appsecret');
			
			if(empty($appid) || empty($appsecret)){
				$is_weixin = true;
			} else {
				// 后台添加订单二维码绑定，数值从1000000000
				$start_number = 1000000000;
				$qrcode_id = $start_number + $uid;
				
				import('Http');
				$http = new Http();
				
				//微信授权获得access_token
				import('WechatApi');
				$tokenObj = new WechatApi(array('appid' => $appid, 'appsecret' => $appsecret));
				$access_token = $tokenObj->get_access_token();
				
				$qrcode_url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token['access_token'];
				$post_data['expire_seconds'] = 604800;
				$post_data['action_name'] = 'QR_SCENE';
				$post_data['action_info']['scene']['scene_id'] = $qrcode_id;
				
				$json = $http->curlPost($qrcode_url, json_encode($post_data));
				
				if (!$json['errcode']){
					$weixin_qr_image = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($json['ticket']);
				}else{
					$is_weixin = true;
				}
			}
		}
		
		$return = array();
		$return['uid'] = $uid;
		$return['is_weixin'] = $is_weixin;
		$return['weixin_qr_image'] = $weixin_qr_image;
		$return['user_address_list'] = $user_address_list;
		
		json_return(0, $return);
	}
	
	// 用户收货地址
	public function user_address() {
		$uid = $_POST['uid'];
		$province = $_POST['province'];
		$city = $_POST['city'];
		$area = $_POST['area'];
		$name = $_POST['name'];
		$tel = $_POST['tel'];
		$address_id = $_POST['address_id'];
		$jiedao = $_POST['jiedao'];
		
		if (empty($uid)) {
			json_return(1000, '请选择用户');
		}
		
		$user = D('User')->where(array('uid' => $uid))->find();
		if (empty($user)) {
			json_return(1000, '未找到用户');
		}
		
		if (!empty($address_id)) {
			$user_address = D('User_address')->where(array('uid' => $uid, 'address_id' => $address_id))->find();
			if (empty($user_address)) {
				json_return(1000, '未找到要修改的收货地址');
			}
		}
		
		if (empty($province)) {
			json_return(1000, '省份没有选择');
		}
		
		if (empty($city)) {
			json_return(1000, '城市没有选择');
		}
		
		if (empty($area)) {
			json_return(1000, '地区没有选择');
		}
		
		if (empty($jiedao)) {
			json_return(1000, '街道没有填写');
		}
		
		if (empty($name)) {
			json_return(1000, '收货人没有填写');
		}
		
		if (empty($tel) || !preg_match("/\d{5,12}$/", $tel)) {
			json_return(1000, '手机号码格式不正确');
		}
		
		$data = array();
		$data['uid'] = $uid;
		$data['name'] = $name;
		$data['tel'] = $tel;
		$data['province'] = $province;
		$data['city'] = $city;
		$data['area'] = $area;
		$data['address'] = $jiedao;
		
		if (empty($address_id)) {
			$data['add_time'] = time();
			$address_id = D('User_address')->data($data)->add();
		} else {
			D('User_address')->where(array('address_id' => $address_id))->data($data)->save();
		}
		
		import('source.class.area');
		$areaClass = new area();
		
		$data['province_txt'] = $areaClass->get_name($data['province']);
		$data['city_txt'] = $areaClass->get_name($data['city']);
		$data['area_txt'] = $areaClass->get_name($data['area']);
		$data['address_id'] = $address_id;
		
		json_return(0, $data);
	}

	// 微信绑定
	public function weixin_bind() {
		$uid = $_POST['uid'];
		
		if (empty($uid)) {
			json_return(1000, '参数错误');
		}
		
		$user = D('User')->where(array('uid' => $uid))->find();
		if (empty($user)) {
			json_return(1000, '未找到用户');
		}
		
		if ($user['openid']) {
			json_return(0, '绑定成功');
		}
		
		json_return(1000, '');
	}
}