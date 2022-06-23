<template>
  <div class="yygh_ks g-wrap">
    <div class="nav-wrap f-mb10 f-fs15">
      <span>不知道挂什么科?</span>
      <van-button round color="#00c792" @click="linkToPage('guidance',queryData||queryData1)">点我咨询 <i class="iconfont icon-arrow-right f-fs12"></i></van-button>
    </div>
    <!-- <div class="s-bgcfff f-pl20 f-pr20 f-pt15 f-pb15 ">
      <div class="search-wrap " @click="linkToPage('yygh_search',queryData)">
        <van-icon name="search" />
        <span>搜索医生、科室</span>
      </div>
    </div> -->
    <div class="tree-wrap s-bgcfff">
      <van-tree-select :style="{height:'100%'}" :items="items" :main-active-index.sync="activeIndex" @click-nav="onClickNav">
        <template #content v-if="itemList">
          <div class="item-list" v-if="itemList">
            <div v-for="(c,i) in itemList" :key="i">
              <!-- 三级科室 -->
              <div class="item-box" :ref="`itemBtn${i}`" v-if="c.MEDS" @click.stop="doShow(i)">
                <div class="b-tit">
                  <span>{{c.NAME}}</span>
                  <span class="icon"><i class="iconfont icon-arrow-right"></i></span>
                </div>
                <div class="b-cont">
                  <div class="item" v-for="(c1,i1) in c.MEDS" :key="i1" @click="onClickItem(c1)">
                    <span>{{c1.NAME}}</span>
                    <i class="iconfont icon-arrow-right"></i>
                  </div>
                </div>
              </div>
              <div class="item" v-else @click="onClickItem(c)">
                <span>{{c.NAME}}</span>
                <i class="iconfont icon-arrow-right"></i>
                <div v-for="(c1,i1) in c.MEDS" :key="i1">{{c1.NAME}}</div>
              </div>
            </div>
          </div>
          <van-empty v-if="itemList&&!itemList.length" description="无科室分类" />
          <van-empty v-if="!itemList" description="正在查询中" />
        </template>
      </van-tree-select>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'yygh_ks',
    components: {},
    computed: {},
    created() {
      var t = this
      t.pathParams = t.queryData || {}
      t.updateTitle(t.queryData.ORG_NAME || '医院' + '-选择科室')
      // 粤事通调转过来挂号

      var Request = undefined
      Request = t.GetRequest()
      t.$store.state.store_yygh.hz_hospitalcode = Request["hospitalCode"] || ''
      t.$store.state.store_yygh.hz_code = Request["code"] || ''
      console.log(t.$store.state.store_yygh.hz_code)
      sessionStorage.setItem("hz_hospitalcode", t.$store.state.store_yygh.hz_hospitalcode)
      sessionStorage.setItem("hz_code", t.$store.state.store_yygh.hz_code)

      if (sessionStorage.getItem("hz_hospitalinfo")) {
        var _data = JSON.parse(sessionStorage.getItem("hz_hospitalinfo")) || []
        t.pathParams.ORG_ID = _data.HOSPITALNO || ''
        t.pathParams.ORG_NAME = _data.MSG || ''
        t.queryData1.ORG_ID = _data.HOSPITALNO || ''
        t.queryData1.ORG_NAME = _data.MSG || ''
      }
      // 保留已选择的科室位置
      t.activeIndex = t.$store.state.store_yygh.ks_tree_index
      if (t.$store.state.store_yygh.ks_tree_data) {
        t.items = t.$store.state.store_yygh.ks_tree_data
        t.itemList = t.items[t.activeIndex].children
      } else {
        t.getDepartment()
      }
    },
    data() {
      return {
        activeIndex: 0,
        items: '',
        itemList: '',
        pathParams: {},
        showIndex: '',
        queryData1: {}
      }
    },
    methods: {
      // 粤事通小程序跳转的code获取用户的信息
      getCode(code, ORG_ID) {
        var t = this
        console.log({
          type: 0,
          CODE: code || '',
          ORG_ID: ORG_ID || '',
        })
        t.ajax({
          method: 'post',
          serverId: 'WXBM00013',
          loading: false,
          params: {
            type: 0,
            CODE: code || '',
            ORG_ID: ORG_ID || '',
          },
          url: t.serverPath1,
          success(data) {
            console.log(data)
            var _data = data[0].CONTENTS || []
            if (_data.length) {
              sessionStorage.setItem("hz_userinfo", JSON.stringify(_data[0]))
            } else {
              // t.$toast(data[0].MSG)
              t.$dialog.alert({
                confirmButtonText: '好的',
                message: data[0].MSG + '请重新选择'
              }).then(function() {
                t.closeWindow()
              })
            }
          }
        })
      },
      doShow(i) {
        var t = this
        var _class = JSON.stringify((t.$refs[`itemBtn${i}`][0].classList))
        if (_class.indexOf('on') == -1) {
          t.$refs[`itemBtn${i}`][0].classList.add('on')
        } else {
          t.$refs[`itemBtn${i}`][0].classList.remove('on')
        }
      },
      getDepartment() {
        var t = this
        //if (t.serverType != 'cz' || (t.serverType == 'cz' && !localStorage.ks_lists)) {科室list缓存 如果换医院就需要处理 池州暂时只有一家医院

        var KSRQ = '',
          JSRQ = '';
        // if (t.serverType == 'cz') {
        KSRQ = t.$moment().add(0, 'days').format('YYYY-MM-DD')
        JSRQ = t.$moment().add(6, 'days').format('YYYY-MM-DD')
        // }
        t.ajax({
          serverId: 'WXBM00002',
          url: t.serverPath4 || t.serverPath1,
          params: {
            ORG_ID: t.queryData.ORG_ID || '',
            KSRQ: KSRQ || '',
            JSRQ: JSRQ || '',
            ORG_YQ: t.queryData.ORG_YQ || '',
            HOSPITALCODE: t.$store.state.store_yygh.hz_hospitalcode || '',
          },
          success: function(data) {
            console.log({
              ORG_ID: t.queryData.ORG_ID || '',
              KSRQ: KSRQ || '',
              JSRQ: JSRQ || '',
              ORG_YQ: t.queryData.ORG_YQ || '',
              HOSPITALCODE: t.$store.state.store_yygh.hz_hospitalcode || '',
            })
            console.log(666, data)
            t.items = []
            t.itemList = []
            if (data[0].CONTENTS.length) {
              for (let item of data[0].CONTENTS) {
                t.items.push({
                  text: item.NAME,
                  id: item.ID,
                  children: item.MEDS
                })
              }
              t.itemList = t.items[0].children
              t.$store.state.store_yygh.ks_tree_data = t.items
              localStorage.ks_lists = JSON.stringify(t.items)
            } else {
              t.$dialog.alert({
                confirmButtonText: '好的',
                message: '网络异常，请稍后再试。'
              }).then(function() {
                // t.getDepartment()
              })
            }
            if (t.$store.state.store_yygh.hz_hospitalcode) {
              var _data = data[0] || []
              sessionStorage.setItem("hz_hospitalinfo", JSON.stringify(_data))
              t.pathParams.ORG_ID = _data.HOSPITALNO || ''
              t.pathParams.ORG_NAME = _data.MSG || ''
              t.queryData1.ORG_ID = _data.HOSPITALNO || ''
              t.queryData1.ORG_NAME = _data.MSG || ''

              if (t.$store.state.store_yygh.hz_code) {
                t.getCode(t.$store.state.store_yygh.hz_code, t.pathParams.ORG_ID)
              }
            }
          }
        })
        // } else {
        //   t.items = JSON.parse(localStorage.ks_lists)
        //   t.itemList = t.items[0].children
        // }
      },
      onClickNav(index) {
        var t = this
        console.log(index)
        t.activeIndex = index
        t.$store.state.store_yygh.ks_tree_index = index
        t.itemList = t.items[index].children
      },
      onClickItem(data) {
        var t = this
        t.pathParams.KSID = data.DEPT_ID
        t.pathParams.GHKS = data.NAME
        if (t.queryData.ORG_YQ || t.serverType == 'hz') {
          t.linkToPage('yygh_ks_home1', t.pathParams)
        } else {
          t.linkToPage('yygh_ks_home', t.pathParams)
        }
      }
    },
    mounted() {},
    beforeDestroy() {
      // this.$store.state.store_yygh.ks_tree_data=''
    }
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .yygh_ks {
    position: relative;
  }

  .yygh_ks .search-wrap {
    padding: 10px 15px;
    border-radius: 4px;
    font-size: 15px;
    background: #f4f4f4;
    color: #969799;
  }

  .yygh_ks .search-wrap>>>.van-icon {
    vertical-align: -2px;
    margin-right: 10px;
  }

  .yygh_ks .tree-wrap {
    position: absolute;
    border-top: 1px solid #ebeef5;
    top: 70px;
    bottom: 0;
    left: 0;
    right: 0;
  }

  .yygh_ks .nav-wrap {
    background: #fff;
    padding: 15px;
    color: #909399;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .yygh_ks .nav-wrap>>>.van-button {
    height: 30px;
    padding: 0 10px;
  }

  .yygh_ks>>>.van-tree-select__nav {
    background: #f9f9f9;
  }

  .yygh_ks>>>.van-sidebar-item {
    border-bottom: 1PX solid #ebeef5;
    background: #f9f9f9;
    font-size: 15px;
    padding: 15px;
  }

  .yygh_ks>>>.van-sidebar-item--select::before {
    background: transparent;
  }

  .yygh_ks>>>.van-sidebar-item--select,
  .yygh_ks>>>.van-sidebar-item--select:active {
    background: #fff;
  }

  .yygh_ks .item-list .item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 17px 15px;
    font-size: 15px;
    border-bottom: 1PX solid #fff;
  }

  .yygh_ks .item-list .item .iconfont {
    color: #909399;
    font-size: 12px;
  }

  /*二级科室展开*/
  .yygh_ks .item-list .item-box {
    padding: 0 15px;
  }

  .yygh_ks .item-list .item-box .b-tit {
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 17px 15px;
    font-size: 15px;
    border-bottom: 1PX solid #fff;
    padding: 17px 0;
  }

  .yygh_ks .item-list .item-box .b-tit .icon {
    border-radius: 50%;
    -moz-transition: all .5s ease;
    -webkit-transition: all .5s ease;
    -o-transition: all .5s ease;
    transition: all .5s ease;
    transform: rotate(90deg);
    border: 1px solid #c0c4cc;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
  }

  .yygh_ks .item-list .item-box .b-tit .iconfont {
    transform: scale(0.8);
    margin: 0 auto;
  }

  .yygh_ks .item-list .item-box .b-cont {
    height: 0;
    overflow: hidden;
    -moz-transition: height .5s ease;
    -webkit-transition: height .5s ease;
    -o-transition: height .5s ease;
    transition: height .5s ease;
  }

  .yygh_ks .item-list .item-box .iconfont {
    font-size: 12px;
    color: #909399;
  }

  .yygh_ks .item-list .item-box .item {
    padding-left: 10px;
    padding-right: 0;
  }

  .yygh_ks .item-list .item-box.on .b-tit .icon {
    color: #00c792;
    border-color: #00c792;
    transform: rotate(-90deg);
  }

  .yygh_ks .item-list .item-box.on .b-tit .iconfont {
    color: #00c792;
  }

  .yygh_ks .item-list .item-box.on .b-cont {
    height: auto;
  }

  /*.yygh_ks .item-list .item-box.on .b-cont {
  color: #00c792;
}*/

  .yygh_ks_home>>>.van-empty {
    font-size: 15px;
    text-align: center;
  }
</style>