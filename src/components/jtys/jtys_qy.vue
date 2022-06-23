<template>
  <div class="jtys_qy g-wrap g-hp100 s-bgcfff">
    <van-notice-bar class="f-fs13" wrapable :scrollable="false">
      签约家庭医生以注册公众号身份证号码 申请签约。
    </van-notice-bar>
    <div class="register-wrap f-pt20">
      <van-field clearable v-model="initForm.ZJHM" maxlength="18" label="注册身份证号码" placeholder="请输入身份证号码" @blur="onBlurZjhmInput" readonly />
    </div>
    <div class="g-wp100 f-pt30">
      <van-button class="item-btn f-bs3" color="#00c792" round @click="toQy01">下一步</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'jtys_qy',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('签约信息')
    // 判断是否注册
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function(data) { 
      t.initForm.ZJHM = data.CONTENTS[0].CARD_NUMBER 
    }) 
  },
  data() {
    return {
      initForm: {
        XM: '',
        ZJHM: '',
        HKDZ: '',
        PHONE: '',
      }
    }
  },
  methods: { 
    onBlurZjhmInput() {
      var t = this
      if (t.regIdCard(t.initForm.ZJHM)) {
        console.log(t.initForm)
      } else {
        t.$dialog.alert({
          title: '提示',
          message: '身份证号码错误'
        })
      }
    },
    doValidate(cb) {
      var t = this
      if (!t.initForm.ZJHM) {
        t.$dialog.alert({
          title: '提示',
          message: '身份证号不能为空'
        })
        return false
      } else if (!t.regIdCard(t.initForm.ZJHM)) {
        t.$dialog.alert({
          title: '提示',
          message: '请填写真实有效的身份证号'
        })
        return false
      } else {
        if (typeof cb == 'function') {
          cb()
        }
      }
    },
    toQy01() {
      var t = this
      setTimeout(function() {
        t.doValidate(function() {
          t.linkToPage('jtys_qy_01')
        })
      }, 50)
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}


.jtys_qy .g-wp100>>>.van-button {
  width: 90%;
  margin: 20px 5% 0;
  height: 50px;
  padding: 0 10px;
}

.jtys_qy>>>.van-cell {
  padding: 15px 20px;
  border-bottom: 1PX solid #ebeef5;
}

.jtys_qy>>>.van-cell .van-cell__title {
  width: 120px;
  color: #606266;
}

.jtys_qy>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.jtys_qy .code-item>>>.van-button {
  color: #00c792;
  border-color: #00c792;
  border-radius: 3px;
  height: 25px;
  width: 140px;
  margin-right: 15px;
  padding: 0;
}
</style>