<template>
  <div class="people_detail g-wrap g-hp100">
    <div class="wrap-box f-bs3">
      <van-cell-group>
        <van-cell title="姓名" :value="patientInfo.XM" />
        <van-cell title="身份证号" :value="regNumber(patientInfo.CNUM,4)" />
        <van-cell title="手机号码" :value="regNumber(patientInfo.SJHM)" />
        <van-cell title="地址" :value="patientInfo.HKDZ||'-'" />
        <van-cell title="设为默认">
          <van-switch v-model="is_def" active-color="#00c792" size="18" @change="doChoose" />
        </van-cell>
        <div v-if="patientInfo.SFBR!='1'" class="btn-item" @click="doDelete">删除就诊人</div>
      </van-cell-group> 
    </div> 
  </div>
</template>
<script>
export default {
  name: 'people_detail',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('就诊人信息')
    t.searchForm.OPENID = t.wx_userInfo.openid
    t.searchForm.XM = t.queryData.XM
    t.searchForm.CNUM = t.queryData.CNUM
    t.searchForm.SJHM = t.queryData.SJHM
    t.searchForm.CARDTYPE = t.queryData.CARDTYPE
    t.is_def = t.queryData.BZ == 1 ? true : false
    t.patientInfo = t.queryData
  },
  data() {
    return {
      patientInfo: '',
      searchForm: {
        OPENID: '',
        FLAG: '',
        XM: '',
        CARDTYPE: '',
        CNUM: '',
        SJHM: ''
      },
      is_def: ''
    }
  },
  methods: {
    setDefFunc(flag) {
      var t = this
      t.searchForm.FLAG = flag
      console.log(t.searchForm)
      t.ajax({
        serverId: 'WXBM00015',
        url: t.serverPath1,
        params: t.searchForm,
        success: function(data) {
          console.log(data[0].CONTENTS)
        }
      })
    },
    doChoose() {
      this.setDefFunc('Choose')
    },
    doDelete() {
      var t = this
      t.$dialog.confirm({
        message: '确认删除就诊人？'
      }).then(() => {
        t.ajax({
          serverId: 'WXBM00014',
          url: t.serverPath1,
          params: {
            FLAG: 'Delete',
            OPENID: t.wx_userInfo.openid,
            CARDTYPE: '01',
            CNUM: t.queryData.CNUM
          },
          success: function(data) {
            if (data[0].CODE == '0') {
              t.linkToPage('people_lists')
            } else { 
              t.$toast('删除失败');
            }
          }
        }) 
      }).catch(() => {
        // on cancel
      })

    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}
  
.people_detail>>>.van-cell {
  padding: 15px;
  font-size: 15px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 14px;
}

.people_detail>>>.van-cell__title {
  color: #606266; 
}

.people_detail>>>.van-cell__value {
  color: #303133;
}

.people_detail>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}
.people_detail {
  padding: 20px;
}
.people_detail .wrap-box{
  border-radius: 5px;

}
.people_detail .wrap-box .btn-item{
  text-align: center;
  font-size: 14px;
  color: #fe0f0f;
  padding: 20px;
}

</style>