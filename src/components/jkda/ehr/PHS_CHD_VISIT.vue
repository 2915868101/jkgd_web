<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="目前症状">
        {{detailData.MQZZ || '--'}}
      </van-cell>
      <van-cell center title="目前服药名">
        {{detailData.CURRENT_DRUG || '--'}}
      </van-cell>
      <van-cell center title="目前采取非药物治疗措施">
        {{detailData.CS || '--'}}
      </van-cell>
      <van-cell center title="效果评价">
        {{detailData.EFFECT_ASSESSMENT || '--'}}
      </van-cell>
      <van-cell center title="服药不规律原因">
        {{detailData.BGLYY || '--'}}
      </van-cell>
      <van-cell center title="治疗情况">
        {{detailData.ZLQK || '--'}}
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
      // 冠心病患者随访表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'612871c9d2004cb4adbe57b95a6fc83c',
      //   TYPE:'PHS_CHD_VISIT'
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