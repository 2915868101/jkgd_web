<template>
  <div class="mbgl_index g-wrap g-hp100">
    <div class="banner-wrap">
      <img class="g-wp100" src="../../assets/img/mbgl/banner1.png">
    </div>
    <div class="menu-list">
      <div class="list-item f-bs3" @click="linkToPage('mbgl_mbjh',{token:token})">
        <img class="g-w40 f-ml10 f-mr20" src="../../assets/img/mbgl/menu-icon1.png">
        <p>慢病监护</p>
      </div>
      <div class="list-item f-bs3" @click="linkToPage('jkzc_index')">
        <img class="g-w40 f-ml10 f-mr20" src="../../assets/img/mbgl/menu-icon2.png">
        <p>健康评估</p>
      </div>
      <div class="list-item f-bs3" @click="linkToPage('mbgl_jktj_list',{token:token})">
        <img class="g-w40 f-ml10 f-mr20" src="../../assets/img/mbgl/menu-icon3.png">
        <p>健康体检</p>
      </div>
      <div class="list-item f-bs3" @click="linkToPage('mbgl_jkxj')">
        <img class="g-w40 f-ml10 f-mr20" src="../../assets/img/mbgl/menu-icon4.png">
        <p>健康宣教</p>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'mbgl_index',
    components: {},
    created() {
      var t = this
      t.updateTitle('慢病管理')
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(sucFun) {
        t.sj = sucFun.CONTENTS[0]
        t.gettoken(t.sj)

      })

    },
    computed: {},
    data() {
      return {
        sj: ''
      }
    },
    methods: {
      gettoken() {
        var t = this
        t.ajax({
          serverId: 'WXBMGDJKDA01',
          url: t.serverPath2,
          params: {
            CNUM: t.sj.CARD_NUMBER,
            NAME: t.sj.NAME
          },
          success: function(data) {
            console.log(data)
            localStorage.token = JSON.stringify(data[0].result);
            t.token = JSON.parse(localStorage.token)
          }
        })
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  .mbgl_index {
    position: relative;
  }

  .mbgl_index .banner-wrap img {
    vertical-align: middle;
    height: 190px;
  }

  .mbgl_index .menu-list {
    padding: 20px 20px 0;
    width: 100%;
  }

  .mbgl_index .menu-list .list-item {
    background: #fff;
    padding: 20px 20px;
    border-radius: 3px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    font-size: 15px;
    font-weight: bold;
    color: #303133;
  }
</style>