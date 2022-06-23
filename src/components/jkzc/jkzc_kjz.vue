<template>
  <div class="jkzc_kjz jkzc_wj_box s-bgcfff f-fs14">
    <div class="f-tac f-fs18 f-pt30 f-pb20">恐惧症程度自测</div>
    <div class="f-pl15 fpr15 s-c999 f-fs14 f-lh18">恐惧症心理测试可以帮助患者测试是否有恐惧症倾向。社交恐惧症已经是排名第三的心理疾病，你可以通过以下的测试表测试一下你自己</div>
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
    <div class="jkzc-tip-box">恐惧症心理测试依据恐惧症自评量表,仅供参考</div>
  </div>
</template>
<script>
export default {
  name: 'jkzc_kjz',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('恐惧症程度自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
  },
  computed: {},
  data() {
    return {
      questions: [
        { name: '经常想到亲人会有不幸？' },
        { name: '有时担心会给自己或所爱的人带来伤害？' },
        { name: '经常检查灯和水龙头关好没有？' },
        { name: '在人群中受到推搡觉得反感？' },
        { name: '有洁癖，多次反复地刷洗衣服和家具，是否老洗手？' },
        { name: '是否老是对自己和自己所干的事不满意，尽管已经努力想干好？' },
        { name: '你是否总是尽量想提前离开有可能使你遭遇尴尬的境地？' },
        { name: '是否能轻易做出困难的决定？' },
        { name: '你是否觉得有一种做某种多余事的必要？' },
        { name: '经常觉得身上衣服有些不对劲？' },
        { name: '有过回家检查门窗是否锁好的情况吗？' },
        { name: '老舍不得扔掉已没用的旧东西？' },
        { name: '是否老在想一般都是不由自主做的事？' },
        { name: '是否有过老重复说同一句话或数一些没有必要数的东西的时候？' },
        { name: '睡觉前会把衣服整齐码好吗？' },
        { name: '你是否干一些不重要的事也很认真？' },
        { name: '你周围的东西是否随时都要放在同一个地方？' },
        { name: '会老做一些无足轻重的老动作吗？' }
      ],
      items: ['否', '是'],
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
      if (n < 5) {
        t.op = '没有恐惧症'
        t.style = 1
      } else if (n >= 5 && n < 10) {
        t.op = '患有轻度恐惧症'
        t.style = 2
      } else if (n >= 10 && n < 15) {
        t.op = '患有中度恐惧症'
        t.style = 3
      } else {
        t.op = '有患上潜伏性神经官能症恐惧的危险'
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