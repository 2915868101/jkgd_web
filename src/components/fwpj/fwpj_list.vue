<template>
  <div class="fwpj_list g-wrap">
    <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
      <van-tab title="未完成">
        <div class="record-list">
          <div class="list-item" v-for="(c,i) in recordList1" :key="i">
            <div class="item-head f-pt15 f-pr15 f-pl15 f-pb10">
              <div class="flex-item">
                <div class="s-c303133 f-fs15 f-mb8"><span class="name f-mr10">{{c.CHECKNAME}}</span> <span class="b-tag">就诊</span></div>
                <van-button class="item-btn" size="small" round color="#00c792" plain @click="doComment(c)">去评价</van-button>
              </div>
              <p class="f-lh24 s-c606266">{{c.DOCTOR_NAME||'-'}}</p>
              <p class="f-lh24 s-c909399">{{c.ORG_NAME}}</p>
            </div>
            <div class="item-cont">
              <div class="f-lh24 s-c909399"><span class="tit">就诊人：</span>{{c.XM}} {{c.TREAT_DATE}}</div>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordList1.length" description="暂无服务评价记录" />
      </van-tab>
      <van-tab title="已完成">
        <div class="record-list"> 
          <div class="list-item" v-for="(c,i) in recordList2" :key="i">
            <div class="item-head f-pt15 f-pr15 f-pl15 f-pb10">
              <div class="flex-item">
                <div class="s-c303133 f-fs15 f-mb8"><span class="name f-mr10">{{c.CHECKNAME}}</span> <span class="b-tag">就诊</span></div>
                <van-button class="item-btn" size="small" round color="#909399" plain>已评价</van-button>
              </div>
              <p class="f-lh24 s-c606266">{{c.DOCTOR_NAME||'-'}}</p>
              <p class="f-lh24 s-c909399">{{c.ORG_NAME}}</p>
            </div>
            <div class="item-cont">
              <div class="f-lh24 s-c909399"><span class="tit">就诊人：</span>{{c.XM}} {{c.TREAT_DATE}}</div>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordList2.length" description="暂无服务评价记录" />
      </van-tab>
    </van-tabs>
  </div>
</template>
<script>
export default {
  name: 'fwpj_list',
  components: {
  },
  created() {
    var t = this
    t.updateTitle('服务评价')
    // 判断是否注册
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function(data) {
      var CARD_NUMBER = data.CONTENTS[0].CARD_NUMBER
      t.getPeopleMPI(CARD_NUMBER)
    })
  },
  computed: {},
  data() {
    return {
      activeIndex: 0,
      recordList1: '',
      recordList2: '',
      yldaForm: {
        MPI: '',
        XH: '1'
      },
    }
  },
  methods: {
    // 获取MPI 
    getPeopleMPI(CARD_NUMBER) {
      var t = this
      t.ajax({
        serverId: 'WXBM00035',
        url: t.serverPath2,
        params: {
          CNUM: CARD_NUMBER
        },
        success: function(data) {
          var peopleData = data[0].CONTENTS ? data[0].CONTENTS[0] : []
          console.log(peopleData)
          t.yldaForm.MPI = peopleData.MPI || ''
          console.log(t.yldaForm.MPI)
          if (t.yldaForm.MPI) {
            t.getRecordList()
          } else {
            t.recordList1 = []
            t.recordList2 = []
          }
        }
      })
    },
    onClickTabs(index) {
      this.activeIndex = index
    },
    getRecordList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00036',
        url: t.serverPath2,
        params: t.yldaForm,
        success: function(data) {
          var recordData = data[0].CONTENTS
          console.log(recordData)
          // t.recordList1 = recordData
          t.recordList1 = []
          t.recordList2 = []
          for (let c of recordData) {
            if (!c.YYLSH) {
              t.recordList1.push(c)
            } else {
              t.recordList2.push(c)
            }
          }
        }
      })
    },
    doComment(data) {
      var t = this
      t.linkToPage('fwpj_detail', { YYID: data.TREATMENT_ID, CHECKNAME: data.CHECKNAME, ORG_NAME: data.ORG_NAME, DOCTOR_NAME: data.DOCTOR_NAME || '', })
    }
  },
  mounted() {}
}
</script>
<style scoped>
.fwpj_list>>>.van-button {
  height: 24px;
  line-height: 24px;
  min-width: 50px;
  padding: 0 8px;
}

.fwpj_list>>>.van-cell {
  padding: 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.fwpj_list>>>.van-cell__title {
  color: #606266;
}

.fwpj_list>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.fwpj_list>>>.van-tabs--line .van-tabs__wrap {
  height: 60px;
  padding-bottom: 5px;
  border-bottom: 1px solid #f4f9f9;
}

.fwpj_list>>>.van-tab__text {
  font-size: 15px;
}

.fwpj_list>>>[class*=van-hairline]::after {
  border: 0;
}

.fwpj_list>>>.van-tabs__line {
  background: #00c792;
}

.fwpj_list .record-list {
  padding: 0 15px;
  font-size: 14px;
}

.fwpj_list .record-list .list-item {
  background: #fff;
  border: 1PX solid #ebeef5;
  border-radius: 5px;
  margin: 15px 0;
  color: #606266;
}

.fwpj_list .record-list .list-item .item-head .name {
  font-weight: bold;
}

.fwpj_list .record-list .b-tag {
  background: #fef7e7;
  color: #ffc437;
  height: 18px;
  border-color: transparent;
  vertical-align: 1px;
}

.fwpj_list .record-list .flex-item {
  display: flex;
  align-content: center;
  justify-content: space-between;
}


.fwpj_list .record-list .list-item .item-cont {
  border-top: 1PX solid #ebeef5;
  border-bottom: 1PX solid #ebeef5;
  padding: 10px 15px;
}

.fwpj_list .record-list .list-item .line-item span.tit {
  display: inline-block;
  width: 80px;
  text-align-last: justify;
}

.fwpj_list .record-list .list-item .btn-item {
  text-align: right;
  padding: 10px 0;
}
</style>