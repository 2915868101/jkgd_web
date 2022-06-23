const hsyy_qr = () => import('@/components/hsyy/hsyy_qr') 
const hsyy_yycg = () => import('@/components/hsyy/hsyy_yycg') 
const hsyy_yyjl = () => import('@/components/hsyy/hsyy_yyjl') 
var routes = [{
  path: '/hsyy_qr', 
  name: 'hsyy_qr',
  component: hsyy_qr
},{
  path: '/hsyy_yycg', 
  name: 'hsyy_yycg',
  component: hsyy_yycg
},{
  path: '/hsyy_yyjl', 
  name: 'hsyy_yyjl',
  component: hsyy_yyjl
}]
export default routes

