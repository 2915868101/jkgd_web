<template>
  <div class="grzx_jfjl g-wrap">
    <van-cell class="f-bb10" title="请选择就诊人" is-link @click="showPopup">
      <span v-if="!searchForm.HZXM">请选择</span>
      <span v-else>{{searchForm.HZXM}}</span>
    </van-cell>
    <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
      <van-tab title="门诊缴费">
        <div class="record-list">
          <div class="list-item">
            <div class="item-head flex-item f-pt15 f-pb15 f-pr15 f-pl15">
              <p class="name s-c303133 f-fs15">医院医院</p>  
              <p class="f-lh18"><span>金额：</span><span class="s-cf30b32">￥ 18.00</span></p>
            </div>
            <div class="item-cont">
              <div class="line-item f-lh24"><span class="tit">就诊科室：</span>2020-34-33</div> 
              <div class="line-item f-lh24"><span class="tit">就诊人：</span>xxx</div>
              <div class="line-item f-lh24"><span class="tit">就诊时间：</span>2020-34-33</div>
            </div>
            <div class="item-bott flex-item f-pt15 f-pb15 f-pr15 f-pl15">
              <p class="f-pt5 f-pb5">2010-02-09 20:00:00</p> 
              <div class="state s-c00c792"><i class="f-mr8 dott-icon s-bgc00c792"></i>支付成功</div>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordData.length" description="暂无预约挂号记录" /> 
      </van-tab>
      <van-tab title="住院缴费">
        <div class="record-list">
          <div class="list-item">
            <div class="item-head flex-item f-pt15 f-pb15 f-pr15 f-pl15">
              <p class="name s-c303133 f-fs15">医院医院</p>  
              <p class="f-lh18"><span>金额：</span><span class="s-cf30b32">￥ 18.00</span></p>
            </div>
            <div class="item-cont">
              <div class="line-item f-lh24"><span class="tit">住院科室：</span>2020-34-33</div> 
              <div class="line-item f-lh24"><span class="tit">就诊人：</span>xxx</div>
              <div class="line-item f-lh24"><span class="tit">住院号：</span>2020-34-33</div>
            </div>
            <div class="item-bott flex-item f-pt15 f-pb15 f-pr15 f-pl15">
              <p class="f-pt5 f-pb5">2010-02-09 20:00:00</p> 
              <div class="state s-c00c792"><i class="f-mr8 dott-icon s-bgc00c792"></i>支付成功</div>
            </div>
          </div>
        </div>
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
        </div>
      </div>
    </van-popup>
  </div>
</template>
<script>
export default {
  name: 'grzx_jfjl',
  components: {
  },
  created() {
    var t = this
    t.updateTitle('缴费记录')
    t.searchForm1.OPENID = t.wx_userInfo.openid
    t.getPatientDef()
    t.getPatientList()
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
      recordData: ''
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
          t.recordData = data[0].CONTENTS 
          console.log(t.recordData) 
        }
      })
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
          var _data = data[0].CONTENTS[0]
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
.grzx_jfjl>>>.van-button {
  height: 22px;
  line-height: 22px;
  width: 50px;
  padding: 0 8px;
}

.grzx_jfjl>>>.van-cell {
  padding: 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.grzx_jfjl>>>.van-cell__title {
  color: #606266;
}

.grzx_jfjl>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
} 

.grzx_jfjl>>>.van-tabs--line .van-tabs__wrap {
  height: 60px;
  padding-bottom: 5px;
  border-bottom: 1px solid #f4f9f9;
}

.grzx_jfjl>>>.van-tab__text {
  font-size: 15px;
}

.grzx_jfjl>>>[class*=van-hairline]::after {
  border: 0;
}

.grzx_jfjl>>>.van-tabs__line {
  background: #00c792;
}

/*popup 样式*/
.grzx_jfjl .pop-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 15px;
  color: #c0c4cc;
  padding: 15px;
}

.grzx_jfjl .pop-cont {
  font-size: 15px;
  padding: 15px;
}

.grzx_jfjl .select-list {
  display: flex;
  flex-wrap: wrap;
  text-align: center;
}

.grzx_jfjl .select-list .select-item {
  width: 25%;
  margin-bottom: 15px;
}

.grzx_jfjl .select-list .select-item .item-icon {
  display: inline-block;
  background: #d2f6ec;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
  margin-bottom: 8px;
}

.grzx_jfjl .select-list .select-item .iconfont {
  color: #00c792;
  font-size: 28px;
}

.grzx_jfjl .record-list {
  padding: 0 15px;
  font-size: 14px;
}

.grzx_jfjl .record-list .list-item {
  background: #fff;
  border: 1PX solid #ebeef5;
  border-radius: 5px;
  margin: 15px 0;
  color: #606266;
}

.grzx_jfjl .record-list .list-item .item-head .name {
  font-weight: bold;
}
.grzx_jfjl .record-list .flex-item{
  display: flex;
  align-items: center;
  justify-content: space-between;
} 
.grzx_jfjl .record-list .dott-icon{
  display: inline-block;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  vertical-align: 2px;
}
.grzx_jfjl .record-list .list-item .item-cont {
  border-bottom: 1PX solid #ebeef5;
  padding: 0 15px 10px;
} 

.grzx_jfjl .record-list .list-item .line-item span.tit {
  display: inline-block;
  width: 80px;
  text-align-last: justify;
}
</style>