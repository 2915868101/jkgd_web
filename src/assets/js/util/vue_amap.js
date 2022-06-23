import Vue from 'vue'
import VueAMap from 'vue-amap'
Vue.use(VueAMap)
 
//初始化高德地图
VueAMap.initAMapApiLoader({
  //高德的key
  key: 'c4488ad1cc622d5e31ab7eb4a136497c',
  //插件集合
  plugin: ['AMap.Autocomplete', 'AMap.PlaceSearch', 'AMap.Scale', 'AMap.OverView', 'AMap.ToolBar', 'AMap.MapType', 'AMap.PolyEditor', 'AMap.CircleEditor','AMap.Geolocation'],
  //默认高德 sdk 版本为 1.4.4
  v: '1.4.4'
})

export default VueAMap