<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="就诊人">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="就诊科室">
        {{detailData.DEP_NAME || '--'}}
      </van-cell>
      <van-cell center title="接诊医生">
        {{detailData.DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell center title="就诊时间">
        {{detailData.TREAT_DATE || '--'}}
      </van-cell>
      <van-cell center title="就诊医院">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
    </van-cell-group>
    <van-cell-group class="f-mt15">
      <van-cell center title="医生诊断">
        {{detailData.DISEASE || '--'}}
      </van-cell>
      <div class="cell-item">
        <div class="tit">检查报告：</div>
        <div class="cont" v-if="jcList">
          <template v-for="(c,i) in jcList">
            <van-button v-if="c.GROUP_NAME" class="f-mr8 f-mt8" size="small" round :key="i" @click="toReport(c)">{{c.GROUP_NAME}}</van-button>
          </template>
          <div v-show="!jcList.length">--</div>
        </div>
      </div>
      <div class="cell-item">
        <div class="tit">检验报告：</div>
        <div class="cont" v-if="jyList">
          <template v-for="(c,i) in jyList">
            <van-button v-if="c.GROUP_NAME" class="f-mr8 f-mt8" size="small" round :key="i" @click="toReport(c)">{{c.GROUP_NAME}}</van-button>
          </template>
          <div v-show="!jyList.length">--</div>
        </div>
      </div>
    </van-cell-group>
    <van-collapse class="s-bgcfff f-mt15" v-model="activeNames" accordion>
      <van-collapse-item title="处方明细" name="1">
        <div class="flex-item" v-for="(c,i) in detailData.CF" :key="i">
          <div class="f-df14">{{c.DRUG_NAME}}</div>
          <div class="f-tfr f-df14">
            <span v-if="c.QUANTITY">{{Number(c.QUANTITY).toFixed(0)}}{{c.UNIT}}</span>
            <span v-else>--</span>
          </div>
          <div class="f-tfr f-df5">{{c.QUANTITY || '--'}}</div>
        </div>
        <div v-show="!detailData.CF" class="f-fs14 f-pt15 f-pb15 f-pl15 f-pr15">暂无处方</div>
      </van-collapse-item>
    </van-collapse>
  </div>
</template>
<script>
  export default {
    name: 'jkda_detail',
    components: {},
    computed: {},
    created() {
      var t = this
      // 门诊记录
      t.updateTitle(t.queryData.pageTitle)
      t.searchForm = t.queryData
      console.log(33333, t.searchForm)
      t.getDetailData()
    },
    data() {
      return {
        detailData: [],
        jcList: [],
        jyList: [],
        activeNames: '1'
      }
    },
    methods: {
      getDetailData() {
        var t = this
        console.log(7777, t.searchForm)
        t.ajax({
          serverId: 'WXBM00037',
          url: t.serverPath2,
          params: t.searchForm,
          success: function(data) {
            console.log(66, t.searchForm)
            t.detailData = data[0]
            console.log(789, data)
            var JYJC = t.detailData.JYJC
            if (JYJC) {
              for (let c of JYJC) {
                console.log(c)
                if (c.TYPE == 'JY') {
                  t.jyList.push(c)
                } else {
                  t.jcList.push(c)
                }
              }
            }
          }
        });
      },
      toReport(data) {
        console.log(132, data)
        var t = this
        if (data.TYPE == 'JC') {
          t.linkToPage('bgcx_jc', {
            id: data.ACTIVITYID,
            type: data.DIRECTORYCODE,
            token: t.searchForm.TOKEN,
            name: t.detailData.ORG_NAME
          })
        } else {
          t.linkToPage('bgcx_jy', {
            id: data.ACTIVITYID,
            type: data.DIRECTORYCODE,
            token: t.searchForm.TOKEN,
            name: t.detailData.ORG_NAME
          })
        }
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @import '../../../assets/css/jkda/jkda_detail.css';

  @media screen and (max-width: 320px) {}
</style>