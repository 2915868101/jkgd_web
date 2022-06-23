import Vue from 'vue'
import wx from 'weixin-js-sdk'
var Base64 = require('js-base64').Base64

Vue.prototype.$wechat = wx

//获取微信用户信息
Vue.prototype.getWxUserInfo = function(cb) {
  var t = this
  t.ajax({
    method: 'get',
    url: t.wx_static_api + 'api/wsd_oauth2.php',
    params: {
      code: t.wechat_code
    },
    success(info) {
      var data = info[0] || {}
      if (data.info) {
        var json = Base64.decode(data.info)
        data = JSON.parse(json)
      }
      // t.$dialog.alert({ 
      //     message: `<div class="f-tal"> 
      //       参数：${JSON.stringify(sessionStorage.wx_userInfo)}
      //       参数：${JSON.stringify(data)}
      //     </div>`
      //   })

      sessionStorage.wx_userInfo = sessionStorage.wx_userInfo || JSON.stringify(data)
      t.$store.state.wx_userInfo = JSON.parse(sessionStorage.wx_userInfo) || data

      console.log(data)
      if (typeof cb == 'function') {
        cb(data)
      }
    },
    error: t.onNetworkError
  })
}
//获取微信 JS-SDK config 注入信息
Vue.prototype.getWxConfigParams = function(cb) {
  var t = this
  t.ajax({
    method: 'get',
    url: t.wx_static_api + 'api/wsd_jssdkConfig.php',
    params: {
      url: location.href.split('#')[0]
    },
    success: function(info) {
      var data = info[0] || {}
      if (data.info) {
        var json = Base64.decode(data.info)
        data = JSON.parse(json)
      }
      console.log(data)

      sessionStorage.wx_configParams = JSON.stringify(data)
      t.$store.state.wx_configParams = data
      if (typeof cb == 'function') {
        cb(data)
      }
      setTimeout(function() {
        t.getWxConfigParams()
      }, 3600 * 1000)
    },
    error: t.onNetworkError
  })
}

Vue.prototype.onNetworkError = function() {
  var t = this
  t.$vux.alert.hide()
  setTimeout(function() {
    t.$vux.alert.show({
      title: '提示',
      content: '网络不稳定，请重新尝试！',
      onHide() {
        t.$wechat.closeWindow()
      }
    })
  }, 200)
}
//获取微信用户基础数据
Vue.prototype.initWxDataInfo = function(cb) {
  var t = this
  // if(!sessionStorage.wx_userInfo){
  t.getWxUserInfo(function(data) {
    if (typeof cb == 'function') {
      cb(data)
    }
  })
  t.getWxConfigParams()
  // }
}

//关闭微信公众号聊天界面
Vue.prototype.closeWindow = function() {
  /*global WeixinJSBridge*/
  if (typeof WeixinJSBridge != 'undefined') {
    WeixinJSBridge.call('closeWindow')
  }
}

//微信获取区域地理位置
Vue.prototype.getAddressInformation = function(config, cb) {
  var t = this
  var wx_config = JSON.parse(JSON.stringify(config))
  wx_config.jsApiList = ['checkJsApi', 'getLocation']
  t.$wechat.config(wx_config)
  t.$wechat.ready(function() {
    t.$wechat.getLocation({
      success: function(res) {
        if (typeof cb == 'function') {
          cb(res)
        }
      },
      cancel: function(res) {
        console.log(res)
        /*强制获取用户地理位置*/
        t.getAddressInformation(config)
      },
      fail: function(err) {
        console.log(err)
      }
    })
  })
}