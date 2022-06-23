<?php
include("access_token.php");
//echo $access_token;
$menu='
{
    "button": [  
{
		   "type": "view",
           "name": "科普视频",
           "url": "https://m.pule.com/coop/hnwisdom_chizhou/"

        }, 	
             
          {
		   "type": "view",
           "name": "健康大厅",
           "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1bf9a40958070470&redirect_uri=https%3A%2F%2Fwww.jiankangchizhou.com%2F_weixin%2Fdist%2F%23%2Findex&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect"

        }, 
        {
		   "type": "view",
           "name": "个人中心",
           "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1bf9a40958070470&redirect_uri=https%3A%2F%2Fwww.jiankangchizhou.com%2F_weixin%2Fdist%2F%23%2Fgrzx_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect"
        }
    ]
}';

/*
个人中心地址
https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0ad3a04e2b125457&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2Fweixin%2Fmenu%2FmenuCheck%2FUserCheck.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect
查询报告地址
https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0ad3a04e2b125457&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2Fweixin%2Fmenu%2FmenuCheck%2FQueryRportCheck.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect
档案查询地址
https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0ad3a04e2b125457&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2Fweixin%2Fmenu%2FmenuCheck%2FFileCheck.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect
分诊叫号地址
https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0ad3a04e2b125457&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2Fweixin%2Fmenu%2FmenuCheck%2FTriageStationCheck.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect

		{
						"type": "view",
						"name": "智能导诊调试",
						"url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fguidance&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect"
				} ,
				{
						"type": "view",
						"name": "预约挂号调试",
						"url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe2645b034a5b7480&redirect_uri=http%3A%2F%2Fwww.gdxyy.org.cn%2F_weixin%2Fdist%2F%23%2Fyygh_index&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect"
				},
*/

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $menu);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$tmpInfo = curl_exec($ch);
if (curl_errno($ch)) {
  echo curl_error($ch);
}

curl_close($ch);
echo $tmpInfo;


?>