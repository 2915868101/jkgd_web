<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="入院日期">
        <span v-if="detailData.ADMISSION_DATE">{{$moment(detailData.ADMISSION_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="出院日期">
        <span v-if="detailData.DISCHARGE_DATE">{{$moment(detailData.DISCHARGE_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="住院次数">
        {{detailData.INPATIENT_TIMES || '--'}}
      </van-cell>
      <van-cell center title="医院名称">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
      <van-cell center title="住院总费用">
        {{detailData.TOTAL_AMOUNT || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="就诊医生">
        {{detailData.FMZDOCT || '--'}}
      </van-cell>
      <van-cell center title="入院情况">
        {{detailData.IN_CONDITION_NAME || '--'}}
      </van-cell>
      <van-cell center title="诊断结果">
        {{detailData.DISEASE || '--'}}
      </van-cell>
      <van-cell center title="诊治部位">
        {{detailData.OPERATION_BP_NAME || '--'}}
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
      // 住院病案首页(电子病历)
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