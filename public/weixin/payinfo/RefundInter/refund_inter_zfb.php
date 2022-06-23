<?php 
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
ini_set('date.timezone','Asia/Shanghai');
ini_set('default_socket_timeout', -1); //不超时
error_reporting(E_ERROR);
require_once '../../dangmianfu_demo_php/f2fpay/model/builder/AlipayTradeRefundContentBuilder.php';
require_once '../../dangmianfu_demo_php/f2fpay/service/AlipayTradeService.php';
require_once '../../wxpay/example/log.php';
$logHandler= new CLogFileHandler("../../logs/backlog/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
$URL="http://113.246.243.65:9998/EasyOnlineService/services/hsWebService?wsdl";  //全局URL
Log::DEBUG("                               ");
Log::DEBUG("-----------------------------------Alipay  refund   by  interface---------------------------------");
$arr=$_POST;
Log::DEBUG("  退款入参   ".json_encode($arr));
Log::DEBUG("支付宝退款     请求ip：".$_SERVER["REMOTE_ADDR"]);
if (!empty($_POST['TRADE'])&& trim($_POST['TRADE'])!=""){
	
	$out_trade_no = trim($_POST['TRADE']);
	$refund_amount = trim($_POST['MONEY']);
	$order_fee= trim($_POST['TOTAL_FEE']);
	
	if(!empty($_POST['REFUND_TRADE'])){
		$out_request_no=$_POST['REFUND_TRADE'];
	}else{
		$out_request_no = "alirefund".date('Ymdhis').mt_rand(100,10000000).mt_rand();
	}	
	
	//第三方应用授权令牌,商户授权系统商开发模式下使用
	$appAuthToken = "";//根据真实值填写
	
	//创建退款请求builder,设置参数
	$refundRequestBuilder = new AlipayTradeRefundContentBuilder();
	$refundRequestBuilder->setOutTradeNo($out_trade_no);
	$refundRequestBuilder->setRefundAmount($refund_amount);
	$refundRequestBuilder->setOutRequestNo($out_request_no);
	$refundRequestBuilder->setAppAuthToken($appAuthToken);

	//初始化类对象,调用refund获取退款应答
	$refundResponse = new AlipayTradeService($config);
	Log::DEBUG("请求退款批次号 ：       ".$out_request_no."   商户订单号    ".$out_trade_no."   退款金额".$refund_amount." 总金额:".$order_fee);
	$refundResult =	$refundResponse->refund($refundRequestBuilder);

	//根据交易状态进行处理
	switch ($refundResult->getTradeStatus()){
		case "SUCCESS":
		    Log::DEBUG($out_request_no."   SUCCESS ".json_encode($refundResult->getResponse()));
		    SaveRefundinfo($out_trade_no,$refund_amount,$out_request_no,$order_fee);
		    Log::DEBUG($out_request_no."   支付宝退款成功,商户订单号    ".$out_trade_no."   退款金额".$refund_amount);
			Log::DEBUG($out_request_no."   支付宝退款成功".json_encode($refundResult->getResponse(),true));
			$back='{"OPERTYPE":"A","SERVICENO":"WX001","HOSPITALNO":"123123123","RESULT_CODE":"SUCCESS","RETURN_MSG":"'.$refundResult->getResponse()->msg.'"}';
	        echo $back;	
			break;
		case "FAILED":
		    Log::DEBUG($out_request_no."  FAILED 支付宝退款失败 ：   ".json_encode($refundResult->getResponse()));
			if(!empty($refundResult->getResponse())){
			  Log::DEBUG($out_request_no." 失败原因 ：   ".$refundResult->getResponse()->sub_msg);
			  $back='{"OPERTYPE":"A","SERVICENO":"WX001","HOSPITALNO":"123123123","RESULT_CODE":"FAIL","RETURN_MSG":"'.$refundResult->getResponse()->sub_msg.'"}';
	          
	          $FIRSTVALUE="管理员，支付宝退费，退费失败，失败原因为：".$refundResult->getResponse()->sub_msg;
	          $LASTVALUE="退款单号：".$out_request_no.",请确认相关退款情况";
	          $KEYWORD='[{"VALUE":"总金额 '.$order_fee.'元","COLOR":"#173177"},{"VALUE":"退款金额  '.$refund_amount.'元","COLOR":"#173177"},{"VALUE":"'.$out_trade_no.'","COLOR":"#173177"}]';
	         // AttentionToGLY($out_trade_no,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	         // AttentionToGLY($out_trade_no,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	         
	          echo $back;
			  //echo json_encode($refundResult->getResponse(),true);  
			}else{
			 $back='{"OPERTYPE":"A","SERVICENO":"WX001","HOSPITALNO":"123123123","RESULT_CODE":"FAIL","RETURN_MSG":"FAILED，支付宝退款失败"}';
	         
	          $FIRSTVALUE="管理员，支付宝退费，退费失败，失败原因为：FAILED";
	          $LASTVALUE="退款单号：".$out_request_no.",请确认相关退款情况";
	          $KEYWORD='[{"VALUE":"总金额 '.$order_fee.'元","COLOR":"#173177"},{"VALUE":"退款金额  '.$refund_amount.'元","COLOR":"#173177"},{"VALUE":"'.$out_trade_no.'","COLOR":"#173177"}]';
	         // AttentionToGLY($out_trade_no,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	         //AttentionToGLY($out_trade_no,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	
	         
	         echo $back;
			}
			break;
		case "UNKNOWN":
		    Log::DEBUG($out_request_no."   UNKNOWN ".json_encode($refundResult->getResponse()));
		    Log::DEBUG($out_request_no."   UNKNOWN 支付宝退款失败 ：     系统异常，订单状态未知!!  ");
			if(!empty($refundResult->getResponse())){
			  $back='{"OPERTYPE":"A","SERVICENO":"WX001","HOSPITALNO":"123123123","RESULT_CODE":"FAIL","RETURN_MSG":"'.$refundResult->getResponse()->msg.'"}';
	          
	          $FIRSTVALUE="管理员，支付宝退费，退费失败，失败原因为：".$refundResult->getResponse()->msg;
	          $LASTVALUE="退款单号：".$out_request_no.",请确认相关退款情况";
	          $KEYWORD='[{"VALUE":"总金额 '.$order_fee.'","COLOR":"#173177"},{"VALUE":"退款金额  '.$refund_amount.'","COLOR":"#173177"},{"VALUE":"'.$out_trade_no.'","COLOR":"#173177"}]';
	          //AttentionToGLY($out_trade_no,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	          //AttentionToGLY($out_trade_no,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	
	          
	          echo $back;
		      //echo json_encode($refundResult->getResponse(),true);       		
			}else{
			  $back='{"OPERTYPE":"A","SERVICENO":"WX001","HOSPITALNO":"123123123","RESULT_CODE":"FAIL","RETURN_MSG":"系统异常，订单状态未知!!"}';
	           
	          $FIRSTVALUE="管理员，支付宝退费，退费失败，失败原因为：UNKNOWN";
	          $LASTVALUE="退款单号：".$out_request_no.",请确认相关退款情况";
	          $KEYWORD='[{"VALUE":"总金额 '.$order_fee.'","COLOR":"#173177"},{"VALUE":"退款金额  '.$refund_amount.'","COLOR":"#173177"},{"VALUE":"'.$out_trade_no.'","COLOR":"#173177"}]';
	          //AttentionToGLY($out_trade_no,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	          //AttentionToGLY($out_trade_no,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	           echo $back;
			}
			break;
		default:
		    Log::DEBUG($out_request_no."    支付宝退款失败 ：     不支持的交易状态，交易返回异常!!!");
		    $back='{"OPERTYPE":"A","SERVICENO":"WX001","HOSPITALNO":"123123123","RESULT_CODE":"FAIL","RETURN_MSG":"不支持的交易状态，交易返回异常!!!"}';
	        
	        $FIRSTVALUE="管理员，支付宝退费，退费失败，失败原因为：不支持的交易状态，交易返回异常!";
	        $LASTVALUE="退款单号：".$out_request_no.",请确认相关退款情况";
	        $KEYWORD='[{"VALUE":"总金额 '.$order_fee.'","COLOR":"#173177"},{"VALUE":"退款金额  '.$refund_amount.'","COLOR":"#173177"},{"VALUE":"'.$out_trade_no.'","COLOR":"#173177"}]';
	        //AttentionToGLY($out_trade_no,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	        //AttentionToGLY($out_trade_no,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
	
	        echo $back;
			break;
	}
	return ;
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
				        <TYPE>1</TYPE>
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
			$temp='ZFPt9ux1BqyOxMLukHUpCfSet8y2zy1OA-8Otfhzm9s';
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
