const mbgl_index = () => import('@/components/mbgl/mbgl_index')
const mbgl_mbjh = () => import('@/components/mbgl/mbgl_mbjh')
const mbgl_jkxj = () => import('@/components/mbgl/mbgl_jkxj')
const mbgl_jktj = () => import('@/components/mbgl/mbgl_jktj')
const mbgl_jktj_list = () => import('@/components/mbgl/mbgl_jktj_list')
var routes = [{
  path: '/mbgl_index', 
  name: 'mbgl_index',
  component: mbgl_index
},{
  path: '/mbgl_mbjh', 
  name: 'mbgl_mbjh',
  component: mbgl_mbjh
},{
  path: '/mbgl_jkxj', 
  name: 'mbgl_jkxj',
  component: mbgl_jkxj
},{
  path: '/mbgl_jktj', 
  name: 'mbgl_jktj',
  component: mbgl_jktj
},{
  path: '/mbgl_jktj_list', 
  name: 'mbgl_jktj_list',
  component: mbgl_jktj_list
}]
export default routes

