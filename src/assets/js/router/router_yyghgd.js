// 广德人民医院挂号流程 
const yyghgd_index = () => import('@/components/yyghgd/yygh_index')
const yyghgd_xz_ks = () => import('@/components/yyghgd/yygh_xz_ks')
const yyghgd_xz_ys = () => import('@/components/yyghgd/yygh_xz_ys')
const yyghgd_qr = () => import('@/components/yyghgd/yygh_qr')
const yyghgd_yycg = () => import('@/components/yyghgd/yygh_yycg')
const yyghgd_search = () => import('@/components/yyghgd/yygh_search')
var routes = [{
  path: '/yyghgd_index',
  name: 'yyghgd_index',
  component: yyghgd_index
},{
  path: '/yyghgd_xz_ks',
  name: 'yyghgd_xz_ks',
  component: yyghgd_xz_ks
},{
  path: '/yyghgd_xz_ys',
  name: 'yyghgd_xz_ys',
  component: yyghgd_xz_ys
},{
  path: '/yyghgd_qr',
  name: 'yyghgd_qr',
  component: yyghgd_qr
},{
  path: '/yyghgd_yycg',
  name: 'yyghgd_yycg',
  component: yyghgd_yycg
},{
  path: '/yyghgd_search',
  name: 'yyghgd_search',
  component: yyghgd_search
}]
export default routes
