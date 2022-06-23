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
    
        /*设置多图文内容方法*/
    private function getNewsContent(){
                $contentStr = [];
        
                $contentStr[0]=array(
                    'title' =>'欢迎关注健康池州公众号！',
                    'description' =>'',
                    'picUrl' => 'https://www.jiankangchizhou.com/_weixin/dist/public/weixin/image/yyxx/chizhou_jiuhuashan.png',
                    'url' =>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1bf9a40958070470&redirect_uri=https%3A%2F%2Fwww.jiankangchizhou.com%2F_weixin%2Fdist%2F%23%2Findex&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'
                );
                
                $contentStr[1]=array(
                    'title' =>'【健康池州】功能介绍',
                    'description' =>'',
                    'picUrl' => 'https://www.jiankangchizhou.com/_weixin/dist/public/weixin/image/yyxx/chizhou_icon.png',
                    'url' =>'https://www.jiankangchizhou.com/_weixin/dist/public/weixin/image/yyxx/功能介绍.png'
                );
                
                $contentStr[2]=array(
                    'title' =>'立即注册',
                    'description' =>'',
                    'picUrl' => 'https://www.jiankangchizhou.com/_weixin/dist/public/weixin/image/yyxx/chizhou_zhuce.png',
                    'url' =>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1bf9a40958070470&redirect_uri=https%3A%2F%2Fwww.jiankangchizhou.com%2F_weixin%2Fdist%2F%23%2Fregister_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'
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
                
                //$contentStr = "您好，欢迎关注健康池州！当前帐号尚在部署内测期间，暂不对外开放，敬请期待！";
                //$resultStr = $this->responseText($object, $contentStr);
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