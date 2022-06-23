<template>
  <div id="yygh_qr" class="yygh_qr s-bgcfff">
    <div class="m-top f-df f-pt15 f-pb15 f-pl15 f-pr15" v-if="queryData.MZZT=='专家门诊'">
      <div class="c_01 f-mr10">
        <span class="u-tag-zhuanjia">
          <img src="../../assets/img/yyghgd/zjmz_hd_img1.jpg" v-if="queryData.SEX=='男'" />
          <img src="../../assets/img/yyghgd/zjmz_hd_img2.jpg" v-if="queryData.SEX=='女'" />
        </span>
      </div>
      <div class="f-df1 f-dfv">
        <div class="f-fs16 s-c373737">
          {{queryData.YSXM}}
          <span class="f-ml5 f-fs14" v-if="queryData.ZZJB">{{queryData.ZZJB}}</span>
        </div>
      </div>
    </div>
    <div class="m-top f-df f-pt15 f-pb15 f-pl15 f-pr15" v-if="queryData.MZZT=='普通门诊'">
      <div class="c_01 f-mr10">
        <img src="../../assets/img/yyghgd/ptmz_hd_img.jpg" />
      </div>
      <div class="f-df1 f-dfv">
        <div class="f-fs16 s-c373737">{{queryData.MZZT}}</div>
      </div>
    </div>
    <div class="m-cell-box">
      <div class="m-cell">
        <span class="s-c8b8b8b">就诊科室：</span>
        <span class="s-c646464 f-fwb">{{queryData.PBKS}}</span>
      </div>
      <div class="m-cell">
        <span class="s-c8b8b8b">预约时间：</span>
        <span class="s-c00c792">
          {{queryData.YYRQ||queryData.checkedDate.date}} {{queryData.checkedIndex?queryData.checkedDate.week:'今天'}}
          <span v-if="queryData.checkedTime.TIMEZONE">{{queryData.checkedTime.YYSD||queryData.checkedTime.RANGE}}</span>
          {{queryData.checkedTime.TIMEZONE||queryData.checkedTime.RANGE}}
          <span class="tag_01" v-if="queryData.checkedIndex">{{queryData.checkedIndex}}天后</span>
        </span>
      </div>
      <div class="m-cell">
        <span class="s-c8b8b8b">挂号费：</span>
        <span class="s-cff0e0e">{{Number(queryData.GHF||queryData.checkedTime.GHF).toFixed(2)}} 元</span>
      </div>
      <div class="m-cell f-df">
        <div class="f-df1">
          <span class="s-c8b8b8b">就诊人：</span>
          <span class="s-c646464 f-fwb">{{jzr_info.XM}}</span>
        </div>
        <div class="s-c00c792 m-check-jzr f-dfv u-arrow" @click="showPopup">更换就诊人</div>
      </div>
      <div class="m-cell">
        <span class="s-c8b8b8b">身份证号：</span>
        <span class="s-c646464">{{jzr_info.CNUM}}</span>
      </div>
      <div class="m-cell">
        <span class="s-c8b8b8b">手机号：</span>
        <span class="s-c646464">{{jzr_info.SJHM}}</span>
      </div>
      <div class="m-cell f-fs14 f-lh20">
        <div class="s-c8b8b8b">*预约提示：</div>
        <div class="s-cffa54d f-mt5">挂号成功后，如需取消请在“我的预约”-“未完成预约”或“我的挂号”中执行取消，已签到用户无法网上取消请至医院窗口办理取消。</div>
      </div>
      <div class="u-sub-btn" @click="doValidate">确认支付 &yen;{{queryData.GHF||queryData.checkedTime.GHF}} 元</div>
    </div>
    <div>
      <van-popup v-model="showDialog" class="yygh_qr-dialog f-lh22">
        <div class="f-tac f-fs16 s-ce6a23c c_01">挂号须知</div>
        <div class="f-tal f-pl10 f-pr10">
          <div class="f-mt10">1.预约挂号采用“实名制”，患者须提供真实姓名及身份证、手机号等信息。就诊当天需出示的身份证和就诊卡信息必须与预约时提供的一致</div>
          <div class="f-mt15 s-cff0e0e">2.首次预约未办理过医院就诊卡的用户不能网上排队就诊，须提前至窗口出示预约信息办理就诊卡。</div>
          <div class="f-mt15">3.如遇所约医生专家因故不能出诊，安排同资质医生专家替诊。</div>
          <div class="f-mt15">4.如您不能赴约，可在线取消。如已加入排队取消挂号需去人工窗口办理。</div>
          <div class="f-mt15">5.网上预约3次爽约者将列入违约名单，此后3个月内不在享受预约服务。</div>
        </div>
        <div class="f-mt15 f-fs16 c_02 f-df">
          <div class="f-df1 f-tac s-c373737" @click="showDialog=false">取消</div>
          <span class="c_02_1"></span>
          <div class="f-df1 f-tac s-c00c792" @click="toPay">同意</div>
        </div>
      </van-popup>
    </div>
    <van-popup v-model="isShowPopup" position="bottom" :style="{height : '30%'}">
      <div class="pop-head">
        <span>请选择就诊人</span>
        <van-icon name="cross" @click.stop="doClose"></van-icon>
      </div>
      <div class="pop-cont">
        <div class="select-list">
          <div class="select-item" v-for="item in patientList" :key="item.CNUM" @click="doSelect(item)">
            <div class="item-icon" v-if="!item.TX">
              <i class="iconfont icon-user"></i>
            </div>
            <img class="item-icon" v-else :src="item.TX" />
            <div class="item-name">{{item.XM}}</div>
          </div>
          <div class="select-item" @click="doAddCard">
            <div class="item-icon">
              <i class="iconfont icon-add"></i>
            </div>
            <div class="item-name">添加就诊人</div>
          </div>
        </div>
      </div>
    </van-popup>
  </div>
</template>
<script>
  import querystring from "qs";
  export default {
    name: "yygh_qr",
    directives: {},
    components: {},
    created() {
      const t = this;
      t.isRegister({
        openid: t.wx_userInfo.openid
      }, function(data) {
        t.setJzrInfo(data.CONTENTS[0]);
        t.getPidGD()
      });
      t.searchForm.OPENID = t.wx_userInfo.openid
      t.getPatientList()
    },
    computed: {},
    data() {
      return {
        jzr_info: "",
        flag: true,
        showDialog: false,

        isShowPopup: false,
        patientList: [],
        searchForm: {
          FLAG: 'Query',
          OPENID: '',
          XM: '',
          CARDTYPE: '',
          CNUM: '',
          SJHM: ''
        }
      };
    },
    methods: {
      doAddCard() {
        var t = this
        if (t.serverHealthCard) {
          // encodeURI(encodeURI(redirect_uri))
          sessionStorage.setItem("prevUrl", t.$route.fullPath)
          var redirect_uri = t.wx_health_card + "/register_card?isRegister=true&openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl + '&prevUrl=' + t.$route.fullPath
          window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(encodeURI(encodeURI(redirect_uri)))
          console.log('https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent())
          console.log(t.$route.fullPath)
        } else {
          t.linkToPage('register_card', {
            isRegister: true
          })
        }
      },
      // doAddCard() {
      //   var t = this
      //   if (t.serverHealthCard) {
      //     sessionStorage.setItem("prevUrl", window.location.href)
      //     var redirect_uri = t.wx_health_card + "/register_card?isRegister=true"
      //     window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(redirect_uri)
      //   } else {
      //     t.linkToPage('register_card', {
      //       isRegister: true
      //     })
      //   }
      // },
      getPidGD() {
        var t = this
        t.ajax({
          serverId: 'WXBM00005',
          url: t.serverPath1,
          params: {
            OPENID: t.wx_userInfo.openid,
            CARDNUM: t.jzr_info.CNUM,
            SJHM: t.jzr_info.SJHM,
            ORG_ID: 'guangde001'
          },
          success: function(data) {
            console.log('pid')
            console.log(data)
            t.jzr_info.ID = data[0].CARDNO
          }
        })
      },
      getPatientList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00014',
          url: t.serverPath1,
          params: t.searchForm,
          success: function(data) {
            t.patientList = data[0].CONTENTS || []
            console.log(data)
            console.log(t.searchForm)
            for (var row of t.patientList) {
              console.log(row)
              if (row.BZ == '1') {
                t.jzr_info.XM = row.XM
                t.jzr_info.CNUM = row.CNUM
                // 获取广德pid
                t.getPidGD()
              }
            }
          }
        })
      },
      showPopup() {
        var t = this
        t.isShowPopup = true
      },
      doClose() {
        this.isShowPopup = false
      },
      doSelect(item) {
        var t = this
        t.isShowPopup = false
        console.log(item)
        t.jzr_info.XM = item.XM
        t.jzr_info.CNUM = item.CNUM
        // 获取广德pid
        t.getPidGD()
      },
      setJzrInfo(data) {
        const t = this;
        console.log(data)
        t.jzr_info = {};
        t.jzr_info.XM = data.NAME;
        t.jzr_info.CARDTYPE = data.JZCARDTYPE;
        t.jzr_info.CNUM = data.CARD_NUMBER;
        t.jzr_info.SJHM = data.SJHM;
        console.log(t.jzr_info)
        // 获取广德pid
        t.getPidGD()
      },
      doValidate() {
        const t = this;
        let xb = t.IdCard(t.jzr_info.CNUM, 2),
          nl = t.IdCard(t.jzr_info.CNUM, 3),
          ksmc = t.queryData.PBKS,
          hours = t.queryData.checkedTime.TIMEZONE.split("-")[0].split(":")[0];
        if (new Date().getHours() == hours) {
          t.$dialog.alert({
            title: "提示",
            message: "当前时间段内请去往窗口预约，或选择其他时间段预约",
          });
          return false;
        } else if (nl >= 18 && ksmc.indexOf("儿科") != -1) {
          t.$dialog.alert({
            title: "提示",
            message: "年龄超过18岁不能挂" + ksmc + "，请重新选择就诊人或科室",
          });
          return false;
        } else if (
          xb == "男" &&
          (ksmc.indexOf("妇科") != -1 || ksmc.indexOf("产科") != -1)
        ) {
          t.$dialog.alert({
            title: "提示",
            message: "男性不能挂" + ksmc + "，请重新选择就诊人或科室",
          });
          return false;
        } else if (!t.jzr_info.ID) {
          t.$dialog.alert({
            title: "提示",
            message: "业务繁忙，请稍后重试！",
          });
          return false;
        } else {
          t.showDialog = true;
        }
      },
      toPay() {
        const t = this;
        console.log(t.queryData.YSXM || "");
        if (t.flag) {
          t.flag = false;
          t.showDialog = false;
          let params = {
            openid: t.wx_userInfo.openid,
            money: t.queryData.GHF || t.queryData.checkedTime.GHF,
            pid: t.jzr_info.ID,
            ysid: t.queryData.YSID || "",
            zsid: t.queryData.checkedTime.ZHENSHIID || "",
            pbksid: t.queryData.PBKSID,
            pbks: t.queryData.PBKS,
            ysxm: t.queryData.YSXM || "",
            zsmc: t.queryData.checkedTime.ZHENSHINAME || "",
            hzxm: t.jzr_info.XM,
            yyrq: t.queryData.checkedDate.date,
            shiduan: t.queryData.checkedTime.SHIDUAN,
            phone: t.jzr_info.SJHM,
            pbzxid: t.queryData.checkedTime.PBZXID,
            gqsj: t.queryData.checkedTime.GQSJ,
            haolei: t.queryData.checkedTime.HAOLEI,
            kh: t.jzr_info.CNUM,
            zzjb: t.queryData.ZZJB || "",
            rang: t.queryData.checkedTime.YYSD || t.queryData.checkedTime.RANGE,
            start: t.queryData.checkedTime.START || "",
            end: t.queryData.checkedTime.END || "",
          };
          window.location.href = "http://www.gdxyy.org.cn/_weixin/dist/weixin/wxpay/example/jsapi_gh_gdspt.php?" + querystring.stringify(params)
          console.log(params)
          // console.log("http://www.hnwisdom.com/HealthRecords/Wxpay/example/jsapi_gh_gdspt.php?" +
          //   querystring.stringify(params)) 
          console.log("http://www.gdxyy.org.cn/_weixin/dist/weixin/wxpay/example/jsapi_gh_gdspt.php?" +
            querystring.stringify(params))
        }
      },
    },
    mounted() {},
  };
</script>
<style>
  .yygh_qr-dialog {
    min-height: 300px;
    padding: 15px 5px 0 5px;
    width: 300px;
    border-radius: 4px;
  }

  .yygh_qr-dialog .c_01 {
    letter-spacing: 2px;
  }

  .yygh_qr-dialog .c_02 {
    letter-spacing: 1px;
    height: 50px;
    line-height: 45px;
    border-top: 1px solid #ebebeb;
  }

  .yygh_qr-dialog .c_02 .c_02_1 {
    border-left: 1px solid #ebebeb;
  }
</style>
<style scoped>
  .yygh_qr {
    font-size: 14px;
  }

  .yygh_qr .m-top {
    border-bottom: 10px solid #f6f6f6;
  }

  .yygh_qr .m-top .c_01 {
    width: 50px;
    height: 50px;
  }

  .yygh_qr .m-top .c_01 img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
  }

  .yygh_qr .m-cell-box .m-cell:not(:first-child) {
    border-top: 1px solid #ebebeb;
  }

  .yygh_qr .m-cell-box .m-cell {
    padding: 15px;
  }

  .yygh_qr .m-cell-box .m-cell .tag_01 {
    display: inline-block;
    font-size: 12px;
    border: 1px solid #00c792;
    color: #00c792;
    height: 18px;
    line-height: 18px;
    padding-left: 8px;
    padding-right: 8px;
    border-radius: 9px;
  }

  .yygh_qr .m-check-jzr {
    padding-left: 15px;
    padding-right: 15px;
    position: relative;
    background: url(../../assets/img/yyghgd/icon05.svg) 0 0 no-repeat;
    background-size: auto 80%;
  }

  /*popup 样式*/
  .yygh_qr .pop-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 15px;
    color: #c0c4cc;
    padding: 15px;
  }

  .yygh_qr .pop-cont {
    font-size: 15px;
    padding: 15px;
  }

  .yygh_qr .select-list {
    display: flex;
    flex-wrap: wrap;
    text-align: center;
  }

  .yygh_qr .select-list .select-item {
    width: 25%;
    margin-bottom: 15px;
  }

  .yygh_qr .select-list .select-item .item-icon {
    display: inline-block;
    background: #d2f6ec;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
    margin-bottom: 8px;
  }

  .yygh_qr .select-list .select-item .iconfont {
    color: #00c792;
    font-size: 28px;
  }

  .u-res-btn,
  .u-sub-btn {
    margin: 15px;
    height: 41px;
    line-height: 40px;
    font-size: 16px;
    border-radius: 20px;
    text-align: center;
    border: 1px solid #00c792;
    color: #fff;
    background: #00c792;
  }
</style>