const yygh_index = () => import('@/components/yygh/yygh_index')
const yygh_ks = () => import('@/components/yygh/yygh_ks')
const yygh_ks_home = () => import('@/components/yygh/yygh_ks_home')
const yygh_qryy = () => import('@/components/yygh/yygh_qryy')
const yygh_yycg = () => import('@/components/yygh/yygh_yycg')
const yygh_ys_home = () => import('@/components/yygh/yygh_ys_home')
const yygh_ys_pb = () => import('@/components/yygh/yygh_ys_pb')
const yygh_search = () => import('@/components/yygh/yygh_search')
const yygh_wc = () => import('@/components/yygh/yygh_wc')
var routes = [{
  path: '/yygh_index',
  name: 'yygh_index',
  component: yygh_index
},{
  path: '/yygh_ks',
  name: 'yygh_ks',
  component: yygh_ks
},{
  path: '/yygh_ks_home',
  name: 'yygh_ks_home',
  component: yygh_ks_home
},{
  path: '/yygh_qryy',
  name: 'yygh_qryy',
  component: yygh_qryy
},{
  path: '/yygh_yycg',
  name: 'yygh_yycg',
  component: yygh_yycg
},{
  path: '/yygh_ys_home',
  name: 'yygh_ys_home',
  component: yygh_ys_home
},{
  path: '/yygh_ys_pb',
  name: 'yygh_ys_pb',
  component: yygh_ys_pb
},{
  path: '/yygh_search',
  name: 'yygh_search',
  component: yygh_search
},{
  path: '/yygh_wc',
  name: 'yygh_wc',
  component: yygh_wc
}]
export default routes
