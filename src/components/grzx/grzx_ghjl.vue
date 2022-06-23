<template>
  <div class="grzx_ghjl g-wrap">
    <van-cell v-show="patientList.length" class="f-bb10" title="请选择就诊人" is-link @click="showPopup">
      <span v-if="!searchForm.HZXM">请选择</span>
      <span v-else>{{searchForm.HZXM}}</span>
    </van-cell>
    <van-notice-bar v-if="serverType=='cz'&&activeIndex===0" class="f-fs13" wrapable :scrollable="false">
      请您在预约当天前往医院,凭身份证现场取号付款！
    </van-notice-bar>
    <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
      <van-tab title="未完成">
        <div class="record-list">
          <div class="list-item" v-for="(c,i) in recordList1" :key="i">
            <div class="item-head f-pt15 f-pb15 f-pr15 f-pl15">
              <div class="flex-item">
                <div class="name s-c303133 f-fs15 f-mb8">{{c.ORG_NAME}}</div>
                <div class="state s-cffa54d" v-if="serverType!='cz'"><i class="f-mr8 dott-icon s-bgcffa54d"></i>待面诊<span v-if="serverType=='gd'&&c.ZFFLAG==0">(未付款)</span></div>
              </div>
              <p>
                <span class="f-mr10">{{c.GHYS}} <span v-show="c.YSJB">({{c.YSJB=='0'?'普通医生':'专家医生'}})</span></span>
                <span>{{c.KSMC}}</span>
              </p>
            </div>
            <div class="item-cont">
              <div class="line-item f-lh24"><span class="tit">就诊人：</span>{{c.XM}}</div>
              <div class="line-item f-lh24"><span class="tit">就诊时间：</span>{{c.YYSJ}} <!--  {{c.TIMERANGE }} --> {{c.TIMEZONE}}</div>
              <div class="line-item f-lh24">
                <div class="flex-item">
                  <div>
                    <span class="tit">挂号费：</span>
                    <i class="s-cf30b32">￥{{c.ORDERMON }}</i>
                  </div>
                </div>
              </div>
              <div class="btn-item">
                <van-button class="item-btn f-mr10" round color="#ffa54d" plain v-if="serverType=='gd'&&c.ZFFLAG==0&&c.YYSJ==$moment().format('YYYY-MM-DD')&&c.ORDERMON!=0" @click="linkToPage('yygh_qryy',{ORG_ID:c.ORG_ID,ORG_NAME:c.ORG_NAME,KSID:c.KSDM,GHKS:c.KSMC,YSID:c.YSID,GHYS:c.GHYS,YYRQ:c.YYSJ,GHF:c.ORDERMON,TIMERANGE:c.TIMERANGE,YYSD:c.TIMEZONE,GHTYPE:c.GHTYPE,CARDNUM:c.CARDNUM,HZXM:c.XM,SJHM:c.SJHM,isGHPAY:1})">去付款</van-button>
                <van-button v-if="c.ORG_NAME!='广德市中医院'&&c.ZFFLAG==0" disabled="true" class="item-btn f-mr8" round color="#00c792">
                  {{'未付款'}}
                </van-button>
                <van-button v-if="c.ORG_NAME=='广德市人民医院'&&c.ZFFLAG==1" class="item-btn f-mr8" round color="#00c792" @click="ts(c,0)">
                  {{'未取号'}}
                </van-button>
                <van-button v-if="c.ORG_NAME!='广德市中医院'&&c.ZFFLAG==1&&c.ORG_NAME!='广德市人民医院'" class="item-btn f-mr8" round color="#00c792" @click="ts(c,1)">
                  {{'未取号'}}
                </van-button>
                <van-button v-if="c.ORG_NAME!='广德市中医院'&&c.ZFFLAG==3" disabled class="item-btn f-mr8" round color="#909399">
                  {{'已取号'}}
                </van-button>
                <van-button class="item-btn " round :color="c.ZFFLAG==3?'#909399':'#00c792'" :disabled="c.ZFFLAG==3?true:false" plain @click="doCancel(c)">取消</van-button>
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
                <span class="f-mr10">{{c.GHYS}} <span v-show="c.YSJB">({{c.YSJB=='0'?'普通医生':'专家医生'}})</span></span>
                <span>{{c.KSMC}}</span>
              </p>
            </div>
            <div class="item-cont">
              <div class="line-item f-lh24"><span class="tit">就诊人：</span>{{c.XM}}</div>
              <div class="line-item f-lh24"><span class="tit">就诊时间：</span>{{c.YYSJ}} <!-- {{c.TIMERANGE}} --> {{c.TIMEZONE}}</div>
              <div class="line-item f-lh24">
                <div>
                  <span class="tit">挂号费：</span>
                  <i class="s-cf30b32">￥{{c.ORDERMON }}</i>
                </div>
              </div>
              <div class="btn-item">
                <van-button v-if="c.DELFLAG==1" class="item-btn f-ml10" round color="#909399" disabled plain>已取消</van-button>
                <van-button v-else class="item-btn f-ml10" round color="#909399" disabled plain>已完成</van-button>
                <template v-if="c.DELFLAG!=1">
                  <van-button class="item-btn f-ml10" round color="#00c792" plain v-if="c.PJBS==0" @click="linkToPage('grzx_fwpj',{YSID:c.YSID,YYID:c.YYID})">去评价</van-button>
                  <van-button class="item-btn f-ml10" round color="#909399" disabled plain v-else>已评价</van-button>
                </template>
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
    components: {},
    created() {
      var t = this
      t.updateTitle('挂号记录')
      t.searchForm1.OPENID = t.wx_userInfo.openid
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(sucFun) {
        var peopleInfo = sucFun.CONTENTS[0] || []
        t.CARDNUM = peopleInfo.CARD_NUMBER || ''
        t.getRecordList()
      })
    },
    computed: {},
    data() {
      return {
        CARDNUM: '',
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
      ts(val, index) {
        var t = this
        console.log(val)
        var rq = val.YYSJ.split('-')
        var time = val.TIMERANGE.split('-')[0].split(':')[0]
        console.log(time - 1)
        var month = new Date().getMonth() + 1
        var day = new Date().getDate()
        var hours = new Date().getHours()
        var min = new Date().getMinutes()
        if (index == 0) {
          var serverId = 'WXGDRMYYQH001'
          var params = {
            LSH: val.YYLSH,
            DJH: val.DJH,
            ORG_ID: val.ORG_ID
          }
        } else {
          serverId = 'WXBM00009'
          params = {
            YYID: val.YYID,
            ORG_ID: val.ORG_ID
          }
        }
        if ((rq[1] == month && rq[2] == day) || index == 1) {
          console.log(time)
          console.log(hours)
          if ((((time - 1) == hours && min >= 30) || time == hours) || index == 1) {
            t.ajax({
              serverId: serverId,
              url: t.serverPath4 || t.serverPath1,
              params: params,
              success: function(data) {
                console.log(11111, data)
                console.log(11111, params)
                if (data[0].CODE == -1) {
                  if (data[0].MSG == '') {
                    t.$toast('取号失败')
                  } else {
                    t.$toast(data[0].MSG)
                  }
                } else {
                  if (data[0].MSG == '') {
                    t.$toast('取号成功')
                  } else {
                    t.$toast(data[0].MSG)
                  }
                  t.getRecordList()
                }
              }
            })
          } else {
            t.$toast('请于就诊时间前半小时内取号')
          }
        } else {
          t.$toast('请于当天取号')
        }
      },
      onClickTabs(index) {
        this.activeIndex = index
      },
      getRecordList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00007',
          url: t.serverPath4 || t.serverPath1,
          params: {
            OPENID: t.wx_userInfo.openid,
            CARDNUM: t.CARDNUM
          },
          success: function(data) {
            console.log(data)
            var recordData = data[0].CONTENTS
            console.log(recordData)
            t.recordList1 = recordData
            t.recordList1 = []
            t.recordList2 = []
            for (let c of recordData) {
              if ((c.BUTTONFLAG === 1 && c.DELFLAG === '0')) {
                if (t.serverType == 'cz') { //池州的挂号过期标志
                  if (c.GQFLAG == '1') {
                    t.recordList1.push(c)
                  } else {
                    t.recordList2.push(c)
                  }
                } else {
                  t.recordList1.push(c)
                }
              } else {
                t.recordList2.push(c)
              }
            }
            console.log(66666666, t.recordList1)
            console.log(66666666, t.recordList2)
          }
        })
      },
      doCancel(data) {
        console.log(data)
        var t = this
        var msg = t.serverType == 'cz' ? '取消预约的号源？' : '确认取消预约挂号?取消成功后，预约挂号费用将在三个工作日退回到账户!'
        if (data.QHFLAG == '0' || data.JZDZ != "广德市中医院") {
          t.$dialog.confirm({
              message: msg,
            }).then(() => {
              console.log(data)
              var params = {
                TRADE: data.TRADE || '',
                YYID: data.YYID,
                ORG_ID: data.ORG_ID,
                OPENID: t.wx_userInfo.openid,
                LSH: data.YYLSH
              }
              if (data.ORG_ID == 'guangde001') {
                params = {
                  LSH: data.YYLSH,
                  DJH: data.DJH,
                  TRADE: data.TRADE,
                  ORG_ID: data.ORG_ID,
                }
              }
              console.log(params)
              t.ajax({
                serverId: 'WXBM00008',
                url: t.serverPath4 || t.serverPath1,
                params: params,
                success: function(data) {
                  console.log(6666, data)
                  if (data[0].CODE == 0) {
                    t.$dialog.alert({
                      confirmButtonText: '好的',
                      message: '已取消预约号源'
                    }).then(function() {
                      t.getRecordList()
                    })
                  } else {
                    // data[0].MSG
                    t.$dialog.alert({
                      confirmButtonText: '好的',
                      message: '取消失败'
                    }).then(function() {
                      // t.getRecordList()
                    })
                  }
                }
              })
            })
            .catch(() => {
              // on cancel
            });

        } else {
          t.$dialog.alert({
            title: '',
            message: '预约的当天号，请到医院窗口取消',
          })
        }
      },
      getPatientDef() {
        var t = this
        t.ajax({
          serverId: 'WXBM00015',
          url: t.serverPath4 || t.serverPath1,
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
          url: t.serverPath4 || t.serverPath1,
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
    height: 25px;
    line-height: 25px;
    width: 60px;
    padding: 0;
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
    padding: 5px 0;
  }
</style>