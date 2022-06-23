<template>
  <div id="yygh_search" class="yygh_search s-bgcfff">
    <div class="m-search f-pt5">
      <van-search v-model="value" shape="round" @input="getResult" ref="search" placeholder="搜索专家" />
    </div>
    <empty :src="require('@/assets/img/yyghgd/empty.jpg')" text="未找到相关医生专家内容" v-if="items2&&!items2.length"></empty>
    <ul class="items s-bgcfff">
      <li v-for="(ys,i) in items2" :key="i" @click="linkToPage('/yyghgd_xz_ys',ys)">
        <div class="f-df f-pt15 f-pb15 item u-arrow" v-if="ys.YSXM">
          <div class="c_01">
            <span class="u-tag-zhuanjia">
              <img src="../../assets/img/yyghgd/zjmz_hd_img1.jpg" v-if="ys.SEX=='男'" />
              <img src="../../assets/img/yyghgd/zjmz_hd_img2.jpg" v-if="ys.SEX=='女'" />
            </span>
          </div>
          <div class="c_02 f-df1">
            <div class="f-fs16 s-c373737">
              {{ys.YSXM}}
              <span class="f-ml5 f-fs14" v-if="ys.ZZJB">{{ys.ZZJB}}</span>
            </div>
            <div class="f-fs13 s-c8b8b8b f-pt10">科室：{{ys.KSMC}}</div>
            <div class="f-fs13 s-c8b8b8b f-pt5 f-toe">擅长：{{ys.YSJJ||'暂无介绍'}}</div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>
<script>
import empty from "@/components/util/empty";
export default {
  name: "yygh_search",
  directives: {},
  components: {
    empty,
  },
  created() {},
  computed: {},
  data() {
    return {
      items2: "",
      value: "",
    };
  },
  methods: {
    getResult() {
      const t = this;
      t.ajax({
        method: "post",
        serverId: "WXGD00015",
        params: {
          YSID: t.value,
        },
        url: t.serverPath1_1,
        success: function(data) {

          console.log(data);
          t.items2 = data[0].CONTENTS || [];
        }
      });
    },
  },
  mounted() {},
};
</script>
<style>
.yygh_search .m-search {
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
}

.yygh_search .vux-search-box {
  padding-left: 10px;
  padding-right: 10px;
}

.yygh_search .weui-search-bar {
  background-color: #fff;
}

.yygh_search .weui-search-bar__form:after,
.yygh_search .weui-search-bar:before,
.yygh_search .weui-search-bar:after {
  display: none;
}

.yygh_search .weui-search-bar__box {
  padding-left: 45px;
  padding-right: 40px;
}

.yygh_search .weui-search-bar__label,
.yygh_search .weui-search-bar__form {
  background-color: #f4f4f4;
  border-radius: 20px;
  color: #d4d4d4;
  line-height: 35px;
}

.yygh_search .weui-search-bar__label .weui-icon-search,
.yygh_search .weui-search-bar__box .weui-icon-search {
  line-height: 35px;
  color: #c9c9c9;
  left: 20px;
}

.yygh_search .weui-search-bar__box .weui-icon-clear {
  line-height: 35px;
  color: #dcdce0;
}

.yygh_search .weui-search-bar__box .weui-search-bar__input {
  padding: 8px 0;
}

.yygh_search .weui-search-bar__cancel-btn {
  line-height: 35px;
  color: #646464;
}

.yygh_search input.weui-input,
.yygh_search input.weui-search-bar__input {
  color: #646464;
}

.yygh_search .items {
  margin-top: 70px;
}

.yygh_search .items .item {
  padding-left: 15px;
  padding-right: 15px;
  position: relative;
}

.yygh_search .items .item:not(:first-child) {
  border-top: 1px solid #ebebeb;
}

.yygh_search .items .item.u-arrow:after {
  width: 12px;
  height: 12px;
  right: 40px;
}

.yygh_search .items .item .c_01 {
  width: 70px;
}

.yygh_search .items .item .c_01 img {
  width: 55px;
  height: 55px;
  border-radius: 50%;
}

.yygh_search .items .item .c_02 {
  width: 70px;
  margin-right: 50px;
}

.yygh_search .m-empty .img {
  height: 100px;
  background: url(../../assets/img/yyghgd/empty.jpg) center center no-repeat;
  background-size: auto 80%;
}

.yygh_search .m-empty {
  margin-top: 150px;
  color: #c9c9c9;
}
</style>