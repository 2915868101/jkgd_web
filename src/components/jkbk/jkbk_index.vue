<template>
  <div class="jkbk_index s-bgcfff g-hp100">
    <div class="items">
      <div v-for="(l, i) in lists" :key="i" class="f-df item m-arrow" @click="linkToPage(links[i]+l.MLID)">
        <div class="f-dfv"><span :class="'icon icon'+l.MLID"></span></div>
        <span class="f-df1 f-dfv text s-c444 f-dfv f-pl10">{{l.NAME}}</span>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'jkbk_index',
  directives: {},
  components: {},
  created() {
    var t = this;
    t.updateTitle('健康百科');
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 0
    t.getJkbkLists()
  },
  data() {
    return {
      lists: '',
      links: ['/jkbk_lists/', '/jkbk_lists/', '/jkbk_lists/', '/jkbk_lists/', '/jkbk_lists/', '/jkbk_lists/']
    }
  },
  methods: {
    //获取健康百科列表 
    getJkbkLists() {
      var t = this
      t.ajax({
        serverId: 'WXPL00008',
        url: t.serverPath2,
        params: {},
        success: function(data) {
          t.lists = data 
          console.log(data)
        }
      })
    }, 
  },
  mounted() {}
}
</script>
<style scoped>
@import '../../assets/css/jkbk/jkbk_index.css';
</style>