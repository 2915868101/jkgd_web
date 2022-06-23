<template>
  <div class="jkzc_xgzc jkzc_wj_box s-bgcfff f-fs14">
    <div v-if='isAction'>
      <div class="f-tac f-fs18 f-pt30 f-pb20">新冠自测</div>
      <div class="f-pl15 fpr15 s-c999 f-fs14 f-lh18">新冠状肺炎传播途径有：经呼吸道飞沫、接触传播</div>
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
      <div id="zysx" style="display:none; margin-left: 25px;"><img src="../../assets/img/jkzc/zysx.png"></div>
    </div>
    <div v-else>
      <div class="jkzc_bk_box">
        <!-- <img src="../../assets/img/jkzc/xin.png"> -->
        <div class="g-main-content">
          <p class="f-titOther">
            本评估参考的是国家卫生健康委员会最新版《新型冠状病毒感染的肺炎诊疗方案（试行第六版 ）》、国家卫建委发布的《新型冠状病毒防控指南（第一版）》、应急总医院发布的《新型冠状病毒感染的肺炎防控知识手册》、以及中国疾控中心发布的《居家隔离防控指南》等。
          </p>
          <p>
            为了保证本次评估的准确性，请如实填写。
          </p>
        </div>
      </div>
      <div class="f-pb10 f-pl15 f-pr15 f-pt30">
        <van-button type="primary" @click.native="doSubmit">开始评估</van-button>
      </div>
      <ul class="f-cb items f-pt30">
        <li v-for="(m,i) in itemsimg" :key="i" class="g-ms-32">
          <a href=javascript: @click="linkToOther(m.link)">
            <span class="othericon">
              <img :src='m.img_url'>
            </span>
            <span class="tt">{{m.title}}</span>
          </a>
        </li>
      </ul>
      <div class="bottominfo">
        <p>湖北加油！中国加油！</p>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'jkzc_xgzc',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.updateTitle('新冠性肺炎自测')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 1
  },
  computed: {},
  data() {
    return {
      questions: [{
        name: '您是否居住或途径湖北？',
        value: 5
      }, {
        name: '您是否居住或途径其他有本地病例传播区域（浙江、广东、湖南、河南、安徽、江西、重庆、北京等）？',
        value: 5
      }, {
        name: '您是否到过农贸市场、人流密集的场所或参与多人聚会？',
        value: 5
      }, {
        name: '您是否当面接触过新冠肺炎确诊病人？',
        value: 5
      }, {
        name: '您所在学校班级、住宅楼或工作部门是否有确诊病人？',
        value: 5
      }, {
        name: '您所乘坐的飞机、高铁、汽车、或轮船是否有确诊病人？',
        value: 5
      }, {
        name: '您是否接触过有发热的湖北居民或近14天到过湖北的人？',
        value: 5
      }, {
        name: '您是否接触过无症状的湖北居民或近14天到过湖北的人？',
        value: 5
      }, {
        name: '您是否有发热、乏力、干咳？',
        value: 5
      }, {
        name: '您是否有过慢性病？',
        value: 5
      }],
      items: ['没有', '有'],
      color: ["31a1a0", "21b05b", "eeb52b", "cf4f39", "e4570a", "e52636"],
      showIndex: 0,
      score: 0,
      checked: null,
      flag: true,
      op: "",
      style: 1,
      isAction: false,
      itemsimg: [{
        link: "https://ncov.dxy.cn/ncovh5/view/pneumonia",
        img_url: require("../../assets/img/jkzc/ss.png"),
        title: "实时疫情"
      }, {
        link: "https://h5.peopleapp.com/txcx/index.html",
        img_url: require("../../assets/img/jkzc/tx.png"),
        title: "同行查询"
      }, {
        link: "https://ncov.dxy.cn/ncovh5/view/pneumonia_rumors",
        img_url: require("../../assets/img/jkzc/yq.png"),
        title: "疫情辟谣"
      }]
    }
  },
  methods: {
    checkedItem: function(i, j) {
      var t = this;
      console.log(i);
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
      if (n <= 5) {
        t.op = '风险低；居家观察，尽量避免去公共场所，注意个人卫生'
        t.style = 1
      } else if (n > 5 && n < 11) {
        t.op = '风险中等；网络平台在线咨询，居家隔离；佩戴口罩，避免与其他人密切接触'
        t.style = 2
      } else {
        t.op = '风险高；佩戴口罩，避免与其他人密切接触；尽快前往最近定点医院发热门诊就诊'
        t.style = 5
      }
      document.getElementById("zysx").style.display = "block";
    },
    retry() {
      var t = this
      t.op = ''
      t.showIndex = 0
      t.checked = null
      t.score = 0
      t.style = 1
      document.getElementById("zysx").style.display = "none";
    },
    linkToOther(m) { 
      window.location = m;
    },
    doSubmit() {
      var t = this
      t.isAction = true;
    }
  },
  mounted() {}
}
</script>
<style scoped>
@import '../../assets/css/util/animate.min.css';
@import '../../assets/css/jkzc/jkzc_style.css';


.jkzc_xgzc ul li a .othericon {
  display: block;
  width: 50px;
  height: 50px;
  margin: 0 auto;
  overflow: hidden;
  position: relative;
  z-index: 2;
  top: 10px
}

.jkzc_xgzc ul li a .tt {
  display: block;
  text-align: center;
  color: #444;
  position: relative;
  z-index: 2;
  margin-top: 20px
}

.jkzc_xgzc ul .g-ms-32 {
  width: 32%;
  float: left;
}

.jkzc_bk_box .g-main-content .f-titOther {
  padding: 5px 0;
  text-align: justify;
}

.jkzc_xgzc .weui-btn {
  line-height: 60px;
}

.jkzc_xgzc .jkzc_bk_box .g-main-content {
  line-height: 1.5;
  color: #999999;
}

.jkzc_xgzc .bottominfo {
  position: absolute;
  bottom: 3px;
  padding: 0px;
  margin: auto;
  text-align: center;
  line-height: 20px;
  width: 100%;
  color: #f14019;
}

.jkzc_xgzc .bottominfo p {
  position: relative;
  margin: auto;
}
</style>