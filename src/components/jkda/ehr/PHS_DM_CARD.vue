<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="就诊时间">
        <span v-if="detailData.MEDICAL_DATE">{{$moment(detailData.MEDICAL_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="收缩压">
        {{Number(detailData.SBP).toFixed(2) || '--'}}
      </van-cell>
      <van-cell center title="舒张压">
        {{Number(detailData.DBP).toFixed(2) || '--'}}
      </van-cell>
      <van-cell center title="糖尿病类型">
        {{detailData.LX || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="空腹血糖">
        {{detailData.FBG_VALUE || '--'}}
      </van-cell>
      <van-cell center title="餐后2小时后血糖">
        {{detailData.PBG2H_VALUE || '--'}}
      </van-cell>
      <van-cell center title="糖化血红蛋白值">
        {{detailData.HBA1C_VALUE || '--'}}
      </van-cell>
      <van-cell center title="总胆固醇值">
        {{detailData.CHOL_VALUE || '--'}}
      </van-cell>
      <van-cell center title="目前症状">
        {{detailData.MQZZ || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="药品名称">
        {{detailData.DRUG_NAME1 || '--'}}
      </van-cell>
      <van-cell center title="药物使用方法1">
        {{detailData.DRUG_USAGE1 || '--'}}
      </van-cell>
      <van-cell center title="药品名称">
        {{detailData.DRUG_NAME2 || '--'}}
      </van-cell>
      <van-cell center title="药物使用方法2">
        {{detailData.DRUG_USAGE2 || '--'}}
      </van-cell>
      <van-cell center title="药品名称">
        {{detailData.DRUG_NAME3 || '--'}}
      </van-cell>
      <van-cell center title="药物使用方法3">
        {{detailData.DRUG_USAGE3 || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="肾脏病变">
        {{detailData.SZBB || '--'}}
      </van-cell>
      <van-cell center title="心脏疾病">
        {{detailData.XZBB || '--'}}
      </van-cell>
      <van-cell center title="足部病变">
        {{detailData.ZBBB || '--'}}
      </van-cell>
      <van-cell center title="神经病变">
        {{detailData.SJBB || '--'}}
      </van-cell>
      <van-cell center title="视网膜病变">
        {{detailData.SWMBB || '--'}}
      </van-cell>
      <van-cell center title="脑血管病变">
        {{detailData.NXGBB || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.MEDICAL_ORG || '--'}}
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
      // 糖尿病患者管理登记表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm = {
      //   TREATMENT_ID: '0000d06409654284a2b426c7c2180efb',
      //   TYPE: 'PHS_DM_CARD'
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