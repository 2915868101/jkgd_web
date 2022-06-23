<template>
  <div class="yygh_ys_home g-wrap">
    <div v-if="doctorInfo">
      <van-row class="info-wrap s-bgcfff f-mb10" type="flex" justify="space-between" :gutter="20">
        <van-col :span="4">
          <div class="item-icon" v-if="!queryData.YSTX">
            <i class="iconfont icon-doctor"></i>
          </div>
          <img class="item-icon" v-else :src="queryData.YSTX">
        </van-col>
        <van-col :span="20">
          <div class="item-cont">
            <div class="item-head f-mb8">
              <div><span class="f-fwb f-mr5">{{doctorInfo.YSXM||queryData.GHYS}}</span>
                <span class="f-mr5" v-if="queryData.GHKS">{{queryData.GHKS}}</span>
                <span class="post" v-if="doctorInfo.YSJB.length!=0">{{doctorInfo.YSJB}}</span></div>
              <div :class="[isFollow ? 's-cffa54d' : '','follow-item']" @click="doFollow">
                <i class="iconfont icon-follow"></i> <span v-show="isFollow">已</span>关注
              </div>
            </div>
            <div class="desc" v-if="doctorInfo.YSJJ">
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
              <div class="date f-mb5">{{c.CZRQ}} <span class="f-ml10">{{getWeek(c.CZRQ)}}</span></div>
              <div class="tips">
                <van-tag plain class="f-mr8" color="#dcdfe6" text-color="#909399">{{c.ZZLXMC}}</van-tag>
                <span class="g-w60 s-cf30b32">￥{{c.GHF?Number(c.GHF).toFixed(2):''}}</span>
              </div>
            </div>
          </van-col>
          <van-col :span="6">
            <!-- <van-button class="item-btn g-w70" round color="#00c792" @click="showPopup(c)">预约</van-button> -->
            <van-button class="item-btn g-w70" round color="#00c792" v-if="isOrder(c)" @click="toPb(c)">预约</van-button>
            <van-button size="small" class="item-btn g-w70 f-bs3" round color="#c0c4cc" disabled v-else>已约满</van-button>
          </van-col>
        </van-row>
      </div>
      <van-empty v-else description="该医生暂无排班" />
      <van-popup v-model="isShowPopup" position="bottom" :style="{height : '30%'}">
        <div class="pop-head">
          <span>请选择预约时段</span>
          <van-icon name="cross" @click.stop="doClose"></van-icon>
        </div>
        <div class="pop-cont">
          <div class="select-list">
            <van-button class="select-item" type="default" round v-for="item in pbList" :key="item.YYSD" @click="doSelect(item)">{{item.YYSD}}</van-button>
          </div>
        </div>
      </van-popup>
    </div>
    <div class="g-hp100 s-bgcfff" v-else>
      <van-empty description="找不到医生信息" />
    </div>
  </div>
</template>
<script>
  export default {
    name: 'yygh_ys_home',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('医生主页')
      t.searchForm.ORG_ID = t.queryData.ORG_ID
      t.searchForm.DEPT_ID = t.queryData.KSID || t.queryData.GHKS || ''
      t.searchForm.YSID = t.queryData.YSID
      t.searchForm.KSRQ = t.$moment().add(0, 'days').format('YYYY-MM-DD')
      t.searchForm.JSRQ = t.$moment().add(3, 'days').format('YYYY-MM-DD')
      t.searchForm1.ORG_ID = t.queryData.ORG_ID
      t.searchForm1.KSDM = t.queryData.KSID || ''
      t.searchForm1.YSID = t.queryData.YSID
      t.getPbList()
      t.getFollowed({
        FLAG: ''
      })
      t.pathParams = t.queryData
      console.log(t.queryData)
    },
    data() {
      return {
        isOpenDetail: false,
        isShowPopup: false,
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
        searchForm1: {
          ORG_ID: '',
          YYRQ: '',
          YSID: '',
          KSDM: '',
        },
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
        t.pathParams.YYRQ = t.$moment(data.CZRQ).format('YYYY-MM-DD')
        t.pathParams.KSID = t.pathParams.KSID || data.PBKS
        t.pathParams.TIMERANGE = data.ZZLXMC
        t.linkToPage('yygh_ys_pb', t.pathParams)
      },
      // 获取医生排班表
      getPbList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00004',
          url: t.serverPath4 || t.serverPath1,
          params: t.searchForm,
          success: function(data) {
            console.log(12, t.searchForm)
            console.log(12, data)
            if (data[0].CODE == 0) {
              t.doctorData = data[0].CONTENTS
              t.doctorInfo = data[0].CONTENTS[0] || t.queryData
              console.log(data)
            }
          }
        })
      },
      openDetail() {
        this.isOpenDetail = !this.isOpenDetail
      },
      showPopup(item) {
        var t = this
        t.searchForm1.YYRQ = item.CZRQ
        t.pathParams.YYRQ = item.CZRQ
        t.pathParams.GHF = item.GHF
        t.ajax({
          serverId: 'WXBM00005',
          url: t.serverPath4 || t.serverPath1,
          params: t.searchForm1,
          success: function(data) {
            if (data[0].CONTENTS[0]) {
              t.isShowPopup = true
              t.pbList = data[0].CONTENTS[0].PB

              t.pathParams.KSID = data[0].CONTENTS[0].PBKSID
              t.pathParams.GHKS = data[0].CONTENTS[0].PBKS
            } else {
              t.$toast('当天无可预约时段！')
              t.pbList = []
            }
          }
        })
      },
      doClose() {
        this.isShowPopup = false
      },
      doSelect(item) {
        var t = this
        t.isShowPopup = false
        t.pathParams.TIMERANGE = item.TIMERANGE
        t.pathParams.YYSD = item.YYSD
        t.pathParams.ZZJB = t.doctorInfo.YSJB
        t.pathParams.PBLX = item.PBLX
        t.pathParams.NUMBERID = item.NUMBERID
        // 判断是否注册
        t.isRegister({
          openid: t.wx_userInfo.openid,
          type: 0
        }, function() {
          t.linkToPage('yygh_qryy', t.pathParams)
        })
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
        } else if (params.FLAG == 'Insert') { //关注某医生
          t.searchForm2.OPENID = t.wx_userInfo.openid
          t.searchForm2.ORG_ID = t.queryData.ORG_ID
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
          url: t.serverPath4 || t.serverPath1,
          params: t.searchForm2,
          success: function(data) {
            if (params.FLAG == '') {
              t.isFollow = data[0].CONTENTS[0].FOCUS == 'NO' ? false : true
              t.followId = data[0].CONTENTS[0].ID
            } else if (params.FLAG == 'Insert') {
              if (data[0].CODE == 0) {
                t.isFollow = true
                t.getFollowed({
                  FLAG: ''
                })
                t.$toast('关注成功')
              } else {
                t.$toast(data[0].MSG)
              }
            } else if (params.FLAG == 'Delete') {
              if (data[0].CODE == 0) {
                t.isFollow = false
                t.$toast('已取消关注')
              } else {
                t.$toast(data[0].MSG)
              }
            }
          }
        })
      },
      doFollow() {
        var t = this
        if (t.isFollow) {
          t.getFollowed({
            FLAG: 'Delete'
          })
        } else {
          t.getFollowed({
            FLAG: 'Insert'
          })
        }
      },
      isOrder(data) { //当天当前时间是否已经过了预约挂号时间
        var t = this
        var state = ''
        if (data.CZRQ == t.$moment().format('YYYY-MM-DD')) {
          if ((data.ZZLXMC == "上午" && t.$moment().format('HHmm') > 1200) || (data.ZZLXMC != "上午" && t.$moment().format('HHmm') > 1800)) {
            state = false
          } else {
            state = true
          }
        } else {
          state = true
        }
        return state
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