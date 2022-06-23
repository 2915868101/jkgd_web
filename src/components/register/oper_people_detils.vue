<template>
  <div class="oper_people_detils g-wrap g-hp100 s-bgcfff">
    <van-notice-bar class="f-fs13" wrapable :scrollable="false">
      就诊采用“实名制”，请填写正确的身份证号和姓名。
    </van-notice-bar>
    <div class="register-wrap">
      <van-field right center>
        <template #label>
          <span class="s-c909399">头像</span>
        </template>
        <template #button>
          <img class="headimg" :src="peopleForm.TX||wx_userInfo.headimgurl||$store.state.def_headimg">
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="peopleForm.XM" placeholder="请输入姓名" :readonly="isReadOnly">
        <template #label>
          <span :class="[peopleForm.XM?'s-c909399':'s-c303133']">姓名：</span>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="peopleForm.CNUM" placeholder="请输入身份证号码" :readonly="isReadOnly" @blur="onBlurCnumInput">
        <template #label>
          <span :class="[peopleForm.CNUM?'s-c909399':'s-c303133']">身份证号码：</span>
        </template>
      </van-field>
      <van-field input-align="right" name="radio">
        <template #label>
          <span :class="[peopleForm.XB?'s-c909399':'s-c303133']">性别：</span>
        </template>
        <template #input>
          <van-radio-group v-model="peopleForm.XB" direction="horizontal">
            <van-radio name="1" checked-color="#00c792" disabled>男</van-radio>
            <van-radio name="2" checked-color="#00c792" disabled>女</van-radio>
          </van-radio-group>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="peopleForm.SJHM" maxlength="11" placeholder="请输入手机号码">
        <template #label>
          <span :class="[peopleForm.SJHM?'s-c909399':'s-c303133']">手机号码：</span>
        </template>
      </van-field>
      <van-field v-model="code_ori" class="code-item" center clearable placeholder="请输入短信验证码">
        <template #label>
          <span :class="[code_ori?'s-c909399':'s-c303133']">输入验证码：</span>
        </template>
        <template #button>
          <van-button size="small" plain color="#00c792" @click="sendCode" :disabled="!flag1">{{getCodeText}}</van-button>
        </template>
      </van-field>
      <van-checkbox class="cell-def" checked-color="#00c792" v-model="is_def" @change="changeDef">设为默认就诊人</van-checkbox>
    </div>
    <div class="g-wp100 f-pt30">
      <van-button class="item-btn f-bs3" round color="#00c792" @click="doSave">保存</van-button>
    </div>
  </div>
</template>
<script>
  var openid, headimgurl, prevUrl
  export default {
    name: 'oper_people_detils',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('添加电子健康卡')
      if (t.queryData) {
        // 身份证识别或手动填写
        if (!t.queryData.ismanual) {
          t.isReadOnly = true
          t.peopleForm.XM = t.queryData.XM
          t.peopleForm.XB = t.queryData.XB == '男' ? '1' : '2'
          t.peopleForm.CNUM = t.queryData.CNUM
          t.peopleForm.HKDZ = t.queryData.HKDZ
        }
        t.peopleForm.WXCODE = t.queryData.wechatCode
        t.visitType = 0
      } else {
        // 跨院用户一键授权 
        var Request = undefined
        Request = t.GetRequest()
        var healthCode = Request["healthCode"]
        openid = Request["openid"] || t.wx_userInfo.openid
        headimgurl = Request["headimgurl"] || t.wx_userInfo.headimgurl
        prevUrl = Request["prevUrl"]
        sessionStorage.wx_userInfo = JSON.stringify({
          openid: openid,
          headimgurl: headimgurl
        })
        t.$store.state.wx_userInfo = JSON.parse(sessionStorage.wx_userInfo)
        sessionStorage.setItem("prevUrl", prevUrl)
        // var healthCode1 = sessionStorage.getItem("healthCode")
        // 根据healthCode判断进行相关操作
        if (healthCode != null && healthCode != "" && healthCode != '-1') {
          // sessionStorage.setItem("healthCode", healthCode);
          if (healthCode == '0') {
            t.visitType = 0
            var redirect_uri = t.wx_health_card + "/register_card?isRegister=true&openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl + '&prevUrl=' + prevUrl
            window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(redirect_uri);
          } else {
            t.visitType = 1
            t.isReadOnly = true
            t.getHealthCode(healthCode)
          }
        }
      }
      t.peopleForm.OPENID = t.wx_userInfo.openid
      t.peopleForm.TX = t.wx_userInfo.headimgurl
    },
    data() {
      return {
        visitType: '',
        isReadOnly: false,
        peopleForm: {
          FLAG: 'Insert',
          OPENID: '',
          CNUM: '',
          SJHM: '',
          XM: '',
          XB: '',
          CSRQ: '',
          NL: '',
          HKDZ: '',
          MZ: '',
          TX: '',
          CARDTYPE: '01',
          BZ: '0',
          CHANNELNUM: '0'
        },
        flag1: true,
        getCodeText: '发送验证码',
        checkCode: '',
        code_ori: '',
        is_def: false,
        save_flag: true,
      }
    },
    methods: {
      // 获取健康授权码
      getHealthCode(code) {
        var t = this
        t.ajax({
          serverId: 'WXBM00032',
          url: t.serverPath1,
          params: {
            HEALTHCODE: code
          },
          success: function(data) {
            console.log(data)
            if (data[0].CODE == 0) {
              var _data = JSON.parse(data[0].rsp)
              console.log(_data)
              _data = _data.card
              // if (_data.idNumber)
              t.peopleForm.QRCODETEXT = _data.qrCodeText
              t.peopleForm.HEALTHCARDID = _data.healthCardId
              t.peopleForm.PHID = _data.phid
              t.peopleForm.CNUM = _data.idNumber
              t.peopleForm.SJHM = _data.phone1 || _data.phone2
              t.peopleForm.XM = _data.name
              t.peopleForm.XB = _data.gender == '男' ? '1' : '2'
              t.peopleForm.CSRQ = _data.birthday
              t.peopleForm.NL = t.IdCard(_data.idNumber, 3)
              t.peopleForm.HKDZ = _data.address || ''
              t.peopleForm.MZ = _data.nation || ''
            } else {
              if (t.serverType == 'cz') {
                t.$dialog.alert({
                  confirmButtonText: '好的',
                  message: '健康卡信息已过期，请选用上传身份证或手动填写方式注册'
                }).then(function() {
                  var redirect_uri = t.wx_health_card + "/register_card?openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl + '&prevUrl=' + t.$route.fullPath
                  window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(redirect_uri)
                })
              } else {
                t.$toast(data.MSG)
              }
            }
          },
          error: function(data) {
            if (t.serverType == 'cz') {
              t.$dialog.alert({
                confirmButtonText: '好的',
                message: '健康卡信息已过期，请选用上传身份证或手动填写方式注册'
              }).then(function() {
                var redirect_uri = t.wx_health_card + "/register_card?openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl + '&prevUrl=' + t.$route.fullPath
                window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(redirect_uri)
              })
            } else {
              t.$toast(data.MSG)
            }
          }
        })
      },
      onBlurCnumInput() {
        var t = this
        if (t.regIdCard(t.peopleForm.CNUM)) {
          t.peopleForm.XB = t.IdCard(t.peopleForm.CNUM, 2) == '男' ? '1' : '2'
          console.log(t.peopleForm.XB)
        } else {
          t.$toast('身份证号码错误')
        }
      },
      cutDown(num) {
        var t = this
        setTimeout(function() {
          if (num > 0) {
            num--
            t.getCodeText = num + '秒重新获取'
            t.cutDown(num)
          } else {
            t.getCodeText = '获取验证码'
            t.flag1 = true
          }
        }, 1000)
      },
      sendCode() {
        var t = this
        if (!t.$store.state.rules.mobile.test(t.peopleForm.SJHM)) {
          t.$toast('请填写真实有效的手机号码')
          return false
        }

        //防止重复点击
        if (t.flag1) {
          t.flag1 = false
          //执行倒计时
          t.cutDown(60)
          t.checkCode = t.getId(4)
          // alert(t.checkCode)
          t.sendCodeFun(t.peopleForm.SJHM, '您的手机验证码：' + t.checkCode + '，2分钟内有效，请确保本人操作！')
        }
      },
      changeDef(val) {
        var t = this
        t.peopleForm.BZ = val ? '1' : '0'
      },
      doValidate(cb) {
        var t = this
        if (!t.peopleForm.XM) {
          t.$toast('姓名不能为空')
          return false
        } else if (!t.peopleForm.CNUM) {
          t.$toast('身份证号不能为空')
          return false
        } else if (!t.regIdCard(t.peopleForm.CNUM)) {
          t.$toast('请填写真实有效的身份证号')
          return false
        } else if (!t.peopleForm.SJHM) {
          t.$toast('手机号不能为空')
          return false
        } else if (!t.$store.state.rules.mobile.test(t.peopleForm.SJHM)) {
          t.$toast('请填写真实有效的手机号')
          return false
        } else if (!t.checkCode) {
          t.$toast('短信验证码不能为空')
          return false
        } else if (t.code_ori != t.checkCode) {
          t.$toast('验证码错误')
          return false
        } else {
          if (typeof cb == 'function') {
            cb()
          }
        }
      },
      doSave() {
        var t = this
        t.peopleForm.CSRQ = t.IdCard(t.peopleForm.CNUM, 1)
        t.peopleForm.NL = t.IdCard(t.peopleForm.CNUM, 3)
        console.log(t.peopleForm)
        setTimeout(function() {
          t.doValidate(function() {
            if (t.save_flag) {
              t.save_flag = false
              t.ajax({
                serverId: 'WXBM00044',
                url: t.serverPath1,
                params: t.peopleForm,
                success: function(data) {
                  console.log(7897987989, data)
                  console.log(7897987989, t.peopleForm)
                  if (data[0].CODE == 0) {
                    t.$toast('注册健康卡成功')
                    setTimeout(function() {
                      // t.goBack(-2) 
                      t.linkToPage(sessionStorage.getItem("prevUrl"), 'slide-right')
                    }, 2000)
                  } else {
                    t.save_flag = true
                    t.$toast(data[0].MSG)
                  }
                },
                error: function(data) {
                  t.save_flag = true
                  t.$toast(data.MSG)
                }
              })
            }
          })
        }, 50)
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}


  .oper_people_detils .g-wp100>>>.van-button {
    width: 90%;
    margin: 20px 5% 0;
    height: 50px;
    padding: 0 10px;
  }

  .oper_people_detils>>>.van-cell {
    padding: 15px 20px;
    border-bottom: 1PX solid #ebeef5;
  }

  .oper_people_detils>>>.van-cell .headimg {
    width: 45px;
    height: 45px;
    border-radius: 50%;
  }

  .oper_people_detils .cell-def {
    font-size: 14px;
    padding: 15px;
    color: #c7c7c7;
  }

  .oper_people_detils>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }


  .oper_people_detils .code-item>>>.van-button--small {
    color: #00c792;
    border-color: #00c792;
    border-radius: 3px;
    height: 25px;
  }

  .oper_people_detils>>>.van-notice-bar {
    color: #ff8711;
    background: #fffde3;
  }
</style>