<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="OAE初筛结果">
        {{detailData.PRELIMINARY_OAE}}
      </van-cell>
      <van-cell center title="OAE复筛结果">
        {{detailData.SECOND_OAE||'--'}}
      </van-cell>
      <van-cell center title="甲状腺功能初筛结果">
        {{detailData.PRELIMINARY_HYPOTHYROIDISM}}
      </van-cell>
      <van-cell center title="甲状腺功能复筛结果">
        {{detailData.SECOND_HYPOTHYROIDISM||'--'}}
      </van-cell>
      <van-cell center title="PKU初筛结果">
        {{detailData.PRELIMINARY_PKU}}
      </van-cell>
      <van-cell center title="PKU复筛结果">
        {{detailData.SECOND_PKU||'--'}}
      </van-cell>
      <van-cell center title="听力筛查诊断">
        {{detailData.HEARING_SCREENING_DIAGNOSIS||'--'}}
      </van-cell>
      <van-cell center title="PKU苯丙酮尿症诊断">
        {{detailData.PH_DIAGNOSIS||'--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="创建人">
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
      // 新生儿疾病筛查
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'00362186bd9948d99f96071daffcccd6',
      //   TYPE:'PHS_NEONATAL_DISEASE_SCREENING'
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