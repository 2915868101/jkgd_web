<template>
  <div id="yygh_yycg" class="yygh_yycg g-wrap s-bgcfff f-fs14">
    <div class="s-bgcfff f-pt30 f-pb30 f-tac f-bb10 ">
      <van-loading size="24px" v-if="resultData.FLAG!=0&&resultData.FLAG!=2&&resultData.FLAG!=4">正在查询支付结果</van-loading>
      <div v-if="resultData.FLAG==0">
        <i class="icon_02"></i><br />
        <span class="f-ib f-fs18 f-mt10 f-pl8">预约成功！</span><br />
        <span class="f-ib s-cc7c7c7 f-mt5">{{resultData.CREATEDATE}}</span>
        <div class="f-lh20 f-mt10 f-pl20 f-pr20 f-tal"><span class="s-ce6a23c">请您于{{resultData.YYRQ}} {{getWeek(resultData.YYRQ)}} 提前30分钟在 “健康大厅-个人中心-挂号记录”中执行取号操作 </span></div>
      </div>
      <div v-if="resultData.FLAG==2||resultData.FLAG==4">
        <i class="icon_05"></i><br />
        <span class="f-ib f-fs18 f-mt10 f-pl8">
          <span v-if="resultData.FLAG==2">预约失败！</span>
          <span v-if="resultData.FLAG==4">缴费异常！</span>
        </span><br />
        <span class="f-ib s-cc7c7c7 f-mt5 f-pl30 f-pr30 f-lh22 f-pre">{{resultData.MSG}}</span>
      </div>
    </div>
    <div class="s-bgcfff m-lists-box f-mb40" v-if="resultData.FLAG==0">
      <div class="lis f-df"><span class="s-c8b8b8b f-taj c_01">就诊人：</span><span class="s-c00c792 f-mr5 f-df1">{{resultData.HZXM}}</span></div>
      <div class="lis"><span class="s-c8b8b8b">预约科室：</span><span>{{resultData.DEPTNAME}}</span></div>
      <div class="lis" v-if="resultData.YSXM"><span class="s-c8b8b8b">预约医生：</span><span class="f-mr5">{{resultData.YSXM}}</span><span>{{resultData.YSJB==0?'普通医生':'专家医生'}}</span></div>
      <div class="lis"><span class="s-c8b8b8b">挂号费用：</span><span class="s-cf30b32">&yen; {{Number(resultData.MONEY).toFixed(2)}}</span></div>
      <div class="lis"><span class="s-c8b8b8b">预约时间：</span><span class="s-c00c792">{{resultData.YYRQ}} {{getWeek(resultData.YYRQ)}} <span v-if="resultData.YYSD">{{resultData.YYSD}}</span> <span v-if="shiduan"><span v-if="!resultData.YYSD">{{getTimeNode(shiduan)}}</span> {{shiduan}}</span></span></div>
    </div>
    <div class="s-bgcfff f-mb40 f-pl15 f-pr15 f-pb15 f-pt20" v-if="resultData.FLAG==2">
      <div class="f-lh20"><span class="s-cff8c05">预约费用将在3个工作日原路退回。</span></div>
    </div>
    <div class="s-bgcfff f-mb40 f-pl15 f-pr15 f-pb15 f-pt20" v-if="resultData.FLAG==4">
      <div class="f-lh20"><span class="s-cff8c05">管理员会确认缴费情况，若失败，缴费费用将在3个工作日原路退回。 </span></div>
    </div>
    <div class="s-bgcfff f-mb40 f-pl15 f-pr15 f-pb15 f-pt20" v-if="resultData.FLAG==0&&resultData.CARDNUM&&parseInt(IdCard(resultData.CARDNUM,3))<18">
      <div class="f-lh20"><span class="s-cff8c05">*就诊人未成年，若无就诊卡，请特别向窗口说明，已在移动端预约，并向收费窗口提供此次预约记录办理就诊卡并取号 </span></div>
    </div>
    <div class="f-lh20 f-pt20 f-pl15 f-pr15 s-c999" v-if="resultData.FLAG==0">温馨提示：根据医院线上预约挂号诚信规则，连续3次爽约用户将纳入线上预约挂号黑名单，半年内无法在本系统线上挂号</div>
    <div class="g-wp100">
      <van-button class="f-bs3" round color="#00c792" @click="aa" v-if="resultData.FLAG==0||resultData.FLAG==2||resultData.FLAG==4">退出</van-button>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'yygh_yycg',
    directives: {},
    components: {},
    created() {
      var t = this
      t.updateTitle('预约完成')
      t.trade = t.$route.query.trade
      t.isPaySuccess(t.trade)
      // t.isPaySuccess('ydd-gh202006051127586589239')
      t.timer = setInterval(function() {
        t.isPaySuccess(t.trade)
      }, 1000)
      history.pushState({
        page: 'yygh_yycg'
      }, null, '')
      addEventListener('popstate', t.closeWechat, false)
    },
    computed: {
      // shiduan() {
      //   var t = this
      //   if (t.resultData) {
      //     var s = String(t.resultData.HYZJ).substr(11) + '-' + String(t.resultData.ZDMC).substr(11)
      //     return s == '-' ? '' : s
      //   }
      // }
    },
    data() {
      return {
        trade: '',
        isSuccess: false,
        timer: '',
        resultData: {}
      }
    },
    methods: {
      isPaySuccess(trade) {
        var t = this
        t.ajax({
          method: 'post',
          loading: false,
          params: {
            trade: trade
          },
          serverId: 'WXGD00005',
          url: t.serverPath1,
          success(data) {
            t.resultData = data[0] || {}
            console.log(data)
            if (data.FLAG == 0) {
              t.onSuccess(data)
            } else if (data.FLAG != 0 && data.FLAG != 3) {
              // t.onError(data)
            }
          }
        })
      },
      onSuccess() {
        var t = this
        clearInterval(t.timer)
        t.isSuccess = true
      },
      onError(data) {
        var t = this
        clearInterval(t.timer)
        t.$dialog.alert({
          title: '缴费失败',
          message: data.MSG,
        }).then(() => {
          t.closeWindow()
        })
      },
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
    beforeDestroy() {
      var t = this
      clearInterval(t.timer)
    }
  }
</script>
<style>
  .yygh_yycg .van-button {
    width: 90%;
    margin: 20px 5%;
    height: 50px;
    padding: 0 10px;
  }

  .yygh_yycg .c_01 {
    width: 5em;
    height: 15px;
  }

  .yygh_yycg .icon_02,
  .yygh_yycg .icon_05 {
    display: inline-block;
    width: 45px;
    height: 45px;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: 100% auto;
  }

  .yygh_yycg .icon_02 {
    background-image: url(../../assets/img/zyjf/icon_02.svg);
  }

  .yygh_yycg .icon_05 {
    background-image: url(../../assets/img/zyjf/icon_05.svg);
  }

  .yygh_yycg .m-lists-box .lis {
    padding: 15px;
    border-bottom: 1px solid #f3f3f3;
  }
</style>