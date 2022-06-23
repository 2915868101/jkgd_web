<template>
  <div class="mbgl_jkxj g-wrap g-hp100 s-bgcfff">
    <div class="infor-list s-bgcfff">
      <div class="list-item" v-for="(c,i) in jkxjList" :key="i">
        <a :href="c.link">
          <img class="item-img" :src="__static+c.img">
          <div class="item-cont">
            <div class="tit line1">{{c.tit1}}</div>
            <div class="txt line2">{{c.tit2}}</div>
          </div>
        </a>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'mbgl_jkxj',
  components: {},
  created() {
    var t = this
    t.updateTitle('健康宣教') 
    t.getJkxjList()
  },
  computed: {},
  data() {
    return {
      jkxjList:[]
    }
  },
  methods: {
    getJkxjList() {
      var t = this;
      if (!localStorage.jkxjList) {
        t.ajax({
          method: 'get',
          url: t.__static + '/data/mbgl-jkxj.json',
          success(data) { 
            t.jkxjList = data
            localStorage.jkxjList = JSON.stringify(t.jkxjList)
          },
        });
      } else {
        t.jkxjList = JSON.parse(localStorage.jkxjList);
      }
    },
  },
  mounted() {

  }
}
</script>
<style scoped>
.mbgl_jkxj .infor-list {
  padding: 0 20px;
}

.mbgl_jkxj .infor-list .list-item {
  padding: 15px 0;
  font-size: 14px;
  overflow: hidden;
  border-bottom: 1px solid #ebeef5;
}

.mbgl_jkxj .list-item:nth-last-child(1) {
  border: 0;
}

.mbgl_jkxj .infor-list .list-item .item-img {
  float: left;
  width: 88px;
  height: 70px;
  margin-right: 15px;
  border-radius: 3px;
}

.mbgl_jkxj .infor-list .list-item .item-cont {
  margin-left: 98px;
}

.mbgl_jkxj .infor-list .list-item .tit {
  font-weight: bold;
  font-size: 15px; 
}

.mbgl_jkxj .infor-list .list-item .txt {
  margin-top: 8px; 
  color: #909399;
  line-height: 24px;
}
</style>