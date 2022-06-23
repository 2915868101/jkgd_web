<template>
  <div class="jkzc_stjkzk jkzc_wj_box s-bgcfff f-fs14">
    <div class="f-tac f-fs18 f-pt30 f-pb20">身体健康状况自测</div>
    <div class="f-pl15 fpr15 s-c999 f-fs14 f-lh18">改善不良生活习惯，拥有健康生活方式</div>
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
        <li v-for="(q,i) in questions2" :key="i">
          <div v-if="questions.length==showIndex">
            <div class="f-que-tit f-cb">
              <span class="tag">问题 {{questions.length + i+1}}</span>
              <span class="tt">{{q.name}}</span>
            </div>
            <div class="f-que-items">
              <a href="javascript:" v-for="(l,j) in items2" :key="j" :class="[{checked:checked==j}]" @click="checkedItem(j,1)">{{l}}</a>
            </div>
          </div>
        </li>
        <li v-for="(q,i) in questions3" :key="i">
          <div v-if="questions.length+questions2.length==showIndex&&score2>0">
            <div class="f-que-tit f-cb">
              <span class="tag">问题 {{questions.length + questions2.length + i+1}}</span>
              <span class="tt">{{q.name}}</span>
            </div>
            <div class="f-que-items">
              <a href="javascript:" v-for="(l,j) in items3" :key="j" :class="[{checked:checked==j}]" @click="checkedItem(j,2)">{{l}}</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <h2 :class="'f-advice animated flipInX s-bgc'+color[style]" v-else>{{op}}<br><br>
      <van-button type="default" @click.native="retry" mini>重新测试</van-button>
    </h2>
    <div class="jkzc-tip-box">本测试依据自测健康评定级表,仅供参考</div>
  </div>
</template>
<script>
export default {
  name: 'jkzc_stjkzk',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('身体健康状况自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
  },
  computed: {},
  data() {
    return {
      questions: [{ name: '是否吸烟？', value: 3 }, { name: '是否酗酒？', value: 3 }, { name: '喜食腌制的食品？', value: 3 }, { name: '喜食油炸的食品？', value: 3 }, { name: '晚睡晚起？', value: 3 }, { name: '家族病史？', value: 3 }, { name: '生活不规律？', value: 3 }, { name: '每日运动低于30分钟？', value: 3 }, { name: '喜食甜食？', value: 3 }, { name: '喜食肥肉？', value: 3 }, { name: '精神郁闷？', value: 3 }, { name: '每日喝水少于6杯？', value: 3 }, { name: '喜食咸食？', value: 3 }, { name: '打麻将过度？', value: 3 }, { name: '很少吃蔬菜水果？', value: 3 }, { name: '是否便秘？', value: 5 }, { name: '是否眼花?', value: 5 }, { name: '是否耳聋?', value: 5 }, { name: '是否浑身无力?', value: 5 }, { name: '是否有老年斑?', value: 5 }, { name: '是否嗜睡?', value: 5 }, { name: '是否易感冒?', value: 5 }, { name: '是否易失眠?', value: 5 }, { name: '是否健忘?', value: 5 }, { name: '是否夜尿多?', value: 5 }, { name: '是否肢体憋胀?', value: 5 }, { name: '是否食欲差?', value: 5 }, { name: '是否肥胖?', value: 5 }, { name: '是否面色差?', value: 5 }, { name: '是否50岁前进入更年期?', value: 5 }, { name: '是否心慌胸闷?', value: 10 }, { name: '是否头晕恶心?', value: 10 }, { name: '是否头昏头痛?', value: 10 }, { name: '是否头胀舌麻?', value: 10 }, { name: '是否手足麻木?', value: 10 }, { name: '是否口齿不清?', value: 10 }, { name: '是否静脉曲张?', value: 10 }, { name: '是否胃寒体凉?', value: 10 }, { name: '是否会平地摔跤?', value: 10 }, { name: '是否有骨关节病（颈、腰椎病、关节炎）?', value: 10 }, { name: '是否有白内障?', value: 10 }, { name: '是否有哮喘?', value: 10 }, { name: '是否有龋齿?', value: 10 }, { name: '是否有高血压?', value: 20 }, { name: '是否有冠心病？', value: 20 }, { name: '是否有做过肿瘤手术?', value: 20 }, { name: '是否有脑中风后遗症?', value: 20 }, { name: '是否有心绞痛?', value: 20 }, { name: '是否有高血脂?', value: 20 }, { name: '是否有糖尿病?', value: 20 }, { name: '是否有前列腺炎?', value: 20 }, { name: '是否有动脉硬化?', value: 20 }, { name: '是否有高血粘?', value: 20 }, { name: '是否有高血糖?', value: 20 }, { name: '是否有脂肪肝?', value: 20 }, { name: '是否有脂肪肝?', value: 20 }],
      questions2: [{
        name: '是否有持续用药?'
      }],
      questions3: [{
        name: '用药种类?'
      }],
      items: ['没有', '有'],
      items2: ['没有', '持续服药 1 ~ 5 年', '持续服药 5 ~ 10 年', '持续服药 10 年以上'],
      items3: ['1 种', '2-3 种', '3 种以上'],
      color: ["31a1a0", "21b05b", "eeb52b", "cf4f39", "e4570a", "e52636"],
      showIndex: 0,
      score: 20,
      checked: null,
      flag: true,
      op: '',
      style: 1,
      score2: 0
    }
  },
  methods: {
    checkedItem: function(i, type) {
      var t = this;
      var len = t.questions.length + t.questions2.length + t.questions3.length
      if (t.flag && t.showIndex < len) {
        t.flag = false
        t.checked = i
        if (!type) {
          t.score += i
        } else {
          if (type == 1) {
            t.score2 = i;
            if (t.score2 == 0) {
              t.doNext(len)
            }
          }
          if (type == 2) {
            t.score2 = t.score2 * (i + 1)
          }
        }
        t.score += t.score2
        t.doNext(len)
      }
    },
    doNext: function(len) {
      var t = this;
      setTimeout(function() {
        t.checked = null;
        t.showIndex++;
        t.flag = true;
        if (t.showIndex == len) {
          t.rules(t.score);
        }
      }, 400)
    },
    rules() {
      var t = this
      t.op = ''
      if (t.score < 40) {
        t.op = '心脑血管病发病概率：正常人群'
        t.style = 1
      } else if (t.score >= 40 && t.score < 70) {
        t.op = '心脑血管病发病概率：轻度人群'
        t.style = 2
      } else if (t.score >= 70 && t.score < 100) {
        t.op = '心脑血管病发病概率：中度人群'
        t.style = 3
      } else if (t.score >= 100 && t.score < 140) {
        t.op = '心脑血管病发病概率：重度人群'
        t.style = 4
      } else if (t.score >= 140) {
        t.op = '心脑血管病发病概率：高危人群'
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
      t.score2 = 0
    }
  },
  mounted() {}
}
</script>
<style scoped>
@import '../../assets/css/util/animate.min.css';
@import '../../assets/css/jkzc/jkzc_style.css';
</style>