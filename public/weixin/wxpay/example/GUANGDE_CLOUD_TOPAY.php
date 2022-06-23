<?php
header("Content-Type: text/html;charset=utf-8");
session_start();
ini_set('date.timezone','Asia/Shanghai');
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
$URL="http://113.246.243.65:6006/inter/order/"; 
$GD_URL="http://113.246.243.65:6006/inter1/order/"; 
//初始化日志
$logHandler=new CLogFileHandler("../../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
$arr=$_GET;
Log::DEBUG("                                ");
$jsApiParameters ="99";
global $_SERVER;  
$ip = $_SERVER["REMOTE_ADDR"];//获取访问者ip 
Log::DEBUG("请求ip：".$ip.'   统一支付平台支付入参   '.json_encode($arr)); 
$agent  = $_SERVER['HTTP_USER_AGENT']; 
Log::DEBUG("请求浏览器".$agent); 
$zfxx=null;
$openId=null;
$mobilesave=null;
$Qrcode=null;
$device=null;
$trade=null;
$orgId=null;
$mobilePAYTYPE=null;
$brxm=null;
$money=null;
$phone=null;

if(!empty($_REQUEST["brxm"])){
   $brxm=$_REQUEST["brxm"];
}
if(!empty($_REQUEST["money"])){
  $money=$_REQUEST["money"];
}
if(!empty($_REQUEST["phone"])){
   $phone =$_REQUEST["phone"];	
}
if(!empty($_REQUEST["orgId"])){
   $orgId =$_REQUEST["orgId"];	
}
if(!empty($_REQUEST["trade"])){
   $trade=$_REQUEST["trade"];	
}
if(!empty($_REQUEST["zfxx"])){
   $zfxx=$_REQUEST["zfxx"];
}


if(empty($_REQUEST["money"])||empty($_REQUEST["trade"])||empty($_REQUEST["orgId"])){
  $mobilesave='您好，参数异常，请重新下单！';
  Log::DEBUG('-------支付信息：'.$_REQUEST["money"].'---------金额、订单号异常'); 
}
else{
		Log::DEBUG($trade." ￥￥￥￥￥￥￥￥     统一支付平台 扫码支付开始      ￥￥￥￥￥￥￥￥￥￥￥￥￥￥￥￥￥￥￥￥                     ");
		$product['zfxx']=$zfxx;
		$product['trade']=$trade;
		$product['orgId']=$orgId;
		$product['phone']=$phone;
		$product['money']=$money;
		$product['brxm']=$brxm;
		//微信或支付宝
		if(strstr($agent,'MicroMessenger') !== false||strstr($agent,'AlipayClient') !== false){ 
			       if(strstr($agent,'MicroMessenger') !== false){
					   $device='WeiXIN';
					   $tools = new JsApiPay();
					   if(empty($_SESSION['openid'])){
							$openId = $tools->GetOpenid();	
							Log::DEBUG($trade." 第一次获取openid   ".$openId);	
						    $_SESSION['openid']=$openId;
					   }else{
							$openId = $_SESSION['openid'];
							Log::DEBUG($trade." 刷新后第二次获取openid  ".$openId);		
					   }	
					   $mobilePAYTYPE='0';
					   $product['openid']=$openId;
				   }else{
					   $mobilePAYTYPE='1';
					   $device='Alipay';
				   }
				   $mobilesave=keepPay($product,$trade,$mobilePAYTYPE);		
 				   if($mobilesave=="success"){
					        Log::DEBUG($mobilesave."  wx_jf 接口保存出错");
 						    $CLOUDTRADE=$orgId.date("YmdHis").mt_rand(100,10000).mt_rand(100,10000);
  						    $arr=nativePay($money,$trade,$CLOUDTRADE,$orgId);
     						if($arr['CODE']==0){
 								$Qrcode=$arr['QRURL'];
 							}else{
 								$mobilesave='尊敬的用户，你好，缴费异常，异常原因：'.$arr['MSG'];
                              }
 					}else{						
						Log::DEBUG($trade."  wx_jf 接口保存出错");	
					}
    }else{
    	  $mobilesave='请使用微信或支付宝扫码付款';
	      Log::DEBUG($trade."   请使用微信或支付宝扫码付款");
    }
}

//保存订单信息
function keepPay($product,$trade,$paytype){
	$hisflag = "error";
	try{
		$product['msg']='未付款';
		$product['paytype']=$paytype;
		$bizContent = json_encode($product,JSON_UNESCAPED_UNICODE);
		Log::DEBUG($trade."  保存订单开始 入参 : " . $bizContent);
		$result=http_POST($bizContent,$GLOBALS['URL'].'create',$second = 120);
		Log::DEBUG($trade."  保存订单表 出参: " . $result);
		$returnInfo=json_decode($result,true);
		if($returnInfo['errno']==0){
			$hisflag = "success";
		}else{
			$hisflag=$returnInfo['errmsg'];
		}
	}catch(Exception $e){
		Log::DEBUG($trade." 保存订单表  error : " . $e->getMessage());
	}
	return $hisflag;
}

//广德云支付 生成二维码链接
function nativePay($MONEY,$TRADE,$CLOUDTRADE,$orgId){
	try{
		$product['MONEY']=$MONEY;
		$product['ORGID']=$orgId;
		$product['TRADE']=$TRADE;
		$product['CLOUDTRADE']=$CLOUDTRADE;
		$bizContent = json_encode($product,JSON_UNESCAPED_UNICODE);
		Log::DEBUG($TRADE." 广德云支付生成二维码 入参 : " . $bizContent);
		$result=http_POST($bizContent,$GLOBALS['GD_URL'].'nativePay',$second = 120);
		Log::DEBUG($TRADE."  广德云支付生成二维码 出参: " . $result);
		$returnInfo=json_decode($result,true);
		return $returnInfo;
	}catch(Exception $e){
		Log::DEBUG($TRADE." 广德云支付生成二维码  error : " . $e->getMessage());
	}
}

//curl模拟POST请求 body请求
/*
* @param int $second   url执行超时时间，默认30s
*/
function http_POST($params,$url, $second = 120){ 	
      //curl模拟POST请求，获取结果
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

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>确认支付</title>
    <script type="text/javascript" src="jquery-1.8.3.min.js"></script>
    <style type="text/css">
      body{
     	 background-color:#f6f6f6;     	 
     }
     html,body{
				font-size: 16px !important;
				font-family: "microsoft yahei" !important;
			}
     *{margin: 0;padding: 0;vertical-align:middle;}
     *{box-sizing:border-box;-moz-box-sizing:border-box; /* Firefox */-webkit-box-sizing:border-box; /* Safari */}
     .divcss{display:flex;flex-direction:column;justify-content:center;align-items:center;}
     .divh{height:100%}
     .bgcolor{background-color:#fff; width:100%;}
     .top{margin-top: 10px;}
     .zfxx{color:#929191;display:flex;padding-top:18px;padding-right:18px;padding-bottom:18px;padding-left: 28px;}
	 .img{width: 30px;height: 21px; display: inline-block;border: 0;padding-right: 10px;}
	 .u-sub-btn { color: #fff;background-color: #28b7a3;}
     .u-sub-btn {
			    margin: 15px;
			    height: 41px;
			    line-height: 40px;
			    font-size: 16px;
			    border-radius: 20px;
			    text-align: center;
			    border: 1px solid #28b7a3;
			}
	 .divcss .icon_05 {
		  display: inline-block;
		  width: 70px;
		  height: 70px;
		  background-position: center center;
		  background-repeat: no-repeat;
		  background-size: 100% auto;
		}
	 .divcss .icon_05 {
            background-image: url(icon_05.svg);
		}
    </style>
    	
</head>
<body>
	<div id="mzPay" class="divcss" style="display:none">
				<div class="divcss bgcolor" style="padding:60px">
					 	<span style="font-size: 30px;line-height:35px;">￥<font style="color: #fd0101;font-size: 30px;margin-top:-8px;display: inline-block;"><?php echo $money?></font></span>
					 	<span style="color:#797878;margin-top: 10px;">合计费用</span>
				</div>
					
				<div class="top bgcolor zfxx">
						<span>支付信息：</span>
						<span><?php echo $zfxx?></span>
			  </div>
			  <?php if ($mobilePAYTYPE == '1'): ?>
			  <div class="top bgcolor zfxx">
						<span>支付方式：</span>
						<span><img src="zhifb.png" class="img"></img>支付宝</span>
			  </div>
			  <?php elseif ($mobilePAYTYPE == '0'): ?>
			  <div class="top bgcolor zfxx">
						<span>支付方式：</span>
						<span><img src="weixin.png" class="img"></img>微信</span>
			  </div>
			  <?php endif; ?>
			  <div style="margin-top:40px;width:100%;">
			   	 <div class="u-sub-btn" id="submit-button">确认支付</div>	
			  </div>
				
  </div>
  
  
  <div  id="errorinfo" class="divh bgcolor" style="display:none">
  	 <div  class="divcss bgcolor" style="padding:70px">	
    	<i class="icon_05"></i><br />
  	  <div class="top bgcolor divcss">
			<span  id="cwxx" style="color:#e21b1b;font-size:22px">无待缴费用</span>					
	  </div> 	
    </div>    	
  </div>
  
  
 <script type="text/javascript">
	var Qrcode= "<?php echo $Qrcode;?>";
    var MONEY= "<?php echo $money;?>";
	var orgId= "<?php echo $orgId;?>";
    var TRADE= "<?php echo $trade;?>";
    var ZFXX= "<?php echo $zfxx;?>";
    var device= "<?php echo $device;?>";
    var mobilesave = "<?php echo $mobilesave;?>"; 
    var xmlheadstr="<?php echo '<?xml version=\"1.0\" encoding=\"UTF-8\"?>';?>";  
    		
       if(mobilesave=="success"){	   	   	   
		    $("#mzPay").css("display","block");	
	   	}else if(mobilesave=="error"){
	   		formError('尊敬的用户，你好,缴费异常,请重新下单');
	   	}else{
	   		formError(mobilesave)
	   	}	
    		 
    $('#submit-button').one("click",function(){
    	  $("#submit-button").css("background-color","#b3bdbc")
   	      $("#submit-button").css("border","1px solid rgb(239, 245, 244)")
		   window.location.href=Qrcode; 	
    }); 
     //微信保存日志
	function wxLog(message)
	{
		$.ajax({
		type:"POST",
        url:'wxLogConfig.php',
        dataType:"json",
        async:false, 
        data:{message:JSON.stringify(message)},//错误信息
				success:function(xml){},
				error: function(e) {}
		});
	}
	
	 function formError(errormsg){	 
	 	 $("#mzPay").css("display","none");	   	
		 $("#errorinfo").css("display","block");
		 $("#cwxx").html(errormsg);
	 }
	</script>
  
  
</body>
</html>