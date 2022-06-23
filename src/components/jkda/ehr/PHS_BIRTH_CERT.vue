<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}} ({{detailData.SEX}})
      </van-cell>
      <van-cell center title="出生日期">
        {{detailData.BIRTH_DATE || '--'}}
      </van-cell>
      <van-cell center title="体重">
        {{detailData.BODY_WEIGHT || '--'}}
      </van-cell>
      <van-cell center title="身高">
        {{detailData.BODY_HEIGHT || '--'}}
      </van-cell>
      <van-cell center title="出生地">
        {{detailData.BIRTH_PLACE || '--'}}
      </van-cell>
      <van-cell center title="分娩孕周">
        {{detailData.CHILDBIRTH_WEEK || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="母亲姓名">
        {{detailData.MOTHER_NAME || '--'}}
      </van-cell>
      <van-cell center title="母亲年龄">
        {{detailData.MOTHER_AGE || '--'}}
      </van-cell>
      <van-cell center title="母亲国籍">
        {{detailData.MOTHER_COUNTRY || '--'}}
      </van-cell>
      <van-cell center title="父亲姓名">
        {{detailData.FATHER_NAME || '--'}}
      </van-cell>
      <van-cell center title="父亲年龄">
        {{detailData.FATHER_AGE || '--'}}
      </van-cell>
      <van-cell center title="父亲国籍">
        {{detailData.FATHER_COUNTRY || '--'}}
      </van-cell>
      <van-cell center title="签证机构">
        {{detailData.CERTIFY_ORG || '--'}}
      </van-cell>
      <van-cell center title="接生机构">
        {{detailData.BIRTHS_ATTENDED_ORG || '--'}}
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
      // 出生證明
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