<template>
  <div class="jkda_detail g-wrap">
    <div class="notice-wrap" v-show="searchForm.age">{{searchForm.age}}</div>
    <van-cell-group>
      <van-cell center title="就诊人">
        {{detailData.XM}}
      </van-cell>
      <van-cell center title="住院时间">
        {{detailData.ADMISSION_DATE || '--'}}
      </van-cell>
      <van-cell center title="主治医生">
        {{detailData.DOCTOR_NAME || '--'}}
      </van-cell>
      <van-cell center title="责任护士">
        {{detailData.NURSE_NAME || '--'}}
      </van-cell>
      <van-cell center title="住院号">
        {{detailData.VISITING_NUM || '--'}}
      </van-cell>
      <van-cell center title="住院医院">
        {{detailData.ORG_NAME || '--'}}
      </van-cell>
      <!--  <div class="cell-item">
        <div class="tit">入院诊断：</div>
        <div class="cont">
          {{detailData.DISEASE || '--'}}
        </div>
      </div> -->
    </van-cell-group>
    <van-cell-group class="f-mt15">
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
    <van-collapse v-model="activeNames">
      <van-collapse-item title="临时医嘱明细" name="2">
        <div v-for="(c,i) in detailData.LYZ" :key="i">
          <div class="flex-item">
            <div class="f-df16">{{c.DRUG_NAME}}</div>
            <div class="f-df2">{{c.SPEC || '--'}}</div>
            <div class="f-df2">
              <span v-if="c.QUANTITY">{{Number(c.QUANTITY).toFixed(0)}}{{c.UNIT}}</span>
              <span v-else>0</span>
            </div>
          </div>
        </div>
        <div v-show="!detailData.YZ" class="f-fs14 f-pt15 f-pb15 f-pl15 f-pr15">暂无处方</div>
      </van-collapse-item>
      <van-collapse-item title="长期医嘱明细" name="1">
        <div v-for="(c,i) in detailData.YZ" :key="i">
          <div class="flex-item" v-for="(c1,i1) in c.QD" :key="i1">
            <div class="f-df16">{{c1.DRUG_NAME}}</div>
            <div class="f-df4">{{c1.SPEC || '--'}}</div>
            <div class="f-df4">
              <span v-if="c1.QUANTITY">{{Number(c1.QUANTITY).toFixed(0)}}{{c1.UNIT}}</span>
              <span v-else>--</span>
            </div>
          </div>
        </div>
        <div v-show="!detailData.YZ" class="f-fs14 f-pt15 f-pb15 f-pl15 f-pr15">暂无处方</div>
      </van-collapse-item>
    </van-collapse>
    <!--  <div class="s-bgcfff f-mt15 swiper-wrap" v-else>
      <div class="swiper-nav" v-if="detailData.YZ">
        <div class="item" @click="ToPrev"><i :class="[current==0?'s-cc0c4cc':'s-c606266','iconfont icon-arrow-left f-fs13']"></i></div>
        <div class="item">第 {{current+1}} 天</div>
        <div class="item" @click="ToNext"><i :class="[current==(detailData.YZ.length-1)?'s-cc0c4cc':'s-c606266','iconfont icon-arrow-right f-fs13']"></i></div>
      </div>
      <van-cell title="临时医嘱明细" />
      <van-swipe ref="mySwipe" class="my-swipe" @change="onChange" :show-indicators="false" :loop="false" v-if="detailData.YZ">
        <van-swipe-item v-for="(c,i) in detailData.LYZ" :key="i">
          <div class="f-df16">{{c.DRUG_NAME}}</div>
          <div class="f-df4">{{c.SPEC || '--'}}</div>
          <div class="f-df4">
            <span v-if="c.QUANTITY">{{Number(c.QUANTITY).toFixed(0)}}{{c.UNIT}}</span>
            <span v-else>--</span>
          </div>
        </van-swipe-item>
      </van-swipe>
      <van-cell title="长期医嘱明细" />
      <van-swipe ref="mySwipe" class="my-swipe" @change="onChange" :show-indicators="false" :loop="false" v-if="detailData.YZ">
        <van-swipe-item v-for="(c,i) in detailData.YZ" :key="i">
          <div class="flex-item" v-for="(c1,i1) in c.QD" :key="i1">
            <div class="f-df16">{{c1.DRUG_NAME}}</div>
            <div class="f-df4">{{c1.SPEC || '--'}}</div>
            <div class="f-df4">
              <span v-if="c1.QUANTITY">{{Number(c1.QUANTITY).toFixed(0)}}{{c1.UNIT}}</span>
              <span v-else>--</span>
            </div>
          </div>
        </van-swipe-item>
      </van-swipe>
      <div class="f-pt20 f-pb20 f-pl20 f-pr20" v-else>暂无数据</div>
    </div> -->
  </div>
</template>
<script>
  export default {
    name: 'jkda_detail',
    components: {},
    computed: {},
    created() {
      var t = this
      // 入院登记详情
      t.updateTitle(t.queryData.pageTitle)
      console.log(5566, t.queryData)
      t.searchForm = t.queryData
      t.getDetailData()
    },
    data() {
      return {
        detailData: [],
        jcList: [],
        jyList: [],
        activeNames: [],
        current: 0,
      }
    },
    methods: {
      getDetailData() {
        var t = this
        console.log(7899, t.queryData, )
        t.ajax({
          serverId: 'WXBM00037',
          url: t.serverPath2,
          params: t.searchForm,
          success: function(data) {
            console.log(777, data)
            t.detailData = data[0]
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
        var t = this
        if (data.TYPE == 'JC') {
          t.linkToPage('bgcx_jc', {
            id: data.ID,
            mpi: data.EXTMPI
          })
        } else {
          t.linkToPage('bgcx_jy', {
            id: data.ID,
            mpi: data.EXTMPI
          })
        }
      },
      onChange(index) {
        this.current = index;
      },
      ToPrev() {
        var t = this
        if (t.current > 0) {
          t.$refs.mySwipe.prev()
        }
      },
      ToNext() {
        var t = this
        console.log(t.current, t.detailData.YZ.length)
        if (t.current < t.detailData.YZ.length) {
          t.$refs.mySwipe.next()
        }
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @import '../../../assets/css/jkda/jkda_detail.css';

  @media screen and (max-width: 320px) {}
</style>