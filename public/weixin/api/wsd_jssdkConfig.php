<?php
header("Content-type: text/html; charset=utf-8");
session_start();
/*include ("Http_Curl.php");*/
require_once "wsd_jssdk.php";
$jssdk = new JSSDK("wx8a3fcccace1a1530", "57b77a49cc4f75357210f632adb10ee9");
$url = $_GET['url'];
$signPackage = $jssdk->GetSignPackage($url);

$data = [];
$data['timestamp'] = $signPackage["timestamp"];
$data['nonceStr'] = $signPackage["nonceStr"];
$data['signature'] = $signPackage["signature"];
$data['appId'] = $signPackage["appId"];
 
$return = array(
     "info"=>base64_encode(json_encode($data))
  );
echo json_encode($return);
?>

 					

