const guidance = () => import('@/components/guidance/index')
const guidance_index = () => import('@/components/guidance/guidance_index')
const part_lists = () => import('@/components/guidance/part_lists')
const suggestion_section = () => import('@/components/guidance/suggestion_section')
var routes = [{
  path: '/guidance',
  name: 'guidance',
  component: guidance
},{
  path: '/guidance_index',
  name: 'guidance_index',
  component: guidance_index
},{
  path: '/part_lists',
  name: 'part_lists',
  component: part_lists
},{
  path: '/suggestion_section',
  name: 'suggestion_section',
  component: suggestion_section
}]
export default routes
