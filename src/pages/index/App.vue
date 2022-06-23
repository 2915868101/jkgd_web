<template>
  <div id="app" class="g-wrap f-pr">
    <div class="f-fdc g-hp100">
      <div class="f-df1 f-pr">
        <transition :name="transitionName" v-if="$store.state.wx_userInfo">
          <router-view class="child-view animated"></router-view>
        </transition>
      </div>
      <template v-if="serverType != 'gd'">
        <div class="footer g-h50" v-if="$route.fullPath=='/index'||$route.fullPath=='/grzx_index'">
          <van-tabbar class="f-bs3" active-color="#00c792">
            <van-tabbar-item @click.native.stop="linkToPage('index','fade')">
              <span :class="[$route.fullPath=='/index'?'s-c00c792':'s-c6c6c6c']">首页</span>
              <template #icon>
                <i :class="[$route.fullPath=='/index'?'s-c00c792':'s-c6c6c6c','iconfont' ,'icon-index']"></i>
              </template>
            </van-tabbar-item>
            <van-tabbar-item @click.native.stop="linkToPage('grzx_index','fade')">
              <span :class="[$route.fullPath=='/grzx_index'?'s-c00c792':'s-c6c6c6c']">我的</span>
              <template #icon>
                <i :class="[$route.fullPath=='/grzx_index'?'s-c00c792':'s-c6c6c6c','iconfont' ,'icon-user']"></i>
              </template>
            </van-tabbar-item>
          </van-tabbar>
        </div>
      </template>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'App',
    components: {},
    computed: {},
    created() {
      var t = this
      //获取微信用户信息及 JS-SDK 配置信息
      // t.initWxDataInfo()   

      // 皖事通app扫码查看
      // 获取token 皖事通
      t.$nextTick(function() {
        if ((t.serverType == 'ng' || t.serverType == 'gd') && !t.wx_userInfo.openid) {
          global.croods.customPlugin({
            action: 'UserPlugin.login',
            params: {},
            success: function(res) {
              t.getWstInfo(res.token)
              // t.$dialog.alert({
              //   message: `<div class="f-tal"> 
              //   token：${JSON.stringify(res.token)} 
              // </div>`
              // })
            },
            fail: function(msg) {
              console.log(msg)
              //     t.$dialog.alert({
              //       message: `<div class="f-tal"> 
              //   错误信息：${JSON.stringify(msg)} 
              // </div>`
              //     })
            }
          })
        }
      })
      // t.getWstInfo('c970424fdd9c4d0fa12ccbccade23bad')
      // t.getWstInfo('f3e135d1fa414e9dbccbb7b47d0b2424')
      // t.getWstInfo('bfcd4059c62e4489a6c5504d2d6f7667')

    },
    data() {
      return {
        transitionName: 'slide-left'
      }
    },
    watch: {
      '$route'(to, from) {
        var t = this
        t.skipPage(to, from)
      },
    },
    methods: {
      getWstInfo(token) {
        var t = this;
        console.log(token)
        if (!t.wx_userInfo.openid) {
          t.ajax({
            method: 'post',
            serverId: 'WXBMNGWST07',
            params: {
              TOKEN: token,
              SERVICEID: '13f13e5596b346f1bd0e529e89092b2c',
              ROLECODE: ''
            },
            url: t.serverPath1,
            success(data) {
              console.log(132, data)
              let CARDNUM = data[0].data['perUserVo.credentNo']
              let SJHM = data[0].data['perUserVo.bindPhone']
              let NAME = data[0].data['perUserVo.name']
              let XB = t.IdCard(CARDNUM, 2) == '男' ? '1' : '2'
              let NL = t.IdCard(CARDNUM, 3)
              let CSRQ = t.IdCard(CARDNUM, 1)
              // 注册信息
              t.registerForm = {
                CARDNUM: CARDNUM,
                SJHM: SJHM,
                NAME: NAME,
                XB: XB,
                CSRQ: CSRQ,
                NL: NL,
                HKDZ: '',
                TX: '',
              }

              if (SJHM && CARDNUM) {
                t.getOPENID(t.registerForm)
              }
            }
          })
        }
      },
      getOPENID(params) {
        var t = this
        t.ajax({
          method: 'post',
          serverId: 'WXBMTZH06',
          loading: false,
          params: params,
          url: t.serverPath1,
          success(data) {
            console.log(data)
            if (data[0].CODE == '0') {
              sessionStorage.wx_userInfo = JSON.stringify({
                openid: data[0].CONTENTS[0].OPENID,
                nickname: data[0].CONTENTS[0].NAME,
                headimgurl: data[0].CONTENTS[0].TX
              })
              console.log(sessionStorage.wx_userInfo)
              t.$store.state.wx_userInfo = JSON.parse(sessionStorage.wx_userInfo)
              console.log(t.wx_userInfo)
            }
          }
        })
      },
    },
    mounted() {
      var t = this
      t.updateNowDate()
    }
  }
</script>
<style>
  body {
    font-size: 15px;
    background-color: #f4f9f9;
    -webkit-font-smoothing: antialiased;
    background-size: auto;
  }

  #app .footer>>>.van-tabbar {
    height: 50px;
  }
</style>