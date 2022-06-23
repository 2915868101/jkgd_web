const grzx_index = () => import('@/components/grzx/grzx_index')
const grzx_grzl = () => import('@/components/grzx/grzx_grzl')
const grzx_xg_sjhm = () => import('@/components/grzx/grzx_xg_sjhm')
const grzx_ghjl = () => import('@/components/grzx/grzx_ghjl')
const grzx_jfjl = () => import('@/components/grzx/grzx_jfjl')
const grzx_fwpj = () => import('@/components/grzx/grzx_fwpj')
const grzx_fwpj_wc = () => import('@/components/grzx/grzx_fwpj_wc')

const doctor_lists = () => import('@/components/grzx/doctor_lists')

const grzx_yycx = () => import('@/components/grzx/grzx_yycx')
var routes = [{
  path: '/grzx_index', 
  name: 'grzx_index',
  component: grzx_index
},{
  path: '/grzx_grzl', 
  name: 'grzx_grzl',
  component: grzx_grzl
},{
  path: '/grzx_xg_sjhm', 
  name: 'grzx_xg_sjhm',
  component: grzx_xg_sjhm
},{
  path: '/grzx_ghjl', 
  name: 'grzx_ghjl',
  component: grzx_ghjl
},{
  path: '/grzx_jfjl', 
  name: 'grzx_jfjl',
  component: grzx_jfjl
},{
  path: '/grzx_fwpj', 
  name: 'grzx_fwpj',
  component: grzx_fwpj
},{
  path: '/grzx_fwpj_wc', 
  name: 'grzx_fwpj_wc',
  component: grzx_fwpj_wc
},{
  path: '/doctor_lists', 
  name: 'doctor_lists',
  component: doctor_lists
},{
  path: '/grzx_yycx', 
  name: 'grzx_yycx',
  component: grzx_yycx
}]
export default routes

