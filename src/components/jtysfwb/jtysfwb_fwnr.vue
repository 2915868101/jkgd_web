<template>
  <div class="jtys_fwnr g-wrap g-hp100 ">
    <div class="fwnr f-oa f-pb30 " :style="{height:(queryData.index==2?'98%':'89%')}">
      <van-row>
        <van-col span="20">
          <div class="f-fwb f-mb4 f-fs20 f-lh28">{{xq.FWB_NAME}}</div>
          <div class="s-c909399 f-fs12 f-lh20">
            <van-tag color="#00C792" plain type="primary">{{xq.FWB_LB}}</van-tag>
          </div>
        </van-col>
        <van-col span="4" class="f-tac">
          <div class="f-fwb f-mb4 f-fs24 f-lh32 " style="color:#fa8c16">￥{{zhifu}}</div>
          <div class=" f-fs12 f-lh18 f-tdl f-tac">￥{{xq.FWB_YINGSHOU}}</div>
        </van-col>
      </van-row>
      <div v-if="queryData.cc==1">
        <van-cell-group class="fyxx f-mt12 xinxi f-fs14 f-lh22 f-pl15">
          <div class="f-fs16 f-lh24 f-fwb  f-pt15">{{'购买人信息'}}</div>
          <div class="f-pb8 f-pt8 ">购买人：<span class="txt">{{xq.WX_NAME}}</span></div>
          <div class="f-pb2 f-df">购买人手机号：<div class="txt f-df">
              <div class="f-lh28 ">
                <van-icon name="phone" color="#00C792" size="17" />
              </div>
              <a :href="'tel:'+xq.WX_SJHM" class="s-c00c792">{{xq.WX_SJHM}}</a>
            </div>
          </div>
          <van-divider />
          <div class="f-fs16 f-lh24 f-fwb  f-pt15">{{'签约对象信息'}}</div>
          <div class="f-pb8 f-pt8">签约对象：<span class="txt">{{xq.NAME}}</span></div>
          <div class="f-pb8">身份证号：<span class="txt">{{xq.SFZH}}</span></div>
          <div class="f-pb2 f-df">手机号码：<div class="txt f-df">
              <div class="f-lh28 ">
                <van-icon name="phone" color="#00C792" size="17" />
              </div>
              <a :href="'tel:'+xq.SJHM" class="s-c00c792">{{xq.SJHM}}</a>
            </div>
          </div>
          <div class="f-pb8">与购买人关系：<span class="txt">{{xq.RELATED}}</span></div>
          <div class="f-pb8">所在地址：<span class="txt">{{xq.USER_AREA}}</span></div>
          <van-row class="f-pb8">
            <van-col span="5.5">详细地址：</van-col>
            <van-col span="18">
              <span class="txt">
                {{xq.USER_ADDRESS}}
              </span></van-col>
          </van-row>
          <div class="f-pb8">医疗机构：<span class="txt">{{xq.FWB_JG}}</span></div>
          <div class="f-pb8">机构地址：<span class="txt">{{xq.FWB_JG_ADDR}}</span></div>
          <div class="f-pb8">负责人姓名：<span class="txt">{{xq.FWB_DOCTOR}}</span></div>
          <div class="f-pb8">负责人电话：<span class="txt">{{xq.FWB_SJHM}}</span></div>
        </van-cell-group>
      </div>
      <van-cell-group class="f-mt15">
        <van-cell v-for="(c,i) in xqlist" :key="i" class="f-pt15">
          <template #title>
            <div class="f-fs16 f-lh24 f-fwb">{{c.FWB_PRO}}</div>
            <div class="f-fs14 f-lh22" style="color:#969799">年收费：{{c.FWB_YEAR_PRICE}}元<span class="f-pl15 f-pr15">|</span>服务机构：{{c.FWB_JG}}</div>
            <div class="f-fs14 f-lh22 f-pt10">{{c.FWB_CONTENT}}</div>
            <div class="f-lh18 f-fs12 f-pt10 f-mb10">
              <van-tag color="#969799" plain style="background:#F4F9F9">年服务次数：{{c.FWB_TIMES}}</van-tag>
              <van-tag color="#969799" plain style="background:#F4F9F9">收费标准：{{c.FWB_PRICE_STANDARD}}元/次</van-tag>
            </div>
          </template>
        </van-cell>
      </van-cell-group>
      <van-cell-group class="fyxx f-mt12">
        <div class="f-fs16 f-lh24 f-fwb f-pl15 f-pt15">{{'费用信息'}}</div>
        <van-cell title="打包应收金额：" :value="'￥'+xq.FWB_YINGSHOU" />
        <van-cell title="实收金额(服务包核定价格)：" :value="'￥'+zhifu" />
      </van-cell-group>
    </div>
    <div class="fixed_btn g-wp100 f-pb30 " style="height:11%" v-if="queryData.index==1">
      <van-button style="width:100%" color="#00c792" @click="linkToPage('jtysfwb_qy', queryData.xq)">确认此服务包</van-button>
    </div>
    <!-- <div class="fixed_btn g-wp100 f-pb30 " style="height:11%" v-if="queryData.index==2&&queryData.cc==1">
      <van-button style="width:100%" color="#00c792" @click="linkToPage('jtysfwb_qy_team')">确认</van-button>
    </div>
    <div class="fixed_btn g-wp100 f-pb30 " style="height:11%" v-if="queryData.index==2&&!queryData.cc">
      <van-button style="width:100%" color="#00c792" @click="linkToPage('jtysfwb_qy_01')">确认</van-button>
    </div> -->
    <div class="fixed_btn g-wp100  f-pb30 " style="height:11%" v-if="!queryData.index">
      <van-button style="width:100%" color="#00c792" @click="toQY02()">选择此服务包</van-button>
    </div>

  </div>
</template>
<script>
  export default {
    name: 'jtys_fwnr',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle(t.queryData.title || '服务包详情')
      console.log(666666, t.queryData)
      t.pathParams = t.queryData.pathParams
      t.xq = t.queryData.xq
      t.currFwb = t.$store.state.store_jtys.currFwb
      if (t.queryData.index == 2) {
        t.zhifu = t.xq.FWB_MONEY
        t.getfwbxq(t.xq.FWB_ID)
      } else {
        t.zhifu = t.xq.FWB_ZIFU
        t.getfwbxq(t.xq.ID)
      }
    },
    data() {
      return {
        zhifu: "",
        pathParams: '',
        xq: '',
        xqlist: [],
      }
    },
    methods: {
      getfwbxq(val) {
        var t = this
        t.ajax({
          serverId: 'WXJT0002',
          url: t.serverPath4,
          params: {
            id: val
          },
          success: function(data) {
            t.xqlist = data[0].CONTENTS
            console.log(t.xqlist)

          }
        })
      },
      toQY02() {
        var t = this
        t.pathParams.ID = t.xq.ID
        t.pathParams.FWB_LB = t.xq.FWB_LB
        t.pathParams.FWB_YINGSHOU = t.xq.FWB_YINGSHOU
        t.pathParams.FWB_ZIFU = t.xq.FWB_ZIFU
        t.pathParams.FWB_NAME = t.xq.FWB_NAME
        t.pathParams.TYPE = t.xq.FWB_NAME + '(￥' + t.xq.FWB_ZIFU + ')'
        t.linkToPage('jtysfwb_index', t.pathParams)
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .fwnr {
    padding: 17px;
  }

  .jtys_fwnr .fyxx>>>.van-cell,
  .jtys_fwnr .fyxx>>>.van-cell__value {
    color: #646566;
  }

  .jtys_fwnr .fyxx>>>.van-cell__title {
    color: #646566;
    min-width: 70%;
  }

  .jtys_fwnr>>>.van-tag {
    padding: 3px 8px;
    margin-right: 10px;
    margin-top: 5px;
  }

  .fixed_btn {
    bottom: 0px;
    padding: 0 17px;
  }

  .jtys_fwnr .xinxi {
    color: #303133;
  }

  .jtys_fwnr .txt {
    color: #969799;
  }
</style>