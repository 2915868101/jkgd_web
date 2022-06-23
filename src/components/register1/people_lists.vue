<template>
  <div class="people_lists g-wrap s-bgcfff g-hp100">
    <div class="card-list s-bgcfff" v-if="patientList.length">
      <template v-for="(c,i) in patientList">
        <div class="card-face-container" v-if="c.ECID" :key="i" @click="toDetail(c)">
          <img class="card-bg" src="../../assets/img/register/cardnewbg.png">
          <div class="card-top-info">
            <div class="card-top-org">安徽省卫生健康委员会</div>
            <div class="card-top-title">
              <img src="../../assets/img/register/icon2.png" alt="">
              <span>电子健康卡</span>
            </div>
          </div>
          <div class="card-detail-info">
            <div class="card-user-info">
              <div class="card-user-name">{{c.XM}} <span v-if="c.BZ=='1'" class="b-tag s-bgc00c792 f-ml10">默认</span></div>
              <span class="card-user-id">{{regNumber(c.CNUM,4)}}</span>
            </div>
            <div class="card-qrcode">
              <img class="card-qrcode-logo" src="../../assets/img/register/logo_.png" alt="">
              <qrcode-vue size="81" v-if="c.QRCODE" level="H" :value="c.QRCODE"></qrcode-vue>
              <!-- <div v-if="c.QRCODE" class="qrcode" :ref="`qrCodeUrl${i}`"></div> -->
              <img v-else class="qrcode" src="../../assets/img/register/qrcode.png" alt="">
            </div>
          </div>
          <div class="card-footer">中华人民共和国国家卫生健康委员会监制</div>
        </div>
        <div class="card-face-container card2" v-else :key="i" @click="toDetail(c)">
          <img class="card-bg" src="../../assets/img/register/cardnewbg2.jpg">
          <div class="card-detail-info">
            <div class="card-user-info">
              <div class="card-user-name">{{c.XM}} <span v-if="c.BZ=='1'" class="b-tag s-bgc00c792 f-ml10">默认</span></div>
              <span class="card-user-id">{{regNumber(c.CNUM,4)}}</span>
            </div>
            <div class="card-upgrade" @click.stop="doUpgrade(c)">升级电子健康卡</div>
          </div>
        </div>
      </template>
    </div>
    <div class="emptycard" v-else>
      <img class="g-w50 g-h50 f-mb10" src="../../assets/img/register/nocard.png" />
      <p>暂无健康卡</p>
    </div>
    <div class="g-wp100 f-pb30 f-pt30 s-bgcfff">
      <van-button plain color="#00c792" @click="linkToPage('register_card',{isRegister:true})">
        <van-icon class="f-mr10" name="plus" />添加健康卡</van-button>
    </div>
  </div>
</template>
<script>
// import QRCode from 'qrcodejs2'
import QrcodeVue from 'qrcode.vue'

export default {
  name: 'people_lists',
  components: {
    QrcodeVue,
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('健康卡管理')
    console.log(t.wx_userInfo)
    t.searchForm.OPENID = t.wx_userInfo.openid
    t.getPatientList()
  },
  data() {
    return {
      patientList: [],
      searchForm: { OPENID: '', FLAG: 'Query' },
      QRCodeList: []
    }
  },
  methods: {
    getPatientList() {
      var t = this
      t.ajax({
        serverId: 'WXCZ00044',
        url: t.serverPath1,
        params: t.searchForm,
        success: function(data) {
          t.patientList = data[0].CONTENTS
          console.log(t.patientList)
        }
      })
    },
    // 电子健康卡详情页
    toDetail(data) {
      var t = this
      t.linkToPage('people_detail', data)
    },
    doUpgrade(data) {
      var t = this
      var params = {
        OPENID: t.wx_userInfo.openid,
        CNUM: data.CNUM,
        SJHM: data.SJHM,
        XM: data.XM,
        XB: t.IdCard(data.CNUM, 2) == '男' ? '1' : '2',
        NL: t.IdCard(data.CNUM, 3),
        MZ: data.MZ || '',
        CSRQ: t.IdCard(data.CNUM, 1),
        HKDZ: data.HKDZ || '',
        TX: data.TX || '',
        CARDTYPE: '01',
        CHANNELNUM: '0',
      }
      t.ajax({
        serverId: 'WXCZ00031',
        url: t.serverPath1,
        params: params,
        success: function(data) {
          console.log(data[0])
          if (data[0].CODE == '0') {
            t.$toast('升级成功')
            t.getPatientList()
          } else {
            t.$toast('抱歉，升级异常，请稍后重试')
          }
        }
      })
    },
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.people_lists>>>.van-button {
  font-size: 16px;
  border-radius: 5px;
  width: 90%;
  margin: 0 5%;
}

.people_lists>>>.van-icon {
  vertical-align: -2px;
}

.people_lists .card-list {
  font-family: PingFangSC-Medium, 'PINGFANG MEDIUM';
  font-size: 12px;
  padding: 0 15px 20px;
  overflow: hidden;
}

.people_lists .card-face-container {
  width: 340px;
  height: 192px;
  margin: 20px auto 0;
  position: relative;
  color: #000;
}

.people_lists .card-face-container .card-top-info {
  position: absolute;
  top: 10px;
  left: 17px;
  right: 13px;
  display: flex;
  justify-content: space-between;
  align-items: center
}

.people_lists .card-face-container .card-top-info .card-top-title {
  display: flex;
  align-items: center;
  font-size: 15px;
  line-height: 21px;
  color: #2b2b2b;
}

.people_lists .card-face-container .card-top-info .card-top-title img {
  width: 27px;
  height: 27px;
  margin-right: 4px;
}

.people_lists .card-face-container .card-detail-info {
  position: absolute;
  bottom: 35px;
  width: calc(100% - 17px - 7px);
  left: 17px;
  right: 7px;
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
}

.people_lists .card-face-container .card-detail-info .card-user-info {
  font-size: 18px;
  color: #2b2b2b;
}

.people_lists .card-face-container .card-detail-info .card-user-info .card-user-name {
  height: 25px;
  display: flex;
  align-items: center;
}

.people_lists .card-face-container .card-detail-info .card-user-info .card-user-id {
  font-size: 15px;
  line-height: 15px;
  padding-top: 6px;
}

.people_lists .card-face-container .card-detail-info .card-qrcode {
  background: #fff;
  width: 85PX;
  height: 85PX;
  padding: 2PX;
  position: relative;
}

.people_lists .card-face-container .card-detail-info .card-qrcode .card-qrcode-logo {
  width: 22px;
  height: 22px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 6;
}

.people_lists .card-face-container .card-detail-info .card-qrcode .qrcode {
  width: 80PX;
  height: 80PX;
}

.people_lists .card-face-container .card-footer {
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
  line-height: 14px;
}

.people_lists .card-face-container.card2 .card-detail-info {}

.people_lists .card-face-container.card2 .card-detail-info .card-user-info {
  color: #fff;
}

.people_lists .card-face-container.card2 .card-upgrade {
  width: 100px;
  height: 30px;
  line-height: 29px;
  border: 1px solid #fff;
  border-radius: 15px;
  text-align: center;
  color: #fff;
}

.people_lists .b-tag {
  color: #fff;
  font-size: 12px;
}

.people_lists .button {
  padding: 30px 15px;
  font-family: PingFangSC-Regular;
}

.people_lists .button .addbtn {
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

.people_lists .quick-bind {
  font-size: 14px;
  color: #606266;
  text-align: center;
  margin-top: 10px;
  padding-bottom: 15px;
}

.people_lists .emptycard {
  width: 100%;
  height: 200px;
  text-align: center;
  font-size: 14px;
  padding-top: 60px;
}

.people_lists .card-face-container img.card-bg {
  width: 100%;
}
</style>