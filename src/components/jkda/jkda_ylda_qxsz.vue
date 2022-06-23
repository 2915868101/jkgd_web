<template>
  <div class="jkda_ylda_qxsz g-wrap s-bgcfff g-hp100">
    <div class="s-c606266 f-fs15 f-fwb f-pt20 f-pl20 f-pr20 f-pb20">请选择本人健康档案访问权限</div>
    <van-radio-group v-model="searchForm.ROLEFLAG" >
      <van-radio :name="c.name" v-for="(c,i) in rollList" :key="i" @click="setAuthority">{{c.txt}}</van-radio> 
    </van-radio-group>
  </div>
</template>
<script>
export default {
  name: 'jkda_ylda_qxsz',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle('权限设置') 
    t.searchForm=t.queryData  
  },
  data() {
    return { 
      searchForm:{
        EXTCA:'',
        ROLEFLAG:"0"
      },
      rollList:[{name:'0',txt:'完全公开'},{name:'1',txt:'仅家庭成员'},{name:'2',txt:'仅签约医生'},{name:'3',txt:'完全保密'}]
    }
  },
  methods: {
    // 设置健康档案访问权限
    setAuthority() {
      var t = this
      console.log(t.searchForm)
      t.ajax({
        serverId: 'WXBMTZH02',
        url: t.serverPath2,
        loading:false,
        params: t.searchForm,
        success: function(data) { 
          console.log(data)
          var _data=data[0].CONTENTS?data[0].CONTENTS[0]:[]
          if(_data.CODE==='0'){
            t.$toast('健康档案访问权限修改成功')
          }else{
            t.$toast(_data.MSG)
          }
        }
      })
    },
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.jkda_ylda_qxsz{
  font-size: 15px;
} 
.jkda_ylda_qxsz>>>.van-radio-group{
  border-top: 1px solid #f2f4f8;
}
.jkda_ylda_qxsz>>>.van-radio{
  padding: 20px 20px 20px 35px;
  border-bottom: 1px solid #f2f4f8;
}
</style>