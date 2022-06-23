<template>
  <div class="jkzc_qpz jkzc_wj_box s-bgcfff f-fs14">
    <div class="f-tac f-fs18 f-pt30 f-pb20">强迫症程度自测</div>
    <div class="f-pl15 fpr15 s-c999 f-fs14 f-lh18">根据自己近一个月以上的情况和感觉，请您对下述问题如实进行作答</div>
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
    <div class="jkzc-tip-box">强迫症测试依据强迫症自测表,仅供参考</div>
  </div>
</template>
<script>
export default {
  name: 'jkzc_qpz',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('强迫症程度自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
  },
  computed: {},
  data() {
    return {
      questions: [
        { name: '我经常反复洗手而且洗手的时间很长，超过正常所必需？' },
        { name: '我有时不得不毫无理由地重复相同的内容、句子或数字好几次？' },
        { name: '我觉得自己穿衣、脱衣、清洗、走路时要遵循特殊的顺序？' },
        { name: '我常常没有必要的检查门窗、开关、煤气、钱物、文件、信件等？' },
        { name: '我不得不反复好多次作某些事情知道我认为自己已经做好为止？' },
        { name: '我对自己做的大多数事情都要产生怀疑？' },
        { name: '一些不愉快的想法常违背我的意愿进入我的大脑，常使我不能摆脱？' },
        { name: '我常常设想自己粗心大意或是细小的差错会引起灾难性的后果？' },
        { name: '我时常担心自己患了某病？' },
        { name: '我时常无原因的计数？' },
        { name: '在某些场合，我很害怕失去控制而做出尴尬的事？' },
        { name: '我经常迟到，因为我没有必要的花了很多时间重复做某些事情？' },
        { name: '当我看到刀、匕首和其他尖锐的物品时我会感到心烦意乱？' },
        { name: '我被要完全记住一些不重要的事情而困扰？' },
        { name: '有时我有毫无原因地想要破坏某些物品的念头，或伤害他人的冲动？' },
        { name: '在某些场合，即使当时我生病了，我也想暴食一顿？' },
        { name: '当我听到自杀、犯罪或生病时，我会心烦意乱很长时间，很难不去想它？' }
      ],
      items: ['否', '是'],
      color: ["31a1a0", "21b05b", "eeb52b", "cf4f39", "e4570a", "e52636"],
      showIndex: 0,
      score: 0,
      checked: null,
      flag: true,
      op: '',
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
      if (n < 5) {
        t.op = '没有强迫症'
        t.style = 1;
      } else if (n >= 5 && n < 8) {
        t.op = '有强迫症倾向'
        t.style = 2;
      } else if (n >= 8 && n < 10) {
        t.op = '有轻度强迫症'
        t.style = 3;
      } else if (n >= 10 && n < 12) {
        t.op = '有中度强迫症'
        t.style = 4;
      } else {
        t.op = '有严重强迫症'
        t.style = 5;
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