<?php
define("TOKEN", "chizhoutoken");//定义TOKEN常量
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
 
	private $appId='wx1bf9a40958070470';
	private $appSecret='14887f8c6107ca324d43fecc5e6d3f44';
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
    
    
    
        
    /*关注自动回复消息函数*/
    private function receiveEvent($object)
    {
        $contentStr = [];
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "您好，欢迎关注健康池州！当前帐号尚在部署内测期间，暂不对外开放，敬请期待！";
                $resultStr = $this->responseText($object, $contentStr);
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
        
        $content = "您好，当前帐号尚在部署内测期间，暂不对外开放，敬请期待！";
        $result = $this->responseText($object, $content);
        return $result;
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