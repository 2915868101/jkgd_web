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
require_once '../wxpay/example/log.php';
//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
$MSG="";  //全局返回信息
$URL="http://172.16.2.117:9999/GdServiceBus/services/hsWebService?wsdl";  //全局URL
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
	        Log::DEBUG($out_trade_no." wx_jf 查询缴费状态 : ". $arr["FLAG"]);
	     	//判断医院业务逻辑状态，防止微信回调再次通知进入医院逻辑
	     	if($arr["LX"]=="5"){
	     		if($arr["FLAG"]!="0"){
	     			Log::DEBUG($arr["TRADE"]." 开始 更新状态为0 （支付成功）");	
	     			updateinfo($arr["TRADE"],"0","自助机支付成功");
	     			Log::DEBUG($arr["TRADE"]."  缴费成功-------------------------------------------------");						
	     		}
	     	}    	
			else if($arr["FLAG"]!="3"&&$arr["FLAG"]!="2"&&$arr["FLAG"]!="0"&&$arr["FLAG"]!="4"){
				Log::DEBUG($out_trade_no." 开始 更新状态为3 （处理中）");				
				//修改缴费状态为处理中3
				updateinfo($arr["TRADE"],"3","正在处理中，请稍后");
				
				Log::DEBUG($out_trade_no."  进入医院业务逻辑处理");
				//his系统交互
				$hisin =hiseach($arr,$data["openid"]);
				Log::DEBUG($out_trade_no."  his交互返回最终结果 标识:".$hisin);
				
				$zfsfcg = "1";//默认成功
				
				//若his交互异常，循环查询交互结果
				if($hisin=="error"){
					$exmsg=$GLOBALS['MSG'];	
					$zfsfcg = "error";
					for($i=0;$i<3;$i++){
						//his查询是否缴费
						$sfjf =hisquery($arr);
						if($sfjf=="SUCCESS"){//查询确认已经成功
							$zfsfcg = "1";
							break;
						}
						if($sfjf=="fail"){//查询确认缴费失败
							$zfsfcg = "0";
							break;
						}
						sleep(1);
					}
					//微信推送管理员
					$FIRSTVALUE="管理员，支付宝支付，就诊号为:".$arr["BLH"]."  缴费进入再次查询，异常原因为：".$exmsg;
					$LASTVALUE="用户手机号码为：".$arr["YHSJH"]."，循环3次返回结果为:".$GLOBALS['MSG'];
					$KEYWORD='[{"VALUE":"'.$arr["HZXM"].'","COLOR":"#173177"},{"VALUE":"'.$arr["MONEY"].'","COLOR":"#173177"},{"VALUE":"'.$arr["TRADE"].'","COLOR":"#173177"}]';
					AttentionToGLY($arr["TRADE"],null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
					AttentionToGLY($arr["TRADE"],null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		

				}
				
				//his返回信息，交互失败，直接退费
				if($hisin=="fail"){
					$zfsfcg = "0";
				}
				
				
				//判断his支付是否成功保存缴费信息或者退费
				if($zfsfcg == "1"){
					Log::DEBUG($arr["TRADE"]."   缴费成功-------------------------------------------------");
					updateinfo($arr["TRADE"],"0",$GLOBALS['MSG']);//修改缴费状态为成功0
				}
				else if ($zfsfcg == "0"){
					Log::DEBUG($arr["TRADE"]."   缴费失败-------------------------------------------------");
					updateinfo($arr["TRADE"],"2",$GLOBALS['MSG']);//修改缴费状态为失败2
					//微信推送管理员
					$FIRSTVALUE="管理员，支付宝支付，就诊号为:".$arr["BLH"]."，缴费失败，失败原因为：".$GLOBALS['MSG'];
					$LASTVALUE="用户手机号码为：".$arr["ZSID"]."，请及时核对情况";
					if($arr["LX"]=="3"){
			        	$LASTVALUE="用户手机号码为：".$arr["PHONE"]."，请及时核对情况。";
					}
					$KEYWORD='[{"VALUE":"'.$arr["HZXM"].'","COLOR":"#173177"},{"VALUE":"'.$arr["MONEY"].'","COLOR":"#173177"},{"VALUE":"'.$arr["TRADE"].'","COLOR":"#173177"}]';
					AttentionToGLY($arr["TRADE"],null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
					AttentionToGLY($arr["TRADE"],null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
					AttentionToGLY($arr["TRADE"],null,"o2akw1KAsJ5uqiu5I8eVTCjlWghY",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
					AttentionToGLY($arr["TRADE"],null,"o2akw1DyFUj_5cA35UR29G37LDEU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
					AttentionToGLY($arr["TRADE"],null,"o2akw1CK3NMVZ1t7wnwo16m9ujcY",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);				
					phoneConfig($arr,$zfsfcg,null,$arr["TRADE"]);//发送短信给用户 	
				}else{
					Log::DEBUG($arr["TRADE"]."   缴费异常-------------------------------------------------");
					updateinfo($arr["TRADE"],"4",$GLOBALS['MSG']);//缴费异常
					//发送缴费异常信息给管理员
					$FIRSTVALUE="管理员，支付宝支付，就诊号为:".$arr["BLH"]."，缴费异常，失败原因为：".$GLOBALS['MSG'];
			        $LASTVALUE="用户手机号码为：".$arr["ZSID"]."，请及时核对情况";
			        if($arr["LX"]=="3"){
			        	$LASTVALUE="用户手机号码为：".$arr["PHONE"]."，请及时核对情况。";
					}
			        $KEYWORD='[{"VALUE":"'.$arr["HZXM"].'","COLOR":"#173177"},{"VALUE":"'.$arr["MONEY"].'","COLOR":"#173177"},{"VALUE":"'.$arr["TRADE"].'","COLOR":"#173177"}]';
			        AttentionToGLY($arr["TRADE"],null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
			        AttentionToGLY($arr["TRADE"],null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);	
			        AttentionToGLY($arr["TRADE"],null,"o2akw1KAsJ5uqiu5I8eVTCjlWghY",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
			        AttentionToGLY($arr["TRADE"],null,"o2akw1DyFUj_5cA35UR29G37LDEU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
			        AttentionToGLY($arr["TRADE"],null,"o2akw1CK3NMVZ1t7wnwo16m9ujcY",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
	
					phoneConfig($arr,$zfsfcg,null,$arr["TRADE"]);//发送短信给用户
    				//phoneConfig($arr,null,"18974838847",$arr["TRADE"]);//发送短信给运营维护人员
    				//phoneConfig($arr,null,"13975699455",$arr["TRADE"]);//发送短信给运营维护人员


				}
			}
							
			//处理并发问题  缴费成功查询是否有2次记录 如果有，则表示异常  
			if($zfsfcg == "1"&&$arr["LX"]!="5"){//排除自助机
				$wxhisarr =queryWxHis($arr["LX"],$out_trade_no);
				Log::DEBUG($out_trade_no."  并发查询结果 : ". json_encode($wxhisarr));								
				if($wxhisarr["CODE"]=="-1"){//异常
					if($arr["LX"]=="6"){//窗口门诊扫码  有分单处理 his那边会生成2条his缴费记录
					   $zfxxmz=json_decode($arr["ZDMC"],true);
					   $CONTENTS= $zfxxmz['CONTENTS'];
					   if(count($CONTENTS)<$wxhisarr["his"]){
					   	    //发送缴费异常信息给管理员
					   	    $FIRSTVALUE="并发异常确认！！！！！！，支付宝支付，就诊号为:".$arr["BLH"]."，缴费可能出现并发异常，接口返回".json_encode($wxhisarr);
							$LASTVALUE="用户手机号码为：".$arr["ZSID"]."，请高度重视，核实情况！！！！！！";
							if($arr["LX"]=="3"){
					        	$LASTVALUE="用户手机号码为：".$arr["PHONE"]."，请高度重视，核实情况！！！！！！";
							}
							$KEYWORD='[{"VALUE":"'.$arr["HZXM"].'","COLOR":"#173177"},{"VALUE":"'.$arr["MONEY"].'","COLOR":"#173177"},{"VALUE":"'.$arr["TRADE"].'","COLOR":"#173177"}]';
							AttentionToGLY($arr["TRADE"],null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
							AttentionToGLY($arr["TRADE"],null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
					   }
					}else{									
						    //发送缴费异常信息给管理员
					   	    $FIRSTVALUE="并发异常确认！！！！！！，支付宝支付，就诊号为:".$arr["BLH"]."，缴费可能出现并发异常，接口返回".json_encode($wxhisarr);
							$LASTVALUE="用户手机号码为：".$arr["ZSID"]."，请高度重视，核实情况！！！！！！";
							if($arr["LX"]=="3"){
					        	$LASTVALUE="用户手机号码为：".$arr["PHONE"]."，请高度重视，核实情况！！！！！！";
							}
							$KEYWORD='[{"VALUE":"'.$arr["HZXM"].'","COLOR":"#173177"},{"VALUE":"'.$arr["MONEY"].'","COLOR":"#173177"},{"VALUE":"'.$arr["TRADE"].'","COLOR":"#173177"}]';
							AttentionToGLY($arr["TRADE"],null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
							AttentionToGLY($arr["TRADE"],null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
					}
				}else if($wxhisarr["CODE"]=="0"){//正常 不做处理
					
				}else{//不确定状态  也发送
					    //发送缴费异常信息给管理员
				   	    $FIRSTVALUE="支付宝支付，就诊号为:".$arr["BLH"]."，缴费成功，但不知道数据库订单状态，缴费可能出现并发异常，接口返回".$wxhisarr;
						$LASTVALUE="用户手机号码为：".$arr["ZSID"]."，请及时核对情况";
						if($arr["LX"]=="3"){
				        	$LASTVALUE="用户手机号码为：".$arr["PHONE"]."，请及时核对情况";
						}
						$KEYWORD='[{"VALUE":"'.$arr["HZXM"].'","COLOR":"#173177"},{"VALUE":"'.$arr["MONEY"].'","COLOR":"#173177"},{"VALUE":"'.$arr["TRADE"].'","COLOR":"#173177"}]';
						AttentionToGLY($arr["TRADE"],null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
						AttentionToGLY($arr["TRADE"],null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
				}
			}							
    }
    Log::DEBUG($out_trade_no."  &&&&&&&&&回调成功&&&&&&&&&&&");  
	echo "success";		//请不要修改或删除
		
}else {
    //验证失败
    echo "fail";	//请不要修改或删除

}

    
	//his系统交互接口
	 function hiseach($arr,$openid){
		$hisflag = "error";
		try{
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			
			if($arr["LX"]=="2"){//住院
				Log::DEBUG($arr["TRADE"]."   hospital : 进入his住院交互接口");
				$trade = $arr["TRADE"];//商户订单号
				$zyh = $arr["BLH"];//门诊号或者住院号
				$money = $arr["MONEY"];//缴费金额
				$paramOrg = "serviceId{=}WXGD00024{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			    $dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN><OPENID>".$arr["OPENID"]."</OPENID><TRADE>".$trade."</TRADE><CZJE>".$money."</CZJE><ZYH>".$zyh."</ZYH><PID>".$arr["JZID"]."</PID><PAYTYPE>2</PAYTYPE></BEAN></DATA></WSD>";
			}
						
			if($arr["LX"]=="1"||$arr["LX"]=="3"){//微信门诊 或扫码支付
				Log::DEBUG($arr["TRADE"]."  MZ  : 进入his门诊交互接口,1=>移动端支付  3=>his扫码支付,类型为".$arr["LX"]);
				$trade = $arr["TRADE"];//商户订单号
				$cardnum = $arr["CARDNUM"];//诊疗卡号
				$cardtype = $arr["CARDTYPE"];//卡类型
				$ghxxid = $arr["GHXXID"];//挂号科室id
				$xmid = $arr["HYZJ"];//项目id
				$jzid = $arr["JZID"];//接诊id
				$deptname = $arr["DEPTNAME"];//科室名称
				$ysxm = $arr["YSXM"];//医生姓名
				$jzsj = $arr["YYRQ"];//就诊时间
				$zdmc= $arr["ZDMC"];//诊断名称
				$brid= $arr["PID"];//病人id
				$money = $arr["MONEY"];//缴费金额
				$mzh = $arr["BLH"];//门诊号
				if($arr["LX"]=="1"){
					$DELFLAG="2";
				}else{
					$DELFLAG="6";
				}
				$mess=	"<OPENID>".$openid."</OPENID>
						<TRADE>".$trade."</TRADE>
						<CARDNUM>".$cardnum."</CARDNUM>
						<CARDTYPE>".$cardtype."</CARDTYPE>
						<GHXXID>".$ghxxid."</GHXXID>
						<XMID>".$xmid."</XMID>
						<MZH>".$mzh."</MZH>
						<HZXM>".$arr["HZXM"]."</HZXM>
						<PID>".$brid."</PID>
						<JZID>".$jzid."</JZID>	
						<KSMC>".$deptname."</KSMC>
						<YSXM>".$ysxm."</YSXM>
						<JZSJ>".$jzsj."</JZSJ>
						<ZDMC>".$zdmc."</ZDMC>
						<BLH>".$mzh."</BLH>		
						<CFJE>".$money."</CFJE>
						<DELFLAG>".$DELFLAG."</DELFLAG>
						<PAYTYPE>2</PAYTYPE>	
						<ZJE>".$money."</ZJE>";
						
				$paramOrg = "serviceId{=}WXGD00022{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			    $dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN>".$mess."</BEAN></DATA></WSD>";
				
			}
			if($arr["LX"]=="0"){//挂号
				Log::DEBUG($arr["TRADE"]."  YYGH : 进入his挂号交互接口");
				$PID  = $arr["PID"];//病人ID
				$YSID = $arr["YSID"];//医生ID
				$KSID = $arr["GHXXID"];//科室ID
				$ZSID = $arr["ZSID"];//诊室ID
				$XMID = $arr["XMID"];//性别ID
				$SJDID= $arr["JZID"];//预约时段
				$CARDNUM = $arr["CARDNUM"];//卡号
				$CARDTYPE = $arr["CARDTYPE"];//卡类型
				$GHF = $arr["GHF"];//挂号费
				$YYRQ = $arr["YYRQ"];//预约日期
				$PBZXID = $arr["PBZXID"];//排班执行id
				$GQSJ = $arr["GQSJ"];//过期时间
				$TRADE = $arr["TRADE"];//商户订单号
				$GHTYPE = $arr["GHTYPE"];//号类				
				$paramOrg = "serviceId{=}WXGD00017{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
				$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD>
				<MESSAGE>
					<VERSION>1</VERSION>
				</MESSAGE>
				<DATA>
					<BEAN>
					<PID>".$PID."</PID>		
					<YSID>".$YSID."</YSID>		
					<KSID>".$KSID."</KSID>		
					<ZSID>".$ZSID."</ZSID>
					<XMID>".$XMID."</XMID>		
					<SJDID>".$SJDID."</SJDID>
					<FKFS>2</FKFS>		
					<CARDNUM>".$CARDNUM."</CARDNUM>
					<FLAG>".$CARDTYPE."</FLAG>		
					<GHTYPE>".$GHTYPE."</GHTYPE>
					<GHF>".$GHF."</GHF>		
					<YYRQ>".$YYRQ."</YYRQ>
					<PBZXID>".$PBZXID."</PBZXID>		
					<GQSJ>".$GQSJ."</GQSJ>
					<TRADE>".$TRADE."</TRADE>	
					</BEAN>
				</DATA>
			   </WSD>";
			}
			if($arr["LX"]=="4"){//his窗口挂号
				Log::DEBUG($arr["TRADE"]."  YYGH : his窗口扫码支付");
				$JSDJH =$arr["PHONE"];//结算单据号
				$JSPJH =$arr["GHXXID"];//结算票据号
				$MZH =$arr["BLH"];//门诊号
				$ZJE =$arr["MONEY"];//支付金额
				$CZYID=$arr["YSID"];//操作员ID
				$BRID=$arr["PID"];//病人ID
				$PBID=$arr["DEPTNAME"];//排班ID
				$FB =$arr["YSXM"];//费别
				$SFFZ =$arr["ZDMC"];//是否复诊	
				$YHCL=$arr["HYZJ"];//优惠策略
				$XMID=$arr["JZID"];//项目ID
				$TRADE = $arr["TRADE"];//商户订单号	
				$paramOrg = "serviceId{=}WXGD00060{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
				$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD>
				<MESSAGE>
					<VERSION>1</VERSION>
				</MESSAGE>
				<DATA>
					<BEAN>
					<JSDJH>".$JSDJH."</JSDJH>	
					<JSPJH>".$JSPJH."</JSPJH>
					<MZH>".$MZH."</MZH>
					<CZYID>".$CZYID."</CZYID>	
					<BRID>".$BRID."</BRID>	
					<ZJE>".$ZJE."</ZJE>	
					<PBID>".$PBID."</PBID>		
					<FB>".$FB."</FB>		
					<SFFZ>".$SFFZ."</SFFZ>		
					<ZFFS>2</ZFFS>
					<SHDDH>".$TRADE."</SHDDH>
					<XM>".$arr["HZXM"]."</XM>			
					<YHCL>".$YHCL."</YHCL>
					<XMID>".$XMID."</XMID>						
					<OPENID>".$arr["OPENID"]."</OPENID>			
					</BEAN>
				</DATA>
			   </WSD>";
			}	
			if($arr["LX"]=="6"){//his窗口门诊缴费
				Log::DEBUG($arr["TRADE"]."  MZJF : his窗口扫码支付");
				$DJH =$arr["PHONE"];//单据号
				$FPH =$arr["GHXXID"];//票据号
				$JZH =$arr["BLH"];//门诊号				
				$ZJE =$arr["MONEY"];//支付金额
				$CZYID=$arr["YSID"];//操作员ID
				$BRID=$arr["PID"];//病人ID
				$FBID =$arr["DEPTNAME"];//费别
				$YBH =$arr["YSXM"];//医保号
				$YHCL=$arr["HYZJ"];//优惠策略
				$JSXMIDS=$arr["JZID"];//项目ID
				$TRADE = $arr["TRADE"];//商户订单号	
				$paramOrg = "serviceId{=}WXGD00061{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
				$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD>
				<MESSAGE>
					<VERSION>1</VERSION>
				</MESSAGE>
				<DATA>
					<BEAN>
					<BRID>".$BRID."</BRID>	
					<JZH>".$JZH."</JZH>	
					<ZFXX>".$arr["ZDMC"]."</ZFXX>					
					<CZRYID>".$CZYID."</CZRYID>	
					<YBH>".$YBH."</YBH>
					<FBID>".$FBID."</FBID>	
					<YHCL>".$YHCL."</YHCL>	
					<DJH>".$DJH."</DJH>		
					<FPH>".$FPH."</FPH>		
					<JSXMIDS>".$JSXMIDS."</JSXMIDS>		
					<ZFFS>2</ZFFS>
					<SHDDH>".$TRADE."</SHDDH>
					<XM>".$arr["HZXM"]."</XM>			
					<JE>".$ZJE."</JE>					
					<OPENID>".$arr["OPENID"]."</OPENID>			
					</BEAN>
				</DATA>
			   </WSD>";
			}
			if($arr["LX"]=="7"){//his窗口住院预缴
				Log::DEBUG($arr["TRADE"]."  ZYYJ : his窗口住院预缴");
				$paramOrg = "serviceId{=}WXGD00062{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
				$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD>
				<MESSAGE>
					<VERSION>1</VERSION>
				</MESSAGE>
				<DATA>
					<BEAN>
					<BRID>".$arr["PID"]."</BRID>	
					<ZYH>".$arr["BLH"]."</ZYH>					
					<CZRYID>".$arr["YSID"]."</CZRYID>	
					<KSID>".$arr["DEPTNAME"]."</KSID>
					<DJH>".$arr["PHONE"]."</DJH>		
					<PJH>".$arr["GHXXID"]."</PJH>		
					<JKDW>".$arr["HYZJ"]."</JKDW>		
					<ZFFS>2</ZFFS>
					<SHDDH>".$arr["TRADE"]."</SHDDH>
					<JKRXM>".$arr["HZXM"]."</JKRXM>		
					<YJJE>".$arr["MONEY"]."</YJJE>					
					<OPENID>".$arr["OPENID"]."</OPENID>			
					</BEAN>
				</DATA>
			   </WSD>";
			}			
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));		
			$data = $result->return; 
			Log::DEBUG($arr["TRADE"]."   call back ：0000000000 : " . $data);
			$queryHis = new SimpleXMLElement($data);
			$JD = $queryHis->DATA;
			Log::DEBUG($arr["TRADE"]."   call back : his交互接口返回结果   ".$JD);
			$Returndata=json_decode($JD,true);
			//若返回的数据不为空执行后续逻辑，如果为空返回error，进入查询环节			
			if($Returndata["CODE"]=="0"){			
			  	 $hisflag = "SUCCESS";	
			  	 $GLOBALS['MSG']=$Returndata["MSG"];			
			}else if($Returndata["CODE"]=="-1"){					
				 $hisflag = "fail";
				 $GLOBALS['MSG']=$Returndata["MSG"];
				 if(strstr($Returndata["MSG"],'项目id串不匹配')){
				 	$GLOBALS['MSG']='病人处方已发生变更,请与开处方医生确认！';
				 }else if(strstr($Returndata["MSG"],'11100003')){
				 	$GLOBALS['MSG']='发票号已被使用！';
				 }else if(strstr($Returndata["MSG"],'11100004')||strstr($Returndata["MSG"],'11100005')){
				 	$GLOBALS['MSG']='病人费用发生改变，请重新获取病人收费信息！';
				 }	
			}else{
				$GLOBALS['MSG']=$Returndata["MSG"];
				Log::DEBUG($arr["TRADE"]."  his交互接口返回为空异常   ".$Returndata["MSG"]);
			}
		}catch(Exception $e){
			$GLOBALS['MSG']=$e->getMessage();
			Log::DEBUG($arr["TRADE"]."  his 交互  Exception : " . $e->getMessage());
		}
		return $hisflag;
	}
    
    	//his系统查询接口
	 function hisquery($arr){
		Log::DEBUG($arr["TRADE"]."   进入平台his查询接口");
		$hisflag = "error";
		try { 
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			$paramOrg = "serviceId{=}WXGD00042{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			//住院查询
			if($arr["LX"]=="2"||$arr["LX"]=="7"){
				Log::DEBUG($arr["TRADE"]."  WXGD00042 hospital : 进入平台住院缴费是否成功结果查询接口");
				$trade = $arr["TRADE"];//商户订单号
				$zyh = $arr["BLH"];//门诊号或者住院号
				$money = $arr["MONEY"];//缴费金额				
			    $dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN><TRADE>".$trade."</TRADE><JFJE>".$money."</JFJE><ZYH>".$zyh."</ZYH><LX>".$arr["LX"]."</LX></BEAN></DATA></WSD>";
			}
			//门诊查询
			if($arr["LX"]=="1"||$arr["LX"]=="3"||$arr["LX"]=="6"){
				Log::DEBUG($arr["TRADE"]."  WXGD00042 MZ  : 进入门诊缴费是否成功结果查询接口");
				$trade = $arr["TRADE"];//商户订单号
				$money = $arr["MONEY"];//缴费金额
				$mzh = $arr["BLH"];//门诊号
				$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN><TRADE>".$trade."</TRADE><JFJE>".$money."</JFJE><MZH>".$mzh."</MZH><LX>".$arr["LX"]."</LX></BEAN></DATA></WSD>";
			}
			//挂号查询
			if($arr["LX"]=="0"){
				Log::DEBUG($arr["TRADE"]."  WXGD00042 YYGH : 进入平台预约挂号是否成功结果查询接口");
				$PID  = $arr["PID"];//病人ID
				$YSID = $arr["YSID"];//医生ID
				$KSID = $arr["GHXXID"];//科室ID
				$GHF = $arr["GHF"];//挂号费
				$YYRQ = $arr["YYRQ"];//预约日期
				$PBZXID = $arr["PBZXID"];//排班执行id
				$TRADE = $arr["TRADE"];//商户订单号
				$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD>
				<MESSAGE>
					<VERSION>1</VERSION>
				</MESSAGE>
				<DATA>
					<BEAN>
					<PID>".$PID."</PID>		
					<YSID>".$YSID."</YSID>		
					<KSID>".$KSID."</KSID>			
					<JFJE>".$GHF."</JFJE>		
					<YYSJ>".$YYRQ."</YYSJ>
					<PBZXID>".$PBZXID."</PBZXID>		
					<TRADE>".$TRADE."</TRADE>
					<LX>".$arr["LX"]."</LX>		
					</BEAN>
				</DATA>
			   </WSD>";
			}
			if($arr["LX"]=="4"){
				Log::DEBUG($arr["TRADE"]."  WXGD00042 gh  : 进入his窗口缴费是否成功结果查询接口");
				$trade = $arr["TRADE"];//商户订单号
				$money = $arr["MONEY"];//缴费金额
				$mzh = $arr["BLH"];//门诊号
				$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN><TRADE>".$trade."</TRADE><JFJE>".$money."</JFJE><MZH>".$mzh."</MZH><LX>".$arr["LX"]."</LX></BEAN></DATA></WSD>";
			}
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));		
			$data = $result->return; 
			Log::DEBUG("WXGD00042 call back: " . $data);
			$queryHis = new SimpleXMLElement($data);
			$JD = $queryHis->DATA;
			Log::DEBUG($arr["TRADE"]."  WXGD00042 : 接口查询解析结果   ".$JD);
			$Returndata=json_decode($JD,true);		
			if($Returndata["CODE"]=="0"){
				 $GLOBALS['MSG']=$Returndata["MSG"];				
			  	 $hisflag = "SUCCESS";	
			}else{
				$GLOBALS['MSG']="his返回为空，异常处理";	
				Log::DEBUG($arr["TRADE"]."  WXGD00042 查询接口缴费失败  ".$Returndata["MSG"]);
			}			
		} catch (Exception $fault){ 
			    $GLOBALS['MSG']=$fault->getMessage();	
				//记录日志   
				Log::DEBUG($arr["TRADE"]."   WXGD00042 hisquerys Exception :" . "页面异常信息：" . $fault->getMessage()); 		
		}
		return $hisflag;
	}
	
    
	//查询登记表用户各项信息
	function queryinfo($trade){
		try{
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			$paramOrg = "serviceId{=}WXGD00005{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN><TRADE>".$trade."</TRADE></BEAN></DATA></WSD>";
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));
			
			$data = $result->return; 
			Log::DEBUG($trade."  queryinfo：查询订单日志表 : " . $data);
			$queryZh = new SimpleXMLElement($data);
			$JD = $queryZh->DATA;
			$arr=json_decode($JD,true);
			return $arr;				
		}catch(Exception $e){
			//微信推送管理员
			$FIRSTVALUE="管理员，支付宝支付时，notify中查询订单时，跳入了异常，异常订单号为：".$trade." 异常原因：".$e->getMessage();
			$LASTVALUE="请及时查看相关情况";
			$KEYWORD='[{"VALUE":"","COLOR":""},{"VALUE":"","COLOR":""},{"VALUE":"'.$trade.'","COLOR":"#173177"}]';
			AttentionToGLY($trade,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
			AttentionToGLY($trade,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
			Log::DEBUG($trade."  queryinfo：查询订单日志 Exception : " . $e->getMessage());
		}
	}
	
	//处理并发问题
	function queryWxHis($lx,$trade){
		try{
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			$paramOrg = "serviceId{=}WXGD00073{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN><LX>".$lx."</LX><TRADE>".$trade."</TRADE></BEAN></DATA></WSD>";
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));			
			$data = $result->return; 
			Log::DEBUG($trade."  WXGD00073：并发问题 : " . $data);
			$queryZh = new SimpleXMLElement($data);
			$JD = $queryZh->DATA;
			$arr=json_decode($JD,true);
			return $arr;				
		}catch(Exception $e){
			Log::DEBUG($trade."  WXGD00073：并发问题  Exception : " . $e->getMessage());
		}
	}
	
	//修改缴费状态
	 function updateinfo($trade,$flag,$msg){
		$updateJf = "error";
		try{
			
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			$paramOrg = "serviceId{=}WXGD00004{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN><FLAG>".$flag."</FLAG><TRADE>".$trade."</TRADE><MSG>".$msg."</MSG><GHHM></GHHM><PAYTYPE>1</PAYTYPE></BEAN></DATA></WSD>";
		
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));
			
			$data = $result->return; 
			$hisbc= new SimpleXMLElement($data);
			$histt = $hisbc->DATA;
			$arr=json_decode($histt,true);			
			if($arr["BEAN"]=="更新成功"){				
				Log::DEBUG($trade."   updateinfo ：修改缴费状态为".$flag." " . $data);
				$updateJf = "SUCCESS";
				
			}else{
				Log::DEBUG($trade."   updateinfo ： 修改缴费状态失败" . $data);
			}				
		}catch(Exception $e){
			Log::DEBUG($trade."   updateinfo Exception 修改缴费状态异常 : " . $e->getMessage());
		}
		return $updateJf;
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
			$LASTCOLOR="#f509f9";		
		}else{
			$FIRSTCOLOR="#FF0000";
			$LASTCOLOR="";
			$openid=$arr["OPENID"];
			$PHONE=$arr["YHSJH"];
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
	
	//发送短信方法
	 function phoneConfig($arr,$zfsfcg,$mzh,$trade){
	 	
	 	$phone=null;
		 if($zfsfcg=="0"){//交互失败短信提示
			if($arr["LX"]=="3"){
				$phone = $arr["PHONE"];
				$content ="尊敬的用户，你好，十分抱歉，门诊扫码支付宝支付失败，失败原因为:".$GLOBALS['MSG']."，所缴费用将会在三个工作日内原路退回，届时请查收。";				
			} 
			if($arr["LX"]=="4"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口挂号扫码支付宝支付失败，失败原因为:".$GLOBALS['MSG']."，所缴费用将会在三个工作日内原路退回，届时请查收。";				
			}
			if($arr["LX"]=="6"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口门诊扫码支付宝支付失败，失败原因为:".$GLOBALS['MSG']."，所缴费用将会在三个工作日内原路退回，届时请查收。";							
			}
			if($arr["LX"]=="7"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口住院扫码支付宝支付失败，失败原因为:".$GLOBALS['MSG']."，所缴费用将会在三个工作日内原路退回，届时请查收。";							
			}
			
		 }else{//缴费异常
		 	if($arr["LX"]=="3"){
				$phone = $arr["PHONE"];
				$content ="尊敬的用户，你好，十分抱歉，门诊扫码支付宝支付异常，金额：".$arr["MONEY"]."，门诊号为:".$arr["BLH"]."，异常原因为:".$GLOBALS['MSG']."，订单号为：".$trade."，您可到窗口核对情况，若确认失败，可重新缴费！医院核对后，若需退款，费用将在三日内原路退回！";				
			}
			if($arr["LX"]=="4"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口挂号扫码支付宝支付异常，金额：".$arr["MONEY"]."，就诊号为:".$arr["BLH"]."，异常原因为:".$GLOBALS['MSG']."，订单号为：".$trade."，您可到窗口核对情况，若确认失败，可重新缴费！医院核对后，若需退款，费用将在三日内原路退回！";				
			}
			if($arr["LX"]=="6"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口门诊扫码支付宝支付异常，金额：".$arr["MONEY"]."，就诊号为:".$arr["BLH"]."，异常原因为:".$GLOBALS['MSG']."，订单号为：".$trade."，您可到窗口核对情况，若确认失败，可重新缴费！医院核对后，若需退款，费用将在三日内原路退回！";							
			}
			if($arr["LX"]=="7"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口住院扫码支付宝支付异常，金额：".$arr["MONEY"]."，住院号为:".$arr["BLH"]."，异常原因为:".$GLOBALS['MSG']."，订单号为：".$trade."，您可到窗口核对情况，若确认失败，可重新缴费！医院核对后，若需退款，费用将在三日内原路退回！";							
			}

		 }
		 
		 if($mzh!=null){
				$phone = $mzh;
				$content ="商户订单号：".$trade."，失败原因为：".$GLOBALS['MSG']."，手机号：".$mzh."，诊疗卡号：".$arr["CARDNUM"]."，住院号：".$arr["BLH"].$arr["LX"]."(0挂号，1门诊，2住院)该笔订单缴费失败，请及时查看处理,广德测试";
			}
		 if($phone!=null){
		 	 $return=http_curl($phone,$content);
		 } 	 	
		  Log::DEBUG($trade."  ".$phone." phone call back : " . $return);
		
	}	
	
	function http_curl($PHONE,$CONTENT){ 	
	  $CONTENT =$CONTENT.'【弋矶山医院广德分院】';
	  $CONTENT=urlencode($CONTENT);
	  $url = "http://172.16.2.117:9999/GdServiceBus/restful/HealthHelper/SendMess?PHONE={$PHONE}&CONTENT={$CONTENT}";  	
      $curlobj = curl_init();
      curl_setopt($curlobj, CURLOPT_URL, $url);
      curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, FALSE);
      $output = curl_exec($curlobj);
      curl_close($curlobj);  
      echo $output;
      return $output;
    } 	
?>

