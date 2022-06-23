const index = () => import('@/components/index/index')
const index_more = () => import('@/components/index/index_more')
const index_search = () => import('@/components/index/index_search')
const syywsm = () => import('@/components/index/syywsm')
const hzlyb = () => import('@/components/index/hzlyb')
var routes = [{
  path: '/index', 
  name: 'index',
  component: index
},{
  path: '/index_more', 
  name: 'index_more',
  component: index_more
},{
  path: '/index_search', 
  name: 'index_search',
  component: index_search
},{
  path: '/syywsm', 
  name: 'syywsm',
  component: syywsm
},{
  path: '/hzlyb', 
  name: 'hzlyb',
  component: hzlyb
}]
export default routes

