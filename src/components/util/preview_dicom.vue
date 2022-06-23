<template>
  <div class="previewDicom g-wrap">
    <div class="g-wrap f-oh f-pr" v-if="src.indexOf('.dcm')!=-1">
      <div class="u-spinner" v-if="!flag">
        <div class="f-df g-wrap">
          <div class="f-df1 f-dfv">
            <div class="f-tac">
              <van-loading :type="spinnerType||'lines'" :size="spinnerSize||'40px'"></van-loading>
            </div>
          </div>
        </div>
      </div>
      <div ref="dicomImage" class="g-wrap" ></div>
    </div>
    <img :src="src" ref="dicomImage" class="img" v-else>
  </div> 
</template>
<script>
import { Loading } from 'vant'
//引入 cornerstone,dicomParser,cornerstoneWADOImageLoader
import * as cornerstone from 'cornerstone-core'
import * as dicomParser from 'dicom-parser'
import * as cornerstoneWADOImageLoader from 'cornerstone-wado-image-loader'
// Cornerstone 工具外部依赖
import * as cornerstoneMath from 'cornerstone-math'
import '^/alloy_finger/transform.js'
import AlloyFinger from 'alloyfinger'
export default {
  name: 'previewDicom',
  props: ['src', 'spinnerType', 'noTouch', 'spinnerSize'],
  directives: {},
  components: {
    [Loading.name]: Loading
  },
  created() {
    var t = this
    t.initDicomConfig()
    t.$set(t.$parent.$data, 'dic_flag', false)
  },
  computed: {
    url() {
      var t = this
      return 'wadouri:' + t.src
    }
  },
  watch: {
    url() {
      var t = this
      t.$nextTick(function() {
        t.loadAndViewImage(t.url)
      })
    }
  },
  data() {
    return {
      zoom: 100,
      flag: false
    }
  },
  methods: {
    initDicomConfig() {
      var t = this
      if (!t.$store.state.store_bgcx.isWebWorkerManagerInitialized) {
        t.$store.state.store_bgcx.isWebWorkerManagerInitialized = true
        //指定外部依赖
        cornerstoneWADOImageLoader.external.dicomParser = dicomParser
        cornerstoneWADOImageLoader.external.cornerstoneMath = cornerstoneMath
        //指定要注册加载程序的基石实例
        cornerstoneWADOImageLoader.external.cornerstone = cornerstone
        cornerstone.registerImageLoader("http", cornerstoneWADOImageLoader.loadImage)
        cornerstone.registerImageLoader("https", cornerstoneWADOImageLoader.loadImage)
        // 配置 webWorker (必须配置)
        var config = {
          webWorkerPath: t.getBaseUrl() + '/static/wadojs/cornerstoneWADOImageLoaderWebWorker.js',
          taskConfiguration: {
            decodeTask: {
              codecsPath: t.getBaseUrl() + '/static/wadojs/cornerstoneWADOImageLoaderCodecs.js'
            }
          }
        }
        cornerstoneWADOImageLoader.webWorkerManager.initialize(config)
      }
    },
    resetZoom() {
      var t = this
      var element = t.$refs.dicomImage
      if (t.zoom != 100) {
        t.zoom = 100
        element.style.zoom = t.zoom + '%'
        element.translateX = 0
        element.translateY = 0
      }
    },
    doubleTapZoom() {
      var t = this
      var element = t.$refs.dicomImage
      if (t.zoom > 100) {
        t.resetZoom()
      } else {
        t.zoom = 150
        element.translateX = -75
        element.translateY = -130
      }
      element.style.zoom = t.zoom + '%'
    },
    loadAndViewImage(imageId) {
      var t = this
      var element = t.$refs.dicomImage
      t.resetZoom()
      if (t.src.indexOf('.dcm') != -1) {
        cornerstone.enable(element)
        // cornerstone.loadAndCacheImage 函数 负责加载图形 需要 图像地址 imageId
        cornerstone.loadAndCacheImage(imageId).then(function(image) {
          var viewport = cornerstone.getDefaultViewportForImage(element, image)
          cornerstone.displayImage(element, image, viewport)
          t.flag = true
          t.$set(t.$parent.$data, 'dic_flag', true)
          t.doAlloyFinger()
        }, function(err) {
          console.error(err)
        })
      } else {
        t.flag = true
        t.$set(t.$parent.$data, 'dic_flag', true)
        t.doAlloyFinger()
      }
    },
    doAlloyFinger() {
      var t = this
      if (!t.noTouch) {
        var element = t.$refs.dicomImage
        t.Transform(element)
        var initScale = 1
        new AlloyFinger(element, {
          multipointStart: function() {
            initScale = element.scaleX
          },
          rotate: function(evt) {
            element.rotateZ += evt.angle
          },
          pinch: function(evt) {
            element.scaleX = element.scaleY = initScale * evt.zoom
          },
          doubleTap: function() {
            t.doubleTapZoom()
          },
          pressMove: function(evt) {
            element.translateX += evt.deltaX
            element.translateY += evt.deltaY
            evt.preventDefault()
          }
        })
      }
    }
  },
  mounted() {
    var t = this
    t.loadAndViewImage(t.url)
  }
}
</script>
<style scoped>
.previewDicom {
  background-color: #000;
}

.previewDicom .u-spinner {
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
}
</style>