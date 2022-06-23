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
      <van-cell center title="孕次">
        {{detailData.GRAVIDITY || '--'}}
      </van-cell>
      <van-cell center title="产次">
        {{detailData.PARITY || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="入院日期">
        {{detailData.ADMISSION_DATE || '--'}}
      </van-cell>
      <van-cell center title="分娩孕周">
        {{detailData.REGISTER_GES || '--'}}
      </van-cell>
      <van-cell center title="胎数">
        {{detailData.BIRTH_NUM || '--'}}
      </van-cell>
      <van-cell center title="分娩时间">
        {{detailData.DELIVERY_TIM || '--'}}
      </van-cell>
      <van-cell center title="出血量">
        {{detailData.BLEEDING_QTY || '--'}}
      </van-cell>
      <van-cell center title="总产程">
        {{detailData.TOTAL_LABOR || '--'}}
      </van-cell>
      <van-cell center title="接产单位">
        {{detailData.CONFINEMENTS_ORG || '--'}}
      </van-cell>
      <van-cell center title="接产者">
        {{detailData.CONFINEMENTS_MAN || '--'}}
      </van-cell>
      <van-cell center title="产后访视时间">
        <span v-if="detailData.POSTPARTUM_VISIT_TIME">{{$moment(detailData.POSTPARTUM_VISIT_TIME).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
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
      // 孕产妇复检
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