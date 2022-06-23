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
      <van-cell center title="体温">
        {{detailData.BODY_TEMPERATURE || '--'}}
      </van-cell>
      <van-cell center title="伤口异常">
        {{detailData.ABNORMAL_WOUND || '--'}}
      </van-cell>
      <van-cell center title="乳房检查结果">
        {{detailData.NIPPLE_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="恶露状况">
        {{detailData.LOCHIA_STATUS || '--'}}
      </van-cell>
      <van-cell center title="子宫异常描述">
        {{detailData.UTERUS_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="健康状况详细描述">
        {{detailData.GENERAL_HEALTH_ST || '--'}}
      </van-cell>
      <van-cell center title="心理状况详细描述">
        {{detailData.GENERAL_MENTATION_ST || '--'}}
      </van-cell>
      <van-cell center title="产妇分类-健康状况评估描述">
        {{detailData.MATERNAL_CLASSIFICATION || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="转诊原因">
        {{detailData.TRANSFER_REASON || '--'}}
      </van-cell>
      <van-cell center title="转入医疗机构名称">
        {{detailData.TRANSFER_DEPT || '--'}}
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
      // 产后访问记录表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'0001573ac143423dacb617d8c594831f',
      //   TYPE:'PHS_PARTURITION_VISIT'
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