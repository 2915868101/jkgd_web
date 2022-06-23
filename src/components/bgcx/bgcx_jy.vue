<template>
  <div class="bgcx_jy g-wrap">
    <div class="notice-wrap" v-show="queryData.age">{{queryData.age}}</div>
    <van-cell-group class="f-bb10">
      <van-cell title="申请单号:">
        {{detailData.APPLY_NUM || '--'}}
      </van-cell>
      <van-cell title="检验医生:">
        {{detailData.EXAM_DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell title="送检标本:">
        {{detailData.SAMPLE_TYPE_NAME || '--'}}
      </van-cell>
    </van-cell-group>
    <div class="cell-item flex-wrap">
      <div class="f-df10 s-c303133 f-ml10">项目</div>
      <div class="f-df7 s-c303133">参考值</div>
      <div class="f-df7 s-c303133">结果</div>
    </div>
    <template v-if="detailData.RESULT">
      <div class="cell-item" v-for="(c,i) in detailData.RESULT" :key="i">
        <div class="flex-wrap" v-if="c.ITEM_NAME">
          <template v-if="!c.REFER_VALUE">
            <div class="f-df10">{{c.ITEM_NAME||'-'}}<span class="aa">({{c.UNIT}})</span></div>
            <div class="f-df7">
              {{c.REFER_VALUE_LOWER||'-'}}
              <span v-show="c.REFER_VALUE_UPPER">~ {{c.REFER_VALUE_UPPER}}</span>
            </div>
            <div class="f-df7">
              {{c.ITEM_RESULT?getNumber(c.ITEM_RESULT):'-'}}<!-- {{c.UNIT}} --> {{c.DRUG_ALLERGY_NAME}}
              <template v-show="getValFormat(c.ITEM_RESULT,c.REFER_VALUE_LOWER,c.REFER_VALUE_UPPER)">
                <i :class="['iconfont',getValFormat(c.ITEM_RESULT,c.REFER_VALUE_LOWER,c.REFER_VALUE_UPPER)=='low'?'s-c00c792':'s-cf30b32','icon-val-'+getValFormat(c.ITEM_RESULT,c.REFER_VALUE_LOWER,c.REFER_VALUE_UPPER)]"></i>
              </template>
            </div>
          </template>
          <template v-else>
            <div class="f-df10">{{c.ITEM_NAME||'-'}}</div>
            <div class="f-df7">
              {{c.REFER_VALUE||'-'}}<span class="aa">({{c.UNIT}})</span>
            </div>
            <div class="f-df7">
              {{c.ITEM_RESULT?getNumber(c.ITEM_RESULT):'-'}}<!-- {{c.UNIT}} -->
              <template v-show="getValFormat1(c.ITEM_RESULT,c.REFER_VALUE)">
                <i :class="['iconfont',getValFormat1(c.ITEM_RESULT,c.REFER_VALUE)=='low'?'s-c00c792':'s-cf30b32','icon-val-'+getValFormat1(c.ITEM_RESULT,c.REFER_VALUE)]"></i>
              </template>
            </div>
          </template>
        </div>
        <!-- 药敏 -->
        <div class="flex-wrap" v-else>
          <div class="f-df10">{{c.ANTIBIOTIC_NAME||'-'}}
          </div>
          <div class="f-df7">{{c.BACSTA_CONCEN||'-'}}</div>
          <div class="f-df7">{{c.DRUG_ALLERGY_NAME||'-'}}</div>
        </div>
      </div>
    </template>
    <div v-else class="cell-item f-pl10 s-c606266">暂无数据</div>
  </div>
</template>
<script>
  export default {
    name: 'bgcx_jy',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('检验报告详情')
      t.getData()
    },
    data() {
      return {
        detailData: ''
      }
    },
    methods: {
      getNumber(val) {
        let num = ''
        if (val.indexOf('lt') != -1) {
          num = '<' + val.slice(3, val.length)

        } else {
          num = val
        }
        return num
      },
      getData() {
        var t = this
        t.ajax({
          serverId: 'BMFW00018',
          url: t.serverPath2,
          params: {
            ACTIVITYID: t.queryData.id,
            DIRECTORYCODE: t.queryData.type,
            TOKEN: t.queryData.token,
            org_name: t.queryData.name
          },
          success: function(data) {
            console.log(666, data)
            console.log(666, {
              ACTIVITYID: t.queryData.id,
              DIRECTORYCODE: t.queryData.type,
              TOKEN: t.queryData.token,
              org_name: t.queryData.name
            })
            t.detailData = data[0]
            console.log(t.detailData)
          }
        })
      },
      getValFormat(v1, v2, v3) {
        v1 = Number(v1)
        v2 = Number(v2)
        v3 = Number(v3)
        var sign = ''
        if (v1 && v2 || v1 && v3) {
          if (v1 < v2) {
            sign = 'low'
          } else if (v1 > v3) {
            sign = 'up'
          }
        }
        return sign
      },
      getValFormat1(v1, str) {
        v1 = Number(v1)
        var v2, v3
        if (str.indexOf('-') != '-1') {
          var val = str.split("-")
          v2 = val[0]
          v3 = val[1]
        }
        var sign = ''
        if (v1 && v2 || v1 && v3) {
          if (v1 < v2) {
            sign = 'low'
          } else if (v1 > v3) {
            sign = 'up'
          }
        }
        return sign
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .bgcx_jy>>>.van-cell {
    padding: 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 15px;
  }

  .bgcx_jy>>>.van-cell__title {
    color: #606266;
    flex: 8;
  }

  .bgcx_jy>>>.van-cell__value {
    color: #303133;
    text-align: left;
    flex: 16;
  }

  .bgcx_jy>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .bgcx_jy .cell-item {
    padding: 18px 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 15px;
    background: #fff;
  }

  .bgcx_jy .flex-wrap.cell-item {
    background: #fdfdfd;
  }

  .bgcx_jy .flex-wrap {
    text-align: center;
    color: #606266;
    display: flex;
    justify-content: space-between;
  }

  .bgcx_jy .flex-wrap .f-df10 {
    text-align: left;
    width: 60%;
  }

  .bgcx_jy .flex-wrap .f-df7 {
    text-align: right;
    width: 20%;
  }

  .bgcx_jy .notice-wrap {
    background: #00c792;
    color: #fff;
    padding: 15px;
    font-size: 14px;
  }

  .bgcx_jy .aa {
    font-size: 1px;
  }
</style>