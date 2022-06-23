<template>
  <div class="zxzx_index g-wrap g-hp100 s-bgcfff">
    <van-tabs v-model="activeTabIndex" type="card" color="#00c792">
      <van-tab title="正在沟通">
        <div class="zxzx-list" v-if="recordList1.length">
          <template v-for="(c,i) in recordList1">
            <div class="list-item" :key="i" @click="linkToPage('zxzx_detail',{CONSULTID:c.CONSULTID,isEnd:false,isDoctor:isDoctor,YSID:YSID,CARDNUM:CARD_NUMBER})">
              <div class="item-top f-df">
                <img class="img g-w40 g-h40 f-brp50" :src="c.TX||$store.state.def_headimg" />
                <div class="cont f-pl15">
                  <div class="f-df f-dfj">
                    <span class="name">
                      {{c.NAME||'--'}}
                    </span>
                    <span class="s-c909399">{{$moment(c.INSERTDATE).format('MM-DD HH:mm')}}</span>
                  </div>
                  <div class="f-df f-dfj g-wp100">
                    <div class="txt line1 s-c606266">{{c.CONTENT}}</div>
                    <!-- <span class="icons s-bgcf30b32 f-fs11" v-if="!isDoctor&&c.HFZT=='1'||isDoctor&&c.HFZT=='0'"></span> -->
                    <div v-if="c.HFZT=='1'">
                      <span class="b-tag type1 line1" v-if="isDoctor">已回复</span>
                      <template v-else>
                        <span class="b-tag type4 line1" v-if="c.HZ_NOSEE>0">{{c.HZ_NOSEE}}</span>
                        <span class="b-tag type1 line1" v-else>进行中</span>
                      </template>
                    </div>
                    <div v-else>
                      <template v-if="isDoctor">
                        <span class="b-tag type4 line1" v-if="c.YS_NOREPLY>0">{{c.YS_NOREPLY}}</span>
                        <span class="b-tag type2 line1" v-else>未回复</span>
                      </template>
                      <span class="b-tag type1 line1" v-else>进行中</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
        <van-empty v-else description="暂无记录" />
        <div v-if="!isDoctor">
          <div class="g-h60 s-bgcfff"></div>
          <div class="g-wp100 fixed-btn">
            <van-button class="f-bs3" round color="#00c792" @click="linkToPage('zxzx_detail',{isEnd:false,isDoctor:isDoctor,CARDNUM:CARD_NUMBER})">
              <div class="f-tac">发新咨询</div>
            </van-button>
          </div>
        </div>
      </van-tab>
      <van-tab title="已结束咨询">
        <div class="zxzx-list" v-if="recordList2.length">
          <template v-for="(c,i) in recordList2">
            <div class="list-item" :key="i" @click="linkToPage('zxzx_detail',{CONSULTID:c.CONSULTID,isEnd:true,isDoctor:isDoctor})">
              <div class="item-top f-df">
                <img class="img g-w40 g-h40 f-brp50" :src="c.TX||$store.state.def_headimg" />
                <div class="cont f-pl15">
                  <div class="f-df f-dfj">
                    <span class="name">{{c.NAME||'--'}}</span>
                    <span class="s-c909399">{{$moment(c.INSERTDATE).format('MM-DD HH:mm')}}</span>
                  </div>
                  <div class="f-df f-dfj g-wp100">
                    <div class="txt line1 s-c606266">{{c.CONTENT}}{{c.HFZT}}</div>
                    <span class="b-tag type3">{{isDoctor?'已结束':'已完成'}}</span>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
        <van-empty v-else description="暂无记录" />
      </van-tab>
    </van-tabs>
  </div>
</template>
<script>
  export default {
    name: 'zxzx_index',
    components: {},
    created() {
      var t = this
      // 判断是否注册
      if (!t.queryData) {
        t.isRegister({
          openid: t.wx_userInfo.openid,
          type: 0
        }, function(data) {
          t.CARD_NUMBER = data.CONTENTS[0].CARD_NUMBER
          console.log(data.CONTENTS[0].YSID)
          t.isDoctor = data.CONTENTS[0].YSID ? true : false
          t.YSID = data.CONTENTS[0].YSID || ''
          t.getData()
        })
      } else {
        t.CARD_NUMBER = t.queryData.CARDNUM
        t.isDoctor = t.queryData.YSID ? true : false
        t.YSID = t.queryData.YSID || ''
        t.getData()
      }
    },
    computed: {},
    data() {
      return {
        CARD_NUMBER: '',
        activeTabIndex: 0,
        isDoctor: false,
        YSID: '',
        value: '',
        recordList1: [],
        recordList2: [],
        searchFrom: {}
      }
    },
    methods: {
      getData() {
        var t = this
        if (t.isDoctor) { //医生查询未回复的咨询
          t.searchFrom.FLAG = '1'
          t.getPLList1()
        } else { //用户查询自己的咨询
          t.searchFrom.OPENID = t.wx_userInfo.openid
        }
        var title = t.isDoctor ? '专家咨询-医生' : '专家咨询'
        t.updateTitle(title)
        t.getPLList()
      },
      getPLList() {
        var t = this
        t.ajax({
          serverId: 'WXPL00001',
          url: t.serverPath2,
          params: t.searchFrom,
          success: function(data) {
            var msgList = data
            console.log(666, data)
            for (let c of msgList) {
              if (c.CLOSEDIALOG == 0) {
                t.recordList1.push(c)
              } else {
                t.recordList2.push(c)
              }
            }
            console.log(t.recordList1)
          }
        })
      },
      // 医生获取已结束的咨询列表
      getPLList1() {
        var t = this
        t.ajax({
          serverId: 'WXPL00001',
          url: t.serverPath2,
          success: function(data) {
            var msgList = data
            console.log(data)
            for (let c of msgList) {
              if (c.CLOSEDIALOG == 1) {
                t.recordList2.push(c)
              }
            }
          }
        })
      },
    },
    mounted() {},
  }
</script>
<style scoped>
  .zxzx_index>>>.van-tabs__wrap {
    margin: 20px;
    height: 40px;
    border-radius: 5px;
  }

  .zxzx_index>>>.van-tabs__nav--card {
    height: 40px;
    border-radius: 8px !important;
    overflow: hidden !important;
  }

  .zxzx_index>>>.van-tab {
    height: 40px;
  }

  .zxzx_index .zxzx-list {
    background: #fff;
  }

  .zxzx_index .zxzx-list .list-item {
    padding: 20px;
    border-top: 1px solid #ebeef5;
    font-size: 14px;
  }

  .zxzx_index .zxzx-list .list-item .item-top {
    height: 40px;
    align-items: center;
  }

  .zxzx_index .zxzx-list .list-item .img {
    width: 45px;
    height: 45px;
  }

  .zxzx_index .zxzx-list .list-item .b-tag {
    width: 55px;
    text-align: center;
    border-radius: 15px;
  }

  .zxzx_index .zxzx-list .list-item .type1 {
    background: #e5f9f4;
    color: #00c792;
    border-color: transparent;
  }

  .zxzx_index .zxzx-list .list-item .type2 {
    background: #fae0e0;
    color: #f56c6c;
    border-color: transparent;
  }

  .zxzx_index .zxzx-list .list-item .type3 {
    background: #f9f9fa;
    color: #909399;
    border-color: transparent;
  }

  .zxzx_index .zxzx-list .list-item .type4 {
    background: #ff9c32;
    color: #fff;
    border-color: transparent;
    border-radius: 50%;
    width: 20px;
    height: 20px;
  }

  .zxzx_index .zxzx-list .list-item .cont {
    width: 86%;
    align-items: center;
  }

  .zxzx_index .zxzx-list .list-item .name {
    font-size: 15px;
    font-weight: bold;
    margin: 2px 0 6px;
  }

  .zxzx_index .zxzx-list .list-item .txt {
    word-wrap: break-word;
    width: 78%;
    margin-top: 4px;
  }

  .zxzx_index .fixed-btn {
    position: fixed;
    bottom: 0;
    width: 100%;
  }

  .zxzx_index .fixed-btn>>>.van-button {
    width: 90%;
    margin: 20px 5%;
  }

  .zxzx_index .zxzx-list .list-item .icons {
    right: 0;
    top: 0px;
    display: inline-block;
    width: 5px;
    height: 5px;
    border-radius: 5px;
  }
</style>