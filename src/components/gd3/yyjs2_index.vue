<template>
  <div :class="[serverType,'index', 'g-wrap', 'g-hp100', 's-bgcfff']">

    <div class="hospital-list s-bgcfff">
      <div class="list-item" v-for="(c,i) in hospitalList" :key="i" @click="linkToPage('yyjs_index',{ORG_NAME:c.ORG_NAME})">
        <img class="item-img" :src="c.ORG_PIC_URL||hospitalimg" />
        <div class="item-cont">
          <div class="flex-item">
            <div class="name f-fs15 line1">{{c.ORG_NAME}}</div>
            <span class="b-tag" v-show="c.ORG_LEVEL">{{c.ORG_LEVEL}} </span>
          </div>
          <div class="addr f-mt10 f-df">
            <i class="iconfont icon-position f-mt1 f-mr5 f-fs12"></i>
            <span class="g-wp95 f-lh18">{{c.ORG_ADDRESS}}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'index',
    components: {},
    created() {
      var t = this
      // 首页菜单显示配置
      t.updateTitle('医院介绍')
      t.getHospitalList();
      // 判断在线咨询是否有新消息 
    },
    computed: {},
    data() {
      return {
        hospitalList: '',
        hospitalimg: require('@/assets/img/index/hospital-img.png'),
        isHospitalData: false,
      }
    },
    methods: {
      getHospitalList() {
        var t = this
        t.isHospitalData = true
        // if (!localStorage.hospitalList) {
        t.ajax({
          loading: false,
          serverId: 'BMFW00024',
          url: t.serverPath1,
          success: function(data) {
            t.hospitalList = data[0].CONTENTS || []
            console.log(t.hospitalList)
            localStorage.hospitalList = JSON.stringify(t.hospitalList)
            t.isHospitalData = false
          }
        })
        // } else {
        //   // t.hospitalList = JSON.parse(localStorage.hospitalList)
        //   t.isHospitalData = false
        // }
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  .index .hospital-list,
  .index .infor-list {
    padding: 0 20px;
  }

  .index .hospital-list .list-item {
    padding: 15px 0;
    font-size: 14px;
    overflow: hidden;
    border-bottom: 1px solid #ebeef5;
  }

  .index .list-item:nth-last-child(1) {
    border: 0;
  }

  .index .hospital-list .list-item .item-img {
    float: left;
    width: 88px;
    height: 70px;
    margin-right: 15px;
    border-radius: 3px;
  }

  .index .hospital-list .list-item .item-cont {
    margin-left: 98px;
    height: 70px;
  }

  .index .hospital-list .list-item .flex-item {
    display: flex;
    height: 28px;
    align-items: center;
    justify-content: space-between;
  }

  .index .hospital-list .list-item .name {
    font-weight: bold;
    width: 160px;
  }


  .index .hospital-list .list-item .addr {
    color: #909399;
  }
</style>