<template>
  <div id="yygh_index" class="yygh_index s-bgcfff">
    <van-sticky>
      <div class="s-bgcf6f6f6">
        <div class="f-pt15 s-bgcfff">
          <div class="f-df f-pb15 f-pl15 f-pr15">
            <div class="img01 f-mr15"></div>
            <div class="f-df1 f-dfv m_01">
              <div class="m_01_1 f-fs16 s-c373737">广德市人民医院</div>
              <span class="m_01_2 f-fs13 f-mt15 f-pl20 icon icon01">地址：安徽省宣城市广德市</span>
              <a class="m_01_3 f-fs13 f-mt8 f-pl20 icon icon02" href="tel:0563-6022659">电话：0563-6022659</a>
            </div>
          </div>
          <div class="g-h20 s-bgcf4f9f9"></div>
        </div>
        <div class="f-pt15 f-pb15 f-pl15 f-pr15 s-bgcfff">
          <div class="m-search f-pt10 f-pb10 f-pl30 f-pr30 icon icon03" @click="linkToPage('/yyghgd_search')">
            <div class="f-ml10 f-fs14 f-lh20">搜索专家</div>
          </div>
        </div>
        <div class="f-pl15 f-pr15 s-bgcfff reset-vant">
          <van-tabs @click="showLists" title-inactive-color="#c9c9c9">
            <van-tab title="找科室" name="0"></van-tab>
            <van-tab title="找专家" name="1"></van-tab>
          </van-tabs>
        </div>
      </div>
    </van-sticky>
    <ul class="items items_01 s-bgcfff" v-show="showIndex==0">
      <li class="item u-arrow" v-for="(ks,i) in items1" :key="i" @click="toXzksPage({KSID:ks.DEPT_ID,KSMC:ks.NAME})">
        <div class="f-pl15 f-fs15">{{ks.NAME}}</div>
      </li>
      <li v-if="items1&&!items1.length">
        <div class="s-cc9c9c9 f-tac f-mt50 f-fs16">暂无科室排班</div>
      </li>
    </ul>
    <ul class="items items_02 s-bgcfff" v-show="showIndex==1">
      <li class="item u-arrow" v-for="(ys,i) in items2" :key="i" @click="linkToPage('/yyghgd_xz_ys',ys)">
        <div class="f-df f-pt15 f-pb15">
          <div class="c_01 f-pr">
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
            <div class="f-fs13 s-c8b8b8b f-pt5 f-toe">简介：{{ys.YSJJ||'暂无介绍'}}</div>
          </div>
        </div>
      </li>
      <li v-if="items2&&!items2.length">
        <div class="s-cc9c9c9 f-tac f-mt50 f-fs16">暂无专家排班</div>
      </li>
    </ul>
    <!-- <div class="m-fiexd_tag" @click="linkToPage('/guidance')">挂什么科室？</div> -->
  </div>
</template>
<script>
  export default {
    name: "yygh_index",
    directives: {},
    components: {},
    created() {
      const t = this;
      // t.updateTitle(t.$t("yygh_index_title"));
      t.$store.state.store_yyghgd.showIndex = 0
      if (t.showIndex == 0) {
        t.getDepaInfoTwo();
      } else {
        t.getYsDataInfo();
      }
    },
    computed: {
      showIndex() {
        return this.$store.state.store_yyghgd.showIndex;
      },
    },
    data() {
      return {
        items1: "",
        items2: "",
      };
    },
    methods: {
      toXzksPage(data) {
        const t = this;
        let str = data.KSID == "101" ? "普外、肛肠、烧伤整形" : "普外、胸外";
        if (data.KSID == "101" || data.KSID == "103") {
          t.$dialog
            .confirm({
              title: "",
              message: `${data.KSMC}包括<br/>${str}`,
              confirmButtonText: "去挂号",
            })
            .then(() => {
              t.linkToPage("/yyghgd_xz_ks", data);
            })
            .catch(() => {});
        } else {
          t.linkToPage("/yyghgd_xz_ks", data);
        }
      },
      showLists(i) {
        const t = this;
        if (t.showIndex != i) {
          t.$store.state.store_yyghgd.showIndex = i;
          if (i == 0 && !t.items1) {
            t.getDepaInfoTwo();
          } else if (i == 1 && !t.items2) {
            t.getYsDataInfo();
          }
        }
      },
      getDepaInfoTwo() {
        var t = this
        t.ajax({
          method: 'post',
          serverId: 'WXGD00015',
          url: t.serverPath1_1,
          success(data) {
            console.log(data)
            t.items1 = data[0].CONTENTS
          }
        })
      },
      getYsDataInfo() {
        var t = this
        t.ajax({
          method: 'post',
          serverId: 'WXGD00015',
          params: {
            YSID: 'ALL'
          },
          url: t.serverPath1_1,
          success(data) {
            console.log(data)
            if (data) {
              t.items2 = data[0].CONTENTS
            }
          }
        })
      }
    },
    mounted() {},
  };
</script>
<style scoped>
  .yygh_index .img01 {
    width: 123px;
    height: 80px;
    background: url(../../assets/img/yyghgd/img01.jpg) 0 0 no-repeat;
    background-size: 100% 100%;
  }

  .yygh_index .icon {
    line-height: 14px;
    background-repeat: no-repeat;
    background-position: 0 center;
    background-size: auto 70%;
  }

  .yygh_index .icon.icon01 {
    background-image: url(../../assets/img/yyghgd/icon01.svg);
  }

  .yygh_index .icon.icon02 {
    background-image: url(../../assets/img/yyghgd/icon02.svg);
  }

  .yygh_index .m-search {
    border-radius: 15px;
    line-height: 20px;
    color: #c9c9c9;
    background-color: #f4f4f4;
  }

  .yygh_index .icon.icon03 {
    background-image: url(../../assets/img/yyghgd/icon03.svg);
    background-position: 15px center;
    background-size: auto 35%;
  }

  .yygh_index .items .item {
    padding-left: 15px;
    padding-right: 15px;
    position: relative;
  }

  .yygh_index .items .item:not(:first-child) {
    border-top: 1px solid #ebebeb;
  }

  .yygh_index .items.items_01 .item {
    height: 55px;
    line-height: 55px;
  }

  .yygh_index .items .item.u-arrow:after {
    right: 40px;
  }

  .yygh_index .items.items_02 .item.u-arrow:after {
    width: 12px;
    height: 12px;
  }

  .yygh_index .items.items_02 .item .c_01 {
    width: 70px;
  }

  .yygh_index .items.items_02 .item .c_01 img {
    width: 55px;
    height: 55px;
    border-radius: 50%;
  }

  .yygh_index .items.items_02 .item .c_02 {
    width: 70px;
    margin-right: 50px;
  }

  .yygh_index .m-fiexd_tag {
    position: fixed;
    bottom: 50px;
    right: 0;
    padding: 0 10px 0 30px;
    height: 20px;
    line-height: 20px;
    border-radius: 10px 0 0 10px;
    border-width: 1px 0 1px 1px;
    border-style: solid;
    border-color: #00c792;
    color: #00c792;
    font-size: 13px;
    background: #fff url(../../assets/img/yyghgd/icon04.svg) 10px center no-repeat;
    background-size: 16px auto;
  }

  .yygh_index>>>.van-tabs__line {
    background-color: #00c792 !important;
  }
</style>