<template>
  <div class="mzjf_index g-wrap g-hp100 s-bgcfff">
    <van-cell class="f-bb10" title="就诊医院" is-link @click="showPicker2 = true">
      <span v-if="!searchForm.ORG_NAME">请选择</span>
      <span v-else>{{searchForm.ORG_NAME}}</span>
    </van-cell>
    <van-popup v-model="showPicker2" position="bottom">
      <div class="s-c909399 f-pt15 f-pl15 f-fs14 f-bb1">请先选择就诊医院</div>
      <van-picker toolbar-position="bottom" show-toolbar :columns="columns1" @cancel="showPicker2 = false" @confirm="onConfirm" />
    </van-popup>
    <van-cell class="f-bb10" title="就诊人" is-link @click="isShowPopup = true">
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
    <div class="record-list s-bgcfff">
      <div class="list-item">
        <div class="item-head f-pt15 f-pb15 f-pr15 f-pl15">
          <p class="name s-c303133 f-fs15">门诊医院费(骨科)</p> 
          <p class="f-mt10">医院医院</p> 
        </div>
        <div class="item-cont">
          <div class="line-item f-lh24"><span class="tit">就诊人：</span>xxx</div>
          <div class="line-item f-lh24"><span class="tit">就诊卡号：</span>20203433</div>
          <div class="line-item f-lh24"><span class="tit">就诊时间：</span>2020-02-13 12:1:12</div>
        </div>
        <div class="item-bott f-df f-dfj">
          <div class="line-item f-lh24"><span class="tit">待缴金额：</span><span class="s-cf30b32">￥ 188.00</span></div>
          <van-button size="small" round color="#00c792" @click="linkToPage('mzjf_detail')">立即缴费</van-button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'mzjf_index',
  components: {
  },
  created() {
    var t = this
    t.updateTitle('门诊缴费')
    t.searchForm1.OPENID = t.wx_userInfo.openid

    t.getHospitalList()
    t.getPatientList()
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
.mzjf_index>>>.van-cell {
  padding: 15px;
  font-size: 15px;
  /*border-bottom: 1PX solid #ebeef5;*/
}

.mzjf_index>>>.van-cell__title {
  color: #606266;
  flex: 8;
}

.mzjf_index>>>.van-cell__value {
  flex: 16;
  color: #303133;
}

.mzjf_index>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}  

 
.mzjf_index .f-bb1 {
  border-bottom: 1px solid #ebeef5;
  padding-bottom: 15px;
  margin-bottom: 15px;
}
 
.mzjf_index .record-list {
  padding: 0 15px;
  font-size: 14px;
}

.mzjf_index .record-list .list-item {
  background: #fff;
  border: 1PX solid #ebeef5;
  border-radius: 5px;
  margin: 15px 0;
  color: #606266;
}

.mzjf_index .record-list .list-item .item-bott  {
  height: 52px; 
  padding: 0 15px;
  align-items: center;
}
.mzjf_index .record-list .list-item .item-bott  {

}
.mzjf_index .record-list .list-item .item-head  {
  background: #fbfcfc;
}
.mzjf_index .record-list .list-item .item-head .name {
  font-weight: bold;
}
.mzjf_index .record-list .flex-item{
  display: flex;
  align-items: center;
  justify-content: space-between;
} 
.mzjf_index .record-list .dott-icon{
  display: inline-block;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  vertical-align: 2px;
}
.mzjf_index .record-list .list-item .item-cont {
  border-bottom: 1px solid #ebeef5;
  border-top: 1px solid #ebeef5;
  padding:10px 15px;
} 

.mzjf_index .record-list .list-item .line-item span.tit {
  display: inline-block;
  width: 80px;
  text-align-last: justify;
}
/*popup 样式*/
.mzjf_index .pop-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 15px;
  color: #c0c4cc;
  padding: 15px;
}

.mzjf_index .pop-cont {
  font-size: 15px;
  padding: 15px;
}

.mzjf_index .select-list {
  display: flex;
  flex-wrap: wrap;
  text-align: center;
}

.mzjf_index .select-list .select-item {
  width: 25%;
  margin-bottom: 15px;
}

.mzjf_index .select-list .select-item .item-icon {
  display: inline-block;
  background: #d2f6ec;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
  margin-bottom: 8px;
}

.mzjf_index .select-list .select-item .iconfont {
  color: #00c792;
  font-size: 28px;
}

</style>