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
$URL="http://113.246.243.65:9998/EasyOnlineService/services/hsWebService?wsdl";  //全局URL

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
			if($arr["LX"]=="5"){
				if($arr["FLAG"]!="0"){
	     			$this->updateinfo($arr["TRADE"],"0","自助机支付成功");
	     			Log::DEBUG($arr["TRADE"]."  缴费成功-------------------------------------------------");						
	     		}
			}
			//判断医院业务逻辑状态，防止微信回调再次通知进入医院逻辑
			else if($arr["FLAG"]!="3"&&$arr["FLAG"]!="2"&&$arr["FLAG"]!="0"&&$arr["FLAG"]!="4"){					
			   //修改缴费状态为处理中3
			   $Three=$this->updateinfoMODIFY($arr["TRADE"],"3");
			   if($Three==1){
				//his系统交互
				$hisin = $this->hiseach($arr,$data["openid"]);
				Log::DEBUG($data["out_trade_no"]."  his交互返回最终结果 标识:".$hisin);
				
				$zfsfcg = "1";//默认成功
				
				//若his交互异常，循环查询交互结果
				if($hisin=="error"){
				   $zfsfcg = "error";
				}
				
				//his返回信息，交互失败，直接退费
				if($hisin=="fail"){
					$zfsfcg = "0";
				}
				
				
				//判断his支付是否成功保存缴费信息或者退费
				if($zfsfcg == "1"){
					Log::DEBUG($arr["TRADE"]."  缴费成功-------------------------------------------------");
					$this->updateinfo($arr["TRADE"],"0",$GLOBALS['MSG']);//修改缴费状态为成功0
				}
				else if ($zfsfcg == "0"){
					Log::DEBUG($arr["TRADE"]."  缴费失败-------------------------------------------------");
					$this->updateinfo($arr["TRADE"],"2",$GLOBALS['MSG']);//修改缴费状态为失败2					
					//发送缴费失败信息给管理员
					$FIRSTVALUE="管理员，微信支付，就诊号为:".$arr["BLH"]."，缴费失败，失败原因为：".$GLOBALS['MSG'];
			        $LASTVALUE="用户手机号码为：".$arr["YHSJH"]."，请及时核对情况。";
			        if($arr["LX"]=="4"||$arr["LX"]=="6"||$arr["LX"]=="7"){
			        	$LASTVALUE="用户手机号码为：".$arr["ZSID"]."，请及时核对情况。";
					}
			        $KEYWORD='[{"VALUE":"'.$arr["HZXM"].'","COLOR":"#173177"},{"VALUE":"'.$arr["MONEY"].'","COLOR":"#173177"},{"VALUE":"'.$arr["TRADE"].'","COLOR":"#173177"}]';
			        //$this->AttentionToGLY($arr["TRADE"],null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
			        //$this->AttentionToGLY($arr["TRADE"],null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);	
			      
                    //发送失败短信给用户：若为移动端支付，则不发送短信，因为已有微信推送功能 若为扫码支付，不确定用户是否关注，都发送短信提示
					$this->phoneConfig($arr,$zfsfcg,null,$data["out_trade_no"]);//发送短信给用户
  				    
				}else{
					Log::DEBUG($arr["TRADE"]."  缴费异常-------------------------------------------------");
					$this->updateinfo($arr["TRADE"],"4",$GLOBALS['MSG']);//缴费异常
					
					//发送缴费异常信息给管理员
					$FIRSTVALUE="管理员，微信支付，就诊号为:".$arr["BLH"]."，缴费异常，异常原因为：".$GLOBALS['MSG'];
			        $LASTVALUE="用户手机号码为：".$arr["YHSJH"]."，请及时核对情况。";
			        if($arr["LX"]=="4"||$arr["LX"]=="6"||$arr["LX"]=="7"){
			        	$LASTVALUE="用户手机号码为：".$arr["ZSID"]."，请及时核对情况。";
					}
			        $KEYWORD='[{"VALUE":"'.$arr["HZXM"].'","COLOR":"#173177"},{"VALUE":"'.$arr["MONEY"].'","COLOR":"#173177"},{"VALUE":"'.$arr["TRADE"].'","COLOR":"#173177"}]';
			        //$this->AttentionToGLY($arr["TRADE"],null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
			        //$this->AttentionToGLY($arr["TRADE"],null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);	
			        $this->phoneConfig($arr,$zfsfcg,null,$data["out_trade_no"]);//发送短信给用户
   				    //缴费异常 推送给用户
  				    $FIRSTVALUE="您好，您有一笔费用缴费异常，异常原因：".$GLOBALS['MSG']." 您可到窗口核对情况，若确认失败，可重新缴费！医院核对后，若需退款，费用将在三日内原路退回！";
			        $LASTVALUE="感谢您的配合，祝您身体健康！";
			        $KEYWORD='[{"VALUE":"'.$arr["HZXM"].'","COLOR":"#173177"},{"VALUE":"'.$arr["MONEY"].'","COLOR":"#173177"},{"VALUE":"'.$arr["TRADE"].'","COLOR":"#173177"}]';
			        $this->AttentionToGLY($arr["TRADE"],null,$arr["OPENID"],null,null,"user",$FIRSTVALUE,$LASTVALUE,$KEYWORD);

				}
			 }
			}		
		}
		Log::DEBUG($data["out_trade_no"]."  &&&&&&&微信回调通知成功&&&&&&&&&&&&&&&&");
		return true;
	}
	
	
	
	//查询登记表用户各项信息
	public function queryinfo($trade){
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
			$queryZh = new SimpleXMLElement($data);
			$JD = $queryZh->DATA;
			Log::DEBUG($trade."  queryinfo：查询订单日志表 : " . $JD);
			$arr=json_decode($JD,true);
			return $arr;				
		}catch(Exception $e){
			//微信推送管理员
			$FIRSTVALUE="管理员，微信支付时，notify中查询订单时，跳入了异常，异常订单号为：".$trade." 异常原因：".$e->getMessage();
			$LASTVALUE="请及时查看相关情况";
			$KEYWORD='[{"VALUE":"","COLOR":""},{"VALUE":"","COLOR":""},{"VALUE":"'.$trade.'","COLOR":"#173177"}]';
			//$this->AttentionToGLY($trade,null,"o2akw1AP65rONmWNa4sRuYCi1-DU",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);
			//$this->AttentionToGLY($trade,null,"o2akw1HXE3ZIr5OmIZERzscn_2ZE",null,null,"admin",$FIRSTVALUE,$LASTVALUE,$KEYWORD);		
			Log::DEBUG($trade."  queryinfo：查询订单日志 Exception : " . $e->getMessage());
		}
	}
	
	
	
	//his系统交互接口
	public function hiseach($arr,$openid){
		$hisflag = "error";
		try{
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			if($arr["LX"]=="0"){//移动端挂号
				Log::DEBUG($arr["TRADE"]."  YYGH : 进入his挂号交互接口");			
				$paramOrg = "serviceId{=}WXBM00006{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
				$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD>
				<MESSAGE>
					<VERSION>1</VERSION>
				</MESSAGE>
				<DATA>
					<BEAN>
					<HZXM>".$arr["HZXM"]."</HZXM>	
					<GHKS>".$arr["DEPTNAME"]."</GHKS>
					<GHYS>".$arr["YSXM"]."</GHYS>
					<YYSD>".$arr["YYSD"]."</YYSD>	
					<TIMERANGE>".$arr["HYZJ"]."</TIMERANGE>	
					<OPENID>".$arr["OPENID"]."</OPENID>		
					<ORG_ID>".$arr["ORG_ID"]."</ORG_ID>		
					<YSID>".$arr["YSID"]."</YSID>		
					<KSID>".$arr["GHXXID"]."</KSID>				
					<PBLX>".$arr["JZID"]."</PBLX>
					<TRADETYPE>1</TRADETYPE>		
					<CARDNUM>". $arr["CARDNUM"]."</CARDNUM>
					<CARDTYPE>".$arr["CARDTYPE"]."</CARDTYPE>		
					<GHF>".$arr["GHF"]."</GHF>		
					<YYRQ>". $arr["YYRQ"]."</YYRQ>
					<NUMBERID>".$arr["PBZXID"]."</NUMBERID>		
					<TRADE>".$arr["TRADE"]."</TRADE>	
					</BEAN>
				</DATA>
			   </WSD>";
			}				
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));		
			$data = $result->return; 
			$queryHis = new SimpleXMLElement($data);
			$JD = $queryHis->DATA;
			Log::DEBUG($arr["TRADE"]." his交互   ".$JD);
			$Returndata=json_decode($JD,true);
			//若返回的数据不为空执行后续逻辑，如果为空返回error，进入查询环节			
			if($Returndata["CODE"]=="0"){			
			  	 $hisflag = "SUCCESS";	
			  	 $GLOBALS['MSG']=$Returndata["MSG"];			
			}else if($Returndata["CODE"]=="-1"){					
				 $hisflag = "fail";
				 $GLOBALS['MSG']=$Returndata["MSG"];			 				 
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

	
	//修改缴费状态
	public function updateinfo($trade,$flag,$msg){
		$updateJf = "error";
		try{
			
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			$paramOrg = "serviceId{=}WXGD00004{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN><FLAG>".$flag."</FLAG><TRADE>".$trade."</TRADE><MSG>".$msg."</MSG><GHHM></GHHM><PAYTYPE>0</PAYTYPE></BEAN></DATA></WSD>";
		
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));
			
			$data = $result->return; 
			$hisbc= new SimpleXMLElement($data);
			$histt = $hisbc->DATA;
			$arr=json_decode($histt,true);
			Log::DEBUG($trade."   updateinfo ：修改缴费状态为".$flag." " .$histt);			
			if($arr["BEAN"]=="更新成功"){				
				$updateJf = "SUCCESS";				
			}			
		}catch(Exception $e){
			Log::DEBUG($trade."   updateinfo Exception 修改缴费状态异常 : " . $e->getMessage());
		}
		return $updateJf;
	}	
	
	//修改缴费状态
	public function updateinfoMODIFY($trade,$flag){
		$updateJf ="error";
		try{		
			libxml_disable_entity_loader(false);
			$client = new SoapClient($GLOBALS['URL']);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8 = false;
			$client->xml_encoding = 'utf-8';
			$paramOrg = "serviceId{=}WXGD00004{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}N";
			$dataOrg = "<?xml version='1.0' encoding='UTF-8'?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA><BEAN><FLAG>".$flag."</FLAG><TRADE>".$trade."</TRADE><MODIFY>88888</MODIFY><PAYTYPE>0</PAYTYPE></BEAN></DATA></WSD>";
		
			$param = array('param'=>$paramOrg, 'data'=>$dataOrg);
			$result = $client->__Call("service", array($param));			
			$data = $result->return; 
			$hisbc= new SimpleXMLElement($data);
			$histt = $hisbc->DATA;
			Log::DEBUG($trade."   updateinfoMODIFY 修改为3处理中 : " .$histt);
			$arr=json_decode($histt,true);	
			$updateJf=$arr["BEAN"];						
		}catch(Exception $e){
			Log::DEBUG($trade."   updateinfoMODIFY Exception 修改缴费状态异常 : " . $e->getMessage());
		}
		return $updateJf;
	}	
	
	//his系统查询接口
	public function hisquery($arr){
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
			$queryHis = new SimpleXMLElement($data);
			$JD = $queryHis->DATA;
			Log::DEBUG($arr["TRADE"]."  WXGD00042 : 接口查询解析结果   ".$JD);
			$Returndata=json_decode($JD,true);		
			if($Returndata["CODE"]=="0"){
				 $GLOBALS['MSG']=$Returndata["MSG"];			
			  	 $hisflag = "SUCCESS";	
			}else if($Returndata["CODE"]=="-1"){
				 $GLOBALS['MSG']="his接口返回为空，异常处理";//平台未查到，不敢确认就是缴费失败
				 Log::DEBUG($arr["TRADE"]."  WXGD00042 查询接口缴费失败  ".$Returndata["MSG"]);
			}else{
				 $GLOBALS['MSG']="缴费异常";
			}			
		} catch (Exception $fault){
			    $GLOBALS['MSG']=$fault->getMessage();	 
				//记录日志   
				Log::DEBUG($arr["TRADE"]."   WXGD00042 hisquerys Exception :" . "页面异常信息：" . $fault->getMessage()); 		
		}
		return $hisflag;
	}
	

	//缴费异常提醒  管理员
	//mess  不为空发送短信
	//temp  不为空发送模板消息
	public function AttentionToGLY($Trade,$arr,$openid,$phone,$MESS,$User,$FIRSTVALUE,$LASTVALUE,$KEYWORD){
		$temp=null;
		if(empty($MESS)){//如果为空  则发送模板消息
			$temp='ZFPt9ux1BqyOxMLukHUpCfSet8y2zy1OA-8Otfhzm9s';
		}
		if($User=="admin"){
			$FIRSTCOLOR="#f509f9";
			$LASTCOLOR="#f509f9";		
		}else{
			$FIRSTCOLOR="";
			$LASTCOLOR="";
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
			Log::DEBUG($Trade."  ".$openid." 缴费异常推送结果 :".$JD);				
		}catch(Exception $e){
			Log::DEBUG($Trade."  ".$openid." 缴费异常推送异常 Exception : " . $e->getMessage());
		}
	}
	
	//发送短信方法
	public function phoneConfig($arr,$zfsfcg,$glyphone,$trade){
		
		$phone=null;
		//交互成功短信提示 暂不提示 移动端已有推送，窗口扫码支付 若成功，操作员就知道，则会打印发票给用户，不再次发送短信
//		if($zfsfcg=="1"){
//			$phone = $arr["YHSJH"];
//		}
		if($zfsfcg=="0"){//交互失败短信提示   微信端已有推送 不发送，扫码支付提醒支付失败
			//3门诊扫码支付  4 挂号扫码支付  5 自助机扫码支付  6 门诊窗口扫码支付  7 住院窗口扫码支付
			if($arr["LX"]=="3"){
				$phone = $arr["PHONE"];
				$content ="尊敬的用户，你好，十分抱歉，门诊扫码支付失败，失败原因为:".$GLOBALS['MSG']."，所缴费用将会在三个工作日内原路退回，届时请查收。";				
			}
			if($arr["LX"]=="4"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口挂号扫码支付失败，失败原因为:".$GLOBALS['MSG']."，所缴费用将会在三个工作日内原路退回，届时请查收。";				
			}
			if($arr["LX"]=="6"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口门诊扫码支付失败，失败原因为:".$GLOBALS['MSG']."，所缴费用将会在三个工作日内原路退回，届时请查收。";							
			}
			if($arr["LX"]=="7"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口住院扫码支付失败，失败原因为:".$GLOBALS['MSG']."，所缴费用将会在三个工作日内原路退回，届时请查收。";							
			}	
		 }
		 
		 if($zfsfcg=="error"){//缴费异常
		 	if($arr["LX"]=="3"){
				$phone = $arr["PHONE"];
				$content ="尊敬的用户，你好，十分抱歉，门诊扫码微信支付异常，金额：".$arr["MONEY"]."，门诊号为:".$arr["BLH"]."，异常原因为:".$GLOBALS['MSG']."，订单号为：".$trade."，您可到窗口核对情况，若确认失败，可重新缴费！医院核对后，若需退款，费用将在三日内原路退回！";				
			}
			if($arr["LX"]=="4"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口挂号扫码微信支付异常，金额：".$arr["MONEY"]."，就诊号为:".$arr["BLH"]."，异常原因为:".$GLOBALS['MSG']."，订单号为：".$trade."，您可到窗口核对情况，若确认失败，可重新缴费！医院核对后，若需退款，费用将在三日内原路退回！";				
			}
			if($arr["LX"]=="6"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口门诊扫码微信支付异常，金额：".$arr["MONEY"]."，就诊号为:".$arr["BLH"]."，异常原因为:".$GLOBALS['MSG']."，订单号为：".$trade."，您可到窗口核对情况，若确认失败，可重新缴费！医院核对后，若需退款，费用将在三日内原路退回！";							
			}
			if($arr["LX"]=="7"){
				$phone = $arr["ZSID"];
				$content ="尊敬的用户，你好，十分抱歉，窗口住院扫码微信支付异常，金额：".$arr["MONEY"]."，住院号为:".$arr["BLH"]."，异常原因为:".$GLOBALS['MSG']."，订单号为：".$trade."，您可到窗口核对情况，若确认失败，可重新缴费！医院核对后，若需退款，费用将在三日内原路退回！";							
			}
		 }
		 
		 if($glyphone!=null){//发送给管理员
		 	$phone=$glyphone;
		 	$content="微信 支付 商户订单号：".$trade."  金额：".$arr['MONEY']."，异常原因为：".$GLOBALS['MSG']."，该笔订单缴费异常，请及时查看处理。";
		 }	
		 if($phone!=null){
		 	 $return=$this->http_curl($phone,$content);
		 } 	 	 
		 Log::DEBUG($trade."  ".$phone."  phone call back : " . $return);	
	}	
   
     public function http_curl($PHONE,$CONTENT){ 	
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

	
}



Log::DEBUG("begin notify************************************");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
