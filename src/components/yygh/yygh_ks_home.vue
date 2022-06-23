<template>
  <div class="yygh_ks_home g-wrap g-hp100 s-bgcfff">
    <div class="tabs-wrap s-bgcfff">
      <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
        <van-tab title="按日期">
          <div class="date-tabs">
            <div :class="[currentIndex === index ? 'on' : '','item f-df3']" v-for="(item,index) in dateList" :key="index" @click="doClickDate(index)">
              <p>{{item.txt}}</p>
              <p class="f-mt10">{{item.date}}</p>
            </div>
          </div>
          <div class="date-tab">
            <div class="doctor-list" v-if="doctorList1">
              <van-row class="list-item" type="flex" justify="space-between" :gutter="20" v-for="(item,i) in doctorList1" :key="i">
                <van-col :span="4">
                  <div v-show="!item.YSTX" class="item-icon">
                    <i class="iconfont icon-doctor"></i>
                  </div>
                  <img v-show="item.YSTX" class="item-icon" :src="item.YSTX" />
                </van-col>
                <van-col :span="14">
                  <div class="item-cont">
                    <div><span class="name f-mr10">{{item.YSXM}}</span></div>
                    <div class="tips">
                      <span class="f-mr10 g-w80">余号:{{item.SUMREMAIN}}</span>
                      <span>挂号费:<i class="s-cf30b32">￥{{item.GHF}}</i></span>
                    </div>
                  </div>
                </van-col>
                <van-col :span="6">
                  <!-- <van-button size="small" class="item-btn" round color="#00c792" v-if="item.SUMREMAIN" @click="showPopup(item)">预约</van-button> -->
                  <van-button size="small" class="item-btn" round color="#00c792" v-if="item.SUMREMAIN>0" @click="toPb(item,i)">预约</van-button>
                  <van-button size="small" class="item-btn f-bs3" round color="#c0c4cc" disabled v-else>已约满</van-button>
                </van-col>
              </van-row>
            </div>
            <van-empty v-show="!doctorList1" description="正在查询中.." />
            <van-empty v-show="doctorList1&&!doctorList1.length" description="当天无可预约的医生" />
          </div>
        </van-tab>
        <van-tab title="按医生">
          <div class="doctor-list" v-if="doctorList2">
            <van-row class="list-item" type="flex" justify="space-between" :gutter="20" v-for="(item,i) in doctorList2" :key="i">
              <van-col :span="4">
                <img v-if="item.YSTX&&item.YSTX.length!=0" class="item-icon" :src="item.YSTX" />
                <div v-else class="item-icon">
                  <i class="iconfont icon-doctor"></i>
                </div>
              </van-col>
              <van-col :span="14">
                <div class="item-cont">
                  <div>
                    <span class="name f-mr10">{{item.YSXM||'--'}}</span>
                    <span class="post" v-if="item.YSJB&&item.YSJB.length!=0">{{item.YSJB}}</span>
                  </div>
                  <div class="tips">
                    <!-- <span class="f-mr10 g-w60">余号:20</span> -->
                    <span>挂号费:<i class="s-cf30b32">￥{{item.GHF}}</i></span>
                  </div>
                </div>
              </van-col>
              <van-col :span="6">
                <van-button v-if="queryData.ORG_NAME=='广德市中医院'" size="small" class="item-btn f-bs3" round color="#00c792" @click="toDoctorHome(item)">预约</van-button>
                <van-button v-else size="small" class="item-btn f-bs3" round color="#00c792" @click="toPb(item,i)">预约</van-button>
              </van-col>
            </van-row>
          </div>
          <van-empty v-show="!doctorList2" description="正在查询中.." />
          <van-empty v-show="doctorList2&&!doctorList2.length" description="科室暂无可预约的医生" />
        </van-tab>
      </van-tabs>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'yygh_ks_home',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle(t.queryData.GHKS || '科室主页')
      console.log(t.queryData)
      t.searchForm1.ORG_ID = t.queryData.ORG_ID
      t.searchForm2.ORG_ID = t.queryData.ORG_ID
      t.searchForm1.KSDM = t.queryData.KSID
      t.searchForm2.DEPT_ID = t.queryData.KSID
      t.pathParams = t.queryData
      t.activeIndex = t.$store.state.store_yygh.kshome_tab_index
      t.getDateList()
      // if(t.queryData.ORG_ID!='czsdzxrmyy'){ 
      t.getDoctorList1()
      // }
      t.getDoctorList2()
    },
    data() {
      return {
        activeIndex: 0,
        currentIndex: 0,
        dateList: [],
        doctorList1: [],
        doctorList2: [],
        searchForm1: {
          ORG_ID: '',
          YYRQ: '',
          YSID: '',
          KSDM: '',
        },
        searchForm2: {
          ORG_ID: '',
          KSRQ: '',
          JSRQ: '',
          DEPT_ID: '',
        },
        pbList: [],
        pathParams: ''
      }
    },
    methods: {
      toPb(data, index) {
        var t = this
        if (data.SUMREMAIN > 0) {
          t.pathParams.YSID = data.YSID
          t.pathParams.GHYS = data.YSXM
          t.pathParams.YYRQ = t.searchForm1.YYRQ
          t.pathParams.TIMERANGE = data.ZZLXMC
          t.pathParams.index = index
          t.linkToPage('yygh_ys_pb', t.pathParams)
        }
      },
      // 按日期
      getDoctorList1() {
        var t = this
        t.ajax({
          serverId: 'WXBM00005',
          url: t.serverPath4 || t.serverPath1,
          params: t.searchForm1,
          success: function(data) {
            console.log(789, data)
            console.log(t.searchForm1)
            t.doctorList1 = data[0].CONTENTS
            console.log(11111111, t.doctorList1)
          }
        })
      },
      // 按医生
      getDoctorList2() {
        var t = this
        t.ajax({
          serverId: 'WXBM00003',
          url: t.serverPath4 || t.serverPath1,
          params: t.searchForm2,
          success: function(data) {
            console.log(666, data)
            t.doctorList2 = data[0].CONTENTS
          }
        })
      },
      getDateList() {
        var t = this
        for (var i = 0; i <= 6; i++) {
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
          if (i === 0) {
            t.dateList.push({
              'day': day,
              'date': date,
              'txt': '今天'
            })
          } else {
            t.dateList.push({
              'day': day,
              'date': date,
              'txt': week
            })
          }
        }
        t.searchForm1.YYRQ = t.dateList[t.currentIndex].day.format("YYYY-MM-DD")
        t.searchForm2.KSRQ = t.searchForm1.YYRQ
        t.searchForm2.JSRQ = t.serverType == 'cz' ? t.dateList[3].day.format("YYYY-MM-DD") : t.dateList[6].day.format("YYYY-MM-DD")
      },
      onClickTabs(index) {
        var t = this
        t.getDoctorList1()
        t.getDoctorList2()
        t.activeIndex = index
        t.doctorList1 = []
        t.doctorList2 = []
        t.$store.state.store_yygh.kshome_tab_index = index
      },
      toDoctorHome(item) {
        var t = this
        t.pathParams.YSID = item.YSID
        t.pathParams.GHYS = item.YSXM
        t.pathParams.YSTX = item.YSTX
        t.linkToPage('yygh_ys_home', t.pathParams)
      },
      doClickDate(index) {
        var t = this
        t.currentIndex = index

        t.doctorList1 = []
        t.doctorList2 = []
        if (t.serverType == 'cz' && index >= 4) { //
          t.doctorList1 = []
        } else {
          t.searchForm1.YYRQ = t.dateList[t.currentIndex].day.format("YYYY-MM-DD")
          t.getDoctorList1()
        }
      }
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

  .yygh_ks_home .doctor-list .iconfont {
    color: #d7dee8;
    font-size: 32px;
  }

  .yygh_ks_home>>>.van-button {
    height: 30px;
    padding: 0 8px;
    font-size: 15px;
  }

  .yygh_ks_home>>>.van-empty {
    font-size: 15px;
    text-align: center;
  }
</style>