<template>
  <div class="fwpj_detail g-wrap g-hp100 s-bgcfff">
    <div class="fwpj-top f-df">
      <img class="img" :src="$store.state.def_adminimg">
      <div class="f-pl15">
        <p class="name f-mt5 f-mb5">{{queryData.CHECKNAME}}</p>
        <p class="f-lh18">{{queryData.DOCTOR_NAME||'-'}}</p>
        <p class="f-lh18">{{queryData.ORG_NAME}}</p>
      </div>
    </div>
    <div class="score-item">
      <div class="f-mb15">医生专业能力评估</div>
      <div class="val f-df f-dfc">
        <van-rate v-model="value1" :size="25" color="#f8aa39" void-icon="star" void-color="#ebeef5" />
        <span>{{formatVal(value1)}}</span>
      </div>
    </div>
    <div class="score-item">
      <div class="f-mb15">医疗费用（根据您就医过程体验）</div>
      <div class="val f-df f-dfc">
        <van-rate v-model="value2" :size="25" color="#f8aa39" void-icon="star" void-color="#ebeef5" />
        <span>{{formatVal(value2)}}</span>
      </div>
    </div>
    <div class="score-item">
      <div class="f-mb15">医院服务（导医指引、护士、医院环境）</div>
      <div class="val f-df f-dfc">
        <van-rate v-model="value3" :size="25" color="#f8aa39" void-icon="star" void-color="#ebeef5" />
        <span>{{formatVal(value3)}}</span>
      </div>
    </div>
    <div class="g-wp100 f-pt20">
      <van-button class="item-btn f-bs3" round color="#00c792" @click="doComment">发布评价</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'fwpj_detail',
  components: {
  },
  created() {
    var t = this
    t.updateTitle('服务评价')
    // 判断是否注册 
  },
  computed: {},
  data() {
    return {
      value1: 0,
      value2: 0,
      value3: 0,
    }
  },
  methods: {
    formatVal(val) {
      var txt = ''
      if (val) {
        if (val == 1) { txt = '非常不满意' } else if (val == 2) { txt = '不满意' } else if (val == 3) { txt = '一般' } else if (val == 4) { txt = '满意' } else { txt = '非常满意' }
      }
      return txt
    },
    doComment() {
      var t = this
      if (t.value1 && t.value2 && t.value3) {
        t.ajax({
          serverId: 'BMFW00023',
          url: t.serverPath1,
          params: {
            OPENID: t.wx_userInfo.openid,
            YSID: t.queryData.YSID,
            YYLSH: t.queryData.YYID,
            YSNLPG: t.value1,
            YLFY: t.value2,
            YYFW: t.value3,
          },
          success: function(data) {
            if (data[0].CODE == '0') {
              t.linkToPage('fwpj_wc')
            } else {
              t.$toast('评价失败')
            }
          }
        })
      } else {
        t.$toast('您还未对服务评分')
      }
    },
  },
  mounted() {}
}
</script>
<style scoped>
.fwpj_detail .fwpj-top {
  padding: 15px;
  background: #e3f1ed;
  color: #606266;
  font-size: 13px; 
  align-items: center;
}
.fwpj_detail .fwpj-top .img{
  width: 58px;
  height: 58px;
}
.fwpj_detail .fwpj-top .name{
  font-weight: bold;
  color: #303133;
}
.fwpj_detail .score-item {
  padding: 30px 15px;
  font-size: 15px;
  color: #606266;
  text-align: center;
  border-bottom: 1px solid #ebeef5;
}
.fwpj_detail .score-item .val{
  align-items: center;
}
.fwpj_detail .score-item .val span{
  color: #f8aa39;
  margin-left: 12px;
  margin-top: 2px;
}

.fwpj_detail>>>.van-rate__item:not(:last-child) {
  padding-right: 15px;
}

.fwpj_detail>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  padding: 0 10px;
}
</style>