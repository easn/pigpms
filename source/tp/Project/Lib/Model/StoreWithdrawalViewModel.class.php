<?php
/**
 * 提现记录数据视图
 * User: pigcms_21
 * Date: 2015/3/19
 * Time: 21:18
 */

class StoreWithdrawalViewModel extends ViewModel
{
    protected $viewFields = array(
        'StoreWithdrawal' => array('*'),
        'Store' => array('name' => 'store', 'tel' => 'mobile', 'balance', '_on' => 'StoreWithdrawal.store_id = Store.store_id'),
        'User' => array('nickname', 'phone' => 'tel', '_on' => 'StoreWithdrawal.uid = User.uid'),
        'Bank' => array('name' => 'bank', '_on' => 'StoreWithdrawal.bank_id = Bank.bank_id')
    );

    //提现记录状态
    public function getWithdrawalStatus()
    {
        return array(
            '1' => '申请中',
            '2' => '银行处理中',
            '3' => '提现成功',
            '4' => '提现失败'
        );
    }
} 