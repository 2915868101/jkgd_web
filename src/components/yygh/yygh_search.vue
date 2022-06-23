<template>
  <div class="yygh_search g-wrap f-hp100 s-bgcfff">
    <div class="serach-wrap">
      <van-search v-model="searchForm.CONTENT" placeholder="搜索医生、科室" input-align="center" @search="onSearch" />
    </div>
    <div class="f-pl20 f-pr20">
      <!-- 医生列表 -->
      <div class="doctor-list">
        <van-row class="list-item" type="flex" justify="space-between" :gutter="20" v-for="item in ysList" :key="item.YSID"  @click="toDoctorHome(item)">
          <van-col :span="4">
            <div v-show="!item.YSTX" class="item-icon">
              <i class="iconfont icon-doctor"></i>
            </div>
            <img v-show="item.YSTX" class="item-icon" :src="item.YSTX" />
          </van-col>
          <van-col :span="14">
            <div class="item-cont">
              <div>
                <span class="name f-mr10">{{item.YSXM}}</span>
                <span class="post">{{item.ZJBZ==0 ? '普通医生' : '专家医生'}}</span>
              </div>
              <div class="tips">
                <!-- <span class="f-mr10 g-w60">余号:20</span> -->
                <span class="g-w60 s-cf30b32">￥{{item.GHF}}</span>
              </div>
            </div>
          </van-col>
          <van-col :span="6">
            <i class="iconfont icon-arrow-right"></i>
          </van-col>
        </van-row>
      </div>
      <!-- 科室列表 -->
      <div class="department-list">
        <div class="item-list">
          <div class="item" v-for="item in ksList" :key="item.DEPT_ID" @click="toKsHome(item)">
            <span>{{item.NAME}}</span>
            <i class="iconfont icon-arrow-right"></i>
          </div>
        </div>
      </div>
      <van-empty v-show="noData" description="没有搜索到相关信息" /> 
    </div>
  </div>
</template>
<script>
export default {
  name: 'yygh_search',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('搜索')
    t.pathParams = t.queryData
    t.searchForm.OPENID = t.wx_userInfo.openid
    // t.searchForm.ORG_ID = t.queryData.ORG_ID
  },
  data() {
    return {
      searchForm: {
        CONTENT: '',
        ORG_ID: '',
        OPENID: '',
      },
      ysList: [],
      ksList: [],
      noData:false,
      pathParams:{
        
      }
    }
  },
  methods: {
    onSearch() {
      var t = this
      t.ysList = []
      t.ksList = []
      if (t.searchForm.CONTENT) {
        t.ajax({
          serverId: 'WXBM00034',
          url: t.serverPath1,
          params: t.searchForm,
          success: function(data) {
            if (data[0].CONTENTS.length) {
              t.noData= false
              for (var i = 0; i < data[0].CONTENTS.length; i++) {
                var row = data[0].CONTENTS[i]
                if (row.SIGN == 'YS') {
                  t.ysList.push(row)
                } else {
                  t.ksList.push(row)
                }
              }
              console.log(t.ysList)
              console.log(data)
            } else{
              t.noData= true
            } 
          }
        })
      } else {
        t.$toast('未填写搜索信息');
      }
    },
    toDoctorHome(item){
      var t = this
      console.log(item.YSID)
      t.pathParams.YSID = item.YSID
      t.pathParams.GHKS = item.PBKS
      t.pathParams.GHYS = item.YSXM
      t.pathParams.GHF = item.GHF
      t.pathParams.YSTX = item.YSTX 
      t.linkToPage('yygh_ys_home', t.pathParams)
    },
    toKsHome(item){
      var t = this
      t.pathParams.KSID = item.DEPT_ID
      t.pathParams.GHKS = item.NAME
      t.linkToPage('yygh_ks_home',t.pathParams)
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.yygh_search .serach-wrap {
  padding: 5px;
  font-size: 15px;
}

.yygh_search .doctor-list .list-item {
  padding: 20px 0;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15px;
  line-height: 50px;
  text-align: center;
}

.yygh_search .doctor-list .item-icon {
  background: #f2f6fc;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
}

.yygh_search .doctor-list .item-icon .iconfont {
  color: #d7dee8;
  font-size: 32px;
}

.yygh_search .doctor-list .item-cont {
  line-height: 25px;
  font-size: 14px;
  text-align: left;
}

.yygh_search .doctor-list .item-cont .name {
  font-weight: bold;
  font-size: 15px;
}

.yygh_search .doctor-list .item-cont .tips span {
  display: inline-block;
}


.yygh_search .department-list .item-list .item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 17px 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
}

.yygh_search .department-list .item-list .item .iconfont {
  color: #909399;
  font-size: 12px;
}

.yygh_search>>>.van-empty {
  font-size: 15px;
  text-align: center;
}
</style>