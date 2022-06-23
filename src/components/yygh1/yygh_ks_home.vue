<template>
  <div class="yygh_ks_home g-wrap g-hp100 s-bgcfff">
    <div class="tabs-wrap s-bgcfff">
      <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
        <van-tab title="按日期">
          <div class="date-tabs" v-if="doctorList1">
            <div :class="[currentIndex === index ? 'on' : '','item f-df3']" v-for="(item,index) in dateList" :key="index" @click="doClickDate(index)">
              <p>{{item.txt}}</p>
              <p class="f-mt10">{{item.date}}</p>
            </div>
          </div>
          <div class="date-tab">
            <div class="doctor-list" v-if="doctorList1.length">
              <van-row class="list-item" type="flex" justify="space-between" :gutter="20" v-for="(c,i) in doctorList1" :key="i">
                <van-col :span="4">
                  <div v-show="!c.YSTX" class="item-icon">
                    <i class="iconfont icon-doctor"></i>
                  </div>
                  <img v-show="c.YSTX" class="item-icon" :src="c.YSTX" />
                </van-col>
                <van-col :span="20">
                  <div class="item-cont">
                    <div>
                      <span class="name f-mr10">{{c.YSXM}}</span>
                      <span class="post" v-if="c.YSJB">{{c.YSJB}}</span>
                    </div>
                    <div class="tips">
                      <span class="f-mr10">挂号费</span>
                      <span class="g-w60 s-cf30b32">￥{{c.GHF}}</span>
                    </div>
                  </div>
                  <div class="item-select f-df f-dfj" v-for="(c1,i1) in c.scheduleInfo" :key="i1">
                    <div class="val">
                      <span class="f-mr8">{{$moment(c1.CZRQ).format('MM-DD')}}({{getWeek(c1.CZRQ)}})</span>
                      <span class="f-mr8">{{c1.ZZLXMC}}</span>
                      <span>余号{{c1.regLeaveCount}}</span>
                    </div>
                    <div v-if="c1.regLeaveCount>0" class="btn s-bgc00c792" @click="toPb(c1,c)">预约</div>
                    <div v-else class="btn s-bgcc0c4cc">预约</div>
                  </div>
                </van-col>
              </van-row>
            </div>
            <van-empty v-else description="当天无可预约的医生" />
          </div>
        </van-tab>
        <van-tab title="按医生">
          <div v-if="doctorList2">
            <div class="doctor-list" v-if="doctorList2.length">
              <van-row class="list-item" type="flex" justify="space-between" :gutter="20" v-for="(c,i) in doctorList2" :key="i">
                <van-col :span="4">
                  <div v-show="!c.YSTX" class="item-icon">
                    <i class="iconfont icon-doctor"></i>
                  </div>
                  <img v-show="c.YSTX" class="item-icon" :src="c.YSTX" />
                </van-col>
                <van-col :span="14">
                  <div class="item-cont">
                    <div>
                      <span class="name f-mr10">{{c.YSXM}}</span>
                      <span class="post" v-if="c.YSJB">{{c.YSJB}}</span>
                    </div>
                    <div class="tips">
                      <span class="f-mr5">挂号费</span>
                      <span class="g-w60 s-cf30b32">￥{{c.GHF}}</span>
                    </div>
                  </div>
                </van-col>
                <van-col :span="6">
                  <van-button class="item-btn f-bs3 g-w70" round color="#00c792" @click="toDoctorHome(c)">预约</van-button>
                </van-col>
              </van-row>
            </div>
            <van-empty v-else description="科室暂无可预约的医生" />
          </div>
        </van-tab>
      </van-tabs>
    </div>
  </div>
</template>
<script>
export default {
  name: 'yygh_ks_home1',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle(t.queryData.GHKS || '科室主页')
    t.searchForm1.ORG_ID = t.queryData.ORG_ID
    t.searchForm2.ORG_ID = t.queryData.ORG_ID
    t.searchForm1.DEPT_ID = t.queryData.KSID
    t.searchForm2.DEPT_ID = t.queryData.KSID
    t.pathParams = t.queryData
    t.activeIndex = t.$store.state.store_yygh.kshome_tab_index
    t.getDateList()
    t.getDoctorList1()
    t.getDoctorList2()
  },
  data() {
    return {
      activeIndex: 0,
      currentIndex: 0,
      dateList: [],
      doctorList1: '',
      doctorList2: '',
      searchForm1: {
        ORG_ID: '',
        DEPT_ID: '',
        KSRQ: '',
        JSRQ: '',
      },
      searchForm2: {
        ORG_ID: '',
        DEPT_ID: '',
        KSRQ: '',
        JSRQ: '',
      },
      pbList: [],
      pathParams: ''
    }
  },
  methods: {
    toPb(data, info) {
      var t = this
      t.pathParams.YSID = info.YSID
      t.pathParams.GHYS = info.YSXM
      t.pathParams.ZJBZ = info.ZJBZ
      t.pathParams.YSJB = info.YSJB
      t.pathParams.YSJJ = info.YSJJ
      t.pathParams.GHF = info.GHF
      t.pathParams.TIMERANGE = data.ZZLXMC
      t.pathParams.YYRQ = data.CZRQ
      t.pathParams.SCHEDULEID = data.scheduleId
      t.pathParams.REGFEE = data.regFee
      t.pathParams.TREATFEE = data.treatFee
      t.pathParams.ISTIMEREG = data.isTimeReg
      t.pathParams.PBLX = data.shiftCode
      t.linkToPage('yygh_ys_pb1', t.pathParams)
    },
    // 按日期
    getDoctorList1() {
      var t = this
      t.ajax({
        serverId: 'WXBM00003',
        url: t.serverPath1,
        params: t.searchForm1,
        success: function(data) {
          console.log(t.searchForm1)
          t.doctorList1 = data[0].CONTENTS || []
          console.log(t.doctorList1)
        }
      })
    },
    // 按医生
    getDoctorList2() {
      var t = this
      t.ajax({
        serverId: 'WXBM00003',
        url: t.serverPath1,
        params: t.searchForm2,
        success: function(data) {
          t.doctorList2 = data[0].CONTENTS || []
          console.log(data)
        }
      })
    },
    getDateList() {
      var t = this
      for (var i = 1; i <= 7; i++) {
        var day = t.$moment().add(i, 'days')
        var date = day.format("MM/DD")
        var week = day.day()
        if (week == 1) {
          week = '周一'
        } else if (week == 2) {
          week = '周二'
        } else if (week == 3) {
          week = '周三'
        } else if (week == 4) {
          week = '周四'
        } else if (week == 5) {
          week = '周五'
        } else if (week == 6) {
          week = '周六'
        } else if (week == 0) {
          week = '周日'
        }
        if (i === 1) {
          t.dateList.push({ 'day': day, 'date': date, 'txt': '明天' })
        } else {
          t.dateList.push({ 'day': day, 'date': date, 'txt': week })
        }
      }
      t.searchForm1.KSRQ = t.dateList[t.currentIndex].day.format("YYYY-MM-DD")
      t.searchForm1.JSRQ = t.searchForm1.KSRQ
      t.searchForm2.KSRQ = t.searchForm1.KSRQ
      t.searchForm2.JSRQ = t.dateList[6].day.format("YYYY-MM-DD")
    },
    doClickDate(index) {
      var t = this
      t.currentIndex = index
      t.searchForm1.KSRQ = t.dateList[t.currentIndex].day.format("YYYY-MM-DD")
      t.searchForm1.JSRQ = t.searchForm1.KSRQ 
      if (index != 6||(index == 6&&t.$moment().format('HHmm')>=2030)) { //最后一天要当天晚上8:30之后才更新排班
        t.getDoctorList1()
      }else{
        t.doctorList1=[]
      }
    },
    onClickTabs(index) {
      var t = this
      t.activeIndex = index
      t.$store.state.store_yygh.kshome_tab_index = index
    },
    toDoctorHome(data) {
      var t = this
      console.log(data)
      t.pathParams.YSID = data.YSID
      t.pathParams.GHYS = data.YSXM
      t.linkToPage('yygh_ys_home1', t.pathParams)
    },
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.yygh_ks_home {}

.yygh_ks_home>>>.van-tabs--line .van-tabs__wrap {
  height: 60px;
  padding-bottom: 5px;
  border-bottom: 10px solid #f8f8f8;
}

.yygh_ks_home>>>.van-tab__text {
  font-size: 15px;
}

.yygh_ks_home>>>[class*=van-hairline]::after {
  border: 0;
}

.yygh_ks_home>>>.van-tabs__line {
  background: #00c792;
}

.yygh_ks_home .date-tabs {
  display: flex;
  border-bottom: 1px solid #ebeef5;
}

.yygh_ks_home .date-tabs .item {
  text-align: center;
  padding: 15px 0;
  font-size: 15px;
  color: #303133;
}

.yygh_ks_home .date-tabs .item.on {
  color: #fff;
  background: #00c792;
}

.yygh_ks_home .doctor-list .list-item {
  padding: 20px 15px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
  line-height: 50px;
  text-align: center;
}

.yygh_ks_home .doctor-list .item-icon {
  background: #f2f6fc;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
}

.yygh_ks_home .doctor-list .item-cont {
  line-height: 25px;
  font-size: 14px;
  text-align: left;
}

.yygh_ks_home .doctor-list .item-cont .name {
  font-weight: bold;
  font-size: 15px;
}

.yygh_ks_home .doctor-list .item-cont .tips span {
  display: inline-block;
}

.yygh_ks_home .doctor-list .item-select {
  align-items: center;
  background: #e8faf2;
  border-radius: 3px;
  height: 34px;
  font-size: 14px;
  color: #00c792;
  margin-top: 12px;
  overflow: hidden;
}

.yygh_ks_home .doctor-list .item-select .val {
  padding-left: 10px;
}

.yygh_ks_home .doctor-list .item-select .btn {
  color: #fff;
  width: 60px;
}

.yygh_ks_home .doctor-list .iconfont {
  color: #d7dee8;
  font-size: 32px;
}

.yygh_ks_home>>>.van-button {
  height: 30px;
  padding: 0 8px;
}

.yygh_ks_home .pop-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 15px;
  color: #c0c4cc;
  padding: 15px;
}

.yygh_ks_home .pop-cont {
  font-size: 15px;
  padding: 15px;
}

.yygh_ks_home .select-list {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
}

.yygh_ks_home .select-list .select-item {
  width: 110px;
  margin-bottom: 15px;
}

.yygh_ks_home>>>.van-empty {
  font-size: 15px;
  text-align: center;
}
</style>