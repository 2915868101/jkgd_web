<template>
  <div class="g-wrap g-hp100 ">
    <div class="jtys_qy_fwb" v-if="ysList.length">
      <div class="fwb-list ">
        <div class="list-item" v-for="(c,i) in ysList" :key="i">
          <div class="cont" @click="linkToPage('jtysfwb_fwnr',{xq:c,index:2,cc:1})">
            <van-row>
              <van-col span="19">
                <div class="f-fwb f-mb4 f-fs14 f-lh22 f-toe">{{c.FWB_NAME}}</div>
                <div class=" cc s-c909399 f-fs12 f-lh20">
                  <van-tag color="#00C792" plain type="primary">{{c.FWB_LB}}</van-tag>
                </div>
                <div class="s-c909399 f-fs14 f-lh22 f-pt6">购买人：{{c.WX_NAME}}<span class="f-pl12 f-pr12">|</span>签约对象：{{c.NAME}}</div>
              </van-col>
              <van-col span="4" class="f-tac">
                <div class="f-fwb  f-fs16 f-lh24" style="color:#fa8c16">￥{{c.FWB_MONEY}}</div>
                <div class=" f-fs12 f-lh18 f-tdl">￥{{c.FWB_YINGSHOU}}</div>
              </van-col>
              <van-col span="1">
                <div style="color:#c8c9cc;line-height:44px"> <i class="iconfont icon-arrow-right f-fs11"></i></div>
              </van-col>
            </van-row>
          </div>
        </div>
      </div>
    </div>
    <van-empty v-show="!ysList.length" description="暂无签约我的服务包" />
  </div>
</template>
<script>
  export default {
    name: 'jtys_qy_fwb',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('签约我的服务包')
      localStorage.setItem('index', '1')
      t.pathParams = t.queryData
      t.currFwb = t.$store.state.store_jtys.currFwb
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(data) {
        console.log(66666, data.CONTENTS[0])
        // 判断是否签约家庭医生
        t.qycx(data.CONTENTS[0])
      })


    },
    data() {
      return {
        userinfo: '',
        activeIndex: 0,
        pathParams: '',
        fwbList: [],
        ysList: [],
        currFwb: ''
      }
    },
    methods: {
      qycx(val) {
        var t = this
        t.ajax({
          serverId: 'WXJT0008',
          url: t.serverPath4,
          params: {
            OPENID: t.wx_userInfo.openid,
            NAME: val.NAME,
            SJHM: val.SJHM
          },
          success: function(data) {
            console.log(7899, data)
            t.ysList = data[0].CONTENTS
          }
        })
      },
      toQY02(data, index) {
        var t = this
        t.currFwb = index
        t.$store.state.store_jtys.currFwb = index
        t.pathParams.data.FWB_ZIFU = data.FWB_ZIFU
        t.pathParams.data.FWB_NAME = data.FWB_NAME
        t.pathParams.TYPE = data.FWB_NAME + '(￥' + data.FWB_ZIFU + ')'
        t.linkToPage('jtysfwb_index', t.pathParams)
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtys_qy_fwb>>>.van-tag {
    font-size: 14px;
    line-height: 22px;

  }

  .jtys_qy_fwb .cc>>>.van-tag {
    font-size: 12px;
    line-height: 18px;
    padding: 2px 15px
  }

  .jtys_qy_fwb .popup {
    width: 90%;
    height: 50%;
    top: 45%;
  }

  .jtys_qy_fwb>>>.van-tabs--line .van-tabs__wrap {
    height: 60px;
    padding-bottom: 5px;
    /* border-bottom: 10px solid #f4f9f9; */
  }

  .jtys_qy_fwb>>>.van-tabs__nav--line {
    padding-bottom: 0.5rem;
  }

  .jtys_qy_fwb>>>.van-tab__text {
    font-weight: 500px;
    font-weight: bold;
    font-size: 14px;
    line-height: 22px;
    color: #323233;
  }

  .jtys_qy_fwb>>>[class*=van-hairline]::after {
    border: 0;
  }

  .jtys_qy_fwb>>>.van-tabs__line {
    background: #00c792;
  }

  .jtys_qy_fwb .fwb-list {
    margin: 17px;
  }


  .jtys_qy_fwb .fwb-list .list-item .icons {
    width: 15px;
    height: 15px;
    line-height: 15px;
    display: inline-block;
    border-radius: 50%;
    text-align: center;
    border: 1px solid #00c792;
  }

  .jtys_qy_fwb .fwb-list .list-item {
    background: #fff;
    border-radius: 4px;
    padding: 12px;
    font-size: 14px;
    margin: 8px 0;
  }

  .fixed_btn {
    position: fixed;
    bottom: 0px;
    padding: 0 17px;
  }
</style>