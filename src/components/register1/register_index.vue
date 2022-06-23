<template>
  <div class="register_index g-wrap g-hp100 s-bgcfff">
    <van-notice-bar v-if="!isReadOnly" class="f-fs13" wrapable :scrollable="false">
      *就诊采用“实名制”，请填写正确的身份证号和姓名789798798。
    </van-notice-bar>
    <van-notice-bar v-else class="f-fs13" wrapable :scrollable="false">
      *就诊采用“实名制”，请填写正确的身份证号和姓名。
    </van-notice-bar>
    <div class="register-wrap">
      <van-field right center disabled>
        <template #label>
          <span class="s-c909399">头像</span>
        </template>
        <template #button>
          <img class="headimg" :src="registerForm.TX||wx_userInfo.headimgurl||$store.state.def_headimg">
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="registerForm.XM" placeholder="请输入姓名" :readonly="isReadOnly">
        <template #label>
          <span :class="[registerForm.XM?'s-c909399':'s-c303133']">姓名：</span>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="registerForm.CNUM" maxlength="18" placeholder="请输入身份证号码" :readonly="isReadOnly" @blur="onBlurCnumInput">
        <template #label>
          <span :class="[registerForm.CNUM?'s-c909399':'s-c303133']">身份证号码：</span>
        </template>
      </van-field>
      <van-field input-align="right" name="radio">
        <template #label>
          <span :class="[registerForm.XB?'s-c909399':'s-c303133']">性别：</span>
        </template>
        <template #input>
          <van-radio-group v-model="registerForm.XB" direction="horizontal">
            <van-radio name="1" checked-color="#00c792" disabled>男</van-radio>
            <van-radio name="2" checked-color="#00c792" disabled>女</van-radio>
          </van-radio-group>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="registerForm.HKDZ" maxlength="18" placeholder="请输入户籍地址" :readonly="isReadOnly">
        <template #label>
          <span :class="[registerForm.HKDZ?'s-c909399':'s-c303133']">户籍地址：</span>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="registerForm.SJHM" maxlength="11" placeholder="请输入手机号码">
        <template #label>
          <span :class="[registerForm.SJHM?'s-c909399':'s-c303133']">手机号码：</span>
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
    </div>
    <div class="g-wp100 f-pt30">
      <van-button class="item-btn f-bs3" round color="#00c792" @click="doSave">确认注册</van-button>
      <!-- <van-button class="item-btn f-bs3" plain color="#00c792" round @click="goBack()">返回上一步</van-button> -->
    </div>
  </div>
</template>
<script>
  export default {
    name: 'register_index',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('注册电子健康卡')
      if (t.queryData) {
        if (!t.queryData.ismanual) {
          t.isReadOnly = true
          t.registerForm.XM = t.queryData.XM
          t.registerForm.XB = t.queryData.XB == '男' ? '1' : '2'
          t.registerForm.CNUM = t.queryData.CNUM
          t.registerForm.HKDZ = t.queryData.HKDZ
          t.registerForm.MZ = t.queryData.MZ
        }
      }
      t.registerForm.OPENID = t.wx_userInfo.openid
      t.registerForm.TX = t.wx_userInfo.headimgurl
    },
    data() {
      return {
        isReadOnly: false,
        registerForm: {
          OPENID: '',
          CNUM: '',
          SJHM: '',
          XM: '',
          XB: '',
          CSRQ: '',
          NL: '',
          HKDZ: '',
          TX: '',
        },
        flag1: true,
        getCodeText: '发送验证码',
        checkCode: '',
        code_ori: '',
        save_flag: true,
        registerPhone_flag: false,
        registerSjhm_flag: false,
      }
    },
    methods: {
      // 开发不是阶段判断是否是内部人员
      isRegisterPhone(phone) {
        var t = this
        t.ajax({
          method: 'get',
          url: t.wx_static_api + 'api/REGISTER_PHONE.json',
          success: function(data) {
            var arr = data[0].SJHM
            if (arr.includes(phone)) {
              t.registerPhone_flag = true
            } else {
              t.registerPhone_flag = false
            }
          }
        })
      },
      // 手机号码是否可用
      isRegisterSjhm(phone) {
        var t = this
        if (t.$store.state.rules.mobile.test(phone)) {
          t.ajax({
            loading: false,
            serverId: 'WXBM00013',
            url: t.serverPath1,
            params: {
              OPENID: t.wx_userInfo.openid,
              SJHM: phone,
              TYPE: '-1'
            },
            success: function(data) {
              console.log(data)
              if (data[0].CODE == '0') {
                t.registerSjhm_flag = true
              } else {
                t.registerSjhm_flag = false
              }
            }
          })
        }
      },
      onBlurCnumInput() {
        var t = this
        if (t.regIdCard(t.registerForm.CNUM)) {
          t.registerForm.XB = t.IdCard(t.registerForm.CNUM, 2) == '男' ? '1' : '2'
          console.log(t.registerForm.XB)
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
        t.isRegisterSjhm(t.registerForm.SJHM)
        setTimeout(function() {
          if (!t.$store.state.rules.mobile.test(t.registerForm.SJHM)) {
            t.$toast('请填写真实有效的手机号码')
            return false
          } else if (!t.registerSjhm_flag) {
            t.$toast('手机号码已注册')
            return false
          } else if (t.flag1) { //防止重复点击
            t.flag1 = false
            //执行倒计时
            t.cutDown(60)
            t.checkCode = t.getId(4)
            // alert(t.checkCode)
            t.sendCodeFun(t.registerForm.SJHM, '您的手机验证码：' + t.checkCode + '，2分钟内有效，请确保本人操作！')
          }
        }, 500)
      },
      doValidate(cb) {
        var t = this
        if (!t.registerForm.XM) {
          t.$toast('姓名不能为空')
          return false
        } else if (!t.registerForm.CNUM) {
          t.$toast('身份证号不能为空')
          return false
        } else if (!t.regIdCard(t.registerForm.CNUM)) {
          t.$toast('请填写真实有效的身份证号')
          return false
        } else if (!t.registerForm.HKDZ) {
          t.$toast('户籍地址不能为空')
          return false
        } else if (!t.registerForm.SJHM) {
          t.$toast('手机号不能为空')
          return false
        } else if (!t.$store.state.rules.mobile.test(t.registerForm.SJHM)) {
          t.$toast('请填写真实有效的手机号')
          return false
        } else if (!t.checkCode) {
          t.$toast('短信验证码不能为空')
          return false
        } else if (t.code_ori != t.checkCode) {
          t.$toast('验证码错误')
          return false
        } else if (!t.registerSjhm_flag) {
          t.$toast('手机号码已注册')
          return false
        } else {
          if (typeof cb == 'function') {
            cb()
          }
        }
      },
      doSave() {
        var t = this

        t.registerForm.CSRQ = t.IdCard(t.registerForm.CNUM, 1)
        t.registerForm.NL = t.IdCard(t.registerForm.CNUM, 3)
        t.registerForm.CARDTYPE = '01'
        t.registerForm.CHANNELNUM = '0'
        console.log(t.registerForm)
        setTimeout(function() {
          t.doValidate(function() {
            if (t.save_flag) {
              t.save_flag = false
              t.ajax({
                serverId: 'WXCZ00043',
                url: t.serverPath1,
                params: t.registerForm,
                success: function(data) {
                  if (data[0].CODE == 0) {
                    t.$toast('注册成功')
                    setTimeout(function() {
                      t.goBack(-2)
                    }, 2000)
                    console.log(history)
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


  .register_index .g-wp100>>>.van-button {
    width: 90%;
    margin: 20px 5% 0;
    height: 50px;
    padding: 0 10px;
  }

  .register_index>>>.van-cell {
    padding: 15px 20px;
    border-bottom: 1PX solid #ebeef5;
  }

  .register_index>>>.van-cell .headimg {
    width: 45px;
    height: 45px;
    border-radius: 50%;
  }

  .register_index>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .register_index .code-item>>>.van-button--small {
    color: #00c792;
    border-color: #00c792;
    border-radius: 3px;
    height: 25px;
  }
</style>