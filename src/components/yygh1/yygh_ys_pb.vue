<template>
  <div class="yygh_ys_pb g-wrap s-bgcfff">
    <div v-if="doctorInfo">
      <van-row class="info-wrap f-bb10" type="flex" justify="space-between" :gutter="20">
        <van-col :span="4">
          <div class="item-icon" v-if="!doctorInfo.YSTX">
            <i class="iconfont icon-doctor"></i>
          </div>
          <img class="item-icon" v-else :src="doctorInfo.YSTX">
        </van-col>
        <van-col :span="20">
          <div class="item-cont">
            <div class="item-head f-mb8">
              <div class="name">{{doctorInfo.GHYS}} <span class="post f-ml8" v-if="queryData.YSJB">{{queryData.YSJB}}</span></div>
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
      <div class="date-list">
        <div class="tit"><i class="icon"></i>选择预约时间</div>
        <template v-for="(c,i) in pbList">
          <template v-if="c.regLeaveCount!=0">
            <div class="list-item" :key="i" @click="toQRYY(c)">
              <span class="f-ml8">{{$moment(doctorInfo.YYRQ).format('MM-DD')}}</span>
              <span class="f-ml8">{{doctorInfo.TIMERANGE}}</span>
              <span class="f-ml8">{{c.startTime}}-{{c.endTime}}</span>
              <div class="num">余号{{c.regLeaveCount}}</div>
              <i class="f-fr iconfont icon-arrow-right f-fs12 s-cc0c4cc"></i>
            </div>
          </template>
        </template>
        <van-empty v-if="isShowPbList1" description="无可预约时段" />
      </div>
    </div>
    <van-empty v-else description="无可预约时段" />
  </div>
</template>
<script>
  export default {
    name: 'yygh_ys_pb1',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('医生排班')
      t.searchForm.ORG_ID = t.queryData.ORG_ID
      t.searchForm.SCHEDULEID = t.queryData.SCHEDULEID
      t.getPbList()
      t.getFollowed({
        FLAG: ''
      })
      t.pathParams = t.queryData
      t.doctorInfo = t.queryData
    },
    data() {
      return {
        isOpenDetail: false,
        isFollow: false,
        searchForm: {
          ORG_ID: '',
          SCHEDULEID: ''
        },
        doctorInfo: '',
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
        isShowPbList1: false,
      }
    },
    methods: {
      isShowPbList() {
        var t = this
        t.$nextTick(function() {
          var len = document.querySelectorAll('.yygh_ys_pb .date-list .list-item').length
          console.log(len)
          t.isShowPbList1 = Boolean(len <= 0)
        })
      },
      toQRYY(data) {
        console.log(data)
        var t = this
        t.pathParams.STARTTIME = data.startTime
        t.pathParams.ENDTIME = data.endTime
        t.pathParams.PERIODID = data.periodId
        // 判断是否注册
        console.log(sessionStorage.getItem("hz_userinfo"))
        if (!sessionStorage.getItem("hz_userinfo")) {
          t.isRegister({
            openid: t.wx_userInfo.openid,
            type: 0
          }, function() {
            if (data.KGHRS != 0) {
              t.linkToPage('yygh_qryy1', t.pathParams)
            } else {
              t.$toast('该时段暂无剩余号源，请预约其他时段的号源。')
            }
          })
        } else {
          if (data.KGHRS != 0) {
            t.linkToPage('yygh_qryy1', t.pathParams)
          } else {
            t.$toast('该时段暂无剩余号源，请预约其他时段的号源。')
          }
        }
      },
      // 获取医生排班表
      getPbList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00005',
          url: t.serverPath1,
          params: t.searchForm,
          success: function(data) {
            t.pbList = data[0].CONTENTS
            console.log(data)
            setTimeout(function() {
              t.isShowPbList()
            }, 100)
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
          t.searchForm2.KSID = t.queryData.KSID || t.doctorInfo.PBKS
        } else if (params.FLAG == 'Insert') { //关注某医生
          t.searchForm2.OPENID = t.wx_userInfo.openid
          t.searchForm2.ORG_ID = t.queryData.ORG_ID
          t.searchForm2.ORG_YQ = t.queryData.ORG_YQ
          t.searchForm2.KSID = t.queryData.KSID || t.doctorInfo.PBKS
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
          t.getFollowed({
            FLAG: 'Delete'
          })
        } else {
          t.getFollowed({
            FLAG: 'Insert'
          })
        }

      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .yygh_ys_pb .info-wrap {
    padding: 15px;
    font-size: 14px;
  }

  .yygh_ys_pb .info-wrap .item-icon {
    background: #f2f6fc;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
  }

  .yygh_ys_pb .info-wrap .icon-doctor {
    color: #d7dee8;
    font-size: 32px;
  }

  .yygh_ys_pb .info-wrap .item-cont .item-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .yygh_ys_pb .info-wrap .item-cont .name {
    font-weight: bold;
    font-size: 15px;
  }

  .yygh_ys_pb .info-wrap .item-cont .post {
    font-weight: normal;
    font-size: 14px;
  }

  .yygh_ys_pb .info-wrap .item-cont .desc {
    line-height: 20px;
  }

  .yygh_ys_pb .info-wrap .item-cont .desc p.close {
    height: 20px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .yygh_ys_pb .date-list {
    background: #fff;
  }

  .yygh_ys_pb .date-list .tit {
    font-weight: bold;
    font-size: 15px;
    align-items: center;
    display: flex;
    padding: 18px 15px;
  }

  .yygh_ys_pb .date-list .tit .icon {
    display: inline-block;
    width: 5px;
    height: 15px;
    background: #00c792;
    border-radius: 10px;
    margin-right: 10px;
  }

  .yygh_ys_pb .date-list .list-item {
    display: flex;
    align-items: center;
    padding: 15px;
    font-size: 15px;
    border-top: 1px solid #ebeef5;
    position: relative;
  }

  .yygh_ys_pb .date-list .list-item .num {
    min-width: 65px;
    background: #f0fdf7;
    color: #00c792;
    font-size: 14px;
    padding: 4px 10px;
    border-radius: 20px;
    margin-left: 20px;
  }

  .yygh_ys_pb .date-list .list-item .iconfont {
    position: absolute;
    right: 15px;
  }

  .yygh_ys_pb>>>.van-button {
    height: 30px;
    padding: 0 8px;
  }
</style>