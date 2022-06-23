<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="就诊人">
        {{detailData.XM}} ({{detailData.SEX_NAME}} {{detailData.AGE}})
      </van-cell>
      <van-cell center title="就诊科室">
        {{detailData.DEP_NAME || '--'}}
      </van-cell>
      <van-cell center title="接诊医生">
        {{detailData.DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell center title="就诊时间">
        {{detailData.REG_DATE || '--'}}
      </van-cell>
      <van-cell center title="就诊医院">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt15">
      <van-cell center title="医生诊断">
        {{detailData.DISEASE || '--'}}
      </van-cell>
      <div class="cell-item">
        <div class="tit">检查报告：</div>
        <div class="cont" v-if="jcList">
          <van-button class="f-mr8 f-mb8" round v-for="(c,i) in jcList" :key="i">{{c.GROUP_NAME}}</van-button>
          <div v-show="!jcList.length">--</div>
        </div>
      </div>
      <div class="cell-item">
        <div class="tit">检验报告：</div>
        <div class="cont" v-if="jyList">
          <van-button class="f-mr8 f-mb8" round v-for="(c,i) in jyList" :key="i">{{c.GROUP_NAME}}</van-button>
          <div v-show="!jyList.length">--</div>
        </div>
      </div>
    </van-cell-group>
    <van-collapse class="s-bgcfff f-mt15" v-model="activeNames" accordion>
      <van-collapse-item title="处方明细" name="1">
        <div class="flex-item" v-for="(c,i) in detailData.CF" :key="i">
          <div class="f-df14">{{c.DRUG_NAME}}</div>
          <div class="f-tfr f-df5">{{c.SPEC || '--'}}</div>
          <div class="f-tfr f-df5">{{c.QUANTITY}}{{c.UNIT}}</div>
        </div>
        <div v-show="!detailData.CF" class="f-fs14 f-pt15 f-pb15 f-pl15 f-pr15">暂无处方</div>
      </van-collapse-item>
    </van-collapse>
  </div>
</template>
<script>
  export default {
    name: 'jkda_detail',
    components: {},
    computed: {},
    created() {
      var t = this
      // 门诊记录
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      t.getDetailData()
    },
    data() {
      return {
        detailData: [],
        jcList: [],
        jyList: [],
        activeNames: '1'
      }
    },
    methods: {
      getDetailData() {
        var t = this
        t.ajax({
          serverId: 'WXBM00037',
          url: t.serverPath2,
          params: t.searchForm,
          success: function(data) {
            t.detailData = data[0]
            console.log(t.detailData)
            var JYJC = t.detailData.JYJC
            if (JYJC) {
              for (let c of JYJC) {
                console.log(c)
                if (c.TYPE == 'JY') {
                  t.jyList.push(c)
                } else {
                  t.jcList.push(c)
                }
              }
            }
          }
        });
      },
      toInfo(val) {
        console.log(val)
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jkda_detail .notice-wrap {
    background: #00c792;
    color: #fff;
    padding: 15px;
    font-size: 14px;
  }

  .jkda_detail>>>.van-collapse-item__content {
    padding: 0;
    color: #606266;
  }

  .jkda_detail>>>.van-cell {
    padding: 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 15px;
  }

  .jkda_detail>>>.van-button {
    height: 30px;
    padding: 0 10px;
  }

  .jkda_detail>>>.van-cell__title {
    color: #606266;
  }

  .jkda_detail>>>.van-cell__value {
    color: #303133;
  }

  .jkda_detail>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .jkda_detail .cell-item {
    font-size: 14px;
    padding: 0 15px 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 15px;
  }

  .jkda_detail .cell-item .tit {
    padding: 15px 0;
  }

  .jkda_detail .cell-item .cont {
    line-height: 24px;
  }

  .jkda_detail .swiper-wrap {
    font-size: 14px;
    padding-top: 15px;
  }

  .jkda_detail .swiper-wrap .swiper-nav {
    width: 60%;
    margin: 0 auto;
    display: flex;
    border: 1PX solid #ebeef5;
    background: #fcfcfc;
    border-radius: 5px;
  }

  .jkda_detail .swiper-wrap .swiper-nav .item {
    flex: 8;
    padding: 10px 0;
    text-align: center;
    border-right: 1PX solid #ebeef5;
  }

  .jkda_detail .flex-item {
    font-size: 15px;
    display: flex;
    padding: 15px;
    border-bottom: 1PX solid #ebeef5;
  }

  .jkda_detail .flex-item .f-tfr {
    text-align: right;
  }
</style>