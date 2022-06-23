<template>
  <div :class="[serverType,'index', 'g-wrap', 'g-hp100', 's-bgcfff']">
    <div class="f-pl15 f-pr15 f-pt15 f-pb15 f-pb10 f-bb10">
      <div class="top-wrap" @click="toHealthCard">
        <img class="index-bg" :src="__static+indexBg">
        <div class="search-wrap f-df f-dfc" @click="linkToPage('index_search')" v-if="serverType!='hz'">
          <van-icon name="search" class="f-mr5" />
          <div class="f-fs11">搜索医生、科室</div>
        </div>
      </div>
      <div class="menu-list f-df f-dfj">
        <div class="list-item f-df" v-for="(c,i) in menuList" :key="i" @click="handlerMenu(c)">
          <img class="g-w40 g-h40 f-mr10" :src="__static+c.icons">
          <div class="f-dfv g-hp100">
            <p class="tit">{{c.tit}}</p>
            <p class="f-fs13 txt line1">{{c.txt}}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="menu-wrap f-bb10">
      <div class="w-item" v-for="(c,i) in menuWrap" :key="i" @click="handlerMenu(c)">
        <img class="g-w45 f-mb10" :src="__static+c.icons">
        <p>{{c.tit}}</p>
      </div>
    </div>
    <div class="consult-wrap f-df f-dfc f-bb10" v-if="serverType=='cz'" @click="toZXZX">
      <img class="g-w50 g-h50 f-ml5" src="../../assets/img/index/consult-headimg.png">
      <div class="cont g-wp70 f-ml15">
        <div class="tit f-mb10 f-mt3">免费咨询</div>
        <div>全天候快速响应，实时沟通</div>
      </div>
      <div class="btn-wrap">
        <van-button color="#00c792" round size="small">立即咨询</van-button>
        <span class="new-msg" v-if="newMsg"><img src="../../assets/img/index/new-icon.png"></span>
      </div>
    </div>
    <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
      <van-tab title="医院列表">
        <div class="f-pt30 f-pb30 f-tac s-bgcfff" v-if="isHospitalData">
          <van-loading size="24px" color="#00c792">加载中...</van-loading>
        </div>
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
      </van-tab>
      <van-tab title="健康资讯">
        <div class="infor-list s-bgcfff">
          <div class="list-item" v-for="(c,i) in jkzxList" :key="i">
            <a :href="c.link">
              <img class="item-img" :src="__static+c.img">
              <div class="item-cont">
                <div class="tit line1">{{c.tit1}}</div>
                <div class="txt line2">{{c.tit2}}</div>
              </div>
            </a>
          </div>
        </div>
      </van-tab>
    </van-tabs>
    <div class="g-h50"></div>
  </div>
</template>
<script>
  export default {
    name: 'index',
    components: {},
    created() {
      var t = this
      // 首页菜单显示配置
      t.getMenus();
      t.getJkzxList();

      t.getHospitalList();
      // 判断在线咨询是否有新消息 
      t.isDoctors(t.wx_userInfo.openid, 0);
    },
    computed: {},
    data() {
      return {
        newMsg: false,
        YSID: '',
        isDoctor: '',
        activeIndex: '',
        hospitalList: '',
        pathParams: {},
        menuWrap: [],
        menuList: [],
        indexBg: '/imgs/index/index-bg.png',
        hospitalimg: require('@/assets/img/index/hospital-img.png'),
        isHospitalData: false,
        jkzxList: [],
      }
    },
    methods: {
      getJkzxList() {
        var t = this;
        if (!localStorage.jkzxList) {
          t.ajax({
            method: 'get',
            url: t.__static + '/data/index-jkzx.json',
            success(data) {
              t.jkzxList = data
              localStorage.jkzxList = JSON.stringify(t.jkzxList)
            },
          });
        } else {
          t.jkzxList = JSON.parse(localStorage.jkzxList);
        }
      },
      getMenus() {
        var t = this;
        if (!localStorage.indexConfig1) {
          t.ajax({
            method: 'get',
            url: t.__static + '/data/' + t.serverType + '/index-config.json',
            success(data) {
              // 动态创建菜单 
              console.log(data)
              localStorage.indexConfig1 = JSON.stringify(data[0])
              t.setIndexConfig(data[0])
            },
          });
        } else {
          t.setIndexConfig(JSON.parse(localStorage.indexConfig1))
        }
      },
      setIndexConfig(data) {
        var t = this;
        t.updateTitle(data.title || '便民服务')
        t.menuWrap = data.menuWrap
        t.menuList = data.menuList
        t.indexBg = data.indexBg || t.indexBg
      },
      // 惠州点击进入 电子健康卡
      toHealthCard() {
        var t = this;
        // 判断是否注册
        if (t.serverType == 'hz') {
          t.isRegister({
            openid: t.wx_userInfo.openid,
            type: 0
          }, function() {
            t.linkToPage('people_lists')
          })
        }
      },
      isDoctors(openid, type) {
        var t = this
        t.ajax({
          method: 'post',
          serverId: 'WXBM00013',
          loading: false,
          params: {
            openid: openid,
            type: type
          },
          url: t.serverPath1,
          success(data) {
            console.log(data)
            if (data[0].CODE != -1) {
              if (data[0].CONTENTS) {
                t.isDoctor = data[0].CONTENTS[0].YSID ? true : false
                t.YSID = data[0].CONTENTS[0].YSID || ''
                t.isNewMsg()
              }
            }
          }
        })
      },
      handlerMenu(data) {
        var t = this
        if (data.islink == '1') {
          window.location.href = data.url
        } else {
          if (data.url) {
            if (data.url == 'xgymyy_index') { //新冠疫苗的跳转问题 
              t.ajax({
                loading: false,
                serverId: 'WXGDYYGHTZH09',
                url: t.serverPath1,
                params: {
                  OPENID: t.wx_userInfo.openid
                },
                success: function(data) {
                  if (data[0].CONTENTS.length) {
                    t.linkToPage('xgymyy_qryy')
                  } else {
                    t.linkToPage('xgymyy_index')
                  }
                }
              })
            } else if (data.url != 'people_lists') {
              t.linkToPage(data.url)
            } else {
              t.isRegister({
                openid: t.wx_userInfo.openid,
                type: 0
              }, function() {
                t.linkToPage(data.url)
              })
            }
          } else {
            if (data.tit) {
              t.$toast('正在建设中')
            }
          }
        }
      },
      isNewMsg() {
        var t = this
        var params
        if (t.isDoctor) {
          params = {
            YSID: t.YSID
          }
        } else {
          params = {
            OPENID: t.wx_userInfo.openid
          }
        }
        console.log(params)
        t.ajax({
          loading: false,
          serverId: 'WXPL00003',
          url: t.serverPath2,
          params: params,
          success: function(data) {
            if (data[0].CODE == 0) {
              console.log(data)
              t.newMsg = data[0].MSG > 0 ? true : false
              console.log(t.newMsg)
            }
          }
        })
      },
      toZXZX() {
        this.linkToPage('zxzx_index')
      },
      onClickTabs(index) {
        var t = this
        t.activeIndex = index
      },
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
  .index .top-wrap {
    background-size: 100%;
    width: 100%;
    position: relative;
    text-align: center;
    overflow: hidden;
  }

  .index .top-wrap .index-bg {
    width: 100%;
    vertical-align: middle;
  }

  .index .search-wrap {
    background: rgba(256, 256, 256, .2);
    width: 60%;
    left: 20%;
    top: 10px;
    border-radius: 20px;
    height: 26px;
    color: #fff;
    align-items: center;
    position: absolute;
    z-index: 10;
  }

  .index .search-wrap>>>.van-icon {
    font-size: 14px;
    font-weight: bold;
  }

  .index .menu-list {
    flex-wrap: wrap;
  }

  .index .menu-list .list-item {
    color: #fff;
    width: 48%;
    justify-content: center;
    padding: 15px 0;
    border-radius: 5px;
    margin-top: 15px;
  }

  .index .menu-list .list-item .tit {
    font-size: 15px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .index .menu-list .list-item .txt {
    width: 90px;
  }

  .index .menu-list .list-item:nth-child(1) {
    background: linear-gradient(to right, #16d888, #36deb1);
  }

  .index .menu-list .list-item:nth-child(2) {
    background: linear-gradient(to right, #6d93ff, #6eb5fe);
  }

  .index.gd .menu-list .list-item:nth-child(2),
  .index.cz .menu-list .list-item:nth-child(2) {
    background: linear-gradient(to right, #fd896e, #ffaf47);
  }

  .index .menu-wrap {
    background: #fff;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    font-size: 15px;
    text-align: center;
    padding: 10px 0;
  }

  .index .menu-wrap .w-item {
    width: 25%;
    padding: 10px 0;
  }

  .index.cz .menu-wrap .w-item img {
    width: 36px;
    margin-bottom: 0;
  }

  .index .menu-wrap .w-item p {
    color: #606266;
  }

  .index .consult-wrap {
    padding: 20px 15px;
    background: #fff;
    font-size: 13px;
    color: #606266;
    align-items: center;
  }

  .index .consult-wrap .btn-wrap {
    position: relative;
  }

  .index .consult-wrap .new-msg {
    position: absolute;
    right: 0;
    top: -12px;
  }

  .index .consult-wrap .new-msg img {
    width: 28px;
  }

  .index .consult-wrap .tit {
    font-size: 15px;
    font-weight: bold;
    color: #303133;
  }

  .index .consult-wrap>>>.van-button--small {
    width: 85px;
    height: 32px;
    font-size: 14px;
  }

  .index>>>.van-cell {
    padding: 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
  }

  .index>>>.van-cell__title {
    color: #606266;
    flex: 8;
  }

  .index>>>.van-cell__value {
    flex: 16;
  }

  .index>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .index .f-bb10 {
    border-bottom: 10px solid #f4f9f9;
  }

  .index>>>.van-tabs--line .van-tabs__wrap {
    height: 60px;
    padding-bottom: 8px;
    border-bottom: 1px solid #ebeef5;
  }

  .index>>>.van-tab__text {
    font-size: 15px;
  }

  .index>>>[class*=van-hairline]::after {
    border: 0;
  }

  .index>>>.van-tabs__line {
    background: #00c792;
  }

  .index>>>.van-tabbar {
    height: 50px;
  }

  .index .hospital-list,
  .index .infor-list {
    padding: 0 20px;
  }

  .index .hospital-list .list-item,
  .index .infor-list .list-item {
    padding: 15px 0;
    font-size: 14px;
    overflow: hidden;
    border-bottom: 1px solid #ebeef5;
  }

  .index .list-item:nth-last-child(1) {
    border: 0;
  }

  .index .hospital-list .list-item .item-img,
  .index .infor-list .list-item .item-img {
    float: left;
    width: 88px;
    height: 70px;
    margin-right: 15px;
    border-radius: 3px;
  }

  .index .hospital-list .list-item .item-cont,
  .index .infor-list .list-item .item-cont {
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

  .index .hospital-list .list-item .tips {
    font-size: 13px;
    padding: 0 5px;
    border-radius: 3px;
    line-height: 26px;
    border: 1px solid #ebeef5;
    color: #909399;
  }

  .index .hospital-list .list-item .addr {
    color: #909399;
  }

  .index .infor-list .list-item .tit {
    font-weight: bold;
    font-size: 15px;
  }

  .index .infor-list .list-item .txt {
    margin-top: 8px;
    color: #909399;
    line-height: 24px;
  }
</style>