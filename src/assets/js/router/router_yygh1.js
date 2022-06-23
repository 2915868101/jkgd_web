// 惠州挂号流程 
const yygh_ks_home1 = () => import('@/components/yygh1/yygh_ks_home')
const yygh_qryy1 = () => import('@/components/yygh1/yygh_qryy') 
const yygh_ys_home1 = () => import('@/components/yygh1/yygh_ys_home')
const yygh_ys_pb1 = () => import('@/components/yygh1/yygh_ys_pb')
var routes = [{
  path: '/yygh_ks_home1',
  name: 'yygh_ks_home1',
  component: yygh_ks_home1
},{
  path: '/yygh_qryy1',
  name: 'yygh_qryy1',
  component: yygh_qryy1
},{
  path: '/yygh_ys_home1',
  name: 'yygh_ys_home1',
  component: yygh_ys_home1
},{
  path: '/yygh_ys_pb1',
  name: 'yygh_ys_pb1',
  component: yygh_ys_pb1
}]
export default routes
