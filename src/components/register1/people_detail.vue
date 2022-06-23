<template>
  <div class="people_detail g-wrap g-hp100">
    <div class="wrap-box f-bs3">
      <div class="wrap-top f-pt20 f-pl10 f-pr10" v-if="patientInfo.QRCODE||qr_image">
        <div class="qrcode-block">
          <img class="qrcode-logo" src="../../assets/img/register/logo_.png" alt="" />
          <!-- <div class="qrcode"></div> -->
          <!-- <div class="qrcode" ref="qrCodeUrl"></div> -->
          <img :src="'data:image/png;base64,'+qr_image" class="qrcode" v-if="qr_image">
          <qrcode-vue v-else size="150" level="H" :value="patientInfo.QRCODE"></qrcode-vue>
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
        <div v-if="patientInfo.SFBR!='1'" class="btn-item" @click="doDelete">{{patientInfo.QRCODE?'解绑电子健康卡':'删除就诊人'}}</div>
      </van-cell-group>
    </div>
  </div>
</template>
<script>
  // import QRCode from 'qrcodejs2'
  import QrcodeVue from 'qrcode.vue'

  export default {
    name: 'people_detail',
    components: {
      QrcodeVue
    },
    computed: {},
    created() {
      var t = this
      t.updateTitle(t.queryData.QRCODE ? '健康卡信息' : '就诊人信息')
      t.searchForm.OPENID = t.wx_userInfo.openid
      t.searchForm.XM = t.queryData.XM
      t.searchForm.CNUM = t.queryData.CNUM
      t.searchForm.SJHM = t.queryData.SJHM
      t.searchForm.CARDTYPE = t.queryData.CARDTYPE
      t.is_def = t.queryData.BZ == 1 ? true : false
      t.patientInfo = t.queryData
      t.getQRCODE()
      t.timer = setInterval(function() {
        t.getQRCODE()
      }, 30000)
    },
    data() {
      return {
        timer: '',
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
        qr_image: ''
      }
    },
    methods: {
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
        var tit = t.patientInfo.QRCODE ? '解绑电子健康卡' : '删除就诊人'
        t.$dialog.confirm({
          message: '确认' + tit
        }).then(() => {
          t.ajax({
            serverId: 'WXCZ00044',
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
                t.$toast('操作失败');
              }
            }
          })
        }).catch(() => {
          // on cancel
        })
      },
      // 获取动态码
      getQRCODE() {
        var t = this
        var params = {
          HEALTHCARDID: t.queryData.ECID,
          IDTYPE: '01',
          IDNUMBER: t.queryData.CNUM,
          CODETYPE: '0'
        }
        t.ajax({
          serverId: 'WXCZ00032',
          url: t.serverPath1,
          params: params,
          success: function(data) {
            console.log(data)
            console.log(t.queryData)
            if (data[0].CODE == 0) {
              var _data = data[0].data
              t.qr_image = _data.qr_image
              console.log(t.qr_image)
            } else {
              // t.$toast('获取动态码失败，请稍后再试')
            }
          },
          error: function(data) {
            t.$toast(data.MSG)
          }
        })
      }
    },
    beforeDestroy() {
      var t = this
      clearInterval(t.timer)
    },
    mounted() {}
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
    height: 30px;
    width: auto;
    position: absolute;
    z-index: 10;
  }

  .people_detail .qrcode-block .qrcode {
    width: 170PX;
    height: 170PX;
    position: relative;
    z-index: 6;
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
</style>