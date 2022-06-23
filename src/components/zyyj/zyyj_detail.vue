<template>
  <div class="zyyj_detail g-wrap s-bgcfff">
    <van-cell-group class="s-bgcfff">
      <van-cell title="就诊人:">
        {{costData.APPLY_NUM || '--'}}
      </van-cell>
      <van-cell title="医院名称:">
        {{costData.EXAM_DOCTOR_NAME1 || '--'}}
      </van-cell>
      <van-cell title="住院号:">
        {{costData.EXAM_DOCTOR_NAME1 || '--'}}
      </van-cell>
      <van-cell title="主治医生:">
        {{costData.EXAM_DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell title="住院科室:">
        {{costData.EXAM_DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell title="入院时间:">
        {{costData.SAMPLE_TYPE_NAME || '--'}}
      </van-cell>
      <van-cell title="账户余额:">
        <div class="s-cf30b32">{{costData.SAMPLE_TYPE_NAME || '--'}}</div>
      </van-cell>
    </van-cell-group> 
    <div class="g-wp100 s-bgcfff f-pt40 f-pb15">
      <van-button size="small" round color="#00c792" @click="linkToPage('zyyj_money')">下一步</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'zyyj_detail',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('住院详情')
    // t.getData()
  },
  data() {
    return {
      costData: {
        APPLY_NUM: '',
        EXAM_DOCTOR_NAME1: '池州市人民医院',
        EXAM_DOCTOR_NAME: '骨科',
        SAMPLE_TYPE_NAME: '2020-02-02 12:21:00', 
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

.zyyj_detail>>>.van-cell {
  padding: 15px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
}

.zyyj_detail>>>.van-cell__title {
  color: #606266; 
  font-size: 14PX;
}

.zyyj_detail>>>.van-cell__value {
  color: #303133; 
  font-size: 15px; 
}

.zyyj_detail>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
} 

.zyyj_detail>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  font-size:15PX;
  padding: 0 10px;
}
</style>