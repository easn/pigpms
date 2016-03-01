<?php
class store_nav_model extends base_model{
	public function getStoreNav($store_id){
		$nav = $this->db->where(array('store_id' => $store_id))->find();
		return $nav;
	}

	public function add($data){
		return $this->db->data($data)->add();
	}

	public function edit($where, $data){
		return $this->db->where($where)->data($data)->save();
	}
	public function getParseNav($store_id, $seller_id = 0, $drp_diy_store = 1){
		$nav = $this->getStoreNav($store_id);
		if(empty($nav)) return '';
		$navData = unserialize($nav['data']);
		if(empty($navData)) return '';
		$storeNav = '';
		switch($nav['style']){
			case '1':
				$storeNav.= '<div class="js-navmenu js-footer-auto-ele shop-nav nav-menu nav-menu-1 has-menu-'.count($navData).'">';
                if (empty($drp_diy_store)) {
                    $storeNav .= '<div class="nav-special-item nav-item"><a href="./home.php?id=' . $seller_id . '" class="home">主页</a></div>';
                } else {
                    $storeNav .= '<div class="nav-special-item nav-item"><a href="./home.php?id=' . $store_id . '" class="home">主页</a></div>';
                }
                foreach($navData as $value){
                    $flag = true;
                    $style = '';
					switch($value['url']){
						case 'ucenter':
                            if (empty($drp_diy_store)) {
                                $value['url'] = './ucenter.php?id='.$seller_id;
                            } else {
                                $value['url'] = './ucenter.php?id='.$store_id;
                            }
							break;
                        case 'drp':
                            $seller_disabled = false;
                            if (!option('config.open_store_drp')) { //未开启排他分销
                                continue 2;
                            }
                            if ((!empty($_SESSION['store']) || !empty($_SESSION['user'])) && empty($_SESSION['store']['drp_supplier_id'])) { //禁止分销自己的店铺
                                $flag = false;
                            }
                            $tmp_store_id = !empty($seller_id) ? $seller_id : $store_id;
                            $store = D('Store')->field('uid,drp_level,status')->where(array('store_id' => $tmp_store_id))->find();
                            //最大分销级别
                            $max_store_drp_level = option('config.max_store_drp_level'); //最大分销级别
                            if ((!empty($max_store_drp_level) && $store['drp_level'] >= $max_store_drp_level) || in_array($store['status'], array(4,5))) {
                                if (in_array($store['status'], array(4,5))) {
                                    $seller_disabled = true;
                                }
                                continue 2;
                            }

                            $supply_chain = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $tmp_store_id, 'type' => 1))->find();
                            $supply_chain = explode(',', $supply_chain['supply_chain']);
                            $stores = D('Store')->where(array('uid' => $_SESSION['wap_user']['uid']))->select();
                            $supply_chain2 = array();
                            if (!empty($stores)) {
                                foreach ($stores as $tmp_store) {
                                    $tmp_supply_chain2 = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $tmp_store['store_id']))->find();
                                    $tmp_supply_chain2 = explode(',', $tmp_supply_chain2['supply_chain']);
                                    if (in_array($tmp_store['store_id'], $supply_chain)) { //当前店铺上级
                                        $tmp_store_id = $tmp_store['store_id'];
                                        $my_store_id = $tmp_store_id;
                                        break;
                                    }
                                    $supply_chain2[$tmp_store['store_id']] = $tmp_supply_chain2;
                                }
                                if (!empty($supply_chain2)) {
                                    foreach ($supply_chain2 as $tmp_seller_id => $tmp_supply_chain2) {
                                        if (in_array($tmp_store_id, $tmp_supply_chain2)) { //当前店铺下级
                                            $tmp_store_id = $tmp_seller_id;
                                            $my_store_id = $tmp_store_id;
                                            break;
                                        }
                                    }
                                }
                            }

                            if (!empty($tmp_store_id)) { //指定分销分销店铺
                                if ($_SESSION['wap_user']['uid'] == $store['uid']) { //自己店铺
                                    $drp_store = M('Store')->getUserDrpStore($_SESSION['wap_user']['uid'], intval(trim($tmp_store_id)), 0, 0);
                                } else { //他人店铺（供货商）
                                    $drp_store = D('Store')->where(array('drp_supplier_id' => $tmp_store_id, 'uid' => $_SESSION['wap_user']['uid'], 'status' => 1))->find();
                                }
                            }
                            if (!empty($_SESSION['wap_drp_store']) && $_SESSION['wap_drp_store']['uid'] != $_SESSION['wap_user']['uid']) {
                                unset($_SESSION['wap_drp_store']);
                            }

                            if (!empty($my_store_id)) {
                                $my_store = D('Store')->where(array('store_id' => $my_store_id))->find();
                                if (!empty($my_store['drp_supplier_id']) && $my_store['status'] == 1) {
                                    $_SESSION['wap_drp_store'] = $my_store;
                                    $flag = true;
                                    $value['name'] = '分销管理';
                                    $value['url'] = './drp_register.php?id=' . $my_store_id;
                                } else {
                                    $my_store_id = 0;
                                    if ($my_store['status'] != 1) {
                                        $seller_disabled = true;
                                    }
                                }
                            }

                            if ((!empty($_SESSION['wap_drp_store']) && $_SESSION['wap_drp_store']['drp_supplier_id'] == $tmp_store_id) || (!empty($_SESSION['wap_user']) && !empty($drp_store))) {
                                if (!empty($_SESSION['wap_drp_store'])) {
                                    $fx_store_id = $_SESSION['wap_drp_store']['store_id'];
                                    $drp_store = D('Store')->field('status')->where(array('store_id' => $fx_store_id))->find();
                                } else {
                                    $_SESSION['wap_drp_store'] = $drp_store;
                                    $fx_store_id = $drp_store['store_id'];
                                }
                                if ($drp_store['status'] != 1) {
                                    $seller_disabled = false;
                                }
                                $value['name'] = '分销管理';
                                $value['url'] = './drp_register.php?id=' . $fx_store_id;
                            } else if (empty($my_store_id)) {
                                if ($seller_disabled) {
                                    continue 2;
                                }
                                if (!empty($_SESSION['wap_user']['uid'])) {
                                    $tmp_store = D('Store')->field('uid')->where(array('store_id' => $tmp_store_id))->find();
                                    if (!empty($tmp_store['uid']) && $tmp_store['uid'] == $_SESSION['wap_user']['uid']) {
                                        $flag = false;
                                    }
                                }
                                if ($flag) {
                                    if (!empty($_SESSION['wap_user']['uid'])) {
                                        if (empty($drp_diy_store)) {
                                            $value['url'] = './drp_register.php?id=' . $seller_id;
                                        } else {
                                            $value['url'] = './drp_register.php?id=' . $store_id;
                                        }
                                    } else {
                                        if (empty($drp_diy_store)) {
                                            $value['url'] = './login.php?referer=' . urlencode(option('config.wap_site_url') . '/drp_register.php?id=' . $seller_id);
                                        } else {
                                            $value['url'] = './login.php?referer=' . urlencode(option('config.wap_site_url') . '/drp_register.php?id=' . $store_id);
                                        }
                                    }
                                } else {
                                    $value['url'] = 'javascript:void(0);';
                                    $style = 'style="color:lightgray;cursor:no-drop;"';
                                }
                            }
                            break;
                        case 'home':
                            if (empty($drp_diy_store)) {
                                $value['url'] = './home.php?id=' . $seller_id;
                            } else {
                                $value['url'] = './home.php?id=' . $store_id;
                            }
                            break;
                        case 'cart':
                            if (empty($drp_diy_store)) {
                                $value['url'] = './cart.php?id=' . $seller_id;
                            } else {
                                $value['url'] = './cart.php?id=' . $store_id;
                            }
                            break;
                        default:
                            if (empty($drp_diy_store) && !empty($value['url']) && stripos($value['url'], option('config.site_url')) !== false) {
                                if (stripos($value['url'], 'home.php?id=') !== false) {
                                    $value['url'] = preg_replace('/home.php\?id=(\d)/i', 'home.php?id=' . $seller_id, $value['url']);
                                    $value['url'] = preg_replace('/home.php\?store_id=(\d)/i', 'home.php?id=' . $seller_id, $value['url']);
                                } else {
                                    $value['url'] .= '&store_id=' . $seller_id;
                                }
                            }
                            break;
					}
					$storeNav.= '<div class="nav-item">';
					$storeNav.= '<a class="mainmenu js-mainmenu" href="'.(!empty($value['url']) ? $value['url'] : 'javascript:void(0);').'">'.(!empty($value['submenu']) ? '<i class="arrow-weixin"></i>' : '').'<span class="mainmenu-txt" ' . $style . '>'.$value['name'].'</span></a>';
					if(!empty($value['submenu'])){
						$storeNav.= '<div class="submenu js-submenu"><span class="arrow before-arrow"></span><span class="arrow after-arrow"></span><ul>';
                        $style = '';
                        foreach($value['submenu'] as $k=>$v){
                            $flag = true;
                            if (strtolower($v['url']) == 'drp') {
                                if (!option('config.open_store_drp')) { //未开启排他分销
                                    continue 2;
                                }
                                if ((!empty($_SESSION['store']) || !empty($_SESSION['user'])) && empty($_SESSION['store']['drp_supplier_id'])) { //禁止分销自己的店铺
                                    $flag = false;
                                }
                                $tmp_store_id = !empty($seller_id) ? $seller_id : $store_id;

                                $store = D('Store')->field('uid,drp_level')->where(array('store_id' => $tmp_store_id))->find();
                                //最大分销级别
                                $max_store_drp_level = option('config.max_store_drp_level'); //最大分销级别
                                if ($store['drp_level'] >= $max_store_drp_level && !empty($max_store_drp_level)) {
                                    continue 2;
                                }

                                $supply_chain = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $tmp_store_id, 'type' => 1))->find();
                                $supply_chain = explode(',', $supply_chain['supply_chain']);
                                $stores = D('Store')->where(array('uid' => $_SESSION['wap_user']['uid']))->select();
                                $supply_chain2 = array();
                                if (!empty($stores)) {
                                    foreach ($stores as $tmp_store) {
                                        $tmp_supply_chain2 = D('Store_supplier')->field('supply_chain')->where(array('seller_id' => $tmp_store['store_id']))->find();
                                        $tmp_supply_chain2 = explode(',', $tmp_supply_chain2['supply_chain']);
                                        if (in_array($tmp_store['store_id'], $supply_chain)) { //当前店铺上级
                                            $tmp_store_id = $tmp_store['store_id'];
                                            $my_store_id = $tmp_store_id;
                                            break;
                                        }
                                        $supply_chain2[$tmp_store['store_id']] = $tmp_supply_chain2;
                                    }
                                    if (!empty($supply_chain2)) {
                                        foreach ($supply_chain2 as $tmp_seller_id => $tmp_supply_chain2) {
                                            if (in_array($tmp_store_id, $tmp_supply_chain2)) { //当前店铺下级
                                                $tmp_store_id = $tmp_seller_id;
                                                $my_store_id = $tmp_store_id;
                                                break;
                                            }
                                        }
                                    }
                                }

                                if (!empty($tmp_store_id)) { //指定分销分销店铺
                                    $store = D('Store')->field('uid')->where(array('store_id' => $tmp_store_id))->find();
                                    if ($_SESSION['wap_user']['uid'] == $store['uid']) { //自己店铺
                                        $drp_store = M('Store')->getUserDrpStore($_SESSION['wap_user']['uid'], intval(trim($tmp_store_id)), 0, 0);
                                    } else { //他人店铺（供货商）
                                        $drp_store = D('Store')->where(array('drp_supplier_id' => $tmp_store_id, 'uid' => $_SESSION['wap_user']['uid'], 'status' => 1))->find();
                                    }
                                }
                                if (!empty($_SESSION['wap_drp_store']) && $_SESSION['wap_drp_store']['uid'] != $_SESSION['wap_user']['uid']) {
                                    unset($_SESSION['wap_drp_store']);
                                }

                                if (!empty($my_store_id)) {
                                    $my_store = D('Store')->where(array('store_id' => $my_store_id))->find();
                                    if (!empty($my_store['drp_supplier_id'])) {
                                        $_SESSION['wap_drp_store'] = $my_store;
                                        $flag = true;
                                        $value['name'] = '分销管理';
                                        $value['url'] = './drp_register.php?id=' . $my_store_id;
                                    } else {
                                        $my_store_id = 0;
                                    }
                                }

                                if ((!empty($_SESSION['wap_drp_store']) && $_SESSION['wap_drp_store']['drp_supplier_id'] == $tmp_store_id) || (!empty($_SESSION['wap_user']) && !empty($drp_store))) {
                                    if (!empty($_SESSION['wap_drp_store'])) {
                                        $fx_store_id = $_SESSION['wap_drp_store']['store_id'];
                                    } else {
                                        $_SESSION['wap_drp_store'] = $drp_store;
                                        $fx_store_id = $drp_store['store_id'];
                                    }
                                    $v['name'] = '分销管理';
                                    $v['url'] = './drp_ucenter.php?id=' . $fx_store_id;
                                } else if (empty($my_store_id)) {
                                    if (!empty($_SESSION['wap_user']['uid'])) {
                                        $tmp_store = D('Store')->field('uid')->where(array('store_id' => $tmp_store_id))->find();
                                        if (!empty($tmp_store['uid']) && $tmp_store['uid'] == $_SESSION['wap_user']['uid']) {
                                            $flag = false;
                                        }
                                    }
                                    if ($flag) {
                                        if (!empty($_SESSION['wap_user']['uid'])) {
                                            if (empty($drp_diy_store)) {
                                                $v['url'] = './drp_register.php?id=' . $seller_id;
                                            } else {
                                                $v['url'] = './drp_register.php?id=' . $store_id;
                                            }
                                        } else {
                                            if (empty($drp_diy_store)) {
                                                $v['url'] = './login.php?referer=' . urlencode(option('config.wap_site_url') . '/drp_register.php?id=' . $seller_id);
                                            } else {
                                                $v['url'] = './login.php?referer=' . urlencode(option('config.wap_site_url') . '/drp_register.php?id=' . $store_id);
                                            }
                                        }
                                    } else {
                                        $v['url'] = 'javascript:void(0);';
                                        $style = 'style="color:lightgray;cursor:no-drop;"';
                                    }
                                }
                            } else if (strtolower($v['url']) == 'ucenter') {
                                if (empty($drp_diy_store)) {
                                    $v['url'] = './ucenter.php?id=' . $seller_id;
                                } else {
                                    $v['url'] = './ucenter.php?id=' . $store_id;
                                }
                            } else if (strtolower($v['url']) == 'home') {
                                if (empty($drp_diy_store)) {
                                    $v['url'] = './home.php?id=' . $seller_id;
                                } else {
                                    $v['url'] = './home.php?id=' . $store_id;
                                }
                            } else {
                                if (empty($drp_diy_store) && !empty($v['url']) && stripos($v['url'], option('config.site_url')) !== false) {
                                    if (stripos($v['url'], 'home.php?id=') !== false) {
                                        $v['url'] = preg_replace('/home.php\?id=(\d)/i', 'home.php?id=' . $seller_id, $v['url']);
                                        $v['url'] = preg_replace('/home.php\?store_id=(\d)/i', 'home.php?id=' . $seller_id, $v['url']);
                                    } else {
                                        $v['url'] .= '&store_id=' . $seller_id;
                                    }
                                }
                            }
                            $storeNav.= ' <li><a href="'.(!empty($v['url']) ? $v['url'] : 'javascript:void(0);').'" ' . $style . '>'.$v['name'].'</a></li>';
							if($k != count($value['submenu'])-1){
								$storeNav.= '<li class="line-divide"></li>';
							}
						}
						$storeNav.= '</ul></div>';
					}
					$storeNav.= '</div>';
				}
				
				$storeNav .= '</div>';
				break;
			case '2':
                foreach($navData as $value){
                    $flag = true;
                    $style = '';
					switch($value['url']){
						case 'ucenter':
                            if (empty($drp_diy_store)) {
                                $value['url'] = './ucenter.php?id=' . $seller_id;
                            } else {
                                $value['url'] = './ucenter.php?id=' . $store_id;
                            }
							break;
                        case 'drp':
                            if (!option('config.open_store_drp')) { //未开启排他分销
                                continue 2;
                            }
                            if ((!empty($_SESSION['store']) || !empty($_SESSION['user'])) && empty($_SESSION['store']['drp_supplier_id'])) { //禁止分销自己的店铺
                                $flag = false;
                            }
                            if (!empty($_GET['id'])) { //指定分销分销店铺
                                $drp_store = M('Store')->getUserDrpStore($_SESSION['wap_user']['uid'], intval(trim($_GET['id'])));
                            }
                            if (!empty($_SESSION['wap_drp_store']) || (!empty($_SESSION['wap_user']) && !empty($drp_store))) {
                                if (!empty($_SESSION['wap_drp_store'])) {
                                    $fx_store_id = $_SESSION['wap_drp_store']['store_id'];
                                } else {
                                    $fx_store_id = $drp_store['store_id'];
                                }
                                $value['name'] = '分销管理';
                                $value['url'] = './drp_register.php?id=' . $fx_store_id;
                            } else {
                                if (!empty($_SESSION['wap_user']['uid'])) {
                                    $tmp_store = D('Store')->field('uid')->where(array('store_id' => $store_id))->find();
                                    if (!empty($tmp_store['uid']) && $tmp_store['uid'] == $_SESSION['wap_user']['uid']) {
                                        $flag = false;
                                    }
                                }
                                if ($flag) {
                                    if (!empty($_SESSION['wap_user']['uid'])) {
                                        $value['url'] = './drp_register.php?id=' . $store_id;
                                    } else {
                                        $value['url'] = './login.php?referer=' . urlencode(option('config.wap_site_url') . '/drp_register.php?id=' . $store_id);
                                    }
                                } else {
                                    $value['url'] = 'javascript:void(0);';
                                    $style = 'style="color:lightgray;cursor:no-drop;"';
                                }
                            }
                            break;
                        case 'home':
                            if (empty($drp_diy_store)) {
                                $value['url'] = './home.php?id=' . $seller_id;
                            } else {
                                $value['url'] = './home.php?id=' . $store_id;
                            }
                            break;
                        case 'cart':
                            if (empty($drp_diy_store)) {
                                $value['url'] = './cart.php?id=' . $seller_id;
                            } else {
                                $value['url'] = './home.php?id=' . $store_id;
                            }
                            break;
					}
					if(!empty($value['image1']) && (substr($value['image1'],0,9) == './upload/' || substr($value['image1'],0,11) == './template/')){
						$value['image1'] = str_replace('./upload/',option('config.site_url').'/upload/',$value['image1']);
						$value['image1'] = str_replace('./template/',option('config.site_url').'/template/',$value['image1']);
					}
					if(!empty($value['image2']) && (substr($value['image2'],0,9) == './upload/' || substr($value['image2'],0,11) == './template/')){
						$value['image2'] = str_replace('./upload/',option('config.site_url').'/upload/',$value['image2']);
						$value['image2'] = str_replace('./template/',option('config.site_url').'/template/',$value['image2']);
					}
					$newNavData[] = $value;
				}
                if (!empty($nav['bgcolor'])) {
                    $background_color = $nav['bgcolor'];
                } else {
                    $background_color = '#2B2D30';
                }
				$storeNav.= '<div class="js-navmenu js-footer-auto-ele shop-nav nav-menu nav-menu-2 has-menu-'.(count($newNavData)).'" style="background-color:' . $background_color . ';">';
				$storeNav.= '<ul class="clearfix">';
				foreach($newNavData as $key=>$value){
					$storeNav.= '<li><a href="'.($value['url'] ? $value['url'] : 'javascript:;').'" style="background-image:url('.$value['image1'].');" title="'.$value['text'].'"></a></li>';
				}
				$storeNav .= '</ul>';
				$storeNav .= '</div>';
				break;
			case '3':
                foreach($navData as $value){
                    $flag = true;
                    $style = '';
					switch($value['url']){
						case 'ucenter':
                            if (empty($drp_diy_store)) {
                                $value['url'] = './ucenter.php?id=' . $seller_id;
                            } else {
                                $value['url'] = './ucenter.php?id=' . $store_id;
                            }
							break;
                        case 'drp':
                            if (!option('config.open_store_drp')) { //未开启排他分销
                                continue 2;
                            }
                            if ((!empty($_SESSION['store']) || !empty($_SESSION['user'])) && empty($_SESSION['store']['drp_supplier_id'])) { //禁止分销自己的店铺
                                $flag = false;
                            }
                            if (!empty($_GET['id'])) { //指定分销分销店铺
                                $drp_store = M('Store')->getUserDrpStore($_SESSION['wap_user']['uid'], $store_id);
                            }
                            if (!empty($_SESSION['wap_drp_store']) || (!empty($_SESSION['wap_user']) && !empty($drp_store))) {
                                if (!empty($_SESSION['wap_drp_store'])) {
                                    $fx_store_id = $_SESSION['wap_drp_store']['store_id'];
                                } else {
                                    $fx_store_id = $drp_store['store_id'];
                                }
                                $value['name'] = '分销管理';
                                $value['url'] = './drp_register.php?id=' . $fx_store_id;
                            } else {
                                if (!empty($_SESSION['wap_user']['uid'])) {
                                    $tmp_store = D('Store')->field('uid')->where(array('store_id' => $store_id))->find();
                                    if (!empty($tmp_store['uid']) && $tmp_store['uid'] == $_SESSION['wap_user']['uid']) {
                                        $flag = false;
                                    }
                                }
                                if ($flag) {
                                    if (!empty($_SESSION['wap_user']['uid'])) {
                                        $value['url'] = './drp_register.php?id=' . $store_id;
                                    } else {
                                        $value['url'] = './login.php?referer=' . urlencode(option('config.wap_site_url') . '/drp_register.php?id=' . $store_id);
                                    }
                                } else {
                                    $value['url'] = 'javascript:void(0);';
                                    $style = 'style="color:lightgray;cursor:no-drop;"';
                                }
                            }
                            break;
                        case 'home':
                            if (empty($drp_diy_store)) {
                                $value['url'] = './home.php?id=' . $seller_id;
                            } else {
                                $value['url'] = './home.php?id=' . $store_id;
                            }
                            break;
                        case 'cart':
                            if (empty($drp_diy_store)) {
                                $value['url'] = './cart.php?id=' . $seller_id;
                            } else {
                                $value['url'] = './cart.php?id=' . $store_id;
                            }
                            break;
					}
					if(!empty($value['image1']) && (substr($value['image1'],0,9) == './upload/' || substr($value['image1'],0,11) == './template/')){
						$value['image1'] = str_replace('./upload/',option('config.site_url').'/upload/',$value['image1']);
						$value['image1'] = str_replace('./template/',option('config.site_url').'/template/',$value['image1']);
					}
					if(!empty($value['image2']) && (substr($value['image2'],0,9) == './upload/' || substr($value['image2'],0,11) == './template/')){
						$value['image2'] = str_replace('./upload/',option('config.site_url').'/upload/',$value['image2']);
						$value['image2'] = str_replace('./template/',option('config.site_url').'/template/',$value['image2']);
					}
					$newNavData[] = $value;
				}
                if (!empty($nav['bgcolor'])) {
                    $background_color = $nav['bgcolor'];
                } else {
                    $background_color = '#2B2D30';
                }
				$storeNav.= '<div class="js-navmenu js-footer-auto-ele shop-nav nav-menu nav-menu-3 has-menu-'.(count($newNavData)-1).'" style="background-color:' . $background_color . ';">';
				foreach($newNavData as $key=>$value){
                    if($key > 0) {
                        $storeNav.= '<div class="nav-item"><a href="'.($value['url'] ? $value['url'] : 'javascript:;').'" style="background-image:url('.$value['image1'].');" title="'.$value['text'].'" data-mouseover="' . $value['image2'] . '" data-mouseout="' . $value['image1'] . '"></a></div>';
                    }
                    if (count($newNavData) == 1) {
                        $storeNav.= '<div class="nav-item"><a href="'.($value['url'] ? $value['url'] : 'javascript:;').'" style="background-image:url('.$value['image1'].');" title="'.$value['text'].'" data-mouseover="' . $value['image2'] . '" data-mouseout="' . $value['image1'] . '"></a></div>';
                    }
                    if(($key == 0 && count($newNavData) == 1) || ($key == 2 && count($newNavData) > 3) || ($key == 1 && count($newNavData) == 2) || ($key == 1 && count($newNavData) == 3)){
                        $storeNav.= '<div class="nav-special-item nav-item"><a href="'.($newNavData[0]['url'] ? $newNavData[0]['url'] : 'javascript:;').'" style="background-image:url('.$newNavData[0]['image1'].');border-color:#2B2D30;" title="'.$newNavData[0]['text'].'" data-mouseover="' . $newNavData[0]['image2'] . '" data-mouseout="' . $newNavData[0]['image1'] . '"></a></div>';
					}
				}
				$storeNav .= '</div>';
				break;
		}
		return $storeNav;
	}
}