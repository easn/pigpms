<?php

class user_collect_model extends base_model {

    /**
     * 增加收藏功能  这功能并不做判断是否有收藏
     * param $uid
     * param $id
     * param $type
     */
    public function add($uid, $id, $type, $store_id = 0) {
        if (!in_array($type, array(1, 2))) {
            return false;
        }

        $param = array();
        $param['user_id'] = $uid;
        $param['dataid'] = $id;
        $param['add_time'] = time();
        $param['type'] = $type;
        $param['store_id'] = $store_id;

        $result = D('User_collect')->data($param)->add();

        if ($result) {
            if ($type == 1) {
                $result = D('Product')->where(array('product_id' => $id))->setInc('collect');
            } else {
                $result = D('Store')->where(array('store_id' => $id))->setInc('collect');
            }

            if ($result) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * 取消收藏功能  这功能并不做判断是否有收藏
     * param $uid
     * param $id
     * param $type
     */
    public function cancel($uid, $id, $type, $store_id = 0) {
        if (!in_array($type, array(1, 2))) {
            return false;
        }

        $result = D('User_collect')->where(array('user_id' => $uid, 'dataid' => $id, 'type' => $type, 'store_id' => $store_id))->delete();

        if ($result) {
            if ($type == 1) {
                $result = D('Product')->where(array('product_id' => $id))->setInc('collect', -1);
            } else {
                $result = D('Store')->where(array('store_id' => $id))->setInc('collect', -1);
            }

            if ($result) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

}

?>