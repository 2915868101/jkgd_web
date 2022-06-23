<template>
  <div id="yygh_xz_ys" class="yygh_xz_ys s-bgcfff">
    <van-sticky>
      <div class="m-ys-details s-bgcfff">
        <div class="f-df f-pt15 f-pb15 f-pl15 f-pr15">
          <div class="c_01">
            <span class="u-tag-zhuanjia">
              <img src="../../assets/img/yyghgd/zjmz_hd_img1.jpg" v-if="queryData.SEX=='男'" />
              <img src="../../assets/img/yyghgd/zjmz_hd_img2.jpg" v-if="queryData.SEX=='女'" />
            </span>
          </div>
          <div class="c_02 f-df1">
            <div class="f-fs16 s-c373737">
              {{queryData.YSXM}}
              <span class="f-ml5 f-fs14" v-if="queryData.ZZJB">{{queryData.ZZJB}}</span>
            </div>
            <div class="f-fs13 s-c8b8b8b f-pt10">科室：{{queryData.KSMC}}</div>
            <div class="f-fs13 s-c8b8b8b f-pt5 f-df f-lh20">
              <span>简介：</span>
              <span class="f-df1">
                <!-- <truncate
                  clamp="...更多"
                  actionClass="s-c00c792"
                  :length="15"
                  less="隐藏"
                  :text="queryData.YSJJ||'暂无介绍'"
                ></truncate> -->
              </span>
            </div>
          </div>
        </div>
      </div>
      <day-lists
        :on-change="changeDay"
        background-color="#fff"
        :show-days="3"
        :show-full-date="true"
      ></day-lists>
    </van-sticky>
    <ul class="items">
      <li class="item f-pr" v-for="(ys,i) in PB" :key="i">
        <div class="f-df f-pt15 f-pb15">
          <div class="f-df1 f-dfv">
            <div class="s-c373737">
              <span class="f-mr5">{{ys.YYSD}}</span>
              <span class="s-c373737">{{ys.TIMEZONE}}</span>
            </div>
            <div class="f-pt10 s-c9c9c9c">
              挂号费：
              <span class="s-cff0e0e">{{Number(ys.GHF).toFixed(2)}} 元</span>
            </div>
          </div>
          <div class="c_03 f-dfv">
            <span class="btn s-bgc00c792 s-cfff" @click="toOrder(ys)">预约</span>
            <div class="text s-cc7c7c7 f-mt5">
              剩余
              <span class="s-c00c792">{{ys.KGHS}}</span> 个号
            </div>
          </div>
        </div>
      </li>
    </ul>
    <empty v-if="isEmpty" :src="require('@/assets/img/yyghgd/empty2.jpg')" text="当日暂无可预约时间"></empty>
  </div>
</template>
<script>
import dayLists from "@/components/util/day_lists";
import empty from "@/components/util/empty";
// import truncate from "vue-truncate-collapsed";
export default {
  name: "yygh_xz_ys",
  directives: {},
  components: {
    dayLists,
    empty,
    // truncate,
  },
  created() { 
  },
  computed: {},
  data() {
    return { 
      items: "",
      PB: "",
      isEmpty: false,
    };
  },
  methods: {
    getIsEmptyFlag() {
      const t = this;
      t.isEmpty = false;
      t.$nextTick(function () {
        let obj1 = document.querySelector(".yygh_xz_ys .items .item");
        if (!obj1) {
          t.isEmpty = true;
        }
      });
    },
    changeDay(d, i) {
      const t = this;
      t.getDoctInfo(d);
      t.cacheData = {
        checkedDate: d,
        checkedIndex: i,
      };
    },
    getDoctInfo(d) {
      var t = this
      t.ajax({
        method: 'post',
        loading: true,
        params: {
          KSID: t.queryData.KSID,
          YYRQ: d.date,
          YSID: t.queryData.YSID
        },
        serverId: 'WXGD00051',
        url: t.serverPath1_1,
        success(data) {
          if(data.length){
            t.items = data[0]
            t.PB = t.items.PB
          }else{
            t.items = ''
            t.PB = ''
          }
          t.getIsEmptyFlag()
        }
      })
    },
    toOrder(ys) {
      const t = this;
      t.getDoctInfo(t.cacheData.checkedDate);
      let data = Object.assign(
        {},
        { checkedTime: ys, MZZT: "专家门诊" },
        t.items,
        t.cacheData,
        t.queryData
      );
      t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function () {
        if (data.KGHRS < 0) {
          t.$vux.alert.show({
            title: "提示",
            content: "此时间段号源已被预约完，请重新选择时间段！",
          });
        } else {
          t.linkToPage("/yyghgd_qr", data);
        }
      });
    },
  },
  mounted() {},
};
</script>
<style scoped>
.yygh_xz_ys{
  font-size: 14px;
}
.yygh_xz_ys .m-ys-details {
  border-bottom: 10px solid #f6f6f6;
}

.yygh_xz_ys .m-ys-details .c_01 {
  width: 70px;
}

.yygh_xz_ys .m-ys-details .c_01 img {
  width: 55px;
  height: 55px;
  border-radius: 50%;
}
.yygh_xz_ys .items {
  border-top: 1px solid #ebebeb;
}

.yygh_xz_ys .items .item {
  padding-left: 15px;
  border-bottom: 1px solid #ebebeb;
}

.yygh_xz_ys .items .item .c_02 {
  width: 70px;
  margin-right: 50px;
}

.yygh_xz_ys .items .item .c_03 {
  width: 100px;
  text-align: center;
}

.yygh_xz_ys .items .item .c_03 .btn {
  display: inline-block;
  margin: 0 auto;
  width: 60px;
  height: 30px;
  line-height: 30px;
  border-radius: 15px;
}
</style>
