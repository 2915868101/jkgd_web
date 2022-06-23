const mzjf_index = () => import('@/components/mzjf/mzjf_index') 
const mzjf_detail = () => import('@/components/mzjf/mzjf_detail') 
var routes = [{
  path: '/mzjf_index', 
  name: 'mzjf_index',
  component: mzjf_index
},{
  path: '/mzjf_detail', 
  name: 'mzjf_detail',
  component: mzjf_detail
}]
export default routes

