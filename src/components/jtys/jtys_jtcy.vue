<template>
  <div class="jtys_jtcy g-wrap g-hp100 s-bgcfff">
    <div class="sign-list s-bgcfff">
      <div class="list-item">
        <div class="f-mb15 f-df f-dfj">
          <div class="info">
            <span class="name f-fwb f-fs15 f-mr10">{{queryData.XM}}</span>
            <i class="iconfont icon-phone"></i>
            <i class="iconfont icon-consult1"></i>
          </div>
          <div class="links" @click="linkToPage('jtys_jtcy_fwjl',queryData)">服务记录 <i class="iconfont icon-arrow-right f-fs11"></i></div>
        </div>
        <div>
          <span class="f-mr10">身份证号：</span>
          <span>{{regNumber(queryData.ZJHM,4)}}</span>
        </div>
        <div class="fwb-type">{{queryData.TYPE}}</div>
      </div>
      <div v-for="(c,i) in qylist" :key='i' class="list-item">
        <div class="f-mb15 f-df f-dfj">
          <div class="info">
            <span class="name f-fwb f-fs15 f-mr10">{{c.XM}}</span>
            <i class="iconfont icon-phone"></i>
            <i class="iconfont icon-consult1"></i>
          </div>
          <div class="links" @click="linkToPage('jtys_jtcy_fwjl',c)">服务记录 <i class="iconfont icon-arrow-right f-fs11"></i></div>
        </div>
        <div>
          <span class="f-mr10">身份证号：</span>
          <span>{{regNumber(c.ZJHM,4)}}</span>
        </div>
        <div class="fwb-type">{{c.TYPE}}</div>
      </div>
    </div>

  </div>
</template>
<script>
  export default {
    name: 'jtys_jtcy',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('签约成员')
      t.qycy()
      console.log(789, t.queryData)

    },
    data() {
      return {
        memberList: [],
        memberData: {},
        qylist: [],
      }
    },
    methods: {
      qycy() {
        var t = this
        console.log(111)
        t.ajax({
          // loading: false,
          serverId: 'DYWJTYSTZH07',
          url: t.serverPath4,
          params: {
            OPENID: t.wx_userInfo.openid,
            CARDNUM: t.queryData.ZJHM
          },
          success: function(res) {
            t.qylist = res
          }
        })

      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtys_jtcy .sign-list {
    padding: 0 20px;
  }

  .jtys_jtcy .sign-list .list-item {
    border-radius: 5px;
    padding: 20px;
    margin: 20px 0;
    color: #fff;
    font-size: 14px;
    background: linear-gradient(to right, #25d8a8, #00c792);
    position: relative;
  }

  .jtys_jtcy .sign-list .info .iconfont {
    font-size: 12px;
    border-radius: 17px;
    display: inline-block;
    width: 24px;
    height: 17px;
    line-height: 17px;
    text-align: center;
    margin-left: 5px;
    vertical-align: 2px;
  }

  .jtys_jtcy .sign-list .list-item .icon-phone {
    background: #e0f5fb;
    color: #62d5f6;
  }

  .jtys_jtcy .sign-list .list-item .icon-consult1 {
    background: #fbf4e0;
    color: #ffb763;
  }

  .jtys_jtcy .sign-list .list-item .fwb-type {
    position: absolute;
    right: 0;
    bottom: 0;
    background: #3fd6ae;
    color: #029f75;
    font-size: 14px;
    padding: 3px 5px;
    border-top-left-radius: 5px;
  }

  .jtys_jtcy .nosign-list {
    display: flex;
    flex-wrap: wrap;
    padding: 0 15px 15px;
  }

  .jtys_jtcy .nosign-list .list-item {
    width: 25%;
    text-align: center;
  }

  .jtys_jtcy .tips-box {
    font-size: 14px;
    padding: 15px 20px;
    color: #909399;
    line-height: 24px;
  }
</style>