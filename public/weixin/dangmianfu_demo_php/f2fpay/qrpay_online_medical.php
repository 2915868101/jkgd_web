<?php 
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
require_once 'model/builder/AlipayTradePrecreateContentBuilder.php';
require_once 'service/AlipayTradeService.php';
require_once '../../wxpay/example/log.php';
$logHandler= new CLogFileHandler("../../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

if (!empty($_POST['money'])&& trim($_POST['money'])!=""){
$title = $_POST['title'];
$money = $_POST['money'];
$orgId = $_POST['orgId'];
$outTradeNo =null;
// (必填) 商户网站订单系统中唯一订单号，64个字符以内，只能包含字母、数字、下划线， 需保证商户系统端不能重复，建议通过数据库sequence生成
if(!empty($_POST["trade"])){
	 $outTradeNo =$_POST["trade"];
}else{
	 $outTradeNo = "qrpay".date('Ymdhis').mt_rand(100,1000);
}
Log::DEBUG($outTradeNo."     ************支付宝统一支付平台下单: 金额". $_POST['money']."    类别：".$_POST['title']."    机构ID：".$_POST['orgId']);
$subject = $title;
$totalAmount = $money;
Log::DEBUG($outTradeNo."     金额". $_POST['money']."    类别：".$_POST['title']);
$timeExpress = "5m"; // 支付超时，线下扫码交易定义为5分钟
$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
$qrPayRequestBuilder->setOutTradeNo($outTradeNo);
$qrPayRequestBuilder->setTotalAmount($totalAmount);
$qrPayRequestBuilder->setTimeExpress($timeExpress);
$qrPayRequestBuilder->setSubject($subject);
$qrPayRequestBuilder->setBody($orgId);
$qrPayRequestBuilder->setOperatorId($orgId);
$qrPayRequestBuilder->setNotifyUrl("http://www.hnwisdom.com/BMFW/dist/public/weixin/dangmianfu_demo_php/notify_url_online_medical.php");
$qrPay = new AlipayTradeService($config);
$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);
switch ($qrPayResult->getTradeStatus()){
    case "SUCCESS":
        $response = $qrPayResult->getResponse();
		Log::DEBUG($outTradeNo."  ".json_encode($response));
        echo json_encode($response,true);    
        break;
    case "FAILED":
       if(!empty($qrPayResult->getResponse())){
       	$response=$qrPayResult->getResponse();
       	Log::DEBUG($outTradeNo."  ".json_encode($response));
       	echo json_encode($response,true);       
       }else{
       	Log::DEBUG($outTradeNo."  FAILED");
       	$back='{"code":"-1","msg":"FAILED","out_trade_no":"'.$outTradeNo.'"}';
           echo $back;
       }
              
       break;
    case "UNKNOWN":
        if(!empty($qrPayResult->getResponse())){
       	$response=$qrPayResult->getResponse();
       	Log::DEBUG($outTradeNo."  ".json_encode($response));
       	echo json_encode($response,true);    
       }else{
       	Log::DEBUG($outTradeNo."  UNKNOWN");
       	$back='{"code":"-1","msg":"FAILED","out_trade_no":"'.$outTradeNo.'"}';
           echo $back;
       }
       
       break;
    default:
       $back='{"code":"-1","msg":"不支持的返回状态，创建订单二维码返回异常!!!","out_trade_no":"'.$outTradeNo.'"}';
       echo $back;
       Log::DEBUG($outTradeNo."  不支持的返回状态，创建订单二维码返回异常!!!");
       break;
}
	
}

?>
