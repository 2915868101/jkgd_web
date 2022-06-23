const yqfk_index = () => import('@/components/gd2/yqfk_index')
const zwb_jkzc_detail = () => import('@/components/gd2/zwb_jkzc_detail')
const zwb_jkzc_history = () => import('@/components/gd2/zwb_jkzc_history')
var routes = [{
  path: '/yqfk_index',
  name: 'yqfk_index',
  component: yqfk_index
}, {
  path: '/zwb_jkzc_detail',
  name: 'zwb_jkzc_detail',
  component: zwb_jkzc_detail
}, {
  path: '/zwb_jkzc_history',
  name: 'zwb_jkzc_history',
  component: zwb_jkzc_history
}]
export default routes