<template>
  <div class="mbgl_jktj g-wrap g-hp100 s-bgcfff">
    <div v-if="detailData">
      <div class="cell-item">
        <div class="tit">健康状况</div>
        <div class="cont" v-if="TJBGPG">{{TJBGPG.EXAM_OVERVIEW}}</div>
        <div class="cont" v-else>{{detailData.ABNOMAL_DESC || '--'}}</div>
      </div>
    </div>
    <van-empty v-else description="暂无慢病评估" />
  </div>
</template>
<script>
  export default {
    name: 'mbgl_jktj',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('健康状况')
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
        TJBGPG: []
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
            console.log(123, data)
            t.detailData = data[0].CONTENTS[0]
            t.TJBGPG = t.detailData.TJBGPG[0]
            console.log(t.detailData)
          }
        });
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .mbgl_jktj .notice-wrap {
    background: #00c792;
    color: #fff;
    padding: 15px;
    font-size: 14px;
  }

  .mbgl_jktj>>>.van-collapse-item__content {
    padding: 0;
    color: #606266;
  }

  .mbgl_jktj>>>.van-cell {
    padding: 15px;
    border-bottom: 1px solid #ebeef5;
    font-size: 15px;
  }

  .mbgl_jktj>>>.van-cell__title {
    color: #606266;
    font-size: 15px;
  }

  .mbgl_jktj>>>.van-cell__value {
    color: #303133;
    font-size: 15px;
  }

  .mbgl_jktj>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .mbgl_jktj .cell-item {
    font-size: 14px;
    padding: 0 15px 15px;
    font-size: 15px;
  }

  .mbgl_jktj .cell-item .tit {
    padding: 15px 0;
    font-weight: bold;
    color: #606266;
  }

  .mbgl_jktj .cell-item .cont {
    line-height: 24px;
  }
</style>