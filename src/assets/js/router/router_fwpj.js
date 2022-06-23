const fwpj_list = () => import('@/components/fwpj/fwpj_list')
const fwpj_detail = () => import('@/components/fwpj/fwpj_detail')
const fwpj_wc = () => import('@/components/fwpj/fwpj_wc')
var routes = [{
  path: '/fwpj_detail', 
  name: 'fwpj_detail',
  component: fwpj_detail
},{
  path: '/fwpj_list', 
  name: 'fwpj_list',
  component: fwpj_list
},{
  path: '/fwpj_wc', 
  name: 'fwpj_wc',
  component: fwpj_wc
}]
export default routes

