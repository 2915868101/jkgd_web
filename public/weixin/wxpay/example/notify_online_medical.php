<?php
header("Content-type: text/html; charset=utf-8");

ini_set('date.timezone','Asia/Shanghai');
ini_set('default_socket_timeout', -1); //不超时
error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
$MSG="";  //全局返回信息
$URL="http://113.246.243.65:6006/inter/order/";//全局URL

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG($data["out_trade_no"]."    NotifyProcess" . json_encode($data));
		$notfiyOutput = array();
		
//		if(!array_key_exists("transaction_id", $data)){
//			$msg = "输入参数不正确";
//			return false;
//		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		
		//判断微信回调通知结果
		if($data["result_code"]=="SUCCESS"){
			//查询登记表用户信息 
			$arr = $this->queryinfo($data["out_trade_no"]);  	
			if($arr["FLAG"]!="201"){
				$this->updateinfo($arr["trade"],"201","已付款");
				Log::DEBUG($arr["trade"]."  缴费成功-------------------------------------------------");						
	     	}
		}
		Log::DEBUG($data["out_trade_no"]."  &&&&&&&微信回调通知成功&&&&&&&&&&&&&&&&");
		return true;
	}
	
	
	
	//查询
	public function queryinfo($trade){
		try{
			$bizContentarr = array();
			$bizContentarr['trade']=$trade;
			$bizContent = json_encode($bizContentarr,JSON_UNESCAPED_UNICODE);
			Log::DEBUG($trade."  查询订单状态开始 入参 : " . $bizContent);
			$result=$this->http_POST($bizContent,$GLOBALS['URL'],$second = 120,'query');
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
			$this->AttentionToGLY($trade,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
			$this->AttentionToGLY($trade,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
			Log::DEBUG($trade."  queryinfo：查询订单日志 Exception : " . $e->getMessage());
		}
	}
	

	
	//修改缴费状态
	public function updateinfo($trade,$newflag,$msg){
		$updateJf = "error";
		try{
			$bizContentarr = array();
            $bizContentarr['trade']=$trade;
			$bizContentarr['newflag']=$newflag;
			$bizContentarr['msg']=$msg;
			$bizContentarr['paytype']='0';
			$bizContent = json_encode($bizContentarr,JSON_UNESCAPED_UNICODE);
			Log::DEBUG($trade."  修改订单状态开始 入参 : " . $bizContent);
			$result=$this->http_POST($bizContent,$GLOBALS['URL'],$second = 120,'update');
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
    public  function http_POST($params,$url, $second = 120,$methodUrl){ 	
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
	
    
	
}


Log::DEBUG("begin notify************************************");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
