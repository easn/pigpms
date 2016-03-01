<?php

require_once dirname(__FILE__) . '/global.php';
$payMethodList = M('Config')->get_pay_method();
import('source.class.pay.Alipay');
//$dir = './';
//file_put_contents($dir . 'error.txt', 'alipay_callbck$_REQUEST:' . json_encode($_REQUEST) . PHP_EOL, FILE_APPEND);
//file_put_contents($dir . 'error.txt', 'alipay_callbck$_GET:' . json_encode($_GET) . PHP_EOL, FILE_APPEND);
//$_GET['notify_data'] = $_GET;
//$_GET['service'] = 'alipay.wap.trade.create.direct';
//$_GET['v'] = '2.0';
//$_GET['sec_id'] = 'MD5';
//unset($_GET['out_trade_no'], $_GET['request_token'], $_GET['result'], $_GET['result'], $_GET['trade_no'], $_GET['sign'], $_GET['sign_type']);

$payClass = new Alipay(array(), $payMethodList['alipay']['config'], $wap_user);
$payInfo = $payClass->call_back_url();
?>
