<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="姓名">
        {{detailData.XM}}
      </van-cell>
      <!-- <van-cell center title="末次出院日期">
        {{detailData.OUTPAT_DATE || '--'}}
      </van-cell> 
      <van-cell center title="服药不良反应描述">
        {{detailData.SIDE_EFFECT || '--'}}
      </van-cell>
      <van-cell center title="转入医疗机构名称">
        {{detailData.TRANSFER_DEPT || '--'}}
      </van-cell> 
      <van-cell center title="目前症状其他">
        {{detailData.OTHER_CURRENT_SYMPTOM || '--'}}
      </van-cell>
      <van-cell center title="康复措施其他">
        {{detailData.OTHER_REHABILITATION_MEASURE || '--'}}
      </van-cell>
      <van-cell center title="转诊原因">
        {{detailData.TRANSFER_REASON || '--'}}
      </van-cell>
    </van-cell-group>    
    <van-cell-group class="f-mt10">  -->
      <van-cell center title="个人生活料理代码">
        <span v-if="detailData.PERSONAL_LIFE_DISHES_CD=='1'">良好</span>
        <span v-else-if="detailData.PERSONAL_LIFE_DISHES_CD=='2'">一般</span>
        <span v-else-if="detailData.PERSONAL_LIFE_DISHES_CD=='3'">较差</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="家庭劳动代码">
        <span v-if="detailData.HOUSE_WORK_CD=='1'">良好</span>
        <span v-else-if="detailData.HOUSE_WORK_CD=='2'">一般</span>
        <span v-else-if="detailData.HOUSE_WORK_CD=='3'">较差</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="生产劳动代码">
        <span v-if="detailData.PRODUCTIVE_LABORWORK_CD=='1'">良好</span>
        <span v-else-if="detailData.PRODUCTIVE_LABORWORK_CD=='2'">一般</span>
        <span v-else-if="detailData.PRODUCTIVE_LABORWORK_CD=='3'">较差</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="学习能力代码">
        <span v-if="detailData.LEARNING_ABILITY_CD=='1'">良好</span>
        <span v-else-if="detailData.LEARNING_ABILITY_CD=='2'">一般</span>
        <span v-else-if="detailData.LEARNING_ABILITY_CD=='3'">较差</span>
        <span v-else>--</span>
      </van-cell>
      <van-cell center title="社会人际交往代码">
        <span v-if="detailData.INTERPERSONAL_COMMUNICATION_CD=='1'">良好</span>
        <span v-else-if="detailData.INTERPERSONAL_COMMUNICATION_CD=='2'">一般</span>
        <span v-else-if="detailData.INTERPERSONAL_COMMUNICATION_CD=='3'">较差</span>
        <span v-else>--</span>
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="轻度滋事次数">
        {{detailData.MILD_TROUBLE_TIMES || '--'}}
      </van-cell>
      <van-cell center title="肇事次数">
        {{detailData.ACCIDENT_TIMES || '--'}}
      </van-cell>
      <van-cell center title="肇祸次数">
        {{detailData.DISASTER_TIMES || '--'}}
      </van-cell>
      <van-cell center title="自伤数">
        {{detailData.SELF_WOUNDING_TIMES || '--'}}
      </van-cell>
      <van-cell center title="自杀未遂次数">
        {{detailData.ATTEMPTED_SUICIDE_TIMES || '--'}}
      </van-cell>
      <van-cell center title="对家庭社会影响">
        {{detailData.SOCIETY_AFFECT || '--'}}
      </van-cell>
      <van-cell center title="不良反应">
        {{detailData.BLFY || '--'}}
      </van-cell>
      <van-cell center title="目前症状">
        {{detailData.MQZZ || '--'}}
      </van-cell>
      <van-cell center title="自知力评价">
        {{detailData.ZZLPJ || '--'}}
      </van-cell>
      <van-cell center title="服药依从性">
        {{detailData.YCX || '--'}}
      </van-cell>
      <van-cell center title="康复措施">
        {{detailData.KFCS || '--'}}
      </van-cell>
      <van-cell center title="随访分类-">
        {{detailData.SFFL || '--'}}
      </van-cell>
      <van-cell center title="是否转诊">
        {{detailData.SFZZ || '--'}}
      </van-cell>
      <van-cell center title="危险等级">
        {{detailData.WXDJ || '--'}}
      </van-cell>
      <van-cell center title="关锁情况">
        {{detailData.GSQK || '--'}}
      </van-cell>
      <van-cell center title="住院情况">
        {{detailData.ZYQK || '--'}}
      </van-cell>
      <van-cell center title="治疗效果">
        {{detailData.ZLXG || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt10">
      <van-cell center title="建档机构">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
      <van-cell center title="随访医生">
        {{detailData.FOLLOWUP_DOCTOR || '--'}}
      </van-cell>
      <van-cell center title="随访日期">
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
      // 重性精神病随访记录
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      // t.searchForm ={
      //   TREATMENT_ID:'3dcf1931018941299370f5aefed61721',
      //   TYPE:'PHS_MENTAL_VISIT'
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