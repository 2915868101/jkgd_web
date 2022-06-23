<template>
  <div class="people_detail g-wrap g-hp100">
    <div class="wrap-box f-bs3">
      <div class="wrap-top f-pt20 f-pl10 f-pr10">
        <div class="qrcode-block">
          <img class="qrcode-logo" src="../../assets/img/register/logo_.png" alt="" />
          <!-- <div class="qrcode"></div> -->
          <!-- <div v-if="patientInfo.QRCODE" class="qrcode" ref="qrCodeUrl"></div> -->
          <qrcode-vue v-if="patientInfo.QRCODE" size="170" level="H" :value="patientInfo.QRCODE" :foreground="patientInfo.color"></qrcode-vue>
          <img v-else class="qrcode" src="../../assets/img/register/qrcode.png" alt="">
        </div>
        <div class="tips f-pb20 s-c909399 f-fs13">就诊时出示此二维码</div>
      </div>
      <van-cell-group>
        <van-cell title="姓名" :value="patientInfo.XM" />
        <van-cell title="身份证号" :value="regNumber(patientInfo.CNUM,4)" />
        <van-cell title="手机号码" :value="regNumber(patientInfo.SJHM,2)" />
        <van-cell title="设为默认">
          <van-switch v-model="is_def" active-color="#00c792" size="18" @change="doChoose" />
        </van-cell>
        <div v-if="patientInfo.SFBR!='1'" class="btn-item" @click="doDelete">解绑电子健康卡</div>
      </van-cell-group>
    </div>
    <div class="button">
      <!--  <div class="addbtn" id="addcard" @click="doEnter">进入卡包</div> -->
    </div>
  </div>
</template>
<script>
  import QrcodeVue from 'qrcode.vue'

  export default {
    name: 'people_detail',
    components: {
      QrcodeVue
    },
    computed: {},
    created() {
      var t = this
      t.updateTitle('电子健康卡信息')
      t.searchForm.OPENID = t.wx_userInfo.openid
      t.searchForm.XM = t.queryData.XM
      t.searchForm.CNUM = t.queryData.CNUM
      t.searchForm.SJHM = t.queryData.SJHM
      t.searchForm.CARDTYPE = t.queryData.CARDTYPE
      t.is_def = t.queryData.BZ == 1 ? true : false
      t.patientInfo = t.queryData
      console.log(t.patientInfo)
      // t.creatQrCode(t.queryData.QRCODE)
      if (t.serverType == 'cz' || t.serverType == 'gd') { //动态二维码
        t.getCODE()
      }
    },
    data() {
      return {
        patientInfo: '',
        searchForm: {
          OPENID: '',
          FLAG: '',
          XM: '',
          CARDTYPE: '',
          CNUM: '',
          SJHM: ''
        },
        is_def: '',
      }
    },
    methods: {
      // 进入卡包
      doEnter() {
        var t = this
        t.ajax({
          serverId: 'WXBM00032',
          url: t.serverPath1,
          params: {
            ORDERID_QRCODETEXT: t.queryData.QRCODE
          },
          success: function(data) {
            if (data[0].CODE == 0) {
              var _data = JSON.parse(data[0].rsp)
              console.log(_data)
              var orderId = _data.orderId
              console.log(orderId);
              var redirect_uri = t.wx_health_card + "/people_lists?openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl
              window.location.href = 'https://health.tengmed.com/open/takeMsCard?order_id=' +
                orderId + '&redirect_uri=' + encodeURIComponent(redirect_uri);
            } else {
              t.$toast(data[0].MSG)
            }
          },
          error: function(data) {
            t.$toast(data.MSG)
            console.log(9999, data)
          }
        })
      },
      getCODE() {
        var t = this
        t.ajax({
          serverId: 'WXBM00032',
          url: t.serverPath1,
          params: {
            HEALTHCARDID: t.queryData.ECID,
            IDTYPE: '01',
            IDNUMBER: t.queryData.QRCODE,
            CODETYPE: 0,
          },
          success: function(data) {
            console.log(666, data)
            var _data = JSON.parse(data[0].rsp)
            console.log(_data)
            if (_data.color == '0') {
              t.patientInfo.color = '#303133'
            } else if (_data.color == '1') {
              t.patientInfo.color = '#246a0a'
            } else if (_data.color == '2') {
              t.patientInfo.color = '#ffc200'
            } else if (_data.color == '3') {
              t.patientInfo.color = '#f90618'
            }
            t.patientInfo.QRCODE = _data.qrCodeText
            t.patientInfo.QRCODEIMG = _data.qrCodeImg
          },
          error: function(data) {
            t.$toast(data.MSG)
          }
        })
      },
      // 设置默认健康卡
      setDefFunc(flag) {
        var t = this
        t.searchForm.FLAG = flag
        console.log(t.searchForm)
        t.ajax({
          serverId: 'WXBM00015',
          url: t.serverPath1,
          params: t.searchForm,
          success: function(data) {
            console.log(data[0].CONTENTS)
          },
          error: function(data) {
            t.$toast(data.MSG)
          }
        })
      },
      doChoose() {
        this.setDefFunc('Choose')
      },
      // 解绑电子健康卡
      doDelete() {
        var t = this
        t.$dialog.confirm({
          message: '确认解绑电子健康卡？'
        }).then(() => {
          t.ajax({
            serverId: 'WXBM00044',
            url: t.serverPath1,
            params: {
              FLAG: 'Delete',
              OPENID: t.wx_userInfo.openid,
              CARDTYPE: '01',
              CNUM: t.queryData.CNUM
            },
            success: function(data) {
              if (data[0].CODE == '0') {
                t.linkToPage('people_lists')
              } else {
                t.$toast('解绑失败');
              }
            }
          })
        }).catch(() => {
          // on cancel
        })

      }
    },
    mounted() {
      var t = this
      t.timer = setInterval(t.getCODE, 1000 * 60)
      this.$once('hook:beforeDestroy', () => clearInterval(t.timer))
    }
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .people_detail .qrcode-block {
    width: 100%;
    height: 200px;
    background: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
  }

  .people_detail .wrap-top {
    background: #fff;
    text-align: center;
  }

  .people_detail .wrap-top .tips {
    border-bottom: 1px dotted #cbced5;
  }

  .people_detail .qrcode-block .qrcode-logo {
    height: 45px;
    width: auto;
    position: absolute;
    z-index: 10;
  }

  .people_detail .qrcode-block .qrcode {
    width: 170px;
    height: 170px;
    position: relative;
    z-index: 6;
  }

  .people_detail .qrcode-block .qrcode img {
    width: 100%;
    height: 100%;
    pointer-events: none;
  }

  .people_detail .qrcode-block .qrcode canvas {
    width: 100% !important;
    height: 100% !important;
  }

  .people_detail>>>.van-cell {
    padding: 10px 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 14px;
  }

  .people_detail>>>.van-cell__title {
    color: #606266;
  }

  .people_detail>>>.van-cell__value {
    color: #303133;
  }

  .people_detail>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .people_detail {
    padding: 20px;
  }

  .people_detail .wrap-box {
    border-radius: 5px;
    overflow: hidden;
  }

  .people_detail .wrap-box .btn-item {
    text-align: center;
    font-size: 14px;
    color: #fe0f0f;
    padding: 20px;
  }

  .people_detail .button {
    padding: 30px 0;
    font-family: PingFangSC-Regular;
  }

  .people_detail .button .addbtn {
    display: block;
    background: #00c792;
    border-radius: .1rem;
    width: 100%;
    height: 40px;
    line-height: 40px;
    font-size: 14px;
    color: #FFFFFF;
    text-align: center;
    border-radius: 5px;
  }
</style>