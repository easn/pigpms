<?php

/*
 * 用户中心
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/12/29 10:25
 * 
 */

class UserAction extends BaseAction {

    public function index() {

        //搜索
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_user['uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'nickname') {
                $condition_user['nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'phone') {
                $condition_user['phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }

        $database_user = D('User');

        $count_user = $database_user->where($condition_user)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 15);
        $user_list = $database_user->field(true)->where($condition_user)->order('`uid` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        if (!empty($user_list)) {
            import('ORG.Net.IpLocation');
            $IpLocation = new IpLocation();
            foreach ($user_list as &$value) {
                $last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
                $value['last_ip_txt'] = iconv('GBK', 'UTF-8', $last_location['country']) . iconv('GBK', 'UTF-8', $last_location['area']);
            }
        }
        $this->assign('user_list', $user_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        $this->display();
    }

    public function edit() {
        $this->assign('bg_color', '#F3F3F3');

        $database_user = D('User');
        $condition_user['uid'] = intval($_GET['uid']);
        $now_user = $database_user->field(true)->where($condition_user)->find();
        if (empty($now_user)) {
            $this->frame_error_tips('没有找到该用户信息！');
        }
        $this->assign('now_user', $now_user);

        $this->display();
    }

    public function amend() {
        if (IS_POST) {
            $database_user = D('User');
            $condition_user['uid'] = intval($_POST['uid']);
            $now_user = $database_user->field(true)->where($condition_user)->find();
            if (empty($now_user)) {
                $this->error('没有找到该用户信息！');
            }
            
            if (empty($_POST['nickname'])) {
            	$this->error('昵称不能为空！');
            }
            
            $condition_user['uid'] = $now_user['uid'];
            $data_user['phone'] = $_POST['phone'];
            $data_user['nickname'] = $_POST['nickname'];
            if ($_POST['pwd']) {
                $data_user['password'] = md5($_POST['pwd']);
            }
            $data_user['status'] = $_POST['status'];
            $data_user['intro'] = $_POST['intro'];

            //检测修改的手机号 是否已经有用户
            if ($database_user->where("uid != '" . $now_user[uid] . "' and phone='" . $_POST['phone'] . "'")->find()) {
                $this->error("您提交的用户手机号 已被别的账号占用！请重新修改");
            } else {
                if ($database_user->where($condition_user)->data($data_user)->save()) {
                    $this->success('修改成功！');
                } else {
                    $this->error('修改失败！请重试。');
                }
            }
        } else {
            $this->error('非法访问！');
        }
    }

    //商家店铺
    public function stores() {
        $store = M('Store');
        $sale_category = M('SaleCategory');

        $uid = $this->_get('id');
        $tmp_stores = $store->field(array('store_id', 'uid', 'name', 'income', 'balance', 'unbalance', 'sale_category_id', 'status', "logo"))->where(array('uid' => $uid))->select();

        $stores = array();
        foreach ($tmp_stores as $store) {
            $category = $sale_category->where(array('cat_id' => $store['sale_category_id']))->getField('name');
            
            if (empty($store['logo'])) {
            	$store['logo'] = getAttachmentUrl('images/default_shop.png', false);
            } else {
            	$store['logo'] = getAttachmentUrl($store['logo']);
            }
            
            $stores[] = array(
                'store_id' => $store['store_id'],
                'uid' => $store['uid'],
                'name' => $store['name'],
                'logo' => $store['logo'],
                'sale_category' => $category,
                'income' => number_format($store['income'], 2, '.', ''),
                'balance' => number_format($store['balance'], 2, '.', ''),
                'unbalance' => number_format($store['unbalance'], 2, '.', ''),
                'status' => $store['status']
            );
        }

        $this->assign('stores', $stores);
        $this->display();
    }

    //切换店铺
    public function tab_store() {
        if (!$_SESSION['system']) {
            return;
        }

        $uid = $this->_get('uid', 'trim,intval');
        if (!$uid) {
            $this->error('传递参数有误！');
        }
        $where['uid'] = $uid;
        $user_info = M('User')->where($where)->find();
        if (!$user_info) {
            $this->error('该用户不存在!');
        }

        $_SESSION['user'] = $user_info;
        redirect('/user.php?c=store&a=select');
    }
    
    
    //导出
    public function checkout() {
		//统计用户数量
		if(IS_AJAX) {
			$searchtype = $_POST['searchtype'];
			$start_time = $_POST['start_time'];
			$end_time = $_POST['end_time'];
			
			$database_user = D('User');
			if(!in_array($searchtype,array('0','1','2'))) {
				
				$return = array('code'=>'100','msg'=>'筛选用户类别不正确');
				echo json_encode($return);exit;
			}
			switch($searchtype) {
				case '1':
					$condition_user['phone'] = array('gt', '0');
					break;
				
				case '2':
					$condition_user['openid'] = array('gt', '0');
			}
			
			if(!empty($start_time) && !empty($end_time)) {
				$starttime = strtotime($start_time);
				$endtime = strtotime($end_time);
				$condition_user['reg_time'] = array('between',array($starttime,$endtime));
			}
			
			if(IS_POST) {
				$count_user = $database_user->where($condition_user)->count();
				$a = $database_user->getLastsql();
				$return = array('code'=>'0','msg'=>$count_user,'mmm'=>$a);
				echo json_encode($return);exit;
			} elseif(IS_GET){
				$user_arr = $database_user->where($condition_user)->select();
				$this->_download_csv_byuser($user_arr);
			}	
		} 

		$this->display();
    }
    
  //download2
    public function download_csv_byuser() {
	
    	$searchtype = $_GET['searchtype'];
    	$start_time = $_GET['start_time'];
    	$end_time = $_GET['end_time'];
    	$condition_user = array();
    	if(!in_array($searchtype,array('0','1','2'))) {
    	
    		$return = array('code'=>'100','msg'=>'筛选用户类别不正确');
    		echo json_encode($return);exit;
    	}
    	switch($searchtype) {
    		case '1':
    			$condition_user['phone'] = array('gt', '0');
    			break;
    			 
    		case '2':
    			$condition_user['openid'] = array('gt', '0');
    			break;
    	
    	}
    	
    	if(!empty($start_time) && !empty($end_time)) {
    		$starttime = strtotime($start_time);
    		$endtime = strtotime($end_time);
    		$condition_user['reg_time'] = array('between',array($start_time,$end_time));
    	}
    	 
    	$user_arr = D('User')->where($condition_user)->select();

    	include 'source/class/execl.class.php';
    	$execl = new execl();
    	

    	//$array = array('用户uid','用户昵称','用户手机号','是否微信用户','注册ip','注册时间','最后登陆时间','店铺数量','登录次数');
    	//$execl->addHeader($array);
    	$filename = date("用户信息_YmdHis",time()).'.xls';
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( "Content-Disposition: attachment;filename=$filename" );	
		header ( 'Cache-Type: charset=gb2312');		
		echo "<style>table td{border:1px solid #ccc;}</style>";
		echo "<table>";
    	//dump($user_arr);
		echo '	<tr>';
		echo ' 		<th><b> 用户uid </b></th>';
		echo ' 		<th><b> 用户昵称 </b></th>';
		echo ' 		<th><b> 用户手机号 </b></th>';
		echo ' 		<th><b> 是否微信用户 </b></th>';
		echo ' 		<th><b> 注册ip </b></th>';
		echo ' 		<th><b> 注册时间 </b></th>';
		echo ' 		<th><b> 最后登陆时间 </b></th>';
		echo ' 		<th><b> 店铺数量 </b></th>';
		echo ' 		<th><b> 登录次数 </b></th>';
		echo '	</tr>';
		
    	foreach ($user_arr as $k => $v) {
			echo '	<tr>';
			echo ' 		<td align="center">' . $v['uid'] . '</td>';
			echo ' 		<td align="center">' . $v['nickname'] . '</td>';
			echo ' 		<td align="center">' . $v['phone'] . '</td>';
			if($v['is_weixin']=='1') {$is_weixin = "是";}else {$is_weixin = "否";}
			echo ' 		<td align="center">' . $is_weixin. '</td>';
			echo ' 		<td align="center">' . long2ip($v['reg_ip']) . '</td>';
			echo ' 		<td align="center">' . date("Y-m-d H:i:s",$v['reg_time']) . '</td>';
			echo ' 		<td align="center">' . date("Y-m-d H:i:s",$v['last_time']) . '</td>';
			echo ' 		<td align="center">' . $v['stores'] . '</td>';
			echo ' 		<td align="center">' . $v['login_count'] . '</td>';
			echo '	</tr>';
    	}
    	echo '</table>';
    }

}