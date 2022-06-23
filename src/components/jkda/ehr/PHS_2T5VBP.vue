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
      <van-cell center title="血糖筛查">
        {{detailData.GLUCOSE_SCREENING || '--'}}
      </van-cell>
      <van-cell center title="产前筛查">
        {{detailData.PRENATAL_SCREENING || '--'}}
      </van-cell>
      <van-cell center title="胎位">
        {{detailData.PF_CD || '--'}}
      </van-cell>
      <van-cell center title="血红蛋白值">
        {{detailData.HAEMO_VALUE || '--'}}
      </van-cell>
      <van-cell center title="宫底高度">
        {{detailData.TBOU_DISTANCE || '--'}}
      </van-cell>
      <van-cell center title="胎心率">
        {{detailData.FHR || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.REGISTER_UNIT || '--'}}
      </van-cell>
      <van-cell center title="建档人">
        {{detailData.CREATOR_NAME || '--'}}
      </van-cell>
      <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_DOCTOR || '--'}}
      </van-cell>
      <van-cell center title="随访日期">
        <span v-if="detailData.FOLLOWUP_DATE">{{$moment(detailData.FOLLOWUP_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
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
      // 孕产妇分娩
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