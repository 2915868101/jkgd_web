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
              <div><span class="f-fwb f-mr5">{{doctorInfo.YSXM||queryData.GHYS}}</span> <span class="f-mr5" v-if="queryData.GHKS">{{queryData.GHKS}}</span> <span class="post">{{doctorInfo.ZZJB}}</span></div>
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
      <div class="date-list" v-if="pbList.length">
        <div class="tit"><i class="icon"></i>选择预约时间</div>
        <template v-for="(c,i) in pbList">
          <template v-if="c.KGHRS!=0">
            <!-- 当天挂号时段过时隐藏 -->
            <template v-if="isShowPbList1||(!isShowPbList1&&hours>7&&hours<12)||(!isShowPbList1&&hours>11&&hours<17)&&(c.TIMERANGE=='下午'||c.TIMERANGE=='PM')||c.TIMERANGE=='全天'">
              <!--  -->
              <div class="list-item pb-item" :key="i" @click="toQRYY(c)">
                <!-- <div class="list-item pb-item" v-if="!queryData.TIMERANGE||c.TIMERANGE==queryData.TIMERANGE||c.TIMERANGE=='全天'" :key="i" @click="toQRYY(c)"> -->
                <span class="f-ml8">{{$moment(queryData.YYRQ).format('MM-DD')}}</span>
                <span class="f-ml8">{{c.TIMERANGE=='AM'||c.TIMERANGE=='上午'?'上午':'下午'}}</span>
                <!-- <span class="f-ml8">{{c.YYSD.indexOf("-") != -1?parseInt(String(c.YYSD.split('-')[1]).replace(/:/g,'')):parseInt(String(c.YYSD).substr(0,5).replace(/:/g,''))}}</span> -->
                <span class="f-ml8">{{c.YYSD}}</span>
                <div :class="[c.KGHRS==0?'on':'','num']">余号{{c.KGHRS}}</div>
                <i class="f-fr iconfont icon-arrow-right f-fs12 s-cc0c4cc"></i>
                <!-- </div> -->
              </div>
            </template>
          </template>
        </template>
      </div>
      <van-empty v-if="isShowPbList2" description="无可预约时段" />
    </div>
  </div>
</template>
<script>
  export default {
    name: 'yygh_ys_home',
    components: {},
    computed: {
      isShowPbList1() {
        var t = this
        return parseInt(String(t.queryData.YYRQ).replace(/-/g, '')) > parseInt(t.nowDate.format('YYYYMMDD'))
      }
    },
    created() {
      var t = this
      t.updateTitle('医生排班')
      t.searchForm.ORG_ID = t.queryData.ORG_ID
      t.searchForm.YYRQ = t.queryData.YYRQ
      t.searchForm.YSID = t.queryData.YSID
      t.searchForm.KSDM = t.queryData.KSID
      t.getPbList()
      t.getFollowed({
        FLAG: ''
      })
      t.pathParams = t.queryData

      t.nowDate = t.$moment()
      // 当天的一个小时之后的才显示
      t.timer = setInterval(function() {
        t.nowDate = t.$moment()
      }, 30000)
    },
    data() {
      return {
        hours: new Date().getHours(),
        timer: '',
        nowDate: '',
        nodata: false,
        isOpenDetail: false,
        isFollow: false,
        searchForm: {
          ORG_ID: '',
          YYRQ: '',
          YSID: '',
          KSDM: '',
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
        isShowPbList2: false
      }
    },
    methods: {
      isShowPbList() {
        var t = this
        t.$nextTick(function() {
          var len = document.querySelectorAll('.yygh_ys_home .date-list > .pb-item').length
          t.isShowPbList2 = Boolean(len <= 0)
        })
      },
      toQRYY(data) {
        var t = this
        t.pathParams.GHF = t.doctorInfo.GHF
        t.pathParams.NUMBERID = data.NUMBERID
        t.pathParams.PBLX = data.PBLX
        t.pathParams.TIMERANGE = data.TIMERANGE
        t.pathParams.YYSD = data.YYSD
        t.pathParams.PBZXID = data.PBZXID
        if (data.KGHRS != 0) {
          // 判断是否注册    
          t.isRegister({
            openid: t.wx_userInfo.openid,
            type: 0
          }, function() {
            t.pathParams.GHKS = t.queryData.GHKS || t.doctorInfo.PBKS
            t.pathParams.KSID = t.queryData.KSID || t.doctorInfo.PBKSID
            if (t.serverType == 'gd') {
              t.pathParams.GHTYPE = t.doctorInfo.GHTYPE
            }
            t.linkToPage('yygh_qryy', t.pathParams)
          })
        } else {
          t.$toast('该时段暂无剩余号源，请预约其他时段的号源')
        }
      },
      // 获取医生排班表
      getPbList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00005',
          url: t.serverPath4 || t.serverPath1,
          params: t.searchForm,
          success: function(data) {
            console.log(t.searchForm)
            console.log(787777, data)
            t.doctorInfo = data[0].CONTENTS[t.queryData.index] || []
            t.pbList = data[0].CONTENTS[t.queryData.index].PB || []
            console.log(t.pbList)
            setTimeout(function() {
              t.isShowPbList()
            }, 100)
          }
        })
      },
      openDetail() {
        this.isOpenDetail = !this.isOpenDetail
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

      }
    },
    mounted() {},
    beforeDestroy() {
      var t = this
      clearInterval(t.timer)
    },
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

  .yygh_ys_home .date-list .tit {
    font-weight: bold;
    font-size: 15px;
    align-items: center;
    display: flex;
    padding: 18px 15px;
  }

  .yygh_ys_home .date-list .tit .icon {
    display: inline-block;
    width: 5px;
    height: 15px;
    background: #00c792;
    border-radius: 10px;
    margin-right: 10px;
  }

  .yygh_ys_home .date-list .list-item {
    display: flex;
    align-items: center;
    padding: 15px;
    font-size: 15px;
    border-top: 1px solid #ebeef5;
    position: relative;
  }

  .yygh_ys_home .date-list .list-item .num {
    min-width: 65px;
    background: #f0fdf7;
    color: #00c792;
    font-size: 14px;
    padding: 4px 10px;
    border-radius: 20px;
    margin-left: 20px;
  }

  .yygh_ys_home .date-list .list-item .num.on {
    background: #f9f9fa;
    color: #909399;
  }

  .yygh_ys_home .date-list .list-item .iconfont {
    position: absolute;
    right: 15px;
  }

  .yygh_ys_home>>>.van-button {
    height: 30px;
    padding: 0 8px;
  }
</style>