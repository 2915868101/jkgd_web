<template>
  <div class="jkbk_lists">
    <div class="header">
      <div class="m-top">本知识库仅供参考!</div>
      <div class="m-search">
        <div :class="['input f-pr',{'f-tac':!is_search_focus&&!searchValue}]">
          <span class="icon_search"></span>{{searchValue?'':'搜索'+pageName+'名称'}}
          <input type="text" v-model="searchValue" @focus.stop="is_search_focus=true" @blur="is_search_focus=false" @change="getJbkLists(searchValue)" @keyup.enter="getJbkLists(searchValue)" />
        </div>
      </div>
    </div>
    <div class="m-list-box">
      <van-index-bar sticky :index-list="index_lists">
        <div v-for="i in index_lists" :key="i">
          <template v-if="jbk_lists[i]">
            <van-index-anchor :index="i"></van-index-anchor>
            <van-cell v-for="(l,c) in jbk_lists[i]" :key="c" @click="showJbDetials(l)">{{l.NAME}}</van-cell>
          </template>
        </div>
      </van-index-bar>
    </div>
  </div>
</template>
<script>
export default {
  name: 'jkbk_lists',
  directives: {},
  components: {
  },
  created() {
    var t = this
    t.MLID = t.$route.params.id
    if (t.MLID == '1') {
      t.pageName = '疾病'
    } else if (t.MLID == '2') {
      t.pageName = '药物'
    } else if (t.MLID == '3') {
      t.pageName = '化验单'
    } else if (t.MLID == '5') {
      t.pageName = '手术'
    }
    t.updateTitle(t.pageName + '库')
    t.$store.state.showFooter = false
    t.$parent.$parent.activeTabIndex = 0
    t.getLists(t.MLID)
  },
  computed: {},
  data() {
    return {
      pageName: '',
      MLID: '',
      searchValue: '',
      is_search_focus: false,
      index_lists: [],
      jbk_lists: {},
      new_jbk_lists: {},
      removeIndex: []
    }
  },
  watch: {},
  methods: {
    //获取疾病或药品库列表
    getLists(MLID) {
      var t = this
      t.ajax({
        method: 'post',
        serverId: 'WXPL00009',
        params: {
          MLID: MLID
        },
        url: t.serverPath2,
        success(data) {
          t.jbk_lists = JSON.parse(JSON.stringify(data[0]).replace(/\?/g, ""))
          console.log(data)
          t.index_lists=[]
          t.index_lists= Object.keys(t.jbk_lists).sort()  
        }
      })
    },
    //搜索框模糊查询疾病或药品信息
    getJbkLists(v) {
      var t = this
      t.ajax({
        method: 'post',
        serverId: 'WXPL00010',
        params: {
          MLID: t.MLID,
          NAME: v
        },
        url: t.serverPath2,  
        success(data) {
          t.jbk_lists = JSON.parse(JSON.stringify(data[0]).replace(/\?/g, ""))
          t.index_lists=[]
          t.index_lists= Object.keys(t.jbk_lists).sort() 
        }
      })
    },
    showJbDetials(l) {
      var t = this
      var id = ''
      if (t.MLID == '1') {
        id = l.JBID
      } else if (t.MLID == '2') {
        id = l.YPID
      } else if (t.MLID == '3') {
        id = l.HYDID
      } else if (t.MLID == '5') {
        id = l.SSKID
      }
      t.linkToPage('/jkbk_details/' + t.MLID, { id: id, name: l.NAME })
    }
  },
  mounted() {}
}
</script>
<style scoped>
@import '../../assets/css/jkbk/jkbk_lists.css';

.jkbk_lists {
  font-size: 14px;
}

.jkbk_lists>>>.van-index-bar__index {
  line-height: 16px;
}

.jkbk_lists>>>.van-cell:not(:last-child)::after {
  border: 0;
}

.jkbk_lists>>>.van-cell {
  padding: 10px 15px;
  font-size: 14px;
  border-bottom: 1px solid #ebeef5;
}

.jkbk_lists>>>.van-cell__title {
  color: #606266;
}

.yygh_qryy>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}
</style>