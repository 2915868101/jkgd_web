<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="收缩压">
        {{Number(detailData.SBP).toFixed(2) || '--'}}
      </van-cell>
      <van-cell center title="舒张压">
        {{Number(detailData.DBP).toFixed(2) || '--'}}
      </van-cell>
      <van-cell center title="健康状况">
        {{detailData.GENERAL_HEALTH_ST || '--'}}
      </van-cell>
      <van-cell center title="心理状况">
        {{detailData.GENERAL_MENTATION_ST || '--'}}
      </van-cell>
      <van-cell center title="乳房检查结果">
        <span v-if="detailData.NIPPLE_TEST_RESULT=='1'">无异常</span>
        <span v-else-if="detailData.NIPPLE_TEST_RESULT=='2'">有异常</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="恶露检查结果">
        <span v-if="detailData.LOCHIA_MARK=='1'">无异常</span>
        <span v-else-if="detailData.LOCHIA_MARK=='2'">有异常</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="子宫检查结果">
        <span v-if="detailData.UTERUS_MARK=='1'">无异常</span>
        <span v-else-if="detailData.UTERUS_MARK=='2'">有异常</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="伤口愈合状况">
        <span v-if="detailData.WOUND_HEALING_CD=='1'">无异常</span>
        <span v-else-if="detailData.WOUND_HEALING_CD=='2'">有异常</span>
        <span v-else>--</span>
      </van-cell>
      <div class="cell-item">
        <div class="tit">指导</div>
        <div class="cont">{{detailData.GUIDANCE || '--'}}</div>
      </div>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_EMPLOY || '--'}}
      </van-cell>
      <van-cell center title="随访日期">
        {{detailData.FOLLOWUP_DATE || '--'}}
      </van-cell>
    </van-cell-group>
  </div>
</template>
<script>
  export default {
    name: 'jkda_detail',
    components: {},
    computed: {},
    created() {
      var t = this
      // 产后42天
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      t.getDetailData()
    },
    data() {
      return {
        detailData: [],
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
          }
        });
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @import '../../../assets/css/jkda/jkda_detail.css';

  @media screen and (max-width: 320px) {}
</style>