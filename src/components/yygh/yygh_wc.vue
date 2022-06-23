<template>
  <div class="yygh_wc g-wrap s-bgcfff f-fs14">
    <div class="s-bgcfff f-pt30 f-tac">
      <div class="f-bb10 f-pb20" v-if="queryData.FLAG==0">
        <i class="icon_02"></i><br />
        <span class="f-ib f-fs18 f-mt10 f-pl8">预约成功！</span><br />
        <div class="f-lh20 f-mt10"><span class="s-ce6a23c">请您在预约当天前往医院,凭身份证现场取号付款！ </span></div>
      </div>
      <div v-else>
        <i class="icon_05"></i><br />
        <span class="f-ib f-fs18 f-mt10 f-pl8">
          <span>预约失败！</span>
        </span><br />
        <div class="f-ib s-cc7c7c7 f-mt5 f-pl30 f-pr30 f-pt30 f-lh22 f-pre">{{queryData.MSG}}</div>
      </div>
    </div>
    <div class="s-bgcfff m-lists-box f-mb40" v-if="queryData.FLAG==0">
      <div class="lis f-df"><span class="s-c8b8b8b f-taj c_01">就诊人：</span><span class="s-c00c792 f-mr5 f-df1">{{queryData.XM}}</span></div>
      <div class="lis"><span class="s-c8b8b8b">预约科室：</span><span>{{queryData.GHKS}}</span></div>
      <div class="lis"><span class="s-c8b8b8b">预约医生：</span><span class="f-mr5">{{queryData.GHYS}}</span><span>{{queryData.YSJB}}</span></div>
      <div class="lis"><span class="s-c8b8b8b">预约时间：</span><span class="s-c00c792 f-mr5">{{queryData.YYRQ}}</span><span class="s-c00c792 f-mr5">{{queryData.TIMERANGE=="AM"?'上午':'下午'}}</span><span class="s-c00c792">{{queryData.YYSD}}</span></div>
    </div>
    <div class="f-lh20 f-pt20 f-pl20 f-pr20" v-if="queryData.FLAG==0">根据医院线上预约挂号诚信规则，连续3次爽约用户将纳入线上预约挂号黑名单，半年内无法在本系统线上挂号</div>
    <div class="g-wp100 fixed-bott">
      <van-button class="f-bs3" round color="#00c792" @click="aa()">退出</van-button>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'yygh_wc',
    directives: {},
    components: {},
    created() {
      var t = this
      t.updateTitle('预约完成')
      history.pushState({
        page: 'yygh_wc'
      }, null, '')
      addEventListener('popstate', t.closeWechat, false)
    },
    data() {
      return {
        isSuccess: false,
      }
    },
    methods: {
      closeWechat() {
        var t = this
        t.closeWindow()
        return false
      },
      aa() {
        this.linkToPage('jkfw_index')
      }
    },
    mounted() {},
  }
</script>
<style>
  .yygh_wc .van-button {
    width: 90%;
    margin: 20px 5%;
    height: 50px;
    padding: 0 10px;
  }

  .yygh_wc .c_01 {
    width: 5em;
    height: 15px;
  }

  .yygh_wc .icon_02,
  .yygh_wc .icon_05 {
    display: inline-block;
    width: 45px;
    height: 45px;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: 100% auto;
  }

  .yygh_wc .icon_02 {
    background-image: url(../../assets/img/zyjf/icon_02.svg);
  }

  .yygh_wc .icon_05 {
    background-image: url(../../assets/img/zyjf/icon_05.svg);
  }

  .yygh_wc .m-lists-box .lis {
    padding: 15px;
    border-bottom: 1px solid #f3f3f3;
  }

  .yygh_wc .fixed-bott {
    position: fixed;
    bottom: 40px;
  }
</style>