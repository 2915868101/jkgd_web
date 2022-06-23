<template>
  <div class="zxzx_detail g-wrap g-hp100 s-bgcf4f9f9" ref="wrapMain">
    <div v-show="!queryData.isEnd">
      <div class="stop-wrap s-bgcfff f-df f-dfc f-fs13 s-c303133" v-show="!queryData.isDoctor" @click="doStop">
        <i class="iconfont icon-stop f-mr3 f-fs13 s-c00c792"></i>结束咨询
      </div>
      <div class="g-h40" v-show="!queryData.isDoctor"></div>
    </div>
    <div class="mobile-page s-bgcf4f9f9" ref="chatMain" v-if="msgList.length">
      <div class="date-group">{{$moment(msgList[0].INSERTDATE).format('YYYY-MM-DD HH:mm:ss')}}</div>
      <div :class="['msg-group animated fadeInUp',c.TYPE==type1?'user-group': 'admin-group']" v-for="(c,i) in msgList" :key="i">
        <img class="g-w40 g-h40 f-brp50" v-show="c.TYPE==type2" :src="c.TX||$store.state.def_headimg||$store.state.def_adminimg" />
        <div class="msg-cont">
          <div class="reply-box" @click="toLinks(c.CONTENT)">
            <!-- <span  :class="!isLinks(c.CONTENT)||'f-tdu'">{{formatLinks(c.CONTENT)}}</span> -->
            <div v-if="isLinks(c.CONTENT)" class="link-box">
              <div class="line1">{{formatLinks(c.CONTENT)}}</div>
              <div class=" f-df f-dfj f-mt5">
                <span :class="['line2 txt',c.TYPE==type1||'s-c909399']">{{c.CONTENT}}</span>
                <i class="iconfont icon-links"></i>
              </div>
            </div>
            <span v-else>{{c.CONTENT}}</span>
          </div>
        </div>
        <img class="g-w40 g-h40 f-brp50" v-show="c.TYPE==type1" :src="c.TX||$store.state.def_headimg" />
      </div>
    </div>
    <!-- 输入消息 -->
    <div v-show="!queryData.isEnd">
      <div v-show="!queryData.isDoctor">
        <div class="g-h50"></div>
        <div class="jztype-wrap s-bgcf4f9f9 f-df f-dfc" v-show="isShow">
          <div class="btn f-df6" @click="showPopup(1)"><i class="s-c00c792 iconfont f-mr5 icon-jzicon1"></i><span>报告</span></div>
          <div class="btn f-df8" @click="showPopup(2)"><i class="s-c00c792 iconfont f-mr5 icon-jzicon2"></i><span>就诊记录</span></div>
          <div class="btn f-df8" @click="showPopup(3)"><i class="s-c00c792 iconfont f-mr5 icon-jzicon3"></i><span>慢病史</span></div>
          <div class="btn f-df8" @click="showPopup(4)"><i class="s-c00c792 iconfont f-mr5 icon-jzicon4"></i><span>体检报告</span></div>
        </div>
      </div>
      <div class="g-h50" id="target"></div>
      <div class="msg-input-bgk f-bs3" v-show="isShow">
        <div class="msg-input-outer">
          <van-field v-model="value" :placeholder="queryData.isDoctor?'请输入回复内容':'请输入您想要咨询的问题'" />
          <div class="button button-fill" id="send-msg" @click="doSendmsg">发送</div>
        </div>
      </div>
    </div>
    <van-popup v-model="isShowPopup" position="bottom" :style="{height : '35%'}" @click-overlay="closePopup">
      <div class="pop-head">
        <span>请选择时间</span>
        <div class="s-c00c792" @click="doSearch">确认</div>
      </div>
      <div class="pop-cont">
        <div class="select-list">
          <van-button size="small" :class="[currType===i?'on':'','select-item']" color="#00c792" plain round v-for="(c,i) in dateList" :key="i" @click="doSelect1(c,i)">{{c.date}}</van-button>
        </div>
      </div>
      <div class="pop-head" v-if="paramsData.jzType=='1'">
        <span>请选择报告类型</span>
      </div>
      <div class="pop-cont" v-if="paramsData.jzType=='1'">
        <div class="select-list">
          <van-button size="small" :class="[currbgType===i?'on':'','select-item']" color="#00c792" plain round v-for="(c,i) in bgtypeList" :key="i" @click="doSelect2(c,i)">{{c.txt}}</van-button>
        </div>
      </div>
      <div class="pop-head" v-if="paramsData.jzType=='3'">
        <span>请选择慢病类型</span>
      </div>
      <div class="pop-cont" v-if="paramsData.jzType=='3'">
        <div class="select-list">
          <van-button size="small" :class="[currmbType===i?'on':'','select-item']" color="#00c792" plain round v-for="(c,i) in mbtypeList" :key="i" @click="doSelect3(c,i)">{{c.txt}}</van-button>
        </div>
      </div>
    </van-popup>
  </div>
</template>
<script>
export default {
  name: 'zxzx_detail',
  components: {
  },
  created() {
    var t = this
    t.updateTitle('咨询详情')
    // 判断是否发起咨询还是继续已有的  
    console.log(t.queryData)
    t.queryData.CONSULTID = t.queryData.CONSULTID || sessionStorage.getItem("NEW_CONSULTID")
    if (t.queryData.CONSULTID) {
      t.getPLList(t.queryData.CONSULTID)
      t.timer = setInterval(function() {
        t.getPLList(t.queryData.CONSULTID)
      }, 3000)
    } else {
      t.isShow = true
    }
    t.type1 = t.queryData.isDoctor ? '1' : '0'
    t.type2 = t.queryData.isDoctor ? '0' : '1'
    t.bgForm.CARDNUM = t.queryData.CARDNUM
    t.getPeopleMPI()
    t.isNewMsg()
  },
  computed: {},
  data() {
    var t = this
    return {
      isShow: false,
      timer: '',
      currType: '',
      currbgType: '',
      currmbType: '',
      isShowPopup: false,
      type1: '',
      type2: '',
      value: '',
      msgList: [],
      dateList: [
        { date: '最近1次', type: '1' },
        { date: '最近3个月', type: '2' },
        { date: '最近半年', type: '3' },
      ],
      bgtypeList: [
        { txt: '检查报告', type: '1' },
        { txt: '检验报告', type: '2' },
      ],
      KSRQ: '',
      JSRQ: t.$moment(new Date()).format('YYYY-MM-DD'),
      paramsData: {},
      bgForm: {
        CARDNUM: '',
        KSRQ: '',
        JSRQ: t.$moment(new Date()).format('YYYY-MM-DD'),
        TYPE: '1',
      },
      bgList: '',
      yldaForm: {
        MPI: '',
        START: '',
        END: t.$moment(new Date()).format('YYYY-MM-DD'),
        XH: '',
      },
      yldaList: '',
      mbtypeList: [
        // { txt: '全部', type: '0' },
        { txt: '高血压', type: '4' },
        { txt: '糖尿病', type: '5' },
      ],
    }
  },
  methods: {
    // 患者进入设置为已浏览
    isNewMsg() {
      var t = this
      // if (t.queryData.isDoctor) { 
      t.ajax({
        loading: false,
        serverId: 'WXPL00003',
        url: t.serverPath2,
        params: {
          GRADE: t.queryData.CONSULTID
        },
        success: function(data) {
          console.log(data)
        }
      })
      // }
    },
    // 获取MPI 
    getPeopleMPI() {
      var t = this
      t.ajax({
        serverId: 'WXBM00035',
        url: t.serverPath2,
        params: {
          CNUM: t.queryData.CARDNUM
        },
        success: function(data) {
          var peopleData = data[0].CONTENTS ? data[0].CONTENTS[0] : []
          console.log(peopleData)
          t.yldaForm.MPI = peopleData.MPI
        }
      })
    },
    // 弹出框关闭按钮
    closePopup() {
      var t = this
      t.currType = ''
      t.currbgType = ''
      t.currmbType = ''
      t.isShowPopup = false
    },
    // 弹出框确认按钮
    doSearch() {
      var t = this
      if (!t.paramsData.dateType) {
        t.$toast('未选择时间')
      } else if (t.paramsData.jzType == '1' && t.paramsData.bgType) {
        t.getBgList()
      } else if (t.paramsData.jzType == '2') {
        t.getYldaList(1)
      } else if (t.paramsData.jzType == '3' && t.paramsData.mbType) {
        t.getYldaList(t.paramsData.mbType)
      } else if (t.paramsData.jzType == '4') {
        t.getYldaList(3)
      }
    },
    // 时间选择
    doSelect1(data, index) {
      var t = this
      t.currType = index
      t.paramsData.dateType = data.type
      if (data.type == '1') {
        t.KSRQ = ''
      } else if (data.type == '2') {
        t.KSRQ = t.$moment().add('days', -90).format('YYYY-MM-DD')
      } else if (data.type == '3') {
        t.KSRQ = t.$moment().add('days', -182).format('YYYY-MM-DD')
      }
    },
    // 报告类型选择
    doSelect2(data, index) {
      var t = this
      t.currbgType = index
      t.paramsData.bgType = data.type
    },
    // 报告类型选择
    doSelect3(data, index) {
      var t = this
      t.currmbType = index
      t.paramsData.mbType = data.type
    },
    // 判断是否有报告记录
    getBgList() {
      var t = this
      t.bgForm.KSRQ = t.KSRQ || t.$moment(new Date()).add(-2, 'y').format("YYYY-MM-DD")
      t.bgForm.TYPE = t.paramsData.bgType
      var title = ''
      if (t.paramsData.dateType == 1) {
        title = '最近一次报告记录'
      } else if (t.paramsData.dateType == 2) {
        title = '最近三个月报告记录'
      } else if (t.paramsData.dateType == 3) {
        title = '最近半年报告记录'
      }
      t.ajax({
        serverId: 'BMFW00016',
        url: t.serverPath2,
        params: t.bgForm,
        success: function(data) {
          t.bgList = data[0].CONTENTS
          if (!t.bgList.length) {
            t.$toast('暂无记录')
          } else {
            if (t.paramsData.dateType == '1') { t.bgForm.KSRQ = '' }
            var today = t.$moment(new Date()).format('YYYY-MM-DD')
            t.value = t.basePath + 'zxzx_record1?data=%7B%22CARDNUM%22%3A%22' + t.bgForm.CARDNUM + '%22,%22KSRQ%22%3A%22' + t.bgForm.KSRQ + '%22,%22JSRQ%22%3A%22' + t.bgForm.JSRQ + '%22,%22TYPE%22%3A%22' + t.bgForm.TYPE + '%22,%22TODAY%22%3A%22' + today + '%22,%22TITLE%22%3A%22' + title + '%22%7D'
            t.dosave(0)
            t.closePopup()
          }
        }
      })
    },
    // 判断是否有就诊记录 检验报告
    getYldaList(type) {
      var t = this
      t.yldaForm.START = t.KSRQ
      t.yldaForm.XH = type

      var title = ''
      var time = ''
      if (t.paramsData.dateType == 1) {
        time = '最近一次'
      } else if (t.paramsData.dateType == 2) {
        time = '最近三个月'
      } else if (t.paramsData.dateType == 3) {
        time = '最近半年'
      }
      if (type == 1) {
        title = '就诊记录'
      } else if (type == 3) {
        title = '体检报告记录'
      } else if (type == 4) {
        title = '高血压记录'
      } else if (type == 5) {
        title = '糖尿病记录'
      }
      title = time + '' + title
      if (!t.yldaForm.MPI) {
        t.$toast('暂无记录')
      } else {
        t.ajax({
          serverId: 'WXBM00036',
          url: t.serverPath2,
          params: t.yldaForm,
          success: function(data) {
            console.log(data)
            t.yldaList = data[0].CONTENTS
            if (!t.yldaList.length) {
              t.$toast('暂无记录')
            } else {
              console.log(data)
              var today = t.$moment(new Date()).format('YYYY-MM-DD')
              t.value = t.basePath + 'zxzx_record2?data=%7B%22MPI%22%3A%22' + t.yldaForm.MPI + '%22,%22START%22%3A%22' + t.yldaForm.START + '%22,%22END%22%3A%22' + t.yldaForm.END + '%22,%22XH%22%3A%22' + t.yldaForm.XH + '%22,%22TITLE%22%3A%22' + title + '%22,%22TODAY%22%3A%22' + today + '%22%7D'
              t.dosave(0)
              t.closePopup()
            }
          }
        })
      }
    },
    // 展示弹出窗
    showPopup(type) {
      var t = this
      t.paramsData.jzType = type
      t.isShowPopup = true
    },
    // 结束咨询
    doStop() {
      var t = this
      if (t.queryData.CONSULTID) {
        t.$dialog.confirm({
          message: '确认结束在线咨询？'
        }).then(() => {
          t.ajax({
            method: 'post',
            serverId: 'WXPL00002',
            params: {
              CLOSEDIALOG: t.queryData.CONSULTID
            },
            url: t.serverPath1,
            success(data) {
              if (data[0].CODE == 0) {
                t.goBack(-1)
              }
            }
          })
        }).catch(() => {
          // on cancel
        })
      } else {
        t.$toast('未进行咨询')
      }

    },
    // 判断是否是链接
    isLinks(data) {
      if (data.indexOf('https://') != -1 || data.indexOf('http://') != -1) {
        return true
      } else {
        return false
      }
    },
    toLinks(data) {
      if (this.isLinks(data)) {
        window.location.href = data
      }
    },
    formatLinks(data) {
      var t = this
      if (this.isLinks(data)) {
        var urlParams = t.GetRequest(data)
        urlParams = JSON.parse(urlParams.data).TITLE || ''
        return urlParams ? urlParams : data
      } else {
        return data
      }
    },
    GetRequest(url) {
      var theRequest = new Object();
      if (url.indexOf("?") != -1) {
        url = url.substr(url.indexOf("?"), url.length)
        var str = url.substr(1)
        var strs = str.split("&")
        for (var i = 0; i < strs.length; i++) {
          theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
        }
      }
      return theRequest;
    },
    // 发送咨询信息
    doSendmsg() {
      var t = this
      if (t.value) {
        if (t.queryData.isDoctor) {
          t.dosave('1');
        } else {
          t.dosave('0');
        }
      }
    },
    dosave(typesign) {
      var t = this
      var params = {
        OPENID: t.wx_userInfo.openid,
        CONTENT: t.value,
        GRADE: t.queryData.CONSULTID || '0',
        TYPE: typesign,
        YSID: t.queryData.YSID || '',
        FORMDATE: t.$moment(new Date()).format('YYYY-MM-DD HH:mm:ss')
      }
      t.ajax({
        method: 'post',
        serverId: 'WXPL00002',
        params: params,
        url: t.serverPath1,
        success(data) {
          if (data[0].CODE == 0) { //进行中的咨询
            if (t.queryData.CONSULTID) {
              t.getPLList(t.queryData.CONSULTID)
            } else { //发起新的咨询 
              sessionStorage.setItem("NEW_CONSULTID", data[0].MSG)
              t.queryData.CONSULTID = data[0].MSG
              t.getPLList(t.queryData.CONSULTID)
            }
            t.value = ''
          }
        }
      })
    },
    /*获取某一咨询内容相关的详细内容*/
    getPLList(consultid) {
      var t = this
      t.ajax({
        loading: false,
        serverId: 'WXPL00001',
        url: t.serverPath1,
        params: {
          consultid: consultid
        },
        success: function(data) {
          t.msgList = data
          console.log(t.msgList)
          t.$nextTick(function() {
            t.isShow = true
            document.getElementById("target").scrollIntoView(false)
          })
        }
      })
    },
  },
  beforeDestroy() {
    var t = this
    clearInterval(t.timer)
    sessionStorage.setItem("NEW_CONSULTID", '')
  },
  mounted() {},
}
</script>
<style scoped>
.zxzx_detail .link-box {
  min-width: 100px;
}

.zxzx_detail .link-box .txt {
  width: calc(100% - 40px);  
}

.zxzx_detail .link-box .line2 {
  white-space: pre-wrap;
  word-wrap: break-word;
}

.zxzx_detail .link-box .iconfont {
  width: 32px;
  height: 32px;
  line-height: 32px;
  text-align: center;
  color: #fff;
  background: #c0c4cc;
  font-size: 20px;
  margin-top: 2px;
}
.zxzx_detail .user-group .link-box .iconfont {
  color:  #747272;
  background: #eef1f5;
}
.zxzx_detail .stop-wrap {
  background: #fff;
  height: 42px;
  padding: 0 20px;
  align-items: center;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 4;
}

.zxzx_detail .jztype-wrap {
  position: fixed;
  padding-bottom: 20px;
  bottom: 50px;
  width: 100%;
  align-items: center;
}

.zxzx_detail .jztype-wrap .btn {
  height: 30px;
  margin: 0 5px;
  background: #fff;
  font-size: 14px;
  color: #606266;
  border-radius: 30px;
  align-items: center;
  display: flex;
  text-align: center;
  justify-content: center;
}

.zxzx_detail .mobile-page {
  padding: 15px 15px;
  font-size: 14px;
}

.zxzx_detail .mobile-page .date-group {
  text-align: center;
  font-size: 13px;
  color: #c0c4cc;
  margin-bottom: 8px;
}

.zxzx_detail .reply-box {
  position: relative;
  display: inline-block;
  padding: 10px 13px;
  border-radius: 5px;
  background-color: #fff;
  margin: 3px 15px 12px;
  font-size: 14px;
  line-height: 18px;
  white-space: pre-wrap;
  word-wrap: break-word;
  max-width: 90%;
}

.zxzx_detail .g-h50 {
  background: transparent;
}

.zxzx_detail .reply-box::before {
  content: '';
  position: absolute;
  top: 12px;
  width: 0px;
  height: 0px;
  border-width: 5px; 
}

.zxzx_detail .reply-box::after {
  content: '';
  position: absolute;
  top: 12px;
  border-width: 5px;
  border-style: solid;
}

.zxzx_detail .admin-group .reply-box::before {
  left: -9px;
  border-color: transparent #c7b4b4 transparent transparent;
}

.zxzx_detail .admin-group .reply-box::after {
  left: -9px;
  border-color: transparent white transparent transparent;
}

.zxzx_detail .user-group .reply-box::before {
  right: -9px;
  border-color: transparent transparent transparent #00c792;
}

.zxzx_detail .user-group .reply-box::after {
  right: -9px;
  border-color: transparent transparent transparent #00c792;
}

.zxzx_detail .mobile-page .msg-group {
  padding: 6px 0;
  display: flex;
  display: -webkit-flex;
}

.zxzx_detail .mobile-page .admin-group {
  justify-content: flex-start;
  -webkit-justify-content: flex-start;
}

.zxzx_detail .mobile-page .user-group {
  justify-content: flex-end;
  -webkit-justify-content: flex-end;
  color: #fff;
}

.zxzx_detail .mobile-page .user-group .reply-box {
  text-align: left;
  background-color: #00c792;
}
  
.zxzx_detail .mobile-page .msg-group .msg-cont {
  width: 75%;
  position: relative;
}

.zxzx_detail .mobile-page .user-group .msg-cont {
  text-align: right;
}


/*--- 消息输入框--- */
.zxzx_detail .msg-input-bgk {
  background: #edf1f2;
  font-size: 14px;
  padding: 12px 15px;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
}

.zxzx_detail .msg-input-outer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.zxzx_detail .msg-input-outer .van-cell {
  border-radius: 5px;
  background: #fff;
  border: 0px;
  padding: 5px 10px;
  margin-right: 15px;
}

.zxzx_detail .msg-input-outer .button {
  margin: 0;
  padding: 8px 10px;
  text-align: center;
  color: #fff;
  border-radius: 5px;
  width: 60px;
  background: #00c792;
}

.zxzx_detail .pop-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 14PX;
  color: #606266;
  padding: 15px;
}

.zxzx_detail .pop-cont {
  font-size: 14px;
  padding: 0 15px 0;
}

.zxzx_detail .select-list {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
}

.zxzx_detail .select-list .select-item {
  width: 100px;
  margin: 0 5px 15px;
}

.zxzx_detail .select-list .select-item.on {
  background: #00c792;
  color: #fff !important;
}
</style>