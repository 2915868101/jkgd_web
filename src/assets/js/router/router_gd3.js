const jkfw_index = () => import('@/components/gd3/jkfw_index')
const yyjs2_index = () => import('@/components/gd3/yyjs2_index')
var routes = [{
  path: '/jkfw_index',
  name: 'jkfw_index',
  component: jkfw_index
}, {
  path: '/yyjs2_index',
  name: 'yyjs2_index',
  component: yyjs2_index
}]
export default routes