<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="建案日期">
        <span v-if="detailData.BUILD_CASE_DATE">{{$moment(detailData.BUILD_CASE_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="结案日期">
        <span v-if="detailData.CLOSED_CASE_DATE">{{$moment(detailData.CLOSED_CASE_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="家长姓名">
        {{detailData.PARENT_NAME || '--'}}
      </van-cell>
      <van-cell center title="结案描述">
        {{detailData.CLOSED_DESC || '--'}}
      </van-cell>
      <van-cell center title="结案状态 ">
        {{detailData.JAZT || '--'}}
      </van-cell>
      <van-cell center title="结案原因 ">
        {{detailData.JAYY || '--'}}
      </van-cell>
      <van-cell center title="体弱儿童类型 ">
        {{detailData.TRLX || '--'}}
      </van-cell>
      <van-cell center title="转归 ">
        {{detailData.ZG || '--'}}
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
      // 体弱儿专项卡
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'d47d287b1dd440e9926dfddaed3342fd',
      //   TYPE:'PHS_FRAIL_CHILD_CARD'
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