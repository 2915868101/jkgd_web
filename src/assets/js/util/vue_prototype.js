import Vue from 'vue'
import CryptoJS from 'crypto-js'
// var Base64 = require('js-base64').Base64

if (!sessionStorage.fromRouteName) {
  sessionStorage.fromRouteName = JSON.stringify([])
}
Vue.prototype.updateTitle = function(value) {
  var t = this
  t.$store.commit('updateTitle', value)
}
Vue.prototype.skipPage = function(to, from) {
  var t = this
  var fromName = JSON.parse(sessionStorage.fromRouteName)
  var index = t.contains(fromName, to.name)
  if (to.name != '404') {
    if (index != -1) {
      t.transitionName = t.$store.state.transitionName || 'slide-right'
      fromName.splice(0, index + 1)
    } else {
      t.transitionName = t.$store.state.transitionName || 'slide-left'
      fromName.unshift(from.name)
    }
  } else {
    t.transitionName = 'fade'
  }
  if (to.name == 'indexPage') {
    fromName = []
    t.transitionName = 'slide-right'
  }
  sessionStorage.fromRouteName = JSON.stringify(fromName)
}
//切换路由
Vue.prototype.linkToPage = function(url, query, animation) {
  var t = this
  var hasQuery = typeof query == 'string' ? false : true
  t.$store.state.transitionName = (hasQuery ? animation : query) || ''
  t.$router.push({ path: url, query: ((query && hasQuery) ? { data: JSON.stringify(query || {}) } : {}) }).catch(error => error)
}
//后退
Vue.prototype.goBack = function(pg) {
  history.go(pg || -1)
}
// 获取随机数
Vue.prototype.getId = function(num) {
  num = num || 16
  var str = ''
  for (var i = 0; i < num; i++) {
    str += Math.floor(Math.random() * 10)
  }
  return str
}
//获取星期
Vue.prototype.getWeek = function(date) {
  var d = date ? new Date(date) : new Date(),
    day = d.getDay(),
    weeks = ['周日', '周一', '周二', '周三', '周四', '周五', '周六']
  return weeks[day]
}
//获取时间段是上午还是下午
Vue.prototype.getTimeNode = function(time) {
  var str = String(time).split('-')
  str = parseInt(str[str.length - 1])
  return str <= 12 ? '上午' : '下午'
}
// 获取元素在数组中的索引值
Vue.prototype.contains = function(arr, v) {
  for (var i = 0; i < arr.length; i++) {
    if (arr[i] === v) {
      return i
    }
  }
  return -1
}
//选中或取消的时候更新 ids
Vue.prototype.spliceIds = function(arr, v, type) {
  var t = this
  var sp = function(arr, v) {
    var index = t.contains(arr, v)
    if (index == -1) {
      if (!type || type == 'add') {
        arr.push(v)
      }
    } else {
      if (!type || type == 'del') {
        arr.splice(index, 1)
      }
    }
    return arr
  }
  var new_arr = []
  if (Object.prototype.toString.call(v) == '[object Array]') {
    for (var i = 0; i < v.length; i++) {
      new_arr = sp(arr, v[i])
    }
  } else {
    new_arr = sp(arr, v)
  }
  return new_arr
}
//替换空字段
Vue.prototype.nullToSpace = function(obj, str) {
  return !obj && obj != 0 && obj != false ? (str || '--') : obj
}
//替换隐藏字符
Vue.prototype.regNumber = function(num, size) {
  var size1 = size ? size : 4
  size = size || 3
  var len = String(num).length,
    str = ''
  for (var i = 0; i < len; i++) {
    if (i > size && i <= len - size1) {
      str += '*'
    }
  }
  return String(num).substr(0, size) + str + String(num).substr(len - size1)
}
//id pid 格式转 id children 格式
Vue.prototype.toTreeData = function(data, id, pid) {
  data = JSON.parse(JSON.stringify(data))
  data.forEach(function(item) { delete item.children })
  var map = {}
  data.forEach(function(item) { map[item[id]] = item })
  var val = []
  data.forEach(function(item) {
    var parent = map[item[pid]]
    if (parent) {
      (parent.children || (parent.children = [])).push(item)
    } else { val.push(item) }
  })
  return val
}
//通过身份证获取生日年龄
Vue.prototype.IdCard = function(UUserCard, num) {
  var t = this
  if (!t.regIdCard(UUserCard)) {
    return ''
  }
  if (num == 1) {
    //获取出生日期
    var birth = UUserCard.substring(6, 10) + "-" + UUserCard.substring(10, 12) + "-" + UUserCard.substring(12, 14)
    return birth
  }
  if (num == 2) {
    //获取性别
    var _s = parseInt(UUserCard.substr(16, 1)) % 2
    if (_s == 1) {
      return "男"
    } else if (_s == 0) {
      return "女"
    } else {
      return ''
    }
  }
  if (num == 3) {
    //获取年龄
    var myDate = new Date()
    var month = myDate.getMonth() + 1
    var day = myDate.getDate()
    var age = myDate.getFullYear() - UUserCard.substring(6, 10) - 1
    if (UUserCard.substring(10, 12) < month || UUserCard.substring(10, 12) == month && UUserCard.substring(12, 14) <= day) {
      age++
    }
    return age
  }
}
//身份证号码严格校验
Vue.prototype.regIdCard = function(idCard) {
  var res = true
  if (idCard.length != 18) {
    res = false
  } else {
    var address = idCard.substring(0, 6) //6位，地区代码
    var birthday = idCard.substring(6, 14) //8位，出生日期
    var sequenceCode = idCard.substring(14, 17) //3位，顺序码：奇为男，偶为女
    // var checkCode = idCard.substring(17) //1位，校验码：检验位
    var province = { 11: "北京", 12: "天津", 13: "河北", 14: "山西", 15: "内蒙古", 21: "辽宁", 22: "吉林", 23: "黑龙江 ", 31: "上海", 32: "江苏", 33: "浙江", 34: "安徽", 35: "福建", 36: "江西", 37: "山东", 41: "河南", 42: "湖北 ", 43: "湖南", 44: "广东", 45: "广西", 46: "海南", 50: "重庆", 51: "四川", 52: "贵州", 53: "云南", 54: "西藏 ", 61: "陕西", 62: "甘肃", 63: "青海", 64: "宁夏", 65: "新疆", 71: "台湾", 81: "香港", 82: "澳门", 91: "国外" }
    var year = birthday.substring(0, 4)
    var month = birthday.substring(4, 6)
    var day = birthday.substring(6)
    var tempDate = new Date(year, parseFloat(month) - 1, parseFloat(day))
    if (province[parseInt(address.substr(0, 2))] == null || (tempDate.getFullYear() != parseFloat(year) || tempDate.getMonth() != parseFloat(month) - 1 || tempDate.getDate() != parseFloat(day))) {
      res = false
    } else {
      var weightedFactors = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1] //加权因子   
      var valideCode = [1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2] // 身份证验证位值，当中10代表X
      var idCardArray = idCard.split("") // 得到身份证数组
      var sum = 0 // 声明加权求和变量
      if (idCardArray[17].toLowerCase() == 'x') {
        idCardArray[17] = 10 // 将最后位为x的验证码替换为10  
      }
      for (var i = 0; i < 17; i++) {
        sum += weightedFactors[i] * idCardArray[i] // 加权求和   
      }
      var valCodePosition = sum % 11 // 得到验证码所在位置
      if (idCardArray[17] == valideCode[valCodePosition]) {
        var sex = "男"
        if (sequenceCode % 2 == 0) {
          sex = "女"
        }
        console.log(sex)
      } else {
        res = false
      }
    }
  }
  return res
}
//发送短信
Vue.prototype.sendCodeFun = function(phone, sendMsg) {
  var t = this
  if (t.serverType == 'hz') {
    sendMsg = sendMsg + '【惠州健康通】'
  }
  t.ajax({
    method: 'get',
    params: {
      PHONE: phone,
      CONTENT: sendMsg,
    },
    url: t.serverPath3,
    success(data) {
      console.log(data)
    }
  })
}
//是否注册
Vue.prototype.isRegister = function(params, sucFun) {
  var t = this
  t.ajax({
    method: 'post',
    serverId: 'WXBM00013',
    loading: false,
    params: {
      openid: params.openid || params.OPENID,
      sjhm: params.sjhm || params.SJHM || '',
      type: params.type || params.TYPE || 0
    },
    url: t.serverPath1,
    success(data) {
      if (data[0].CODE == -2) {
        t.$dialog.confirm({
            message: data[0].MSG,
          }).then(() => { 
            t.closeWindow()
          })
          .catch(() => { 
            t.closeWindow()
          });
      }else if (data[0].CODE != -1) {
        var _CONTENTS = data[0].CONTENTS ? data[0].CONTENTS[0] : {}
        //_CONTENTS.FACEIMAGE = 1  //注册未采集人脸照片是为 0，已采集为 1 
        sessionStorage.register_info = JSON.stringify(data[0])
        t.$store.state.register_info = data[0]
        if (t.serverType != 'hz' || t.serverType == 'hz' && _CONTENTS.FACEIMAGE == '1') {
          if (typeof sucFun == 'function') {
            sucFun(data[0])
          }
        } else if (_CONTENTS.FACEIMAGE == 0) { // 惠州人脸采集
          if (!params.faceimg) {
            sessionStorage.setItem("prevUrl", t.$route.fullPath)
            t.$dialog.confirm({
              message: '您尚未采集人脸信息，去完善！',
            }).then(() => {
              t.linkToPage('/face_verify', { type: 'detect' })
            }).catch(() => {
              t.goBack(-1)
            })
          } else {
            if (typeof sucFun == 'function') {
              sucFun(data[0])
            }
          }
        }
      } else {
        if (!params.type && !params.TYPE) {
          if (t.$route.name != 'register_index') {
            if (!params.notJump) {
              t.$dialog.confirm({
                message: '您尚未注册，去注册！',
              }).then(() => {
                if (t.serverHealthCard) {
                  sessionStorage.setItem("prevUrl", t.$route.fullPath)
                  var redirect_uri = t.wx_health_card + "/register_card?openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl + '&prevUrl=' + t.$route.fullPath
                  window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(redirect_uri)
                } else {
                  t.linkToPage('/register_index')
                }
              }).catch(() => {
                t.goBack(-1)
              })
            } else {
              sucFun(data[0])
            }
          } else {
            sucFun(data[0])
          }
        } else {
          sucFun(data[0])
        }
      }
    }
  })
}

//获取基础路径
Vue.prototype.getBaseUrl = function() {
  var local = window.location
  var contextPath = local.pathname
  contextPath = contextPath.substring(0, contextPath.length - 1)
  return local.origin + contextPath
}

//des加解密  
Vue.prototype.encryptByDES = (decrypt, key) => {
  key = key || 'Jalone00'
  var encrypt = CryptoJS.DES.encrypt(decrypt, CryptoJS.enc.Utf8.parse(key), {
    mode: CryptoJS.mode.ECB,
    padding: CryptoJS.pad.Pkcs7
  }).toString()
  // Base64.encode(encrypt)
  return encrypt
}
//DES解密
Vue.prototype.decryptByDES = (encrypt, key) => {
  key = key || 'Jalone00'
  //DES  ECB模式解密 
  var decrypt = CryptoJS.DES.decrypt(encrypt, CryptoJS.enc.Utf8.parse(key), {
    mode: CryptoJS.mode.ECB,
    padding: CryptoJS.pad.Pkcs7
  }).toString(CryptoJS.enc.Utf8)
  return decrypt
}

//非当天刷新一次时间
Vue.prototype.updateNowDate = function() {
  var t = this
  if (!localStorage.nowDate || localStorage.nowDate.indexOf(t.$moment().format('YYYY-MM-DD')) == -1) {
    localStorage.nowDate = t.$moment().format('YYYY-MM-DD HH:mm:ss')
    localStorage.hospitalList = ''
    localStorage.ks_lists = ''
    console.log(localStorage.nowDate)
  }
}


Vue.prototype.GetRequest = function() {
  var url = window.location.href //获取url中"?"符后的字串 
  var theRequest = new Object()
  if (url.indexOf("?") != -1) {
    url = url.substr(url.indexOf("?"), url.length)
    var str = url.substr(1)
    var strs = str.split("&")
    for (var i = 0; i < strs.length; i++) {
      theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
    }
  }
  return theRequest
}

Vue.prototype.getDayListsTjyy = function(info) {
  var t = this
  //2021年节假日
  var jrdate_2021 = ["2021-04-03", "2021-04-04", "2021-04-05", "2021-05-01", "2021-05-02", "2021-05-03", "2021-05-04", "2021-05-05", "2021-06-12", "2021-06-13", "2021-06-14", "2021-09-19", "2021-09-20", "2021-09-21", "2021-10-01", "2021-10-02", "2021-10-03", "2021-10-04", "2021-10-05", "2021-10-06", "2021-10-07"];
  info = info || {}
  var nowDate = info.date ? new Date(info.date) : new Date()
  var num = info.num || 7
  var days = []
  for (var i = 0; i < num; i++) {
    var date = new Date(nowDate.getTime() + i * 24 * 60 * 60 * 1000)
    var week = t.getWeek(date)
    if (!info.onlyWorkingDays || (info.onlyWorkingDays && week != '周六' && week != '周日')) {
      if (jrdate_2021.indexOf(t.$moment(date).format('yyyy-MM-DD')) == -1) {
        days.push({
          date: t.$moment(date).format('yyyy-MM-DD'),
          week: week
        })
      }
    }
  }
  return days
}
// 广德人民医院
//获取日期列表
Vue.prototype.getDayLists = function(info) {
  var t = this
  info = info || {}
  var nowDate = info.date ? new Date(info.date) : new Date()
  var num = info.num || 7
  var days = []
  for (var i = 0; i < num; i++) {
    var date = new Date(nowDate.getTime() + i * 24 * 60 * 60 * 1000)
    var week = t.getWeek(date)
    if (!info.onlyWorkingDays || (info.onlyWorkingDays && week != '周六' && week != '周日')) {
      days.push({
        date: t.$moment(date).format('YYYY-MM-DD'),
        week: week
      })
    }
  }
  return days
}
//比较日期大小
Vue.prototype.thanDate = function(date2, date1) {
  var t = this
  var oDate2 = parseInt(t.formatTime(new Date(date2)).substring(0, 10).replace(/-|\//g, ''))
  var oDate1 = parseInt(t.formatTime(date1 ? new Date(date1) : new Date()).substring(0, 10).replace(/-|\//g, ''))
  return !!(oDate2 >= oDate1)
}
//获取时间段是上午还是下午
Vue.prototype.getTimeNode = function(time) {
  var str = String(time).split('-')
  str = parseInt(str[str.length - 1])
  return str <= 12 ? '上午' : '下午'
}
//日期加减
Vue.prototype.computeDate = function(day, date) {
  var nowDate = new Date() || new Date(date)
  return this.$moment(new Date((nowDate.getTime() / 1000 + 86400 * day) * 1000)).format('YYYY-MM-DD')
}
//获取日期字符串
Vue.prototype.formatTime = function(date) {
  var t = this
  var d = date ? new Date(date) : new Date(),
    Y = d.getFullYear(),
    M = d.getMonth() + 1,
    D = d.getDate(),
    h = d.getHours(),
    m = d.getMinutes(),
    s = d.getSeconds(),
    ms = d.getMilliseconds(),
    t1 = [Y, M, D].map(t.formatNumber),
    t2 = [h, m, s].map(t.formatNumber)
  if (ms < 10) { ms = '00' + ms } else if (ms >= 10 && ms < 100) { ms = '0' + ms }
  return t1.join('-') + ' ' + t2.join(':') + ' ' + ms
}
//格式化数值
Vue.prototype.formatNumber = function(n) { var str = n.toString(); return str[1] ? str : '0' + str }
// 广德人民医院******************end//