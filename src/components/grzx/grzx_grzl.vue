<template>
  <div class="grzx_grzl g-wrap g-hp100 s-bgcfff">
    <div v-show="userInfo">
      <van-field right center disabled>
        <template #label>
          <span class="s-c909399">头像</span>
        </template>
        <template #button>
          <img class="headimg" :src="userInfo.TX||wx_userInfo.headimgurl||$store.state.def_headimg">
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="userInfo.NAME" readonly>
        <template #label>
          <span :class="[userInfo.NAME?'s-c909399':'s-c303133']">姓名：</span>
        </template>
      </van-field>
      <van-field input-align="right" readonly>
        <template #label>
          <span :class="[userInfo.XB?'s-c909399':'s-c303133']">性别：</span>
        </template>
        <template #input>
          {{userInfo.XB=='1'?'男':'女'}}
        </template>
      </van-field>
      <van-field input-align="right" v-model="userInfo.CARD_NUMBER" readonly>
        <template #label>
          <span :class="[userInfo.CARD_NUMBER?'s-c909399':'s-c303133']">身份证号码：</span>
        </template>
      </van-field>
      <van-field input-align="right" v-model="userInfo.SJHM" readonly @click="linkToPage('grzx_xg_sjhm',userInfo)">
        <template #label>
          <span :class="[userInfo.SJHM?'s-c909399':'s-c303133']">手机号码：</span>
        </template>
        <template #input>
          <span>{{userInfo.SJHM}}</span><i class="iconfont icon-arrow-right f-fs12 s-c606266 f-ml5"></i>
        </template>
      </van-field>
    </div>
  </div>
</template>
<script>
export default {
  name: 'grzx_grzl',
  components: {},
  created() {
    var t = this
    t.updateTitle('个人资料')
    t.userInfo = t.queryData
  },
  computed: {},
  data() {
    return {
      userInfo: ''
    }
  },
  methods: {},
  mounted() {}
}
</script>
<style scoped>
.grzx_grzl>>>.van-cell {
  padding: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.grzx_grzl>>>.van-cell .headimg {
  width: 45px;
  height: 45px;
  border-radius: 50%;
}

.grzx_grzl>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.grzx_grzl>>>.van-cell .van-cell__value {
  color: #606266;
}

.grzx_grzl .code-item>>>.van-button--small {
  color: #00c792;
  border-color: #00c792;
  border-radius: 3px;
  height: 25px;
}
</style>