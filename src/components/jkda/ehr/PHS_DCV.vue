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
      <van-cell center title="空腹血糖">
        {{detailData.FBS_VALUE || '--'}}
      </van-cell>
      <van-cell center title="糖化血红蛋白值">
        {{detailData.SHB_VALUE || '--'}}
      </van-cell>
      <van-cell center title="糖化血红蛋白检查日期">
        {{detailData.TEST_DATE || '--'}}
      </van-cell>
      <van-cell center title="总胆固醇值">
        {{detailData.CHOL_VALUE || '--'}}
      </van-cell>
      <van-cell center title="目前症状">
        {{detailData.MQZZ || '--'}}
      </van-cell>
      <van-cell center title="随访结果评价">
        {{detailData.SFPJ || '--'}}
      </van-cell>
      <van-cell center title="慢病用药记录" is-link @click="linkToPage('mbyyjl',searchForm)"></van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
      <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_EMPLOY || '--'}}
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
      // 糖尿病患者随访表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm = {
      //   TREATMENT_ID: '0000b2d136254060b884297981d44088',
      //   TYPE: 'PHS_DCV'
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