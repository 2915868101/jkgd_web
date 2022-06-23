<template>
  <div class="grzx_yycx g-wrap">
    <div class="t-title">运营监管列表</div>
    <div class="list-wrap">
      <div>
        <van-cell is-link @click="showMyddc(`${c.id}`)" v-for="(c,i) in infos" :key="i">{{c.name}}</van-cell>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'grzx_yycx',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('运营监管列表')
    },
    data() {
      return {
        infos: [{
            name: '公卫运营监管',
            id: '2'
          },
          {
            name: '医政运营监管',
            id: '3'
          }, {
            name: '领导运营监控',
            id: '4'
          }, {
            name: '卫生资源监控 ',
            id: '5'
          }, {
            name: '健康扶贫监控',
            id: '6'
          }
        ],
        userInfo: ''
      }
    },
    methods: {
      showMyddc(id) {
        var t = this
        if (t.serverType == 'gd') {
          if (id == 3) {
            window.location.href = 'http://www.jkgd12320.com:8086/yzk/dist/#/?token=' + t.wx_userInfo.openid
          } else if (id == 2) {
            window.location.href = 'http://www.jkgd12320.com:8086/ggws/dist/#/?token=' + t.wx_userInfo.openid
          } else if (id == 4) {
            window.location.href = 'http://www.jkgd12320.com:8086/ldjk/dist/#/?token=' + t.wx_userInfo.openid
          } else if (id == 5) {
            window.location.href = 'http://www.jkgd12320.com:8086/wszy/dist/#/?token=' + t.wx_userInfo.openid
          } else if (id == 6) {
            window.location.href = 'http://www.jkgd12320.com:8086/wszy/dist/#/zyzb_zyyzb'
          }
        }
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  .g-wrap {
    background: #f6f6f6;
  }

  .grzx_yycx {
    padding: 15px;
    font-size: 15px;
  }

  .grzx_yycx .t-title {
    font-size: 18px;
    font-weight: bold;
    color: #373737;
    padding: 10px;
    text-align: center;
  }

  .list-wrap {
    margin: 20px 0;
  }

  .list-wrap>div {
    border-radius: 10px;
  }
</style>