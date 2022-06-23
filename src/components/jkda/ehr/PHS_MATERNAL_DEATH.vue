<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="孕次">
        {{detailData.GRAVIDITY || '--'}}
      </van-cell>
      <van-cell center title="产次">
        {{detailData.PARITY || '--'}}
      </van-cell>
      <van-cell center title="引产">
        {{detailData.ABORTION || '--'}}
      </van-cell>
      <van-cell center title="产前检查">
        {{detailData.PRENATAL_EXAM || '--'}}
      </van-cell>
      <van-cell center title="产检次数">
        {{detailData.CHECK_NUM || '--'}}
      </van-cell>
      <van-cell center title="分娩方式">
        {{detailData.CHILDBIRTH_TYPE || '--'}}
      </van-cell>
      <van-cell center title="分娩日期">
        {{detailData.CHILDBIRTH_DATE || '--'}}
      </van-cell>
      <van-cell center title="分娩地点">
        {{detailData.CHILDBIRTH_PLACE || '--'}}
      </van-cell>
      <van-cell center title="接产者">
        {{detailData.CONFINEMENTS_MAN || '--'}}
      </van-cell>
      <van-cell center title="接产单位">
        {{detailData.CONFINEMENTS_ORG || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="死亡时间">
        {{detailData.DEATH_TIME || '--'}}
      </van-cell>
      <van-cell center title="死亡地点">
        {{detailData.DEATH_PLACE || '--'}}
      </van-cell>
      <van-cell center title="死亡诊断">
        {{detailData.DEATH_DIAGNOSIS || '--'}}
      </van-cell>
      <van-cell center title="死亡诊断2">
        {{detailData.DEATH_DIAGNOSIS2 || '--'}}
      </van-cell>
      <van-cell center title="死亡诊断3">
        {{detailData.DEATH_DIAGNOSIS3 || '--'}}
      </van-cell>
      <van-cell center title="诊断依据">
        {{detailData.DIAGNOSIS_BASIS || '--'}}
      </van-cell>
      <van-cell center title="接生方法">
        {{detailData.MIDWIFE_METHOD || '--'}}
      </van-cell>
      <van-cell center title="死因分类">
        {{detailData.DEATH_CAUSE || '--'}}
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
      // 孕妇死亡记录表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'8c7c98ce043140869272a211e03b087c',
      //   TYPE:'PHS_MATERNAL_DEATH'
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