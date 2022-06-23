<template>
  <div class="jtys_jtcy_fwjl g-wrap g-hp100">
    <div class="info-wrap  ">
      <div class="f-df">
        <img class="g-w50 g-h50 f-brp50 f-mb10 f-ml30 f-mr15 f-ml40" :src="$store.state.def_headimg">
        <div class="cont g-h50">
          <p class="f-mb10">
            <span class="f-fwb f-fs15">{{queryData.XM}}</span>
            <!-- <span v-if="peopleInfo.XB=='1'" class="icons iconbg1 f-mr10"><i class="iconfont icon-male f-fs14"></i></span> -->
            <span class="icons iconbg2 f-mr10"><i class="iconfont icon-female f-fs14"></i></span>
          </p>
          <p>
            <span class="f-mr2">身份证号：</span>
            <span>{{regNumber(queryData.ZJHM,4)}}</span>
          </p>
        </div>
      </div>
    </div>
    <div class="relative-wrap">
      <div class="menu-wrap f-df f-dfc">
        <div class="item" @click="linkToPage('jtys_qy_org',{isFWJG:true,ORG_NAME:queryData.SIGN_ORG,ORG_ADDR:queryData.SIGN_ORG,ORG_PHONE:queryData.URGENT_CONTACT_PHONE,ORG_ID:queryData.ORG_ID,SIGN_DOCTOR:queryData.SIGN_DOCTOR,YSTEL:queryData.YSTEL})">
          <img class="g-w22 g-h22 f-mb10" src="../../assets/img/jtys/icon-fwjg.png">
          <p>服务机构</p>
        </div>
        <b class="b-border"></b>
        <div class="item" @click="linkToPage('jtys_fwnr')">
          <img class="g-w22 g-h22 f-mb10" src="../../assets/img/jtys/icon-fwnr.png">
          <p>服务详情</p>
        </div>
      </div>
      <div class="fwjl-list f-mb20" v-if="recordsList">
        <template v-for="(c,i) in recordsList">
          <div class="list-item" v-if="c.TYPE=='PHS_HCV'||c.TYPE=='PHS_DCV'||c.TYPE=='PHS_CHD_VISIT'||c.TYPE=='PHS_TB_VISIT'||c.TYPE=='PHS_STROKE_VISIT'||c.TYPE=='PHS_TUMOR_VISIT'||c.TYPE=='PHS_AGED_VISIT'||c.TYPE=='PHS_MENTAL_VISIT'" :key="i" @click="doDetail(c)">
            <p class="date s-c909399 f-mb8 f-ml15">{{c.TREAT_DATE}}</p>
            <div class="cont">
              <p class="f-df f-dfj f-mb10">
                <span class="s-c00c792 f-fs15 f-fwb">{{c.CHECKNAME||'--'}}</span>
                <i class="iconfont icon-arrow-right f-fs11 s-cc0c4cc"></i>
              </p>
              <p class="s-c909399">{{c.ORG_NAME||'--'}}</p>
            </div>
          </div>
        </template>
      </div>
      <van-empty v-if="isShowListLength" description="该时期暂无记录" />
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jtys_jtcy_fwjl',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('服务记录')
      console.log(t.queryData)
      // t.searchForm.MPI = '342829194705192220'
      t.searchForm.MPI = t.queryData.ZJHM
      t.gettoken()

    },
    data() {
      return {
        recordsList: '',
        searchForm: {
          MPI: '',
          token: ''
        },
        isShowListLength: false
      }
    },
    methods: {
      gettoken() {
        var t = this
        t.ajax({
          serverId: 'WXBMGDJKDA01',
          url: t.serverPath2,
          params: {
            CNUM: t.queryData.ZJHM,
            NAME: t.queryData.XM
          },
          success: function(data) {
            t.searchForm.token = data[0].result
            localStorage.token = JSON.stringify(data[0].result);
            t.token = JSON.parse(localStorage.token)
            t.getRecordsList()

          }
        })
      },
      isShowList() {
        var t = this
        t.$nextTick(function() {
          var len = document.querySelectorAll('.jtys_jtcy_fwjl .fwjl-list .list-item').length
          console.log(len)
          t.isShowListLength = Boolean(len <= 0)
        })
      },
      // 获取所有的事件
      getRecordsList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00036',
          url: t.serverPath2,
          params: {
            MPI: t.searchForm.MPI,
            NAME: t.queryData.XM,
            TOKEN: t.searchForm.token
          },
          success: function(data) {
            t.recordsList = data
            console.log(t.recordsList)
            setTimeout(function() {
              t.isShowList()
            }, 100)
          }
        });
      },
      doDetail(data) {
        var t = this
        console.log(789, data)
        console.log(789, {
          ACTIVITYID: data.ACTIVITYID,
          EXTBID: data.EXTBID,
          EXTMPI: data.EXTMPI,
          TOKEN: t.searchForm.token,
          TYPE: data.TYPE,
        })
        t.linkToPage(data.TYPE, {
          ACTIVITYID: data.ACTIVITYID,
          EXTBID: data.EXTBID,
          EXTMPI: data.EXTMPI,
          TOKEN: t.searchForm.token,
          TYPE: data.TYPE,
          pageTitle: data.CHECKNAME
        })
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtys_jtcy_fwjl .info-wrap {
    width: 100%;
    height: 135px;
    background: url(../../assets/img/jtys/fwjl-bg.png) no-repeat;
    background-size: 100%;
    color: #fff;
    font-size: 14px;
  }

  .jtys_jtcy_fwjl .info-wrap .f-df {
    align-items: center;
    padding-top: 20px;
  }


  .jtys_jtcy_fwjl .info-wrap .icons {
    border-radius: 10px;
    height: 15px;
    padding: 1px 6px;
    margin-left: 10px;
  }

  .jtys_jtcy_fwjl .info-wrap .icons.iconbg1 {
    background: #7d95ff;
  }

  .jtys_jtcy_fwjl .info-wrap .icons.iconbg2 {
    background: #ffcd7d;
  }

  .jtys_jtcy_fwjl .fwjl-list {
    padding: 0 15px;
  }

  .jtys_jtcy_fwjl .fwjl-list .list-item {
    margin-top: 15px;
    font-size: 14px;
  }

  .jtys_jtcy_fwjl .fwjl-list .list-item .cont {
    border-radius: 4px;
    border: 1px solid #ebeef5;
    padding: 15px;
    background: #fff;
  }

  .jtys_jtcy_fwjl .relative-wrap {
    position: relative;
    top: -50px;
    font-size: 15px;
  }

  .jtys_jtcy_fwjl .menu-wrap {
    background: #fff;
    border-radius: 5px;
    text-align: center;
    padding: 20px;
    width: 90%;
    margin: 0 auto 30px;
    color: #606266;
    align-items: center;
    font-size: 14px;
  }

  .jtys_jtcy_fwjl .menu-wrap .item {
    width: 45%;
  }

  .jtys_jtcy_fwjl .menu-wrap .b-border {
    border: 1px solid #ebeef5;
    display: inline-block;
    height: 30px;
  }
</style>