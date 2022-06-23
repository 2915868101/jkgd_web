<?php
header("Content-Type: text/html;charset=utf-8");
session_start();
ini_set('date.timezone','Asia/Shanghai');

require_once '../wxpay/example/log.php';

$logHandler=new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

$arr=$_GET;
Log::DEBUG("  挂号入参   ".json_encode($arr));
if($arr['ORG_ID']=="hzdy001"){
	header("Location: https://www.hnwisdom.com/BMFW/dist/public/weixin/huizhoupay/jsapi_gh_bmfw_HuiZhou_dyrmyy.php?Array=".json_encode($arr));
}
if($arr['ORG_ID']=="hzzy001"||$arr['ORG_ID']=="hzzy002"){
	header("Location: https://www.hnwisdom.com/BMFW/dist/public/weixin/huizhoupay/jsapi_gh_bmfw_HuiZhou_hzzyy.php?Array=".json_encode($arr));
}

?>

