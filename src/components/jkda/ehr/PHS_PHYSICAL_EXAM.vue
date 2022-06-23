/* eslint-disable vue/no-parsing-error */
<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM||'--'}}
      </van-cell>
      <van-cell center title="体重(kg)">
        {{detailData.BODY_WEIGHT||'--'}}
      </van-cell>
      <van-cell center title="身高(cm)">
        {{detailData.HEIGHT||'--'}}
      </van-cell>
      <van-cell center title="体质指数">
        {{detailData.BMI||'--'}}
      </van-cell>
      <van-cell center title="腰围(cm) ">
        {{detailData.WAISTLINE ||'--'}}
      </van-cell>
      <van-cell center title="左侧血压">
        {{detailData.left_blood_pressure||'--'}}
      </van-cell>
      <van-cell center title="右侧血压 ">
        {{detailData.left_blood_pressure||'--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="心脏疾病">
        {{detailData.HEART_DISEASE||'--'}}
      </van-cell>
      <van-cell center title="血管疾病">
        {{detailData.VESSEL_DISEASE||'--'}}
      </van-cell>
      <van-cell center title="肾脏疾病">
        {{detailData.KIDNEY_DISEASE||'--'}}
      </van-cell>
      <!--  <van-cell center title="神经系统疾病标志">
        {{detailData.IS_NERVOUS_DISEASE||'--'}}
      </van-cell>
      <van-cell center title="其他辅助检查">
        {{detailData.IS_OTHER_DISEASE||'--'}}
      </van-cell> -->
      <div class="cell-item">
        <div class="tit">其他症状</div>
        <div class="cont">{{detailData.SYMPTOM ||'--'}}</div>
      </div>
      <div class="cell-item">
        <div class="tit">健康评价：</div>
        <div class="cont">{{detailData.ABNOMAL_DESC || '--'}}</div>
      </div>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="检查日期">
        <span v-if="detailData.EXAM_DATE">{{$moment(detailData.EXAM_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="负责医生">
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
      // 健康体检
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