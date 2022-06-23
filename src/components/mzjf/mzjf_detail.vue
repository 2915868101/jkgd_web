<template>
  <div class="mzjf_detail g-wrap">
    <van-cell-group class="f-bb10">
      <van-cell title="就诊人:">
        {{costData.APPLY_NUM || '--'}}
      </van-cell>
      <van-cell title="医院名称:">
        {{costData.EXAM_DOCTOR_NAME1 || '--'}}
      </van-cell>
      <van-cell title="就诊科室:">
        {{costData.EXAM_DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell title="就诊时间:">
        {{costData.SAMPLE_TYPE_NAME || '--'}}
      </van-cell>
    </van-cell-group>
    <div class="tit-item"><i class="icon"></i>项目明细</div>
    <div class="cell-item flex-wrap">
      <div class="f-df10 s-c303133 f-ml10">药品名称</div>
      <div class="f-df7 s-c303133">数量</div>
      <div class="f-df7 s-c303133">价格</div>
    </div>
    <template v-if="costData.RESULT">
      <div class="cell-item" v-for="(c,i) in costData.RESULT" :key="i">
        <div class="flex-wrap">
          <div class="f-df10">{{c.ANTIBIOTIC_NAME||'-'}}
          </div>
          <div class="f-df7">{{c.BACSTA_CONCEN||'-'}}</div>
          <div class="f-df7">{{c.DRUG_ALLERGY_NAME||'-'}}</div>
        </div>
      </div>
      <div class="cell-item total">
        <div class="flex-wrap f-dfj">
          <div class="f-df10">合计金额</div>
          <div class="f-df7 s-cf30b32">￥155.00</div>
        </div>
      </div>
    </template>
    <div class="g-wp100 s-bgcfff f-pt15 f-pb15">
      <van-button size="small" round color="#00c792" @click="linkToPage('mzjf_jfcg')">确认缴费</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'mzjf_detail',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle('费用详情')
  },
  data() {
    return {
      costData: {
        APPLY_NUM: '',
        EXAM_DOCTOR_NAME1: '池州市人民医院',
        EXAM_DOCTOR_NAME: '骨科',
        SAMPLE_TYPE_NAME: '2020-02-02 12:21:00',
        RESULT: [
          { ANTIBIOTIC_NAME: '5%葡萄糖注射/250ml*12.5g /p… ', BACSTA_CONCEN: '1支', DRUG_ALLERGY_NAME: '¥106.00' },
          { ANTIBIOTIC_NAME: '5%葡萄糖注射/250ml*12.5g /p… ', BACSTA_CONCEN: '1支', DRUG_ALLERGY_NAME: '¥106.00' },
          { ANTIBIOTIC_NAME: '盐酸二甲双胍肠溶片 ', BACSTA_CONCEN: '1支', DRUG_ALLERGY_NAME: '¥106.00' },
          { ANTIBIOTIC_NAME: '5%葡萄糖注射/250ml*12.5g /p… ', BACSTA_CONCEN: '1支', DRUG_ALLERGY_NAME: '¥106.00' },
          { ANTIBIOTIC_NAME: '盐酸二甲双胍肠溶片 ', BACSTA_CONCEN: '1支', DRUG_ALLERGY_NAME: '¥106.00' },
        ]
      }
    }
  },
  methods: {
    getData() {
      var t = this
      t.ajax({
        serverId: 'BMFW00018',
        url: t.serverPath2,
        params: {
          id: t.queryData.id
        },
        success: function(data) {
          t.costData = data[0].CONTENTS ? data[0].CONTENTS[0] : []
          console.log(t.costData)
        }
      })
    },
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.mzjf_detail>>>.van-cell {
  padding: 15px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
}

.mzjf_detail>>>.van-cell__title {
  color: #606266;
  flex: 8;
  font-size: 14PX;
}

.mzjf_detail>>>.van-cell__value {
  color: #303133;
  text-align: left;
  font-size: 15px;
  flex: 16;
}

.mzjf_detail>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.mzjf_detail .tit-item {
  background: #fff;
  padding: 20px 15px;
  font-size: 15px;
  color: #303133;
  font-weight: bold;
  align-items: center;
  display: flex;
}

.mzjf_detail .tit-item .icon {
  height: 15PX;
  width: 4px;
  border-radius: 5px;
  margin-right: 8px;
  background: #00c792;
  display: inline-block;
}

.mzjf_detail .cell-item {
  padding: 18px 15px;
  font-size: 14PX;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
  background: #fff;
}

.mzjf_detail .cell-item.total {
  font-weight: bold;
}

.mzjf_detail .flex-wrap.cell-item {
  background: #fdfdfd;
}

.mzjf_detail .flex-wrap {
  text-align: center;
  color: #606266;
  display: flex;
  justify-content: space-between;
}

.mzjf_detail .flex-wrap .f-df10 {
  text-align: left;
  width: 60%;
}

.mzjf_detail .flex-wrap .f-df7 {
  text-align: right;
  width: 20%;
}

.mzjf_detail>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  font-size: 15PX;
  padding: 0 10px;
}
</style>