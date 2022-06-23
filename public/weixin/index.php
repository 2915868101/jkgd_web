<?php
define("TOKEN", "gdrmyytoken");//定义TOKEN常量
require_once "SendModel.php";
$wechatObj = new wechatCallbackapiTest();//实例化wechatCallbackapiTest类

/*$_GET["echostr"])随机字符串，实际上这里执行的两种请求：
 * 1：用于验证签名  2：用于响应消息
 * 区分这两种操作的标志就为这个随机字符串，1存在,2不存在
 * */
if(!isset($_GET["echostr"])){
     $wechatObj->responseMsg();
}else{
 	 $wechatObj->valid();
}

/*消息回复业务类*/
class wechatCallbackapiTest
{
 
	private $appId='wxe2645b034a5b7480';
	private $appSecret='444a7718eb68d42d8451cdbbeb1bac53';
     private $accessToken;
    /*验证签名函数*/
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
         header("content-type:text");
         ob_clean();
         echo $echoStr;
         exit;
        }
    }


    /*响应消息函数*/
    public function responseMsg()
    {
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");//获取传入的参数
        if (!empty($postStr)){
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $RX_TYPE = trim($postObj->MsgType);//获取请求的消息数据类型
                switch($RX_TYPE)
                {
                    case "event":
                        $resultStr = $this->receiveEvent($postObj);
                        break;
                    case "text":
                        $resultStr = $this->receiveText($postObj);
                        break;
                    default:
                        $resultStr = "Unknow msg type: ".$RX_TYPE;
                        break;
                }
                echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }
    
        /*设置多图文内容方法*/
    private function getNewsContent(){
                $contentStr = [];
        
                $contentStr[0]=array(
                    'title' =>'点击此处免费办理电子健康卡！',
                    'description' =>'',
                    'picUrl' => 'http://www.gdxyy.org.cn/_weixin/dist/weixin/image/yyxx/dzjkk.jpg',
                    'url' =>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fregister_health_card&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'
                );
                
                $contentStr[1]=array(
                    'title' =>'预约挂号',
                    'description' =>'',
                    'picUrl' => 'http://www.gdxyy.org.cn/_weixin/dist/weixin/image/yyxx/yygh.png',
                    'url' =>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fyygh_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'
                );
                
                $contentStr[2]=array(
                    'title' =>'门诊缴费',
                    'description' =>'',
                    'picUrl' => 'http://www.gdxyy.org.cn/_weixin/dist/weixin/image/yyxx/mzjf.png',
                    'url' =>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fmzjf_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'
                );
                
                $contentStr[3]=array(
                    'title' =>'住院预缴',
                    'description' =>'',
                    'picUrl' => 'http://www.gdxyy.org.cn/_weixin/dist/weixin/image/yyxx/zyyj.png',
                    'url' =>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fzyjf_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'
                );
				
				$contentStr[4]=array(
                    'title' =>'报告查询',
                    'description' =>'',
                    'picUrl' => 'http://www.gdxyy.org.cn/_weixin/dist/weixin/image/yyxx/bgcx.png',
                    'url' =>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fbgjl_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'
                );

                $contentStr[5]=array(
                    'title' =>'体检中心',
                    'description' =>'',
                    'picUrl' => 'http://www.gdxyy.org.cn/_weixin/dist/weixin/image/yyxx/tjyy.png',
                    'url' =>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Ftjzx_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'
                );
            return $contentStr;
    }
    
    
        
    /*关注自动回复消息函数*/
    private function receiveEvent($object)
    {
        $contentStr = [];
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = $this->getNewsContent();//设置图文内容
                $resultStr = $this->responseTotalNews($object, $contentStr);
                           
                 break;   
            case 'CLICK':
				$tmp=$object->EventKey;
                if($tmp=='yygh'||$tmp=='zndz'){
                	 $contentStr ="https://mp.weixin.qq.com/s/AWYJbkPeRfoXPKVpXtnV2w";
                	 $resultStr = $this->responseText($object, $contentStr);
                }
				break; 				
            default :
                $contentStr = "unsubscribe".$object->Event;
                $resultStr = $this->responseText($object, $contentStr);
                break;
        }
        return $resultStr;
    }



    
    
    
    
     
    
     /*关键字接收文本消息*/
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        if(strstr($keyword,"+")||strstr($keyword,"人工")){
	        $content = "您好，已经收到您的回复，我们会尽快处理您的问题，谢谢您的支持与理解！";
	        //推送给管理员
	        $this->accessToken = $this->getToken();
	        $userToken = $this->getToken();  //得到关注用户token
	        $userInfos = $this->getUserInfo($object->FromUserName); //得到用户信息
	        $sd = new SendModel();
	        $sd->send_notice($userToken, "o2akw1AP65rONmWNa4sRuYCi1-DU", $userInfos['nickname'],$keyword);
	        //$sd->send_notice($userToken, "o2akw1DyFUj_5cA35UR29G37LDEU", $userInfos['nickname'],$keyword);

        }else if(strstr($keyword,"专家")||strstr($keyword,"医生")){
        	 $content = "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fyyjs_ys&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>名医介绍</a>";
        }else if(strstr($keyword,"科室")){
        	 $content = "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fyyjs_ks&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>科室介绍</a>";
        }else if(strstr($keyword,"医院")){
        	 $content = "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fyyjs_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>医院信息</a>";
        }else if(strstr($keyword,"体检")){
        	 $content = "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Ftjzx_js&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>体检管理中心介绍</a>";
        }else if(strstr($keyword,"上班时间")||strstr($keyword,"上班")){
		     $content = "上午上班时间：8:00-11:30   下午上班时间：1:30-5:00";
		}else if(strstr($keyword,"签到")||strstr($keyword,"取号")){
			 $content = "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fgrzx_wdgh&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>在线取号</a>";
		}else if(strstr($keyword,"就诊卡")){
			 $content = "暂不支持就诊卡绑定，请使用身份证信息绑定就诊人；\n小孩身份证在窗口建过档且已有就诊卡的情况，请确保在窗口提供了小孩身份证，只提供了家长身份证的情况下，无法关联到小孩档案，请先去窗口补全小孩身份证。\n若不补全，将重新以小孩身份证建档，请再去窗口出示预约记录重新办卡取号";
		}else if(strstr($keyword,"小孩")||strstr($keyword,"宝宝")){
			 $content = "1、孩子如有身份证号，可凭身份证号添加就诊人预约挂号，到窗口出示预约记录办卡取号；\n2、 孩子如没有身份证号，请到门诊现场挂号。";
		}else if(strstr($keyword,"退号")||strstr($keyword,"取消预约")){
			 $content = "请点击菜单栏健康中心--在线取号，选择相应预约记录，点击取消即可。\n 若已取号，请去自助机打印发票后，凭发票去窗口退号退费";
		}else if(strstr($keyword,"预约")||strstr($keyword,"挂号")){
			 $content = " 请点击菜单栏移动就诊--预约挂号，选择就诊人挂号即可。\n 挂号成功后，请于就诊当天提前半小时取号，取号成功后，即可前往就诊科室候诊";
		}	
        else{
           $content = "您好，欢迎关注广德市人民医院，请选择您所需要的服务：\n1、<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fyygh_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>预约挂号</a>\n2、<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fmzjf_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>门诊缴费</a>\n3、<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fzyjf_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>住院预缴</a>\n4、<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fgrzx_wdgh&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>在线取号</a>\n5、<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Ftjzx_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>体检中心</a>\n友情提示，所有的功能都要<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fregister_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>注册</a>后才能使用，您注册时的身份证信息务必实名制，若注册后发现信息有误，请携带身份证去窗口修改。如您还有其他问题，可按格式“人工服务+问题描述”，发送消息到此公众号，我们会第一时间为你处理。同时谢谢您的理解与支持！";
        }
        $result = $this->responseText($object, $content);
        return $result;
    }
    
    
    /*回复多条图文消息函数*/
    private function responseTotalNews($object,$newsContent){
        
        $newsTplHead = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>%d</ArticleCount>
                <Articles>";
                //ArticleCount设置图文条数，最多为十条
        $newsTplBody = "<item>
                <Title><![CDATA[%s]]></Title> 
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>";
        $newsTplFoot = "</Articles>
                <FuncFlag>%d</FuncFlag>
                </xml>";
        
        $newsLength = count($newsContent);
        $bodyCount = $newsLength < 10 ? $newsLength : 10;
            
        $header = sprintf($newsTplHead, $object->FromUserName, $object->ToUserName, time(),$bodyCount);
        
        /*遍历图文*/
        foreach($newsContent as $key => $value){
        $body .= sprintf($newsTplBody, $value['title'], $value['description'], $value['picUrl'], $value['url']);
        }
      
        
        $FuncFlag = 0;
        $footer = sprintf($newsTplFoot, $FuncFlag);
        
        return $header.$body.$footer;
    }
 
  
  
    /*默认消息函数，只提示文字无图文*/
    public function responseText($object, $content, $flag=0)
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        echo $fromUsername;
        return $resultStr;
    }
   
   
   
     /*用户关注后获取用户信息*/
    public function getUserInfo($openid)
    {
     $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $this->accessToken . "&openid=" . $openid;
     $user = $this->http_curl($url);
     //转化为数组
     $return_data = json_decode($user, true);
     /*$city=$return_data["city"];*/
     return $return_data;
     }
    
    /*得到token*/
    protected function getToken()
    {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appId . "&secret=" . $this->appSecret;
      
            $res = $this->http_curl($url);
            //转化为数组
                    $return_data = json_decode($res, true);
            $access_token = $return_data['access_token'];
            
            
                return $access_token;
    }
 
    /* curl模拟get请求*/
    public function http_curl($url){
        $curlobj = curl_init();
            curl_setopt($curlobj, CURLOPT_URL, $url);
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, FALSE);               
            $output = curl_exec($curlobj);
        curl_close($curlobj);
            return $output;
    }
   
    /*检验签名函数*/
    private function checkSignature()
    {
        $signature = $_GET["signature"];//微信加密签名
        $timestamp = $_GET["timestamp"];//时间戳
        $nonce = $_GET["nonce"];//随机数
                
        $token = TOKEN;//token
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);//对三个参数进行字典序排序
        $tmpStr = implode( $tmpArr );//三个参数拼成字符串
        $tmpStr = sha1( $tmpStr );//sha1加密
        /*校验签名*/
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

?>