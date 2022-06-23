<template>
  <div class="jkfw_index s-bgcfff">
    <div class="f-pl10 f-pr10 f-pb5 f-pt10"><img :src="require('../../assets/img/gd3/001 .png')"></div>
    <div class="bg f-pl5 f-pr5 f-pt5">
      <van-row gutter="1" class="main">
        <van-col class="main_1" span="8" @click="handlerMenu(c)" v-for="(c,i) in list" :key="i">
          <div class=" f-tac s-bgcfff f-fs14  f-pb20">
            <div class="f-pt20"><img :src=c.img height="50px" /></div>
            <div class=" f-pt10" v-if="c.islink!=2"> {{c.text}}</div>
            <wx-open-launch-weapp v-if="c.islink==2" class="g-wp100 item-r" id="launch-btn" username="gh_45334679e384" path="pages/new_index/index/index">
              <script type="text/wxtag-template">
                <style> 
                    .btn {  
                      width:100%;
                      text-align:center;
                      font-size: 12px;
                      color: #303133;
                      padding-top:8px;
                    }
                  </style> 
                  <div class="btn">电子医保凭证</div> 
                </script>
            </wx-open-launch-weapp>
          </div>
        </van-col>
      </van-row>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jkfw_index',
    components: {},
    created() {
      var t = this
      // 首页菜单显示配置 
      t.updateTitle('健康服务')

      // t.timer = setInterval(function() {
      //   t.getwxapp(t.wx_configParams)
      // }, 100)
    },
    computed: {},
    data() {
      return {
        timer: '',
        a: [],
        list: [{
            url: 'yygh_index',
            img: require('../../assets/img/gd3/Ellipse 6.png'),
            text: '预约挂号 ',
          },
          {
            url: 'bgcx_index',
            img: require('../../assets/img/gd3/Ellipse 7.png'),
            text: '报告查询 ',
          },
          {
            url: 'jkda_index',
            img: require('../../assets/img/gd3/Ellipse 15.png'),
            text: '健康档案 ',
          },
          {
            url: 'tjbg_index',
            img: require('../../assets/img/grzx/ldcx_icon.png'),
            text: '体检报告 ',
          },
          /*  {
             url: 'guidance_index',
             img: require('../../assets/img/gd3/Ellipse 16.png'),
             text: '智能分诊 ',
           }, */
          /*  {
             url: 'hsyy_qr',
             img: require('../../assets/img/gd3/Ellipse 17.png'),
             text: '核酸检测 ',
           }, */
          {
            url: 'people_lists',
            img: require('../../assets/img/gd/icon_8.png'),
            text: '电子健康卡 ',
          },
          {
            url: 'mbgl_index',
            img: require('../../assets/img/gd3/Ellipse 18.png'),
            text: '慢病管理 ',
          },
          {
            url: 'jtys_index',
            img: require('../../assets/img/gd3/Ellipse 19.png'),
            text: '家庭医生 ',
          },
          {
            url: 'https://m.pule.com/coop/hnwisdom_guangde/',
            islink: '1',
            img: require('../../assets/img/gd3/Ellipse 20.png'),
            text: '健康科普视频 ',
          }, {
            url: '',
            islink: '2',
            img: require('../../assets/img/gd3/Ellipse 21.png'),
            text: '电子医保凭证 ',
          }, {
            url: 'yyjs2_index',
            img: require('../../assets/img/gd3/bgcx_icon.png'),
            text: '医院介绍 ',
          }, {
            url: 'https://mp.weixin.qq.com/mp/appmsgalbum?__biz=Mzk0OTI0ODE4Mw==&action=getalbum&album_id=1938331566330429447#wechat_redirect',
            islink: '1',
            img: require('../../assets/img/guidance/jkzsk_icon.png'),
            text: '健康资讯 ',
          }, {
            url: 'grzx_index',
            img: require('../../assets/img/grzx/wdjzr_icon.png'),
            text: '个人中心 ',
          },
        ]
      }
    },
    methods: {
      // getwxapp(config) {
      //   var t = this
      //   var wx_config = JSON.parse(JSON.stringify(config))
      //   wx_config.jsApiList = ['getLocation']
      //   wx_config.openTagList = ['wx-open-launch-weapp']
      //   t.$wechat.config(wx_config)
      //   t.$wechat.ready(function() {
      //     if (t.timer) {
      //       clearInterval(t.timer)
      //       t.timer = ''
      //     }
      //   })
      //   t.$wechat.error(function(res) {
      //     console.log(res)
      //     // t.$dialog.alert({
      //     //   message: `<div class="f-tal"> 
      //     //     参数：${JSON.stringify(res)} 
      //     //   </div>`
      //     // })
      //   })
      // },
      handlerMenu(data) {
        var t = this
        if (data.islink == '1') {
          window.location.href = data.url
        } else if (!data.islink) {
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

              // } else if (data.url == 'jtys_index') {
              //   if (t.serverType == 'gd') {
              //     t.isRegister({
              //       openid: t.wx_userInfo.openid,
              //       type: 0
              //     }, function(sunFun) {
              //       console.log(66666666666, sunFun)
              //       var CARDNUM = sunFun.CONTENTS[0].CARD_NUMBER
              //       var SJHM = sunFun.CONTENTS[0].SJHM
              //       var NAME = sunFun.CONTENTS[0].NAME
              //       t.ajax({
              //         loading: false,
              //         serverId: 'DYWJTYSTZH01',
              //         url: t.serverPath1,
              //         params: {
              //           CARDNUM: CARDNUM
              //         },
              //         success: function(res) {
              //           if (res[0].code != -1) { //如果是医生，进入家医工作台 
              //             t.linkToPage('/jtys_jygzt', {
              //               CARDNUM: CARDNUM,
              //               SJHM: SJHM,
              //               YSID: res[0].YSID
              //             })
              //           } else {
              //             t.ajax({
              //               serverId: 'WXJT0007',
              //               url: t.serverPath4,
              //               params: {
              //                 OPENID: t.wx_userInfo.openid,
              //                 NAME: NAME,
              //                 SJHM: SJHM
              //               },
              //               success: function(data) {
              //                 if (data[data.length - 1].code == 0) {
              //                   t.linkToPage('/jtys_jygzt', {
              //                     CARDNUM: CARDNUM,
              //                     SJHM: SJHM,
              //                     // YSID: res[0].YSID
              //                   })
              //                 } else {
              //                   t.ajax({
              //                     serverId: 'BMFW00019',
              //                     url: t.serverPath1,
              //                     params: {
              //                       OPENID: t.wx_userInfo.openid,
              //                       CARDNUM: CARDNUM
              //                     },
              //                     success: function(data) {
              //                       if (!data[0]) { //没有数据就签约服务包
              //                         t.ajax({
              //                           serverId: 'WXJT0005',
              //                           url: t.serverPath4,
              //                           params: {
              //                             openid: t.wx_userInfo.openid,
              //                           },
              //                           success: function(data) {
              //                             t.fwbList = data[0].CONTENTS
              //                             if (t.fwbList.length == 0) {
              //                               t.linkToPage('jtysfwb_index')
              //                             } else {
              //                               t.linkToPage('jtysfwb_qy_01')
              //                             }
              //                           },
              //                         })
              //                       } else {
              //                         t.linkToPage('jtys_index')
              //                       }
              //                     }
              //                   })
              //                 }
              //               }
              //             })
              //           }
              //         }
              //       })
              //     })
              //   } else {
              //     t.linkToPage(data.url)
              //   }
            } else if (data.url == 'jtysfwb_index') {
              t.ajax({
                serverId: 'WXJT0005',
                url: t.serverPath4,
                params: {
                  openid: t.wx_userInfo.openid,
                },
                success: function(data) {
                  t.fwbList = data[0].CONTENTS
                  if (t.fwbList.length == 0) {
                    t.linkToPage('jtysfwb_index')
                  } else {
                    t.linkToPage('jtysfwb_qy_01')
                  }
                },
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
            if (data.text) {
              t.$toast('正在建设中')
            }
          }
        }
      },

    },
    mounted() {},
    unmounted() {
      let t = this
      if (t.timer) {
        clearInterval(t.timer)
      }
    },
  }
</script>
<style scoped>
  .jkfw_index .bg {
    border-top: 10px solid #f4f9f9;
  }

  .jkfw_index .main {
    background: #DFE2E8;
  }

  .jkfw_index .main .main_1 {
    border-bottom: 1px solid #DFE2E8;
    height: 125px;
    background: #fff;
    position: relative;
  }

  .jkfw_index .main .main_1:not(:nth-child(3n+3)):before {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 1px;
    background: #DFE2E8;
  }
</style>