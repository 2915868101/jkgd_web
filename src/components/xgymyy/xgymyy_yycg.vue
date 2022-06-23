<template>
  <div id="xgymyy_yycg" class="xgymyy_yycg g-wrap s-bgcfff f-fs14">
    <div class="s-bgcfff f-pt50 f-pb30 f-tac f-mb50">
      <div>
        <i class="icon_02"></i><br />
        <span class="f-ib f-fs18 f-mt10 f-pl8">疫苗预约成功！</span><br />
        <span class="f-ib s-c909399 f-mt10 f-lh20">新冠疫苗预约成功，请留意您的<br />手机短信和微信公众号通知</span>
      </div>
    </div>
    <div class="g-wp100">
      <van-button class="f-bs3" round color="#00c792" @click="linkToPage('jkfw_index')">返回首页</van-button>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'xgymyy_yycg',
    directives: {},
    components: {},
    created() {
      var t = this
      t.updateTitle('预约完成')
      history.pushState({
        page: 'xgymyy_yycg'
      }, null, '')
      addEventListener('popstate', t.closeWechat, false)
    },
    computed: {},
    data() {
      return {}
    },
    methods: {
      closeWechat() {
        var t = this
        t.closeWindow()
        return false
      }
    },
    mounted() {},
    beforeDestroy() {
      var t = this
      clearInterval(t.timer)
    }
  }
</script>
<style>
  .xgymyy_yycg .van-button {
    width: 90%;
    margin: 20px 5%;
    height: 50px;
    padding: 0 10px;
  }

  .xgymyy_yycg .c_01 {
    width: 5em;
    height: 15px;
  }

  .xgymyy_yycg .icon_02,
  .xgymyy_yycg .icon_05 {
    display: inline-block;
    width: 45px;
    height: 45px;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: 100% auto;
  }

  .xgymyy_yycg .icon_02 {
    background-image: url(../../assets/img/zyjf/icon_02.svg);
  }

  .xgymyy_yycg .icon_05 {
    background-image: url(../../assets/img/zyjf/icon_05.svg);
  }

  .xgymyy_yycg .m-lists-box .lis {
    padding: 15px;
    border-bottom: 1px solid #f3f3f3;
  }
</style>