<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="填表时间">
        <span v-if="detailData.DIAGNOSIS_DATE">{{$moment(detailData.DIAGNOSIS_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="收缩压">
        {{Number(detailData.SBP).toFixed(2) || '--'}}
      </van-cell>
      <van-cell center title="舒张压">
        {{Number(detailData.DBP).toFixed(2) || '--'}}
      </van-cell>
      <!-- <van-cell center title="神经功能缺失评分">
  {{detailData.NEUROLOGICAL_DEFICIT_SCORE || '--'}}
</van-cell> -->
      <van-cell center title="评分分数">
        {{detailData.FILING_SCORE || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="并发症">
        {{detailData.COMPLICATING || '--'}}
      </van-cell>
      <van-cell center title="意识障碍状态">
        {{detailData.YSZT || '--'}}
      </van-cell>
      <van-cell center title="颅内神经症">
        {{detailData.SJZ || '--'}}
      </van-cell>
      <van-cell center title="眼症">
        {{detailData.YJ || '--'}}
      </van-cell>
      <van-cell center title="血常规">
        {{detailData.XCG || '--'}}
      </van-cell>
      <van-cell center title="血生化">
        {{detailData.XSH || '--'}}
      </van-cell>
      <van-cell center title="血糖">
        {{detailData.XT || '--'}}
      </van-cell>
      <van-cell center title="血脂">
        {{detailData.XZ || '--'}}
      </van-cell>
      <van-cell center title="肝功能">
        {{detailData.GGN || '--'}}
      </van-cell>
      <van-cell center title="肾功能">
        {{detailData.SGN || '--'}}
      </van-cell>
      <van-cell center title="语言能力">
        {{detailData.YYNL || '--'}}
      </van-cell>
      <van-cell center title="诊断结果">
        {{detailData.ZDJG || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.DIAGNOSIS_PLACE || '--'}}
      </van-cell>
      <van-cell center title="建档人">
        {{detailData.DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell center title="建档时间">
        {{detailData.DIAGNOSIS_DATE || '--'}}
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
      // 脑卒中患者管理登记表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // 脑卒中患者随访表
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm = {
      //   TREATMENT_ID: '9caa0a99b41f496b910643b0376639f9',
      //   TYPE: 'PHS_STROKE_CARD'
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