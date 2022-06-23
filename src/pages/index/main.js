import 'amfe-flexible';
import '^/iconfont/iconfont.css'
import 'vue2-animate/dist/vue2-animate.min.css'
import '@/assets/css/util/common.css'
import '@/assets/css/util/base.css'
import '@/assets/css/util/style.css'

import Vue from 'vue'
import '@/config.js'
import App from './App'

import router from '@/assets/js/util/router.js'
import store from '@/assets/js/util/store.js'
import { sync } from 'vuex-router-sync'
sync(store, router)

import vueMoment from 'vue-moment'
import moment from 'moment'
moment.locale('zh-cn')
Vue.use(vueMoment, { moment })

import VueCookies from 'vue-cookies'
Vue.use(VueCookies)

import '@/assets/js/util/vant.js'
import '@/assets/js/util/vue_mixin.js'
import '@/assets/js/util/vue_prototype.js'
import '@/assets/js/util/vue_wechat_func.js'

router.beforeEach(function(to, from, next) {
  store.state.toPageName = to.name
  store.state.fromPageName = from.name
  if (to.name != '404') {
    store.commit('updateLoading', true)
  }
  next()
})
router.afterEach(function() {
  store.commit('updateLoading', false)
})

new Vue({
  router,
  store,
  el: '#app',
  render: h => h(App)
});

