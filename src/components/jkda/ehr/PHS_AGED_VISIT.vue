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
      <van-cell center title="空腹血糖">
        {{detailData.FBG_VALUE || '--'}}
      </van-cell>
      <van-cell center title="总胆固醇">
        {{detailData.CHOL_VALUE || '--'}}
      </van-cell>
      <van-cell center title="现心率">
        {{detailData.HEART_RATE || '--'}}
      </van-cell>
      <van-cell center title="目标心率">
        {{detailData.HEART_RATE_GOAL || '--'}}
      </van-cell>
      <van-cell center title="症状">
        {{detailData.ZZ || '--'}}
      </van-cell>
      <van-cell center title="症状其他">
        {{detailData.OTHER_SYMPTOM || '--'}}
      </van-cell>
      <van-cell center title="并发症">
        {{detailData.BFZ || '--'}}
      </van-cell>
      <van-cell center title="其他并发症">
        {{detailData.OTHER_COMPLICATION || '--'}}
      </van-cell>
      <van-cell center title="随访建议">
        {{detailData.FOLLOWUP_ADVICE || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
      <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_DOCTOR || '--'}}
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
      // 老年人随访表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'1b3d067bdff749c39a70989296cf4801',
      //   TYPE:'PHS_AGED_VISIT'
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