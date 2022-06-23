const jkbk_index = () => import('@/components/jkbk/jkbk_index')
const jkbk_lists = () => import('@/components/jkbk/jkbk_lists')
const jkbk_details = () => import('@/components/jkbk/jkbk_details')
var routes = [{
    path: '/jkbk_index',
    name: 'jkbk_index',
    component: jkbk_index
  },
  {
    path: '/jkbk_lists/:id',
    name: 'jkbk_lists',
    component: jkbk_lists
  },
  {
    path: '/jkbk_details/:id',
    name: 'jkbk_details',
    component: jkbk_details
  }
]
export default routes