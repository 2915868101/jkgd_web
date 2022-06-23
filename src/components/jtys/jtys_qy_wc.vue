<template>
  <div class="jtys_qy_wc g-wrap g-hp100 s-bgcfff">
    <div class="tips-wrap s-bgcfff f-mb10">
      <div class="item-icon s-bgc00c792 g-w50 g-h50 f-brp50 f-lh50"><i class="iconfont icon-success f-fs25 s-cfff"></i></div>
      <div class="f-mb10 f-mt10 s-c303133 tit">提交成功!</div>
      <div class="f-mb10 f-mt10 s-c999">资料提交成功，请耐心等待服务机构审核</div>
      <div class="f-pb20 f-pl30 f-pr30 f-lh18 f-fs15">
        <p class="f-tac f-mt15">
          <span class="s-c6c6c6c f-tdu" @click="linkToPage('jtys_qy_jd')">查看签约进度 </span>
        </p>
      </div>
    </div>
    <div class="g-wp100">
      <van-button class="item-btn f-bs3" round color="#00c792" @click="linkToPage('jtys_index')">返回首页</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'jtys_qy_wc',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('签约完成') 
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

.jtys_qy_wc .tips-wrap {
  text-align: center;
  padding: 30px 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.jtys_qy_wc .tips-wrap .item-icon {
  display: inline-block;
  margin: 0 auto;
}

.jtys_qy_wc .tips-wrap .tit {
  font-size: 16px;
  margin: 15px 0;
  font-weight: bold;
}

.jtys_qy_wc>>>.van-button {
  width: 90%;
  margin: 30px 5% 0;
  height: 50px;
}
</style>