<template>
  <div class="jkda_ylda g-wrap g-hp100 s-bgcfff">
    <div class="tabs-wrap">
      <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="activeIndex" @click="onClickTabs">
        <van-tab :title="c.name" v-for="(c,i) in  dateList" :key="i">
          <div class="records-list s-bgcfff">
            <div class="list-item" v-for="(c1,i1) in recordsList" :key="i1" @click="doDetail(c1)">
              <div class="item-date">
                {{c1.TREAT_DATE}}
                <i class="node-icon"></i>
              </div>
              <div class="item-cont">
                <i class="iconfont icon-ylda s-c00c792 f-fs14"></i>
                <i class="b-border"></i>
                <div class="desc">
                  <p class="f-fs15 f-mb5 s-c00c792">{{c1.CHECKNAME}}</p>
                  <p class="orgname ">
                    <span>{{c1.ORG_NAME}}</span>
                    <i class="iconfont icon-arrow-right f-fs11 s-cc0c4cc"></i>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <van-empty v-show="!recordsList.length" description="该时期暂无记录" />
        </van-tab>
      </van-tabs>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jkda_ylda',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('健康档案')
      t.searchForm = t.queryData
      console.log(t.searchForm)
      /*  t.searchForm.MPI = '96903451409454246'
       t.searchForm.MPI = '96903451407015773'
       t.searchForm.MPI = '342829196310020925' */
      t.activeIndex = t.$store.state.store_jkda.ylda_tab_index
      t.getRecordsList()
    },
    data() {
      return {
        activeIndex: '',
        dateList: [{
          name: '1-3岁',
          children: []
        }, {
          name: '4-12岁',
          children: []
        }, {
          name: '13-17岁',
          children: []
        }, {
          name: '18-30岁',
          children: []
        }, {
          name: '31-59岁',
          children: []
        }, {
          name: '60岁以上',
          children: []
        }],
        recordsList: [],
        archivesData: [],
      }
    },
    methods: {
      onClickTabs(index) {
        var t = this
        t.activeIndex = index
        t.$store.state.store_jkda.ylda_tab_index = index
        t.recordsList = t.dateList[index].children
        console.log(6666, t.recordsList)
      },
      // 获取所有的事件
      getRecordsList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00036',
          url: t.serverPath2,
          params: {
            MPI: t.searchForm.MPI,
            NAME: t.searchForm.name,
            TOKEN: t.searchForm.token
          },
          success: function(data) {
            console.log(789978, data)
            t.archivesData = data

            console.log(12, t.archivesData)
            for (let c of t.archivesData) {
              var age = c.TREAT_DATE ? t.getCurrAge(c.TREAT_DATE) : ''
              if (age <= 3) {
                c.age = '婴儿期 ' + age
                t.dateList[0].children.push(c)
                t.activeIndex = t.activeIndex || 0
              } else if (4 < age && age <= 12) {
                c.age = '儿童期 ' + age
                t.dateList[1].children.push(c)
                t.activeIndex = t.activeIndex || 1
              } else if (13 < age && age <= 17) {
                c.age = '少年期 ' + age
                t.dateList[2].children.push(c)
                t.activeIndex = t.activeIndex || 2
              } else if (18 < age && age <= 30) {
                c.age = '青年期 ' + age
                t.dateList[3].children.push(c)
                t.activeIndex = t.activeIndex || 3
              } else if (31 < age && age <= 59) {
                c.age = '中年期 ' + age
                t.dateList[4].children.push(c)
                t.activeIndex = t.activeIndex || 4
              } else {
                c.age = '老年期 ' + age
                t.dateList[5].children.push(c)
                t.activeIndex = t.activeIndex || 5
              }
            }
            t.recordsList = t.dateList[t.activeIndex].children
            t.recordsList.sort(function(a, b) {
              return Date.parse(b.TREAT_DATE) - Date.parse(a.TREAT_DATE); //按时间排序
            });
            console.log(789798, t.dateList)
          }
        });
      },
      getCurrAge(date) {
        //获取年龄 
        var t = this
        var idnum = t.queryData.MPI
        var month = t.$moment(date).format('MM')
        var day = t.$moment(date).format('DD')
        var age = t.$moment(date).format('YYYY') - idnum.substring(6, 10) - 1
        if (idnum.substring(10, 12) < month || idnum.substring(10, 12) == month && idnum.substring(12, 14) <= day) {
          age++
        }
        return age
      },
      // 查看详情
      doDetail(data) {
        var t = this
        console.log(666789, data)
        t.ajax({
          serverId: 'WXBM00037',
          url: t.serverPath2,
          params: {
            EXTBID: data.EXTBID,
            TYPE: data.TYPE,
            ACTIVITYID: data.ACTIVITYID,
            TOKEN: t.searchForm.token,
            EXTMPI: data.EXTMPI
          },
          success: function(val) {
            console.log(1212121, val)
          }
        });
        console.log(data.age)
        if (data.TYPE == 'EMR_EXAM_IMAGE_REP') { //检验报告详情
          t.linkToPage('bgcx_jc', {
            id: data.TREATMENT_ID,
            age: data.age
          })
        } else if (data.TYPE == 'EMR_INSPECTION') { //检查报告详情
          t.linkToPage('bgcx_jy', {
            id: data.TREATMENT_ID,
            age: data.age
          })
        } else if (data.TYPE.indexOf('PHS_CHILD_PHYSICAL_EXAM') != -1) { //儿童体检
          t.linkToPage('PHS_CHILD_PHYSICAL_EXAM', {
            MPI: t.queryData.MPI,
            TREATMENT_ID: data.TREATMENT_ID,
            TYPE: data.TYPE,
            TREAT_TABLENAME: data.TREAT_TABLENAME,
            age: data.age,
            pageTitle: data.CHECKNAME
          })
        } else if (data.TYPE == 'PHS_FVBP') { // 孕产妇保健手册和初检记录
          t.linkToPage('PHS_MATERNAL_MANUAL', {
            MPI: t.queryData.MPI,
            TREATMENT_ID: data.TREATMENT_ID,
            TYPE: data.TYPE,
            TREAT_TABLENAME: data.TREAT_TABLENAME,
            age: data.age,
            pageTitle: data.CHECKNAME
          })
        } else {
          t.linkToPage(data.TYPE, {
            EXTBID: data.EXTBID || '',
            TYPE: data.TYPE,
            ACTIVITYID: data.ACTIVITYID,
            TOKEN: t.searchForm.token,
            EXTMPI: data.EXTMPI || '',
            pageTitle: data.CHECKNAME,
            /*  data: data */
          })
        }
        console.log(12, data.TYPE)
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jkda_ylda>>>.van-tabs--line .van-tabs__wrap {
    height: 60px;
    padding-bottom: 5px;
    border-bottom: 10px solid #f8f8f8;
    vertical-align:
  }

  .jkda_ylda>>>.van-tab__text {
    font-size: 15px;
  }

  .jkda_ylda>>>..van-tab__pane {
    padding-top: 10px;
  }

  .jkda_ylda>>>[class*=van-hairline]::after {
    border: 0;
  }

  .jkda_ylda>>>.van-tabs__line {
    background: #00c792;
  }

  .jkda_ylda .records-list {
    font-size: 14px;
    color: #606266;
    padding: 15px 18px;
  }

  .jkda_ylda .records-list .list-item {
    position: relative;
    min-height: 65px;
  }

  .jkda_ylda .records-list .list-item .item-date {
    width: 85px;
    height: 100%;
    line-height: 20px;
    position: absolute;
    left: 0;
  }

  .jkda_ylda .records-list .list-item:not(:last-child) .item-date {
    background: url(../../assets/img/jkda/border-dotted.png) repeat-y right;
  }

  .jkda_ylda .records-list .list-item .node-icon {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #909399;
    border-radius: 50%;
    position: absolute;
    top: 4px;
    right: -2PX;
  }

  .jkda_ylda .records-list .list-item .item-cont {
    position: absolute;
    height: 100%;
    left: 100px;
    right: 0;
  }

  .jkda_ylda .records-list .list-item .item-cont .icon-ylda {
    position: relative;
    top: 3px;
  }

  .jkda_ylda .records-list .list-item .b-border {
    border-left: 1px solid #c0c4cc;
    width: 4px;
    height: 10px;
    display: inline-block;
    margin: 0 8px 0 10px;
    vertical-align: -2px;
  }

  .jkda_ylda .records-list .list-item .desc {
    position: absolute;
    left: 35px;
    right: 0;
    top: 0;
    line-height: 20px;
    height: 100%;
  }

  .jkda_ylda .records-list .list-item .orgname {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .jkda_ylda .records-list .list-item .orgname span {
    display: inline-block;
    font-size: 14px;
    color: #909399;
    width: 190px;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
  }
</style>