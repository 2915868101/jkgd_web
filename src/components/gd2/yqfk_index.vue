<template>
  <div class="yqfk_index s-bgcfff">
    <div class="f-pl10 f-pr10 f-pb5 f-pt10"><img :src="require('../../assets/img/gd2/banner3.png')"></div>
    <div class="bg f-pl15 f-pr15 f-pt5">
      <van-row gutter="15">
        <div v-for="(c,i) in list" :key="i">
          <van-col span="12" @click="handlerMenu(c)">
            <div :class=a[i]>
              <div class="main f-mt15 f-fs14">
                <div class="f-fl "><img :src=c.img height="20px" /></div>
                <div class="f-lh18 g-w100 f-pl5 f-fr "> {{c.text}}</div>
              </div>
            </div>
          </van-col>
        </div>
      </van-row>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'yqfk_index',
    components: {},
    created() {
      var t = this
      // 首页菜单显示配置 
      t.updateTitle('疫情防控')
      //判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(sucFun) {
        t.userInfo = sucFun.CONTENTS[0]
      })
      this.a = ['a1', 'a2', 'a3', 'a4', 'a5', 'a6', 'a7', 'a8']
    },
    computed: {},
    data() {
      return {
        a: [],
        list: [{
          url: "https://www.ahzwfw.gov.cn/bog-bsdt/static/PreventionAndControl/sameJourneyQuery.html",
          islink: '1',
          img: require('../../assets/img/gd2/icon_8.png'),
          text: "新冠同行程查询"
        }, {
          url: 'https://sa.sogou.com/new-weball/page/sgs/epidemic?type_page=VR',
          islink: '1',
          img: require('../../assets/img/gd2/icon_11.png'),
          text: '最新疫情通报与管理 ',
        }, {
          url: 'http://www.nhc.gov.cn/xcs/yqfkdt/202001/bc661e49b5bc487dba182f5c49ac445b.shtml?R0NMKk6uozOC=1621585144788',
          islink: '1',
          img: require('../../assets/img/gd2/icon_5.png'),
          text: '新冠预防手册 ',
        }, {
          url: 'https://mp.weixin.qq.com/s/Krr49tUnGi59TE_YJvbbZg',
          islink: '1',
          img: require('../../assets/img/gd2/icon_6.png'),
          text: '新冠预约须知 ',
        }, {
          url: ' http://bmfw.www.gov.cn/hsjcjgcx/index.html',
          islink: '1',
          img: require('../../assets/img/gd2/icon_4.png'),
          text: '核酸检测机构查询 ',
        }, {
          url: 'http://bmfw.www.gov.cn/xxgzbdfyyqfkzsk/index.html',
          islink: '1',
          img: require('../../assets/img/gd2/icon_2.png'),
          text: '疫情防控知识库',
          label: ''
        }, {
          url: " https://ncp.patentstar.cn/",
          islink: '1',
          img: require('../../assets/img/gd2/icon_3.png'),
          text: "防疫专利信息共享平台"
        }, {
          url: ' http://www.gov.cn/zhengce/qiyefugongfuchanzczlc/mobile.htm',
          islink: '1',
          img: require('../../assets/img/gd2/icon_7.png'),
          text: '企业复工复产政策查询 ',
        }],
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
  .yqfk_index .bg {
    border-top: 10px solid #f4f9f9;
  }

  .yqfk_index .main {
    height: 80px;
    padding: 20px;
    padding-top: 25px;
    border-radius: 2px;
    box-shadow: 1px 1px 5px #c0c4cc;
  }

  .yqfk_index .a1 .main {
    background: #E4F2FF;
  }

  .yqfk_index .a2 .main {
    background: #FFFDE4;
  }

  .yqfk_index .a3 .main {
    background: #E4EDFF;
  }

  .yqfk_index .a4 .main {
    background: #EBFFE4;
  }

  .yqfk_index .a5 .main {
    background: #E4F7FF;
  }

  .yqfk_index .a6 .main {
    background: #E4f1FF;
  }

  .yqfk_index .a7 .main {
    background: #FFF6E4;
  }

  .yqfk_index .a8 .main {
    background: #F0ECFE;
  }
</style>