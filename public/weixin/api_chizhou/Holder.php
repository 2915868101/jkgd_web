<?php

ini_set('date.timezone','Asia/Shanghai');
//header('Content-Type:application/json');
//header('Content-type:text/json');  
header('Content-Type:text/html');
$only=strtotime(date('Y-m-d H:i:s'));//获取当前时间戳
$imgname = $only.iconv("UTF-8","gb2312",$_FILES['file']['name']);//拿到上传文件的名称拼接时间戳，文件名唯一
$tmp = $_FILES['file']['tmp_name'];//文件上传存储在服务器的文件的临时副本的名称
$filepath = '../../../PhysicalReportImage/ExpertIMAGE/';//保存地址
//$filepath = 'images/';//保存地址
$type=$_FILES["file"]["type"];//被上传文件的类型

//如果为图片则保存
if(($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")){
	//保存文件
	if(move_uploaded_file($tmp,$filepath.$imgname)){
		
	//	if(($_FILES["file"]["type"] == "image/gif")
	//	|| ($_FILES["file"]["type"] == "image/jpeg")
	//	|| ($_FILES["file"]["type"] == "image/pjpeg")){
			$data = '{"code":0,"msg":"","data":{"src":"http://www.hnwisdom.com/weixintest/PhysicalReportImage/ExpertIMAGE/'.iconv("gb2312","UTF-8",$imgname).'"}}';
//			$back = json_decode($data);
//			var_dump($back);
			
			echo $data;

	//		$data = '{"code":0,"msg":"","data":{"src":"http://www.hnwisdom.com/HealthRecords/images/'.iconv("gb2312","UTF-8",$imgname).'"name":"'.iconv("gb2312","UTF-8",$imgname).'",}}';
	//		$back = json_decode($data);
	//		echo $data;
	//	}
		
	}else{
//	  if(($_FILES["file"]["type"] == "image/gif")
//		|| ($_FILES["file"]["type"] == "image/jpeg")
//		|| ($_FILES["file"]["type"] == "image/pjpeg")){
			$data = '{"code":1,"msg":"图片上传失败","data":{"src":""}}';
			$back = json_decode($data);
			echo $back;
//		}else{
//			$data = '{"code":1,"msg":"文件上传失败","data":{"src":"",name":"",}}';
//			$back = json_decode($data);
//			echo $data;
//		}
	}
}else{
	$data = '{"code":1,"msg":"请上传图片","data":{"src":""}}';
	$back = json_decode($data);
	echo $back;
}

	
?>