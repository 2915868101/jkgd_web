<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="初产年龄">
        {{detailData.PRIMIPAROUS_AGE || '--'}}
      </van-cell>
      <van-cell center title="孕次">
        {{detailData.GRAVIDITY || '--'}}
      </van-cell>
      <van-cell center title="宫颈备注">
        {{detailData.CERVIX_REMARK || '--'}}
      </van-cell>
      <van-cell center title="分泌物备注">
        {{detailData.SECRETIONS_REMARK || '--'}}
      </van-cell>
      <van-cell center title="阴道备注">
        {{detailData.VAGINA_REMARK || '--'}}
      </van-cell>
      <van-cell center title="外阴备注">
        {{detailData.VULVA_REMARK || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="宫体位置">
        {{detailData.GTWZ || '--'}}
      </van-cell>
      <van-cell center title="宫体大小">
        {{detailData.GTDX || '--'}}
      </van-cell>
      <van-cell center title="宫体硬度">
        {{detailData.GTYD || '--'}}
      </van-cell>
      <van-cell center title="宫体活动">
        {{detailData.GTHD || '--'}}
      </van-cell>
      <van-cell center title="宫体表面">
        {{detailData.GTBM || '--'}}
      </van-cell>
      <van-cell center title="评分分数">
        {{detailData.FILING_SCORE || '--'}}
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
      // 妇女病普查登记表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'8b66ad4a24454ab1b0811884213e4d4f',
      //   TYPE:'PHS_WOMEN_CENSUS_REG'
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