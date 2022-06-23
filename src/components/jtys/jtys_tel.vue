<template>
  <div class="jtys_tel g-wrap g-hp100">
    <div v-if="queryData.index==1">
      <div class="list-item  f-mt15 f-ml15 f-mr15 s-bgcfff" v-for="(c,i) in queryData.list" :key="i">
        <a :href="'tel:'+c.ORG_PHONE">
          <van-row>
            <van-col span="22">
              <div class="f-fwb f-fs16 f-pb10">{{c.ORG_NAME}}</div>
              <div><span class="s-c909399 f-pr8">{{c.ORG_LEADER}}</span><span class="s-c909399 ">{{c.ORG_PHONE}}</span></div>
              <!-- <div class="s-c909399">{{c.ORG_ADDR}}</div> -->
            </van-col>
            <van-col span="2" class="f-tac">
              <div style="color:#C8C9CC;line-height:43px"> <i class="iconfont icon-arrow-right f-fs11"></i></div>
            </van-col>
          </van-row>
        </a>
      </div>
    </div>
    <div v-else class="list-item  f-mt15 f-ml15 f-mr15 s-bgcfff">
      <a :href="'tel:'+queryData.list.YSTEL">
        <van-row>
          <van-col span="22">
            <div class="f-fwb f-fs16 f-pb10">{{queryData.list.SIGN_ORG}}</div>
            <div><span class="s-c909399 f-pr8">{{queryData.list.SIGN_DOCTOR}}</span><span class="s-c909399 ">{{queryData.list.YSTEL}}</span></div>
            <!-- <div class="s-c909399">{{c.ORG_ADDR}}</div> -->
          </van-col>
          <van-col span="2" class="f-tac">
            <div style="color:#C8C9CC;line-height:43px"> <i class="iconfont icon-arrow-right f-fs11"></i></div>
          </van-col>
        </van-row>
      </a>
    </div>
  </div>

</template>
<script>
  export default {
    name: 'jtys_tel',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('电话咨询')
      // 判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(data) {
        console.log(data)
        console.log(t.queryData)
      })
    },
    data() {
      return {}
    },
    methods: {},
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtys_tel {
    font-size: 14px;
    color: #00c792;
  }

  .jtys_tel a {

    color: #00c792;
  }

  .jtys_tel .list-item {
    padding: 15px;
  }
</style>