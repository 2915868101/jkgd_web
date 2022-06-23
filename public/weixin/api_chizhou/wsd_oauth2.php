<?php
  header("Content-type: text/html; charset=utf-8");
  session_start();
  $code=$_GET["code"];
  $appid="wx1bf9a40958070470";//填写appid
  $secret="14887f8c6107ca324d43fecc5e6d3f44";//填写secret
  $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
    //curl模拟get请求，获取结果
  $res = http_curl($url);
    //转化为数组
   
    $return_data = json_decode($res, true);
  if(isset($return_data["openid"])){
    $openid=$return_data["openid"];
    $access_token = $return_data['access_token'];
		$_SESSION['openid']=$openid;
    $_SESSION['access_token']=$access_token;
  }else{
    $openid=$_SESSION['openid'];
    $access_token = $_SESSION['access_token'];
  }
  $urL = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
  $res1 = http_curl($urL);
  $return_data1 = json_decode($res1, true);
  if(isset($return_data1["nickname"])){
    $nickname=$return_data1["nickname"];
    $sex=$return_data1["sex"];
    $language=$return_data1["language"];
    $city=$return_data1["city"];
    $province=$return_data1["province"];
    $country=$return_data1["country"];
    $headimgurl=$return_data1["headimgurl"];
	
	  $_SESSION['nickname']=$nickname;
    $_SESSION['sex']=$sex;
    $_SESSION['language']=$language;
    $_SESSION['city']=$city;
    $_SESSION['province']=$province;
    $_SESSION['country']=$country;
    $_SESSION['headimgurl']=$headimgurl;
  }else{
    $nickname=$_SESSION['nickname'];
    $sex=$_SESSION['sex'];
    $language=$_SESSION['language'];
    $city=$_SESSION['city'];
    $province=$_SESSION['province'];
    $country=$_SESSION['country'];
    $headimgurl=$_SESSION['headimgurl'];
  }
  $wx = array(
    "openid"=>$openid,
    "sex"=>$sex,
    "access_token"=>$access_token,
    "nickname"=>$nickname,
    "language"=>$language,
    "city"=>$city,
    "province"=>$province,
    "country"=>$country,
    "headimgurl"=>$headimgurl
  );
  header("content-type:application/json");
  //echo json_encode($wx);
  $wx=json_encode($wx);
  $return = array(
    "info"=>base64_encode($wx)
  );
  echo json_encode($return);
  //curl模拟get请求
  function http_curl($url){
    $curlobj = curl_init();
    curl_setopt($curlobj, CURLOPT_URL, $url);
    curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, FALSE);
    $output = curl_exec($curlobj);
    curl_close($curlobj);
    return $output;
  }
?>