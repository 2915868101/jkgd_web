<template>
  <div class="yyjs_ks g-hp100 s-bgcfff">
    <div class="f-df g-hp100 oh" v-if="dataLists.length">
      <div class="c_01 g-hp100">
        <div class="m-menus-box">
          <div :class="['menu f-df',{'on':activeIndex==i}]" v-for="(c,i) in dataLists" :key="i" @click="activeIndex=i">
            <div class="f-df1 f-dfv f-tal">
              <span>
                <!-- <img :src="c.icon" class="icon" /> -->
                <span class="f-fs15">{{c.KSMC}}</span>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="f-df1 g-hp100 s-bgcf6f6f6">
        <div class="m-content-box s-c606266 f-lh26">
          {{dataLists[activeIndex].KSJS|replaceQuotes}}
        </div>
      </div>
    </div>
    <van-empty v-else description="暂无科室介绍" />
  </div>
</template>
<script>
export default {
  name: 'yyjs_ks',
  components: {},
  filters: {
    replaceQuotes(val) {
      return val?val.replace(/^(\s|")+|(\s|")+$/g, ''):'暂无介绍'
    }
  },
  created() {
    var t = this
    t.updateTitle('科室介绍')
    t.searchFrom = t.queryData
    t.getKsList()
  },
  computed: {},
  data() {
    return {
      activeIndex: 0,
      dataLists: []
    }
  },
  methods: {
    getKsList() {
      var t = this
      t.ajax({
        serverId: 'BMFW00026',
        url: t.serverPath1,
        params: t.searchFrom,
        success: function(data) {
          console.log(data)
          t.dataLists = data[0].CONTENTS || []
        }
      })
    }
  },
  mounted() {}
}
</script>
<style>
.yyjs_ks p {
  text-indent: 2em;
  margin-top: 8px;
  line-height: 1.5;
}
</style>
<style scoped>
.yyjs_ks .m-menus-box,
.yyjs_ks .m-content-box {
  height: 100%;
  max-height: 100%;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  font-size: 14px;
}

.yyjs_ks .m-menus-box {
  width: 120px;
}

.yyjs_ks .m-menus-box .menu {
  height: 50px;
  border-bottom: 1px solid #ebebeb;
  border-right: 1px solid #ebebeb;
  padding-left: 12px;
  padding-right: 12px;
  font-size: 15px;
  color: #606266;
}

.yyjs_ks .m-menus-box .menu.on {
  font-weight: bold;
  color: #303133;
  border-right: 1px solid #f6f6f6;
}

.yyjs_ks .m-menus-box .icon {
  width: 16px;
  height: 16px;
  position: relative;
  top: 2px;
}

.yyjs_ks .m-content-box {
  padding: 12px 15px;
}
</style>