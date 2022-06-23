<template>
  <div class="yyjs_ys_grjs g-wrap g-hp100 s-bgcfff">
    <div class="info-wrap">
      <div class="w-head s-c303133">
        <span class="name f-mr10">{{dataInfo.YSXM}}</span>
        <span>{{dataInfo.YSJB}}</span>
      </div>
      <div class="w-cont f-mt20">
        {{dataInfo.YSSC||'暂无介绍'}}
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'yyjs_ys_grjs',
  directives: {},
  components: {},
  created() {
    var t = this
    t.dataInfo = t.queryData
    t.updateTitle('名医介绍-'+t.dataInfo.YSXM)
  },
  computed: {},
  data() {
    return {
      dataInfo: ''
    }
  },
  methods: {},
  mounted() {}
}
</script>
<style>
.yyjs_ys_grjs .info-wrap {
  padding:15px 18px;
  font-size: 14px;
  line-height: 18px;
  color: #606266;
}
.yyjs_ys_grjs .info-wrap .w-head .name{
  font-size: 15px;
  font-weight: bold;
}

</style>