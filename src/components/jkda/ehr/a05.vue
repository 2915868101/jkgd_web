<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap">青年期 26岁</div>
    <van-cell-group>
      <van-cell title="就诊人" value="张果果 (女 37)" />
      <van-cell title="住院时间" value="2012-08-17" />
      <van-cell title="主治医生" value="李梅(主任医生)" />
      <van-cell title="责任护士" value="郝仁" />
      <van-cell title="住院号" value="A1052" />
      <van-cell title="住院医院" value="池州市人民医院" />
      <div class="cell-item">
        <div class="tit">入院诊断：</div>
        <div class="cont">内容详情内容详情内容详情内容详情内容详情内容内 容详情内容详情</div>
      </div>
    </van-cell-group>
    <div class="s-bgcfff f-mt15">
      <div class="swiper-wrap">
        <div class="swiper-nav">
          <div class="item" @click="ToPrev"><i class="iconfont icon-arrow-left s-c606266 f-fs13"></i></div>
          <div class="item">第 {{current+1}} 天</div>
          <div class="item" @click="ToNext"><i class="iconfont icon-arrow-right s-c606266 f-fs13"></i></div>
        </div>
        <van-swipe ref="mySwipe" class="my-swipe" @change="onChange" :show-indicators="false" :loop="false">
          <van-swipe-item>
            <van-cell title="处方明细" @click="toInfo(1)"/>
            <div class="flex-item">
              <div class="f-df16">5%葡萄糖注射/250ml*12.5/p…</div>
              <div class="f-df4">500ml</div>
              <div class="f-df4">1支</div>
            </div>
            <div class="flex-item">
              <div class="f-df16">5%葡萄糖注射/250ml*12.5/p…</div>
              <div class="f-df4">500ml</div>
              <div class="f-df4">1支</div>
            </div>
          </van-swipe-item>
          <van-swipe-item>
            <van-cell title="处方明细2" @click="toInfo(2)"/>
            <div class="flex-item">
              <div class="f-df16">5%葡萄糖注射/250ml*12.5/p…</div>
              <div class="f-df4">5200ml</div>
              <div class="f-df4">2支</div>
            </div>
            <div class="flex-item">
              <div class="f-df16">5%葡萄糖注射/250ml*12.5/p…</div>
              <div class="f-df4">500ml</div>
              <div class="f-df4">2支</div>
            </div>
          </van-swipe-item>
        </van-swipe>
      </div>
      <div class="cell-item f-mt15">
        <div class="tit">住院记录：</div>
        <div class="cont">内容详情内容详情内容详情内容详情内容详情内容内 容详情内容详情</div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'jkda_detail',
  components: {
  },
  computed: { 
  },
  created() {
    var t = this
    t.updateTitle('住院详情')
    t.searchForm = t.queryData
  },
  data() {
    return {
      current: 0,
    }
  },
  methods: {
    onChange(index) {
      this.current = index;
    },
    ToPrev(){
      this.$refs.mySwipe.prev()
    },
    ToNext(){
      this.$refs.mySwipe.next()
    },
    toInfo(val){
      console.log(val)
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.jkda_detail .notice-wrap {
  background: #00c792;
  color: #fff;
  padding: 15px;
  font-size: 14px;
}

.jkda_detail>>>.van-cell {
  padding: 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
}

.jkda_detail>>>.van-cell__title {
  color: #606266;
}

.jkda_detail>>>.van-cell__value {
  color: #303133;
}

.jkda_detail>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.jkda_detail .cell-item {
  font-size: 14px;
  padding: 0 15px 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
}

.jkda_detail .cell-item .tit {
  padding: 15px 0;
}

.jkda_detail .cell-item .cont {
  line-height: 24px;
}
.jkda_detail .swiper-wrap {
  font-size: 14px;
  padding-top: 15px;
}
.jkda_detail .swiper-wrap .swiper-nav{
  width: 60%;
  margin: 0 auto;
  display: flex; 
  border:1PX solid #ebeef5; 
  background: #fcfcfc;
  border-radius: 5px;
}
.jkda_detail .swiper-wrap .swiper-nav .item{ 
  flex:8;
  padding: 10px 0;
  text-align: center; 
  border-right:1PX solid #ebeef5;
}
.jkda_detail .flex-item{
  display: flex;
  padding: 15px ;
  border-bottom: 1PX solid #ebeef5;
}
.jkda_detail .flex-item .f-df4{
  text-align: right;
}
</style>