<template>
  <div class="jkzc_yyz jkzc_wj_box s-bgcfff f-fs14">
    <div class="f-tac f-fs18 f-pt30 f-pb20">抑郁症程度自测</div>
    <div class="f-pl15 fpr15 s-c999 f-fs14 f-lh18">抑郁症是青春期常见的情感性障碍，容易引起冲动、疲劳、忧郁、恐惧、内疚的念头，严重者导致自杀</div>
    <div class="g-que-box animated zoomIn f-mt20" v-if="!op">
      <ul>
        <li v-for="(q,i) in questions" :key="i">
          <div v-if="i==showIndex">
            <div class="f-que-tit f-cb">
              <span class="tag">问题 {{i+1}}</span>
              <span class="tt">{{q.name}}</span>
            </div>
            <div class="f-que-items">
              <a href="javascript:" v-for="(l,j) in items" :key="j" :class="[{checked:checked==j}]" @click="checkedItem(j)">{{l}}</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <h2 :class="'f-advice animated flipInX s-bgc'+color[style]" v-else>{{op}}<br><br>
      <van-button type="default" @click.native="retry" mini>重新测试</van-button>
    </h2>
    <div class="jkzc-tip-box">抑郁症测试依据抑郁自评量表,仅供参考</div>
  </div>
</template>
<script>
export default {
  name: 'jkzc_yyz',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('抑郁症程度自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
  },
  computed: {},
  data() {
    return {
      questions: [
        { name: "你是否一直感到伤心或悲哀？" },
        { name: "你是否感到前景渺茫？" },
        { name: "你是否觉得自己没有价值或自以为是一个失败者？" },
        { name: "你是否觉得力不从心或自叹比不上别人？" },
        { name: "你是否对任何事都自责？" },
        { name: "你是否在做决定时犹豫不决？" },
        { name: "这段时间你是否一直处于愤怒和不满状态？" },
        { name: "你对事业、家庭、爱好或朋友是否丧失了兴趣？" },
        { name: "你是否感到一蹶不振做事情毫无动力？" },
        { name: "你是否以为自己已衰老或失去魅力？" },
        { name: "你是否感到食欲不振或情不自禁地暴饮暴食？" },
        { name: "你是否患有失眠症或整天感到体力不支、昏昏欲睡？" },
        { name: "你是否丧失了对性的兴趣？" },
        { name: "你是否经常担心自己的健康？" },
        { name: "你是否认为生存没有价值，或生不如死？" }
      ],
      items: ["没有", "轻度", "中度", "严重"],
      color: ["31a1a0", "21b05b", "eeb52b", "cf4f39", "e4570a", "e52636"],
      showIndex: 0,
      score: 0,
      checked: null,
      flag: true,
      op: "",
      style: 1
    }
  },
  methods: {
    checkedItem(i) {
      var t = this;
      if (t.flag && t.showIndex < t.questions.length) {
        t.flag = false
        t.checked = i
        t.score += i
        setTimeout(function() {
          t.checked = null
          t.showIndex++
          t.flag = true
          if (t.showIndex == t.questions.length) {
            t.rules(t.score)
          }
        }, 400)
      }
    },
    rules(n) {
      var t = this
      t.op = ''
      if (n >= 0 && n <= 4) {
        t.op = '没有抑郁症'
        t.style = 1
      } else if (n >= 5 && n <= 10) {
        t.op = '偶尔有抑郁情绪'
        t.style = 2
      } else if (n >= 11 && n <= 20) {
        t.op = '有轻度抑郁症'
        t.style = 3
      } else if (n >= 21 && n <= 30) {
        t.op = '有中度抑郁症，需要立即到心理专科诊治';
        t.style = 4
      } else if (n >= 31 && n <= 45) {
        t.op = '有严重抑郁症，需要立即到心理专科诊治';
        t.style = 5
      }
    },
    retry() {
      var t = this
      t.op = ''
      t.showIndex = 0
      t.checked = null
      t.score = 0
      t.style = 1
    }
  },
  mounted() {}
}
</script>
<style scoped>
@import '../../assets/css/util/animate.min.css';
@import '../../assets/css/jkzc/jkzc_style.css';
</style>