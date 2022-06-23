<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="父亲姓名">
        {{detailData.FATHER_NAME}}
      </van-cell>
      <van-cell center title="母亲姓名">
        {{detailData.MOTHER_NAME}}
      </van-cell>
      <van-cell center title="助产机构">
        {{detailData.DE_ORG_NAME}}
      </van-cell>
      <van-cell center title="新生儿出生体重">
        {{detailData.BIRTH_BODY_WEIGHT}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="黄疸部位">
        {{detailData.HDBW}}
      </van-cell>
      <van-cell center title="喂养方式">
        {{detailData.WYFS}}
      </van-cell>
      <van-cell center title="眼外观检查异常标志">
        <span v-if="detailData.EYE_TF=='1'">未见异常</span>
        <span v-else-if="detailData.EYE_TF=='2'">异常</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="四肢活动度异常标志">
        <span v-if="detailData.LIMBS_TF=='1'">未见异常</span>
        <span v-else-if="detailData.LIMBS_TF=='2'">异常</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="耳外观检查异常标志">
        <span v-if="detailData.EAR_TF=='1'">未见异常</span>
        <span v-else-if="detailData.EAR_TF=='2'">异常</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="鼻检查异常标志">
        <span v-if="detailData.NOSE_TF=='1'">未见异常</span>
        <span v-else-if="detailData.NOSE_TF=='2'">异常</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="心肺听诊异常标志">
        <span v-if="detailData.HEART_LUNG_TF=='1'">未见异常</span>
        <span v-else-if="detailData.HEART_LUNG_TF=='2'">异常</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="腹部触诊异常标志">
        <span v-if="detailData.BELLY_TF=='1'">未见异常</span>
        <span v-else-if="detailData.BELLY_TF=='2'">异常</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="脊柱检查异常标志">
        <span v-if="detailData.BACKBONE_TF=='1'">未见异常</span>
        <span v-else-if="detailData.BACKBONE_TF=='2'">异常</span>
        <span v-else>--</span>
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="随访日期">
        {{detailData.FOLLOWUP_DATE || '--'}}
      </van-cell>
      <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_DOCTOR || '--'}}
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
      // 产后新生儿访视
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'1b3ed9c69bb54c7cba747032922b05e7',
      //   TYPE:'PHS_NABV'
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