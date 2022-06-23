<template>
  <div class="mbyyjl g-wrap s-bgcfff g-hp100">
    <div v-if="detailData.length"> 
      <div class="cell-item flex-wrap f-mt10">
        <div class="f-df8 s-c303133 f-ml10">药名</div>
        <div class="f-df8 s-c303133">用法</div>
        <div class="f-df8 s-c303133">提取时间</div>
      </div>
      <div class="cell-item" v-for="(c,i) in detailData" :key="i">
        <div class="flex-wrap" v-if="c.DRUG_NAME">
          <div class="f-df8">{{c.DRUG_NAME||'-'}}</div>
          <div class="f-df8">{{c.DOSAGE}}</div>
          <div class="f-df8">{{c.EXTRACTION_TIME}}</div>
        </div> 
      </div>
    </div>
    <van-empty v-else description="无用药记录" /> 
  </div>
</template>
<script>
export default {
  name: 'mbyyjl',
  components: {
  },
  computed: {},
  created() {
    var t = this
    // 慢病用药记录
    t.updateTitle(t.queryData.pageTitle)
    t.searchForm = t.queryData
    // t.searchForm = {
    //   TREATMENT_ID: '8e35a1443bf847de9a82fc9b8027f98f',
    //   TYPE: 'PHS_TUMOR_VISIT'
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
        serverId: 'WXYYJL01',
        url: t.serverPath2,
        params: t.searchForm,
        success: function(data) {
          t.detailData = data[0].CONTENTS ||[]
          console.log(t.detailData)
        }
      });
    },
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}
.mbyyjl .cell-item {
  padding: 18px 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
  background: #fff;
}
.mbyyjl .flex-wrap {
  display: flex;
  justify-content: space-between;
  text-align: center;
  color: #606266;
  font-size: 14px;
}

.mbyyjl .flex-wrap .f-df8:nth-child(1) {
  text-align: left;
}
</style>