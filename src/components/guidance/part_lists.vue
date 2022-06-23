<template>
  <div class="part_lists g-wrap">
    <div class="part_lists_menus">
      <ul class="icon_lists">
        <li v-for="(l,i) in largePartList" :ref="l.FBWXH==$store.state.store_guidance.showIconIndex?'part_lists':''" :class="['animated fadeInUpBig',{on:l.FBWXH==$store.state.store_guidance.showIconIndex}]" @click="showbodyPartsLists(l)" :style="{'animation-delay':0.1*i+'s'}" :key="i">
          <div :class="[{active:l.FBWXH==$store.state.store_guidance.showIconIndex?true:false}]">
            <img :src="require('../../assets/img/guidance/icons/icon'+l.FBWSX+'_'+(l.FBWXH==$store.state.store_guidance.showIconIndex?'2':'1')+'.svg')" class="icon" />
            <div>{{l.FBWMC}}</div>
          </div>
        </li>
      </ul>
      <div class="cont_lists">
        <van-cell-group gutter="0">
          <van-cell v-for="(c,i) in bodyPartsList" class="animated lightSpeedIn" :style="{'animation-delay':0.2*i+'s'}" @click.native="getProblem(c)" :key="i">
            {{c.BWMC}}
          </van-cell>
        </van-cell-group>
      </div>
    </div>
    <!-- 二级 -->
    <van-popup v-model="$store.state.store_guidance.showDrawer" position="right" :overlay="false">
      <van-cell-group :style="{width:drawerWidth+'px'}" gutter="0">
        <van-cell v-for="(c,i) in cacheData" class="animated lightSpeedIn" :style="{'animation-delay':0.2*i+'s'}" @click.native="toSuggestionSection(c)" :key="i">
          {{c.WTMS}}
        </van-cell>
      </van-cell-group>
    </van-popup>
  </div>
</template>
<script>
  export default {
    name: 'part_lists',
    components: {},
    created() {
      var t = this;
      t.updateTitle('智能导诊')
      if (!t.$store.state.store_guidance.showIconIndex) {
        t.$store.state.store_guidance.showIconIndex = t.queryData.FBWXH
      }
      t.$store.state.store_guidance.showDrawer = false
      t.drawerWidth = document.body.clientWidth - 100
      t.getLargePart()
      t.getBodyParts(t.$store.state.store_guidance.showIconIndex)
    },
    data() {
      return {
        drawerWidth: '',
        largePartList: '',
        bodyPartsList: '',
        timer: '',
        cacheData: ''
      }
    },
    methods: {
      toSuggestionSection(c) {
        var t = this
        t.linkToPage('/suggestion_section', {
          WTMS: c.WTMS,
          ORG_ID: t.queryData.ORG_ID,
          ORG_NAME: t.queryData.ORG_NAME
        })
      },
      autoScroll() {
        var t = this
        clearInterval(t.timer)
        t.$nextTick(function() {
          var _a = document.querySelector('.part_lists .part_lists_menus .animated.on')
          var _b = document.querySelector('.part_lists .part_lists_menus .icon_lists')
          if (_a) {
            var offsetTop = _a.offsetTop
            if (offsetTop > 300) {
              if (_b.scrollTop < offsetTop - 300) {
                t.timer = setInterval(function() {
                  _b.scrollTop++
                  if (_b.scrollTop == offsetTop - 300) {
                    clearInterval(t.timer)
                  }
                }, 1)
              } else {
                t.timer = setInterval(function() {
                  _b.scrollTop--
                  if (_b.scrollTop == offsetTop - 300) {
                    clearInterval(t.timer)
                  }
                }, 1)
              }
            } else {
              t.timer = setInterval(function() {
                _b.scrollTop--
                if (_b.scrollTop == 0) {
                  clearInterval(t.timer)
                }
              }, 1)
            }
          }
        })
      },
      showbodyPartsLists(l) {
        var t = this
        t.$store.state.store_guidance.showDrawer = false
        t.$store.state.store_guidance.showIconIndex = l.FBWXH
        t.getBodyParts(t.$store.state.store_guidance.showIconIndex)
        t.autoScroll()
      },
      getLargePart() {
        var t = this
        t.ajax({
          method: 'post',
          serverId: 'YY000044',
          url: t.serverPath1,
          success(data) {
            t.largePartList = data
          }
        })
      },
      getBodyParts(id) {
        var t = this
        t.ajax({
          method: 'post',
          serverId: 'YY000045',
          url: t.serverPath1,
          params: {
            FBWXH: id,
            XBHET: t.queryData.XBHET
          },
          success(data) {
            t.bodyPartsList = ''
            t.$nextTick(function() {
              t.bodyPartsList = data
            })
          }
        })
      },
      getProblem(c) {
        var t = this
        t.cacheData = ''
        t.ajax({
          method: 'post',
          serverId: 'YY000046',
          url: t.serverPath1,
          params: {
            BWXH: c.BWXH,
            BWMC: c.BWMC
          },
          success(data) {
            t.cacheData = data
            t.$store.state.store_guidance.showDrawer = true
          }
        })
      }
    },
    mounted() {
      var t = this
      setTimeout(function() {
        t.autoScroll()
      }, 1000)
    }
  }
</script>
<style scoped>
  .part_lists .part_lists,
  .part_lists .drawer_box {
    height: 100%;
  }

  .part_lists .drawer_box {
    overflow-y: auto;
  }

  .part_lists .weui-cell {
    padding: 18px;
    font-size: 16px;
  }

  .part_lists .part_lists_menus {
    display: flex;
    height: 100%;
    color: #666;
  }

  .part_lists .part_lists_menus .icon_lists,
  .part_lists .part_lists_menus .cont_lists {
    height: 100%;
    overflow-y: auto;
    font-size: 15px;
  }

  .part_lists .part_lists_menus .icon_lists {
    width: 100px;
    text-align: center;
  }

  .part_lists .part_lists_menus .icon_lists li {
    height: 100px;
    padding-top: 10px;
    padding-bottom: 10px;
    line-height: 20px;
    background-color: #f1f1f1;
    border-right: 1px solid #ccc;
    border-top: 1px solid #ccc;
  }

  .part_lists .part_lists_menus .icon_lists li:first-child {
    border-top: 0;
  }

  .part_lists .part_lists_menus .icon_lists li.on {
    background-color: #fff;
    border-right: 1px solid #fff;
  }

  .part_lists .part_lists_menus .icon_lists .icon {
    width: 50px;
    height: 50px;
  }

  .part_lists .part_lists_menus .cont_lists {
    flex: 1;
    background-color: #fff;
  }

  .part_lists .part_lists_menus .weui-cell {
    padding: 18px;
    font-size: 16px;
  }

  .part_lists .part_lists_menus .weui-cell_access.vux-cell-box:after {
    display: none;
  }
</style>