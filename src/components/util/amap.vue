<template>
  <div class="amap g-wrap">
    <el-amap ref="map" vid="amapDemo" :zoom="zoom" :center="center" class="g-wrap">
      <el-amap-marker vid="component-marker" :position="componentMarker.position" :content-render="componentMarker.contentRender"></el-amap-marker>
      <el-amap-marker v-for="(marker, index) in markers" :key="index" :position="marker.position" :visible="marker.visible" :draggable="marker.draggable" :vid="index"></el-amap-marker>
    </el-amap>
  </div>
</template>
<script>
import '@/assets/js/util/vue_amap.js'

const exampleComponents = {
  props: ['text'],
  template: `<div>text from  parent: {{text}}</div>`
}
export default {
  name: 'amap',
  components: {},
  created() {
    var t = this
    t.updateTitle('院内地图')
    var LONGITUDE = t.queryData.ORG_LONGITUDE
    var LATITUDE = t.queryData.ORG_LATITUDE 
    if (LONGITUDE && LATITUDE) {
      t.center = [LONGITUDE, LATITUDE]
      t.markers = [{
        position: [LONGITUDE, LATITUDE],
        visible: true,
        draggable: false,
      }]
      t.componentMarker.position = {
        position: [LONGITUDE, LATITUDE],
        contentRender: (h) => h(exampleComponents, { style: { backgroundColor: '#fff' }, props: { text: 'text' } })
      }
    } 
  },
  data() {
    return {
      zoom: 16,
      center: [0, 0],
      componentMarker: {
        position: [114.454521, 31.21315058],
        contentRender: (h) => h(exampleComponents, { style: { backgroundColor: '#fff' }, props: { text: 'is here' } }, ['xxxxxxx'])
      },
      markers: [{
        position: [0, 0]
      }],
    }
  }
}
</script>
<style>
.amap-tips {
  width: 160px;
  padding: 8px;
  background-color: #fff;
  color: #222;
  position: relative;
  left: -70px;
  top: 40px;
  line-height: 1.5;
  border-radius: 7px;
}

.amap-logo,
.amap-copyright {
  display: none !important;
}
</style>