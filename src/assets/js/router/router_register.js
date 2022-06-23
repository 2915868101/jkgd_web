import Vue from 'vue'
// 是否是电子健康卡注册 register:惠州 register1:池州
var _url=Vue.prototype.serverHealthCard?'register':'register1' 
const register_card = () => import('@/components/'+_url+'/register_card')
const register_index = () => import('@/components/'+_url+'/register_index')
const oper_people_detils = () => import('@/components/'+_url+'/oper_people_detils')
const people_lists = () => import('@/components/'+_url+'/people_lists')
const people_detail = () => import('@/components/'+_url+'/people_detail') 
//惠州人脸认证 Android:face_verify_jg ios:face_verify
var face_url=/Android/i.test(navigator.userAgent)?'face_verify_jg':'face_verify' 
const face_verify = () => import('@/components/'+_url+'/'+face_url)
var routes = [{
  path: '/register_card',
  name: 'register_card',
  component: register_card
},{
  path: '/register_index',
  name: 'register_index',
  component: register_index
},{
  path: '/oper_people_detils',
  name: 'oper_people_detils',
  component: oper_people_detils
},{
  path: '/people_lists',
  name: 'people_lists',
  component: people_lists
},{
  path: '/people_detail',
  name: 'people_detail',
  component: people_detail
},{
  path: '/face_verify',
  name: 'face_verify',
  component: face_verify
}]
export default routes
