<template>
  <div class="jtys_qy_fwb g-wrap g-hp100 s-bgcfff">
    <div class="fwb-list">
      <div class="list-item f-df" v-for="(c,i) in fwbList" :key="i" @click="toQY02(c,i)">
        <div class="icons f-mr10 f-mt2"><i v-if="currFwb===i" class="iconfont icon-success s-c00c792 f-fs11"></i></div>
        <div class="cont">
          <p class="f-df f-dfj f-mb10">
            <span class="f-fwb f-fs15">{{c.txt}}</span>
            <span class="s-c909399" @click.stop="linkToPage('jtys_fwnr')">查看详情 <i class="iconfont icon-arrow-right f-fs11"></i></span>
          </p>
          <p class="s-c909399">签约适合对象：本市常住居民</p>
        </div>
      </div>
    </div> 
    <div class="s-cffa54d f-pl20 f-pr20 f-pt20 f-fs14">*如需要签订付费服务包，请线下联系社区医生签订。</div>
  </div>
</template>
<script>
export default {
  name: 'jtys_qy_fwb',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('服务包')
    t.pathParams = t.queryData
    t.currFwb = t.$store.state.store_jtys.currFwb
  },
  data() {
    return {
      pathParams: '',
      fwbList:[
        {id:'1',txt:'免费包',}, 
      ],
      currFwb:''
    }
  },
  methods: {
    toQY02(data,index) {
      var t = this 
      t.currFwb=index
      t.$store.state.store_jtys.currFwb=index
      t.pathParams.TYPE = data.txt
      t.linkToPage('jtys_qy_02', t.pathParams)
    }, 
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}


.jtys_qy_fwb .fwb-list {
  padding:0 20px;
}

.jtys_qy_fwb .fwb-list .list-item .cont{
  width: calc(100% - 25px);
}
.jtys_qy_fwb .fwb-list .list-item .icons{
  width: 15px;
  height: 15px;
  line-height: 15px;
  display: inline-block;
  border-radius: 50%;
  text-align: center;
  border:1px solid #00c792;
} 
.jtys_qy_fwb .fwb-list .list-item {
  background: #f7faf9;
  border-radius: 4px;
  padding: 15px;
  font-size: 14px;
  margin-top: 20px;
}
</style>