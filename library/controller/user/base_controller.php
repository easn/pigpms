<?php
/**
 * 基础类
 *
 */
class base_controller extends controller{
	public $user_session;
	public $store_session;
    public $allow_store_drp; //是否允许排他分销
    public $allow_platform_drp; //是否允许全网分销
    private $enabled_drp; //是否开启分销
	public function __construct(){
		parent::__construct();
        $open_store = true;
        if (!empty($_GET['sessid'])) {
            $_SESSION['sync_store'] = true;
            $user = M('User');
            $userinfo = $user->checkUser(array('session_id' => trim($_GET['sessid'])));
            if (!empty($userinfo)) {
                $_SESSION['user'] = $userinfo;
            } else {
                pigcms_tips('该用户不存在！');
            }
            if (!empty($_GET['store_id'])) {
                $tmp_store = M('Store')->getStoreById($_GET['store_id'],$userinfo['uid']);
                if($tmp_store) {
                    if(empty($tmp_store['logo'])) $tmp_store['logo'] = 'default_shop_2.jpg';
                    $_SESSION['store'] = $tmp_store;
                } else {
                    pigcms_tips('该店铺不存在！');
                }
            }
            //setcookie('session',serialize($_SESSION),$_SERVER['REQUEST_TIME']+10000000,'/');
			
			$data = array();
			$data['sync_store'] = true;
			$data['user'] = $userinfo['uid'];
			$data['store_id'] = $tmp_store['store_id'];
				
			setcookie('session',serialize($data),$_SERVER['REQUEST_TIME']+10000000,'/');
        }

		if(empty($_SESSION['user'])){
            if (!empty($_COOKIE['session'])) {
				$cookie_session = unserialize(stripslashes($_COOKIE['session']));
				$_SESSION['sync_store'] = $cookie_session['sync_store'];
				$userinfo = D('User')->where("uid = '" . $cookie_session['user'] . "'")->find();
				$_SESSION['user'] = $userinfo;
				
				if (!empty($cookie_session['store_id'])) {
					$tmp_store = M('Store')->getStoreById($cookie_session['store_id'], $userinfo['uid']);
					if($tmp_store) {
						if(empty($tmp_store['logo'])) {
							$tmp_store['logo'] = 'default_shop_2.jpg';
						}
						$_SESSION['store'] = $tmp_store;
					} else {
						pigcms_tips('该店铺不存在！');
					}
				}
            } else {
                //对接用户超时提示
                $is_sync     = option('config.weidian_version');
                if(empty($is_sync)) {
                    redirect(url('user:user:login'));
                }else{
                    redirect(url('user:user:sycn_timeout'));
                }
                
            }
		}

        if (empty($_SESSION['store']['store_id']) && (ACTION_NAME != 'store' && MODULE_NAME != 'setting')) {  //选择的店铺session过期
            $open_store = false;
        }

        $store_info = M('Store')->getStoreById($_SESSION['store']['store_id'], $_SESSION['store']['uid']);
        if (!empty($_SESSION['store']['drp_supplier_id'])) {
            if (empty($store_info['drp_approve'])) { //未审核店铺不能打开
                $open_store = false;
            }
        }

        if (empty($_SESSION['store']['drp_approve'])) {
            $_SESSION['store']['drp_approve'] = $store_info['drp_approve'];
        }

        if (empty($_SESSION['store']['drp_level'])) {
            $_SESSION['store']['drp_level'] = $store_info['drp_level'];
        }

        if(empty($_SESSION['store']['name']))
        {
            $_SESSION['store']['name'] = $store_info['name'];
        }

        if(empty($_SESSION['store']['update_drp_store_info']))
        {
            $_SESSION['store']['update_drp_store_info'] = $store_info['update_drp_store_info'];
        }

        if (empty($_SESSION['store']['drp_diy_store'])) { //不允许装修店铺
            $supplier = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $_SESSION['store']['store_id']))->find();
            if (!empty($supplier['supply_chain'])) {
                $supplier = explode(',', $supplier['supply_chain']);
                if (!empty($supplier[1])) {
                    $_SESSION['store']['top_supplier_id'] = !empty($supplier[1]) ? $supplier[1] : $_SESSION['store']['store_id'];
                }
            }
        }
        $_SESSION['drp_diy_store'] = $store_info['drp_diy_store'];

		$this->store_session = $_SESSION['store'];
		$this->assign('store_session',$_SESSION['store']);
		
		$this->user_session = $_SESSION['user'];
		$this->assign('user_session',$_SESSION['user']);
        $this->assign('open_store', $open_store); //是否能打开店铺
        $this->assign('store_select', url('store:select')); //店铺打不开跳转地址


        //是否启用分销
        $open_store_drp = option('config.open_store_drp');
        if (!$this->checkFx(true) && !$open_store_drp) {
            $this->assign('enabled_drp', false);
            $this->enabled_drp = false;
        } else {
            $this->assign('enabled_drp', true);
            $this->enabled_drp = true;
        }
        $this->assign('method', $method);
		
		
		//版本检测
		$version = $_COOKIE['my_version'];
		$this->my_version = $version;
		$this->assign('my_version',$version);
	}
	
	


    //分销检测(排他分销)
    protected function checkDrp($return = false)
    {
        //是否开启排他分销
        $open_store_drp = option('config.open_store_drp');
        //分销最大级别
        $max_store_drp_level = option('config.max_store_drp_level');
        //店铺是否允许分销（显示分销菜单）
        $this->allow_store_drp = false;
        if (!empty($open_store_drp) && (empty($this->store_session['drp_level']) || $this->store_session['drp_level'] <= $max_store_drp_level)) { //开启排他分销，并且店铺分销级别在允许分销级别内
            $this->allow_store_drp = true;
        }
        if ($return) {
            return $this->allow_store_drp;
        } else {
            $this->assign('allow_store_drp', $this->allow_store_drp);
        }
    }
    //分销检测(全网分销)
    protected function checkFx($return = false)
    {
        $open_platform_drp = option('config.open_platform_drp');
        if ($return) {
            return $this->open_platform_drp = $open_platform_drp;
        } else {
            $this->assign('allow_platform_drp', $open_platform_drp);
        }
    }

    protected function enabled_drp()
    {
        return $this->enabled_drp;
    }
}
?>