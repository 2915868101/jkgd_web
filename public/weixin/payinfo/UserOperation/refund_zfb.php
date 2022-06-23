<?php 
header("Content-type: text/html; charset=utf-8");
require_once '../../dangmianfu_demo_php/f2fpay/model/builder/AlipayTradeRefundContentBuilder.php';
require_once '../../dangmianfu_demo_php/f2fpay/service/AlipayTradeService.php';
require_once '../../wxpay/example/log.php';
$logHandler= new CLogFileHandler("../../logs/backlog/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

if (!empty($_POST['out_trade_no'])&& trim($_POST['out_trade_no'])!=""){
	
	$out_trade_no = trim($_POST['out_trade_no']);
	$refund_amount = trim($_POST['refund_amount']);
	$out_request_no = "alipayrefund".date('Ymdhis').mt_rand(100,1000);

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
	Log::DEBUG("                               ");
	Log::DEBUG("-----------------------------------Alipay  refund   by  browser---------------------------------");
	Log::DEBUG($out_trade_no."      浏览器支付宝退费开始          退款金额为：".$refund_amount."  退款请求批次号 ".$out_request_no);
	$refundResult =	$refundResponse->refund($refundRequestBuilder);
	//根据交易状态进行处理
	switch ($refundResult->getTradeStatus()){
		case "SUCCESS":
		    Log::DEBUG($out_request_no."   SUCCESS ".json_encode($refundResult->getResponse()));
		    SaveRefundinfo($out_trade_no,$refund_amount,$out_request_no);
			echo "支付宝退款成功:"."<br>--------------------------<br>";
			print_r($refundResult->getResponse());
			break;
		case "FAILED":
		    Log::DEBUG($out_request_no."  FAILED 支付宝退款失败 ：   ".json_encode($refundResult->getResponse()));
		    Log::DEBUG($out_request_no."  FAILED 失败原因 ：   ".$refundResult->getResponse()->sub_msg);
			echo "支付宝退款失败!!!"."<br>--------------------------<br>";
			if(!empty($refundResult->getResponse())){
				print_r($refundResult->getResponse());
			}
			break;
		case "UNKNOWN":
		    Log::DEBUG($out_request_no."   UNKNOWN ".json_encode($refundResult->getResponse()));
		    Log::DEBUG($out_request_no."   UNKNOWN 支付宝退款失败 ：     系统异常，订单状态未知!!  ");
			echo "系统异常，订单状态未知!!!"."<br>--------------------------<br>";
			if(!empty($refundResult->getResponse())){
				print_r($refundResult->getResponse());
			}
			break;
		default:
		    Log::DEBUG($out_request_no."    支付宝退款失败 ：     不支持的交易状态，交易返回异常!!!");
			echo "不支持的交易状态，交易返回异常!!!";
			break;
	}
	return ;
}

//保存退款信息
function SaveRefundinfo($trade,$refund_fee,$refund_trade){
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
				        <REFUND_TRADE>".$refund_trade."</REFUND_TRADE>
				        <REFUND_FEE>".$refund_fee."</REFUND_FEE>
				        <TYPE>1</TYPE>
				        </BEAN></DATA></WSD>";
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));			
			$data = $result->return; 
			Log::DEBUG($trade."     ".$refund_trade."   WX_REFUND_RECORD：保存退费日志表 : " . $data);
			$queryZh = new SimpleXMLElement($data);
			$JD = $queryZh->DATA;
			Log::DEBUG($trade."   ".$refund_trade."   WX_REFUND_RECORD：是否保存成功 : " . $JD);			
		}catch(Exception $e){
			Log::DEBUG($trade."    ".$refund_trade."   WX_REFUND_RECORD：保存退费日志 Exception : " . $e->getMessage());
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	<title>支付宝当面付 交易退款</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
*{
	margin:0;
	padding:0;
}
ul,ol{
	list-style:none;
}
.title{
    color: #ADADAD;
    font-size: 14px;
    font-weight: bold;
    padding: 8px 16px 5px 10px;
}
.hidden{
	display:none;
}

.new-btn-login-sp{
	border:1px solid #D74C00;
	padding:1px;
	display:inline-block;
}

.new-btn-login{
    background-color: transparent;
    background-image: url("../img/new-btn-fixed.png");
    border: medium none;
}
.new-btn-login{
    background-position: 0 -198px;
    width: 82px;
	color: #FFFFFF;
    font-weight: bold;
    height: 28px;
    line-height: 28px;
    padding: 0 10px 3px;
}
.new-btn-login:hover{
	background-position: 0 -167px;
	width: 82px;
	color: #FFFFFF;
    font-weight: bold;
    height: 28px;
    line-height: 28px;
    padding: 0 10px 3px;
}
.bank-list{
	overflow:hidden;
	margin-top:5px;
}
.bank-list li{
	float:left;
	width:153px;
	margin-bottom:5px;
}

#main{
	width:750px;
	margin:0 auto;
	font-size:14px;
	font-family:'宋体';
}
#logo{
	background-color: transparent;
    background-image: url("../img/new-btn-fixed.png");
    border: medium none;
	background-position:0 0;
	width:166px;
	height:35px;
    float:left;
}
.red-star{
	color:#f00;
	width:10px;
	display:inline-block;
}
.null-star{
	color:#fff;
}
.content{
	margin-top:5px;
}

.content dt{
	width:160px;
	display:inline-block;
	text-align:right;
	float:left;
	
}
.content dd{
	margin-left:100px;
	margin-bottom:5px;
}
#foot{
	margin-top:10px;
}
.foot-ul li {
	text-align:center;
}
.note-help {
    color: #999999;
    font-size: 12px;
    line-height: 130%;
    padding-left: 3px;
}

.cashier-nav {
    font-size: 14px;
    margin: 15px 0 10px;
    text-align: left;
    height:30px;
    border-bottom:solid 2px #CFD2D7;
}
.cashier-nav ol li {
    float: left;
}
.cashier-nav li.current {
    color: #AB4400;
    font-weight: bold;
}
.cashier-nav li.last {
    clear:right;
}
.alipay_link {
    text-align:right;
}
.alipay_link a:link{
    text-decoration:none;
    color:#8D8D8D;
}
.alipay_link a:visited{
    text-decoration:none;
    color:#8D8D8D;
}
</style>
</head>
<body text=#000000 bgColor="#ffffff" leftMargin=0 topMargin=4>
	<div id="main">
		<div id="head">
            <dl class="alipay_link">
                <a target="_blank" href="http://www.alipay.com/"><span>支付宝首页</span></a>|
                <a target="_blank" href="https://b.alipay.com/home.htm"><span>商家服务</span></a>|
                <a target="_blank" href="http://help.alipay.com/support/index_sh.htm"><span>帮助中心</span></a>
            </dl>
            <span class="title">支付宝 当面付2.0  订单退款接口</span>
		</div>
        <div class="cashier-nav">
            <ol>
				<li class="current">1、确认信息 →</li>
				<li>2、点击确认 →</li>
				<li class="last">3、确认完成</li>
            </ol>
        </div>
        <form name=alipayment action="" method=post target="_blank">
            <div id="body" style="clear:left">
                <dl class="content">
					<dt>商户订单号：</dt>
					<dd>
						<span class="null-star">*</span>
						<input size="30" name="out_trade_no" />
						<span>必填</span>
					</dd>
					
					<dt>退款金额：</dt>
					<dd>
						<span class="null-star">*</span>
						<input size="30" name="refund_amount" />
						<span>必填 ， 不能超过订单总金额</span>
					</dd>
									
                    <dt></dt>
                    <dd>
                        <span class="new-btn-login-sp">
                            <button class="new-btn-login" type="submit" style="text-align:center;">确 认</button>
                        </span>
                    </dd>
                </dl>
            </div>
		</form>
        <div id="foot">
			<ul class="foot-ul">
				<li><font class="note-help">如果您点击“确认”按钮，即表示您同意该次的执行操作。 </font></li>
				<li>
					支付宝版权所有 2011-2015 ALIPAY.COM 
				</li>
			</ul>
		</div>
	</div>
</body>
</html>