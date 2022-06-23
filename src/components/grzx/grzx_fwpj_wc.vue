<template>
  <div class="grzx_fwpj_wc g-wrap g-hp100 s-bgcfff">
    <div class="tips-wrap s-bgcfff f-mb10">
      <div class="item-icon s-bgc00c792 g-w50 g-h50 f-brp50 f-lh50"><i class="iconfont icon-success f-fs25 s-cfff"></i></div>
      <div class="f-mb10 f-mt10 s-c303133 tit">发布成功!</div>
      <div class="f-mb5 f-mt5 s-c999">感谢您的评价,祝您身体健康</div> 
      <div class="f-mt15 s-c999">{{$moment(new Date()).format('YYYY-MM-DD HH-ss-mm')}}</div> 
    </div>
    <div class="g-wp100">
      <van-button class="item-btn f-bs3" round color="#00c792" @click="linkToPage('index')">返回首页</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'grzx_fwpj_wc',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('评价成功') 
  },
  data() {
    return { 
    }
  },
  methods: { 
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.grzx_fwpj_wc .tips-wrap {
  text-align: center;
  padding: 30px 15px;
  font-size: 15px;
}

.grzx_fwpj_wc .tips-wrap .item-icon {
  display: inline-block;
  margin: 0 auto;
}

.grzx_fwpj_wc .tips-wrap .tit {
  font-size: 17px;
  margin: 15px 0 8px;
  font-weight: bold;
}

.grzx_fwpj_wc>>>.van-button {
  width: 90%;
  margin: 30px 5% 0;
  height: 50px;
}
.grzx_fwpj_wc .g-wp100{
  position: fixed;
  bottom: 30%;
}
</style>