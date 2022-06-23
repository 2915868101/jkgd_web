<template>
  <div id="hsyy_yycg" class="hsyy_yycg g-wrap s-bgcfff">
    <div class="s-bgcfff f-pt30 f-pb30 f-tac f-bb10">
      <van-loading type="spinner" color="#1989fa" size="24px" v-if="resultData.FLAG!=0&&resultData.FLAG!=2&&resultData.FLAG!=4">正在查询支付结果</van-loading>
      <div v-if="resultData.FLAG==0">
        <i class="icon_02"></i>
        <br />
        <span class="f-ib f-fs18 f-mt10 f-pl8">核酸检测预约成功！</span>
        <br />
        <span class="f-ib s-cc7c7c7 f-mt5">{{resultData.CREATEDATE}}</span>
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
        <span class="s-c8b8b8b">检测人：</span>
        <span class="s-c00c792 f-mr5 f-df1">{{resultData.HZXM}}</span>
      </div>
      <div class="lis">
        <span class="s-c8b8b8b">预约项目：</span>
        <span>{{resultData.DEPTNAME}}</span>
      </div>
      <div class="lis">
        <span class="s-c8b8b8b">检测地点：</span>
        <span class="f-mr5 f-lh20">广德市人民医院<br />急诊医技楼南侧核酸采样点</span>
      </div>
      <div class="lis">
        <span class="s-c8b8b8b">预约费用：</span>
        <span class="s-cf30b32">&yen; {{Number(resultData.MONEY).toFixed(2)}}</span>
      </div>
      <div class="lis">
        <span class="s-c8b8b8b">采样时间段：</span>
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
        <span class="s-cff8c05">*预约提示：请按预约检测日期的预约时间段，携带相关证件准时来院检测。</span>
      </div>
    </div>
    <div class="u-sub-btn" @click="aa" v-if="resultData.FLAG==0||resultData.FLAG==2||resultData.FLAG==4">退出</div>
  </div>
</template>
<script>
  export default {
    name: "hsyy_yycg",
    directives: {},
    components: {},
    created() {
      const t = this;
      t.trade = t.$route.query.trade;
      t.openid = t.$route.query.openid;
      t.isPaySuccess(t.trade);
      t.timer = setInterval(function() {
        t.isPaySuccess(t.trade);
      }, 600);
      history.pushState({
        page: "hsyy_yycg"
      }, null, "");
      addEventListener("popstate", t.closeWechat, false);
      setTimeout(function() {
        t.doSaveHSYY(t.resultData)
      }, 1000)
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
            console.log(data)
            t.resultData = data[0]
            console.log(t.resultData)
            if (t.resultData.FLAG == 0) {
              t.onSuccess(t.resultData)
            } else if (t.resultData.FLAG != 0 && t.resultData.FLAG != 3) {
              // t.onError(data)
            }
          }
        })
      },
      // 在健康广德保存记录
      doSaveHSYY(info) {
        var t = this
        console.log(info)
        var params = {
          OPENID: t.openid,
          GHF: info.MONEY,
          PHONE: info.PHONE,
          TRADE: info.TRADE,
          CARDNUM: info.CARDNUM,
          HZXM: info.HZXM,
          GHKS: info.KSMC,
          KSID: info.KSDM,
          YYRQ: info.YYRQ,
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
          FLAG: info.FLAG,
        }
        console.log(params)
        t.ajax({
          method: 'post',
          serverId: 'WXHSJCTZH01',
          params: params,
          url: t.serverPath1,
          success(data) {
            console.log(data);
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
        t.$dialog
          .alert({
            title: "缴费失败",
            message: data.MSG,
          })
          .then(() => {
            t.closeWindow();
          });
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
  .hsyy_yycg {
    font-size: 14px;
  }

  .hsyy_yycg .c_01 {
    width: 5em;
    height: 15px;
  }

  .hsyy_yycg .icon_02,
  .hsyy_yycg .icon_05 {
    display: inline-block;
    width: 45px;
    height: 45px;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: 100% auto;
  }

  .hsyy_yycg .icon_02 {
    background-image: url(../../assets/img/zyjf/icon_02.svg);
  }

  .hsyy_yycg .icon_05 {
    background-image: url(../../assets/img/zyjf/icon_05.svg);
  }

  .hsyy_yycg .m-lists-box .lis {
    padding: 15px;
    display: flex;
    border-bottom: 1px solid #f3f3f3;
  }

  .hsyy_yycg .m-lists-box .lis span:first-child {
    width: 85px;
    text-align: justify;
    text-align-last: justify;
  }

  .hsyy_yycg .m-lists-box .lis span:last-child {
    width: calc(100% - 85px);
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