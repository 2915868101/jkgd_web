<template>
  <div class="hsyy_qr g-wrap">
    <div class="jcxm-head">
      <div>
        <p class="f-pb10">检测项目:<span class="f-fwb f-fs16 f-ml5">{{reserveForm.TITLE}}</span></p>
        <p class="f-pb10">项目价格:<span class="s-cf30b32 f-ml5">&yen; {{reserveForm.MONEY.toFixed(2)}}</span></p>
      </div>
      <div class="s-c00c792" @click="linkToPage('/hsyy_yyjl')"><i class="iconfont icon-jzicon1 f-mr4"></i>预约记录</div>
    </div>
    <van-cell-group>
      <div class="g-wp100 g-h10 s-bgcf4f9f9"></div>
      <van-cell title="检测医院" is-link @click="showPicker1=true">
        <span class="s-c303133" v-if="reserveForm.ORG_NAME">{{reserveForm.ORG_NAME}}</span>
        <span v-else class="s-c999">请选择预约检测医院</span>
      </van-cell>
      <van-cell title="预约时间" is-link @click="showPicker">
        <span class="s-c303133" v-if="yysj_value">{{yysj_value.date +' '+ yysj_value.week}}</span>
        <span v-else class="s-c999">请选择预约时间</span>
      </van-cell>
      <van-cell title="采样时间段" is-link @click="showPickerYysd">
        <span class="s-c303133" v-if="yysd_value">{{yysd_value.value}}</span>
        <span v-else class="s-c999">请选择采样时间段</span>
      </van-cell>
      <van-cell title="预约用户" is-link @click="showPopup">
        <span class="s-c303133" v-if="reserveForm.HZXM">{{reserveForm.HZXM}}</span>
        <span v-else class="s-c999">请选择就诊人</span>
      </van-cell>
      <van-cell title="身份证号">
        <span class="s-c303133">{{reserveForm.CARDNUM}}</span>
      </van-cell>
      <van-cell title="手机号码">
        <span class="s-c303133">{{reserveForm.SJHM}}</span>
      </van-cell>
    </van-cell-group>
    <div class="tips-wrap f-mt10 s-bgcfff">
      <div class="head s-c303133 f-pb10">预约须知</div>
      <div class="cont s-cffa54d">本院就诊实行实名制预约，请用本人真实身份证预约就诊，否则预约无效，后果自负。</div>
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
    <van-popup v-model="showPicker1" position="bottom">
      <div class="s-c909399 f-pt15 f-pb15 f-pl15 f-fs14 f-bb1">请先选择检测医院</div>
      <van-picker toolbar-position="bottom" show-toolbar :columns="columns1" @cancel="showPicker1 = false" @confirm="onConfirmORG" />
    </van-popup>
    <!-- 预约时段时间选择 -->
    <mt-popup-picker ref="picker" :datas="popupLists" :value-key="value_key" :confirm="onConfirm"></mt-popup-picker>
    <mt-popup-picker ref="pickeryysd" text-title="请选择采样时间段" :datas="popupyysdLists" :value-key="value_key" :confirm="onConfirmyysd"></mt-popup-picker>
    <div class="g-wp100">
      <van-button v-if="reserveForm.HZXM" class="item-btn f-bs3" round color="#00c792" @click="doValidate">提交预约</van-button>
      <van-button v-else class="item-btn f-bs3" round color="#c0c4cc" disabled>提交预约</van-button>
    </div>
  </div>
</template>
<script>
import mtPopupPicker from '@/components/util/mt_popup_picker'
import querystring from 'querystring'

export default {
  name: 'hsyy_qr',
  components: {
    mtPopupPicker
  },
  computed: {
    yysj_value() {
      return this.$store.state.store_hsyy.yysj_value
    },
    yysd_value() {
      return this.$store.state.store_hsyy.yysd_value
    }
  },
  created() {
    var t = this
    t.updateTitle('核酸检测预约')
    // 判断是否注册
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function(sucFun) {
      t.userInfo = sucFun.CONTENTS[0]
      t.searchForm.OPENID = t.wx_userInfo.openid 
      t.getPatientList() 
      console.log(t.$moment().format('MMDD'))
      var day = parseInt(t.$moment().format('MMDD')) 
      if(day&&(day >= 210) && (day <= 218)) {
        t.$dialog.alert({
          confirmButtonText: '好的',
          message: '2021年2月10日到2021年2月18日春节放假期间暂停网上预约核酸检测，祝您新年快乐！身体健康！'
        }).then(function(){
          t.goBack()
        })
      }
      // t.getHospitalList()
      setTimeout(function() {
        t.getPidGD()
      }, 300)

    })
  },
  data() {
    return {
      userInfo: '',
      columns1: ["广德市人民医院", "广德市中医院"],
      showPicker1: false,
      hospitalList: [{ ORG_ID: "guangde001" ,ORG_NAME: "广德市人民医院" },{ ORG_ID: "guangdezyy" ,ORG_NAME: "广德市中医院" }],

      reserveForm: {
        ID: '',
        HZXM: '',
        CARDNUM: '',
        SJHM: '',
        YYRQ: '',
        YYSD: '',
        ORG_ID: '',
        ORG_NAME: '',
        MONEY: 66.18,
        TITLE: '新型冠状病毒核酸检测'
      },
      isShowPopup: false,
      patientList: [],
      searchForm: {
        FLAG: 'Query',
        OPENID: '',
        XM: '',
        CARDTYPE: '',
        CNUM: '',
        SJHM: ''
      },

      popupLists: '',
      popupyysdLists:'',
      popupyysdLists1: [{ name: '1', value: '08:30-11:00' },{ name: '2', value: '13:30-16:30' }],
      value_key: 'value',
    }
  },
  methods: {
    getPidGD() {
      var t = this
      t.ajax({
        serverId: 'WXBM00005',
        url: t.serverPath1,
        params: {
          OPENID: t.wx_userInfo.openid,
          CARDNUM: t.reserveForm.CARDNUM,
          SJHM: t.reserveForm.SJHM,
          ORG_ID: 'guangde001'
        },
        success: function(data) { 
          console.log(data)
          t.reserveForm.ID = data[0].CARDNO
        }
      })
    },
    // 获取医院列表
    getHospitalList() {
      var t = this
      if (localStorage.hospitalList) {
        t.hospitalList = JSON.parse(localStorage.hospitalList)
      } else {
        t.ajax({
          loading: false,
          serverId: 'BMFW00024',
          url: t.serverPath1,
          success: function(data) {
            t.hospitalList = data[0].CONTENTS || []
            console.log(t.hospitalList)
            localStorage.hospitalList = JSON.stringify(t.hospitalList)
          }
        })
      }
      console.log(t.hospitalList)
      t.columns1 = Object.values(t.hospitalList).map(function(row) {
        return row.ORG_NAME
      })
      console.log(t.columns1)
    },
    onConfirmORG(val, index) {
      var t = this;
      if (val == '广德市人民医院') {
        t.reserveForm.ORG_NAME = val
        t.reserveForm.ORG_ID = t.hospitalList[index].ORG_ID
        t.reserveForm.ORG_YQ = t.hospitalList[index].ORG_YQ || ''
        t.showPicker1 = false
      } else {
        t.$dialog.alert({
            confirmButtonText: '好的',
            message: val + '还未进行数据对接，暂时无法预约。',
          })
          .then(() => {

          })
      }
    },
    onConfirm(res) {
      var t = this
      t.$store.state.store_hsyy.yysj_value = res
      t.$store.state.store_hsyy.yysd_value = ''
    },
    onConfirmyysd(res) {
      var t = this
      t.$store.state.store_hsyy.yysd_value = res
    },
    showPicker() {
      var t = this
      var arr = t.getDayListsTjyy({ num: 60, onlyWorkingDays: false, startdate: t.queryData.YYJJ_START, enddate: t.queryData.YYJJ_END })
      t.popupLists = []
      var nowTime = t.formatTime()
      var nowTime_ori = parseInt(nowTime.substring(11, 16).replace(/:/g, ''))
      console.log(nowTime_ori)
      for (var i = 0; i < 22; i++) {
        //上午16:00以前可以预约当天
        if (t.$moment().format('yyyy-MM-DD') == arr[i].date) {
          if (nowTime_ori <= 1700) {
            if (arr[i]) {
              t.popupLists.push({
                value: arr[i].date.replace(/-/g, '/').substr(5) + ' 今天',
                date: arr[i].date,
                week: arr[i].week
              })
            }
          }
        } else {
          if (arr[i]) {
            t.popupLists.push({
              value: arr[i].date.replace(/-/g, '/').substr(5) + ' ' + (t.$moment().format('yyyy-MM-DD') == arr[i].date ? '今天' : arr[i].week),
              date: arr[i].date,
              week: arr[i].week
            })
          }
        }
      }
      t.$refs.picker.showPopup = true
    },
    showPickerYysd() {
      var t = this
      t.popupyysdLists=[]
      if(t.yysj_value){
        if(t.$moment().format('yyyy-MM-DD')==t.yysj_value.date){
          for(var c of t.popupyysdLists1){ 
            if(parseInt(String(c.value.split('-')[1]).replace(/:/g,''))>parseInt(t.$moment().format('HHmm'))){
              console.log(c)
              t.popupyysdLists.push(c);
            }
          }
        }else{
          t.popupyysdLists=t.popupyysdLists1
        }
        t.$refs.pickeryysd.showPopup = true
      }else{
        t.$toast('请先选择预约预约时间')
      }
    },
    doAddCard() {
      var t = this
      if (t.serverHealthCard) {
        sessionStorage.setItem("prevUrl", t.$route.fullPath)
        var redirect_uri = t.wx_health_card + "/register_card?isRegister=true&openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl + '&prevUrl=' + t.$route.fullPath
        window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(redirect_uri)
      } else {
        t.linkToPage('register_card', { isRegister: true })
      }
    },
    getPatientList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00014',
        url: t.serverPath1,
        params: t.searchForm,
        success: function(data) {
          console.log(data)
          t.patientList = data[0].CONTENTS || []
          console.log(t.patientList)
          var row = t.patientList.find(function(row) {
            return row.BZ == '1'
          })

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
    doValidate() {
      var t = this
      var day = parseInt(t.$moment().format('MMDD'))
      if(day&&(day >= 210) && (day <= 218)) {
        t.$dialog.alert({
          confirmButtonText: '好的',
          message: '2021年2月10日到2021年2月18日春节放假期间暂停网上预约核酸检测，祝您新年快乐！身体健康！'
        })
        return false
      } else if (!t.reserveForm.ORG_ID) {
        t.$toast('请选择预约检测医院')
        return false
      } else if (!t.yysj_value) {
        t.$toast('请选择预约时间')
        return false
      } else if (!t.yysd_value) {
        t.$toast('请选择采样时间段')
        return false
      } else if (!t.reserveForm.HZXM) {
        t.$toast('请选择就诊人')
        return false
      } else if (!t.reserveForm.SJHM) {
        t.$toast('手机号码不能为空')
        return false
      } else if (!t.$store.state.rules.mobile.test(t.reserveForm.SJHM)) {
        t.$toast('请填写真实有效的手机号')
        return false
      } else {
        t.toPay()
      }
    },
    toPay() {
      var t = this
      var params = {
        money: t.reserveForm.MONEY.toFixed(2),
        title: t.reserveForm.TITLE,
        orgid: t.reserveForm.ORG_ID,
        orgname: t.reserveForm.ORG_NAME,
        sfzh: t.reserveForm.CARDNUM,
        phone: t.reserveForm.SJHM,
        hzxm: t.reserveForm.HZXM,
        yysj: t.yysj_value.date,
        week: t.yysj_value.week,
        yysd: t.yysd_value.value, 
        openid: t.wx_userInfo.openid,
        pid: t.reserveForm.ID,
        xb:t.IdCard(t.reserveForm.CARDNUM, 2) == '男' ? '1' : '2'
      }
      console.log(params)
      window.location.href = 'http://www.gdxyy.org.cn/_weixin/dist/weixin/wxpay/example/jsapi_hesuan_gdspt.php?' + querystring.stringify(params)
      // window.location.href = 'http://www.hnwisdom.com/HealthRecords/Wxpay/example/jsapi_hesuan_gdspt.php?' + querystring.stringify(params)
      // console.log('http://www.gdxyy.org.cn/_weixin/dist/weixin/wxpay/example/jsapi_hesuan_gdspt.php?' + querystring.stringify(params))
      // t.linkToPage('/hsyy_yycg', params)
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.hsyy_qr>>>.van-cell {
  padding: 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.hsyy_qr>>>.van-cell__title {
  color: #606266;
}

.hsyy_qr>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.hsyy_qr .tips-wrap {
  font-size: 15px;
  padding: 15px;
}

.hsyy_qr .tips-wrap .head {
  font-weight: bold;
}

.hsyy_qr .tips-wrap .cont {
  line-height: 24px;
}

.hsyy_qr>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  padding: 0 10px;
}

/*popup 样式*/
.hsyy_qr .pop-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 15px;
  color: #c0c4cc;
  padding: 15px;
}

.hsyy_qr .pop-cont {
  font-size: 15px;
  padding: 15px;
}

.hsyy_qr .select-list {
  display: flex;
  flex-wrap: wrap;
  text-align: center;
}

.hsyy_qr .select-list .select-item {
  width: 25%;
  margin-bottom: 15px;
}

.hsyy_qr .select-list .select-item .item-icon {
  display: inline-block;
  background: #d2f6ec;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
  margin-bottom: 8px;
}

.hsyy_qr .select-list .select-item .iconfont {
  color: #00c792;
  font-size: 28px;
}

.hsyy_qr>>>.van-picker__cancel {
  width: 0;
  padding: 0;
}

.hsyy_qr>>>.van-picker__confirm {
  width: 100%;
  background: #00c792;
  color: #fff;
  padding: 0;
}

.hsyy_qr .jcxm-head {
  background: #fff;
  padding: 20px 15px 10px;
  font-size: 15px;
  display: flex;
  justify-content: space-between;
}
</style>