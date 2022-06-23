<?php
header("Content-Type: text/html;charset=utf-8");
session_start();
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "dyrmyy/libdyrmyy/WxPay.Api.php";
require_once "dyrmyy/WxPay.JsApiPay.php";
require_once '../wxpay/example/log.php';

//初始化日志
$logHandler=new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
$URL="http://113.96.111.2:8089/EasyOnlineService/services/hsWebService?wsdl"; 
$trade='ydd-gh'.date('YmdHis').mt_rand(100,10000).mt_rand(100,10000);
Log::DEBUG("                                            ");
Log::DEBUG($trade."第一人民医院挂号入参".$_REQUEST["Array"]);
$arr=json_decode($_REQUEST["Array"],true);

//①、获取用户openid
$tools = new JsApiPay();
if(empty($_SESSION['openid'])){
	$openId = $tools->GetOpenid();
	$_SESSION['openid']=$openId;
}else{
	$openId = $_SESSION['openid'];
}
$mobilesave=null;
$mobilesave=keepPay($trade,$openId,$arr);
if($mobilesave=="success"){
	$wxmoney=floatval($arr['MONEY']) * 100;//微信金额
	//②、统一下单
	$input = new WxPayUnifiedOrder();
	$input->SetBody('移动挂号费用：'.$arr['CARDNUM']);//商品
	$input->SetAttach($arr['ORG_ID']);
	$input->SetOut_trade_no($trade);//单号
	$input->SetTotal_fee($wxmoney);//金额
	$input->SetTime_start(date('YmdHis'));
	$input->SetTime_expire(date('YmdHis', time() + 120));
	$input->SetNotify_url('http://www.hnwisdom.com/BMFW/dist/public/weixin/wxpay/example/notify_bmfw_HuiZhou_dyrmyy.php');
	$input->SetTrade_type('JSAPI');
	$input->SetOpenid($openId);
	$order = WxPayApi::unifiedOrder($input);
	Log::DEBUG($trade."  订单返回数据 ".json_encode($order));		
	$jsApiParameters = $tools->GetJsApiParameters($order);
 }

  //保存登记表用户各项信息
  function keepPay($trade,$openId,$arr){
  	Log::DEBUG($trade."   -----预约挂号------".$arr['CARDNUM']."-----start first-------------------------");
	$hisflag = "error";
	try{	
        libxml_disable_entity_loader(false);
		$client = new SoapClient($GLOBALS['URL']);
		$client->soap_defencoding = 'utf-8';
		$client->decode_utf8 = false;
		$client->xml_encoding = 'utf-8';
		$bean = '<OPENID>'.$openId.'</OPENID>
		        <XMID>'.$arr['ISTIMEREG'].'</XMID>
				<ZDMC>'.$arr['STARTTIME'].'</ZDMC>
				<ZSMC>'.$arr['ENDTIME'].'</ZSMC>
				<PID>'.$arr['REGFEE'].'</PID>
				<ZSID>'.$arr['TREATFEE'].'</ZSID>
		        <ORG_ID>'.$arr['ORG_ID'].'</ORG_ID> 
				<MONEY>'.$arr['MONEY'].'</MONEY>
				<PHONE>'.$arr['PHONE'].'</PHONE>
		      	<TRADE>'.$trade.'</TRADE>
		      	<CARDNUM>'.$arr['CARDNUM'].'</CARDNUM>
		      	<CARDTYPE>2</CARDTYPE>
		      	<HZXM>'.$arr['HZXM'].'</HZXM>
		      	<DEPTNAME>'.$arr['PBKS'].'</DEPTNAME>
		      	<YYSD>'.$arr['YYSD'].'</YYSD>
		      	<JZID>'.$arr['PBLX'].'</JZID>
		      	<YYRQ>'.$arr['YYRQ'].'</YYRQ>
		      	<HYZJ>'.$arr['TIMERANGE'].'</HYZJ>
		      	<YSXM>'.$arr['YSXM'].'</YSXM>
				<YSID>'.$arr['YSID'].'</YSID>
				<GHXXID>'.$arr['PBKSID'].'</GHXXID>
				<GHF>'.$arr['MONEY'].'</GHF>
				<PBZXID>'.$arr['NUMBERID'].'</PBZXID>
				<YSJB>'.$arr['ZZJB'].'</YSJB>
				<HOSPITALCODE>'.$arr['HOSPITALCODE'].'</HOSPITALCODE>
				<DZJKM>'.$arr['DZJKM'].'</DZJKM>
				<PAYTYPE>0</PAYTYPE>
		      	<LX>0</LX>';
		$paramOrg = "serviceId{=}WXGD00003{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
		$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN>".$bean."</BEAN></DATA></WSD>";
		$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
		$result = $client->__Call("service", array($param));		
		$data = $result->return; 
		Log::DEBUG($trade."  call back ：保存登记表 : " . $data);
		$hiscc = new SimpleXMLElement($data);
		$JD = $hiscc->DATA;
		$info=json_decode($JD,true);
		Log::DEBUG($trade."  call back ：保存登记表 : " . $info["BEAN"]);	
		if($info["BEAN"]=="编辑成功"){
			$hisflag = "success";
		}	
	}catch(Exception $e){
		Log::DEBUG($trade." 保存登记表  error : " . $e->getMessage());
	}
	return $hisflag;
}

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>预约挂号-支付</title>
    <script type="text/javascript" src="jquery-1.8.3.min.js"></script>
    <script type="text/javascript">
    var url="http://113.96.111.2:8089/EasyOnlineService/services/hsWebService?wsdl";
    var kh="<?php echo $arr['CARDNUM']; ?>";
	var mobilesave = "<?php echo $mobilesave;?>";
	var trade="<?php echo $input->GetOut_trade_no(); ?>";
	var xmlheadstr="<?php echo '<?xml version=\"1.0\" encoding=\"UTF-8\"?>';?>";
	
	if(kh!=null&&kh!="undefined"){	
			if(mobilesave=="success"){
				callpay();
			}else{
				alert("尊敬的用户，你好，当前手机信号不稳定，请在网络良好的环境下再试");
				window.history.back(-1); 
			}
	}else{
		  alert("尊敬的用户，你好，请选择就诊人后重新发起支付");
		  window.history.back(-1); 
	}
	
	
	
	
	
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
	
	//查询缴费状态
	function queryPay(){
		wxLog(trade+"  call back : 微信返回取消状态时，查询订单状态");
		var soap = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.zsgr.com/">'+
		'<soapenv:Header/>'+
		'<soapenv:Body>'+
		'<ser:service>'+
		'<param>serviceId{=}WXGD00005{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N</param>'+
	  '<data><![CDATA['+xmlheadstr+
		'<WSD>'+
		'<MESSAGE>'+
		'<VERSION>1</VERSION>'+
		'</MESSAGE>'+
		'<DATA>'+
		'<BEAN>'+
      	'<TRADE>'+trade+'</TRADE>'+//商户订单号
		'</BEAN>'+
		'</DATA>'+
		'</WSD>]]>'+
		'</data>'+
		'</ser:service>'+
		'</soapenv:Body>'+
		'</soapenv:Envelope>';
		$.ajax({
	        type:"post",
	       	url:url,
	        dataType:'text',
	        data:soap,
	        async:false,
	        timeout: 60000, 
	     	success:function(data){
			    var jqueryObj = $(data);
				var message = jqueryObj.children();
				var info = $(message.text()).find("DATA").text();				
				info=JSON.parse(info); 	
				wxLog(trade+"  支付标识："+info.FLAG+"   订单查询结果："+JSON.stringify(info));			
				if(info.FLAG!=null&&info.FLAG!=undefined){
				    window.location.href="http://www.hnwisdom.com/BMFW/dist1/#/yygh_yycg?trade="+trade; 
				}else{
					window.history.back(-1); 
				}					
			},
			error:function(e){
				window.history.back(-1);
				wxLog(trade+"  订单查询结果异常"+e.responseText);
			}			
	    });	  
	}
	
	//调用微信JS api 支付
	function jsApiCall()
	{			
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){	
				wxLog(trade+"    pl_jsapi微信支付接口回调标志 ："+res.err_msg);				
				if(res.err_msg == "get_brand_wcpay_request:cancel"){//用户取消支付或者余额不足支付失败   微信返回不一定准确
					queryPay();
				}else{
					window.location.href="http://www.hnwisdom.com/BMFW/dist1/#/yygh_yycg?trade="+trade; 
				}			
			}
		);
	}
	//点击立即支付事件
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
    <div align="center">	
		<div id="atten" style="display:flex;flex-direction:column;justify-content:center;align-items:center;height:60%">
			<span><img src="loading.gif" style="width:65px;margin-bottom: 30px;"></img></span>	
			<span style="color:#28b7a3">支付检测中，请耐心等待. . .</span>	
		</div>	
	</div>
</body>
</html>