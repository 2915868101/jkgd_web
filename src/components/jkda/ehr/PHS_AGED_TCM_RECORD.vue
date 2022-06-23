<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="精力充沛分数">
        {{detailData.FULL_ENERGY_SCORE || '--'}}
      </van-cell>
      <van-cell center title="容易疲乏分数">
        {{detailData.EAZY_TIRED_SCORE || '--'}}
      </van-cell>
      <van-cell center title="呼吸短促分数">
        {{detailData.SHORT_BREATH_SCORE || '--'}}
      </van-cell>
      <van-cell center title="声音无力分数">
        {{detailData.SOUND_WEAK_SCORE || '--'}}
      </van-cell>
      <van-cell center title="手脚发凉分数">
        {{detailData.HAND_FOOT_COLD_SCORE || '--'}}
      </van-cell>
      <van-cell center title="胃脘部、背部或腰膝部怕冷分数">
        {{detailData.CRAW_BACK_WAIST_COLD_SCORE || '--'}}
      </van-cell>
      <van-cell center title="耐受寒冷分数">
        {{detailData.COLD_RESISTANT_SCORE || '--'}}
      </van-cell>
      <van-cell center title="感冒分数">
        {{detailData.INFLUENZA_SCORE || '--'}}
      </van-cell>
      <van-cell center title="过敏分数">
        {{detailData.ALLERGY_SCORE || '--'}}
      </van-cell>
      <van-cell center title="口干咽燥分数">
        {{detailData.ALLERGY_SCORE || '--'}}
      </van-cell>
      <van-cell center title="腹部肥大分数">
        {{detailData.PHYSOGASTRY_SCORE || '--'}}
      </van-cell>
      <van-cell center title="舌苔厚腻分数">
        {{detailData.TONGUE_THICK_SCORE || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="气虚质分数">
        {{detailData.QI_ASTHENIA_SCORE || '--'}}
      </van-cell>
      <van-cell center title="气虚质状态">
        {{detailData.QI_ASTHENIA_STATUS || '--'}}
      </van-cell>
      <van-cell center title="阳虚质分数">
        {{detailData.YANG_ASTHENIA_SCORE || '--'}}
      </van-cell>
      <van-cell center title="阴虚质分数">
        {{detailData.YIN_ASTHENIA_SCORE || '--'}}
      </van-cell>
      <van-cell center title="痰湿质分数">
        {{detailData.PHLEGM_DAMPNESS_SCORE || '--'}}
      </van-cell>
      <van-cell center title="湿热质分数">
        {{detailData.DAMP_HEAT_SCORE || '--'}}
      </van-cell>
      <van-cell center title="血瘀质分数">
        {{detailData.BLOOD_STASIS_SCORE || '--'}}
      </van-cell>
      <van-cell center title="气郁质分数">
        {{detailData.QI_STAGNATION_SCORE || '--'}}
      </van-cell>
      <van-cell center title="特禀质分数">
        {{detailData.ALLERGIC_SCORE || '--'}}
      </van-cell>
      <van-cell center title="平和质分数">
        {{detailData.YIN_YANG_HARMONY_SCORE || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.DIAGNOSIS_PLACE || '--'}}
      </van-cell>
      <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_DOCTOR || '--'}}
      </van-cell>
      <van-cell center title="随访时间">
        {{detailData.FOLLOWUP_DATE || '--'}}
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
      // 老年人中医药保健  
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'00012f9e168f49ae99eb2b1be0ae68fb',
      //   TYPE:'PHS_AGED_TCM_RECORD'
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