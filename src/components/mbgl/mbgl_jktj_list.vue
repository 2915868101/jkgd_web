<template>
  <div class="mbgl_jktj_list g-wrap">
    <!-- <van-cell title="健康状况" is-link @click="linkToPage('',{token: queryData.token})"></van-cell> -->
    <van-collapse class="s-bgcfff" v-model="activeNames" accordion v-if="detailData.TJJL">
      <van-collapse-item title="体检记录" name="1">
        <div class="flex-item" v-for="(c,i) in detailData.TJJL" :key="i" @click="toReport(c)">
          <div class="f-df8">{{c.TREAT_DATE || '--'}}</div>
          <div class="f-df14">{{c.ORG_NAME || '--'}}</div>
          <div class="f-df2 f-tfr iconfont icon-arrow-right f-fs12 s-c606266"></div>
        </div>
        <div v-show="!detailData.TJJL.length" class="f-fs14 f-pt15 f-pb15 f-pl15 f-pr15">暂无记录</div>
      </van-collapse-item>
    </van-collapse>
    <van-collapse class="s-bgcfff f-mt15" v-model="activeNames" accordion v-if="detailData.TJBG">
      <van-collapse-item title="报告记录" name="2">
        <div class="flex-item" v-for="(c,i) in detailData.TJBG" :key="i" @click="toReport(c)">
          <div class="f-df8">{{c.TREAT_DATE || '--'}}</div>
          <div class="f-df14">{{c.ORG_NAME || '--'}}</div>
          <div class="f-df2 f-tfr iconfont icon-arrow-right f-fs12 s-c606266"></div>
        </div>
        <div v-show="!detailData.TJBG.length" class="f-fs14 f-pt15 f-pb15 f-pl15 f-pr15">暂无记录</div>
      </van-collapse-item>
    </van-collapse>
  </div>
</template>
<script>
  export default {
    name: 'mbgl_jktj_list',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('健康体检')
      // 判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(sucFun) {
        t.searchForm.CNUM = sucFun.CONTENTS[0].CARD_NUMBER
        t.searchForm.NAME = sucFun.CONTENTS[0].NAME
        t.getDetailData()
      })
    },
    data() {
      return {
        searchForm: {
          CNUM: '',
          NAME: ''
        },
        detailData: [],
        activeNames: '1'
      }
    },
    methods: {
      getDetailData() {
        var t = this
        t.ajax({
          serverId: 'WXBM00040',
          url: t.serverPath2,
          params: {
            CNUM: t.searchForm.CNUM,
            NAME: t.searchForm.NAME,
            TOKEN: t.queryData.token
          },
          success: function(data) {
            console.log(132, data)
            t.detailData = data[0]
            console.log(t.detailData)
          }
        });
      },
      toReport(data) {
        var t = this
        t.linkToPage(data.TYPE, {
          EXTBID: data.EXTBID || '',
          TYPE: data.TYPE,
          ACTIVITYID: data.ACTIVITYID,
          TOKEN: t.queryData.token,
          EXTMPI: data.EXTMPI || '',
          pageTitle: data.CHECKNAME,
        })
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .mbgl_jktj_list .flex-item {
    font-size: 15px;
    display: flex;
    padding: 15px;
    border-bottom: 1PX solid #ebeef5;
  }

  .mbgl_jktj_list .flex-item .f-tfr {
    text-align: right;
  }

  .mbgl_jktj_list>>>.van-collapse-item__content {
    padding: 0;
    color: #606266;
  }

  .mbgl_jktj_list>>>.van-cell {
    padding: 15px;
    border-bottom: 1px solid #ebeef5;
    font-size: 15px;
  }

  .mbgl_jktj_list>>>.van-cell__title {
    color: #606266;
    font-size: 15px;
  }

  .mbgl_jktj_list>>>.van-cell__value {
    color: #303133;
    font-size: 15px;
  }

  .mbgl_jktj_list>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }
</style>