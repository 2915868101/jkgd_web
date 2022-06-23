<template>
  <div class="yygh_ys_home g-wrap">
    <div v-if="doctorInfo">
      <van-row class="info-wrap s-bgcfff f-mb10" type="flex" justify="space-between" :gutter="20">
        <van-col :span="4">
          <div class="item-icon" v-if="!doctorInfo.YSTX">
            <i class="iconfont icon-doctor"></i>
          </div>
          <img class="item-icon" v-else :src="doctorInfo.YSTX">
        </van-col>
        <van-col :span="20">
          <div class="item-cont">
            <div class="item-head f-mb8">
              <div class="name">{{doctorInfo.YSXM||doctorInfo.GHYS}} <span class="post f-ml8" v-if="doctorInfo.YSJB">{{doctorInfo.YSJB}}</span></div>
              <div :class="[isFollow ? 's-cffa54d' : '','follow-item']" @click="doFollow">
                <i class="iconfont icon-follow"></i> <span v-show="isFollow">已</span>关注
              </div>
            </div>
            <div class="desc" v-if="doctorInfo.YSJJ.length">
              <p :class="[isOpenDetail ? '':'close']">{{doctorInfo.YSJJ}}</p>
              <span class="s-c00c792 f-fs14" @click="openDetail" v-if="doctorInfo.YSJJ.length>20">
                <span v-if="!isOpenDetail">详情</span>
                <span v-else>收起</span>
                <i class="iconfont icon-arrow-right f-fs12"></i>
              </span>
            </div>
            <div class="desc s-c999" v-else>医生介绍暂无</div>
          </div>
        </van-col>
      </van-row>
      <div class="date-list" v-if="doctorData.length">
        <van-row class="list-item" type="flex" justify="space-between" :gutter="20" v-for="(c,i) in doctorData" :key="i">
          <van-col :span="18">
            <div class="item-cont">
              <div class="date f-mb5">
                <span class="f-mr8">{{c.CZRQ}}</span>
                <span class="f-mr8">{{getWeek(c.CZRQ)}}</span>
                <span>{{c.ZZLXMC}}</span>
              </div>
              <div class="tips">
                <span class="f-mr8">挂号费</span>
                <span class="g-w60 s-cf30b32">￥{{doctorInfo.GHF}}</span>
              </div>
            </div>
          </van-col>
          <van-col :span="6">
            <van-button class="item-btn g-w70" round color="#00c792" @click="toPb(c)">预约</van-button>
          </van-col>
        </van-row>
      </div>
      <van-empty v-else description="该医生暂无排班" />
    </div>
    <div class="g-hp100 s-bgcfff" v-else>
      <van-empty description="找不到医生信息" />
    </div>
  </div>
</template>
<script>
export default {
  name: 'yygh_ys_home1',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle('医生主页')
    t.searchForm.ORG_ID = t.queryData.ORG_ID
    t.searchForm.DEPT_ID = t.queryData.KSID || t.queryData.GHKS || ''
    t.searchForm.YSID = t.queryData.YSID
    t.searchForm.KSRQ = t.$moment().add(1, 'days').format('YYYY-MM-DD')
    t.searchForm.JSRQ = t.$moment().add(7, 'days').format('YYYY-MM-DD')
    t.getPbList()
    t.getFollowed({ FLAG: '' })
    t.pathParams = t.queryData
  },
  data() {
    return {
      isOpenDetail: false,
      isFollow: false,
      searchForm: {
        ORG_ID: '',
        KSRQ: '',
        JSRQ: '',
        DEPT_ID: '',
        YSID: '',
      },
      doctorInfo: '',
      doctorData: [],
      pbList: [],
      searchForm2: {
        FLAG: '',
        ORG_ID: '',
        OPENID: '',
        YSXM: '',
        YSSC: '',
        YSID: '',
        ZJBZ: '',
        YSTX: '',
        GHF: '',
        YSJB: '',
        ID: '',
      },
      pathParams: '',
      followId: '',
    }
  },
  methods: {
    toPb(data) {
      var t = this
      t.pathParams.ZJBZ = t.doctorInfo.ZJBZ
      t.pathParams.YSJJ = t.doctorInfo.YSJJ
      t.pathParams.YSJB = t.doctorInfo.YSJB
      t.pathParams.GHF = t.doctorInfo.GHF
      t.pathParams.GHKS = t.doctorInfo.PBKS
      t.pathParams.KSID = t.queryData.KSID || t.doctorInfo.KSDM
      t.pathParams.TIMERANGE = data.ZZLXMC
      t.pathParams.YYRQ = data.CZRQ
      t.pathParams.SCHEDULEID = data.scheduleId
      t.pathParams.REGFEE = data.regFee
      t.pathParams.TREATFEE = data.treatFee
      t.pathParams.ISTIMEREG = data.isTimeReg
      t.pathParams.PBLX = data.shiftCode
      t.linkToPage('yygh_ys_pb1', t.pathParams)
    },
    // 获取医生排班表
    getPbList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00003',
        url: t.serverPath1,
        params: t.searchForm,
        success: function(data) { 
          console.log(t.searchForm)
          console.log(data) 
          t.doctorInfo = data[0].CONTENTS[0] || t.queryData
          t.doctorData = data[0].CONTENTS[0] ? data[0].CONTENTS[0].scheduleInfo : []
          console.log(t.doctorInfo,t.doctorData)
        }
      })
    },
    openDetail() {
      this.isOpenDetail = !this.isOpenDetail
      console.log(this.isOpenDetail)
    },
    // 关注医生接口
    getFollowed(params) {
      var t = this
      t.searchForm2.FLAG = params.FLAG
      if (params.FLAG == '') { // 该医生是否被用户关注
        t.searchForm2.OPENID = t.wx_userInfo.openid
        t.searchForm2.YSXM = t.queryData.GHYS
        t.searchForm2.YSID = t.queryData.YSID
        t.searchForm2.ORG_ID = t.queryData.ORG_ID
        t.searchForm2.ORG_YQ = t.queryData.ORG_YQ
        t.searchForm2.KSID = t.queryData.KSID||t.doctorInfo.PBKS
      } else if (params.FLAG == 'Insert') { //关注某医生
        t.searchForm2.OPENID = t.wx_userInfo.openid
        t.searchForm2.ORG_ID = t.queryData.ORG_ID
        t.searchForm2.ORG_YQ = t.queryData.ORG_YQ
        t.searchForm2.KSID = t.queryData.KSID||t.doctorInfo.PBKS
        t.searchForm2.YSXM = t.queryData.GHYS
        t.searchForm2.YSID = t.queryData.YSID
        t.searchForm2.YSTX = t.queryData.YSTX || ''
        t.searchForm2.GHF = t.doctorInfo.GHF
        t.searchForm2.YSJB = t.doctorInfo.YSJB || ''
        t.searchForm2.ZJBZ = t.doctorInfo.ZJBZ || ''
        t.searchForm2.YSSC = t.doctorInfo.YSJJ || ''
      } else if (params.FLAG == 'Delete') { //取消关注 
        t.searchForm2.ID = t.followId
      }
      t.ajax({
        serverId: 'WXBM00033',
        url: t.serverPath1,
        params: t.searchForm2,
        success: function(data) {
          console.log(t.searchForm2)
          console.log(data)
          if (params.FLAG == '') {
            t.isFollow = data[0].CONTENTS[0].FOCUS == 'NO' ? false : true
            t.followId = data[0].CONTENTS[0].ID
          } else if (params.FLAG == 'Insert') {
            if (data[0].CODE == 0) {
              t.isFollow = true
              t.getFollowed({ FLAG: '' })
              t.$toast('关注成功')
            }
          } else if (params.FLAG == 'Delete') {
            if (data[0].CODE == 0) {
              t.isFollow = false
              t.$toast('已取消关注')
            }
          }
        }
      })
    },
    doFollow() {
      var t = this
      if (t.isFollow) {
        t.getFollowed({ FLAG: 'Delete' })
      } else {
        t.getFollowed({ FLAG: 'Insert' })
      }

    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.yygh_ys_home .info-wrap {
  padding: 15px;
  font-size: 14px;
}

.yygh_ys_home .info-wrap .item-icon {
  background: #f2f6fc;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
}

.yygh_ys_home .info-wrap .icon-doctor {
  color: #d7dee8;
  font-size: 32px;
}

.yygh_ys_home .info-wrap .item-cont .item-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.yygh_ys_home .info-wrap .item-cont .name {
  font-weight: bold;
  font-size: 15px;
}

.yygh_ys_home .info-wrap .item-cont .post {
  font-weight: normal;
  font-size: 14px;
}

.yygh_ys_home .info-wrap .item-cont .desc {
  line-height: 20px;
}

.yygh_ys_home .info-wrap .item-cont .desc p.close {
  height: 20px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.yygh_ys_home .date-list {
  background: #fff;
}

.yygh_ys_home .date-list .list-item {
  padding: 20px 15px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
  line-height: 50px;
  text-align: center;
}

.yygh_ys_home .date-list .item-cont {
  line-height: 25px;
  font-size: 14px;
  text-align: left;
}

.yygh_ys_home .date-list .date {
  font-weight: bold;
  font-size: 15px;
}

.yygh_ys_home>>>.van-button {
  height: 30px;
  padding: 0 8px;
}

.yygh_ys_home .pop-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 15px;
  color: #c0c4cc;
  padding: 15px;
}

.yygh_ys_home .pop-cont {
  font-size: 15px;
  padding: 15px;
}

.yygh_ys_home .select-list {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
}

.yygh_ys_home .select-list .select-item {
  width: 110px;
  margin-bottom: 15px;
}
</style>