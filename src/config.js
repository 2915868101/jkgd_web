import Vue from 'vue'
/*********=====服务配置=====*********/
//微信api wx_static_api 
//一般请求   serverPath1 &&serverPath4(调测试的)
//健康档案  serverPath2
//电子健康卡  wx_health_card
//是否是健康卡开放平台注册方式serverHealthCard
// public文件夹 => static => config.js
for (var key in window.WSD) {
  Vue.prototype[key] = window.WSD[key]
}
/*********=====服务配置=====*********/