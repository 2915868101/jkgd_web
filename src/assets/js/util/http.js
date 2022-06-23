import Vue from 'vue'
import axios from 'axios-jsonp-pro'
import xmlreader from 'xmlreader'
import {
  Toast,
  Dialog
} from 'vant'

Vue.use(Toast)
Vue.use(Dialog)
Toast.setDefaultOptions({
  duration: 2000
});

var instance = axios.create()
instance.interceptors.request.use(function(config) {
  return config
})
instance.interceptors.response.use(function(response) {
  return Promise.resolve(response)
}, function(err) {
  console.log(err)
  if (err.response) {
    return Promise.reject(err.response)
  }
  return Promise.reject({
    code: 'fail',
    message: err.message
  })
})
Vue.prototype.axios = instance

Vue.prototype.getWebserviceParams = function(serviceId, params) {
  var t = this
  var op = ''
  for (var index in params) {
    op += `\n<${index.toLocaleUpperCase()}>${typeof params[index] != 'undefined' ? params[index] : ''}</${index.toLocaleUpperCase()}>`
  }
  var des = `<?xml version="1.0" encoding="UTF-8"?><WSD><MESSAGE><VERSION>1</VERSION></MESSAGE><DATA>
  <BEAN>${op}\n</BEAN>
  </DATA></WSD>`
  return `<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.zsgr.com/"><soapenv:Header/><soapenv:Body><ser:service>
<param>serviceId{=}${serviceId}{,}dataPackType{=}1{,}userName{=}dep{,}password{=}1{,}compress{=}N{,}encrypt{=}Y</param>
<data><![CDATA[${t.encryptByDES(des)}]]></data></ser:service></soapenv:Body></soapenv:Envelope>`
}
//转译中文字符
Vue.prototype.reconvert = function(str) {
  str = str.replace(/(\\u)(\w{1,4})/gi, function($0) {
    return (String.fromCharCode(parseInt((escape($0).replace(/(%5Cu)(\w{1,4})/g, "$2")), 16)))
  })
  str = str.replace(/(&#x)(\w{1,4});/gi, function($0) {
    return String.fromCharCode(parseInt(escape($0).replace(/(%26%23x)(\w{1,4})(%3B)/g, "$2"), 16))
  })
  str = str.replace(/(&#)(\d{1,6});/gi, function($0) {
    return String.fromCharCode(parseInt(escape($0).replace(/(%26%23)(\d{1,6})(%3B)/g, "$2")))
  })
  return str
}
//字符串转换成 html 标签
Vue.prototype.html_decode = function(str) {
  str = str.replace(/&amp;/g, '&')
  str = str.replace(/&lt;/g, '<')
  str = str.replace(/&gt;/g, '>')
  str = str.replace(/&quot;/g, "''")
  str = str.replace(/&#039;/g, "'")
  return str
}
Vue.prototype.returnArrayObj = function(obj) {
  try {
    if (typeof obj == 'string') {
      let a1 = eval("(" + obj + ")")
      return obj == "{}" ? [] : !Array.isArray(a1) ? [a1] : a1
    } else if (obj instanceof Object) {
      let a2 = JSON.stringify(obj)
      let a3 = JSON.parse(a2)
      return a2 == "{}" ? [] : !Array.isArray(a3) ? [a3] : a3
    } else {
      return obj
    }
  } catch (e) {
    return obj
  }
}
//解析 xml 格式数据
Vue.prototype.xml_decode = function(data) {
  var t = this
  var obj = null
  return new Promise(function(reslove, reject) {
    xmlreader.read(data, function(error, response) {
      if (error) {
        reject(new Error(error))
      } else {
        var res = t.decryptByDES(response['soap:Envelope']['soap:Body']['ns2:serviceResponse']['return'].text())
        res = res.replace(/&/g, " ")
        xmlreader.read(res, function(error, response) {
          if (response.WSD.MESSAGE.ERRORID) {
            obj = response.WSD.MESSAGE.LIST.ERROR.MESSAGE.text()
            reslove(obj)
          } else {
            if (response.WSD.DATA) {
              if (response.WSD.DATA.text) {
                obj = JSON.parse(response.WSD.DATA.text())
              } else { //<data>为空时
                obj = ''
              }
              reslove(obj)
            } else if (response.WSD.CDATA.text) {
              obj = JSON.parse(response.WSD.CDATA.text())
              reslove(obj)
            }
          }
        })
      }
    })
  })
}
Vue.prototype.ajax = function(opts) {
  var t = this
  if (typeof opts.loading == 'undefined') {
    opts.loading = true
  }
  if (opts.loading) {
    t.$toast.loading({
      duration: 0,
      forbidClick: true,
      loadingType: 'spinner',
      message: opts.loadMessage || ''
    })
  }
  var _method = opts.method ? opts.method.toLowerCase() : 'post'
  var _url = opts.url || t.serverPath
  if (opts.formData == true) {
    t.axios.defaults.headers[_method]['Content-Type'] = 'multipart/form-data'
  } else {
    t.axios.defaults.headers[_method]['Content-Type'] = 'application/x-www-form-urlencoded'
  }
  var info = {
    method: _method,
    url: _url
  }
  var _params = opts.params || {}
  _params = opts.serverId ? t.getWebserviceParams(opts.serverId, _params, opts.encrypt) : _params
  if (_method == 'get') {
    info.params = _params
  } else {
    info.data = (opts.formData == true || opts.serverId) ? _params : JSON.stringify(_params)
  }
  t.axios(info).then(function(response) {
    // console.log(response)
    hideLoading()
    var data = null
    if (opts.serverId) {
      t.xml_decode(response.data).then(function(res) {
        data = res
      })
    } else {
      data = response.data
    }
    setTimeout(function() {
      if (typeof data.resultCode != 'undefined') {
        if (data.resultCode == '0') {
          successFun(t.returnArrayObj(opts.serverId ? data : data.result), response)
        } else if (data.resultCode == '203') {
          errorFun(data)
        } else {
          errorFun(data)
        }
      } else {
        if (opts.serverId && data.errorMsg && !opts.hideAlert) {
          errorFun(data.errorMsg)
        } else if (opts.serverId && data[0] && data[0].STATE == 'error' && !opts.hideAlert) {
          t.$dialog.alert({
            title: '提示',
            message: data[0].MESS
          }).then(function() {
            t.$wechat.closeWindow()
          })
        } else {
          successFun(t.returnArrayObj(data))
        }
      }
    }, 0)
  }).catch(errorFun)

  function hideLoading() {
    if (opts.loading) {
      t.$toast.clear()
    }
  }

  function successFun(data) {
    if (opts.success) {
      opts.success(data)
    }
  }

  function errorFun(error) {
    hideLoading()
    if (!opts.error) {
      console.log('请求：' + _url)
      console.log('参数：' + JSON.stringify(_params))
      console.log('错误：' + JSON.stringify(error))
      // if (!opts.hideAlert) {
      //   t.$dialog.alert({
      //     title: '请求失败',
      //     message: `<div class="f-tal">网络不佳或系统繁忙,请稍后再试。</div>`
      //   })
      // }
    } else {
      opts.error(error)
    }
  }
}