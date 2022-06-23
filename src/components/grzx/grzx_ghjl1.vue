<template>
  <div class="grzx_ghjl g-wrap">
    <van-cell v-show="patientList.length" class="f-bb10" title="请选择就诊人" is-link @click="showPopup">
      <span v-if="!searchForm.HZXM">请选择</span>
      <span v-else>{{searchForm.HZXM}}</span>
    </van-cell>
    <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
      <van-tab title="全部">
        <div class="record-list">
          <div class="list-item" v-for="(c,i) in recordList1" :key="i">
            <div class="item-head f-pt15 f-pb15 f-pr15 f-pl15">
              <div class="flex-item">
                <div class="name s-c303133 f-fs15 f-mb8">{{c.ORG_NAME}}</div>
                <div class="state s-cffa54d"><i class="f-mr8 dott-icon s-bgcffa54d"></i>待面诊</div>
              </div>
              <p>
                <span class="f-mr10">{{c.GHYS}} <span v-show="c.YSJB">({{c.YSJB}})</span></span>
                <span>{{c.KSMC}}</span>
              </p>
            </div>
            <div class="item-cont">
              <div class="line-item f-lh24"><span class="tit">就诊人：</span>{{c.XM}}</div>
              <div class="line-item f-lh24"><span class="tit">就诊时间：</span>{{c.YYSJ}} {{c.TIMERANGE }}</div>
              <div class="line-item f-lh24">
                <div class="flex-item">
                  <div>
                    <span class="tit">挂号费：</span>
                    <i class="s-cf30b32">￥{{c.ORDERMON }}</i>
                  </div>
                  <van-button v-if="c.BUTTONFLAG=='1'" class="item-btn" size="mini" round color="#00c792" plain @click="doCancel(c)">取消</van-button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordList1.length" description="暂无预约挂号记录" />
      </van-tab>
      <van-tab title="未完成">
        <div class="record-list">
          <div class="list-item" v-for="(c,i) in recordList1" :key="i">
            <div class="item-head f-pt15 f-pb15 f-pr15 f-pl15">
              <div class="flex-item">
                <div class="name s-c303133 f-fs15 f-mb8">{{c.ORG_NAME}}</div>
                <div class="state s-cffa54d"><i class="f-mr8 dott-icon s-bgcffa54d"></i>待面诊</div>
              </div>
              <p>
                <span class="f-mr10">{{c.GHYS}} <span v-show="c.YSJB">({{c.YSJB}})</span></span>
                <span>{{c.KSMC}}</span>
              </p>
            </div>
            <div class="item-cont">
              <div class="line-item f-lh24"><span class="tit">就诊人：</span>{{c.XM}}</div>
              <div class="line-item f-lh24"><span class="tit">就诊时间：</span>{{c.YYSJ}} {{c.TIMERANGE }}</div>
              <div class="line-item f-lh24">
                <div class="flex-item">
                  <div>
                    <span class="tit">挂号费：</span>
                    <i class="s-cf30b32">￥{{c.ORDERMON }}</i>
                  </div>
                  <van-button v-if="c.BUTTONFLAG=='1'" class="item-btn" size="mini" round color="#00c792" plain @click="doCancel(c)">取消</van-button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordList1.length" description="暂无预约挂号记录" />
      </van-tab>
      <van-tab title="已完成">
        <div class="record-list">
          <div class="list-item" v-for="(c,i) in recordList2" :key="i">
            <div class="item-head f-pt15 f-pb15 f-pr15 f-pl15">
              <div class="flex-item">
                <div class="name s-c303133 f-fs15 f-mb8">{{c.ORG_NAME}}</div>
              </div>
              <p>
                <span class="f-mr10">{{c.GHYS}} <span v-show="c.YSJB">({{c.YSJB}})</span></span>
                <span>{{c.KSMC}}</span>
              </p>
            </div>
            <div class="item-cont">
              <div class="line-item f-lh24"><span class="tit">就诊人：</span>{{c.XM}}</div>
              <div class="line-item f-lh24"><span class="tit">就诊时间：</span>{{c.YYSJ}} {{c.TIMERANGE }}</div>
              <div class="line-item f-lh24">
                <div>
                  <span class="tit">挂号费：</span>
                  <i class="s-cf30b32">￥{{c.ORDERMON }}</i>
                </div>
              </div>
              <div class="btn-item">
                <van-button v-if="c.DELFLAG==1" class="item-btn f-mr10" size="mini" round color="#909399" disabled plain>已取消</van-button>
                <van-button v-else class="item-btn f-mr10" size="mini" round color="#909399" disabled plain>已完成</van-button>
                <van-button class="item-btn" size="mini" round color="#00c792" plain v-if="c.PJBS==0" @click="linkToPage('grzx_fwpj',{YSID:c.YSID,YYID:c.YYID})">去评价</van-button>
                <van-button class="item-btn" size="mini" round color="#909399" disabled plain v-else>已评价</van-button>
              </div>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordList2.length" description="暂无预约挂号记录" />
      </van-tab>
    </van-tabs>
    <van-popup v-model="isShowPopup" position="bottom" :style="{height : '30%'}">
      <div class="pop-head">
        <span>请选择就诊人</span>
        <van-icon name="cross" @click.stop="doClose"></van-icon>
      </div>
      <div class="pop-cont">
        <div class="select-list">
          <div class="select-item" v-for="item in patientList" :key="item.CNUM" @click="doSelect(item)">
            <div class="item-icon" v-if="!item.TX">
              <i class="iconfont icon-user"></i>
            </div>
            <img class="item-icon" v-else :src="item.TX" />
            <div class="item-name">{{item.XM}}</div>
          </div>
          <div class="select-item" @click="linkToPage('register_card',{isRegister:true})">
            <div class="item-icon">
              <i class="iconfont icon-add"></i>
            </div>
            <div class="item-name">添加就诊人</div>
          </div>
        </div>
      </div>
    </van-popup>
  </div>
</template>
<script>
export default {
  name: 'grzx_ghjl',
  components: {
  },
  created() {
    var t = this
    t.updateTitle('挂号记录')
    t.searchForm1.OPENID = t.wx_userInfo.openid
    t.getRecordList()
  },
  computed: {},
  data() {
    return {
      searchForm: {
        ORG_NAME: '',
        ORG_ID: '',
        HZXM: '',
        CARDNUM: ''
      },
      searchForm1: {
        FLAG: 'Query',
        OPENID: '',
        XM: '',
        CARDTYPE: '',
        CNUM: '',
        SJHM: ''
      },
      isShowPopup: false,
      patientList: [],
      activeIndex: 0,
      recordList1: '',
      recordList2: ''
    }
  },
  methods: {
    onClickTabs(index) {
      this.activeIndex = index
    },
    getRecordList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00007',
        url: t.serverPath1,
        params: {
          OPENID: t.wx_userInfo.openid,
        },
        success: function(data) {
          var recordData = data[0].CONTENTS
          console.log(recordData)
          t.recordList1 = recordData
          t.recordList1 = []
          t.recordList2 = []
          for (let c of recordData) {
            if ((c.BUTTONFLAG === 0 && c.QHFLAG === 1 && c.DELFLAG === '0') || (c.BUTTONFLAG === 1 && c.DELFLAG === '0')) {
              t.recordList1.push(c)
            } else {
              t.recordList2.push(c)
            }
          }
        }
      })
    },
    doCancel(data) {
      console.log(data)
      var t = this
      if (data.BUTTONFLAG == '1') {
        t.$dialog.confirm({
            message: '确认取消预约挂号?取消成功后，预约挂号费用将在三个工作日退回到账户!',
          })
          .then(() => {
            t.ajax({
              serverId: 'WXBM00008',
              url: t.serverPath1,
              params: {
                YYID: data.YYID,
                ORG_ID: data.ORG_ID,
                OPENID: t.wx_userInfo.openid,
              },
              success: function(data) {
                if (data[0].CODE == 0) {
                  t.$toast(data[0].MSG)
                  setTimeout(function() {
                    t.getRecordList()
                  }, 2000)
                } else {
                  t.$toast(data[0].MSG)
                }
              }
            })
          })
          .catch(() => {
            // on cancel
          });

      } else {
        t.$alert('当天不能取消')
      }
    },
    getPatientDef() {
      var t = this
      t.ajax({
        serverId: 'WXBM00015',
        url: t.serverPath1,
        params: {
          FLAG: 'Query',
          OPENID: t.wx_userInfo.openid,
        },
        success: function(data) {
          var _data = data[0].CONTENTS ? data[0].CONTENTS[0] : []
          t.searchForm.HZXM = _data.XM
          t.searchForm.CARDNUM = _data.CNUM
          t.searchForm.SJHM = _data.SJHM
          console.log(t.searchForm)
        }
      })
    },
    getPatientList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00014',
        url: t.serverPath1,
        params: t.searchForm1,
        success: function(data) {
          t.patientList = data[0].CONTENTS
        }
      })
    },
    showPopup() {
      var t = this
      t.isShowPopup = true
      t.getPatientList()
    },
    doClose() {
      this.isShowPopup = false
    },
    doSelect(item) {
      var t = this
      t.isShowPopup = false
      console.log(item)
      t.searchForm.HZXM = item.XM
      t.searchForm.CARDNUM = item.CNUM
      t.searchForm.SJHM = item.SJHM
    },
  },
  mounted() {}
}
</script>
<style scoped>
.grzx_ghjl>>>.van-button {
  height: 22px;
  line-height: 22px;
  min-width: 50px;
  padding: 0 8px;
}

.grzx_ghjl>>>.van-cell {
  padding: 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.grzx_ghjl>>>.van-cell__title {
  color: #606266;
}

.grzx_ghjl>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.grzx_ghjl .f-bb10 {
  border-bottom: 10px solid #f4f9f9;
}

.grzx_ghjl>>>.van-tabs--line .van-tabs__wrap {
  height: 60px;
  padding-bottom: 5px;
  border-bottom: 1px solid #f4f9f9;
}

.grzx_ghjl>>>.van-tab__text {
  font-size: 15px;
}

.grzx_ghjl>>>[class*=van-hairline]::after {
  border: 0;
}

.grzx_ghjl>>>.van-tabs__line {
  background: #00c792;
}

/*popup 样式*/
.grzx_ghjl .pop-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 15px;
  color: #c0c4cc;
  padding: 15px;
}

.grzx_ghjl .pop-cont {
  font-size: 15px;
  padding: 15px;
}

.grzx_ghjl .select-list {
  display: flex;
  flex-wrap: wrap;
  text-align: center;
}

.grzx_ghjl .select-list .select-item {
  width: 25%;
  margin-bottom: 15px;
}

.grzx_ghjl .select-list .select-item .item-icon {
  display: inline-block;
  background: #d2f6ec;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
  margin-bottom: 8px;
}

.grzx_ghjl .select-list .select-item .iconfont {
  color: #00c792;
  font-size: 28px;
}

.grzx_ghjl .record-list {
  padding: 0 15px;
  font-size: 14px;
}

.grzx_ghjl .record-list .list-item {
  background: #fff;
  border: 1PX solid #ebeef5;
  border-radius: 5px;
  margin: 15px 0;
  color: #606266;
}

.grzx_ghjl .record-list .list-item .item-head .name {
  font-weight: bold;
}

.grzx_ghjl .record-list .flex-item {
  display: flex;
  justify-content: space-between;
}

.grzx_ghjl .record-list .dott-icon {
  display: inline-block;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  vertical-align: 2px;
}

.grzx_ghjl .record-list .list-item .item-cont {
  border-top: 1PX solid #ebeef5;
  border-bottom: 1PX solid #ebeef5;
  padding: 10px 15px;
}

.grzx_ghjl .record-list .list-item .line-item span.tit {
  display: inline-block;
  width: 80px;
  text-align-last: justify;
}

.grzx_ghjl .record-list .list-item .btn-item {
  text-align: right;
  padding: 10px 0;
}
</style>