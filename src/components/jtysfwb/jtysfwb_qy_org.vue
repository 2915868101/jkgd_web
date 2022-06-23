<template>
  <div class="jtys_qy_org g-wrap g-hp100 ">
    <van-search v-model="value" shape="round" @input="getOrgList" ref="search" placeholder="搜索机构名称或地址" />
    <div v-if="orgList.length" class="org-list f-mt15 f-ml15 f-mr15">
      <div class="list-item" v-for="(c,i) in orgList" :key="i" @click="toTeam(c)">
        <van-row class="">
          <van-col span="22">
            <div class="f-fwb">{{c.ORG_NAME}}</div>
            <div><span class="s-c909399 f-pr8">{{c.ORG_LEADER}}</span><span class="s-c909399 ">{{c.ORG_PHONE}}</span></div>
            <div class="s-c909399 ">{{c.ORG_ADDR}}</div>
          </van-col>
          <van-col span="2" class="f-tac">
            <div style="color:#C8C9CC;line-height:66px"> <i class="iconfont icon-arrow-right f-fs11"></i></div>
          </van-col>
        </van-row>
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
      t.updateTitle('选择医疗机构')
      t.pathParams = t.queryData
      var address = t.pathParams.AREA.split('/')[1]
      if (t.queryData.isFWJG) {
        t.orgList.push(t.queryData)
      } else {
        t.getOrgList(address)
      }
    },
    data() {
      return {
        value: "",
        orgList: [],
        pathParams: '',
      }
    },
    methods: {
      getOrgList(val) {
        var t = this
        t.ajax({
          serverId: 'WXJT0003',
          url: t.serverPath4,
          params: {
            address: val
          },
          success: function(data) {
            console.log(data)
            t.orgList = data[0].CONTENTS || []
          }
        })
      },
      toTeam(c) {
        var t = this
        t.pathParams.XH = c.XH
        t.pathParams.ORG_ID = c.ORG_ID
        t.pathParams.ORG_NAME = c.ORG_NAME
        t.pathParams.ORG_LEADER = c.ORG_LEADER
        t.pathParams.ORG_ADDR = c.ORG_ADDR
        t.pathParams.ORG_PHONE = c.ORG_PHONE
        t.linkToPage('jtysfwb_index', t.pathParams)
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtys_qy_org .org-list .list-item {
    font-size: 14px;
    line-height: 22px;
    color: #323233;
    border-radius: 4px;
    padding: 8px 12px;
    margin: 1px;
    background: #fff;
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