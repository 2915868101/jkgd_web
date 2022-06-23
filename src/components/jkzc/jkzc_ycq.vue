<template>
  <div class="jkzc_ycq s-bgcfff f-fs14">
    <div class="f-tac f-fs18 f-pt30 f-pb30">根据月经周期可以准确计算出预产期！</div>
    <van-cell title="平均月经周期" is-link>
      
    </van-cell>
    <van-cell title="您的末次月经时间" is-link>
      
    </van-cell>
      <!-- <popup-picker title="平均月经周期" :data="aveLists" v-model="aveCycle"></popup-picker>
      <datetime v-model="lastDate" :start-date="computeDate(-365)" :end-date="formatTime().substring(0,10)" format="YYYY-MM-DD" title="您的末次月经时间" year-row="{value}年" month-row="{value}月" day-row="{value}日" confirm-text="完成" cancel-text="取消" :display-format="formatValue"></datetime> -->
    
    <h2 class="f-advice s-bgce4570a animated flipInX">
      预产期：{{formatterDate(state)}}<br><br>
      距离宝宝出生还有 <span class=fate>{{fate}}</span> 天
    </h2>
    <div class="f-tag f-pt10 f-pl10 f-pb10 f-pr10 f-fs14 f-lh18 s-c999">如果孕妇的末次月经日期记忆不清或月经不准，就需要医生代为测算预产期。</div>
  </div>
</template>
<script>
export default {
  name: 'jkzc_ycq',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('预产期自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
    var _now = t.formatTime()
    t.lastDate = _now.substring(0, 10)
  },
  computed: {
    aveLists() {
      var arr = [
        []
      ]
      for (var i = 20; i <= 45; i++) {
        arr[0].push(i + ' 天')
      }
      return arr
    },
    fate() {
      var t = this
      if (t.state) {
        return t.getFate(t.state)
      } else { 
        return ''
      }
    }
  },
  data() {
    return {
      aveCycle: ['30 天'],
      state: '',
      lastDate: '',
      num: 280
    }
  },
  methods: {
    formatterDate(date) {
      var t = this
      var a = date ? new Date(date) : new Date()
      var Y = a.getFullYear()
      var M = t.formatNumber(a.getMonth() + 1)
      var D = t.formatNumber(a.getDate())
      return Y + " 年 " + M + " 月 " + D + " 日"
    },
    addOrSubDate(date, day) {
      var t = this
      var a = date ? new Date(date) : new Date()
      a = a.valueOf()
      a = a + day * 24 * 60 * 60 * 1000
      t.state = a
    },
    getFate(date) {
      return parseInt(Math.abs(new Date(date) - new Date()) / (1000 * 60 * 60 * 24))
    },
    getState() {
      var t = this
      var a = Number(String(t.aveCycle).substring(0, 2))
      if (a <= 26) {
        t.num = 273
      } else if (a > 26 && a < 33) {
        t.num = 280
      } else {
        t.num = 287
      }
      t.addOrSubDate(t.lastDate, t.num)
    },
    formatValue() {
      var t = this
      t.getState()
      return t.formatterDate(t.lastDate)
    }
  },
  mounted() {}
}
</script>
<style scoped>
@import '../../assets/css/util/animate.min.css';
@import '../../assets/css/jkzc/jkzc_style.css';
</style>