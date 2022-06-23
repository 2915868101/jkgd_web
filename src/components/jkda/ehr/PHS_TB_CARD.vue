<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="填表时间">
        <span v-if="detailData.FILING_DATE">{{$moment(detailData.FILING_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="评分分数">
        {{detailData.FILING_SCORE || '--'}}
      </van-cell>
      <van-cell center title="终止理由">
        {{detailData.LOST_FOLLOWUP_REASON || '--'}}
      </van-cell>
      <van-cell center title="终止日期">
        {{detailData.END_MANAGE_DATE || '--'}}
      </van-cell>
      <van-cell center title="失访原因">
        {{detailData.ZZYY || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.DIAGNOSIS_PLACE || '--'}}
      </van-cell>
      <van-cell center title="建档人">
        {{detailData.DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell center title="建档时间">
        {{detailData.DIAGNOSIS_DATE || '--'}}
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
      // 结核病患者管理登记表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'3574bde9a99042e7bbf557973729d577',
      //   TYPE:'PHS_TB_CARD'
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