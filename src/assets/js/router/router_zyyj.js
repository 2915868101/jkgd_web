const zyyj_index = () => import('@/components/zyyj/zyyj_index') 
const zyyj_detail = () => import('@/components/zyyj/zyyj_detail') 
const zyyj_money = () => import('@/components/zyyj/zyyj_money') 
const zyyj_jfcg = () => import('@/components/zyyj/zyyj_jfcg') 
var routes = [{
  path: '/zyyj_index', 
  name: 'zyyj_index',
  component: zyyj_index
},{
  path: '/zyyj_detail', 
  name: 'zyyj_detail',
  component: zyyj_detail
},{
  path: '/zyyj_money', 
  name: 'zyyj_money',
  component: zyyj_money
},{
  path: '/zyyj_jfcg', 
  name: 'zyyj_jfcg',
  component: zyyj_jfcg
}]
export default routes

