<template>
  <div class="jkzc_bmi s-bgcfff f-fs14">
    <div class="f-tac f-fs18 f-pt30 f-pb30">快看看自己的 BMI 是否在理想范围吧！</div>
    <van-cell title="身高" class="cell-item" center >
    <div class="f-df f-dfj">
      
      <div class="m-range">
        <van-slider v-model="heightValue" button-size="18px" :min="40" :max="240" bar-height="5"></van-slider>
      </div>
      <span>{{heightValue}} cm</span>
    </div>
    </van-cell>
    <van-cell title="体重" class="cell-item" center >
    <div class="f-df f-dfj">
      
      <div class="m-range">
        <van-slider v-model="weightValue" button-size="18px" :min="2" :max="240" bar-height="5"></van-slider>
      </div>
      <span>{{weightValue}} kg</span>
    </div>
    </van-cell>
    <van-cell  title="您的 BMI 指数"><span :class="'s-c'+color[style]">{{bmi}}</span></van-cell>
    <van-cell title="身体状况"><span :class="'s-c'+color[style]">{{state[style]}}</span></van-cell>
    <h2 :class="'f-advice animated flipInX s-bgc'+color[style]">{{advice}}</h2>

    <div class="jkzc-tip-box">BMI测试分类依据中国参考标准,仅供参考</div>
  </div>
</template>
<script>
export default {
  name: 'jkzc_bmi',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('BMI自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
  },
  computed: {
    bmi() {
      var t = this
      return (t.weightValue / (t.heightValue / 100 * (t.heightValue / 100))).toFixed(1)
    },
    advice() {
      var t = this
      var advice = ''
      if (t.bmi < 18.5) {
        t.style = 0
        advice = '要增加点营养了，别熬夜！'
      } else if (t.bmi >= 18.5 && t.bmi < 24) {
        t.style = 1
        advice = '身材不错，要保持哦！'
      } else if (t.bmi >= 24 && t.bmi < 28) {
        t.style = 2
        advice = '还行，要有好的生活习惯哟！'
      } else if (t.bmi >= 28 && t.bmi < 30) {
        t.style = 3
        advice = '应该多运动运动了！'
      } else if (t.bmi >= 30 && t.bmi < 40) {
        t.style = 4
        advice = '再不减肥就危险了！'
      } else {
        t.style = 5
        advice = '您太胖了，必须努力减肥了！'
      }
      return advice
    }
  },
  data() {
    return {
      heightValue: 175,
      weightValue: 60,
      state: ["偏瘦", "正常", "过重", "轻度肥胖", "中度肥胖", "重度肥胖"],
      color: ["31a1a0", "21b05b", "eeb52b", "cf4f39", "e4570a", "e52636"],
      style: 1
    }
  },
  methods: {

  },
  mounted() { 
  }
}

</script>
<style scoped>
@import '../../assets/css/util/animate.min.css';
@import '../../assets/css/jkzc/jkzc_style.css';
.jkzc_bmi .cell-item>>>.van-cell__title{
  flex:4;
}
.jkzc_bmi .cell-item>>>.van-cell__value{
  flex:20; 
  align-items: center;
}
</style>
