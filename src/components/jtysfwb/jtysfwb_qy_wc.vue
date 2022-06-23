<template>
  <div class="jtys_qy_wc g-wrap g-hp100 ">
    <div class="jtysfwb_qy_01  g-wrap g-hp100">
      <div class="steps-wrap">
        <div class="steps-item f-df8 on">
          <div class="steps">1</div>
          <div class="lines"></div>
          <div class="tit">填写信息</div>
        </div>
        <div class="steps-item f-df8 on">
          <div class="steps">2</div>
          <div class="lines"></div>
          <div class="tit">确认信息</div>
        </div>
        <div class="steps-item f-df8 on">
          <div class="steps">3</div>
          <div class="lines"></div>
          <div class="tit">签约完成</div>
        </div>
      </div>
      <div class="border tips-wrap s-bgcfff f-mb10 ">
        <div class="item-icon s-bgc00c792 g-w60 g-h60 f-brp50" style="line-height:70px"><i class="iconfont icon-success f-fs30 s-cfff"></i></div>
        <div class="f-mb10 f-fs16 f-lh24 f-mt10 s-c303133">签约完成</div>
        <div class="tag f-pt15" @click="linkToPage('jtysfwb_qy_01')">
          <van-tag color="#00C792" round plain type="primary">查看已签约服务包</van-tag>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jtys_qy_wc',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('签约服务包')
    },
    data() {
      return {}
    },
    methods: {},
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtysfwb_qy_01 .tag>>>.van-tag {
    padding: 10px 30px;
    font-size: 16px;
    line-height: 24px;
  }

  .jtysfwb_qy_01 .border {
    border-top-right-radius: 12px;
    border-top-left-radius: 12px;
  }

  .jtysfwb_qy_01 .steps-wrap {
    background: #f4f9f9;
    padding: 15px;
    font-size: 14px;
    display: flex;
    color: #323233;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item .steps {
    position: relative;
    z-index: 2;
    background: #fff;
    border-radius: 50%;
    width: 27px;
    height: 27px;
    line-height: 27px;
    font-size: 14px;
    margin: 0 auto 10px;
    border: 1PX solid #dde0e7;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item .lines {
    border-bottom: 1PX solid #dde0e7;
    position: relative;
    top: -22px;
    left: 50%;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item.on .steps,
  .jtysfwb_qy_01 .steps-wrap .lines.on,
  .jtysfwb_qy_01 .steps-wrap .tit.on {
    border-color: #00c792;
    background: #00c792;
    color: #fff;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item:nth-last-child(1) .lines {
    border: 0;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item {
    text-align: center;

  }

  .jtys_qy_wc .tips-wrap {
    text-align: center;
    padding: 50px 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
    height: 89.8%
  }

  .jtys_qy_wc .tips-wrap .item-icon {
    display: inline-block;
    margin: 0 auto;
  }

  .jtys_qy_wc>>>.van-button {
    width: 90%;
    margin: 30px 5% 0;
    height: 50px;
  }
</style>