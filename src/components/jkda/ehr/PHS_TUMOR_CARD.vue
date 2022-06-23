<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="诊断时间">
        <span v-if="detailData.DIAGNOSIS_DATE">{{$moment(detailData.DIAGNOSIS_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="责任医生">
        {{detailData.DOCTOR_NAME}}
      </van-cell>
      <van-cell center title="诊断医院">
        {{detailData.DIAGNOSIS_HOSPITAL}}
      </van-cell>
      <van-cell center title="其他医院">
        {{detailData.OTHER_HOSPITAL}}
      </van-cell>
      <van-cell center title="处方意见">
        {{detailData.RECIPE_ADVICE || '--'}}
      </van-cell>
      <van-cell center title="并发症">
        {{detailData.RECIPE_DIET || '--'}}
      </van-cell>
      <van-cell center title="转归">
        {{detailData.ZG || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="原用药1">
        {{detailData.ORIGINAL_DRUG_USAGE1 || '--'}}
      </van-cell>
      <van-cell center title="原用药2">
        {{detailData.ORIGINAL_DRUG_USAGE2 || '--'}}
      </van-cell>
      <van-cell center title="原用药3">
        {{detailData.ORIGINAL_DRUG_USAGE4 || '--'}}
      </van-cell>
      <van-cell center title="现用药1">
        {{detailData.ACTIVE_DRUG_USAGE1 || '--'}}
      </van-cell>
      <van-cell center title="现用药2">
        {{detailData.ACTIVE_DRUG_USAGE2 || '--'}}
      </van-cell>
      <van-cell center title="现用药3">
        {{detailData.ACTIVE_DRUG_USAGE4 || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.DIAGNOSIS_HOSPITAL || '--'}}
      </van-cell>
      <van-cell center title="建档人">
        {{detailData.DOCTOR_NAME || '--'}}
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
      // 肿瘤患者管理登记表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm = {
      //   TREATMENT_ID: 'a44e7324bc2a4966ac3bf27842b96e1b',
      //   TYPE: 'PHS_TUMOR_CARD'
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