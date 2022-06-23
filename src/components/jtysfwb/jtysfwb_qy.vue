<template>
  <div class="jtys_index g-wrap g-hp100">
    <!-- 未签约 -->
    <template v-if="!isSigned">
      <div class="jtysfwb_qy_01 g-wrap g-hp100">
        <div class="steps-wrap">
          <div class="steps-item f-df8 on">
            <div class="steps">1</div>
            <div class="lines"></div>
            <div class="tit">填写信息</div>
          </div>
          <div class="steps-item f-df8 on">
            <div class="steps">2</div>
            <div class="lines"></div>
            <div class="tit">确认信息</div>
          </div>
          <div class="steps-item f-df8">
            <div class="steps">3</div>
            <div class="lines"></div>
            <div class="tit">签约完成</div>
          </div>
        </div>
        <div class="s-bgcfff border f-oa" style="height:77%">
          <div class=" border s-bgcfff f-pt25 f-pl15">
            <div class="xinxi f-fs14 f-lh22 ">
              <div class="f-fs20 f-lh28 f-pb15">确认签约对象信息</div>
              <div class="f-pb8">签约对象：<span class="txt">{{queryData.XM}}</span></div>
              <div class="f-pb8">身份证号：<span class="txt">{{queryData.ZJHM}}</span></div>
              <div class="f-pb8">手机号码：<span class="txt">{{queryData.PHONE}}</span></div>
              <div class="f-pb8">与本人关系：<span class="txt">{{queryData.URGENT_CONTACT_RELATE}}</span></div>
              <van-row class="f-pb8">
                <van-col span="3.5"> 服务包：</van-col>
                <van-col span="20">
                  <span class="txt">
                    {{queryData.FWB_NAME}}<span style="color:#fa8c16" v-if="queryData.FWB_ZIFU>=0">(￥{{queryData.FWB_ZIFU}})</span>
                    <van-tag color="#fff" text-color="#00c792" size="large" @click="linkToPage('jtysfwb_fwnr',{xq:queryData,index:1})">查看</van-tag>
                  </span>
                </van-col>
              </van-row>
              <div class="f-pb8">所在地址：<span class="txt">{{queryData.AREA}}</span></div>
              <van-row class="f-pb8">
                <van-col span="4.5">详细地址：</van-col>
                <van-col span="19">
                  <span class="txt">
                    {{queryData.HKDZ}}
                  </span></van-col>
              </van-row>
              <div class="f-pb8">医疗机构：<span class="txt">{{queryData.ORG_NAME}}</span></div>
              <div class="f-pb8">机构地址：<span class="txt">{{queryData.ORG_ADDR}}</span></div>
              <div class="f-pb8">负责人姓名：<span class="txt">{{queryData.ORG_LEADER}}</span></div>
              <div class="f-pb8">负责人电话：<span class="txt">{{queryData.ORG_PHONE}}</span></div>
            </div>
          </div>
        </div>
        <div class="fixed_btn g-wp100 f-pb30 s-bgcfff" style="height:13%">
          <van-button class="item-btn f-bs3" color="#00c792" @click="showPicker=true">下一步</van-button>
          <van-popup v-model="showPicker" round position="bottom" closeable>
            <div class="f-pt15 f-ml20 f-mr20 ">
              <div class="f-fs16 f-lh26 f-tac s-c000">支付详情</div>
              <div class="f-fs16 f-lh24 f-tac f-pt30" style="color:#969799">{{queryData.FWB_NAME}}</div>
              <div class="f-mb30 f-tac f-pt12 f-fwb" style="color:#323233;font-size:44px;line-height:56px">{{queryData.FWB_ZIFU}}</div>
              <van-divider />
              <div class="f-mt20 f-fs16 f-lh24">
                <van-row class="s-c000">
                  <van-col span="15">支付方式</van-col>
                  <van-col span="2" class="f-tac">
                    <van-icon name="wechat-pay" color="#04C54B" size="22px" class="f-lh22 f-tac" />
                  </van-col>
                  <van-col span="5" @click="tx">
                    微信支付</van-col>
                  <van-col span="2">
                    <span class="f-lh28">
                      <van-icon name="arrow" /></span>
                  </van-col>
                </van-row>
              </div>
              <van-row class="s-c000" @click="tx">
                <van-col span="22" class="f-tar">
                  <div class="f-fs12 f-lh18" style="color:#969799;padding-bottom:70%">点击切换更多支付方式</div>
                </van-col>
                <van-col span="2">
                </van-col>
              </van-row>
            </div>
            <div class="f-tac f-pt15 f-pb30 ">
              <van-button class="item-btn f-bs3" color="#00c792" @click="toQy">提交订单</van-button>
            </div>
          </van-popup>
        </div>
      </div>
    </template>
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
      console.log(t.queryData)
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(data) {
        t.userinfo = data.CONTENTS[0]
        console.log(data.CONTENTS[0])
        // 判断是否签约家庭医生
      })
    },
    data() {
      return {
        userinfo: '',
        showPicker: false,
        pageTitle: '签约服务包',
        isSigned: false,
      }
    },
    methods: {
      //获得支付链接
      toQy() {
        var t = this
        var params = {
          OPENID: t.wx_userInfo.openid,
          WX_SJHM: t.userinfo.SJHM,
          WX_NAME: t.userinfo.NAME,
          WX_CARDNUM: t.userinfo.CARD_NUMBER,
          QY_NAME: t.queryData.XM,
          QY_SJHM: t.queryData.PHONE,
          QY_CARDNUM: t.queryData.ZJHM,
          RELATED: t.queryData.URGENT_CONTACT_RELATE,
          FWB_ID: t.queryData.ID,
          FWB_NAME: t.queryData.FWB_NAME,
          FWB_JG: t.queryData.ORG_NAME,
          FWB_JG_ADDR: t.queryData.ORG_ADDR,
          FWB_ORG: t.queryData.ORG_ID,
          FWB_XH: t.queryData.XH,
          FWB_DOCTOR: t.queryData.ORG_LEADER,
          FWB_SJHM: t.queryData.ORG_PHONE,
          FWB_MONEY: t.queryData.FWB_ZIFU,
          USER_AREA: t.queryData.AREA,
          USER_ADDRESS: t.queryData.HKDZ,
          FWB_LB: t.queryData.FWB_LB,
          FWB_YINGSHOU: t.queryData.FWB_YINGSHOU,
        }
        console.log(66666666456465, params)
        t.ajax({
          serverId: 'WXJT0009',
          url: t.serverPath4,
          params: params,
          success: function(data) {
            console.log(666666666666, data)
            if (data[0].CODE == 0) {
              if (t.queryData.FWB_ZIFU == 0) {
                t.doOuder(data[0].TRADE)
              } else(
                window.location.href = data[0].QRCODE
                // console.log(data[0].QRCODE)
              )
            } else if (data[0].CODE == -1) {
              t.$dialog.alert({
                title: '提示',
                message: data[0].MSG
              })
            } else {
              t.$dialog.alert({
                title: '提示',
                message: '查询失败'
              })
            }
          }
        })
      },
      doOuder(val) {
        var t = this
        var params = {
          OPENID: t.wx_userInfo.openid,
          WX_SJHM: t.userinfo.SJHM,
          WX_NAME: t.userinfo.NAME,
          WX_CARDNUM: t.userinfo.CARD_NUMBER,
          QY_NAME: t.queryData.XM,
          QY_SJHM: t.queryData.PHONE,
          QY_CARDNUM: t.queryData.ZJHM,
          RELATED: t.queryData.URGENT_CONTACT_RELATE,
          FWB_ID: t.queryData.ID,
          FWB_NAME: t.queryData.FWB_NAME,
          FWB_JG: t.queryData.ORG_NAME,
          FWB_JG_ADDR: t.queryData.ORG_ADDR,
          FWB_ORG: t.queryData.ORG_ID,
          FWB_XH: t.queryData.XH,
          FWB_DOCTOR: t.queryData.ORG_LEADER,
          FWB_SJHM: t.queryData.ORG_PHONE,
          FWB_YINGSHOU: t.queryData.FWB_YINGSHOU,
          FWB_MONEY: t.queryData.FWB_ZIFU,
          USER_AREA: t.queryData.AREA,
          USER_ADDRESS: t.queryData.HKDZ,
          FWB_LB: t.queryData.FWB_LB,
          TRADE: val
        }
        console.log(789798798, params)
        t.ajax({
          serverId: 'WXJT0004',
          url: t.serverPath4,
          params: params,
          success: function(data) {
            console.log(789, data)
            if (data[0].CODE == 0) {
              t.linkToPage('jtysfwb_qy_wc')
            } else if (data[0].CODE == -1) {
              t.$dialog.alert({
                title: '提示',
                message: "该订单购买失败，请稍后再试"
              })
            }
          }
        })
      },
      tx() {
        this.$dialog.alert({
          title: '提示',
          message: '暂不支持其他支付方式'
        })
        return false
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtysfwb_qy_01 .border {
    border-top-right-radius: 12px;
    border-top-left-radius: 12px;
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
    height: 11%
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

  .fixed_btn {
    position: fixed;
    bottom: 0px;
  }
</style>