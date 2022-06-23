const bgcx_index = () => import('@/components/bgcx/bgcx_index')
const bgcx_jc = () => import('@/components/bgcx/bgcx_jc')
const bgcx_jy = () => import('@/components/bgcx/bgcx_jy')
var routes = [{
  path: '/bgcx_index', 
  name: 'bgcx_index',
  component: bgcx_index
},{
  path: '/bgcx_jc', 
  name: 'bgcx_jc',
  component: bgcx_jc
},{
  path: '/bgcx_jy', 
  name: 'bgcx_jy',
  component: bgcx_jy
}]
export default routes

