<template>
  <div class="jkbk_details s-bgceee s-c000 f-fs14">
    <div class="box">
      <div class="tit" v-if="MLID=='1'">疾病名称</div>
      <div class="tit" v-if="MLID=='2'">药物名称</div>
      <div class="tit" v-if="MLID=='3'">化验单名称</div>
      <div class="tit" v-if="MLID=='5'">手术名称</div>
      <div class="cont">{{medName}}</div>
    </div>
    <div class="box" v-if="MLID=='1'">
      <div class="tit">概述</div>
      <div class="cont" v-html="overView"></div>
    </div>
    <div class="box" v-if="MLID=='1'">
      <div class="tit">所属科室</div>
      <div class="cont">{{details.CLASS_NAME==null?'暂无相关数据':details.CLASS_NAME}}</div>
    </div>
    <div class="box  btm" v-if="MLID=='1'">
      <div class="tit">病因描述</div>
      <div class="cont">{{details.REASON_DESCRIPTION==null?'暂无相关数据':details.REASON_DESCRIPTION}}</div>
    </div>
    <div class="box" v-if="MLID=='2'">
      <div class="tit">药物类型</div>
      <div class="cont">{{details.DRUG_TYPE==null?'暂无相关数据':details.DRUG_TYPE}}</div>
    </div>
    <div class="box" v-if="MLID=='2'">
      <div class="tit">药物成分</div>
      <div class="cont">{{details.DRUG_COMPOSITIONE==null?'暂无相关数据':details.DRUG_COMPOSITIONE}}</div>
    </div>
    <div class="box" v-if="MLID=='2'">
      <div class="tit">剂量</div>
      <div class="cont">{{details.DRUG_DOES==null?'暂无相关数据':details.DRUG_DOES}}</div>
    </div>
    <div class="box" v-if="MLID=='2'">
      <div class="tit">适用症</div>
      <div class="cont">{{details.APPLICATION_SYMPTOMS==null?'暂无相关数据':details.APPLICATION_SYMPTOMS}}</div>
    </div>
    <div class="box" v-if="MLID=='3'">
      <div class="tit">单位</div>
      <div class="cont">{{details.UNIT==null?'暂无相关数据':details.UNIT}}</div>
    </div>
    <div class="box" v-if="MLID=='3'">
      <div class="tit">正常值</div>
      <div class="cont">{{details.NORMALA==null?'暂无相关数据':details.NORMALA}}</div>
    </div>
    <div class="box" v-if="MLID=='3'">
      <div class="tit">临床意义</div>
      <div class="cont">{{details.CLINIC==null?'暂无相关数据':details.CLINIC}}</div>
    </div>
    <div class="box" v-if="MLID=='3'">
      <div class="tit">正文</div>
      <div class="cont">{{details.TEXT==null?'暂无相关数据':details.TEXT}}</div>
    </div>
    <div class="box" v-if="MLID=='5'">
      <div class="tit">科室名称</div>
      <div class="cont">{{details.KSMC==null?'暂无相关数据':details.KSMC}}</div>
    </div>
    <div class="box" v-if="MLID=='5'">
      <div class="tit">手术详情</div>
      <div class="cont">{{details.SSXQ==null?'暂无相关数据':details.SSXQ}}</div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'jkbk_details',
  directives: {},
  components: {},
  created() {
    var t = this
    t.updateTitle(t.queryData.name)
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 0
    t.MLID = t.$route.params.id
    t.medName = t.queryData.name
    t.getDetails(t.MLID, t.queryData.id)
  },
  computed: {},
  data() {
    return {
      MLID: '',
      details: '',
      overView: '',
      medName: ''
    }
  },
  methods: {
    //获取详情
    getDetails(MLID, ID) {
      var t = this
      t.ajax({
        method: 'post',
        serverId: 'WXPL00015',
        loading: false,
        params: {
          MLID: MLID,
          ID: ID
        },
        url: t.serverPath2,
        success(data) {
          t.details = data[0]
          //字符串转换成 html 标签
          if (data[0].INTRODUCTION == null) {
            t.overView = '暂无相关数据'
          } else {
            t.overView = t.html_decode(data[0].INTRODUCTION)
          }
        }
      })
    }
  },
  mounted() {}
}
</script>
<style scoped>
@import '../../assets/css/jkbk/jkbk_details.css';
</style>