<template>
  <div class="index_search g-wrap f-hp100 s-bgcfff">
    <div class="serach-wrap s-bgcfff">
      <!-- <van-search v-model="searchForm.YSANDKS" placeholder="搜索医生、科室" @search="onSearch" />  -->
      <van-search v-model="searchForm.YSANDKS" show-action placeholder="搜索医生、科室" @search="onSearch" @cancel="onCancel" />
    </div>
    <div class="f-pl20 f-pr20 s-bgcfff">
      <!-- 医生列表 -->
      <div class="doctor-list">
        <van-row class="list-item" type="flex" justify="space-between" :gutter="20" v-for="(c,i) in ysList" :key="i" @click="toDoctorHome(c)">
          <van-col :span="4">
            <div class="item-icon" v-if="!c.YSTX">
              <i class="iconfont icon-doctor"></i>
            </div>
            <img class="item-icon" v-else :src="c.YSTX"/>
          </van-col>
          <van-col :span="20">
            <div class="item-cont">
              <div>
                <p class="f-mr10 f-df f-dfj">
                  <span class="name">{{c.YS_KS}}</span>
                  <i class="iconfont icon-arrow-right s-cc0c4cc f-fs12"></i>
                </p>
                <p class=""><span v-if="c.DEPNAME" class="f-mr10">{{c.DEPNAME||'-'}}</span> <span class="f-mr10">{{c.ORG_NAME||'-'}}</span></p>
              </div>
            </div>
          </van-col>
        </van-row>
      </div>
      <!-- 科室列表 -->
      <div class="doctor-list">
        <van-row class="list-item" type="flex" justify="space-between" :gutter="20" v-for="(c,i) in ksList" :key="i" @click="toKsHome(c)">
          <van-col :span="4">
            <div class="item-icon">
              <i class="iconfont icon-hospital f-fs12"></i>
            </div>
          </van-col>
          <van-col :span="20">
            <div class="item-cont">
              <div>
                <p class="f-mr10 f-df f-dfj">
                  <span class="name">{{c.YS_KS}}</span>
                  <i class="iconfont icon-arrow-right s-cc0c4cc f-fs12"></i>
                </p>
                <p class="f-mr10">{{c.ORG_NAME}}</p>
              </div>
            </div>
          </van-col>
        </van-row>
        <!-- <div class="item-list">

          <div class="item" v-for="item in ksList" :key="item.DEPT_ID" @click="toKsHome(item)">
            <div>
              <span class="f-mr10">{{item.ORG_NAME}}</span>
              <span>{{item.YS_KS}}</span>
            </div>
            <i class="iconfont icon-arrow-right"></i>
          </div>
        </div> -->
      </div>
      <van-empty v-show="noData" description="没有搜索到相关信息" />
    </div>
  </div>
</template>
<script>
export default {
  name: 'index_search',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('搜索')
  },
  data() {
    return {
      searchForm: {
        YSANDKS: '',
      },
      ysList: [],
      ksList: [],
      noData: false,
      pathParams: {}
    }
  },
  methods: {
    onSearch() {
      var t = this
      t.ysList = []
      t.ksList = [] 
      if (t.searchForm.YSANDKS) {
        t.ajax({
          serverId: 'BMFW00027',
          url: t.serverPath1,
          params: t.searchForm,
          success: function(data) { 
            if (data[0].CODE == '0') {
              if (data[0].CONTENTS.length) {
                t.noData = false
                for (var i = 0; i < data[0].CONTENTS.length; i++) {
                  var row = data[0].CONTENTS[i]
                  if (row.FLAG == '0') {
                    t.ksList.push(row)
                  } else {
                    t.ysList.push(row)
                  }
                }
                console.log(t.ysList)
                console.log(data)
              } else {
                t.noData = true
              }
            } else {
              t.noData = true
            }
          }
        })
      } else {
        t.$toast('未填写搜索信息');
      }
    },
    onCancel() {
      var t = this
      t.ksList = []
      t.ysList = []
      t.noData = true
    },
    toDoctorHome(data) {
      var t = this
      console.log(data.YSID)
      t.pathParams.YSID = data.YSID
      t.pathParams.KSID = data.GHKS
      t.pathParams.GHKS = data.DEPNAME
      t.pathParams.GHYS = data.YS_KS
      t.pathParams.ORG_ID = data.ORG_ID
      t.pathParams.ORG_NAME = data.ORG_NAME
      t.pathParams.YSTX = data.YSTX
      t.linkToPage('yygh_ys_home', t.pathParams)
    },
    toKsHome(data) {
      var t = this
      console.log(data.KSID)
      t.pathParams.KSID = data.KSID
      t.pathParams.GHKS = data.YS_KS
      t.pathParams.ORG_ID = data.ORG_ID
      t.pathParams.ORG_NAME = data.ORG_NAME
      t.linkToPage('/yygh_ks_home', t.pathParams)
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.index_search .serach-wrap {
  padding: 5px;
  font-size: 15px;
}

.index_search .doctor-list .list-item {
  padding: 20px 0;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
  line-height: 50px;
  text-align: center;
}

.index_search .doctor-list .item-icon {
  background: #f2f6fc;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
} 
.index_search .doctor-list .item-icon .iconfont {
  color: #d7dee8;
  font-size: 32px;
}

.index_search .doctor-list .item-cont {
  line-height: 25px;
  font-size: 14px;
  text-align: left;
}

.index_search .doctor-list .item-cont .name {
  font-weight: bold;
  font-size: 15px;
}

.index_search .doctor-list .item-cont .tips span {
  display: inline-block;
}


.index_search .department-list .item-list .item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 17px 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.index_search .department-list .item-list .item .iconfont {
  color: #909399;
  font-size: 11px;
}

.index_search>>>.van-empty {
  font-size: 15px;
  text-align: center;
}
</style>