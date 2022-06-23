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
      <van-cell center title="依从性">
        {{detailData.YCX || '--'}}
      </van-cell>
      <van-cell center title="遵从医嘱行为">
        {{detailData.ZYXW || '--'}}
      </van-cell>
      <van-cell center title="并发症">
        {{detailData.COMPLICATION || '--'}}
      </van-cell>
      <van-cell center title="转诊原因">
        {{detailData.TRANSFER_REASON || '--'}}
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
      // 高血压患者随访表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'979ec688d2d0435686e7bd600c4566d0',
      //   TYPE:'PHS_HCV'
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
            console.log(data)
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