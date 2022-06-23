<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="初次发病日期">
        {{detailData.FALL_ILL_DATE || '--'}}
      </van-cell>
      <van-cell center title="监护人姓名">
        {{detailData.GUARDIAN_NAME || '--'}}
      </van-cell>
      <van-cell center title="确诊日期">
        {{detailData.DIAG_DATE || '--'}}
      </van-cell>
      <van-cell center title="终止管理日期">
        {{detailData.END_MANAGE_DATE || '--'}}
      </van-cell>
      <van-cell center title="既往精神专科住院数">
        {{detailData.PH_INPAT_TIMES || '--'}}
      </van-cell>
      <van-cell center title="首次药物治疗日期">
        {{detailData.ANTI_FIR_TR_DATE || '--'}}
      </van-cell>
      <van-cell center title="填报医师姓名">
        {{detailData.REPORT_DR_NAME || '--'}}
      </van-cell>
      <div class="cell-item">
        <div class="tit">专科医生意见</div>
        <div class="cont">{{detailData.TR_OPINION || '--'}} </div>
      </div>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="确诊医疗机构名称">
        {{detailData.DIAG_ORG_NAME || '--'}}
      </van-cell>
      <van-cell center title="诊断">
        {{detailData.DIAG_CONTENT || '--'}}
      </van-cell>
      <van-cell center title="目前症状">
        {{detailData.MQZZ || '--'}}
      </van-cell>
      <van-cell center title="对家庭影响">
        {{detailData.JTYX || '--'}}
      </van-cell>
      <van-cell center title="目前症状">
        {{detailData.MQZZ || '--'}}
      </van-cell>
      <van-cell center title="最近治疗效果">
        {{detailData.JWMZ || '--'}}
      </van-cell>
      <van-cell center title="关锁情况">
        {{detailData.GSQK || '--'}}
      </van-cell>
      <van-cell center title="知情同意">
        {{detailData.ZQTYBZ || '--'}}
      </van-cell>
      <van-cell center title="最近治疗效果">
        {{detailData.ZYQK || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="村(居委会)联系人电话">
        {{detailData.VILLAGE_LINK_TEL || '--'}}
      </van-cell>
      <van-cell center title="村(居委会)联系人姓名">
        {{detailData.VILLAGE_LINK_NAME || '--'}}
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
      // 重性精神病个人信息
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'5e7da389e1dd44e494e51e4c13b52965',
      //   TYPE:'PHS_MRC'
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