<template>
  <div class="jtys_jygzt g-wrap g-hp100 s-bgcfff">
    <div class="banner-wrap f-pt20 f-pb20 f-pl15 f-pr15">
      <img class="g-wp100" src="../../assets/img/jtys/jygzt-bg.png">
    </div>
    <div class="menu-list">
      <div class="list-item f-df" v-for="(c,i) in menuList" :key="i" @click="handlerMenu(c)">
        <img class="icons" :src="c.icons">
        <div class="f-df1 f-pl10">
          <p class="s-c303133 f-fwb">{{c.txt1}}</p>
          <p class="s-c909399 f-mt8 f-fs12">{{c.txt2}}</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jtys_jygzt',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('家医工作台')
      // 判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function() {})
    },
    data() {
      return {
        menuList: [
          // {
          //   icons: require('../../assets/img/jtys/jygzt-icon1.png'),
          //   txt1: '签约申请',
          //   txt2: '查看签约申请',
          //   url: '/jtys_qysq'
          // },
          {
            icons: require('../../assets/img/jtys/jygzt-icon2.png'),
            txt1: '居民咨询',
            txt2: '查看居民咨询',
            url: '/zxzx_index'
          },
          // {
          //   icons: require('../../assets/img/jtys/jygzt-icon3.png'),
          //   txt1: '签约家庭',
          //   txt2: '已签约的家庭',
          //   url: '/jtys_qyjt'
          // },
          {
            icons: require('../../assets/img/jtys/jygzt-icon1.png'),
            txt1: '随访计划',
            txt2: '我的随访计划',
            url: '/jtys_qysf'
          },
          // {
          //   icons: require('../../assets/img/jtys/jygzt-icon1.png'),
          //   txt1: '签约服务包',
          //   txt2: '已签约的服务包',
          //   url: '/jtysfwb_index'
          // },
          {
            icons: require('../../assets/img/jtys/jygzt-icon3.png'),
            txt1: '我的签约用户',
            txt2: '查看我的签约用户',
            url: '/jtysfwb_qy_team'
          }
        ]
      }
    },
    methods: {
      handlerMenu(data) {
        var t = this
        if (data.islink == '1') {
          window.location.href = data.url
        } else {
          if (data.url == '/jtysfwb_index') {
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
          } else if (data.url) {
            t.linkToPage(data.url, t.queryData)
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
  @media screen and (max-width: 320px) {}

  .jtys_jygzt .menu-list {
    padding: 0 15px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
  }

  .jtys_jygzt .menu-list .list-item {
    border: 1px solid #e8effc;
    border-radius: 5px;
    font-size: 14px;
    width: 48%;
    padding: 10px;
    align-items: center;
    height: 70px;
    margin-bottom: 15px;
  }

  .jtys_jygzt .menu-list .icons {
    width: 45px;
    height: 45px;
  }
</style>