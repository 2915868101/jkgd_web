<?php
header("Content-type: text/html; charset=utf-8");	
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once 'log.php';
//获取ajax请求的日志信息
$message = $_POST["message"];
/*echo $message;*/
//初始化日志
$logHandler= new CLogFileHandler("../../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//记录日志
Log::DEBUG("jsapi:" . $message);

?>
