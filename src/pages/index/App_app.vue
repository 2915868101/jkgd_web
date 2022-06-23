<template>
  <div id="app" class="g-wrap f-pr">
    <div class="f-fdc g-hp100">
      <div class="f-df1 f-pr">
        <transition :name="transitionName">
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
      t.initWxDataInfo()
      console.log('1' + t.$store.state.wx_userInfo)
      console.log('2' + localStorage.wx_userInfo)
      if (localStorage.wx_userInfo) {
        t.linkToPage('/jkfw_index')
      }
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
    methods: {},
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