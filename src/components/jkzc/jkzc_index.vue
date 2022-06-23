<template>
  <div class="jkzc_index f-fs14">
    <ul class="m-menus-box f-cb">
      <li v-for="(m,i) in menus" :key="i" class="g-ms-4">
        <a href="javascript:" @click="linkToPage(m.link)">
          <span class="icon" :style="{'background-position':'center '+(-(m.icon-1)*40)+'px'}"></span>
          <span class="tt">{{m.title}}</span>
        </a>
      </li>
    </ul>
    <div class="m-items-box">
      <div>健康自测八项指标</div>
      <ul class="f-cb items">
        <li v-for="(m,i) in items" :key="i" class="g-ms-3">
          <a href=javascript: @click="linkToPage(m.link)">
            <span class="icon"><i class="img" :style="{'background-position':'center '+(-(m.icon-1)*38)+'px'}"></i></span>
            <span class="tt">{{m.title}}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jkzc_index',
    directives: {},
    components: {},
    created() {
      var t = this
      t.updateTitle('健康评估')
      t.$store.state.showFooter = false
      t.$parent.$parent.activeTabIndex = 1
    },
    data() {
      return {
        menus: [{
          link: "/jkzc_bmi",
          title: "BMI 自测",
          icon: "1"
        }, {
          link: "/jkzc_ytw",
          title: "腰臀围自测",
          icon: "2"
        }, {
          link: "/jkzc_ycq",
          title: "预产期自测",
          icon: "4"
        }, {
          link: "/jkzc_yyz",
          title: "抑郁症自测",
          icon: "4"
        }, {
          link: "/jkzc_yjk",
          title: "亚健康自测",
          icon: "5"
        }, {
          link: "/jkzc_kjz",
          title: "恐惧症自测",
          icon: "6"
        }, {
          link: "/jkzc_stjkzk",
          title: "身体健康状况",
          icon: "7"
        }, {
          link: "/jkzc_xljk",
          title: "心理健康自测",
          icon: "8"
        }, {
          link: "/jkzc_qpz",
          title: "强迫症自测",
          icon: "9"
        }],
        items: [{
          link: "/jkzc_tw",
          title: "体温",
          icon: "1"
        }, {
          link: "/jkzc_mb",
          title: "脉搏",
          icon: "2"
        }, {
          link: "/jkzc_hx",
          title: "呼吸",
          icon: "3"
        }, {
          link: "/jkzc_xyzl",
          title: "血液质量",
          icon: "4"
        }, {
          link: "/jkzc_tz",
          title: "体重",
          icon: "5"
        }, {
          link: "/jkzc_ys",
          title: "饮食",
          icon: "6"
        }, {
          link: "/jkzc_js",
          title: "精神",
          icon: "7"
        }, {
          link: "/jkzc_sm",
          title: "睡眠",
          icon: "8"
        }]
      }
    },
    methods: {},
    mounted() {}
  }
</script>
<style scoped>
  @import '../../assets/css/jkzc/jkzc_index.css';
</style>