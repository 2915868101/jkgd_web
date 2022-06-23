<template>
  <div class="yyjs_index g-wrap g-hp100 s-bgcfff" v-if="hospitalInfo">
    <div class="banner-wrap noimg-wrap f-mb10" v-if="!hospitalInfo.ORG_PIC_URL">
      <img class="g-w100 f-pt20" :src="$store.state.def_hospitalimg">
    </div>
    <div class="banner-wrap f-mb10" v-else>
      <img class="g-wp100 g-h200" :src="hospitalInfo.ORG_PIC_URL">
    </div>
    <div class="info-wrap f-bb10">
      <div class="item">
        <span class="name">{{hospitalInfo.ORG_NAME}}</span>
        <span class="f-ml15 b-tag" v-show="hospitalInfo.ORG_LEVEL">{{hospitalInfo.ORG_LEVEL}}</span>
      </div>
      <div class="item f-df">
        <i class="f-df1 f-mr10 iconfont icon-phone"></i>
        <div class="f-df23"> <a :href="'tel:' + hospitalInfo.ORG_PHONE">{{hospitalInfo.ORG_PHONE}}</a></div>
      </div>
      <div class="item f-df">
        <i class="f-df1 f-mr10 iconfont icon-position f-fs11"></i>
        <div class="f-df23">{{hospitalInfo.ORG_ADDRESS}}</div>
      </div>
    </div>
    <div class="menu-wrap f-df f-bb10">
      <div class="item f-df8" @click="linkToPage('yyjs_ks',{ORG_ID:hospitalInfo.ORG_ID})">
        <img class="g-w45 f-pb10" src="../../assets/img/zswy/menu-icon1.png">
        <p>科室介绍</p>
      </div>
      <div class="item f-df8" @click="linkToPage('yyjs_ys',{ORG_ID:hospitalInfo.ORG_ID})">
        <img class="g-w45 f-pb10" src="../../assets/img/zswy/menu-icon2.png">
        <p>名医介绍</p>
      </div>
      <!--       <div class="item f-df8" @click="linkToPage('amap',{ORG_LONGITUDE:hospitalInfo.ORG_LONGITUDE,ORG_LATITUDE:hospitalInfo.ORG_LATITUDE})">
        <img class="g-w45 f-pb10" src="../../assets/img/zswy/menu-icon3.png">
        <p>院内地图</p>
      </div> -->
      <div class="item f-df8" @click="doDepartment(hospitalInfo.ORG_ID,hospitalInfo.ORG_NAME,hospitalInfo.ORG_YQ)">
        <img class="g-w45 f-pb10" src="../../assets/img/zswy/menu-icon4.png">
        <p>去挂号</p>
      </div>
    </div>
    <div class="desc-wrap s-bgcfff">
      <p :class="[isOpenDetail?'':'line3']">{{hospitalInfo.ORG_INTRODUCE||'暂无介绍.'}}</p>
      <template v-if="hospitalInfo.ORG_INTRODUCE">
        <span class="s-c00c792 f-fs14" @click="openDetail" v-if="hospitalInfo.ORG_INTRODUCE.length>73">
          <span v-if="!isOpenDetail">查看更多</span>
          <span v-else>收起</span>
          <i class="iconfont icon-arrow-right f-fs12"></i>
        </span>
      </template>
    </div>
  </div>
</template>
<script>
export default {
  name: 'yyjs_index',
  components: {},
  created() {
    var t = this
    t.updateTitle('医院主页')
    t.getHospitalList()
  },
  computed: {},
  data() {
    return {
      hospitalInfo: '',
      isOpenDetail: false,
      pathParams: {},
    }
  },
  methods: {
    doDepartment(id, name, yq) {
      var t = this
      console.log(id, name)
      console.log(t.hospitalInfo)
      t.$store.state.store_yygh.ks_tree_data = ''
      t.$store.state.store_yygh.ks_tree_index = 0
      if (t.serverType == 'cz'|| t.serverType == 'gd') {
        if ((t.serverType == 'gd'&&id != 'guangde001'&&t.hospitalInfo.GH_FLAG==1)||(t.serverType == 'cz'&&t.hospitalInfo.GH_FLAG==1)) { //池州人民医院,东至人民
          t.pathParams.ORG_ID = id
          t.pathParams.ORG_NAME = name
          t.pathParams.ORG_YQ = yq || ''
          t.$store.state.store_yygh.ks_tree_data = ''
          t.$store.state.store_yygh.ks_tree_index = 0
          t.$store.state.store_yygh.hz_code = ''
          t.linkToPage('yygh_ks', t.pathParams)
        } else if (id == 'guangde001'&&t.hospitalInfo.GH_FLAG==1) { //广德人民医院
          t.linkToPage('yyghgd_index', { ORG_ID: id, ORG_NAME: name })
        } else {
          t.$dialog.alert({
              confirmButtonText: '好的',
              message: name + '还未进行数据对接，暂时无法挂号。',
            })
            .then(() => {

            })
        }
      } else {
        t.pathParams.ORG_ID = id
        t.pathParams.ORG_NAME = name
        t.pathParams.ORG_YQ = yq || ''
        t.$store.state.store_yygh.ks_tree_data = ''
        t.$store.state.store_yygh.ks_tree_index = 0
        t.$store.state.store_yygh.hz_code = ''
        t.linkToPage('yygh_ks', t.pathParams)
      } 
    },
    getHospitalList() {
      var t = this
      var list = JSON.parse(localStorage.hospitalList)
      for (let c of list) {
        if (c.ORG_NAME == t.queryData.ORG_NAME) {
          t.hospitalInfo = c
          console.log(t.hospitalInfo)
        }
      }
    },
    openDetail() {
      this.isOpenDetail = !this.isOpenDetail
      console.log(this.isOpenDetail)
    },
  },
  mounted() {}
}
</script>
<style scoped>
.yyjs_index .banner-wrap {
  width: 100%;
  text-align: center;
}

.yyjs_index .banner-wrap.noimg-wrap {
  height: 160px;
}

.yyjs_index .f-bb10 {
  border-bottom: 10px solid #f4f9f9;
}

.yyjs_index .info-wrap {
  font-size: 14px;
}

.yyjs_index .info-wrap .item {
  padding: 15px;
}

.yyjs_index .info-wrap .item:nth-last-child(1) {
  border-top: 1px solid #ebeef5;
}

.yyjs_index .info-wrap .item .name {
  font-weight: bold;
  font-size: 15px;
}

.yyjs_index .info-wrap .item .iconfont {
  position: relative;
  font-size: 15px;
  top: -1px;
}

.yyjs_index .menu-wrap {
  padding: 15px;
  font-size: 14px;
  line-height: 20px;
  color: #303133;
  text-align: center;
}

.yyjs_index .desc-wrap {
  font-size: 14px;
  color: #606266;
  line-height: 22px;
  padding: 20px 15px;
  text-indent: 2em;
}

.yyjs_index .desc-wrap {}
</style>