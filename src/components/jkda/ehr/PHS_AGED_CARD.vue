<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="手术史">
        {{detailData.OPERATION_HISTORY1 && detailData.OPERATION_HISTORY2|| '--'}}
      </van-cell>
      <van-cell center title="评分">
        {{detailData.FILING_SCORE || '--'}}
      </van-cell>
      <van-cell center title="暴露史">
        {{detailData.BLS || '--'}}
      </van-cell>
      <van-cell center title="疾病史">
        {{detailData.JBS || '--'}}
      </van-cell>
      <van-cell center title="过敏史">
        {{detailData.GMS || '--'}}
      </van-cell>
      <van-cell center title="残疾情况">
        {{detailData.CJQK || '--'}}
      </van-cell>
      <van-cell center title="健康状态">
        {{detailData.JKZK || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.DIAGNOSIS_PLACE || '--'}}
      </van-cell>
      <van-cell center title="建档人">
        {{detailData.FILING_DOCTOR || '--'}}
      </van-cell>
      <van-cell center title="建档时间">
        {{detailData.FILING_DATE || '--'}}
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
      // 老年人专项健康档案  
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