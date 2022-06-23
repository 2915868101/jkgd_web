<template>
  <div class="jtys_xg_sjhm g-wrap g-hp100 s-bgcfff">
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
  name: 'jtys_xg_sjhm',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('修改手机号码') 
    if (t.queryData) {
      t.initForm.XM = t.queryData.NAME
      t.initForm.XB = t.queryData.XB
      t.initForm.CNUM = t.queryData.CARD_NUMBER
      t.initForm.HKDZ = t.queryData.HKDZ||''
      t.initForm.MZ = t.queryData.MZ||''
    }
    t.initForm.OPENID = t.wx_userInfo.openid
    t.initForm.TX = t.wx_userInfo.headimgurl
    console.log(t.initForm)
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
      getCodeText: '获取验证码',
      checkCode: '',
      code_ori: '',
      save_flag: true,
    }
  },
  methods: {
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
      if (!t.$store.state.rules.mobile.test(t.initForm.SJHM)) {
        t.$toast('请填写真实有效的手机号码')
        return false
      }

      //防止重复点击
      if (t.flag1) {
        t.flag1 = false
        //执行倒计时
        t.cutDown(60)
        t.checkCode = t.getId(4) 
        if (t.serverType == 'cz') { 
          t.sendCodeFun(t.initForm.SJHM, '您的手机验证码：' + t.checkCode + '，2分钟内有效，请确保本人操作！')
        } else { 
          alert(t.checkCode)
        }
      }
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
      console.log(t.initForm)
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
                  setTimeout(function(){
                    t.goBack(-1) 
                  },2000)
                } else {
                  t.$toast(data[0].MSG)
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


.jtys_xg_sjhm .g-wp100>>>.van-button {
  width: 90%;
  margin: 20px 5% 0;
  height: 50px;
  padding: 0 10px;
}

.jtys_xg_sjhm>>>.van-cell {
  padding: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.jtys_xg_sjhm>>>.van-cell .van-cell__title {
  width: 120px;
  color: #606266;
}

.jtys_xg_sjhm>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.jtys_xg_sjhm .code-item>>>.van-button--small {
  color: #00c792;
  border-color: #00c792;
  border-radius: 3px;
  height: 25px;
}
</style>