<template>
  <div class="wodo_index g-wrap f-pr f-oh">
    <preview-dicom :src="showDicom" v-if="showDicom"></preview-dicom>
    <div class="m-lists-box">
      <div :class="['arrow arrow_left',{disabled:showIndex==0}]" @click="prevDicom"></div>
      <div :class="['arrow arrow_right',{disabled:showIndex==images.length-1}]" @click="nextDicom"></div>
      <div class="m-progress-bar">
        <div class="m-progress" :style="{width:Math.round(((showIndex+1)/images.length)*10000)/100+'%'}"></div>
      </div>
      <div class="g-wrap g-wp100 f-oh">
        <div class="g-wrap g-wp100 m-lists">
          <div class="f-cb" :style="{width:images.length*65+'px'}">
            <div v-for="(c,i) in images" :key="i" :class="['f-fl lis',{on:showIndex==i}]" @click="changeDicom(c,i)">
              <preview-dicom :src="c" :no-touch="true" spinner-size="20px" ref="part_lists"></preview-dicom>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import previewDicom from '@/components/util/preview_dicom'
export default {
  name: 'wodo_index',
  directives: {},
  components: {
    previewDicom
  },
  created() {
    var t = this
    t.updateTitle('报告图像')
    // t.cacheImages = ['/static/wadojs/series-000001/111111.jpg', '/static/wadojs/series-000001/image-000000.dcm', '/static/wadojs/series-000001/image-000001.dcm', '/static/wadojs/series-000001/image-000002.dcm', '/static/wadojs/series-000001/image-000003.dcm', '/static/wadojs/series-000001/image-000004.dcm', '/static/wadojs/series-000001/image-000005.dcm', '/static/wadojs/series-000001/image-000006.dcm']
    t.cacheImages = t.queryData.img
    t.showDicom = t.cacheImages[0]
    t.asynLoadDicom(0)
    t.autoScroll()
  },
  computed: {
    // websock() {
    //   return this.$store.state.store_websocket.websock
    // }
  },
  watch: {
    dic_flag(v) {
      var t = this
      if (v && t.images.length < t.cacheImages.length) {
        t.asynLoadDicom(t.images.length)
      }
    }
  },
  data() {
    return {
      images: [],
      baseUrl: '/static/wadojs/series-000001/',
      showDicom: '',
      showIndex: 0,
      timer: null,
      cacheImages: [],
      dic_flag: ''
    }
  },
  methods: {
    asynLoadDicom(i) {
      var t = this
      t.$set(t.images, i, t.cacheImages[i])
      t.$nextTick(function() {
        var dic = t.$refs.part_lists[i]
        t.$set(t, 'dic_flag', dic.flag)
      })
    },
    changeDicom(c, i) {
      var t = this
      t.showDicom = t.images[i]
      t.showIndex = i
      t.autoScroll()
    },
    prevDicom() {
      var t = this
      if (t.showIndex > 0) {
        t.changeDicom(null, t.showIndex - 1)
      }
    },
    nextDicom() {
      var t = this
      if (t.showIndex < t.images.length - 1) {
        t.changeDicom(null, t.showIndex + 1)
      }
    },
    autoScroll() {
      var t = this
      t.$nextTick(function() {
        clearInterval(t.timer)
        var _b = document.querySelector('.wodo_index .m-lists')
        var _offsetLeft = t.showIndex * 58
        if (_offsetLeft >= 174) {
          if (_b.scrollLeft <= _offsetLeft - 174) {
            t.timer = setInterval(function() {
              _b.scrollLeft++
              if (parseInt(_b.scrollLeft) >= _offsetLeft - 174) {
                clearInterval(t.timer)
              } else {
                if (t.showIndex == t.images.length - 1 && parseInt(_b.scrollLeft) >= _offsetLeft - 232) {
                  clearInterval(t.timer)
                }
              }
            }, 1)
          } else {
            t.timer = setInterval(function() {
              _b.scrollLeft--
              if (parseInt(_b.scrollLeft) <= _offsetLeft - 174) {
                clearInterval(t.timer)
              }
            }, 1)
          }
        } else {
          t.timer = setInterval(function() {
            _b.scrollLeft--
            if (parseInt(_b.scrollLeft) <= 0) {
              clearInterval(t.timer)
            }
          }, 1)
        }
      })
    },
  },
  mounted() {}
}
</script>
<style scoped>
.wodo_index {
  padding-bottom: 75px;
  background-color: #000;
}

.wodo_index .m-lists-box {
  width: 282px;
  height: 50px;
  position: absolute;
  bottom: 25px;
  left: 50%;
  margin-left: -141px;
}

.wodo_index .m-lists-box .m-lists {
  overflow-x: auto;
}

.wodo_index .m-lists-box .m-lists .lis {
  width: 50px;
  height: 50px;
  margin-right: 8px;
  border: 2px solid #444;
  padding: 1px;
  overflow: hidden;
}

.wodo_index .m-lists-box .m-lists .lis.on {
  border: 2px solid #fff;
}

.wodo_index .m-lists-box .arrow {
  position: absolute;
  width: 30px;
  height: 50px;
  top: 0;
  background-position: center center;
  background-repeat: no-repeat;
  background-size: 60% auto;
}

.wodo_index .m-lists-box .arrow.arrow_left {
  background-image: url(../../assets/img/wodo/arrow_left2.svg);
  left: -30px;
}

.wodo_index .m-lists-box .arrow.arrow_left.disabled {
  background-image: url(../../assets/img/wodo/arrow_left1.svg);
}

.wodo_index .m-lists-box .arrow.arrow_right {
  background-image: url(../../assets/img/wodo/arrow_right2.svg);
  right: -30px;
}

.wodo_index .m-lists-box .arrow.arrow_right.disabled {
  background-image: url(../../assets/img/wodo/arrow_right1.svg);
}

.wodo_index .m-lists-box .m-progress-bar,
.wodo_index .m-lists-box .m-progress {
  height: 4px;
  line-height: 4px;
  border-radius: 2px;
  overflow: hidden;
}

.wodo_index .m-lists-box .m-progress-bar {
  width: 220px;
  background-color: #fff;
  position: absolute;
  bottom: 75px;
  left: 50%;
  margin-left: -110px;
}

.wodo_index .m-lists-box .m-progress {
  background-color: #7f7f7f;
}
</style>