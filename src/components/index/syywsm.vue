<template>
  <div class="syywsm g-wrap f-hp100 s-bgcfff">
    <div class="list-item">
      <div class="tit">1.关注<b>“粤省事”</b>公众号，进入公众号。</div>
      <div class="img">
        <img class="g-w180" src="../../assets/img/index/syywsm-img1.jpg" alt="">
      </div>
    </div>
    <div class="list-item">
      <div class="tit">2.选择<b>“指尖办事”</b>菜单中的<b>“个人办事”</b>。</div>
      <div class="img">
        <img class="g-w155" src="../../assets/img/index/syywsm-img2.jpg" alt="">
      </div>
    </div>
    <div class="list-item">
      <div class="tit">3.进入小程序<b>“粤省事”</b>，先选择<b>“业务所在地”</b>，下拉选择<b>“生育”</b>。</div>
      <div class="img">
        <img class="g-w172" src="../../assets/img/index/syywsm-img3.jpg" alt="">
      </div>
    </div>
    <div class="list-item">
      <div class="tit">4.进入<b>“生育”</b>小程序,选择需要办理的业务。</div>
      <div class="img">
        <img class="g-w104" src="../../assets/img/index/syywsm-img4.jpg" alt="">
      </div>
    </div>
    <div class="g-wp100 s-bgcfff f-pt30 f-pb30"> 
      <van-button class="item-btn f-bs3" round color="#00c792" @click="doSubmit">确认，我已知晓</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'syywsm',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('生育业务说明')
  },
  data() {
    return {
    }
  },
  methods: {
    doSubmit(){
      window.location.href='https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzU2NjU2OTY2NQ==&scene=124#wechat_redirect'
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.syywsm .list-item{
  padding: 15px;
  background: #fff;
  font-size: 15px;
  line-height: 20px;
  border-bottom: 1px solid #ebeef5;
}
.syywsm .list-item b{
  font-weight: bold;
}
.syywsm .list-item .img{
  margin-top: 15px; 
  margin-left: 45px; 
}
.syywsm .list-item .g-w180{width: 180px;}
.syywsm .list-item .g-w155{width: 155px;}
.syywsm .list-item .g-w172{width: 172px;}
.syywsm .list-item .g-w104{width: 104px;} 
.syywsm>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  padding: 0 10px;
}
</style>