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
      <van-cell center title="血脂值">
        {{detailData.BLOODFAT || '--'}}
      </van-cell>
      <van-cell center title="目前服药名">
        {{detailData.CURRENT_DRUG || '--'}}
      </van-cell>
      <van-cell center title="神经功能缺失">
        {{detailData.NEUROLOGICAL_DEFICIT || '--'}}
      </van-cell>
      <van-cell center title="目前症状">
        {{detailData.ZZ || '--'}}
      </van-cell>
      <van-cell center title="并发症">
        {{detailData.BFZ || '--'}}
      </van-cell>
      <van-cell center title="评分分数">
        {{detailData.FILING_SCORE || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
      <van-cell center title="随访医生">
        {{detailData.DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell center title="下次随访日期">
        {{detailData.NEXT_FOLLOWUP_DATE || '--'}}
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
      // 脑卒中患者随访表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'7c53457eee8f4c019b9c3616916df599',
      //   TYPE:'PHS_STROKE_VISIT'
      // }
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