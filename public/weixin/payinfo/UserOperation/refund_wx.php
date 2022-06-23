<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信支付样例-退款</title>
</head>
<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../../wxpay/lib/WxPay.Api.php";
require_once '../../wxpay/example/log.php';

//初始化日志
$logHandler= new CLogFileHandler("../../logs/backlog/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);


function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#f00;'>$key</font> : $value <br/>";
    }
}

Log::DEBUG(" ^^^^^^^^^^^^^^^^^^^^^^^^ weixin pay  refund by  browser ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^             ");

//保存退款信息
function SaveRefundinfo($trade,$TRANSACTION_ID,$refund_fee,$refund_trade,$total_fee){
		try{
			libxml_disable_entity_loader(false);
			$client = new SoapClient("http://113.246.243.65:9998/EasyOnlineService/services/hsWebService?wsdl");
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			$paramOrg = "serviceId{=}GD00006{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			$dataOrg = "<?xml version='1.0' encoding='UTF-8'?>
				        <WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN>
				        <TRADE>".$trade."</TRADE>
				        <TRANSACTION_ID>".$TRANSACTION_ID."</TRANSACTION_ID>				        
				        <REFUND_TRADE>".$refund_trade."</REFUND_TRADE>
				        <REFUND_FEE>".($refund_fee/100)."</REFUND_FEE>
				        <TOTAL_FEE>".($total_fee/100)."</TOTAL_FEE>
				        <TYPE>0</TYPE>
				        </BEAN></DATA></WSD>";
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));			
			$data = $result->return; 
			Log::DEBUG($trade."  ".$TRANSACTION_ID."   ".$refund_trade."   WX_REFUND_RECORD：保存退费日志表 : " . $data);
			$queryZh = new SimpleXMLElement($data);
			$JD = $queryZh->DATA;
			Log::DEBUG($trade."  ".$TRANSACTION_ID."   ".$refund_trade."   WX_REFUND_RECORD：是否保存成功 : " . $JD);			
		}catch(Exception $e){
			Log::DEBUG($trade."  ".$TRANSACTION_ID."   ".$refund_trade."   WX_REFUND_RECORD：保存退费日志 Exception : " . $e->getMessage());
		}
	}

if(isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""){
	Log::DEBUG("                            ");
	Log::DEBUG("交易号退款  交易号为：               ".$_REQUEST["transaction_id"]);
	$transaction_id = $_REQUEST["transaction_id"];
	$total_fee = $_REQUEST["total_fee"];
	$refund_fee = $_REQUEST["refund_fee"];
	$Out_refund_no=WxPayConfig::MCHID.date("YmdHis").rand(999,10000);
	Log::DEBUG("商户退款单号 ：       ".$Out_refund_no."   交易号    ".$transaction_id."   订单金额".($total_fee/100)."   退款金额".($refund_fee/100));
	$input = new WxPayRefund();
	$input->SetTransaction_id($transaction_id);
	$input->SetTotal_fee($total_fee);
	$input->SetRefund_fee($refund_fee);
    $input->SetOut_refund_no($Out_refund_no);
    $input->SetOp_user_id(WxPayConfig::MCHID);
    $result=WxPayApi::refund($input);
    Log::DEBUG(json_encode($result,JSON_UNESCAPED_UNICODE));//记录每一次请求返回结果
    if($result["return_code"]=="SUCCESS"){
		if($result["result_code"]=="SUCCESS"){
			Log::DEBUG($trade."  ".$Out_refund_no."  退费成功,退费金额：".($refund_fee/100)."元");//记录退费成功请求返回结果
			SaveRefundinfo(null,$transaction_id,$refund_fee,$Out_refund_no,$total_fee);
		}
	}
	printf_info($result);
	exit();
}


if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""){
	Log::DEBUG("                            ");
	Log::DEBUG("商户订单号退款  订单号为：                ".$_REQUEST["out_trade_no"]);
	$out_trade_no = $_REQUEST["out_trade_no"];
	$total_fee = $_REQUEST["total_fee"];
	$refund_fee = $_REQUEST["refund_fee"];
	$Out_refund_no=WxPayConfig::MCHID.date("YmdHis").rand(999,10000);
	Log::DEBUG("商户退款单号 ：       ".$Out_refund_no."   商户订单号    ".$out_trade_no."   订单金额".($total_fee/100)."   退款金额".($refund_fee/100));
	$input = new WxPayRefund();
	$input->SetOut_trade_no($out_trade_no);
	$input->SetTotal_fee($total_fee);
	$input->SetRefund_fee($refund_fee);
    $input->SetOut_refund_no($Out_refund_no);
    $input->SetOp_user_id(WxPayConfig::MCHID);
    $result=WxPayApi::refund($input);
    Log::DEBUG(json_encode($result,JSON_UNESCAPED_UNICODE));//记录每一次请求返回结果
    if($result["return_code"]=="SUCCESS"){
		if($result["result_code"]=="SUCCESS"){
			Log::DEBUG($trade."  ".$Out_refund_no."  退费成功,退费金额：".($refund_fee/100)."元");//记录退费成功请求返回结果
			SaveRefundinfo($out_trade_no,null,$refund_fee,$Out_refund_no,$total_fee);
		}
	}
	printf_info($result);
	exit();
}
?>
<body>  
	<form action="#" method="post">
        <div style="margin-left:2%;color:#f00">微信订单号和商户订单号选少填一个，微信订单号优先：</div><br/>
        <div style="margin-left:2%;">微信订单号：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="transaction_id" /><br /><br />
        <div style="margin-left:2%;">商户订单号：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="out_trade_no" /><br /><br />
        <div style="margin-left:2%;">订单总金额(分)：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="total_fee" /><br /><br />
        <div style="margin-left:2%;">退款金额(分)：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="refund_fee" /><br /><br />
		<div align="center">
			<input type="submit" value="提交退款" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" />
		</div>
	</form>
</body>
</html>