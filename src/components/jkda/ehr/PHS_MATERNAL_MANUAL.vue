<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="孕产妇">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="预产期">
        <span v-if="detailData.ECB_DATE">{{$moment(detailData.ECB_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="孕早期情况">
        {{detailData.EARLY_PREGNANCY_STATUS || '--'}}
      </van-cell>
      <van-cell center title="建册孕周">
        {{detailData.REGISTER_GES || '--'}}
      </van-cell>
      <van-cell center title="末次月经">
        {{detailData.LAST_MENSTRUATION_DATE || '--'}}
      </van-cell>
      <van-cell center title="孕次">
        {{detailData.GRAVIDITY || '--'}}
      </van-cell>
      <van-cell center title="产次">
        {{detailData.PARITY || '--'}}
      </van-cell>
      <van-cell center title="个人史">
        {{detailData.SELF_HISTORY || '--'}}
      </van-cell>
      <van-cell center title="既往疾病史">
        {{detailData.PAST_HISTORY || '--'}}
      </van-cell>
      <van-cell center title="妇科手术史">
        {{detailData.OP_HIS || '--'}}
      </van-cell>
      <van-cell center title="家庭史">
        {{detailData.FAMILY_HISTORY || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="现高危因素">
        {{detailData.RISK_FACTOR_NAME || '--'}}
      </van-cell>
      <van-cell center title="现高危评分">
        {{detailData.RISK_SCORE || '--'}}
      </van-cell>
      <van-cell center title="B超检查">
        {{detailData.B_ULTRASOUND || '--'}}
      </van-cell>
      <van-cell center title="外阴检查结果">
        {{detailData.VULVA_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="阴道检查结果">
        {{detailData.VAGINA_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="宫颈检查结果">
        {{detailData.CERVIX_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="子宫检查结果">
        {{detailData.UTERUS_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="附件检查结果">
        {{detailData.ANNEX_TEST_RESULT || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.REGISTER_UNIT || '--'}}
      </van-cell>
      <van-cell center title="建档人">
        {{detailData.CREATOR_NAME || '--'}}
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
      // 孕产妇保健手册和初检记录
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
      toInfo(val) {
        console.log(val)
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @import '../../../assets/css/jkda/jkda_detail.css';

  @media screen and (max-width: 320px) {}
</style>