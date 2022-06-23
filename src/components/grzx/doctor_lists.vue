<template>
  <div class="doctor_lists g-wrap f-hp100 s-bgcfff">
    <!-- 医生列表 -->
    <div class="doctor-list">
      <van-row class="list-item" type="flex" justify="space-between" v-for="(c,i) in ysList" :key="i">
        <van-col :span="4">
          <div class="item-icon" v-if="!c.YSTX">
            <i class="iconfont icon-doctor"></i>
          </div>
          <img class="item-icon" v-else :src="c.YSTX" />
        </van-col>
        <van-col :span="22">
          <div class="item-cont f-fdc f-fdj g-hp100 f-pl15">
            <div class="f-df f-dfj">
              <div>
                <span class="name  f-mr10">{{c.YSXM}}</span>
                <span class="post">{{c.YSJB}}</span>
              </div>
              <div class="f-df">
                <van-button size="small" class="item-btn g-w55" round color="#ffbd1f" @click="doClick">咨询</van-button>
                <van-button size="small" class="item-btn f-mb8 g-w55 f-ml10" round color="#00c792" @click="toDoctorHome(c)">挂号</van-button>
              </div>
            </div>
            <div class="line1">{{c.ORG_NAME}}</div>
          </div>
        </van-col>
        <!-- <van-col :span="8">
        </van-col> -->
      </van-row>
    </div>
    <van-empty v-show="!ysList.length" description="暂无关注的医生" />
  </div>
</template>
<script>
export default {
  name: 'doctor_lists',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle('关注的医生')
    t.getDoctorList()
  },
  data() {
    return {
      ysList: [],
      pathParams: {}
    }
  },
  methods: {
    getDoctorList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00033',
        url: t.serverPath4 || t.serverPath1,
        params: {
          OPENID: t.wx_userInfo.openid,
          FLAG: 'Query'
        },
        success: function(data) {
          console.log(data)
          if (data[0].CODE == '0') {
            t.ysList = data[0].CONTENTS
          }
        }
      })
    },
    toDoctorHome(data) {
      var t = this
      console.log(data)
      t.pathParams.YSID = data.YSID
      t.pathParams.GHYS = data.YSXM
      t.pathParams.ORG_ID = data.ORG_ID
      t.pathParams.ORG_NAME = data.ORG_NAME
      if (t.serverType != 'hz') {
        t.linkToPage('yygh_ys_home', t.pathParams)
      } else {
        t.pathParams.ORG_YQ = data.ORG_YQ
        t.pathParams.KSID = data.KSID
        t.linkToPage('yygh_ys_home1', t.pathParams)
      }
    },
    doClick() {
      var t = this
      t.$toast('正在建设中...')
    },
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.doctor_lists .serach-wrap {
  padding: 5px;
  font-size: 15px;
}

.doctor_lists .doctor-list .list-item {
  padding: 20px 15px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
  line-height: 50px;
  text-align: center;
}

.doctor_lists .doctor-list .item-icon {
  background: #f2f6fc;
  width: 55px;
  height: 55px;
  line-height: 55px;
  text-align: center;
  border-radius: 50%;
}

.doctor_lists .doctor-list .item-icon .iconfont {
  color: #d7dee8;
  font-size: 32px;
}

.doctor_lists .doctor-list .item-cont {
  line-height: 25px;
  font-size: 14px;
  text-align: left;
}

.doctor_lists .doctor-list .item-cont .name {
  font-weight: bold;
  font-size: 15px;
  margin-bottom: 5px;
}

.doctor_lists .doctor-list .item-cont .tips span {
  display: inline-block;
}

.doctor_lists>>>.van-empty {
  font-size: 15px;
  text-align: center;
}

.doctor_lists>>>.van-button--small {
  min-width: 50px;
  padding: 0;
  height: 24px;
  line-height: 24px;
  text-align: center;

}
</style>