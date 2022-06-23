<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="患者类型">
        {{detailData.PATIENT_TYPE || '--'}}
      </van-cell>
      <van-cell center title="症状">
        {{detailData.ZZ || '--'}}
      </van-cell>
      <van-cell center title="并发症">
        {{detailData.BFZ || '--'}}
      </van-cell>
      <van-cell center title="化疗方案">
        {{detailData.HLFA || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="(痰)提取时间">
        {{detailData.EXTRACTION_TIME || '--'}}
      </van-cell>
      <van-cell center title="痰菌情况">
        {{detailData.SPUTUM_STATUS || '--'}}
      </van-cell>
      <van-cell center title="赖药情况">
        {{detailData.赖药情况 || '--'}}
      </van-cell>
      <van-cell center title="症状和体征">
        {{detailData.SYMPTOMS || '--'}}
      </van-cell>
      <van-cell center title="其他症状及体征">
        {{detailData.OTHER_SYMPTOMS || '--'}}
      </van-cell>
      <van-cell center title="化疗方案描述">
        {{detailData.CHEM_SCHEMES || '--'}}
      </van-cell>
      <van-cell center title="药品剂型">
        {{detailData.MEDICINE_TYPE || '--'}}
      </van-cell>
      <van-cell center title="随访诊断">
        {{detailData.ZDFS || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
      <van-cell center title="随访医生">
        {{detailData.DOCTOR || '--'}}
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
      // 结核病患者随访表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'00030faab4a24217b427124a6adc14a3',
      //   TYPE:'PHS_TB_VISIT'
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