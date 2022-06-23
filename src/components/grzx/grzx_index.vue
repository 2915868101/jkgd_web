<template>
  <div class="grzx_index g-wrap g-hp100 s-bgcfff">
    <div v-if="userInfo">
      <div class="user-top s-bgcfff">
        <img class="img-item f-fl" :src="userInfo.TX||wx_userInfo.headimgurl||$store.state.def_headimg">
        <div class="cont-item">
          <div class="f-pt4">
            <span class="name f-fs16">{{userInfo.NAME}}</span>
            <span class="f-fr s-c909399" @click="linkToPage('grzx_grzl',userInfo)">更多 <i class="iconfont icon-arrow-right f-fs11"></i></span>
          </div>
          <div class="f-pt8 s-c606266">
            {{regNumber(userInfo.SJHM)}}
          </div>
        </div>
      </div>
      <div class="box-wrap s-bgcfff">
        <div class="b-head">就诊记录</div>
        <div class="b-cont">
          <div class="menu-list">
            <div class="list-item f-df" v-for="(c,i) in jzjlList" :key="i" @click="handlerMenu(c)">
              <img class="item-l" :src="__static+c.icons">
              <span class="item-r">{{c.tit}}</span>
            </div>
          </div>
          <div class="list-item f-df " @click="linkToPage('fwpj_list')">
            <img class="item-l" :src="require('../../assets/img/gd3/Ellipse 20.png')">
            <span class="item-r">满意度评价</span>
          </div>
        </div>
      </div>
      <div class="box-wrap s-bgcfff">
        <div class="b-head">更多服务</div>
        <div class="b-cont">
          <div class="menu-list">
            <div class="list-item f-df" v-if="(serverType=='gd'||serverType=='cz')&&userInfo.LDJOB" @click="towebBI">
              <img class="item-l" src="../../assets/img/grzx/ldcx_icon.png">
              <span class="item-r">运营监管</span>
            </div>
            <div class="list-item " v-for="(c,i) in gdfwList" :key="i">
              <div class="f-df " v-if="c.tit=='健康自测'" @click="linkToPage('jkbk_index')">
                <img class="item-l" :src="require('../../assets/img/guidance/jkzsk_icon.png')">
                <span class="item-r">健康知识库</span>
              </div>
              <div class="f-df " v-else @click="handlerMenu(c)">
                <img class="item-l" :src="__static+c.icons">
                <span class="item-r">{{c.tit}}</span></div>
            </div>
            <div class="list-item f-df " v-if="show==true" @click="linkToPage('jtys_jygzt', userInfo)">
              <img class="item-l" :src="require('../../assets/img/gd3/Ellipse 19.png')">
              <span class="item-r">家医工作台</span>
            </div>
            <!-- <div class="list-item f-df " v-if="show[index].code==0" @click="linkToPage('jtysfwb_qy_team')">
              <img class="item-l" :src="require('../../assets/img/gd3/Ellipse 19.png')">
              <span class="item-r">签约我的服务包</span>
            </div> -->
            <!-- 池州、广德移动BI -->
            <!-- <div class="list-item f-df" v-if="serverType=='gd'&&userInfo.LDJOB==1" @click="linkToPage('xgymyy_tjcx')">
              <img class="item-l" src="../../assets/img/grzx/ldcx_icon.png">
              <span class="item-r">新冠疫苗统计</span>
            </div> -->
            <!-- 池州小程序跳转 -->
            <!-- <div class="list-item f-dfc g-hp100" v-if="serverType=='cz'&&(userInfo.SJHM=='18774855385'||userInfo.SJHM=='15111463783'||userInfo.SJHM=='18974838847'||userInfo.SJHM=='18684685836'||userInfo.SJHM=='13828794030'||userInfo.SJHM=='18627607101'||userInfo.SJHM=='17620739366')">
              <img class="item-l" src="../../assets/img/guidance/jkzsk_icon.png">
              <wx-open-launch-weapp class="g-wp60 item-r" id="launch-btn" username="gh_780a88953dc6" path="pages/index/index?username=1111">
                <script type="text/wxtag-template">
                  <style> 
                    .btn {  
                      width:100px;
                      font-size: 14px;
                      color: #303133;
                    }
                  </style> 
                  <div class="btn">视频会诊</div> 
                </script>
              </wx-open-launch-weapp>
            </div> -->
          </div>
        </div>
      </div>
    </div>
    <div class="g-h50"></div>
  </div>
</template>
<script>
  export default {
    name: 'grzx_index',
    components: {},
    created() {
      var t = this
      t.updateTitle('个人中心')
      // 个人中心菜单显示配置
      t.getMenus()
      // 判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(sucFun) {
        t.userInfo = sucFun.CONTENTS[0]
        t.getpd(t.userInfo)
        console.log(t.show)
        if (t.serverType == 'cz') {
          t.getwxapp(t.wx_configParams)
        }
      })
    },
    computed: {},
    data() {
      return {
        userInfo: '',
        gdfwList: '',
        jzjlList: '',
        show: 'false',
        index: '',
      }
    },
    methods: {
      //判断是否为医生
      getpd(val) {
        var t = this
        var params = {
          OPENID: t.wx_userInfo.openid,
          NAME: val.NAME,
          SJHM: val.SJHM,
          CARDNUM: val.CARD_NUMBER
        }
        t.ajax({
          loading: false,
          serverId: 'DYWJTYSTZH01',
          url: t.serverPath1,
          params: params,
          success: function(res) {
            if (res[0].code == 200) {
              t.show = true
            }
          }
        })
      },
      getMenus() {
        var t = this;
        // if (!localStorage.grzxConfig1) {
        t.ajax({
          method: 'get',
          url: t.__static + '/data/' + t.serverType + '/grzx-config.json',
          success(data) {
            // 动态创建菜单 
            console.log(data)
            localStorage.grzxConfig1 = JSON.stringify(data[0])
            t.setGrzxConfig(data[0])
          },
        });
        // } else {
        //   t.setGrzxConfig(JSON.parse(localStorage.grzxConfig1))
        // }
      },
      setGrzxConfig(data) {
        var t = this;
        t.jzjlList = data.jzjlList
        t.gdfwList = data.gdfwList
      },
      towebBI() {
        var t = this
        console.log(789798, t.userInfo)
        if (t.serverType == 'gd') {
          if (t.userInfo.LDJOB == 3) {
            window.location.href = 'http://www.jkgd12320.com:8086/yzk/dist/#/?token=' + t.wx_userInfo.openid
          } else if (t.userInfo.LDJOB == 2) {
            window.location.href = 'http://www.jkgd12320.com:8086/ggws/dist/#/?token=' + t.wx_userInfo.openid
          } else if (t.userInfo.LDJOB == 1) {
            t.linkToPage('/grzx_yycx')
          }
        }
      },
      toXGYMYY() {
        let t = this
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
      },
      getwxapp(config) {
        var t = this
        var wx_config = JSON.parse(JSON.stringify(config))
        wx_config.jsApiList = ['getLocation']
        wx_config.openTagList = ['wx-open-launch-weapp']
        t.$wechat.config(wx_config)
        t.$wechat.ready(function() {

        })
        t.$wechat.error(function(res) {
          console.log(res)
          // t.$dialog.alert({
          //   message: `<div class="f-tal"> 
          //     参数：${JSON.stringify(res)} 
          //   </div>`
          // })
        })
      },
      handlerMenu(data) {
        var t = this
        if (data.islink == '1') {
          window.location.href = data.url
        } else {
          if (data.url) {
            t.linkToPage(data.url)
          } else {
            if (data.tit) {
              t.$toast('正在建设中')
            }
          }
        }
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  .grzx_index .user-top {
    padding: 20px;
    font-size: 14px;
  }

  .grzx_index .user-top .img-item {
    width: 45px;
    height: 45px;
    border-radius: 50%;
  }

  .grzx_index .user-top .cont-item {
    margin-left: 65px;
    height: 45px;
  }

  .grzx_index .user-top .name {
    font-weight: bold;
  }

  .grzx_index .box-wrap {
    border-top: 10px solid #f4f9f9;
    padding: 20px;
    font-size: 14px;
    color: #606266;
  }

  .grzx_index .box-wrap .b-head {
    font-size: 15px;
    padding: 0;
    color: #303133;
  }

  .grzx_index .box-wrap .menu-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .grzx_index .box-wrap .list-item {
    flex: 50%;
    padding-top: 20px;
    align-items: center;
  }

  .grzx_index .box-wrap .list-item:nth-child(even) {
    flex: 35%;
  }

  .grzx_index .box-wrap .item-l {
    width: 45px;
    height: 45px;
  }

  .grzx_index .box-wrap .item-r {
    display: inline-block;
    height: 45px;
    line-height: 45px;
    text-align: center;
    margin-left: 10px;
  }
</style>