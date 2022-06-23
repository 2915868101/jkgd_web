<template>
  <div class="g-wrap">
    <!-- 顶部导航 -->
    <div v-show="cc=='1'">
      <!-- 答题历史列表 -->
      <div class="g-main" v-cloak>
        <ul class="m-lists" v-for="(t,i) in listData" :key="i">
          <li v-for="(d,j) in t.LIST" :key="j">
            <dl>
              <dt>{{t.YEAR+'-'+d.DAY}}</dt>
              <dd class="arrow" v-for="(m,k) in d.REPORT" :key="k" @click="change(m)">
                <div class=" name">{{m.NAME}}
                </div>
                <div class="time">{{(m.ID).substring(8,10)+':'+(m.ID).substring(10,12)}}</div>
              </dd>
            </dl>
          </li>
        </ul>
      </div>
    </div>
    <div v-show="cc=='2'">
      <!-- 历史答题分析 -->
      <ul class="m-img-lists f-cb" v-if="resultsData">
        <li>
          <div v-if="!resultsData[0].INFO">
            <img class="previewer-demo-img" v-for="(t,i) in resultsData" :key="i" :src="t" @click="showImg(i)">
            <van-image-preview v-model="show" ref="imagePreview" :images="resultsData" @change="onChange" closeable>
              <template v-slot:index>{{ index+1 }} / {{ resultsData.length}}</template>
            </van-image-preview>
          </div>
          <div v-else class="text">{{resultsData[0].INFO}}</div>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'zwb_jkzc_history',
    components: {},
    created() {
      var t = this
      t.updateTitle('问卷历史')
      t.getHistoryImp()
    },
    computed: {},
    watch: {
      cacheFlag(val) {
        var t = this
        if (val == 1) {
          t.updateTitle('问卷历史')
        } else {
          t.updateTitle('问卷测试结果')
        }
      }
    },
    data() {
      return {
        cc: 1,
        show: false,
        index: 0,
        listData: '',
        resultsData: '',
        options: {
          getThumbBoundsFn(index) {
            let thumbnail = document.querySelectorAll('.previewer-demo-img')[index]
            let pageYScroll = window.pageYOffset || document.documentElement.scrollTop
            let rect = thumbnail.getBoundingClientRect()
            return {
              x: rect.left,
              y: rect.top + pageYScroll,
              w: rect.width
            }
          }
        }
      }
    },
    methods: {
      change(m) {
        var t = this;
        t.cc = 2;
        history.pushState({
          page: 'showImg'
        }, null, '')
        window.onpopstate = function() {
          if (t.$store.state.cacheFlag == 2) {
            t.$store.state.cacheFlag = 1
          }
        }
        t.ajax({
          method: 'post',
          serverId: 'WXTJ00031',
          params: {
            openid: t.wx_userInfo.openid,
            columnid: m.COLUMNID,
            id: m.ID,
            cnum: t.queryData.cnum,
          },
          url: t.serverPath1,
          success(data) {
            t.resultsData = []
            for (var i = 0; i < data.length; i++) {
              t.resultsData.push(
                'data:image/jpeg;base64,' + data[i]['IMAGE' + i]
              )
            }
            console.log(89, data)
          }
        })
      },
      showImg(i) {
        var t = this
        t.show = true;
        t.index = i
        t.$refs.imagePreview.swipeTo({
          index: t.index
        })
      },
      onChange(i) {
        var t = this;
        t.index = i
      },
      getHistoryImp: function() {
        var t = this;
        t.ajax({
          method: 'post',
          serverId: 'WXTJ00030',
          params: {
            openid: t.wx_userInfo.openid,
            cnum: t.queryData.cnum,
            COLUMNID: t.queryData.columnid
          },
          url: t.serverPath1,
          success(data) {
            t.listData = data;
            console.log(t.listData)
          }
        })
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  .g-wrap {
    overflow-y: auto;
  }

  img {
    max-width: 100%;
  }

  .animated {
    animation-duration: .6s;
  }

  .vux-header {
    background-color: #26a2ff;
    z-index: 2;
  }

  .g-main {
    height: 100%;
    position: relative;
    z-index: 1;
  }

  .g-main:before {
    content: '';
    display: block;
    position: fixed;
    height: 100%;
    width: 100px;
    margin-top: 6px;
    border-right: 1px solid #F0F0F0;
    z-index: 3;
  }

  .arrow {
    background-image: url(../../assets/img/gd/arrow.svg), none;
    background-repeat: no-repeat;
    background-position: right center;
    background-size: 20px 15px;
    margin-left: 114px;
    margin-top: 20px;
  }

  .m-lists dl {
    background-color: #fff;
  }

  .m-lists dt {
    height: 30px;
    line-height: 30px;
    background-color: #f0f0f0;
    color: #666;
    font-size: 15px;
    padding-left: 15px;
    padding-right: 15px;
  }

  .m-lists dd {
    margin-left: 100px;
    padding: 0 15px 15px 15px;
    border-bottom: 1px solid #dedede;
  }

  .m-lists dd:last-child {
    border-bottom: 0;
  }

  .m-lists dd .name {
    position: relative;
    font-size: 16px;
    color: #333;
  }

  .m-lists dd .name:before {
    content: '';
    display: block;
    width: 12px;
    height: 12px;
    border: 1px solid #999;
    background-color: #fff;
    border-radius: 50%;
    position: absolute;
    left: -22px;
    top: 50%;
    z-index: 5;
    margin-top: -7px;
  }

  .m-lists dd .time {
    margin-top: 10px;
    font-size: 14px;
    color: #999;
  }

  .m-img-lists img {
    display: block;
    width: 100%;
  }
</style>