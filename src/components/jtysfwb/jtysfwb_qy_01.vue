<template>
  <div class="g-wrap g-hp100 ">
    <div v-if="fwbList.length">
      <div class="jtys_qy_fwb">
        <div class="fwb-list">
          <div class="list-item f-oa" style="height:89%" v-for="(c,i) in fwbList" :key="i">
            <div class="cont">
              <van-row>
                <van-col span="17">
                  <div class="f-fwb f-mb4 f-fs14 f-lh22 f-toe">{{c.FWB_NAME}}
                    <span style="color:#fa8c16" v-if="c.FWB_MONEY">(￥{{c.FWB_MONEY}})</span></div>
                  <div class="s-c909399 f-fs12 f-lh18">{{c.FWB_LB}}<span class="f-pl5 f-pr5">|</span>签约对象：{{c.NAME}}</div>
                </van-col>
                <van-col span="7" class="f-tar">
                  <div class="s-c909399 f-fs12 f-lh20" @click="linkToPage('jtysfwb_qy_02',c)">
                    <van-tag color="#00C792" round plain type="primary">签约信息</van-tag>
                  </div>
                </van-col>
              </van-row>
            </div>
          </div>
        </div>
        <div class="fixed_btn g-wp100" style="height:11%">
          <div class="aa ">
            <van-button style="width:100%" class="item-btn f-bs3 f-pt5" color="#00c792" @click="linkToPage('jtysfwb_index')">
              <div class="f-fl">
                <van-icon name="add-o" class="f-lh16 f-fs16 f-pr4" />
              </div>新增服务
            </van-button>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jtys_qy_fwb',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('我签约的服务包')
      t.pathParams = t.queryData
      t.currFwb = t.$store.state.store_jtys.currFwb
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(data) {
        console.log(66666, data.CONTENTS[0])
        // 判断是否签约家庭医生
        // t.getpd(data.CONTENTS[0])
        t.qyfwb()
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
      qyfwb() {
        var t = this
        t.ajax({
          serverId: 'WXJT0005',
          url: t.serverPath4,
          params: {
            openid: t.wx_userInfo.openid,
          },
          success: function(data) {
            console.log(789, data)
            t.fwbList = data[0].CONTENTS
          },
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
    padding: 1px 10px
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

  .fixed_btn .aa {
    position: fixed;
    left: 16px;
    right: 16px;
  }
</style>