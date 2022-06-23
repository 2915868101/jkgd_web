<template>
  <div class="zxzx_record g-wrap g-hp100 s-bgcfff">  
    <div class="records-list s-bgcfff" v-if="recordList.length">
      <div class="list-item" v-for="(c,i) in recordList" :key="i" @click="doDetail(c)">
        <div class="item-date">
          {{$moment(c.TREAT_DATE).format('YYYY-MM-DD')}}
          <i class="node-icon"></i>
        </div>
        <div class="item-cont">
          <i class="iconfont icon-ylda s-c00c792 f-fs14"></i>
          <i class="b-border"></i>
          <div class="desc">
            <p class="f-fs15 f-mb5 s-c00c792 line1">{{c.CHECKNAME}}</p>
            <p class="orgname ">
              <span class="line1">{{c.ORG_NAME}}</span>
              <i class="iconfont icon-arrow-right f-fs11 s-cc0c4cc"></i>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="f-fs13 f-tac f-pt20 s-c606266" v-if="!isValid">时间超过1天，链接已失效</div>
  </div>
</template>
<script>
export default {
  name: 'zxzx_record',
  components: {
  },
  created() {
    var t = this
    // xh 1:就诊记录 3:健康体检 4:高血压 5:糖尿病
    t.updateTitle(t.queryData.TITLE) 
    var day = t.$moment(new Date()).diff(t.$moment(t.queryData.TODAY), 'days') 
    if (day >= 1) {
      t.isValid=false
    } else { 
      t.getYldaList()
    } 

  },
  computed: {},
  data() {
    return {
      isValid: true,
      recordList: []
    }
  },
  methods: {
    // 判断是否有报告记录
    getYldaList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00036',
        url: t.serverPath2,
        params: t.queryData,
        success: function(data) {
          console.log(data[0].CONTENTS)
          if (t.queryData.START) {
            t.recordList = data[0].CONTENTS
          } else {
            t.recordList.push(data[0].CONTENTS[0])
          }
        }
      })
    },
    // 查看详情
    doDetail(data) {
      var t = this 
      if (data.TYPE == 'EMR_EXAM_IMAGE_REP') { //检验报告详情
        t.linkToPage('bgcx_jc', { id: data.TREATMENT_ID, age: data.age })
      } else if (data.TYPE == 'EMR_INSPECTION') { //检查报告详情
        t.linkToPage('bgcx_jy', { id: data.TREATMENT_ID, age: data.age })
      } else if (data.TYPE.indexOf('PHS_CHILD_PHYSICAL_EXAM') != -1) { //儿童体检
        t.linkToPage('PHS_CHILD_PHYSICAL_EXAM', { MPI: t.queryData.MPI, TREATMENT_ID: data.TREATMENT_ID, TYPE: data.TYPE, TREAT_TABLENAME: data.TREAT_TABLENAME, age: data.age, pageTitle: data.CHECKNAME }) 
      } else if (data.TYPE == 'PHS_FVBP') { // 孕产妇保健手册和初检记录
        t.linkToPage('PHS_MATERNAL_MANUAL', { MPI: t.queryData.MPI, TREATMENT_ID: data.TREATMENT_ID, TYPE: data.TYPE, TREAT_TABLENAME: data.TREAT_TABLENAME, age: data.age, pageTitle: data.CHECKNAME })
      }else { 
        t.linkToPage(data.TYPE, { MPI: t.queryData.MPI, TREATMENT_ID: data.TREATMENT_ID, TYPE: data.TYPE, TREAT_TABLENAME: data.TREAT_TABLENAME, age: data.age, pageTitle: data.CHECKNAME })
      }
      console.log(data)
    }
  },
  mounted() {},
}
</script>
<style scoped>

.zxzx_record .records-list {
  font-size: 14px;
  color: #606266;
  padding: 15px 18px;
}

.zxzx_record .records-list .list-item {
  position: relative;
  min-height: 65px;
}

.zxzx_record .records-list .list-item .item-date {
  width: 85px;
  height: 100%;
  line-height: 20px;
  position: absolute;
  left: 0;
}
.zxzx_record .records-list .list-item:not(:last-child) .item-date{ 
  background: url(../../assets/img/jkda/border-dotted.png) repeat-y right;
}
.zxzx_record .records-list .list-item .node-icon {
  display: inline-block;
  width: 5px;
  height: 5px;
  background: #909399;
  border-radius: 50%;
  position: absolute;
  top: 5px;
  right: -2PX;
}

.zxzx_record .records-list .list-item .item-cont {
  position: absolute;
  height: 100%;
  left: 100px;
  right: 0;
}

.zxzx_record .records-list .list-item .item-cont .icon-ylda {
  position: relative;
  top: 3px;
}

.zxzx_record .records-list .list-item .b-border {
  border-left: 1px solid #c0c4cc;
  width: 4px;
  height: 10px;
  display: inline-block;
  margin: 0 8px 0 10px;
  vertical-align: -2px;
}

.zxzx_record .records-list .list-item .desc {
  position: absolute;
  left: 35px;
  right: 0;
  top: 0;
  line-height: 20px;
  height: 100%;
}

.zxzx_record .records-list .list-item .orgname {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.zxzx_record .records-list .list-item .orgname span {
  display: inline-block;
  font-size: 14px;
  color: #909399;
  width: 190px;
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
}
</style>