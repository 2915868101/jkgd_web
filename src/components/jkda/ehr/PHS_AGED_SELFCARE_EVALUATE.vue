<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="进餐分数">
        {{detailData.MEAL_SCORE || '--'}}
      </van-cell>
      <van-cell center title="梳洗分数">
        {{detailData.GROOMING_SCORE || '--'}}
      </van-cell>
      <van-cell center title="穿衣分数">
        {{detailData.DRESSING_SCORE || '--'}}
      </van-cell>
      <van-cell center title="如厕分数">
        {{detailData.TOILET_SCORE || '--'}}
      </van-cell>
      <van-cell center title="活动分数">
        {{detailData.ACTIVITY_SCORE || '--'}}
      </van-cell>
      <van-cell center title="总评分">
        {{detailData.TOTAL_SCORE || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.DIAGNOSIS_PLACE || '--'}}
      </van-cell>
      <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_DOCTOR || '--'}}
      </van-cell>
      <van-cell center title="随访时间">
        {{detailData.FOLLOWUP_DATE || '--'}}
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
      // 老年人自理评估资料  
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
    },
    mounted() {}
  }
</script>
<style scoped>
  @import '../../../assets/css/jkda/jkda_detail.css';

  @media screen and (max-width: 320px) {}
</style>