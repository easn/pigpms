<?php
class user_model extends base_model{
	/*添加用户*/
	public function add_user($user){
		if(!isset($user['phone'])) return array('err_code'=>1006,'err_msg'=>'必须携带手机号！');
		if(!isset($user['nickname'])) return array('err_code'=>1007,'err_msg'=>'必须携带用户名！');
		if(!isset($user['password'])) return array('err_code'=>1008,'err_msg'=>'必须携带密码！');
		if(!isset($user['check_phone'])) $user['check_phone']=0;
        if(!isset($user['openid'])) $user['openid'];

		$data_user['phone'] = $user['phone'];
		$data_user['nickname'] = $user['nickname'];
		$data_user['password'] = $user['password'];
		$data_user['check_phone'] = $user['check_phone'];
		$data_user['reg_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user['reg_ip'] = $data_user['last_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
        $data_user['avatar'] = $user['avatar'];
        $data_user['login_count'] = $user['login_count'];
        $data_user['openid'] = $user['openid'];

        $data_user['drp_store_id'] = $user['drp_store_id']; //用户所属店铺id
        $data_user['item_store_id'] = $user['item_store_id']; //用户管理门店id
        $data_user['type'] = $user['type']; //管理员类型 0 店铺总管理员 1 门店管理员
		if($uid = $this->db->data($data_user)->add()){
			$data_user['uid'] = $uid;
            //var_dump($uid);
			return array('err_code'=>0,'err_msg'=>$data_user);
		}else{
			return array('err_code'=>1009,'err_msg'=>'用户添加失败！请重试。');
		}
	}

    /*修改资料*/
    public function edit_user($user){
        $condition_store_edit['uid'] = $user['uid'];
        $condition_store_edit['type'] = $user['type'];
        $data_user['phone'] = $user['phone'];
        $data_user['nickname'] = $user['nickname'];
        $data_user['reg_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
        $data_user['reg_ip'] = $data_user['last_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
        $data_user['login_count'] = $user['login_count'];
        $data_user['drp_store_id'] = $user['drp_store_id']; //用户所属店铺id
        $data_user['item_store_id'] = $user['item_store_id']; //用户管理门店id
        if($uid = $this->db->where($condition_store_edit)->data($data_user)->save()){
            $data_user['uid'] = $user['uid'];
            return array('err_code'=>0,'err_msg'=>$data_user);
        }else{
            return array('err_code'=>1009,'err_msg'=>'用户修改失败！请重试。');
        }
    }
	
	public function wap_getUser($uid) {
		
		  $user = $this->db->where(array('uid'=>$uid))->find();

		   if(!empty($user)){
			    /*是否移动端*/
                $is_mobile = is_mobile();
                /*是否微信端*/
                $is_weixin = is_weixin();
				
				/*如果是微信端，且配置文件中配置了微信信息，得到openid*/
                if($is_weixin && (empty($_SESSION['openid']) || empty($_SESSION['wap_user']))){
                    //openid存在 通过openid查找用户
					 
                    if (!empty($_SESSION['openid'])) {
                        $userinfo = M('User')->get_user('openid', $_SESSION['openid']);
                        $_SESSION['wap_user'] = $userinfo['user'];
                         mergeSessionUserInfo(session_id(), $userinfo['user']['uid']);
                         unset($_SESSION['wap_drp_store']);
                    }					
					
					//用户未登录 调用授权获取openid, 通过openid查找用户，如果已经存在，设置登录，如果不存在，添加一个新用户和openid关联
                    if (empty($_SESSION['wap_user'])) {
						 
						$customeUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
						
						if(empty($_GET['code'])){
							
							$oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.option('config.wechat_appid').'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope=snsapi_userinfo&state='.$_SESSION['weixin']['state'].'#wechat_redirect';
						 redirect($oauthUrl);exit;
						} else if(isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['weixin']['state'])){
							 
                                unset($_SESSION['weixin']);
                                import('Http');
                                $http = new Http();
								$tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.option('config.wechat_appid').'&secret='.option('config.wechat_appsecret').'&code='.$_GET['code'].'&grant_type=authorization_code';
                            		
						
                                $return = $http->curlGet($tokenUrl);
                                $jsonrt = json_decode($return,true);	

 

                                if($jsonrt['errcode']){
                                    $error_msg_class = new GetErrorMsg();
                                    exit('授权发生错误：'.$jsonrt['errcode']);
                                }
							
                                if($jsonrt['openid']){ //微信中打开直接登陆
                                    $url 	= 'https://api.weixin.qq.com/sns/userinfo?access_token='.$jsonrt['access_token'].'&openid='.$jsonrt['openid'].'&lang=zh_CN';
                                    $wxuser 	= $http->curlGet($url);
                                    $wxuser = json_decode($wxuser, true);
 
                                    $_SESSION['openid'] = $jsonrt['openid'];

                                    $userinfo = M('User')->get_user('openid', $_SESSION['openid']);
									 
                                    if(empty($userinfo['user'])){ //用户不存在，添加新用户，并设置登录
									
                                        $data = array();
                                        $data['phone']       = '';
                                        $data['nickname']    = $wxuser['nickname'];
                                        $data['openid']      = $_SESSION['openid'];
                                        $data['avatar']      = $wxuser['headimgurl'];
                                        $data['password']    = '';
                                        $data['check_phone'] = 1;
                                        $data['login_count'] = 1;
                                        $add_result = M('User')->add_user($data);
                                        if($add_result['err_code'] == 0){
                                            $_SESSION['wap_user'] = $add_result['err_msg'];
											$_SESSION['wap_user']['sex'] = $wxuser['sex'];
											$_SESSION['wap_user']['province'] = $wxuser['province'];
											$_SESSION['wap_user']['city'] = $wxuser['city'];
                                            mergeSessionUserInfo(session_id(),$add_result['err_msg']['uid']);
                                        }
                                    } else { //用户已存在，设置登录
									
                                        $_SESSION['wap_user'] = $userinfo['user'];
										$_SESSION['wap_user']['sex'] = $wxuser['sex'];
										$_SESSION['wap_user']['province'] = $wxuser['province'];
										$_SESSION['wap_user']['city'] = $wxuser['city'];
                                        mergeSessionUserInfo(session_id(), $userinfo['user']['uid']);
                                    }
                                    unset($_SESSION['wap_drp_store']); //删除保存在session中的分销店铺
                                }
								
						}
						
					}
							
							
				}
			   
		   }
		  
	}
	
	/*得到用户*/
	public function get_user($field,$value){
		if(!in_array($field,array('uid','phone','nickname','openid'))) return array('err_code'=>-1,'err_msg'=>'field参数错误！');
		if(empty($value)) return array('err_code'=>-1,'err_msg'=>'value参数错误！');
		return array('err_code'=>0,'user'=>$this->db->where(array($field=>$value))->find());
	}
	/*保存用户信息*/
	public function save_user($condition,$data){
		return array('err_code'=>0,'err_msg'=>$this->db->where($condition)->data($data)->save());
	}
	/*手机号、union_id、open_id 直接登录入口*/
	public function autologin($field,$value){
		$condition_user[$field] = $value;
		$now_user = $this->db->where($condition_user)->find();
		if($now_user){
			if(empty($now_user['status'])){
				return array('err_code' => -1, 'err_msg' => '该帐号被禁止登录!');
			}
			$condition_save_user['uid'] = $now_user['uid'];
			$data_save_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_save_user['last_ip'] = get_client_ip(1);
			$this->db->where($condition_save_user)->data($data_save_user)->save();

			return array('err_code' => 0, 'err_msg' => 'OK' ,'user'=>$now_user);
		}else{
			return array('err_code'=>1001,'err_msg'=>'没有此用户！');
		}
	}
    public function getUserById($uid)
    {
        $user = $this->db->where(array('uid' => $uid))->find();
        return $user;
    }

    //获取用户头像
    public function getAvatarById($uid)
    {
        $user = $this->db->field('avatar')->where(array('uid' => $uid))->find();
        return !empty($user['avatar']) ? $user['avatar'] : '';
    }

    //修改字段值
    public function setField($where, $data)
    {
        return $this->db->where($where)->data($data)->save();
    }

    //微信用户
    public function isWeixinFans($uid)
    {
        $user = $this->db->field('is_weixin')->where(array('uid' => $uid))->find();
        return !empty($user['is_weixin']) ? true : false;
    }

    //用户店铺数加1
    public function setStoreInc($uid)
    {
        return $this->db->where(array('uid' => $uid))->setInc('stores');
    }

    //用户店铺数减1
    public function setStoreDec($uid)
    {

        if($result = $this->db->where(array('uid' => $uid))->setDec('stores')) {
            /*$user = $this->db->field('stores')->where(array('uid' => $uid))->find();
            if (empty($user['stores'])) {
                $result = $this->db->where(array('uid' => $uid))->data(array('is_seller' => 0))->save();
            }*/
        }
        return $result;
    }

    //检测用户是否存在
    public function checkUser($where)
    {
        return $this->db->where($where)->find();
    }

    //检测店铺是否已被管理
    public function checkStore($where)
    {
        return $this->db->where($where)->find();
    }

    //添加新用户
    public function addUser($data)
    {
        return $this->db->data($data)->add();
    }

    //通过token获取开店用户
    public function getUserByToken($token) {
        $user = $this->db->where(array('token' => $token, 'is_seller' => 1))->find();
        return !empty($user['uid']) ? $user['uid'] : '';
    }

    public function getPaymentUrlByToken($token, $uid = '')
    {
        $where = array();
        $where['token'] = $token;
        if (!empty($uid)) {
            $where['uid'] = $uid;
        }
        $where['is_seller'] = 1;
        $user = $this->db->where($where)->find();
        return !empty($user['payment_url']) ? $user['payment_url'] : '';
    }

    public function getNotifyUrlByToken($token, $session_id = '', $third_id = '')
    {
        $where = array();
        $where['token'] = $token;
        if (!empty($session_id)) {
            $where['session_id'] = $session_id;
        }
        if (!empty($third_id)) {
            $where['third_id'] = $third_id;
        }
        $where['is_seller'] = 1;
        $user = $this->db->where($where)->find();
        return !empty($user['notify_url']) ? $user['notify_url'] : '';
    }

    //设置卖家
    public function setSeller($uid, $status)
    {
        return $this->db->where(array('uid' => $uid))->data(array('is_seller' => $status))->save();
    }

    //获取店铺粉丝数量
    public function getFansCount($where)
    {
        $sql = "SELECT COUNT(uid) AS count FROM " . option('system.DB_PREFIX') . 'user u';
        $_string = '';
        if (array_key_exists('_string', $where)) {
            $_string = ' AND ' . $where['_string'];
            unset($where['_string']);
        }
        $condition = array();
        foreach ($where as $key => $value) {
            $condition[] = $key . " = '" . $value . "'";
        }
        $where = ' WHERE ' . implode(' AND ', $condition) . $_string;
        $sql .= $where;
        $fans = $this->db->query($sql);
        if (!empty($fans)) {
            return !empty($fans[0]['count']) ? $fans[0]['count'] : 0;
        } else {
            return 0;
        }
    }

    //
    public function getFans($where, $offset, $limit, $order = '')
    {
        //$sql = "SELECT u.uid,u.nickname,u.phone,(SELECT COUNT(order_id) FROM " . option('system.DB_PREFIX') . "order o WHERE u.uid = o.uid) AS order_count, (SELECT SUM(total) FROM " . option('system.DB_PREFIX') . "order o WHERE u.uid = o.uid) AS order_total FROM " . option('system.DB_PREFIX') . "user u";
        $sql = "SELECT u.uid,u.nickname,u.phone,(SELECT COUNT(fx_order_id) FROM " . option('system.DB_PREFIX') . "fx_order fo1 WHERE fo1.uid = u.uid AND fo1.store_id = '" . $_SESSION['wap_drp_store']['store_id'] . "') AS order_count, (SELECT SUM(total) FROM " . option('system.DB_PREFIX') . "fx_order fo2  WHERE fo2.uid = u.uid AND fo2.store_id = '" . $_SESSION['wap_drp_store']['store_id'] . "') AS order_total FROM " . option('system.DB_PREFIX') . "user u";
        $_string = '';
        if (array_key_exists('_string', $where)) {
            $_string = ' AND ' . $where['_string'];
            unset($where['_string']);
        }
        $condition = array();
        foreach ($where as $key => $value) {
            $condition[] = $key . " = '" . $value . "'";
        }
        $where = ' WHERE ' . implode(' AND ', $condition) . $_string;
        $sql .= $where;
        if (empty($order)) {
            $order = 'u.uid DESC';
        }
        $order = ' ORDER BY ' . $order;
        $sql .= $order;
        $sql .= ' LIMIT ' . $offset . ',' . $limit;
        $fans = $this->db->query($sql);
        return $fans;
    }

	// 根据条件获取用户列表
	public function getList($where) {
		$user_list = $this->db->where($where)->select();

		$return_user_list = array();
		foreach ($user_list as $tmp) {
			if ($tmp['avatar']) {
				$tmp['avatar'] = getAttachmentUrl($tmp['avatar']);
			} else {
				$tmp['avatar'] = getAttachmentUrl('images/touxiang.png', false);
			}

			$return_user_list[$tmp['uid']] = $tmp;
		}

		return $return_user_list;
	}
}
?>