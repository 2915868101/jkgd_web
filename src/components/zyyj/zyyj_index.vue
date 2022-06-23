<template>
  <div class="zyyj_index g-wrap g-hp100 s-bgcfff">
    <van-cell title="就诊医院" is-link @click="showPicker2 = true">
      <span v-if="!searchForm.ORG_NAME">请选择</span>
      <span v-else>{{searchForm.ORG_NAME}}</span>
    </van-cell>
    <van-popup v-model="showPicker2" position="bottom">
      <div class="s-c909399 f-pt15 f-pl15 f-fs14 f-bb1">请先选择就诊医院</div>
      <van-picker toolbar-position="bottom" show-toolbar :columns="columns1" @cancel="showPicker2 = false" @confirm="onConfirm" />
    </van-popup>
    <van-cell title="就诊人" is-link @click="isShowPopup = true">
      <span v-if="!searchForm.HZXM">请选择</span>
      <span v-else>{{searchForm.HZXM}}</span>
    </van-cell>
    <van-popup v-model="isShowPopup" position="bottom" :style="{height : '30%'}">
      <div class="pop-head">
        <span>请选择就诊人</span>
        <van-icon name="cross" @click.stop="isShowPopup = false"></van-icon>
      </div>
      <div class="pop-cont">
        <div class="select-list">
          <div class="select-item" v-for="c in patientList" :key="c.CNUM" @click="doSelect(c)">
            <div class="item-icon" v-if="!c.TX">
              <i class="iconfont icon-user"></i>
            </div>
            <img class="item-icon" v-else :src="c.TX" />
            <div class="item-name">{{c.XM}}</div>
          </div>
        </div>
      </div>
    </van-popup>
    <div class="g-wp100 s-bgcfff f-pt50 f-pb15">
      <van-button size="small" round color="#00c792" @click="linkToPage('zyyj_detail')">确认查找</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'zyyj_index',
  components: {
  },
  created() {
    var t = this
    t.updateTitle('住院预缴')
    t.searchForm1.OPENID = t.wx_userInfo.openid

    // t.getHospitalList()
    // t.getPatientList()
    // 判断是否注册
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function(sucFun) {
      t.userInfo = sucFun.CONTENTS[0]
    })
  },
  computed: {},
  data() {
    var t = this
    return {
      searchForm: {
        ORG_NAME: '',
        CARDNUM: '',
        KSRQ: '',
        JSRQ: t.$moment(new Date()).format('YYYY-MM-DD'),
        TYPE: '',
      },

      isShowPopup: false,
      patientList: [],
      searchForm1: {
        FLAG: 'Query',
        OPENID: '',
        XM: '',
        CARDTYPE: '',
        CNUM: '',
        SJHM: ''
      },

      showPicker2: false,
      searchForm2: {
        ORG_AREA: '',
        LONGITUDE: '112.889211',
        LATITUDE: '28.21384',
        GRADE: '',
        Q_TYPE: 'ALL',
      },
      hospitalLiat: [],
      columns1: [],
    }
  },
  methods: {
    // 获取医院列表
    getHospitalList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00001',
        url: t.serverPath1,
        params: t.searchForm2,
        success: function(data) {
          t.hospitalLiat = data[0].CONTENTS
          console.log(t.hospitalLiat)
          t.columns1 = Object.values(t.hospitalLiat).map(function(row) {
            return row.ORG_NAME
          })
        }
      })
    },
    onConfirm(val) {
      var t = this;
      t.searchForm.ORG_NAME = val
      t.showPicker2 = false
      console.log(t.searchForm)
    },
    // 获取就诊人列表
    getPatientList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00014',
        url: t.serverPath1,
        params: t.searchForm1,
        success: function(data) {
          t.patientList = data[0].CONTENTS || []
          var row = t.patientList.find(function(row) {
            return row.BZ == '1'
          })
          t.searchForm.HZXM = row.XM
          t.searchForm.CARDNUM = row.CNUM
        }
      })
    },
    // 就诊人选择 
    doSelect(data) {
      var t = this
      t.isShowPopup = false
      t.searchForm.HZXM = data.XM
      t.searchForm.CARDNUM = data.CNUM
      console.log(t.searchForm)
    },
  },
  mounted() {}
}
</script>
<style scoped>
.zyyj_index>>>.van-cell {
  padding: 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.zyyj_index>>>.van-cell__title {
  color: #606266;
  flex: 8;
}

.zyyj_index>>>.van-cell__value {
  flex: 16;
  color: #303133;
}

.zyyj_index>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}  
 
.zyyj_index>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  font-size:15PX;
  padding: 0 10px;
} 
/*popup 样式*/
.zyyj_index .pop-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 15px;
  color: #c0c4cc;
  padding: 15px;
}

.zyyj_index .pop-cont {
  font-size: 15px;
  padding: 15px;
}

.zyyj_index .select-list {
  display: flex;
  flex-wrap: wrap;
  text-align: center;
}

.zyyj_index .select-list .select-item {
  width: 25%;
  margin-bottom: 15px;
}

.zyyj_index .select-list .select-item .item-icon {
  display: inline-block;
  background: #d2f6ec;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
  margin-bottom: 8px;
}

.zyyj_index .select-list .select-item .iconfont {
  color: #00c792;
  font-size: 28px;
}

</style>