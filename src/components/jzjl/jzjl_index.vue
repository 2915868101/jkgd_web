<template>
  <div class="jzjl_index g-wrap g-hp100">
    <div class="header">就诊记录列表</div>
    <div class="f-mt10 s-bgcfff">
      <div class="records-list">
        <div class="list-item" v-for="(c, i) in recordsList" :key="i" @click="doDetail(c)">
          <div class="item-date">
            {{ c.TREAT_DATE }}
            <i class="node-icon"></i>
          </div>
          <div class="item-cont">
            <i class="iconfont icon-ylda s-c00c792 f-fs14"></i>
            <i class="b-border"></i>
            <div class="desc">
              <p class="f-fs15 f-mb5 s-c00c792">{{ c.CHECKNAME }}</p>
              <p class="orgname ">
                <span>{{ c.ORG_NAME }}</span>
                <i class="iconfont icon-arrow-right f-fs11 s-cc0c4cc"></i>
              </p>
            </div>
          </div>
        </div>
      </div>
      <van-empty v-show="!recordsList.length" description="暂无记录" />
    </div>
  </div>
</template>
<script>
  export default {
    name: "jzjl_index",
    components: {},
    computed: {},
    created() {
      var t = this;
      t.updateTitle("就诊记录列表");
      // 判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(data) {
        console.log(666, data.CONTENTS[0])
        var CARD_NUMBER = data.CONTENTS[0].CARD_NUMBER;
        t.searchForm.MPI = data.CONTENTS[0].CARD_NUMBER;
        t.SJHM = data.CONTENTS[0].SJHM
        t.getPeopleMPI(CARD_NUMBER);
      });
    },
    data() {
      return {
        list: [],
        recordsList: [],
        searchForm: {
          MPI: "",
        },
        SJHM: ''
      };
    },
    methods: {
      // 获取MPI
      getPeopleMPI(CARD_NUMBER) {
        var t = this;
        t.ajax({
          serverId: "WXBM00013",
          url: t.serverPath2,
          params: {
            CNUM: CARD_NUMBER,
            openid: t.wx_userInfo.openid,
            SJHM: t.SJHM
          },
          success: function(data) {
            var peopleData = data[0].CONTENTS ? data[0].CONTENTS[0] : [];
            console.log(999, peopleData)
            if (t.searchForm.MPI) {
              t.getRecordsList();
            } else {
              t.recordList = [];
            }
          }
        });
      },
      // 获取所有的事件
      getRecordsList() {
        var t = this;
        t.ajax({
          serverId: "WXBMJZJL00036",
          url: t.serverPath2,
          params: t.searchForm,
          success: function(data) {
            t.recordsList = data[0].CONTENTS;
          }
        });
      },
      // 查看详情
      doDetail(data) {
        var t = this;
        var aa = data;
        t.ajax({
          serverId: "WXBMJZJL00037",
          url: t.serverPath2,
          params: {
            MPI: t.searchForm.MPI,
            TYPE: data.TYPE,
            TREAT_TABLENAME: data.TREAT_TABLENAME,
            TREATMENT_ID: data.TREATMENT_ID
          },
          success: function(data2) {
            t.list = data2[0].CONTENTS[0];
            if (data.TYPE == "EMR_ADMISSION_REGISTER") {
              t.linkToPage("EMR_ADMISSION_REGISTER", {
                //住院记录
                MPI: t.searchForm.MPI,
                TREATMENT_ID: aa.TREATMENT_ID,
                TYPE: aa.TYPE,
                TREAT_TABLENAME: aa.TREAT_TABLENAME,
                AGE: t.list.AGE,
                pageTitle: aa.CHECKNAME,
                FYMX: t.list.FYMX,
                list: t.list,
                ORG_NAME: aa.ORG_NAME
              });
            } else if (data.TYPE == "EMR_OUTPATIENT_REGISTER") {
              //门诊记录
              t.linkToPage("EMR_OUTPATIENT_REGISTER", {
                MPI: t.searchForm.MPI,
                TREATMENT_ID: aa.TREATMENT_ID,
                TYPE: aa.TYPE,
                TREAT_TABLENAME: aa.TREAT_TABLENAME,
                AGE: t.list.AGE,
                pageTitle: aa.CHECKNAME,
                FYMX: t.list.FYMX,
                list: t.list,
                ORG_NAME: aa.ORG_NAME
              });
            }
          }
        });
      }
    },
    mounted() {}
  };
</script>
<style scoped>
  .jzjl_index .header {
    font-size: 18px;
    background: #fff;
    text-align: center;
    font-weight: 600;
    padding: 15px 0 10px;
  }

  .jzjl_index .records-list {
    font-size: 14px;
    color: #606266;
    padding: 15px 18px;
  }

  .jzjl_index .records-list .list-item {
    position: relative;
    min-height: 65px;
  }

  .jzjl_index .records-list .list-item .item-date {
    width: 85px;
    height: 100%;
    line-height: 20px;
    position: absolute;
    left: 0;
  }

  .jzjl_index .records-list .list-item:not(:last-child) .item-date {
    background: url(../../assets/img/jkda/border-dotted.png) repeat-y right;
  }

  .jzjl_index .records-list .list-item .node-icon {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #909399;
    border-radius: 50%;
    position: absolute;
    top: 4px;
    right: -2px;
  }

  .jzjl_index .records-list .list-item .item-cont {
    position: absolute;
    height: 100%;
    left: 100px;
    right: 0;
  }

  .jzjl_index .records-list .list-item .item-cont .icon-ylda {
    position: relative;
    top: 3px;
  }

  .jzjl_index .records-list .list-item .b-border {
    border-left: 1px solid #c0c4cc;
    width: 4px;
    height: 10px;
    display: inline-block;
    margin: 0 8px 0 10px;
    vertical-align: -2px;
  }

  .jzjl_index .records-list .list-item .desc {
    position: absolute;
    left: 35px;
    right: 0;
    top: 0;
    line-height: 20px;
    height: 100%;
  }

  .jzjl_index .records-list .list-item .orgname {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .jzjl_index .records-list .list-item .orgname span {
    display: inline-block;
    font-size: 14px;
    color: #909399;
    width: 190px;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
  }

  @media screen and (max-width: 320px) {}
</style>