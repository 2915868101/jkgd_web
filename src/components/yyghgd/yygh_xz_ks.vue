<template>
  <div id="yygh_xz_ks" class="yygh_xz_ks s-bgcfff">
    <van-sticky>
      <day-lists :on-change="changeDay"></day-lists>
    </van-sticky>
    <empty v-if="isEmpty" :src="require('@/assets/img/yyghgd/empty2.jpg')" text="当天无可预约的医生"></empty>
    <ul class="items">
      <li class="item f-pr" v-for="ys in items" :key="ys.ZZJBID" v-show="ys.MZZT=='专家门诊'&&ys.PB.length&&thanDate(computeDate(2),cacheData.checkedDate.date)">
        <div class="f-df f-pt15 f-pb15">
          <div class="c_01">
            <span class="u-tag-zhuanjia">
              <img src="../../assets/img/yyghgd/zjmz_hd_img1.jpg" v-if="ys.SEX=='男'" />
              <img src="../../assets/img/yyghgd/zjmz_hd_img2.jpg" v-if="ys.SEX=='女'" />
            </span>
          </div>
          <div class="c_02 f-df1">
            <div class="f-fs16 s-c373737 f-pt5">
              {{ys.YSXM}}
              <span class="f-ml5 f-fs14" v-if="ys.ZZJB">{{ys.ZZJB}}</span>
            </div>
            <div class="f-fs13 s-c8b8b8b f-pt15">
              挂号费：
              <span class="s-cff0e0e">{{Number(ys.GHF).toFixed(2)}} 元</span>
            </div>
            <div class="f-fs13 s-c8b8b8b f-pt20 f-toe">擅长：{{ys.YSJJ||'暂无介绍'}}</div>
          </div>
        </div>
        <div class="c_03 f-fs13">
          <span class="btn s-bgc00c792 s-cfff" @click="showPicker(ys)">预约</span>
          <div class="text s-cc7c7c7 f-mt5">
            剩余
            <span class="s-c00c792">{{ys.KGHRS}}</span> 个号
          </div>
        </div>
      </li>
      <li class="item f-pr" v-for="ys in items" :key="ys.PBZXID" v-show="ys.MZZT=='普通门诊'&&ys.PB.length">
        <div class="f-df f-pt15 f-pb15">
          <div class="c_01">
            <img src="../../assets/img/yyghgd/ptmz_hd_img.jpg" />
          </div>
          <div class="c_02 f-df1">
            <div class="f-fs16 s-c373737 f-pt5">{{ys.MZZT}}</div>
            <div class="f-fs13 s-c8b8b8b f-pt15">
              挂号费：
              <span class="s-cff0e0e">{{Number(ys.GHF).toFixed(2)}} 元</span>
            </div>
          </div>
        </div>
        <div class="c_03 f-fs13">
          <span class="btn s-bgc00c792 s-cfff " @click="showPicker(ys)">预约</span>
          <div class="text s-cc7c7c7 f-mt5">
            剩余
            <span class="s-c00c792">{{ys.KGHRS}}</span> 个号
          </div>
        </div>
      </li>
    </ul>
    <mt-popup-picker ref="picker" :datas="popupLists" :value-key="value_key" :columns="columns" :confirm="onConfirm"></mt-popup-picker>
  </div>
</template>
<script>
  import dayLists from "@/components/util/day_lists";
  import empty from "@/components/util/empty";
  import mtPopupPicker from "@/components/util/mt_popup_picker";
  export default {
    name: "yygh_xz_ks",
    directives: {},
    components: {
      dayLists,
      empty,
      mtPopupPicker,
    },
    created() {
      // const t = this;
      // t.queryData = JSON.parse(t.$route.query.data);
      // setTimeout(() => {
      //   t.updateTitle(t.queryData.KSMC);
      // }, 0);
    },
    computed: {},
    data() {
      return {
        cacheData: "",
        items: "",
        def_ys_info: "",
        popupLists: "",
        value_key: "RANGE",
        columns: "",
        isEmpty: false,
      };
    },
    methods: {
      showPicker(ys) {
        const t = this;
        t.getDoctInfo(t.cacheData.checkedDate);
        let date = new Date();
        let hours =
          date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
        let minutes =
          date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        if (hours == "00" && minutes < "05") {
          t.$dialog.alert({
            title: "提示",
            message: "号源刷新中，请00:04后重试！",
          });
        } else {
          t.def_ys_info = ys;
          if (ys.PTHYXX && ys.PTHYXX == "Y") {
            t.value_key = "TIMEZONE";
            t.columns = 3;
          } else {
            t.value_key = ys.MZZT == "专家门诊" ? "TIMEZONE" : "RANGE";
            t.columns = ys.MZZT == "专家门诊" ? 3 : 2;
          }
          t.popupLists = ys.PB;
          if (t.popupLists.length) {
            t.$refs.picker.showPopup = true;
          }
        }
      },
      getIsEmptyFlag() {
        const t = this;
        t.isEmpty = false;
        t.$nextTick(function() {
          let obj1 = document.querySelector(".yygh_xz_ks .items .item");
          if (!obj1) {
            t.isEmpty = true;
          }
        });
      },
      onConfirm(res) {
        const t = this;
        let data = Object.assign({}, t.def_ys_info, t.cacheData, t.queryData, {
          checkedTime: res,
        });
        t.toOrder(data);
      },
      changeDay(d, i) {
        const t = this;
        t.getDoctInfo(d);
        t.cacheData = {
          checkedDate: d,
          checkedIndex: i,
        };
      },
      getDoctInfo(d, i, cb) {
        var t = this
        t.ajax({
          method: 'post',
          loading: true,
          params: {
            KSDM: t.queryData.KSID,
            YYRQ: d.date
          },
          serverId: 'WXGD00051',
          url: t.serverPath1_1,
          success(data) {
            console.log({
              KSDM: t.queryData.KSID,
              YYRQ: d.date
            })
            console.log(data)
            t.items = data
            t.getIsEmptyFlag()
            cb && cb(data)
          }
        })
      },
      toOrder(data) {
        const t = this;
        t.isRegister({
          openid: t.wx_userInfo.openid,
          type: 0
        }, function() {
          if (data.KGHRS < 0) {
            t.$dialog.alert({
              title: "提示",
              message: "此时间段号源已被预约完，请重新选择时间段！",
            });
          } else {
            console.log(66666666, data)
            t.linkToPage("/yyghgd_qr", data);
          }
        });
      },
    },
    mounted() {},
  };
</script>
<style scoped>
  .yygh_xz_ks .items .item {
    padding-left: 15px;
  }

  .yygh_xz_ks .items .item:not(:first-child) {
    border-top: 1px solid #ebebeb;
  }

  .yygh_xz_ks .items .item .c_01 {
    width: 70px;
  }

  .yygh_xz_ks .items .item .c_01 img {
    width: 55px;
    height: 55px;
    border-radius: 50%;
  }

  .yygh_xz_ks .items .item .c_02 {
    width: 70px;
    margin-right: 50px;
  }

  .yygh_xz_ks .items .item .c_03 {
    width: 100px;
    position: absolute;
    right: 15px;
    top: 16px;
    text-align: center;
  }

  .yygh_xz_ks .items .item .c_03 .btn {
    display: inline-block;
    width: 65px;
    height: 20px;
    font-size: 14px;
    line-height: 20px;
    border-radius: 20px;
    overflow: hidden;
  }
</style>