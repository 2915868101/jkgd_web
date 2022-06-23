<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="检查日期">
        {{detailData.EXAM_DATE || '--'}}
      </van-cell>
      <van-cell center title="医疗机构">
        {{detailData.MEDICAL_ORG || '--'}}
      </van-cell>
      <van-cell center title="检查结果">
        {{detailData.EXAM_RESULT || '--'}}
      </van-cell>
      <van-cell center title="其他检查结果">
        {{detailData.OTHER_EXAM_RESULT || '--'}}
      </van-cell>
      <van-cell center title="类型">
        {{detailData.TYPE || '--'}}
      </van-cell>
      <van-cell center title="转归">
        {{detailData.ZG || '--'}}
      </van-cell>
      <van-cell center title="备注">
        {{detailData.REMARK || '--'}}
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
      // 两癌筛查登记表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'afeb05892b27493ebf27bb83e441a1f5',
      //   TYPE:'PHS_TWO_CANCER_REG'
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