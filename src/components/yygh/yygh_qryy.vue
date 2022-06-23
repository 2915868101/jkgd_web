<template>
  <div class="yygh_qryy g-wrap">
    <van-cell-group>
      <van-cell title="医院">
        <div class="s-c303133">{{reserveForm.ORG_NAME}}</div>
      </van-cell>
      <van-cell title="科室">
        <div class="s-c303133">{{reserveForm.GHKS}}</div>
      </van-cell>
      <van-cell title="医生">
        <div class="s-c303133">{{reserveForm.GHYS}}</div>
      </van-cell>
      <van-cell title="挂号费">
        <div class="s-cf30b32">￥{{reserveForm.GHF}}</div>
      </van-cell>
      <van-cell title="预约时间">
        <div class="s-c303133">{{reserveForm.YYRQ}} {{reserveForm.YYSD}}</div>
      </van-cell>
      <van-cell title="就诊人" v-if="reserveForm.isGHPAY">
        <div class="s-c303133">{{reserveForm.HZXM}}</div>
      </van-cell>
      <van-cell title="就诊人" is-link @click="showPopup" v-else>
        <span class="s-c303133" v-if="reserveForm.HZXM">{{reserveForm.HZXM}}</span>
        <span v-else class="s-c999">请选择就诊人</span>
      </van-cell>
    </van-cell-group>
    <div class="tips-wrap f-mt10 s-bgcfff">
      <div class="head s-c303133 f-pb10">预约须知</div>
      <div class="cont s-cffa54d">本院就诊实行实名制挂号，请用本人真实身份证挂号就诊，否则挂号无效，后果自负。如需取消预约请在就诊前一天24点前线上取消，当日号无法取消。</div>
    </div>
    <van-popup v-model="isShowPopup" position="bottom" :style="{height : '30%'}">
      <div class="pop-head">
        <span>请选择就诊人</span>
        <van-icon name="cross" @click.stop="doClose"></van-icon>
      </div>
      <div class="pop-cont">
        <div class="select-list">
          <div class="select-item" v-for="item in patientList" :key="item.CNUM" @click="doSelect(item)">
            <div class="item-icon" v-if="!item.TX">
              <i class="iconfont icon-user"></i>
            </div>
            <img class="item-icon" v-else :src="item.TX" />
            <div class="item-name">{{item.XM}}</div>
          </div>
          <div class="select-item" @click="doAddCard">
            <div class="item-icon">
              <i class="iconfont icon-add"></i>
            </div>
            <div class="item-name">添加就诊人</div>
          </div>
        </div>
      </div>
    </van-popup>
    <div class="g-wp100">
      <van-button v-if="reserveForm.HZXM" class="item-btn f-bs3" round color="#00c792" @click="doSubmit">提交预约</van-button>
      <van-button v-else class="item-btn f-bs3" round color="#c0c4cc" disabled>提交预约</van-button>
    </div>
  </div>
</template>
<script>
  import querystring from 'querystring'

  export default {
    name: 'yygh_qryy',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('确认预约')
      t.reserveForm = t.queryData
      t.searchForm.OPENID = t.wx_userInfo.openid
      if (!t.reserveForm.CARDNUM) { //挂号记录点击付款的不需要修改就诊人
        t.getPatientList()
      }
    },
    data() {
      return {
        reserveForm: '',
        isShowPopup: false,
        patientList: [],
        searchForm: {
          FLAG: 'Query',
          OPENID: '',
          XM: '',
          CARDTYPE: '',
          CNUM: '',
          SJHM: ''
        }
      }
    },
    methods: {
      doAddCard() {
        var t = this
        if (t.serverHealthCard) {
          sessionStorage.setItem("prevUrl", t.$route.fullPath)
          var redirect_uri = t.wx_health_card + "/register_card?isRegister=true&openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl + '&prevUrl=' + t.$route.fullPath
          window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(encodeURI(encodeURI(redirect_uri)))
        } else {
          t.linkToPage('register_card', {
            isRegister: true
          })
        }
      },
      getPatientList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00014',
          url: t.serverPath1,
          params: t.searchForm,
          success: function(data) {
            t.patientList = data[0].CONTENTS || []
            console.log(t.patientList)
            var row = t.patientList.find(function(row) {
              return row.BZ == '1'
            })
            console.log(1111111111, row)
            t.reserveForm.HZXM = row.XM
            t.reserveForm.CARDNUM = row.CNUM
            t.reserveForm.SJHM = row.SJHM
          }
        })
      },
      showPopup() {
        var t = this
        t.isShowPopup = true
      },
      doClose() {
        this.isShowPopup = false
      },
      doSelect(item) {
        var t = this
        t.isShowPopup = false
        console.log(item)
        t.reserveForm.HZXM = item.XM
        t.reserveForm.CARDNUM = item.CNUM
        t.reserveForm.SJHM = item.SJHM
      },
      doSubmit() {
        var t = this
        var params = {
          MONEY: t.reserveForm.GHF,
          ORG_ID: t.reserveForm.ORG_ID,
          NUMBERID: t.reserveForm.NUMBERID,
          YSID: t.reserveForm.YSID,
          PBKSID: t.reserveForm.KSID,
          PBKS: t.reserveForm.GHKS,
          YSXM: t.reserveForm.GHYS,
          HZXM: t.reserveForm.HZXM,
          YYRQ: t.reserveForm.YYRQ,
          PBLX: t.reserveForm.PBLX,
          PHONE: t.reserveForm.SJHM,
          CARDNUM: t.reserveForm.CARDNUM,
          ZZJB: t.reserveForm.ZZJB,
          TIMERANGE: t.reserveForm.TIMERANGE,
          YYSD: t.reserveForm.YYSD,
          PBZXID: t.reserveForm.NUMBERID, //
          GGLX: t.reserveForm.PBLX, //
          USEID: t.reserveForm.CARDNUM, //
          BRXM: t.reserveForm.HZXM, //
          KSID: t.reserveForm.KSID, //
        }
        console.log(7987979, params)
        if (t.serverType == 'cz' || (t.serverType == 'gd' && params.YYRQ != t.$moment().format('YYYY-MM-DD'))) { //不付款的调用预约接口
          t.doOrder()
        } else if (t.serverType == 'gd' && params.YYRQ == t.$moment().format('YYYY-MM-DD')) { //健康广德当天挂号付款，非当天的调用预约的接口暂不付款
          console.log(Number(t.reserveForm.GHF))
          console.log(Number(t.reserveForm.GHF) == 0)
          if (Number(t.reserveForm.GHF) == 0) {
            t.doOrder('WXGDYYGHTZH04')
          } else {
            t.getPayUrl()
          }
        } else { //php跳转支付后 返回到 yygh_yycg 页面
          console.log(querystring.stringify(params))
          window.location.href = 'http://www.hnwisdom.com/BMFW/dist/public/weixin/wxpay/example/jsapi_gh_bmfw_chizhou.php?' + querystring.stringify(params)
        }
        console.log(7897123, t.reserveForm)
      },
      doOrder(serverId) {
        var t = this
        var params = {
          OPENID: t.wx_userInfo.openid,
          GHF: t.reserveForm.GHF,
          ORG_ID: t.reserveForm.ORG_ID,
          NUMBERID: t.reserveForm.NUMBERID,
          YSID: t.reserveForm.YSID,
          KSID: t.reserveForm.KSID,
          GHKS: t.reserveForm.GHKS,
          GHYS: t.reserveForm.GHYS,
          HZXM: t.reserveForm.HZXM,
          YYRQ: t.reserveForm.YYRQ,
          PBLX: t.reserveForm.PBLX,
          PHONE: t.reserveForm.SJHM,
          CARDNUM: t.reserveForm.CARDNUM,
          ZZJB: t.reserveForm.ZZJB,
          TIMERANGE: t.reserveForm.TIMERANGE,
          YYSD: t.reserveForm.YYSD,
          GHTYPE: t.reserveForm.GHTYPE,
          PBZXID: t.reserveForm.PBZXID
        }
        var serverName = ''
        if (serverId) {
          serverName = serverId
        } else {
          serverName = t.serverType == 'gd' ? 'WXGDYYGHTZH03' : 'WXBM00047'
        }
        console.log(serverName)
        t.ajax({
          serverId: serverName,
          url: t.serverPath1,
          params: params,
          success: function(data) {
            console.log(data)
            if (data[0].CODE == '0') {
              t.linkToPage('yygh_wc', {
                FLAG: 0,
                XM: t.reserveForm.HZXM,
                GHKS: t.reserveForm.GHKS,
                GHYS: t.reserveForm.GHYS,
                YYRQ: t.reserveForm.YYRQ,
                TIMERANGE: t.reserveForm.TIMERANGE,
                YYSD: t.reserveForm.YYSD,
                ORG_NAME: t.reserveForm.ORG_NAME
              })
            } else {
              t.linkToPage('yygh_wc', {
                FLAG: -1,
                MSG: data[0].MSG
              })
            }
          }
        })
      },
      getPayUrl() {
        var t = this
        var params = {
          NUMBERID: t.reserveForm.NUMBERID,
          HZXM: t.reserveForm.HZXM,
          PBLX: t.reserveForm.PBLX,
          ZZJB: t.reserveForm.ZZJB,
          TIMERANGE: t.reserveForm.TIMERANGE,

          OPENID: t.wx_userInfo.openid,
          CARDNUM: t.reserveForm.CARDNUM,
          GHF: t.reserveForm.GHF,
          GHKS: t.reserveForm.GHKS,
          YYRQ: t.reserveForm.YYRQ,
          YYSD: t.reserveForm.YYSD,
          GHTYPE: t.reserveForm.GHTYPE,
          GHYS: t.reserveForm.GHYS,
          YSID: t.reserveForm.YSID,
          KSID: t.reserveForm.KSID,
          HYZJ: t.reserveForm.TIMERANGE,
          ZFXX: '',
          BRXM: t.reserveForm.HZXM,
          PHONE: t.reserveForm.SJHM,
          ORG_ID: t.reserveForm.ORG_ID,
          PBZXID: t.reserveForm.PBZXID, //
          GGLX: t.reserveForm.PBLX, //
          USEID: t.reserveForm.CARDNUM, //
        }
        console.log(params)
        t.ajax({
          serverId: 'WXGDYYGHTZH01',
          url: t.serverPath1,
          params: params,
          success: function(data) {
            console.log(213, params)
            console.log(12, data)
            window.location.href = data[0].QRCODE
            // sessionStorage.setItem("yygh_trade", data[0].TRADE)
            // if (data[0].CODE == 0) {
            //   t.linkToPage('yygh_wc', {
            //     FLAG: 0,
            //     XM: t.reserveForm.HZXM,
            //     GHKS: t.reserveForm.GHKS,
            //     GHYS: t.reserveForm.GHYS,
            //     YYRQ: t.reserveForm.YYRQ,
            //     TIMERANGE: t.reserveForm.TIMERANGE,
            //     YYSD: t.reserveForm.YYSD,
            //     ORG_NAME: t.reserveForm.ORG_NAME
            //   })
            // }
          }
        })
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .yygh_qryy>>>.van-cell {
    padding: 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
  }

  .yygh_qryy>>>.van-cell__title {
    color: #606266;
  }

  .yygh_qryy>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .yygh_qryy .tips-wrap {
    font-size: 15px;
    padding: 15px;
  }

  .yygh_qryy .tips-wrap .head {
    font-weight: bold;
  }

  .yygh_qryy .tips-wrap .cont {
    line-height: 24px;
  }

  .yygh_qryy>>>.van-button {
    width: 90%;
    margin: 20px 5%;
    height: 50px;
    padding: 0 10px;
  }

  /*popup 样式*/
  .yygh_qryy .pop-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 15px;
    color: #c0c4cc;
    padding: 15px;
  }

  .yygh_qryy .pop-cont {
    font-size: 15px;
    padding: 15px;
  }

  .yygh_qryy .select-list {
    display: flex;
    flex-wrap: wrap;
    text-align: center;
  }

  .yygh_qryy .select-list .select-item {
    width: 25%;
    margin-bottom: 15px;
  }

  .yygh_qryy .select-list .select-item .item-icon {
    display: inline-block;
    background: #d2f6ec;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
    margin-bottom: 8px;
  }

  .yygh_qryy .select-list .select-item .iconfont {
    color: #00c792;
    font-size: 28px;
  }
</style>