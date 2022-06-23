<?php
/*模板消息发送类,用于用户发送消息到公众号提示管理员*/
class SendModel
{

	/**
	* 发送模板消息方法
	*/
	public function send_notice($token, $openid, $toUserName,$keyword){
		//模板消息
		$json_template = $this->json_tempalte($openid, $toUserName,$keyword);
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
		$res=$this->curl_post($url,urldecode($json_template));
	}


	/**
	* 将模板消息json格式化
	*/
	public function json_tempalte($openid, $toUserName,$keyword){
		//模板消息
		$template=array(
			'touser'=>$openid,  //用户openid
			'template_id'=>"_zorcQ9H9vKm07udT3tRx-2cvhfwpehJFwkmgyhtLXM", //在公众号下配置的模板id
			'topcolor'=>"#000000",
			'data'=>array(
			'first'=>array('value'=>urlencode("有患者给您留言了:".$keyword),'color'=>"#000000"),
			'keyword1'=>array('value'=>urlencode('留言人：'.$toUserName),'color'=>'#000000'),  //keyword需要与配置的模板消息对应
			'keyword2'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#000000'),
			'remark' =>array('value'=>urlencode('请及时查看回复'),'color'=>'#000000'), )
		);
		$json_template=json_encode($template);
		return $json_template;
	}
 
	/**
	* @param $url
	* @param array $data
	* @return mixed
	* curl发送post请求
	*/
	function curl_post($url , $data=array()){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}
?>