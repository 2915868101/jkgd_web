<template>
  <div class="jkzc_xljk jkzc_wj_box s-bgcfff f-fs14">
    <div class="f-tac f-fs18 f-pt30 f-pb20">心理健康自测</div>
    <div class="f-pl15 fpr15 s-c999 f-fs14 f-lh18">帮助你了解自己的心理健康程度，使你更清楚的认识自己</div>
    <div class="g-que-box animated zoomIn f-mt20" v-if="!op">
      <ul>
        <li v-for="(q,i) in questions" :key="i">
          <div v-if="i==showIndex">
            <div class="f-que-tit f-cb">
              <span class="tag">问题 {{i+1}}</span>
              <span class="tt">{{q.name}}</span>
            </div>
            <div class="f-que-items">
              <a href="javascript:" v-for="(l,j) in items" :key="j" :class="[{checked:checked==j}]" @click="checkedItem(j,i)">{{l}}</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <h2 :class="'f-advice animated flipInX s-bgc'+color[style]" v-else>{{op}}<br><br>
      <van-button type="default" @click.native="retry" mini>重新测试</van-button>
    </h2>
    <div class="jkzc-tip-box">心理健康自测依据心理卫生综合评定量表,仅供参考</div>
  </div>
</template>
<script>
export default {
  name: 'jkzc_xljk',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('心理健康自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
  },
  computed: {},
  data() {
    return {
      questions: [
        { name: '心情总是闷闷不乐，情绪善变？' },
        { name: '老是担心门没锁好，电源可能有问题，因而多次检查，甚至走了好远还拐回来看看？' },
        { name: '虽未曾患过恶性疾病，却一直担心会不会染上什么严重的病？' },
        { name: '容易脸红，害怕站在高处，害怕当众发言？' },
        { name: '由于关心呼吸和心脏跳动的情况而难以入睡？' },
        { name: '每天总是多次洗手，认为公用电话不洁，而不敢使用？' },
        { name: '总是担心"这样做是否顺利？"，以致无法放手去做？' },
        { name: '有些奇怪的观念总是出现在脑海，明知这些念头很无聊，却又无法摆脱？' },
        { name: '离开家门时，如果不从某只脚开始走，心里总是不安。改变床附近的东西就无法入睡？' },
        { name: '尽管四周的人在欢乐的取闹，自己却觉着没有什么意思？' },
        { name: '外界的东西犹如影子一般朦胧，见到的东西无法清晰的回忆出来？' },
        { name: '总觉着父母或亲友最近对自己太冷漠，或者不知为什么总是很反感或产生强烈的孤独感？' },
        { name: '心中无端的产生"这个世界正趋于灭亡，新的世界即将开始。"的感觉？' },
        { name: '总觉着有人在注意、凝视自己或追赶自己？' },
        { name: '有时会产生被人左右或事不由己的感觉？' },
        { name: '常自言自语或暗自发笑？' },
        { name: '虽然没人却总觉着有声音，晚上睡觉时总觉着有人进入了房间？' },
        { name: '遭遇失败或与同学不和谐时，会很敏感的觉着"我被人嘲笑"？' },
        { name: '当自己的权利受到侵害时拼死力争？' },
        { name: '当东西丢掉时，便不由自主的想到"大概是某某偷去的"，当受到老师的批评时，立即会想到"一定是某某告密的"？' }
      ],
      items: ['不是', '无法确定', '是'],
      color: ["31a1a0", "21b05b", "eeb52b", "cf4f39", "e4570a", "e52636"],
      showIndex: 0,
      score: 0,
      checked: null,
      flag: true,
      op: '',
      style: 1,
      A: 0,
      B: 0,
      C: 0
    }
  },
  methods: {
    checkedItem(i, n) {
      var t = this;
      console.log(i, n)
      if (t.flag && t.showIndex < t.questions.length) {
        t.flag = false
        t.checked = i
        t.score += i
        if (n < 11) {
          t.A += i
        } else if (n < 17 && n >= 11) {
          t.B += i
        } else {
          t.C += i
        }
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
    rules() {
      var t = this
      t.op = ''
      var g = t.A + t.B
      if (g < 5) {
        t.op = '你的心理非常健康，神经也非常正常'
        t.style = 1
      } else if (g >= 5 && g < 8) {
        t.op = '你的心理健康情况一般，可算是一个很正常的人'
        t.style = 0
      } else if (g >= 8 && g < 11) {
        t.op = '你的神经有些疲倦，要设法减轻压力， 进行娱乐以调节生活而放松精神'
        t.style = 2
      }
      if (t.A >= 11) {
        t.op = '你可能会有神经衰弱的倾向，你需要关心一下自己的健康了'
        t.style = 3
      } else {
        t.op = '你的神经有些疲倦，要设法减轻压力， 进行娱乐以调节生活而放松精神'
        t.style = 2
      }
      if (t.B >= 11) {
        t.op = '你有预防精神分裂的必要了，最好是请心理辅导老师进行辅导，早些预防'
        t.style = 4
      } else {
        t.op = '你的神经有些疲倦，要设法减轻压力， 进行娱乐以调节生活而放松精神'
        t.style = 2
      }
      if (t.C >= 4) {
        t.op = '你有强烈的妄想倾向，最好是尽早的请老师进行辅导'
        t.style = 5
      } else {
        t.op = '你的神经有些疲倦，要设法减轻压力， 进行娱乐以调节生活而放松精神'
        t.style = 2
      }
    },
    retry() {
      var t = this
      t.op = ''
      t.showIndex = 0
      t.checked = null
      t.score = 0
      t.style = 1
      t.A = 0
      t.B = 0
      t.C = 0
    }
  },
  mounted() {}
}
</script>
<style scoped>
@import '../../assets/css/util/animate.min.css';
@import '../../assets/css/jkzc/jkzc_style.css';
</style>