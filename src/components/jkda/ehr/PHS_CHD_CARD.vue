<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="填表时间">
        <span v-if="detailData.FILING_DATE">{{$moment(detailData.FILING_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="收缩压">
        {{Number(detailData.SBP).toFixed(2) || '--'}}
      </van-cell>
      <van-cell center title="舒张压">
        {{Number(detailData.DBP).toFixed(2) || '--'}}
      </van-cell>
      <van-cell center title="心音">
        {{detailData.HEART_SOUNDS || '--'}}
      </van-cell>
      <van-cell center title="心率">
        {{detailData.HEART_RATE || '--'}}
      </van-cell>
      <van-cell center title="心律">
        {{detailData.HEART_RHYTHM || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="胸前区不适">
        {{detailData.XQQ || '--'}}
      </van-cell>
      <van-cell center title="心肌肌钙蛋白">
        {{detailData.XJJGDB || '--'}}
      </van-cell>
      <van-cell center title="肌红蛋白">
        {{detailData.JHDB || '--'}}
      </van-cell>
      <van-cell center title="心肌酶">
        {{detailData.XJM || '--'}}
      </van-cell>
      <van-cell center title="诊断结果"> {{detailData.ZDJG || '--'}} </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.DIAGNOSIS_PLACE || '--'}}
      </van-cell>
      <van-cell center title="建档人">
        {{detailData.DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell center title="建档时间">
        {{detailData.DIAGNOSIS_DATE || '--'}}
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
      // 冠心病患者管理登记表
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