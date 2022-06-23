<template>
  <div class="jkzc_yjk jkzc_wj_box s-bgcfff f-fs14">
    <div class="f-tac f-fs18 f-pt30 f-pb20">亚健康自测</div>
    <div class="f-pl15 fpr15 s-c999 f-fs14 f-lh18">导致亚健康的主要原因有：饮食不合理、缺乏运动、作息不规律、睡眠不足、精神紧张、心理压力大、长期不良情绪等。</div>
    <div class="g-que-box animated zoomIn f-mt20" v-if="!op">
      <ul>
        <li v-for="(q,i) in questions" :key="i">
          <div v-if="i==showIndex">
            <div class="f-que-tit f-cb">
              <span class="tag">问题 {{i+1}}</span>
              <span class="tt">{{q.name}}</span>
            </div>
            <div class="f-que-items">
              <a href="javascript:" v-for="(l,j) in items" :key="j" :class="[{checked:checked==j}]" @click="checkedItem(j==0?j:q.value,j)">{{l}}</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <h2 :class="'f-advice animated flipInX s-bgc'+color[style]" v-else>{{op}}<br><br>
      <van-button type="default" @click.native="retry" mini>重新测试</van-button>
    </h2>
    <div class="jkzc-tip-box">亚健康测试依据亚健康状态自测量表,仅供参考</div>
  </div>
</template>
<script>
export default {
  name: 'jkzc_yjk',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('亚健康自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
  },
  computed: {},
  data() {
    return {
      questions: [{
        name: '早上起床时，有持续的头发丝掉落？',
        value: 5
      }, {
        name: '感到情绪有些抑郁，会对着窗外发呆？',
        value: 3
      }, {
        name: '昨天想好的某件事，今天怎么也记不起来了，而且近些天来，经常出现这种情况？',
        value: 10
      }, {
        name: '害怕走进办公室，觉得工作令人厌倦？',
        value: 5
      }, {
        name: '不想面对同事和上司，有自闭症式的渴望？',
        value: 5
      }, {
        name: '工作效率下降，上司已表达了对你的不满？',
        value: 5
      }, {
        name: '工作一小时后，就感到身体倦怠，胸闷气短？',
        value: 10
      }, {
        name: '工作情绪始终无法高涨。最令自己不解的是：无名的火气很大，但又没有精力发作？',
        value: 5
      }, {
        name: '一日三餐，进餐甚少，排除天气因素，即使口味非常适合自己的菜，近来也经常如嚼干蜡？',
        value: 5
      }, {
        name: '盼望早早地逃离办公室，为的是能够回家，躺在床上休息片刻？',
        value: 5
      }, {
        name: '对城市的污染、噪声非常敏感，比常人更渴望清幽、宁静的山水，休息身心。5分？',
        value: 5
      }, {
        name: '不再像以前那样热衷于朋友的聚会，有种强打精神、勉强应酬的感觉？',
        value: 2
      }, {
        name: '晚上经常睡不着觉，即使睡着了，又老是在做梦的状态中，睡眠质量很糟糕？',
        value: 10
      }, {
        name: '体重有明显的下降趋势，今天早上起来，发现眼眶深陷，下巴突出？',
        value: 10
      }, {
        name: '感觉免疫力在下降，春秋流感一来，自己首当其冲，难逃"流"运？',
        value: 5
      }, {
        name: '性能力下降，妻子（或丈夫）对你明显地表示了性要求，但你却经常感到疲惫不堪，没有什么性欲望。妻子（或丈夫）甚至怀疑你有外遇了？',
        value: 10
      }],
      items: ['没有', '有'],
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
    checkedItem: function(i, j) {
      var t = this;
      if (t.flag && t.showIndex < t.questions.length) {
        t.flag = false
        t.checked = j
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
      if (n <= 40) {
        t.op = '健康状况不错，继续保持哟！'
        t.style = 1
      } else if (n > 40 && n < 50) {
        t.op = '您的健康已经敲响警钟！'
        t.style = 2
      } else {
        t.op = '请坐下来，好好地反省自己的生活状态，加强锻炼和注重饮食营养的配搭！'
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