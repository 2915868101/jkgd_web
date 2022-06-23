<?php
$config = array (
		//签名方式,默认为RSA2(RSA2048)
		'sign_type' => "RSA2",

		//支付宝公钥
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwK6EiKM26oXIYbbYvysBYmPGHwZk08v/XyUolrPQTxFspTlLe9g92igM1YTF0nRvEnDDBtsf/ITKk/YlW+JpnLPKymq0RT51a44AetS8H6SgFYfBpQaL7Ape0bgkCa9ZaAAnnYLbHjuD3wVBCUMv4aHHdtIuh7wil4I1cCDP3TD+ENnPCc9IT/tTD7qdyuGwbEE5UsXW9bHPjyemFZTWEPVZsCJLKzfl11v8+rIV9KC5GwROFowgVHL389NHCW4y96QEt1n3BkQxDv5Nb/FUOcmqOt5HYMr2YRl0+7pT0a78tgzPvw2XtGXFXisP3e2Om34aUzx4bb2yOah8SySsXQIDAQAB",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAyA4XcZioqkeBcpMMWcaviM6Dc2Liymi012fbfat8burOsPGkE9EzCVWn8O5alMNCV1HfyiiXlBuG2qhqWdG78wzQ+IBnaF18OaFlI2nxtmq2A6sbU6g4ERXEKsdolX1jLZ2BmXHN4jhwtLvETqZ9OaHh6jZnx33yNSObZSeLpZJ1EQAhkrNQ2o3XyItS8wS2pvewHMPXi0BwfC0RHCGaw6pTAcgkOT6e8byWPenDhBwUoj40FzAzvW/h1P1SNekn/2OOpEHquZJvZ6drjQWEtXRRpONiTbgu8f/1L/4ZV3X20E3IkzC7xcqJ/gGaXzEhlz5enjeM/bONagAkRCVxewIDAQABAoIBAF1cvjIkdWpqTo4i9BudruPgxNX/qymdLfBtJrECa+LzsBxB2TnqYrATinnVMu7EJVzM9+zHft3+7rNy75cVW2p1a7zdZ+T74/KdUK7zTXu9V05k9y7WjbvjdZYlU7iYOMXcANSUmOnfPzIJgvbVmhFgbCc61rnyHCwSfv35M6HD4XxEpU1QJenoW/pCJiqYS6JmsIvKyDlxVqztUxMCgetMpWN3ZivVTXMigOb3jy/KO0J6qyIXHGCExrxZNHTCoJ7xuHXH19+6FBZ82buNCMtNbvtDf6WzZLnYAcxDSHa6mMwLJnh+/+PEtwjMjHJg4Ub/ICLwwscDYTIwxcopokkCgYEA61ifUH2JPZWIIJtw9VZfsq07G0wz0lq3TwIHpGW46lseh+1yyfikyELYyg/UY2+OScIKf6gRx9bC68PrTLBjxk7zynRRwiDASdTkh6AnLpyWJzsWp9cBA9JtuMuTLJUOa9Vkx0Q1CmluM4QQIuQxW40/qX3QXXJLZ4a20sG5RYcCgYEA2Zya5Ga4EGPMBXLGV8eyVJlaH8NRfKvOV58GYUBErE8DMhKgr8a4Pq/CvmHS3XYyNvHdD4UJwpftpLD9hlioEG1lwJef5F4L3DrGb/qjqK6oU6N9rGIu6od2nhYDnWKu7V2PkO0KXurF7a/CGSIUpKbUsDzlU2MlL3EVtVtsMW0CgYEA39kAV2/AHMpgmxseOsx345wvbwxw8fCFEGoO2MhENOyMQp+QIhkUqsIiLzcVPar6vJ8Km1dB6kEUT2mfIedYT2QDMBqpNSsvmkobQAENo6EelJv4NZYkCf2ZjT5ccvgnqltjKsFubcPQJMHFE1rWs4zK86yOhoNSw1p4BEhsr3sCgYAjthqJLv432ljJ8Bryntf6ICx1F/WQcxVqtbMzVHuwl0rb9cie8AqeZvObkKwtIc99ytVPTbLbV1ltdIo2eOgjwgA3yeo4bOKfYpncGJKxBPCkwSjHmFlArbhemxg+xMoWNAtyaiQQjZCrv/MncrtWhfZTcbrko1mf3nwEhhtMRQKBgGKNzkukz0iJY3W00bBPIe4JCNF6FU95fqZDRA24RlVwo9ZWNYnH3t4mAo5Xaqaglip1BOon5XfLFFzJULRLflrMselX141ahORy8vdaq+G0FsL6FtHooVKc1rtFI7h5/goqDJD+y5/BlvG6pefManrL88v351b8kKgfgfM6pMWk",

		//编码格式
		'charset' => "UTF-8",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//应用ID
		'app_id' => "2018052360241158",

		//异步通知地址,只有扫码支付预下单可用
		//'notify_url' => "http://www.hnwisdom.com/HealthRecords/wsd.healthrecords.com.php/dangmianfu_demo_php/notify_url.php",
		'notify_url' => "http://www.hnwisdom.com/HealthRecords/wsd.healthrecords.com.php/dangmianfu_demo_php/notify_url_online_medical.php",

		//最大查询重试次数
		'MaxQueryRetry' => "10",

		//查询间隔
		'QueryDuration' => "3"
);