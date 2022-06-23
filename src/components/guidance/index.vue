<template>
  <div class="guidance"> 
    <div class="nav-wrap f-bs3" v-if="showPic">
      <div :class="[showPic==1||showPic==-1?'on':'','nav-item']" @click="handler(0)">男性</div>
      <div :class="[showPic==2||showPic==-2?'on':'','nav-item']" @click="handler(1)">女性</div>
      <div :class="[showPic==3||showPic==-3?'on':'','nav-item']" @click="handler(2)">儿童</div>
    </div>
    <div class="switch_btn f-fs14" @click="switchImg">正反切换</div>
    <transition name="fade">
      <div v-if="showPic==1||showPic==-1" class="guide_map">
        <div :class="['man1_box',{front:showPic==1,back:showPic==-1}]">
          <div class="svg_man_1" @click="showLists(1)"></div>
          <div class="svg_man_2_l" @click="showLists(5)"></div>
          <div class="svg_man_2_r" @click="showLists(5)"></div>
          <div class="svg_man_3_1" @click="showLists(2)"></div>
          <div class="svg_man_3_2" @click="showLists(3)"></div>
          <div class="svg_man_4" @click="showLists(4)"></div>
          <div class="svg_man_5_l" @click="showLists(6)"></div>
          <div class="svg_man_5_r" @click="showLists(6)"></div>
        </div>
        <div :class="['man2_box',{front:showPic==-1,back:showPic==1}]">
          <div class="svg_man_1" @click="showLists(1)"></div>
          <div class="svg_man_2_l" @click="showLists(5)"></div>
          <div class="svg_man_2_r" @click="showLists(5)"></div>
          <div class="svg_man_3_1" @click="showLists(7)"></div>
          <div class="svg_man_3_2" @click="showLists(8)"></div>
          <div class="svg_man_4" @click="showLists(9)"></div>
          <div class="svg_man_5_l" @click="showLists(6)"></div>
          <div class="svg_man_5_r" @click="showLists(6)"></div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div v-if="showPic==2||showPic==-2" class="guide_map">
        <div :class="['woman1_box',{front:showPic==2,back:showPic==-2}]">
          <div class="svg_woman_1" @click="showLists(1)"></div>
          <div class="svg_woman_2_l" @click="showLists(5)"></div>
          <div class="svg_woman_2_r" @click="showLists(5)"></div>
          <div class="svg_woman_3_1" @click="showLists(2)"></div>
          <div class="svg_woman_3_2" @click="showLists(3)"></div>
          <div class="svg_woman_4" @click="showLists(4)"></div>
          <div class="svg_woman_5_l" @click="showLists(6)"></div>
          <div class="svg_woman_5_r" @click="showLists(6)"></div>
        </div>
        <div :class="['woman2_box',{front:showPic==-2,back:showPic==2}]">
          <div class="svg_woman_1" @click="showLists(1)"></div>
          <div class="svg_woman_2_l" @click="showLists(5)"></div>
          <div class="svg_woman_2_r" @click="showLists(5)"></div>
          <div class="svg_woman_3_1" @click="showLists(7)"></div>
          <div class="svg_woman_3_2" @click="showLists(8)"></div>
          <div class="svg_woman_4" @click="showLists(9)"></div>
          <div class="svg_woman_5_l" @click="showLists(6)"></div>
          <div class="svg_woman_5_r" @click="showLists(6)"></div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div v-if="showPic==3||showPic==-3" class="guide_map">
        <div :class="['children1_box',{front:showPic==3,back:showPic==-3}]">
          <div class="svg_children_1" @click="showLists(1)"></div>
          <div class="svg_children_2_l" @click="showLists(5)"></div>
          <div class="svg_children_2_r" @click="showLists(5)"></div>
          <div class="svg_children_3_1" @click="showLists(2)"></div>
          <div class="svg_children_3_2" @click="showLists(3)"></div>
          <div class="svg_children_4" @click="showLists(4)"></div>
          <div class="svg_children_5_l" @click="showLists(6)"></div>
          <div class="svg_children_5_r" @click="showLists(6)"></div>
        </div>
        <div :class="['children2_box',{front:showPic==-3,back:showPic==3}]">
          <div class="svg_children_1" @click="showLists(1)"></div>
          <div class="svg_children_2_l" @click="showLists(5)"></div>
          <div class="svg_children_2_r" @click="showLists(5)"></div>
          <div class="svg_children_3_1" @click="showLists(7)"></div>
          <div class="svg_children_3_2" @click="showLists(8)"></div>
          <div class="svg_children_4" @click="showLists(9)"></div>
          <div class="svg_children_5_l" @click="showLists(6)"></div>
          <div class="svg_children_5_r" @click="showLists(6)"></div>
        </div>
      </div>
    </transition>
  </div>
</template>
<script> 
export default {
  name: 'guidance',
  components: { 
  },
  created() {
    var t = this;
    t.updateTitle('智能导诊')
    t.showPic = t.$store.state.store_guidance.guidance_showPic
    t.XBHET = t.$store.state.store_guidance.guidance_XBHET
    t.$store.state.store_guidance.showIconIndex = ''
  },
  computed: {},
  data() {
    return {
      showPic: '',
      XBHET: ''
    }
  },
  methods: {
    handler(index) {
      var t = this
      if (index == 0) {
        t.showPic = 1
        t.XBHET = '男'
      } else if (index == 1) {
        t.showPic = 2
        t.XBHET = '女'
      } else if (index == 2) {
        t.showPic = 3
        t.XBHET = '儿童'
      }
      t.$store.state.store_guidance.guidance_showPic = t.showPic
      t.$store.state.store_guidance.guidance_XBHET = t.XBHET
    },
    switchImg() {
      var t = this
      if (t.showPic == 1) {
        t.showPic = -1
      } else if (t.showPic == -1) {
        t.showPic = 1
      } else if (t.showPic == 2) {
        t.showPic = -2
      } else if (t.showPic == -2) {
        t.showPic = 2
      } else if (t.showPic == 3) {
        t.showPic = -3
      } else if (t.showPic == -3) {
        t.showPic = 3
      }
      t.$store.state.store_guidance.guidance_showPic = t.showPic
    },
    showLists(num) {
      var t = this
      t.linkToPage('/part_lists', { FBWXH: num, XBHET: t.XBHET, ORG_ID: t.queryData.ORG_ID, ORG_NAME: t.queryData.ORG_NAME })
    }
  },
  mounted() {}
}
</script>
<style scoped>
.guidance {
  min-height: 100%;
  background-image: url(../../assets/img/guidance/index_body_bg.jpg);
}

.guidance .guide_map {
  min-height: 550px;
}
.guidance .nav-wrap{
  display: flex;
  font-size:15px;
  background: #fff; 
}
.guidance .nav-wrap .nav-item{
  flex:8;
  text-align: center;
  padding: 13px 0;
  border-bottom: 2PX solid #eee;  
  border-right: 1PX solid #eee;  
}
.guidance .nav-wrap .nav-item.on{
  color: #00c792;
  border-bottom-color: #00c792;
}
.guidance .switch_btn {
  position: absolute;
  right: 30px;
  bottom: 30px;
  height: 60px;
  line-height: 30px;
  padding-top: 30px;
  background-image: url(../../assets/img/guidance/switch_btn.svg);
  background-position: center 0;
  background-repeat: no-repeat;
  background-size: auto 30px;
  z-index: 100;
}

.guidance .man1_box,
.guidance .man2_box,
.guidance .woman1_box,
.guidance .woman2_box,
.guidance .children1_box,
.guidance .children2_box {
  display: block;
  width: 248px;
  height: 500px;
  position: absolute;
  left: 50%;
  margin-left: -124px;
  top: 70px;
  transition: all 0.5s ease-out;
}

.guidance .man1_box,
.guidance .man2_box,
.guidance .woman1_box,
.guidance .woman2_box,
.guidance .children1_box,
.guidance .children2_box,
.guidance .svg_man_1,
.guidance .svg_man_2_l,
.guidance .svg_man_2_r,
.guidance .svg_man_3,
.guidance .svg_man_3_1,
.guidance .svg_man_3_2,
.guidance .svg_man_4,
.guidance .svg_man_5_l,
.guidance .svg_man_5_r,
.guidance .svg_woman_1,
.guidance .svg_woman_2_l,
.guidance .svg_woman_2_r,
.guidance .svg_woman_3,
.guidance .svg_woman_3_1,
.guidance .svg_woman_3_2,
.guidance .svg_woman_4,
.guidance .svg_woman_5_l,
.guidance .svg_woman_5_r,
.guidance .svg_children_1,
.guidance .svg_children_2_l,
.guidance .svg_children_2_r,
.guidance .svg_children_3,
.guidance .svg_children_3_1,
.guidance .svg_children_3_2,
.guidance .svg_children_4,
.guidance .svg_children_5_l,
.guidance .svg_children_5_r {
  background-position: center center;
  background-repeat: no-repeat;
  background-size: 100% 100%;
}

.guidance .man1_box {
  background-image: url(../../assets/img/guidance/man1.png);
}

.guidance .man2_box {
  background-image: url(../../assets/img/guidance/man2.png);
}

.guidance .woman1_box {
  background-image: url(../../assets/img/guidance/woman1.png);
}

.guidance .woman2_box {
  background-image: url(../../assets/img/guidance/woman2.png);
}

.guidance .children1_box {
  background-image: url(../../assets/img/guidance/children1.png);
}

.guidance .children2_box {
  background-image: url(../../assets/img/guidance/children2.png);
}

.guidance .children1_box.front,
.guidance .woman1_box.front,
.guidance .man1_box.front {
  transform: rotate3d(0, 1, 0, 0deg);
  opacity: 1;
  z-index: 5;
}

.guidance .children2_box.front,
.guidance .woman2_box.front,
.guidance .man2_box.front {
  transform: rotate3d(0, 1, 0, 0deg);
  opacity: 1;
  z-index: 5;
}

.guidance .children1_box.back,
.guidance .woman1_box.back,
.guidance .man1_box.back,
.guidance .children2_box.back,
.guidance .woman2_box.back,
.guidance .man2_box.back {
  transform: rotate3d(0, 1, 0, -180deg);
  opacity: 0;
  z-index: 4;
}

.guidance .svg_man_1 {
  position: absolute;
  top: 0;
  left: 89px;
  width: 69px;
  height: 88px;
  background-image: url(../../assets/img/guidance/svg_man_1.svg);
  z-index: 12;
}

.guidance .svg_man_2_l,
.guidance .svg_man_2_r {
  position: absolute;
  top: 120px;
  width: 84px;
  height: 190px;
  z-index: 8;
}

.guidance .svg_man_2_l {
  left: 0;
  background-image: url(../../assets/img/guidance/svg_man_2_l.svg);
}

.guidance .svg_man_2_r {
  right: 0;
  background-image: url(../../assets/img/guidance/svg_man_2_r.svg);
}

.guidance .svg_man_3 {
  position: absolute;
  top: 79px;
  left: 61px;
  width: 129px;
  height: 135px;
  background-image: url(../../assets/img/guidance/svg_man_3.svg);
  z-index: 9;
}

.guidance .svg_man_3_1 {
  position: absolute;
  top: 79px;
  left: 61px;
  width: 129px;
  height: 87px;
  background-image: url(../../assets/img/guidance/svg_man_3_1.svg);
  z-index: 9;
}

.guidance .svg_man_3_2 {
  position: absolute;
  top: 166px;
  left: 84px;
  width: 82px;
  height: 49px;
  background-image: url(../../assets/img/guidance/svg_man_3_2.svg);
  z-index: 9;
}

.guidance .svg_man_4 {
  position: absolute;
  top: 214px;
  left: 78px;
  width: 95px;
  height: 62px;
  background-image: url(../../assets/img/guidance/svg_man_4.svg);
  z-index: 11;
}

.guidance .svg_man_5_l,
.guidance .svg_man_5_r {
  position: absolute;
  top: 275px;
  width: 43px;
  height: 220px;
  z-index: 10;
}

.guidance .svg_man_5_l {
  left: 77px;
  background-image: url(../../assets/img/guidance/svg_man_5_l.svg);
}

.guidance .svg_man_5_r {
  right: 75px;
  background-image: url(../../assets/img/guidance/svg_man_5_r.svg);
}

.guidance .svg_woman_1 {
  position: absolute;
  top: 0;
  left: 89px;
  width: 68px;
  height: 82px;
  background-image: url(../../assets/img/guidance/svg_woman_1.svg);
  z-index: 12;
}

.guidance .svg_woman_2_l,
.guidance .svg_woman_2_r {
  position: absolute;
  top: 98px;
  width: 56px;
  height: 179px;
  z-index: 8;
}

.guidance .svg_woman_2_l {
  left: 30px;
  background-image: url(../../assets/img/guidance/svg_woman_2_l.svg);
}

.guidance .svg_woman_2_r {
  right: 25px;
  background-image: url(../../assets/img/guidance/svg_woman_2_r.svg);
}

.guidance .svg_woman_3 {
  position: absolute;
  top: 82px;
  left: 80px;
  width: 94px;
  height: 145px;
  background-image: url(../../assets/img/guidance/svg_woman_3.svg);
  z-index: 9;
}

.guidance .svg_woman_3_1 {
  position: absolute;
  top: 82px;
  left: 80px;
  width: 94px;
  height: 75px;
  background-image: url(../../assets/img/guidance/svg_woman_3_1.svg);
  z-index: 9;
}

.guidance .svg_woman_3_2 {
  position: absolute;
  top: 157px;
  left: 84px;
  width: 84px;
  height: 70px;
  background-image: url(../../assets/img/guidance/svg_woman_3_2.svg);
  z-index: 9;
}

.guidance .svg_woman_4 {
  position: absolute;
  top: 219px;
  left: 80px;
  width: 93px;
  height: 54px;
  background-image: url(../../assets/img/guidance/svg_woman_4.svg);
  z-index: 11;
}

.guidance .svg_woman_5_l,
.guidance .svg_woman_5_r {
  position: absolute;
  top: 244px;
  width: 45px;
  height: 256px;
  z-index: 10;
}

.guidance .svg_woman_5_l {
  left: 79px;
  background-image: url(../../assets/img/guidance/svg_woman_5_l.svg);
}

.guidance .svg_woman_5_r {
  right: 75px;
  background-image: url(../../assets/img/guidance/svg_woman_5_r.svg);
}

.guidance .svg_children_1 {
  position: absolute;
  top: 65px;
  left: 79px;
  width: 98px;
  height: 113px;
  background-image: url(../../assets/img/guidance/svg_children_1.svg);
  z-index: 12;
}

.guidance .svg_children_2_l,
.guidance .svg_children_2_r {
  position: absolute;
  top: 197px;
  width: 69px;
  height: 130px;
  z-index: 8;
}

.guidance .svg_children_2_l {
  left: 23px;
  background-image: url(../../assets/img/guidance/svg_children_2_l.svg);
}

.guidance .svg_children_2_r {
  right: 22px;
  background-image: url(../../assets/img/guidance/svg_children_2_r.svg);
}

.guidance .svg_children_3 {
  position: absolute;
  top: 175px;
  left: 83px;
  width: 85px;
  height: 114px;
  background-image: url(../../assets/img/guidance/svg_children_3.svg);
  z-index: 9;
}

.guidance .svg_children_3_1 {
  position: absolute;
  top: 175px;
  left: 83px;
  width: 85px;
  height: 85px;
  background-image: url(../../assets/img/guidance/svg_children_3_1.svg);
  z-index: 9;
}

.guidance .svg_children_3_2 {
  position: absolute;
  top: 259px;
  left: 83px;
  width: 85px;
  height: 30px;
  background-image: url(../../assets/img/guidance/svg_children_3_2.svg);
  z-index: 9;
}

.guidance .svg_children_4 {
  position: absolute;
  top: 289px;
  left: 82px;
  width: 86px;
  height: 38px;
  background-image: url(../../assets/img/guidance/svg_children_4.svg);
  z-index: 11;
}

.guidance .svg_children_5_l,
.guidance .svg_children_5_r {
  position: absolute;
  top: 327px;
  width: 36px;
  height: 110px;
  z-index: 10;
}

.guidance .svg_children_5_l {
  left: 84px;
  background-image: url(../../assets/img/guidance/svg_children_5_l.svg);
}

.guidance .svg_children_5_r {
  right: 82px;
  background-image: url(../../assets/img/guidance/svg_children_5_r.svg);
}

.guidance .vux-tab .vux-tab-item {
  font-size: 16px !important;
  color: #c9c9c9 !important;
  height: 44px !important;
  line-height: 44px !important;
  position: relative !important;
}

.guidance .vux-tab .vux-tab-item.vux-tab-selected {
  color: #00c792 !important;
  border-bottom-color: #00c792;
}

.guidance .vux-tab .vux-tab-item:before {
  content: '';
  display: block !important;
  position: absolute !important;
  width: 1px !important;
  height: 30px !important;
  background-color: #f3f3f3 !important;
  left: -1px !important;
  top: 50% !important;
  right: none !important;
  margin-top: -15px !important;
}

.guidance .vux-tab .vux-tab-item:first-child:before {
  display: none !important;
}

@media screen and (max-width: 320px) {
  .guidance .guide_map {
    min-height: 450px;
  }

  .guidance .man1_box,
  .guidance .man2_box,
  .guidance .woman1_box,
  .guidance .woman2_box,
  .guidance .children1_box,
  .guidance .children2_box {
    width: 208px;
    height: 420px;
    top: 60px;
    margin-left: -104px;
  }

  .guidance .svg_man_1 {
    left: 75px;
    width: 58px;
    height: 75px;
  }

  .guidance .svg_man_2_l,
  .guidance .svg_man_2_r {
    top: 101px;
    width: 70px;
    height: 160px;
  }

  .guidance .svg_man_3 {
    top: 65px;
    left: 50px;
    width: 108px;
    height: 115px;
  }

  .guidance .svg_man_3_1 {
    top: 73px;
    left: 50px;
    width: 108px;
    height: 65px;
  }

  .guidance .svg_man_3_2 {
    top: 136px;
    left: 70px;
    width: 69px;
    height: 45px;
  }

  .guidance .svg_man_4 {
    top: 180px;
    left: 65px;
    width: 80px;
    height: 52px;
  }

  .guidance .svg_man_5_l,
  .guidance .svg_man_5_r {
    top: 231px;
    width: 36px;
    height: 185px;
  }

  .guidance .svg_man_5_l {
    left: 64px;
  }

  .guidance .svg_man_5_r {
    right: 63px;
  }

  .guidance .svg_woman_1 {
    left: 75px;
    width: 58px;
    height: 70px;
  }

  .guidance .svg_woman_2_l,
  .guidance .svg_woman_2_r {
    top: 80px;
    width: 50px;
    height: 160px;
  }

  .guidance .svg_woman_2_l {
    left: 21px;
  }

  .guidance .svg_woman_2_r {
    right: 19px;
  }

  .guidance .svg_woman_3 {
    top: 70px;
    left: 66px;
    width: 78px;
    height: 120px;
  }

  .guidance .svg_woman_3_1 {
    top: 70px;
    left: 66px;
    width: 78px;
    height: 62px;
  }

  .guidance .svg_woman_3_2 {
    top: 132px;
    left: 68px;
    width: 71px;
    height: 59px;
  }

  .guidance .svg_woman_4 {
    top: 183px;
    left: 66px;
    width: 78px;
    height: 45px;
  }

  .guidance .svg_woman_5_l,
  .guidance .svg_woman_5_r {
    top: 204px;
    width: 38px;
    height: 216px;
  }

  .guidance .svg_woman_5_l {
    left: 67px;
  }

  .guidance .svg_woman_5_r {
    right: 63px;
  }

  .guidance .svg_children_1 {
    top: 54px;
    left: 65px;
    width: 84px;
    height: 97px;
  }

  .guidance .svg_children_2_l,
  .guidance .svg_children_2_r {
    top: 170px;
    width: 56px;
    height: 105px;
  }

  .guidance .svg_children_2_l {
    left: 18px;
  }

  .guidance .svg_children_2_r {
    right: 17px;
  }

  .guidance .svg_children_3 {
    top: 148px;
    left: 68px;
    width: 74px;
    height: 99px;
  }

  .guidance .svg_children_3_1 {
    top: 148px;
    left: 68px;
    width: 74px;
    height: 74px;
  }

  .guidance .svg_children_3_2 {
    top: 222px;
    left: 68px;
    width: 74px;
    height: 22px;
  }

  .guidance .svg_children_4 {
    top: 243px;
    left: 67px;
    width: 75px;
    height: 32px;
  }

  .guidance .svg_children_5_l,
  .guidance .svg_children_5_r {
    top: 275px;
    width: 29px;
    height: 90px;
  }

  .guidance .svg_children_5_l {
    left: 70px;
  }

  .guidance .svg_children_5_r {
    right: 70px;
  }
}
</style>