<template>
  <div class="bgcx_jc g-wrap g-hp100 s-bgcfff">
    <div class="notice-wrap" v-show="queryData.age">{{queryData.age}}</div>
    <van-cell-group class="f-bb10">
      <van-cell title="申请单号：">
        {{detailData.REPORT_NUM || '--'}}
      </van-cell>
      <van-cell title="报告医生：">
        {{detailData.REPORT_DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell title="申请类型：">
        {{detailData.EXAM_TYPE || '--'}}
      </van-cell>
      <div class="cell-item">
        <div class="tit s-c606266 f-pb15">检查所见：</div>
        <div class="f-lh25" v-if="index==1" @click="sj(0)">
          {{detailData.REPORT_OBJECTIVE|ellipsis}}
        </div>
        <div class="f-lh25" v-if="index==0" @click="sj(1)">{{detailData.REPORT_OBJECTIVE}}</div>
      </div>
      <div class="cell-item">
        <div class="tit s-c606266 f-pb15">检查结果：</div>
        <div class="f-lh25" v-if="index2==1" @click="jc(0)">{{detailData.REPORT_RESULT|ellipsis}}</div>
        <div class="f-lh25" v-if="index2==0" @click="jc(1)">{{detailData.REPORT_RESULT}}</div>
      </div>
    </van-cell-group>
    <div v-if="images.length">
      <div class="cell-item f-df f-dfj">
        <div class="tit s-c606266 ">影像所见</div>
        <!-- 
      <van-button round color="#00c792" plain size="mini">影像图片</van-button> -->
        <div class="txt f-tdu s-c00c792" @click="linkToPage('wodo_index',{img:images})">影像图片</div>
      </div>
      <div class="cell-item f-lh25" v-if="detailData.DIAGNOSIS_IDEA">
        {{detailData.DIAGNOSIS_IDEA}}
      </div>
    </div>

  </div>
</template>
<script>
  export default {
    name: 'bgcx_jc',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('检查报告详情')
      t.getData()
    },
    data() {
      return {
        index: 1,
        index2: 1,
        detailData: '',
        images: [],
      }
    },
    methods: {
      getData() {
        var t = this
        t.ajax({
          serverId: 'BMFW00017',
          url: t.serverPath2,
          loadMessage: '因影片文件较大，加载时间较长，请耐心等待',
          params: {
            ACTIVITYID: t.queryData.id,
            DIRECTORYCODE: t.queryData.type,
            TOKEN: t.queryData.token,
            org_name: t.queryData.name
          },
          success: function(data) {
            console.log(666789, data)
            t.detailData = data[0]
            console.log(789, t.detailData)
            if (t.detailData.IMG.length) {
              t.images = Object.values(t.detailData.IMG).map(function(row) {
                return row.IMAGE_SAVE_PATH
              })
            }
          }
        })
      },
      sj(val) {
        this.index = val
        console.log(888, val)
      },
      jc(val) {
        this.index2 = val
        console.log(888, val)
      }
    },
    filters: {
      ellipsis(value) {
        if (!value) return "";
        if (value.length > 300) { // 设置限定字数
          return value.slice(0, 300) + '......';
        }
        return value;
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .bgcx_jc>>>.van-cell {
    padding: 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 15px;
  }

  .bgcx_jc>>>.van-cell__title {
    color: #606266;
    flex: 8;
  }

  .bgcx_jc>>>.van-cell__value {
    color: #303133;
    text-align: left;
    flex: 16;
  }

  .bgcx_jc>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .bgcx_jc .cell-item {
    padding: 18px 15px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 15PX;
    background: #fff;
  }

  .bgcx_jc .f-lh25 {
    line-height: 22PX;
    text-indent: 2em;
  }

  .bgcx_jc .tit-item {
    border-bottom: 1PX solid #ebeef5;
    padding: 18px 15px;
    font-size: 15px;
  }

  .bgcx_jc .notice-wrap {
    background: #00c792;
    color: #fff;
    padding: 15px;
    font-size: 14px;
  }
</style>