<template>
  <div class="bgcx_index g-wrap g-hp100 s-bgcfff">
    <div class="cell-item f-bb10 f-fs15 f-df">
      <div class="tit s-c606266">周期</div>
      <div class="cont">
        <span :class="[activeCycle=='2'?'on':'','btn-item f-ml10']" plain round @click="changeCycle(2)">历史记录</span>
        <span :class="[activeCycle=='1'?'on':'','btn-item f-ml10']" plain round @click="changeCycle(1)">今天</span>
      </div>
    </div>
    <van-cell v-show="activeCycle=='1'" title="就诊医院" is-link @click="showPicker2 = true">
      <span v-if="!searchForm.ORG_NAME">请选择</span>
      <span v-else>{{searchForm.ORG_NAME}}</span>
    </van-cell>
    <van-popup v-model="showPicker2" position="bottom">
      <div class="s-c909399 f-pt15 f-pl15 f-fs14 f-bb1">请先选择就诊医院</div>
      <van-picker toolbar-position="bottom" show-toolbar :columns="columns1" @cancel="showPicker2 = false" @confirm="onConfirm" />
    </van-popup>
    <van-cell class="f-bb10" title="就诊人" is-link @click="isShowPopup = true">
      <!--   <van-cell class="f-bb10" title="就诊人"> -->
      <span v-if="!searchForm.NAME">请选择</span>
      <span v-else>{{searchForm.NAME}}</span>
    </van-cell>
    <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
      <van-tab title="检查报告">
        <div class="report-list s-bgcfff">
          <div class="list-item" @click="linkToPage('bgcx_jc',{id:c.ACTIVITYID,name:c.org_name,type:c.DIRECTORYCODE,token:token})" v-for="(c,i) in recordList1" :key="i">
            <div class="p1"><i class="iconfont icon-jyjc f-fs13 s-c00c792 f-mr8"></i>{{c.treatment_conclusion||'--'}}</div>
            <div class="p2" v-if="c.org_name">{{c.org_name}}</div>
            <div class="p2" v-if="c.treat_date">检查日期：
              <span v-if="c.treat_date.time"> {{$moment(c.treat_date.time).format('YYYY-MM-DD')}}</span>
              <span v-else> {{$moment(c.treat_date).format('YYYY-MM-DD')}}</span>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordList1.length" description="暂无检查报告" />
      </van-tab>
      <van-tab title="检验报告">
        <div class="report-list s-bgcfff">
          <div class="list-item" @click="linkToPage('bgcx_jy',{id:c.ACTIVITYID,type:c.DIRECTORYCODE,token:token,name:c.org_name})" v-for="(c,i) in recordList2" :key="i">
            <div class="p1"><i class="iconfont icon-jyjc f-fs13 s-c00c792 f-mr8"></i>{{c.treatment_conclusion||'--'}}</div>
            <div class="p2">{{c.org_name}}</div>
            <div class="p2" v-if="c.treat_date">检查日期：
              <span v-if="c.treat_date.time"> {{$moment(c.treat_date.time).format('YYYY-MM-DD')}}</span>
              <span v-else> {{$moment(c.treat_date).format('YYYY-MM-DD')}}</span>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordList2.length" description="暂无检验报告" />
      </van-tab>
    </van-tabs>
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
          <div class="select-item" @click="doAddCard">
            <div class="item-icon">
              <i class="iconfont icon-add"></i>
            </div>
            <div class="item-name">添加就诊人</div>
          </div>
        </div>
      </div>
    </van-popup>
  </div>
</template>
<script>
  export default {
    name: 'bgcx_index',
    components: {},
    created() {
      var t = this
      t.updateTitle('报告查询')
      t.searchForm1.OPENID = t.wx_userInfo.openid
      t.getHospitalList()
      t.getPatientList()
      // t.activeIndex = t.$store.state.store_bgcx.bgcx_tab_index
      // 判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(sucFun) {
        t.userInfo = sucFun.CONTENTS[0]
        // 人脸对比识别
        if (!t.$cookies.get('FACE_MATCH') && t.serverType == 'hz') {
          t.$dialog.confirm({
              title: '',
              message: '为了保护用户信息\n需要认证是否是本人',
            })
            .then(() => {
              sessionStorage.setItem("prevUrl", t.$route.fullPath)
              t.linkToPage('/face_verify', {
                type: 'match'
              })
            })
            .catch(() => {
              t.goBack()
            })
        }
      })
    },
    computed: {},
    data() {
      var t = this
      return {
        token: '',
        activeCycle: '2',
        activeIndex: '',
        searchForm: {
          ORG_NAME: '',
          CARDNUM: '',
          KSRQ: '',
          JSRQ: t.$moment(new Date()).format('YYYY-MM-DD'),
          TYPE: '1',
        },
        recordList1: '',
        recordList2: '',
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
      gettoken() {
        var t = this
        t.ajax({
          serverId: 'WXBMGDJKDA01',
          url: t.serverPath2,
          params: {
            CNUM: t.searchForm.CARDNUM,
            NAME: t.searchForm.NAME
          },
          success: function(data) {
            localStorage.token = JSON.stringify(data[0].result);
            t.token = JSON.parse(localStorage.token)

          }
        })
      },
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
      },
      // 周期切换
      changeCycle(type) {
        var t = this
        t.activeCycle = type
        if (type == '1') {
          t.searchForm.date = t.$moment(new Date()).format('YYYY-MM-DD')
          t.recordList1 = []
          t.recordList2 = []
        } else if (type == '2') {
          t.searchForm.JSRQ = t.$moment(new Date()).format('YYYY-MM-DD')
          t.gettoken()
          t.getRecordList(1)
        }
      },
      // 检验检查tabs切换
      onClickTabs(index) {
        var t = this
        if ("undefined" == typeof index) {
          index = 0
        } else {
          t.activeIndex = index
        }
        t.gettoken()
        t.getPatientList(index + 1)
        t.$store.state.store_bgcx.bgcx_tab_index = index
      },
      // 获取检验检查列表
      getRecordList(type) {
        var t = this
        if ("undefined" == typeof type) {
          type = 1
        }
        console.log(123, {
          TYPE: type,
          TOKEN: JSON.parse(localStorage.token),
          CARDNUM: t.searchForm.CARDNUM
        })
        t.ajax({
          serverId: 'BMFW00016',
          url: t.serverPath2,
          params: {
            TYPE: type,
            TOKEN: JSON.parse(localStorage.token),
            CARDNUM: t.searchForm.CARDNUM
          },
          success: function(data) {
            console.log(66, data)
            if (type == '1') {
              t.recordList1 = data
            } else {
              t.recordList2 = data
            }
          }
        })
      },
      // 获取就诊人列表
      getPatientList(val) {
        var t = this
        t.ajax({
          serverId: 'WXBM00014',
          url: t.serverPath1,
          params: t.searchForm1,
          success: function(data) {
            console.log(789, data)
            t.patientList = data[0].CONTENTS || []
            var row = t.patientList.find(function(row) {
              return row.BZ == '1'
            })
            if (t.$store.state.store_bgcx.searchForm) {
              t.searchForm = t.$store.state.store_bgcx.searchForm
            } else {
              t.searchForm.NAME = row.XM
              t.searchForm.CARDNUM = row.CNUM
              t.$store.state.store_bgcx.searchForm = t.searchForm
            }
            t.recordList2 = []
            t.recordList1 = []
            t.gettoken()
            t.getRecordList(val)
          },
        })
      },
      // 就诊人选择 
      doSelect(data) {
        var t = this
        t.isShowPopup = false
        t.searchForm.NAME = data.XM
        t.searchForm.CARDNUM = data.CNUM
        if (t.activeCycle == '1') {
          t.recordList2 = []
          t.recordList1 = []
        } else {
          t.recordList2 = []
          t.recordList1 = []
          t.onClickTabs(0)
        }
      },
      doAddCard() {
        var t = this
        if (t.serverHealthCard) {
          sessionStorage.setItem("prevUrl", t.$route.fullPath)
          var redirect_uri = t.wx_health_card + "/register_card?isRegister=true&openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl + '&prevUrl=' + t.$route.fullPath
          window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(redirect_uri)
        } else {
          t.linkToPage('register_card', {
            isRegister: true
          })
        }
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  .bgcx_index>>>.van-cell {
    padding: 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
  }

  .bgcx_index>>>.van-cell__title {
    color: #606266;
    flex: 8;
  }

  .bgcx_index>>>.van-cell__value {
    flex: 16;
    color: #303133;
  }

  .bgcx_index>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .bgcx_index .cell-item {
    padding: 15px;
    justify-content: space-between;
    align-items: center;
  }

  .bgcx_index .cell-item .btn-item {
    display: inline-block;
    height: 30px;
    line-height: 30px;
    text-align: center;
    border-radius: 15px;
    color: #606266;
    border: 1px solid #ebeef5;
    padding: 0 10px;
    font-size: 14PX;
  }

  .bgcx_index .cell-item .btn-item.on {
    border-color: #00c792;
    color: #00c792;
  }

  .bgcx_index .f-bb10 {
    border-bottom: 10px solid #f4f9f9;
  }

  .bgcx_index .report-list .list-item {
    padding: 15px;
    border-bottom: 1PX solid #eef0f6;
    font-size: 14PX;
    color: #606266;
    line-height: 22px;
  }

  .bgcx_index .report-list .list-item .p1 {
    color: #303133;
    font-size: 15px;
    margin-bottom: 8px;
    font-weight: bold;
  }

  .bgcx_index .report-list .list-item .p1 .iconfont {
    vertical-align: -1px;
    font-weight: normal;
  }

  /*popup 样式*/
  .bgcx_index .pop-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 15px;
    color: #c0c4cc;
    padding: 15px;
  }

  .bgcx_index .pop-cont {
    font-size: 15px;
    padding: 15px;
  }

  .bgcx_index .select-list {
    display: flex;
    flex-wrap: wrap;
    text-align: center;
  }

  .bgcx_index .select-list .select-item {
    width: 25%;
    margin-bottom: 15px;
  }

  .bgcx_index .select-list .select-item .item-icon {
    display: inline-block;
    background: #d2f6ec;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
    margin-bottom: 8px;
  }

  .bgcx_index .select-list .select-item .iconfont {
    color: #00c792;
    font-size: 28px;
  }

  .bgcx_index>>>.van-tabs--line .van-tabs__wrap {
    height: 60px;
    padding-bottom: 5px;
    border-bottom: 10px solid #f4f9f9;
  }

  .bgcx_index>>>.van-tab__text {
    font-size: 15px;
  }

  .bgcx_index>>>[class*=van-hairline]::after {
    border: 0;
  }

  .bgcx_index>>>.van-tabs__line {
    background: #00c792;
  }

  .bgcx_index .f-bb1 {
    border-bottom: 1px solid #ebeef5;
    padding-bottom: 15px;
    margin-bottom: 15px;
  }

  .bgcx_index>>>.van-picker__cancel {
    width: 0;
    padding: 0;
  }

  .bgcx_index>>>.van-picker__confirm {
    width: 100%;
    background: #00c792;
    color: #fff;
    padding: 0;
  }
</style>