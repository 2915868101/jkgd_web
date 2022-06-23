<template>
  <div class="jtysfwb_qy_01 g-wrap g-hp100 s-bgcfff">
    <!-- 未签约 -->
    <div class="register-wrap ">
      <div class=" border s-bgcfff f-pt15 f-pl15">
        <div class="xinxi f-fs14 f-lh22 ">
          <div class="f-pb8">签约对象：<span class="txt">{{queryData.NAME}}</span></div>
          <div class="f-pb8">身份证号：<span class="txt">{{queryData.SFZH}}</span></div>
          <div class="f-pb8">手机号码：<span class="txt">{{queryData.SJHM}}</span></div>
          <div class="f-pb8">与本人关系：<span class="txt">{{queryData.RELATED}}</span></div>
          <van-row class="f-pb8">
            <van-col span="3.5"> 服务包：</van-col>
            <van-col span="20">
              <span class="txt">
                {{queryData.FWB_NAME}}
                <span style="color:#fa8c16" v-if="queryData.FWB_MONEY">(￥{{queryData.FWB_MONEY}})</span>
                <van-tag color="#fff" text-color="#00c792" size="large" @click="linkToPage('jtysfwb_fwnr',{xq:queryData,index:2})">查看</van-tag>
              </span> </van-col>
          </van-row>
          <div class="f-pb8">服务开始时间：<span class="txt">
              {{new Date(parseInt(queryData.ADD_TIME)).toLocaleString().replace(/:\d{1,2}$/,' ')}}</span></div>
          <div class="f-pb8">服务时限：<span class="txt">{{'一年'}}</span></div>
          <div class="f-pb8">所在地址：<span class="txt">{{queryData.USER_AREA}}</span></div>
          <van-row class="f-pb8">
            <van-col span="4.5">详细地址：</van-col>
            <van-col span="19">
              <span class="txt">
                {{queryData.USER_ADDRESS}}
              </span></van-col>
          </van-row>
          <div class="f-pb8">医疗机构：<span class="txt">{{queryData.FWB_JG}}</span></div>
          <div class="f-pb8">机构地址：<span class="txt">{{queryData.FWB_JG_ADDR}}</span></div>
          <div class="f-pb8">负责人姓名：<span class="txt">{{queryData.FWB_DOCTOR}}</span></div>
          <div class="f-pb8 f-df">负责人电话：<div class="txt f-df">
              <div class="f-lh28 ">
                <van-icon name="phone" color="#00C792" size="17" />
              </div>
              <a :href="'tel:'+queryData.FWB_SJHM" class="s-c00c792">{{queryData.FWB_SJHM}}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import '@/assets/js/util/vue_amap.js'
  export default {
    name: 'jtys_index',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle(t.pageTitle)
      // 判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(data) {
        console.log(data.CONTENTS[0])
        // 判断是否签约家庭医生
      })
      console.log(78979, t.queryData)
    },
    data() {
      return {
        showPicker: false,
        pageTitle: '签约信息',
        isSigned: false,
      }
    },
    methods: {
      toQy() {
        var t = this
        t.linkToPage('jtysfwb_qy_wc')
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtysfwb_qy_01 .border {
    border-top-right-radius: 12px;
    border-top-left-radius: 12px;
    padding-bottom: 90px;
  }

  .jtysfwb_qy_01 .xinxi {
    color: #303133;
  }

  .jtysfwb_qy_01 .txt {
    color: #969799;
  }

  /*签约前样式*/
  .jtysfwb_qy_01 .position-wrap {
    position: fixed;
    top: 0;
    bottom: 0;
    width: 100%;
    z-index: 10;
  }

  .jtysfwb_qy_01>>>.van-field__control {
    color: #909399
  }

  .jtysfwb_qy_01 .input-item input {
    border: 0;
    outline: 0;
    width: 50px;
    font-size: 15PX;
    text-align: right;
    padding-right: 5px;
  }

  .jtysfwb_qy_01 .g-wp100>>>.van-button {
    width: 90%;
    margin: 20px 5% 0;
    height: 50px;
    padding: 0 10px;
  }

  .jtysfwb_qy_01 .cell-item,
  .jtysfwb_qy_01>>>.van-cell {
    padding: 15px 20px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 15PX;
  }

  .jtysfwb_qy_01>>>.van-cell__title {
    color: #606266
  }

  .jtysfwb_qy_01>>>.van-cell__value {
    color: #303133;
  }

  .jtysfwb_qy_01>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .jtysfwb_qy_01>>>.van-field__button .van-button--small {
    height: 20px;
    min-width: 20px;
  }

  .jtysfwb_qy_01 .tips-wrap {
    color: #c0c4cc;
    font-size: 14px;
    padding: 15px;
  }

  .jtysfwb_qy_01 .steps-wrap {
    background: #f4f9f9;
    padding: 15px;
    font-size: 14px;
    display: flex;
    color: #323233;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item .steps {
    position: relative;
    z-index: 2;
    background: #fff;
    border-radius: 50%;
    width: 27px;
    height: 27px;
    line-height: 27px;
    font-size: 14px;
    margin: 0 auto 10px;
    border: 1PX solid #dde0e7;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item .lines {
    border-bottom: 1PX solid #dde0e7;
    position: relative;
    top: -22px;
    left: 50%;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item.on .steps,
  .jtysfwb_qy_01 .steps-wrap .lines.on,
  .jtysfwb_qy_01 .steps-wrap .tit.on {
    border-color: #00c792;
    background: #00c792;
    color: #fff;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item:nth-last-child(1) .lines {
    border: 0;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item {
    text-align: center;

  }


  /*签约后样式*/
  .jtys_index .jtys-index .menu-wrap {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: #929395;
  }

  .jtys_index .jtys-index .menu-wrap .w-item {
    width: 48%;
    padding: 15px 0;
    border-radius: 5px;
    background: #fbf7f3;
    justify-content: center;
  }

  .jtys_index .jtys-index .iconfont {
    font-size: 28px;
    color: #fff;
    background: #ff8648;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
  }

  .jtys_index .jtys-index .w-item .item-cont .tit {
    font-size: 15px;
    color: #303133;
    font-weight: bold;
    margin: 5px 0 10px;
  }

  .jtys_index>>>.van-cell {
    padding: 13px 16px;
    font-size: 14px;
  }

  .jtys_index>>>.van-cell__title {
    color: #303133;
  }

  .jtys_index>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .jtys_index .m-areaList-box /deep/ .van-picker__columns .van-picker-column:nth-child(3) {
    display: none;
  }
</style>