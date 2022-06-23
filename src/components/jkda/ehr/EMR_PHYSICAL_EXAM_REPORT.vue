<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group v-if="detailData.ksxj">
      <van-cell center title="姓名">
        {{detailData.ksxj[0].XM||'--'}}
      </van-cell>
      <van-cell center title="体检时间">
        {{detailData.ksxj[0].EXAM_DATE||'--'}}
      </van-cell>
      <van-cell center title="体检机构">
        {{detailData.ksxj[0].ORG_NAME||'--'}}
      </van-cell>
      <div class="cell-item" v-if="detailData.tjxm">
        <div class="tit">体检评估：</div>
        <div class="cont">
          {{detailData.tjxm[0].EXAM_OVERVIEW||'--'}}
        </div>
      </div>
    </van-cell-group>
    <van-collapse class="s-bgcfff f-mt15" v-model="activeNames" accordion>
      <van-collapse-item title="体检项目" name="1">
        <van-cell center :title="c.ITEM_NAME" v-for="(c,i) in detailData.ksxj" :key="i">
          {{c.RESULT||'--'}}
        </van-cell>
      </van-collapse-item>
      <van-collapse-item title="科室小结" name="2">
        <van-cell center :title="c.DEP_NAME" v-for="(c,i) in detailData.tjxm" :key="i">
          {{c.EXAM_ADVICE||'--'}}
        </van-cell>
      </van-collapse-item>
    </van-collapse>
  </div>
</template>
<script>
  export default {
    name: 'jkda_detail',
    components: {},
    computed: {},
    created() {
      var t = this
      // 体检报告详情
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm = {
      //   TREATMENT_ID: '456660090202005280817',
      //   TYPE: 'EMR_PHYSICAL_EXAM_REPORT'
      // }
      t.getDetailData()
    },
    data() {
      return {
        detailData: [],
        activeNames: '1'
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
            console.log(data)
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