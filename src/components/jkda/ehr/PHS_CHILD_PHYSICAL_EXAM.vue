<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="年龄">
        {{detailData.MONTHS_AGE||'--'}}
      </van-cell>
      <van-cell center title="体重">
        {{detailData.BODY_WEIGHT || '--'}}
      </van-cell>
      <van-cell center title="体重评价">
        {{detailData.WEIGHT_EVALUATE || '--'}}
      </van-cell>
      <van-cell center title="身高">
        {{detailData.BODY_LENGTH || '--'}}
      </van-cell>
      <van-cell center title="身高评价">
        {{detailData.LENGTH_EVALUATE || '--'}}
      </van-cell>
      <van-cell center title="头尾">
        {{detailData.HCF || '--'}}
      </van-cell>
      <van-cell center title="体格发育评价">
        {{detailData.PHYSICAL_GROWTH || '--'}}
      </van-cell>
      <van-cell center title="面色检查结果">
        {{detailData.COMPLEXION_TEST_RESULT || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="眼外观检查">
        {{detailData.EYE_ASPECT_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="耳外观检查">
        {{detailData.EAR_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="听力检测">
        {{detailData.AUDI_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="视力检查">
        {{detailData.VISION_TEST_RESULT || '--'}}
      </van-cell>
      <van-cell center title="前囟横径(cm)">
        {{detailData.BREGAM_TRANS_DIA || '--'}}
      </van-cell>
      <van-cell center title="前囟纵径(cm)">
        {{detailData.BREGAM_VERT_DIA || '--'}}
      </van-cell>
      <van-cell center title="前囟闭合标识">
        {{detailData.BREGAM_CLOSE_MARK || '--'}}
      </van-cell>
      <van-cell center title="颈部包块检查结果">
        {{detailData.NECK_MASS_TEST_RESULT || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.REGISTER_UNIT || '--'}}
      </van-cell>
      <van-cell center title="建档人">
        {{detailData.CREATOR_NAME || '--'}}
      </van-cell>
      <!-- <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_DOCTOR || '--'}}
      </van-cell> -->
      <van-cell center title="随访日期">
        <span v-if="detailData.FOLLOWUP_DATE">{{$moment(detailData.FOLLOWUP_DATE).format('YYYY-MM-DD')}}</span>
        <span v-else>--</span>
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
      // 儿童体检
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
            console.log(data)
            t.detailData = data[0]
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