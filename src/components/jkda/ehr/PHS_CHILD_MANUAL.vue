<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="出生日期">
        {{detailData.BIRTH_DATE||'--'}}
      </van-cell>
      <van-cell center title="分娩地点">
        {{detailData.CHILDBIRTH_ADDR || '--'}}
      </van-cell>
      <van-cell center title="分娩孕周">
        {{detailData.CHILDBIRTH_WEEK || '--'}}
      </van-cell>
      <van-cell center title="监护人姓名">
        {{detailData.GUARDIAN || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="体重">
        {{detailData.BODY_WEIGHT || '--'}}
      </van-cell>
      <van-cell center title="身长">
        {{detailData.HEIGHT || '--'}}
      </van-cell>
      <van-cell center title="听力筛查">
        {{detailData.HEARING_SCREENING_RESULT || '--'}}
      </van-cell>
      <van-cell center title="出生缺陷">
        {{detailData.BIRTH_DEFECTS || '--'}}
      </van-cell>
      <van-cell center title="新生儿其他并发症">
        {{detailData.OTHER_NEONATAL_COMPLICATION || '--'}}
      </van-cell>
      <van-cell center title="体弱儿情况">
        {{detailData.FRAIL_INFANTS_SITUATION || '--'}}
      </van-cell>
      <van-cell center title="体弱儿原因">
        {{detailData.FRAIL_INFANTS_REASON || '--'}}
      </van-cell>
      <van-cell center title="卡介苗接种">
        {{detailData.BCG_VACCINATE || '--'}}
      </van-cell>
      <van-cell center title="乙肝疫苗接种">
        {{detailData.HEPATITISB_VACCINATE || '--'}}
      </van-cell>
      <van-cell center title="其他疫苗接种">
        {{detailData.OTHER_VACCINATE || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.REGISTER_UNIT || '--'}}
      </van-cell>
      <van-cell center title="建档人">
        {{detailData.CREATOR_NAME || '--'}}
      </van-cell>
      <!-- <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_DOCTOR || '--'}}
      </van-cell> -->
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
      // 儿童保健手册
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
            console.log(data)
            t.detailData = data[0]
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