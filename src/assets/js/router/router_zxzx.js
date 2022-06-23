const zxzx_index = () => import('@/components/zxzx/zxzx_index')
const zxzx_detail = () => import('@/components/zxzx/zxzx_detail') 
const zxzx_record1 = () => import('@/components/zxzx/zxzx_record1')
const zxzx_record2 = () => import('@/components/zxzx/zxzx_record2')
const zxzx_record3 = () => import('@/components/zxzx/zxzx_record3')
var routes = [{
  path: '/zxzx_index', 
  name: 'zxzx_index',
  component: zxzx_index
},{
  path: '/zxzx_detail', 
  name: 'zxzx_detail',
  component: zxzx_detail
},{
  path: '/zxzx_record1', 
  name: 'zxzx_record1',
  component: zxzx_record1
},{
  path: '/zxzx_record2', 
  name: 'zxzx_record2',
  component: zxzx_record2
},{
  path: '/zxzx_record3', 
  name: 'zxzx_record3',
  component: zxzx_record3
}]
export default routes

