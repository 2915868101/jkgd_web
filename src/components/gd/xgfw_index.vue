<template>
  <div class="xgfw_index">
    <van-row gutter="10">
      <div v-for="(c,i) in list" :key="i">
        <van-col span="12" @click="handlerMenu(c)">
          <div class="main s-bgcfff f-mt10 f-tac">
            <div class="f-pt20"><img :src=c.img height="45px"></div>
            <div class="f-fwb f-fs14 f-pt5 f-pb12"> <span class="f-fs14">{{c.text}}</span></div>
            <div class="main_1 f-fs13"> <span class="f-fs13">{{c.label}}</span></div>
          </div>
        </van-col>
      </div>
    </van-row>
  </div>
</template>
<script>
  export default {
    name: 'xgfw_index',
    components: {},
    created() {
      // 首页菜单显示配置
      var t = this
      t.updateTitle('新冠服务')
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(sucFun) {
        t.userInfo = sucFun.CONTENTS[0]
      })
    },
    computed: {},
    data() {
      return {
        list: [{
            url: 'yygh_index',
            img: require('../../assets/img/gd/icon_1.png'),
            text: '预约挂号',
            label: '减少医院，停留时间',
          }, {
            url: 'hsyy_qr',
            img: require('../../assets/img/gd/icon_2.png'),
            text: '核酸检测',
            label: '核酸检测'
          }, {
            url: /* 'xgymyy_index' */ '',
            img: require('../../assets/img/gd/icon_5.png'),
            text: '新冠疫苗预约',
            label: '提前预约，减少聚集',
          }, {
            url: '' /* 'people_lists' */ ,
            img: require('../../assets/img/gd/icon_3.png'),
            text: '安康码',
            label: '健康码，出行通行证'
          }, {
            url: "https://feiyan.wecity.qq.com/wuhan/dist/selftest.html#/vaccinumai?nav_color=5391FF&front_color=ffffff",
            islink: '1',
            img: require('../../assets/img/gd/icon_6.png'),
            text: '新冠疫苗智能问答',
            label: '自我检测，健康防护'
          },
          {
            id: 0,
            url: 'jkzc_index',
            img: require('../../assets/img/grzx/ldcx_icon.png'),
            text: '身体健康自测 ',
            label: '身体健康自测'
          },
          {
            id: 1,
            url: 'zwb_jkzc_detail',
            img: require('../../assets/img/gd/icon_4.png'),
            text: '新冠心理评估 ',
            label: '新冠心理评估'
          },
          {
            id: 2,
            url: 'zwb_jkzc_detail',
            img: require('../../assets/img/gd/icon_7.png'),
            text: '中医体质辨识 ',
            label: '中医体质辨识'
          },
        ],
        listData: []
      }
    },
    methods: {
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
            } else if (data.url == 'jtys_index') {
              if (t.serverType == 'gd') {
                console.log(data.url == 'jtys_index')
                t.isRegister({
                  openid: t.wx_userInfo.openid,
                  type: 0
                }, function(sunFun) {
                  var CARDNUM = sunFun.CONTENTS[0].CARD_NUMBER
                  var SJHM = sunFun.CONTENTS[0].SJHM
                  t.ajax({
                    loading: false,
                    serverId: 'DYWJTYSTZH01',
                    url: t.serverPath1,
                    params: {
                      CARDNUM: CARDNUM
                    },
                    success: function(res) {
                      console.log(res)
                      if (res[0].code != -1) { //如果是医生，进入家医工作台 
                        t.linkToPage('/jtys_jygzt', {
                          CARDNUM: CARDNUM,
                          SJHM: SJHM,
                          YSID: res[0].YSID
                        })
                      } else {
                        t.linkToPage(data.url)
                      }
                    }
                  })
                })
              } else {
                t.linkToPage(data.url)
              }
            } else if (data.url == 'zwb_jkzc_detail') {
              var id = data.id
              t.ajax({
                serverId: 'WXTJ00011',
                params: {},
                url: t.serverPath1,
                success(data) {
                  for (var i = 0; i <= data.length; i++) {
                    if (i == id) {
                      t.listData = data[i];
                      console.log(66666, t.listData)
                    }
                  }
                }
              })
              t.ajax({
                method: 'post',
                serverId: 'WXTJ00029',
                url: t.serverPath1,
                params: {
                  openid: t.wx_userInfo.openid,
                  columnid: t.listData.COLUMNID,
                  cnum: t.userInfo.CARD_NUMBER
                },
                success(data) {
                  //进入问卷填写页面
                  if (data[0].BEAN == 'YES') {
                    t.linkToPage('/zwb_jkzc_detail', Object.assign({}, {
                      openid: t.wx_userInfo.openid,
                      cnum: t.userInfo.CARD_NUMBER
                    }, t.listData))
                  }
                  //提示填写就诊档案
                  else {
                    t.show = true;
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
            if (data.text) {
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
  .xgfw_index {
    background: linear-gradient(180deg, #00C792 0%, rgba(0, 199, 146, 0) 100%);
    padding: 20px 15px;
  }

  .xgfw_index .main {
    border-radius: 4px;
  }

  p {
    color: #606266;
  }

  .xgfw_index .main .main_1 {
    background: #FEFBEA;
    padding: 8px;
    border-radius: 0px 0px 4px 4px;
  }
</style>