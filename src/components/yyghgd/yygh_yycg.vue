<template>
  <div id="yygh_yycg" class="yygh_yycg g-wrap s-bgcfff">
    <div class="s-bgcfff f-pt30 f-pb30 f-tac f-bb10">
      <van-loading type="spinner" color="#1989fa" size="24px" v-if="resultData.FLAG!=0&&resultData.FLAG!=2&&resultData.FLAG!=4">正在查询支付结果</van-loading>
      <div v-if="resultData.FLAG==0">
        <i class="icon_02"></i>
        <br />
        <span class="f-ib f-fs18 f-mt10 f-pl8">预约成功！</span>
        <br />
        <span class="f-ib s-cc7c7c7 f-mt5">{{resultData.CREATEDATE}}</span>
        <div class="f-lh20 f-mt10 f-pl20 f-pr20 f-tal"><span class="s-ce6a23c">您已成功预约挂号，请您根据预约时间提前30分钟到健康广德12320微信公众号进行取号，取号成功后可到选定科室等待就诊，取号步骤：健康大厅-个人中心-挂号记录 点击‘取号’</span></div>
      </div>
      <div v-if="resultData.FLAG==2||resultData.FLAG==4">
        <i class="icon_05"></i>
        <br />
        <span class="f-ib f-fs18 f-mt10 f-pl8">
          <span v-if="resultData.FLAG==2">预约失败！</span>
          <span v-if="resultData.FLAG==4">缴费异常！</span>
        </span>
        <br />
        <span class="f-ib s-cc7c7c7 f-mt5 f-pl30 f-pr30 f-lh22 f-pre">{{resultData.MSG}}</span>
      </div>
    </div>
    <div class="s-bgcfff m-lists-box f-mb40" v-if="resultData.FLAG==0">
      <div class="lis f-df">
        <span class="s-c8b8b8b f-taj c_01">就诊人：</span>
        <span class="s-c00c792 f-mr5 f-df1">{{resultData.HZXM}}</span>
      </div>
      <div class="lis">
        <span class="s-c8b8b8b">预约科室：</span>
        <span>{{resultData.DEPTNAME}}</span>
      </div>
      <div class="lis" v-if="resultData.YSXM">
        <span class="s-c8b8b8b">预约医生：</span>
        <span class="f-mr5">{{resultData.YSXM}}</span>
        <span>{{resultData.YSJB}}</span>
      </div>
      <div class="lis">
        <span class="s-c8b8b8b">挂号费用：</span>
        <span class="s-cff0e0e">&yen; {{Number(resultData.MONEY).toFixed(2)}}</span>
      </div>
      <div class="lis">
        <span class="s-c8b8b8b">预约时间：</span>
        <span class="s-c00c792">
          {{resultData.YYRQ}} {{getWeek(resultData.YYRQ)}}
          <span v-if="resultData.YYSD">{{resultData.YYSD}}</span>
          <span v-if="shiduan">
            <span v-if="!resultData.YYSD">{{getTimeNode(shiduan)}}</span>
            {{shiduan}}
          </span>
        </span>
      </div>
    </div>
    <div class="s-bgcfff f-mb40 f-pl15 f-pr15 f-pb15" v-if="resultData.FLAG==2">
      <div class="f-lh20">
        <span class="s-cff8c05">预约费用将在3个工作日原路退回。</span>
      </div>
    </div>
    <div class="s-bgcfff f-mb40 f-pl15 f-pr15 f-pb15" v-if="resultData.FLAG==4">
      <div class="f-lh20">
        <span class="s-cff8c05">管理员会确认缴费情况，若失败，缴费费用将在3个工作日原路退回。</span>
      </div>
    </div>
    <div class="s-bgcfff f-mb40 f-pl15 f-pr15 f-pb15" v-if="resultData.FLAG==0&&resultData.CARDNUM&&parseInt(IdCard(resultData.CARDNUM,3))<18">
      <div class="f-lh20">
        <span class="s-cff8c05">*就诊人未成年，若无就诊卡，请特别向窗口说明，已在移动端预约，并向收费窗口提供此次预约记录办理就诊卡并取号</span>
      </div>
    </div>
    <div class="u-sub-btn" @click="aa()" v-if="resultData.FLAG==0||resultData.FLAG==2||resultData.FLAG==4">退出</div>
    <van-popup v-model="isShow2" round class="popup" style="width:80%">
      <div class="f-pt20 f-pb20 f-pl20 f-pr20 f-tac">
        <div style="color:#e6a23c;font-size:14px;" class="f-fwb">挂号失败</div>
        <div class="f-fs14 f-lh28  f-pt15">
          {{index==10||index2==10?'可重新预约或到医院窗口人工缴费预约':sbfh.MSG}}
        </div>
        <div class="f-fs14 f-lh28  f-pt5">
          {{index==10||index2==10?'管理员会确认缴费情况，缴费费用将在3个工作日原路退回。':'缴费费用将在3个工作日原路退回'}}
        </div>
      </div>
      <div class="f-tac f-pt0 f-pb20">
        <van-button color="#e6a23c" class="f-pl12 f-pr10" @click="aa()" size="mini">我知道了</van-button>
      </div>
    </van-popup>
  </div>
</template>
<script>
  export default {
    name: "yygh_yycg",
    directives: {},
    components: {},
    created() {
      const t = this;
      t.trade = t.$route.query.trade;
      t.openid = t.$route.query.openid;
      t.isPaySuccess(t.trade);
      t.timer = setInterval(function() {
        t.isPaySuccess(t.trade);
      }, 1000);
      history.pushState({
        page: "yygh_yycg"
      }, null, "");
      addEventListener("popstate", t.closeWechat, false);
      setTimeout(function() {
        t.getGhjlLists();
      }, 300)
    },
    computed: {
      shiduan() {
        let [t, s] = [this, ""];
        if (t.resultData) {
          s =
            String(t.resultData.HYZJ).substr(11) +
            "-" +
            String(t.resultData.ZDMC).substr(11);
        }
        return s == "-" ? "" : s;
      },
    },
    data() {
      return {
        trade: "",
        openid: "",
        isSuccess: false,
        timer: "",
        resultData: "",
        resultData2: "",
        isShow2: false,
        sbfh: '',
        index: 0,
        index2: 0,
        // isShow: false
      };
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
          url: t.serverPath1_1,
          success(data) {
            console.log(11, data)
            t.resultData2 = data[0]
            t.index++
            if (t.index > 9) {
              clearInterval(t.timer);
              t.isShow2 = true
            }
            console.log(12313, t.index)
            if (data[0].FLAG == 0) {
              t.onSuccess(data[0])
            } else if (data[0].FLAG == 2 || t.index > 9) {
              t.onError(data)
            }
          }
        })
      },
      getGhjlLists() {
        var t = this
        console.log(t.resultData2)
        t.ajax({
          method: 'post',
          serverId: 'WXGD00018',
          params: {
            openid: t.resultData2.OPENID,
          },
          url: t.serverPath1_1,
          success(data) {
            console.log(t.openid)
            console.log(data[0])
            console.log(22, data)
            t.index2++
            if (t.index > 9) {
              clearInterval(t.timer);
              t.isShow2 = true
            }
            if (!data.length) {
              t.getGhjlLists()
            }
            if (data.length && t.resultData2.FLAG == 0) {
              t.resultData = t.resultData2
              t.doSaveGD(data[0])
            }
          }
        })
      },
      doSaveGD(info) {
        var t = this
        console.log(info)
        var params = {
          OPENID: t.openid,
          GHF: info.ORDERMON,
          PHONE: info.SJHM,
          TRADE: info.USERORDID,
          CARDNUM: info.CARDNUM,
          HZXM: info.XM,
          GHKS: info.KSMC,
          KSID: info.KSDM,
          YYRQ: info.YYSJ,
          GHYS: info.GHYS || '',
          YYSD: info.YYSD,
          ZZJB: info.YSJB,
          YSID: info.YSID,
          ORG_ID: 'guangde001',
          PBZXID: info.YYID,
          TIMERANGE: info.TIMEZONE,
          JZDZ: info.JZDZ,
          YYLSH: info.YYLSH,
          MZH: info.MZH,
          DJH: info.DJH,
        }
        console.log(params)
        t.ajax({
          method: 'post',
          serverId: 'WXBM00006',
          params: params,
          url: t.serverPath1,
          success(data) {
            console.log(33, data);
          }
        })
      },
      onSuccess() {
        const t = this;
        clearInterval(t.timer);
        t.isSuccess = true;
      },
      onError(data) {
        const t = this;
        clearInterval(t.timer);
        t.isShow2 = true
        t.sbfh = data[0]
        console.log(data)
        // t.$dialog
        //   .alert({
        //     title: "挂号失败",
        //     message: data[0].MSG,
        //   })
        //   .then(() => {
        //     t.closeWindow();
        //   });
      },
      closeWechat() {
        this.closeWindow();
        return false;
      },
      aa() {
        this.linkToPage('jkfw_index')
      }
    },
    mounted() {},
    beforeDestroy() {
      clearInterval(this.timer);
    },
  };
</script>
<style>
  .yygh_yycg {
    font-size: 14px;
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

  .u-res-btn,
  .u-sub-btn {
    margin: 15px;
    height: 41px;
    line-height: 40px;
    font-size: 16px;
    border-radius: 20px;
    text-align: center;
    border: 1px solid #00c792;
    color: #fff;
    background: #00c792;
  }
</style>