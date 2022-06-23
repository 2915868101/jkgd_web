<template>
  <div class="suggestion_section s-bgcfff">
    <div class="f-pl15 f-pr15 s-bgcfff f-tit f-fs15">
      <i class="iconfont icon-warn icon2 f-mr10 s-cffa54d"></i>
      <span class="s-c909399">预检结果及推荐就诊科室仅供参考</span>
    </div>
    <div class="s-bgcfff s-c303133 f-fs16 f-fwb item f-bb10">{{queryData.WTMS}}</div>
    <div class="f-pl15 f-pr15 s-bgcfff f-tit f-fs15">
      <i class="iconfont icon-res icon2 f-mr10 s-c00c792"></i>
      <span class="s-c909399">参考科室</span>
    </div>
    <div class="s-bgcfff f-fs16 f-fwb m-items f-bb10">
      <div class="item f-df" v-for="(c,i) in dataInfo" :key="i">
        <div class="f-df1">{{c.KSMC}}</div>
        <div class="f-dfv" v-if="serverType!='hz'&&c.KSDM"><span class="send_btn" @click="GoChooseDoctorURL(c)">去挂号</span></div>
      </div>
    </div>
    <div class="more-wrap">
      <div class="tit f-pt10 f-pb10 f-pl10 s-c909399">您还可以先了解</div>
      <div class="flex-box">
        <div class="flex-item f-dfc g-hp100" v-if="serverType=='cz'" @click="handClick('https://m.pule.com/coop/hnwisdom_chizhou/')">
          <img class="img" src="../../assets/img/guidance/jkzsk_icon.png">
          <p>科普视频</p>
        </div>
        <div class="flex-item f-dfc g-hp100" v-else @click="linkToPage('jkbk_index')">
          <img class="img" src="../../assets/img/guidance/jkzsk_icon.png">
          <p>健康知识库</p>
        </div>
        <div class="flex-item f-dfc g-hp100" v-if="serverType=='hz'">
          <img class="img" src="../../assets/img/guidance/jkzsk_icon.png">
          <wx-open-launch-weapp class="g-wp80" id="launch-btn" username="gh_0540bb418833" path="pages/wellcome/wellcome.html">
            <script type="text/wxtag-template">
              <style> 
                .btn {  
                  width:100px;
                  font-weight: bold;
                  font-size: 15px;
                  color: #303133;
                }
              </style> 
              <div class="btn">互联网医院</div> 
            </script>
          </wx-open-launch-weapp>
        </div>
        <!-- <div class="flex-item f-dfc g-hp100" @click="linkToPage('zxzx_index')">
          <img class="img" src="../../assets/img/guidance/zxzx_icon.png">
          <p>在线咨询</p>
        </div> -->
      </div> 
    </div>
  </div>
</template>
<script>
export default {
  name: 'suggestion_section',
  components: {},
  created() {
    var t = this;
    t.updateTitle('智能导诊')
    t.getDepartment()
    // gh_4b2065b61139
    // gh_00398bb875d5
    if(t.serverType=='hz'){ //只有惠州三医院的才会显示
      t.getwxapp(t.wx_configParams)
    }
  },
  data() {
    return {
      dataInfo: ''
    }
  },
  methods: {
    getwxapp(config) {
      var t = this
      // t.getHospitalList()
      var wx_config = JSON.parse(JSON.stringify(config))
      wx_config.jsApiList = ['getLocation']
      wx_config.openTagList = ['wx-open-launch-weapp']
      t.$wechat.config(wx_config)
      t.$wechat.ready(function() {
        
      })
      t.$wechat.error(function(res) {
        console.log(res)
        // t.$dialog.alert({
        //   message: `<div class="f-tal"> 
        //     参数：${JSON.stringify(res)} 
        //   </div>`
        // })
      })
    },
    handClick(url) {
      window.location.href = url
    },
    getDepartment() {
      var t = this
      t.ajax({
        method: 'post',
        serverId: 'YY000047',
        url: t.serverPath1,
        params: {
          WTMS: t.queryData.WTMS,
          ORG_ID: t.queryData.ORG_ID
        },
        success(data) {
          console.log(data)
          t.dataInfo = data
        }
      })
    },
    GoChooseDoctorURL(objdata) {
      var t = this
      if (objdata.KSDM) {
        if(t.serverType=='hz'){
          t.linkToPage('/yygh_ks_home1', { KSID: objdata.KSDM, GHKS: objdata.KSMC, ORG_ID: t.queryData.ORG_ID, ORG_NAME: t.queryData.ORG_NAME })
        }else{
          t.linkToPage('/yygh_ks_home', { KSID: objdata.KSDM, GHKS: objdata.KSMC, ORG_ID: t.queryData.ORG_ID, ORG_NAME: t.queryData.ORG_NAME })
        } 
      } else {
        t.$dialog.alert({
          title: '提示',
          message: '当前医院未开通此科室,为您推荐其他可挂号医院'
        }).then(() => {
          t.linkToPage('/yygh_index', { ORG_ID: t.queryData.ORG_ID, ORG_NAME: t.queryData.ORG_NAME })
        })
      }
    }
  },
  mounted() {}
}
</script>
<style scoped>
.suggestion_section .f-tit {
  height: 47px;
  line-height: 47px;
  border-bottom: 1px solid #ebebeb;
}

.suggestion_section .f-tit .icon {
  height: 18px;
  position: relative;
  top: 2px;
}

.suggestion_section .f-tit .icon.icon2 {
  height: 16px;
}

.suggestion_section .item {
  padding: 15px 20px 15px 40px;
  line-height: 30px;
}

.suggestion_section .m-items .item {
  border-bottom: 1px solid #ebebeb;
}

.suggestion_section .m-items .item .send_btn {
  display: inline-block;
  font-size: 14px;
  font-weight: normal;
  color: #fff;
  background-color: #00c792;
  width: 65px;
  height: 30px;
  line-height: 30px;
  border-radius: 15px;
  text-align: center;
}

.suggestion_section .f-bb10 {
  border-bottom: 10px solid #f4f9f9;
}

.suggestion_section .more-wrap {
  padding: 15px;
  font-size: 14px;
}

.suggestion_section .more-wrap .flex-box {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.suggestion_section .more-wrap .flex-box .flex-item {
  width: 50%;
  display: flex;
  align-items: center;
  padding: 15px;
}

.suggestion_section .more-wrap .flex-box .img {
  width: 30px;
  margin-right: 15px;
}

.suggestion_section .more-wrap .flex-box p {
  font-weight: bold;
  font-size: 15px;
  color: #303133;
}
</style>