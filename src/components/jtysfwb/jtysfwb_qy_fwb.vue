<template>
  <div class="jtys_qy_fwb g-wrap g-hp100 ">
    <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
      <van-tab title="全部"></van-tab>
      <van-tab title="基础包"></van-tab>
      <van-tab title="初级包"></van-tab>
      <van-tab title="中级包"></van-tab>
    </van-tabs>
    <div v-if="fwbList.length">
      <div class="fwb-list ">
        <div class="list-item" v-for="(c,i) in fwbList" :key="i" @click.stop="xzfwb(c)" :style="{background:((key == 0 && c.FWB_LB.indexOf('基础包') == 0)?'#cccccc':'#fff')}">
          <div class="cont">
            <van-row>
              <van-col span="19">
                <div class="f-fwb f-mb4 f-fs14 f-lh22 f-toe" :class="(key == 0 && c.FWB_LB.indexOf('基础包') == 0)?'f-tdl':''">{{c.FWB_NAME}}</div>
                <div class="s-c909399 f-fs12 f-lh18">{{c.FWB_LB}}</div>
              </van-col>
              <van-col span="4">
                <div class="f-fwb f-mb4 f-fs16 f-lh24" style="color:#fa8c16">￥{{c.FWB_ZIFU}}</div>
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
    <van-popup v-model="show" round class="popup">
      <div class="f-pt15 f-pb20 f-pl20 f-pr20">
        <div class="s-c00c792 f-tac"><span class=" f-fs26 f-pr5 f-lh36 icon wsdfont wsd-tishitixingyiwenbeizhushuomingi"></span><span class="f-fs24 f-lh36">签约须知</span></div>
        <div class="f-fs14 f-lh22 f-pt15 s-c000">
          <p>1、初、中级服务包除表中项目外，同时享有基础服务包服务内容；</p>
          <p class="f-pt10">2、残疾人签约后村医须填写《残疾人入户信息登记表》、《残疾人精准康复服务手册》。《残疾人入户信息登记表》由村卫生室保留，《残疾人精准康复服务手册》由残疾人或其监护人保留，村医每服务一次，需在手册上填写记录一次，每月统计上报《残疾人家庭医生签约转介汇总表》。
          </p>
        </div>
      </div>
      <div class="f-tac f-pt15 f-pb30">
        <van-button style="width:90%" color="#00c792" @click="show = false">我知道了</van-button>
      </div>
    </van-popup>
  </div>
</template>
<script>
  export default {
    name: 'jtys_qy_fwb',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('选择服务包')
      t.key = localStorage.getItem("key")
      if (localStorage.getItem('index') == 1) {
        t.show = true
        localStorage.setItem('index', '0')
      }
      t.pathParams = t.queryData
      t.currFwb = t.$store.state.store_jtys.currFwb
      t.getfwb(0)
    },
    data() {
      return {
        activeIndex: 0,
        show: false,
        pathParams: '',
        fwbList: [],
        currFwb: '',
        key: '',
      }
    },
    methods: {
      onClickTabs(index) {
        var t = this
        t.activeIndex = index
        t.getfwb(t.activeIndex)
      },
      getfwb(val) {
        var t = this
        t.ajax({
          serverId: 'WXJT0001',
          url: t.serverPath4,
          params: {
            LB: val
          },
          success: function(data) {
            console.log(789, data)
            t.fwbList = data[0].CONTENTS

          }
        })
      },
      xzfwb(val) {
        var t = this
        console.log(val.FWB_LB.indexOf('基础包'))
        console.log(t.key)
        if (t.key == 0 && val.FWB_LB.indexOf('基础包') == 0) {
          return
        }
        if (val.ID == 5 && t.pathParams.SEX == '男') {
          t.$toast('该服务包只限女生购买')
        } else
        if (val.ID == 6 && t.pathParams.AGE >= 1) {
          t.$toast('该服务包只限0-12月婴幼儿购买')
        } else
        if (val.ID == 7 && (t.pathParams.AGE < 1 || t.pathParams.AGE > 3)) {
          t.$toast('该服务包只限1-3周岁儿童购买')
        } else
        if (val.ID == 8 && (t.pathParams.AGE < 4 || t.pathParams.AGE > 6)) {
          t.$toast('该服务包只限4-6周岁儿童购买')
        } else
        if (val.ID == 9 && t.pathParams.AGE < 35) {
          t.$toast('该服务包只限35岁周岁以上人群购买')
        } else
        if ((val.ID == 14 || val.ID == 16 || val.ID == 18) && t.pathParams.AGE >= 65) {
          t.$toast('该服务包只限65周岁以下人群购买')
        } else
        if ((val.ID == 4 || val.ID == 15 || val.ID == 17 || val.ID == 19) && t.pathParams.AGE < 65) {
          t.$toast('该服务包只限65周岁以上人群购买')
        } else {
          t.linkToPage('jtysfwb_fwnr', {
            xq: val,
            pathParams: t.pathParams,
          })
        }
      }

    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtys_qy_fwb .popup {
    width: 90%;
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
    border-radius: 4px;
    padding: 8px 12px;
    font-size: 14px;
    margin: 1px;

  }
</style>