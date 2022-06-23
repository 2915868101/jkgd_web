import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
var state = {
  routerLoading: false,
  toPageName: '',
  fromPageName: '',
  title: '',
  wx_userInfo: '',
  wx_configParams: '',
  register_info: '',
  def_headimg: require('@/assets/img/util/headerImg.jpg'),
  def_hospitalimg: require('@/assets/img/util/hospitalimg.png'),
  def_adminimg: require('@/assets/img/util/adminimg.png'), 
  rules: {
    idCard: /^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/,
    mobile: /^1\d{10}$/,
    email: /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
  }
}
var mutations = {
  updateLoading(state, loading) {
    state.routerLoading = loading
  },
  updateTitle(state, title) {
    if(document){
      document.title = title
    }
    state.title = title
  }
}

const files = require.context("../store", false, /\.js$/)
const modules = {}

files.keys().forEach((key) => {
  modules[key.replace(/(\.\/|\.js)/g, "")] = files(key).default
})

import storeConfig from '@/storeConfig.js'
modules['storeConfig'] = storeConfig

Object.keys(modules).forEach((key) => {
  modules[key]["namespaced"] = true
})

export default new Vuex.Store({
  state: state,
  mutations: mutations,
  modules
})
