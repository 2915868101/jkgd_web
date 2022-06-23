<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="建卡日期">
        <span v-if="detailData.CARD_DATE">{{$moment(detailData.CARD_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="收缩压">
        {{Number(detailData.SBP).toFixed(2) || '--'}}
      </van-cell>
      <van-cell center title="舒张压">
        {{Number(detailData.DBP).toFixed(2) || '--'}}
      </van-cell>
      <van-cell center title="疾病类别">
        {{detailData.FJ || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="药品名称">
        {{detailData.DRUG_NAME1 || '--'}}
      </van-cell>
      <van-cell center title="药品名称">
        {{detailData.DRUG_NAME2 || '--'}}
      </van-cell>
      <van-cell center title="药品名称">
        {{detailData.DRUG_NAME3 || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="脑血管疾病">
        {{detailData.NXGJB || '--'}}
      </van-cell>
      <van-cell center title="心脏疾病">
        {{detailData.XZJB || '--'}}
      </van-cell>
      <van-cell center title="肾脏疾病">
        {{detailData.SZJB || '--'}}
      </van-cell>
      <van-cell center title="眼睛疾病">
        {{detailData.YJJB || '--'}}
      </van-cell>
      <van-cell center title="病情症状">
        {{detailData.BQMS || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.ORG_NAME || '--'}}
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
      // 高血压患者管理登记表
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