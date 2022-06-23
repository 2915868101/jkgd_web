<template>
  <div class="yyjs_ys g-wrap">
    <div :class="[{'gd-doctor-list':serverType=='gd'},'doctor-list f-df']" v-if="dataLists.length">
      <div class="list-item f-bs3" v-for="(c,i) in dataLists" :key="i" @click="linkToPage('yyjs_ys_grjs',c)">
        <div>
          <div class="item-icon" v-if="!c.YSTX">
            <i class="iconfont icon-doctor"></i>
          </div>
          <img class="item-icon" v-else :src="c.YSTX" />
        </div>
        <div class="gd-doctor-info">
          <div class="name">{{c.YSXM}}</div>
          <div class="f-mt8 f-mb8">
            <span class="f-mr10">{{c.KSMC}}</span>
            <span>{{c.YSJB}}</span>
            <span v-if="serverType=='gd'" class="f-pt8 line3">{{c.YSSC||'暂无介绍'}}</span>
          </div>
        </div>
      </div>
    </div>
    <van-empty v-else description="暂无名医介绍" />
  </div>
</template>
<script>
export default {
  name: 'yyjs_ys',
  components: {},
  created() {
    var t = this
    t.updateTitle('名医介绍')
    t.searchFrom = t.queryData
    t.getKsList()
  },
  computed: {},
  data() {
    return {
      dataLists: []
    }
  },
  methods: {
    getKsList() {
      var t = this
      t.ajax({
        serverId: 'BMFW00025',
        url: t.serverPath1,
        params: t.searchFrom,
        success: function(data) {
          t.dataLists = data[0].CONTENTS || []
          console.log(data)
        }
      })
    }
  },
  mounted() {}
}
</script>
<style>
.yyjs_ys .doctor-list {
  padding: 20px;
  justify-content: space-between;
  flex-wrap: wrap;
  font-size: 14px;
  color: #606266;
}

.yyjs_ys .doctor-list .list-item {
  width: 47%;
  background: #fff;
  text-align: center;
  margin-bottom: 20px;
  border-radius: 5px;
  padding: 15px;
}

.yyjs_ys .doctor-list .list-item .name {
  font-size: 15px;
  font-weight: bold;
  color: #303133;
}

.yyjs_ys .doctor-list .item-icon {
  background: #f2f6fc;
  margin: 0 auto 20px;
  width: 65px;
  height: 65px;
  line-height: 65px;
  text-align: center;
  border-radius: 50%;
}

.yyjs_ys .doctor-list .item-icon .iconfont {
  color: #d7dee8;
  font-size: 40px;
}

/*广德样式*/
.yyjs_ys .gd-doctor-list.doctor-list .list-item {
  width: 100%;
  text-align: left;
  display: flex;
}

.yyjs_ys .gd-doctor-list.doctor-list .item-icon {
  border-radius: 0;
  height: 90px;
  width: 70px;
  margin-bottom: 0;
}

.yyjs_ys .gd-doctor-list.doctor-list .gd-doctor-info {
  width: calc(100% - 75px);
  padding-left: 20px;
}
</style>