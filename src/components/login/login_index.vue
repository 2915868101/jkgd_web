<template>
  <div class="login_index g-wrap f-df f-dfv">
    <div class="cont-box">
      <div class="logo f-df f-dfc"><img src="../../assets/img/login/login_icon.png"><span class="f-fwb f-fs18">欢迎登录!</span></div>
      <van-field input-align="left" clearable v-model="loginForm.SJHM" maxlength="11" placeholder="请输入手机号码" @blur="onBlurInput">
        <template #label>
          <span class="s-c909399">+86</span>
        </template>
      </van-field>
      <van-field v-model="code_ori" class="code-item" center clearable placeholder="请输入短信验证码">
        <template #button>
          <van-button size="small" plain color="#3AC6B2" @click="sendCode" :disabled="!flag1">{{getCodeText}}</van-button>
        </template>
      </van-field>
      <div class="g-wp100 f-pt30">
        <van-button class="item-btn f-bs3" round color="#3AC6B2" @click="doLogin">登录</van-button>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'login_index',
    created() {
      localStorage.wx_userInfo = ''
    },
    data() {
      return {
        loginForm: {
          OPENID: '',
          CNUM: '',
          SJHM: '',
        },
        flag1: true,
        getCodeText: '发送验证码',
        checkCode: '',
        code_ori: '',
        save_flag: true,
        isOPENID: false,
        login_form: ''
      }
    },
    methods: {

      getOPENID(params) {
        var t = this
        t.ajax({
          method: 'post',
          serverId: 'WXBMTZH03',
          loading: false,
          params: params,
          url: t.serverPath1,
          success(data) {
            console.log(data)
            if (data[0].CODE == '0') {
              t.loginForm.OPENID = data[0].CONTENTS[0].OPENID
              if (!t.loginForm.OPENID) {
                // 调用注册接口
                t.$dialog.alert({
                  confirmButtonText: '好的',
                  message: '手机号未注册，请先进行用户注册！'
                }).then(function() {
                  t.linkToPage('/register_index')
                })
              } else {
                // 判断是否注册
                t.isRegister({
                  openid: t.loginForm.OPENID,
                  type: 0
                }, function(sucFun) {
                  console.log(sucFun.CONTENTS[0])
                  var sex = t.IdCard(t.loginForm.OPENID, 2) == '男' ? '1' : '2'
                  t.login_form = {
                    openid: t.loginForm.OPENID,
                    nickname: sucFun.CONTENTS[0].NAME,
                    sex: sex
                  }
                })
                t.isOPENID = true
              }
            }
          }
        })
      },
      onBlurInput() {
        var t = this
        if (!t.$store.state.rules.mobile.test(t.loginForm.SJHM)) {
          t.$toast('请填写真实有效的手机号码')
          return false
        } else {
          t.getOPENID({
            PHONE: t.loginForm.SJHM
          })
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
        setTimeout(function() {
          if (!t.$store.state.rules.mobile.test(t.loginForm.SJHM)) {
            t.$toast('请填写真实有效的手机号码')
            return false
          } else if (t.flag1 && t.isOPENID) { //防止重复点击
            t.flag1 = false
            //执行倒计时
            t.cutDown(60)
            t.checkCode = t.getId(4)
            // alert(t.checkCode)
            t.sendCodeFun(t.loginForm.SJHM, '您的手机验证码：' + t.checkCode + '，2分钟内有效，请确保本人操作！')
          }
        }, 500)
      },
      doValidate(cb) {
        var t = this
        if (!t.loginForm.SJHM) {
          t.$toast('手机号不能为空')
          return false
        } else if (!t.$store.state.rules.mobile.test(t.loginForm.SJHM)) {
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
      doLogin() {
        var t = this
        setTimeout(function() {
          t.doValidate(function() {
            if (t.save_flag) {

              localStorage.wx_userInfo = JSON.stringify(t.login_form)
              t.$store.state.wx_userInfo = JSON.parse(localStorage.wx_userInfo)
              t.linkToPage('/jkfw_index')
            }
          })
        }, 50)
      }
    },
    mounted() {

    }
  }
</script>
<style scoped>
  .login_index {
    position: relative;
    background: linear-gradient(175.46deg, #6CDDC6 1.69%, #15D3B8 98.98%);
  }

  .login_index:after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: url(../../assets/img/login/login_bg.png) no-repeat;
    background-size: 100%;
    background-position: bottom;
  }

  .login_index .cont-box {
    background: #fff;
    width: 80%;
    height: 350px;
    border-radius: 10px;
    padding: 15px;
    margin: 0 auto;
    position: relative;
    z-index: 10;
  }

  .login_index .cont-box .logo {
    align-items: center;
    color: #434343;
    padding: 30px 0 20px;
  }

  .login_index .cont-box .logo img {
    width: 30px;
    margin-right: 10px;
  }

  .login_index>>>.van-cell {
    padding: 15px 15px;
  }

  .login_index>>>.van-field__label {
    width: 50px;
  }

  .login_index .code-item>>>.van-button--small {
    color: #3AC6B2;
    border-color: #3AC6B2;
    border-radius: 15px;
    height: 30px;
    line-height: 30px;
  }

  .login_index .g-wp100>>>.van-button {
    width: 90%;
    margin: 20px 5% 0;
    height: 40px;
    padding: 0 10px;
    background: #3AC6B2;
    margin-top: 40px;
    box-shadow: 0px 12px 18px rgba(150, 230, 218, 0.25);
  }
</style>