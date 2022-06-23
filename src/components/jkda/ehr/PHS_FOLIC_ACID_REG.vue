<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="怀孕时间">
        {{detailData.PREGNANT_DATE || '--'}}
      </van-cell>
      <van-cell center title="末次月经时间">
        {{detailData.LAST_MENSTRUATION_DATE || '--'}}
      </van-cell>
      <van-cell center title="是否怀孕">
        {{detailData.SFHY || '--'}}
      </van-cell>
      <van-cell center title="缺陷名称">
        {{detailData.DEFECT_NAME || '--'}}
      </van-cell>
      <van-cell center title="评估">
        {{detailData.PG || '--'}}
      </van-cell>
      <van-cell center title="备注">
        {{detailData.REMARK || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="发放日期1">
        {{detailData.ISSUE_DATE1 || '--'}}
      </van-cell>
      <van-cell center title="发放数1">
        {{detailData.ISSUE_QTY1 || '--'}}
      </van-cell>
      <van-cell center title="是否服用1">
        {{detailData.SFFY1 || '--'}}
      </van-cell>
      <van-cell center title="是否规范1">
        {{detailData.SFGF1 || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="发放日期2">
        {{detailData.ISSUE_DATE2 || '--'}}
      </van-cell>
      <van-cell center title="发放数2">
        {{detailData.ISSUE_QTY2 || '--'}}
      </van-cell>
      <van-cell center title="是否服用2">
        {{detailData.SFFY2 || '--'}}
      </van-cell>
      <van-cell center title="是否规范2">
        {{detailData.SFGF2 || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
      <van-cell center title="建档人">
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
      // 发放叶酸记录表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'d1e7b6cdd33f4bddb9f516177b36e636',
      //   TYPE:'PHS_FOLIC_ACID_REG'
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