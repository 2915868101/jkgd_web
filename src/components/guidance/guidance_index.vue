<template>
  <div class="guidance_index g-wrap"> 
    <div class="mobile-page"> 
      <div class="admin-group animated fadeInUp">
        <img class="admin-img" src="../../assets/img/guidance/headimg.jpg" />
        <div class="admin-msg">
          <i class="triangle-admin"></i>
          <div class="admin-reply">身体不适，不知道看什么科？ <span class="s-c00c792">智能导诊</span> 快速定位到科室。</div>
        </div>
      </div> 
      <div class="admin-group animated fadeInUp" @click="showPicker1=true">
        <img class="admin-img" src="../../assets/img/guidance/headimg.jpg" />
        <div class="admin-msg">
          <i class="triangle-admin"></i>
          <div class="admin-reply">
            <div class="s-c909399 f-bb1">请先选择您要去的医院：</div>
            <div class="g-wp100 f-df f-dfc" v-if="!pathParams.ORG_NAME"><span class="f-tdu s-c00c792">选择医院</span></div>
            <div class="g-wp100 f-df f-dfj" v-else>
              <span>{{pathParams.ORG_NAME}}</span>
              <i class="iconfont icon-change s-c00c792 f-fs14"></i>
            </div>
          </div>
        </div>
      </div>
      <van-popup v-model="showPicker1" position="bottom">
        <div class="s-c909399 f-pt15 f-pl15 f-fs14 f-bb1">请先选择就诊医院</div>
        <van-picker toolbar-position="bottom" show-toolbar :columns="columns1" @cancel="showPicker1 = false" @confirm="onConfirm" /> 
      </van-popup>
      <!-- <div class="user-group" v-if="show2">
        <div class="user-msg">
          <pre class="user-reply s-cfff">{{ORG_NAME}}</pre>
          <i class="triangle-user"></i>
        </div>
        <img class="user-img" :src="wx_userInfo.headimgurl||$store.state.def_headimg" />
      </div> -->
      <div class="admin-group animated fadeInUp" v-if="show3">
        <img class="admin-img" src="../../assets/img/guidance/headimg.jpg" />
        <div class="admin-msg">
          <i class="triangle-admin"></i>
          <div class="admin-reply f-df img-item" @click="linkToPage('guidance', pathParams)">
            <img class="f-mr20 g-w40 g-h40" src="../../assets/img/guidance/iconimg.jpg" alt="">
            <span>人体智能导诊图</span>
          </div>
        </div>
      </div>
    </div>  
  </div>
</template>
<script>
export default {
  name: 'guidance_index',
  components: {
  },
  created() {
    var t = this
    t.updateTitle('智能分诊') 
    t.getHospitalList()
  },
  computed: {},
  data() {
    return { 
      hospitalList:'',
      columns1:[],
      showPicker1:false,
      show1:'',
      show2:'',
      show3:'', 
      pathParams:{
        ORG_ID:'',
        ORG_NAME:'',
        ORG_YQ:'',
      }
    }
  },
  methods: { 
    // 获取医院列表 
    getHospitalList() {
      var t = this
      if (localStorage.hospitalList) {
        t.hospitalList = JSON.parse(localStorage.hospitalList)
      } else {
        t.ajax({
          loading: false,
          serverId: 'BMFW00024',
          url: t.serverPath1,
          success: function(data) {
            t.hospitalList = data[0].CONTENTS || []
            console.log(t.hospitalList)
            localStorage.hospitalList = JSON.stringify(t.hospitalList)
          }
        })
      }
      t.columns1 = Object.values(t.hospitalList).map(function(row) {
        return row.ORG_NAME
      })
    },
    onConfirm(val,index) {
      var t = this;
      t.pathParams.ORG_NAME = val
      t.pathParams.ORG_ID = t.hospitalList[index].ORG_ID
      t.pathParams.ORG_YQ = t.hospitalList[index].ORG_YQ||''
      t.showPicker1 = false
      t.show3 = true
    }, 
  },
  mounted() {}
}
</script>
<style scoped>
.guidance_index .mobile-page {
  padding: 15px;
  margin-bottom: 2.5rem;
  font-size: 15px;
}

.guidance_index .mobile-page .admin-img,
.guidance_index .mobile-page .user-img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

.guidance_index .f-bb1{
  border-bottom: 1px solid #ebeef5;
  padding-bottom: 15px;
  margin-bottom: 15px;
}
.guidance_index i.triangle-admin,
.guidance_index i.triangle-user {
  width: 0;
  height: 0;
  position: absolute;
  top: 16px;
  display: inline-block;
  border-top: 5px solid transparent;
  border-bottom: 5px solid transparent;
}

.guidance_index .mobile-page i.triangle-admin {
  left: 10px;
  border-right: 5px solid #fff;
}
.guidance_index .mobile-page i.triangle-user {
  right: 10px;
  border-left: 5px solid #00c792;
}
.guidance_index .mobile-page .admin-group,
.guidance_index .mobile-page .user-group{
  padding: 6px 0;
  display: flex;
  display: -webkit-flex;
}

.guidance_index .mobile-page .admin-group {
  justify-content: flex-start;
  -webkit-justify-content: flex-start;
}

.guidance_index .mobile-page .user-group {
  justify-content: flex-end;
  -webkit-justify-content: flex-end;
}

.guidance_index .mobile-page .admin-reply,
.guidance_index .mobile-page .user-reply{
  display: inline-block;
  padding: 15px;
  border-radius: 5px;
  background-color: #fff;
  margin: 0 15px 12px;
  font-size: 14px;
  line-height: 20px;
  white-space: pre-wrap;
  width: 90%;
} 
.guidance_index .mobile-page .user-reply {
  text-align: left;
  background-color: #00c792; 
}
.guidance_index .mobile-page .admin-msg {
  width: 90%;
  position: relative;
}
.guidance_index .mobile-page .mobile-page .user-msg {
  text-align: right;
}
.guidance_index .img-item {
  display: block;
  font-weight: bold;
  align-items: center;
} 
.guidance_index .img-item img{
  vertical-align: middle;
}

.guidance_index>>>.van-picker__cancel {
  width:0;
  padding: 0;
}
.guidance_index>>>.van-picker__confirm {
  width: 100%;
  background: #00c792;
  color: #fff;
  padding: 0;
}

</style>