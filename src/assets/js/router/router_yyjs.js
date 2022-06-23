const yyjs_index = () => import('@/components/yyjs/yyjs_index')
const yyjs_ks = () => import('@/components/yyjs/yyjs_ks')
const yyjs_ys = () => import('@/components/yyjs/yyjs_ys')
const yyjs_ys_grjs = () => import('@/components/yyjs/yyjs_ys_grjs') 
var routes = [{
  path: '/yyjs_index', 
  name: 'yyjs_index',
  component: yyjs_index
},{
  path: '/yyjs_ks', 
  name: 'yyjs_ks',
  component: yyjs_ks
},{
  path: '/yyjs_ys', 
  name: 'yyjs_ys',
  component: yyjs_ys
},{
  path: '/yyjs_ys_grjs', 
  name: 'yyjs_ys_grjs',
  component: yyjs_ys_grjs
}]
export default routes

