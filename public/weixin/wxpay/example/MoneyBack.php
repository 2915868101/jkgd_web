<?php 
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler=new CLogFileHandler("../../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("费用补缴");
$input->SetAttach("test");
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis").rand(999,10000));
$input->SetTotal_fee("500");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
$jsApiParameters = $tools->GetJsApiParameters($order);
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>广德市人民医院费用补缴</title>
    <link rel="stylesheet" type="text/css" href="../../font_csbarut7qkjzto6r/demo.css"/>
	<link rel="stylesheet" type="text/css" href="common.css"/>
	<link rel="stylesheet" type="text/css" href="publics.css"/>
	<link rel="stylesheet" type="text/css" href="../../wsd.healthrecords.com.css/fakeLoader.css"/>
	<link rel="stylesheet" type="text/css" href="../../wsd.healthrecords.com.css/successJsApi.css"/>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{

		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				 if(res.err_msg == "get_brand_wcpay_request:ok"){ 
				 	alert("您已成功补缴5元！谢谢您的使用");
				 	WeixinJSBridge.call('closeWindow');			
				 }			
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	
	</script>
</head>
<body>
	<div class="head">
			<div class="head-return">
				<div class="return-svg">
					<svg class="icon" aria-hidden="true">
                      
                    </svg>
				</div>
			</div>
			<div class="head-title">广德市人民医院</div>
			<div class="add-report"></div>
	</div>
	 <div class="nav">
            <div class="nav-auto" id="report">
              	<div style="background-color: #EFEFF4;float: left;width: 100%;cursor: pointer;margin-bottom: .5rem;">
			

				<div class="qxjg" id="qxjg">
				<br/>
					<div style="color:#9ACD32;font-size:20px;margin-bottom:50px;">
                 	 		尊敬的王悦（诊疗卡号 ：0100001083023 ）， 2019年02月25号挂号，需补缴金额为<span style="color:#f00;font-size:30px">5</span>元。
                 	</div>
            
			<div align="center">
				<button style="width:28rem; height:3.4rem; border-radius:5px;background-color:#21aef3; border:0px #FE6714 solid; cursor: pointer;  color: #FFFFFF;  font-size:1.5rem;margin-top: .6rem;" type="button" onclick="callpay()" >立即支付</button>
			</div>
					
					
				</div>									
		       </div>				
			</div>
	</div>
   
</body>
</html>