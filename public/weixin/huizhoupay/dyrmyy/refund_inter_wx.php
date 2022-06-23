<?php
header('Content-Type:text/html; charset=utf-8;');
header("Access-Control-Allow-Origin: *");
ini_set('date.timezone','Asia/Shanghai');
ini_set('default_socket_timeout', -1); //不超时
error_reporting(E_ERROR);
require_once "libdyrmyy/WxPay.Api.php";
require_once '../../wxpay/example/log.php';

//初始化日志
$logHandler= new CLogFileHandler("../../logs/backlog/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
$URL="http://113.96.111.2:8089/EasyOnlineService/services/hsWebService?wsdl";  //全局URL
$ip = $_SERVER["REMOTE_ADDR"];//获取访问者ip 
Log::DEBUG("                               ");
Log::DEBUG(" ^^^^^^^^^^^^^^^^^^^^^^^^ weixin pay  refund by  interface ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^             ");
Log::DEBUG("请求ip：".$ip);
//if($_SERVER['REMOTE_ADDR'] != '193.112.122.99') {
    //exit; //不是指定ip，直接退出
//}

//从post中得到商户订单号并去掉前后引号
$trade=$_GET['TRADE'];
$money=$_GET['MONEY'];
$order_fee=$_GET['TOTAL_FEE'];

if(!empty($_POST['REFUND_TRADE'])){
	$Out_refund_no=$_POST['REFUND_TRADE'];
}else{
	$Out_refund_no=WxPayConfig::MCHID.date("YmdHis").mt_rand(100,10000000).mt_rand();
}	

$wxmoney=floatval($money) * 100;//退款金额
$Totalfee=floatval($order_fee) * 100;//订单金额

Log::DEBUG("商户退款单号 ：       ".$Out_refund_no."   商户订单号    ".$trade."   订单金额".$order_fee."   退款金额".$money);
    $input = new WxPayRefund();
	$input->SetOut_trade_no($trade);
	$input->SetTotal_fee($Totalfee);
	$input->SetRefund_fee($wxmoney);
    $input->SetOut_refund_no($Out_refund_no);
    $input->SetOp_user_id(WxPayConfig::MCHID);
	try{
	    $result=WxPayApi::refund($input);	
		Log::DEBUG(json_encode($result,JSON_UNESCAPED_UNICODE));//记录每一次请求返回结果
		if($result["return_code"]=="SUCCESS"){
			if($result["result_code"]=="SUCCESS"){
				Log::DEBUG($trade."  ".$Out_refund_no."  退费成功,退费金额：".$money."元");//记录退费成功请求返回结果
				SaveRefundinfo($trade,$money,$Out_refund_no,$order_fee);
				$msg=$result["return_msg"];//通信成功标识	
				$code=$result["result_code"];
			}else{
				$code=$result["result_code"];
				$msg=$result["err_code_des"];//失败描述
				$FIRSTVALUE="管理员，微信退费，退费失败1，失败原因为：".$msg;
				$LASTVALUE="退款单号：".$Out_refund_no.",请确认相关退款情况";
				$KEYWORD='[{"VALUE":"总金额 '.$order_fee.'元","COLOR":"#173177"},{"VALUE":"退款金额  '.$money.'元","COLOR":"#173177"},{"VALUE":"'.$trade.'","COLOR":"#173177"}]';
				AttentionToGLY($trade,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
				AttentionToGLY($trade,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
			}
		}else{
			$code=$result["return_code"];//失败描述
			$msg=$result["return_msg"];//失败描述
			$FIRSTVALUE="管理员，微信退费，退费失败2，失败原因为：".$msg;
			$LASTVALUE="退款单号：".$Out_refund_no.",请确认相关退款情况";
			$KEYWORD='[{"VALUE":"总金额 '.$order_fee.'元","COLOR":"#173177"},{"VALUE":"退款金额  '.$money.'元","COLOR":"#173177"},{"VALUE":"'.$trade.'","COLOR":"#173177"}]';
			AttentionToGLY($trade,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
			AttentionToGLY($trade,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
		} 
		$back='{"OPERTYPE":"A","SERVICENO":"WX001","HOSPITALNO":"123123123","RESULT_CODE":"'.$code.'","RETURN_MSG":"'.$msg.'"}';
		echo $back;
	}catch(Exception $e){
		Log::DEBUG($trade."  ".$Out_refund_no." 微信退费异常 Exception : " . $e->getMessage());
		$FIRSTVALUE="管理员，微信退费，退费异常，异常原因为：网络故障，退费失败，请稍后再试";
		$LASTVALUE="退款单号：".$Out_refund_no.",请确认相关退款情况";
		$KEYWORD='[{"VALUE":"总金额 '.$order_fee.'元","COLOR":"#173177"},{"VALUE":"退款金额  '.$money.'元","COLOR":"#173177"},{"VALUE":"'.$trade.'","COLOR":"#173177"}]';
		AttentionToGLY($trade,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
		AttentionToGLY($trade,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);	
		echo '{"OPERTYPE":"A","SERVICENO":"WX001","HOSPITALNO":"123123123","RESULT_CODE":"FAIL","RETURN_MSG":"网络故障，退费失败，请使用相同退款订单号稍后重试"}';				
	}
		
	//保存退款信息
	 function SaveRefundinfo($trade,$refund_fee,$refund_trade,$total_fee){
		try{
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			$paramOrg = "serviceId{=}GD00006{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			$dataOrg = "<?xml version='1.0' encoding='UTF-8'?>
				        <WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN>
				        <TRADE>".$trade."</TRADE>
				        <REFUND_TRADE>".$refund_trade."</REFUND_TRADE>
				        <REFUND_FEE>".$refund_fee."</REFUND_FEE>
				        <TOTAL_FEE>".$total_fee."</TOTAL_FEE>
				        <TYPE>0</TYPE>
				        </BEAN></DATA></WSD>";
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));			
			$data = $result->return; 
			Log::DEBUG($trade."  ".$refund_trade."   WX_REFUND_RECORD：保存退费日志表 : " . $data);
			$queryZh = new SimpleXMLElement($data);
			$JD = $queryZh->DATA;
			Log::DEBUG($trade."  ".$refund_trade."   WX_REFUND_RECORD：是否保存成功 : " . $JD);			
		}catch(Exception $e){
			Log::DEBUG($trade."  ".$refund_trade."   WX_REFUND_RECORD：保存退费日志 Exception : " . $e->getMessage());
		}
	}
	
	//缴费异常提醒  管理员
	//mess  不为空发送短信
	//temp  不为空发送模板消息
	function AttentionToGLY($Trade,$arr,$openid,$phone,$MESS,$User,$FIRSTVALUE,$LASTVALUE,$KEYWORD){
		$temp=null;
		if(empty($MESS)){//如果为空  则发送模板消息
			$temp='BTnwZj7L0peX0m3ijD43j2DQEArAfJ9LHv-LMrhQrLg';
		}
		if($User=="admin"){
			$FIRSTCOLOR="#f509f9";
			$LASTCOLOR="#FF0000";		
		}else{
			$FIRSTCOLOR="#FF0000";
			$LASTCOLOR="";
			$openid=$arr["OPENID"];
			$PHONE=$arr["PHONE"];
		}
		try{
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			$paramOrg = "serviceId{=}GD00001{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			$dataOrg = "<?xml version='1.0' encoding='UTF-8'?>
				        <WSD><MESSAGE><VERSION>1</VERSION></MESSAGE>
				        <DATA><BEAN>
				        <TEMPLATE>".$temp."</TEMPLATE>	
				        <FIRSTVALUE>".$FIRSTVALUE."</FIRSTVALUE>			
				        <FIRSTCOLOR>".$FIRSTCOLOR."</FIRSTCOLOR>
				        <LASTVALUE>".$LASTVALUE."</LASTVALUE>	
				        <LASTCOLOR>".$LASTCOLOR."</LASTCOLOR>	
				        <KEYWORD>".$KEYWORD."</KEYWORD>
				        <OPENID>".$openid."</OPENID>	
				        <PHONE>".$PHONE."</PHONE>
				        <MESSAGE>".$MESS."</MESSAGE>		       	
				        </BEAN></DATA></WSD>";
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));
			$data = $result->return; 
			$queryZh = new SimpleXMLElement($data);
			$JD = $queryZh->DATA;
			Log::DEBUG($Trade."  缴费异常推送结果 :".$JD);				
		}catch(Exception $e){
			Log::DEBUG($Trade."  缴费异常推送异常 Exception : " . $e->getMessage());
		}
	}
	
	
	
	
	
?>
