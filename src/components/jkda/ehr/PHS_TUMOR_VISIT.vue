<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="健康处方">
        {{detailData.HEALTH_RECIPE || '--'}}
      </van-cell>
      <van-cell center title="目前症状">
        {{detailData.SYMPTOM || '--'}}
      </van-cell>
      <van-cell center title="并发症">
        {{detailData.BFZ || '--'}}
      </van-cell>
      <van-cell center title="评分分数">
        {{detailData.FILING_SCORE || '--'}}
      </van-cell>
      <van-cell center title="评估">
        {{detailData.PG || '--'}}
      </van-cell>
      <van-cell center title="备注">
        {{detailData.REMARK || '--'}}
      </van-cell>
      <van-cell center title="慢病用药记录" is-link @click="linkToPage('mbyyjl',searchForm)"></van-cell>
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
      // 肿瘤患者随访表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'8e35a1443bf847de9a82fc9b8027f98f',
      //   TYPE:'phs_tumor_visit'
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