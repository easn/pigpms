<?php

class Alipay {

    protected $order_info;
    protected $pay_config;
    protected $user_info;
    protected $token;

    public function __construct($order_info, $pay_config, $user_info) {
        $this->order_info = $order_info;
        //$this->pay_config = $pay_config;
        $this->user_info = $user_info;
        $this->pay_config = array(
            'partner' => $pay_config['pay_alipay_pid'],
            'seller_email' => $pay_config['pay_alipay_name'],
            'key' => $pay_config['pay_alipay_key'],
            'private_key_path' => 'key/rsa_private_key.pem',
            'ali_public_key_path' => 'key/alipay_public_key.pem',
            'sign_type' => 'MD5', //0001
            'input_charset' => 'utf-8',
            'cacert' => '/wap/cacert.pem',
            'transport' => 'http',
        );
    }

    public function pay() {
        require('Alipay/alipay_submit.class.php');

        /*         * ************************请求参数************************* */
        $format = "xml";

        //返回格式
        $v = "2.0";
        //请求号
        $req_id = date('Ymdhis');

        //支付类型
        $payment_type = "1";
        $notify_url = option('config.wap_site_url') . '/paynotice.php';
        $return_url = option('config.wap_site_url') . '/alipay_callback.php';
        $out_trade_no = $this->order_info['trade_no'];
        $subject = $this->order_info['order_no'] . '_alipay';
        $total_fee = $this->order_info['total'];


        //$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $return_url . '</call_back_url><seller_account_name>' . trim($this->pay_config['seller_email']) . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
        $req_data = '<direct_trade_create_req><subject>' . $subject . '</subject><call_back_url>' . $return_url . '</call_back_url><out_trade_no>' . $out_trade_no . '</out_trade_no><total_fee>' . $total_fee . '</total_fee><seller_account_name>' . trim($this->pay_config['seller_email']) . '</seller_account_name><notify_url>' . $notify_url . '</notify_url></direct_trade_create_req>';

        $para_token = array(
            "service" => "alipay.wap.trade.create.direct",
            "partner" => trim($this->pay_config['partner']),
            "sec_id" => 'MD5',
            "format" => $format,
            "v" => $v,
            "req_id" => $req_id,
            "req_data" => $req_data,
            "_input_charset" => trim(strtolower($this->pay_config['input_charset']))
        );
     //   $dir = './';
     //   file_put_contents($dir . 'error_1.txt', 'alipay_notify POST1:' . json_encode($para_token) . PHP_EOL, FILE_APPEND);

//建立请求
        //$alipaySubmit = new AlipaySubmit($alipay_config);
        //$html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");
        //建立请求

        $alipaySubmit = new AlipaySubmit($this->pay_config);

        $html_text = $alipaySubmit->buildRequestHttp($para_token);

        //URLDECODE返回的信息
        $html_text = urldecode($html_text);
       // file_put_contents($dir . 'error_1.txt', 'alipay_notify $html_text:' . json_encode($html_text) . PHP_EOL, FILE_APPEND);

        //解析远程模拟提交后返回的信息
        $para_html_text = $alipaySubmit->parseResponse($html_text);

        //获取request_token
        $request_token = $para_html_text['request_token'];
        $this->token = $request_token;

        /*         * ************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute************************* */

        //业务详细
        //$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
        $req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
        //必填
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.wap.auth.authAndExecute",
            "partner" => trim($this->pay_config['partner']),
            "sec_id" => 'MD5',
            "format" => $format,
            "v" => $v,
            "req_id" => $req_id,
            "req_data" => $req_data,
            "_input_charset" => 'utf-8'
        );
        //error_log(json_encode($parameter,true));
        //建立请求
        $alipaySubmit = new AlipaySubmit($this->pay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
       // file_put_contents('./a.txt', $html_text);

        header("Content-type: text/html; charset=utf-8");
        echo $html_text;
    }

    public function notice() {
        require_once("Alipay/alipay_notify.class.php");
        //$_POST['notify_data'] = htmlspecialchars_decode($_POST['notify_data']);
        //$from = htmlentities($_GET['from']);
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->pay_config);
       // $dir = './';
      //  file_put_contents($dir . 'error_1.txt', '$alipayNotify' . json_encode($alipayNotify) . PHP_EOL, FILE_APPEND);
       // file_put_contents($dir . 'error_1.txt', '$alipayNotify $_REQUEST' . json_encode($_REQUEST) . PHP_EOL, FILE_APPEND);
       // file_put_contents($dir . 'error_1.txt', '$alipayNotify $_POST' . json_encode($alipayNotify) . PHP_EOL, FILE_APPEND);
        $verify_result = $alipayNotify->verifyNotify();


        if ($verify_result) {//验证成功
            //请在这里加上商户的业务逻辑程序代
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //解析notify_data
            //注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
            $doc = new DOMDocument();
            if ($this->pay_config['sign_type'] == 'MD5') {
                $doc->loadXML($_POST['notify_data']);
            }

            if ($this->pay_config['sign_type'] == '0001') {
                $doc->loadXML($alipayNotify->decrypt($_POST['notify_data']));
            }

            if (!empty($doc->getElementsByTagName("notify")->item(0)->nodeValue)) {
                //商户订单号
                $out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
                //支付宝交易号
                $trade_no = $doc->getElementsByTagName("trade_no")->item(0)->nodeValue;
                //交易状态
                $trade_status = $doc->getElementsByTagName("trade_status")->item(0)->nodeValue;

                if ($trade_status == 'TRADE_FINISHED') {

                    echo "success";  //请不要修改或删除
                } else if ($trade_status == 'TRADE_SUCCESS') {
                    /* $payHandel = new payHandle($this->token, $from, 'alipay');
                      $orderInfo = $payHandel->beforePay($out_trade_no);
                      file_put_contents($dir . 'error.txt', '$orderInfo:' . json_encode($orderInfo) . PHP_EOL, FILE_APPEND);
                      if (empty($orderInfo['paid'])) {
                      $orderInfo = $payHandel->afterPay($out_trade_no, $trade_no);
                      $url = C('site_url') . '/index.php?g=Wap&m=' . $from . '&a=payReturn&token=' . $orderInfo['token'] . '&wecha_id=' . $orderInfo['wecha_id'] . '&rget=1&orderid=' . $out_trade_no;
                      file_get_contents($url);
                      } */

                    //$order_param['trade_no'] = str_replace('PIG', '', $out_trade_no);
                    $order_param['trade_no'] = str_replace(option('config.orderid_prefix'), '', $out_trade_no);
                    $order_param['pay_type'] = 'alipay';
                    $order_param['third_id'] = $trade_no;
                    $order_param['pay_money'] = $doc->getElementsByTagName("price")->item(0)->nodeValue;
                    $order_param['third_data'] = $_POST;
                    return array('err_code' => 0, 'order_param' => $order_param);
                    //echo "success";  //请不要修改或删除
                }
            }
        } else {
            //验证失败
            //echo "fail";
            exit('fail');
        }
    }

    public function call_back_url() {
        //$dir = './';
        //file_put_contents($dir . 'error_1.txt', 'call_back_urlcall_back_urlcall_back_url' . json_encode($_GET) . PHP_EOL, FILE_APPEND);
        require_once("Alipay/alipay_notify.class.php");
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->pay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if ($verify_result) {//验证成功
            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];

            //支付宝交易号
            $trade_no = $_GET['trade_no'];

            //交易状态
            $result = $_GET['result'];


            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            if ($result == 'success') {
                $where['trade_no'] = $out_trade_no;
                $order_info = D('Order')->where($where)->find();
                //$dir = './';
                //file_put_contents($dir . 'error_1.txt', '$order_info' . json_encode(D('Order')->last_sql) . PHP_EOL, FILE_APPEND);
                //$url = option('config.wap_site_url') . '/paycallback.php?orderno=PIG' . $order_info['order_no'];
                $url = option('config.wap_site_url') . '/paycallback.php?orderno=' .option('config.orderid_prefix'). $order_info['order_no'];
                echo '<script type="text/javascript">if (window.parent.breakIframe) {window.parent.breakIframe("' . $url . '");}else{window.location.href="' . $url . '"}</script>';
            } else {
                exit('付款失败');
            }
        } else {
            //验证失败
            echo "验证失败";
            exit;
        }
    }

}

?>
