import Vue from 'vue'
import VueRouter from 'vue-router'
import '@/assets/js/util/http.js'

const noPage = () => import('@/components/util/404')
const amap = () => import('@/components/util/amap')

function getQueryString(name) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)")
  var r = window.location.search.substr(1).match(reg)
  return r ? decodeURI(r[2]) : null
}
Vue.prototype.wechat_code = getQueryString('code')
Vue.use(VueRouter)
// jkfw_index app端 login_index
var routes = [{
  path: '/',
  redirect: '/jkfw_index'
}, {
  path: '*',
  name: '404',
  component: noPage
}, {
  path: '/amap',
  name: 'amap',
  component: amap
}]
const files = require.context("../router/", false, /\.js$/)
files.keys().forEach((key) => {
  let route = files(key).default
  routes = routes.concat(route)
})

export default new VueRouter({
  routes: routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }
})