<template>
  <div class="jkzc_ytw s-bgcfff f-fs14">
    <div class="f-tac f-fs18 f-pt30 f-pb30">腰臀围比值是衡量肥胖的重要标准！</div>
    <van-cell title="性别" class="cell-item">
      <div class="radio f-cb">
        <span class="g-ms-6 f-tac" @click="checkedSex(1)">
          <span :class="['icon',{active:sex==1}]"></span> 男
        </span>
        <span class="g-ms-6 f-tal" @click="checkedSex(2)">
          <span :class="['icon',{active:sex==2}]"></span> 女
        </span>
      </div>
    </van-cell>
    <van-cell title="腰围" class="cell-item">
      <div class="f-df f-dfj">
        <div class="m-range">
          <van-slider v-model="waistValue" button-size="18px" :min="40" :max="200" bar-height="5"></van-slider>
        </div>
        <span>{{waistValue}} cm</span>
      </div>
    </van-cell>
    <van-cell title="臀围" class="cell-item">
      <div class="f-df f-dfj">
        <div class="m-range">
          <van-slider v-model="hipValue" button-size="18px" :min="40" :max="200" bar-height="5"></van-slider>
        </div>
        <span>{{hipValue}} cm</span>
      </div>
    </van-cell>
    <van-cell title="您的腰臀围比值"><span :class="'s-c'+color[style]">{{ytw}}</span></van-cell>
    <van-cell title="结果"><span :class="'s-c'+color[style]">{{advice}}</span></van-cell>
    <div class="f-tag f-pt10 f-pl10 f-pb10 f-pr10 f-fs14 f-lh18 s-c999">男性腰围大于等于 90 厘米为肥胖，女性腰围大于等于 80 厘米为肥胖。</div>
    <div class="jkzc-tip-box">腰臀围比值测试分类依据世界卫生组织标准,仅供参考</div>
  </div> 
</template>
<script>

export default {
  name: 'jkzc_ytw',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('腰臀围自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
  },
  computed: {
    ytw() {
      var t = this
      return (t.waistValue / t.hipValue).toFixed(1)
    },
    advice() {
      var t = this
      var advice = ''
      if (t.sex == 1) {
        if (t.ytw <= 1) {
          t.style = 1
          advice = '正常'
        } else {
          t.style = 5
          advice = '高'
        }
      } else {
        if (t.ytw <= 0.8) {
          t.style = 1
          advice = '正常'
        } else {
          t.style = 5
          advice = '高'
        }
      }
      return advice
    }
  },
  data() {
    return {
      sex: 1,
      waistValue: 85,
      hipValue: 85,
      color: ["31a1a0", "21b05b", "eeb52b", "cf4f39", "e4570a", "e52636"],
      style: 1
    }
  },
  methods: {
    checkedSex(id) {
      var t = this
      t.sex = id
    }
  },
  mounted() {}
}
</script>
<style scoped>
@import '../../assets/css/util/animate.min.css';
@import '../../assets/css/jkzc/jkzc_style.css';

.jkzc_ytw .cell-item>>>.van-cell__title {
  flex: 4;
}

.jkzc_ytw .cell-item>>>.van-cell__value {
  flex: 20;
  align-items: center;
}
</style>