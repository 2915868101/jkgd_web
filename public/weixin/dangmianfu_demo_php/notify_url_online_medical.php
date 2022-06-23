<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
ini_set('date.timezone','Asia/Shanghai');
ini_set('default_socket_timeout', -1); //不超时
error_reporting(E_ERROR);
/* *
 * 功能：支付宝服务器异步通知页面
 */
require_once("f2fpay/config/config.php");
require_once 'f2fpay/service/AlipayTradeService.php';
require_once 'log.php';
//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
$MSG="";  //全局返回信息
$URL="http://113.246.243.65:6006/inter/order/";  //全局URL
$arr=$_POST;
$out_trade_no=$_REQUEST['out_trade_no'];
$total_amount=$_REQUEST['total_amount'];
$trade_status=$_REQUEST['trade_status'];

Log::DEBUG($_POST['out_trade_no']."   alipay   begin notify +++++++++++++++++++++++++++++++++++++++++++++++++++++");
Log::DEBUG($_POST['out_trade_no']."     ".json_encode($arr));

$alipaySevice = new AlipayTradeService($config); 
//$alipaySevice->writeLog(var_export($_POST,true));
Log::DEBUG($out_trade_no."  订单金额 :   ".$total_amount."  交易状态：    ".$trade_status."  交易类型: ".$_POST['subject']);
$result = $alipaySevice->check($arr);
//$result =1;
Log::DEBUG($_POST['out_trade_no']." 校验结果 : ". $result);

/* 实际验证过程建议商户添加以下校验。
1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
4、验证app_id是否为该商户本身。
*/
if($result) {//验证成功
    Log::DEBUG($out_trade_no."     his业务开始        ");
    if($trade_status=='TRADE_FINISHED') {

		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
    }
    else if ($trade_status=='TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序			
		//注意：
		//付款完成后，支付宝系统发送该交易状态通知
		
		    //查询登记表用户信息 
	        $arr =queryinfo($out_trade_no);  
	     	//判断医院业务逻辑状态，防止微信回调再次通知进入医院逻辑
	     		if($arr["flag"]!="201"){
	     			updateinfo($arr["trade"],"201","已付款");
	     			Log::DEBUG($arr["trade"]."  缴费成功-------------------------------------------------");						
	     		}   								
    }
    Log::DEBUG($out_trade_no."  &&&&&&&&&回调成功&&&&&&&&&&&");  
	echo "success";		//请不要修改或删除
		
}else {
    //验证失败
    echo "fail";	//请不要修改或删除

}

	//查询
	function queryinfo($trade){
		try{
			$bizContentarr = array();
			$bizContentarr['trade']=$trade;
			$bizContent = json_encode($bizContentarr,JSON_UNESCAPED_UNICODE);
			Log::DEBUG($trade."  查询订单状态开始 入参 : " . $bizContent);
			$result=http_POST($bizContent,$GLOBALS['URL'],$second = 120,'query');
			Log::DEBUG($trade."  查询订单状态 出参: " . $result);
			$returnInfo=json_decode($result,true);
			if($returnInfo['errno']==0){
				$orderinfo=$returnInfo['data'];
				return $orderinfo;	
			}				
		}catch(Exception $e){
			//微信推送管理员
			$FIRSTVALUE="管理员，微信支付时，notify中查询订单时，跳入了异常，异常订单号为：".$trade." 异常原因：".$e->getMessage();
			$LASTVALUE="请及时查看相关情况";
			$KEYWORD='[{"VALUE":"","COLOR":""},{"VALUE":"","COLOR":""},{"VALUE":"'.$trade.'","COLOR":"#173177"}]';
			AttentionToGLY($trade,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
			AttentionToGLY($trade,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
			Log::DEBUG($trade."  queryinfo：查询订单日志 Exception : " . $e->getMessage());
		}
	}
	
		
	//修改缴费状态
	function updateinfo($trade,$newflag,$msg){
		$updateJf = "error";
		try{
			$bizContentarr = array();
	        $bizContentarr['trade']=$trade;
			$bizContentarr['newflag']=$newflag;
			$bizContentarr['msg']=$msg;
			$bizContentarr['paytype']='1';
			$bizContent = json_encode($bizContentarr,JSON_UNESCAPED_UNICODE);
			Log::DEBUG($trade."  修改订单状态开始 入参 : " . $bizContent);
			$result=http_POST($bizContent,$GLOBALS['URL'],$second = 120,'update');
			Log::DEBUG($trade."  修改订单状态 出参: " . $result);
			$returnInfo=json_decode($result,true);
			if($returnInfo['errno']==0){
				$updateJf = "SUCCESS";	
			}						
		}catch(Exception $e){
			Log::DEBUG($trade."   updateinfo Exception 修改缴费状态异常 : " . $e->getMessage());
		}
		return $updateJf;
	}	
	
	
	 //curl模拟POST请求 body请求
	/*
	* @param int $second   url执行超时时间，默认30s
	*/
    function http_POST($params,$url, $second = 120,$methodUrl){ 	
	      //curl模拟POST请求，获取结果
	      $url=$url.$methodUrl;
	      $curlobj = curl_init();
		  $headers = array('content-type: application/json;charset=UTF-8');
		  //设置超时
		  curl_setopt($curlobj, CURLOPT_TIMEOUT, $second);
	      curl_setopt($curlobj, CURLOPT_URL, $url);
	      curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
	      curl_setopt($curlobj, CURLOPT_POST, 1);
	      curl_setopt($curlobj, CURLOPT_POSTFIELDS,  $params);
		  curl_setopt($curlobj, CURLOPT_HTTPHEADER, $headers);
	      $output = curl_exec($curlobj);
	      if (curl_errno($curlobj)) {
			  $error = curl_errno($curlobj);
			  $errorContent=curl_error($curlobj);
	     	  throw new Exception(curl_error($curlobj), 0);
	      }else{
			  $httpStatusCode = curl_getinfo($curlobj, CURLINFO_HTTP_CODE);
			  if (200 !== $httpStatusCode) {
			  	throw new Exception($reponse, $httpStatusCode);
			  }
		  }  
	      curl_close($curlobj);    
	      return $output;
	} 
	
?>

