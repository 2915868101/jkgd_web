<template>
  <div class="hzlyb g-wrap g-hp100 s-bgcfff">
    <div class="box-wrap s-bgcfff f-bb1">
      <div class="b-head">
        <b class="b-border f-mr8"></b>
        <span class="f-fwb">欢迎留言</span>
      </div>
    </div>
    <div class="g-wp100 s-bgcfff f-pb30">
      <van-cell-group>
        <van-field v-model="messageForm.XM" placeholder="请输入您的姓名">
          <template #label>
            <span :class="[messageForm.XM?'s-c909399':'s-c303133']">姓名：</span>
          </template>
        </van-field>
        <van-field v-model="messageForm.PHONE" placeholder="请输入您的手机号码" maxlength="11">
          <template #label>
            <span :class="[messageForm.PHONE?'s-c909399':'s-c303133']">手机号码：</span>
          </template>
        </van-field>
        <van-field v-model="messageForm.EMAIL" placeholder="请输入您的邮箱地址">
          <template #label>
            <span :class="[messageForm.EMAIL?'s-c909399':'s-c303133']">邮箱地址：</span>
          </template>
        </van-field>
        <van-field v-model="messageForm.TOPIC" placeholder="请输入主题,不超过20字" maxlength="20">
          <template #label>
            <span :class="[messageForm.TOPIC?'s-c909399':'s-c303133']">留言主题：</span>
          </template>
        </van-field>
        <van-field v-model="messageForm.MSG" rows="5" autosize type="textarea" maxlength="150" placeholder="请输入留言内容" show-word-limit>
          <template #label>
            <span :class="[messageForm.MSG?'s-c909399':'s-c303133']">留言内容：</span>
          </template>
        </van-field>
      </van-cell-group>
      <div class="f-pt20 f-pl15 f-pr15 f-fs13 s-c909399">* 我们将通过电话或邮箱方式与您取得联系。</div>
      <div class="g-wp100 f-pt30">
        <van-button class="item-btn f-bs3" color="#00c792" round @click="doSave">提交</van-button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'hzlyb',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle('留言板')
    console.log(t.wx_userInfo)
    t.messageForm.OPENID = t.wx_userInfo.openid
    t.messageForm.NAME = t.wx_userInfo.nickname
  },
  data() {
    return {
      messageForm: {
        OPENID: '',
        NAME: '',
        XM:'',
        PHONE: '',
        EMAIL: '',
        TOPIC: '',
        MSG: '',
      },
      save_flag: true
    }
  },
  methods: {
    doValidate(cb) {
      var t = this
      if (!t.messageForm.XM) {
        t.$toast('姓名不能为空')
        return false
      } else if (!t.messageForm.PHONE&&!t.messageForm.EMAIL) {
        t.$toast('手机号码和邮箱必填一个')
        return false
      } else if (t.messageForm.PHONE&&!t.$store.state.rules.mobile.test(t.messageForm.PHONE)) {
        t.$toast('请填写真实有效的手机号')
        return false
      } else if (t.messageForm.EMAIL&&!t.$store.state.rules.email.test(t.messageForm.EMAIL)) {
        t.$toast('请填写真实有效的邮箱地址')
        return false
      } else if (!t.messageForm.TOPIC) {
        t.$toast('留言主题不能为空')
        return false
      } else if (!t.messageForm.MSG) {
        t.$toast('留言内容不能为空')
        return false
      } else {
        if (typeof cb == 'function') {
          cb()
        }
      }
    },
    doSave() {
      var t = this;
      setTimeout(function() {
        t.doValidate(function() {
          if (t.save_flag) { 
            t.save_flag = false
            t.ajax({
              method: 'post',
              serverId: 'WXBMTZH05',
              params: t.messageForm,
              url: t.serverPath1,
              success(data) { 
                if (data.length && data[0].CODE == '0') {
                  t.$toast.success('提交成功')
                  t.messageForm.XM = ''
                  t.messageForm.PHONE = ''
                  t.messageForm.EMAIL = ''
                  t.messageForm.TOPIC = ''
                  t.messageForm.MSG = ''
                } else {
                  t.$toast('提交失败')
                }
                t.save_flag = true
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

.hzlyb .g-wp100>>>.van-button {
  width: 90%;
  margin: 20px 5% 0;
  height: 50px;
  padding: 0 10px;
}

.hzlyb>>>.van-cell {
  padding: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.hzlyb>>>.van-cell .headimg {
  width: 45px;
  height: 45px;
  border-radius: 50%;
}

.hzlyb>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.hzlyb>>>.van-cell .van-cell__value {
  color: #606266;
}
</style>