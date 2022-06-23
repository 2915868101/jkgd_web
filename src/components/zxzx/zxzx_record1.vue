<template>
  <div class="zxzx_record g-wrap g-hp100 s-bgcfff">
    <div class="records-list s-bgcfff" v-if="recordList.length">
      <div class="list-item" v-for="(c,i) in recordList" :key="i" @click="doDetail(c)">
        <div class="item-date" v-if="c.treat_date">
          <span v-if="c.treat_date.time"> {{$moment(c.treat_date.time).format('YYYY-MM-DD')}}</span>
          <span v-else> {{$moment(c.treat_date).format('YYYY-MM-DD')}}</span>
          <i class="node-icon"></i>
        </div>
        <div class="item-cont">
          <i class="iconfont icon-ylda s-c00c792 f-fs14"></i>
          <i class="b-border"></i>
          <div class="desc">
            <p class="f-fs15 f-mb5 s-c00c792 line1">{{c.treatment_conclusion||'--'}}</p>
            <p class="orgname ">
              <span class="line1">{{c.org_name}}</span>
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
    t.updateTitle(t.queryData.TITLE)
    var day = t.$moment(new Date()).diff(t.$moment(t.queryData.TODAY), 'days')
    if (day >= 1) {
      t.isValid = false
    } else {
      t.getBgList()
    } 
  },
  computed: {},
  data() {
    return {
      isValid: true,
      recordList: [],
      isLately: false
    }
  },
  methods: {
    // 判断是否有报告记录
    getBgList() {
      var t = this
      t.isLately = t.queryData.KSRQ == '' ? true : false
      t.queryData.KSRQ = t.queryData.KSRQ || t.$moment(new Date()).add(-2, 'y').format("YYYY-MM-DD")
      t.ajax({
        serverId: 'BMFW00016',
        url: t.serverPath2,
        params: t.queryData,
        success: function(data) {
          console.log(data[0].CONTENTS)
          if (!t.isLately) {
            t.recordList = data[0].CONTENTS
          } else {
            t.recordList.push(data[0].CONTENTS[0])
          }
        }
      })
    },
    doDetail(data) {
      var t = this
      if (t.queryData.TYPE == 1) {
        t.linkToPage('bgcx_jc', { id: data.treatment_id })
      } else if (t.queryData.TYPE == 2) {
        t.linkToPage('bgcx_jy', { id: data.treatment_id })
      }
    }
  },
  mounted() {},
}
</script>
<style scoped>
/*.zxzx_record .record-list .list-item {
  padding: 15px;
  font-size: 14px;
  border-bottom: 1px solid #eef0f6;
  color: #909399;
}

.zxzx_record .record-list .list-item .name {
  margin: 10px 0 6px;
  font-size: 15px;
}*/


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

.zxzx_record .records-list .list-item:not(:last-child) .item-date {
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