<template>
  <div class="jtys_qy_org g-wrap g-hp100 s-bgcfff">
    <div v-if="orgList.length" class="org-list s-bgcfff">
      <div class="list-item" v-for="(c,i) in orgList" :key="i" @click="toTeam(c)">
        <div class="name">{{c.ORG_NAME}} <i class="iconfont icon-arrow-right f-fs12"></i></div>
        <div class="addr"><i class="iconfont icon-position f-fs14 f-mr8"></i>地址{{c.ORG_ADDR}}</div>
      </div>
    </div>
    <van-empty v-else description="暂无可选机构" />
  </div>
</template>
<script>
  export default {
    name: 'jtys_qy_org',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle(t.queryData.isFWJG ? '签约机构' : '选择机构')
      t.pathParams = t.queryData
      if (t.queryData.isFWJG) {
        t.orgList.push(t.queryData)
      } else {
        t.getOrgList()
      }
    },
    data() {
      return {
        orgList: [],
        pathParams: '',
      }
    },
    methods: {
      getOrgList() {
        var t = this
        t.ajax({
          serverId: 'BMFW00021',
          url: t.serverPath1,
          params: {
            STREET: t.queryData.AREA
          },
          success: function(data) {
            t.orgList = data[0].CONTENTS || []
            console.log(t.orgList)
          }
        })
      },
      toTeam(c) {
        var t = this
        console.log(7897987997898, c)
        t.pathParams.SIGN_ORG_ID = c.ORG_ID
        t.pathParams.SIGN_ORG = c.ORG_NAME
        t.pathParams.SIGN_ADDRESS = c.ORG_ADDR
        t.pathParams.SIGN_PHONE = c.ORG_PHONE
        t.pathParams.SIGN_DOCTOR = c.SIGN_DOCTOR
        t.pathParams.YSTEL = c.YSTEL
        t.linkToPage('jtys_qy_team', t.pathParams)
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}


  .jtys_qy_org .org-list {
    padding: 20px;
  }

  .jtys_qy_org .org-list .list-item {
    background: #f7faf9;
    font-size: 14px;
    color: #606266;
    border-radius: 4px;
    margin-bottom: 20px;
    padding: 20px;
  }

  .jtys_qy_org .org-list .list-item .name {
    font-size: 15px;
    font-weight: bold;
    color: #303133;
    margin-bottom: 15px;
  }

  .jtys_qy_org .iconfont {
    vertical-align: 1px;
  }
</style>