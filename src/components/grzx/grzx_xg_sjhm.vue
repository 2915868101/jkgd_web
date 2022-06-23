<template>
  <div class="grzx_xg_sjhm g-wrap g-hp100 s-bgcfff">
    <div class="register-wrap">
      <van-field input-align="right" clearable v-model="initForm.SJHM" maxlength="11" label="手机号码" placeholder="请输入手机号码" />
      <van-field class="code-item" v-model="code_ori" center clearable label="短信验证码" placeholder="请输入短信验证码">
        <template #button>
          <van-button size="small" plain color="#00c792" @click="sendCode" :disabled="!flag1">{{getCodeText}}</van-button>
        </template>
      </van-field>
    </div>
    <div class="g-wp100 f-pt30">
      <van-button class="item-btn f-bs3" color="#00c792" round @click="doSave">保存</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'grzx_xg_sjhm',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle('修改手机号码')
    if (t.queryData) {
      t.initForm.XM = t.queryData.NAME
      t.initForm.XB = t.queryData.XB
      t.initForm.CNUM = t.queryData.CARD_NUMBER
      t.initForm.HKDZ = t.queryData.HKDZ || ''
      t.initForm.MZ = t.queryData.MZ || ''
    }
    t.initForm.OPENID = t.wx_userInfo.openid
    t.initForm.TX = t.wx_userInfo.headimgurl
  },
  data() {
    return {
      initForm: {
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
      registerSjhm_flag: false,

    }
  },
  methods: {
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
      t.isRegisterSjhm(t.initForm.SJHM)
      setTimeout(function() {
        if (!t.$store.state.rules.mobile.test(t.initForm.SJHM)) {
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
          t.sendCodeFun(t.initForm.SJHM, '您的手机验证码：' + t.checkCode + '，2分钟内有效，请确保本人操作！')
        }
      }, 500)
    },
    doValidate(cb) {
      var t = this
      if (!t.$store.state.rules.mobile.test(t.initForm.SJHM)) {
        t.$toast('请填写真实有效的手机号')
        return false
      } else if (!t.checkCode) {
        t.$toast('手机验证码不能为空')
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
      t.initForm.CSRQ = t.IdCard(t.initForm.CNUM, 1)
      t.initForm.NL = t.IdCard(t.initForm.CNUM, 3) 
      setTimeout(function() {
        t.doValidate(function() {
          if (t.save_flag) {
            t.save_flag = false
            t.ajax({
              serverId: 'WXBM00012',
              url: t.serverPath1,
              params: t.initForm,
              success: function(data) {
                console.log(data)
                if (data[0].CODE == 0) {
                  t.$toast('修改成功')
                  setTimeout(function() {
                    t.goBack(-2)
                  }, 2000)
                } else {
                  t.$toast(data[0].MSG)
                  t.save_flag = false
                }
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


.grzx_xg_sjhm .g-wp100>>>.van-button {
  width: 90%;
  margin: 20px 5% 0;
  height: 50px;
  padding: 0 10px;
}

.grzx_xg_sjhm>>>.van-cell {
  padding: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.grzx_xg_sjhm>>>.van-cell .van-cell__title {
  width: 120px;
  color: #606266;
}

.grzx_xg_sjhm>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.grzx_xg_sjhm .code-item>>>.van-button--small {
  color: #00c792;
  border-color: #00c792;
  border-radius: 3px;
  height: 25px;
}
</style>