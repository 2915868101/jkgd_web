<template>
  <div class="tjbg_details s-bgcf6f6f6" @click="hideTips">
    <van-sticky @scroll="onScroll">
      <div class="m-head-box f-oh s-bgcf6f6f6">
        <div class="c_01 f-df s-bgcfff">
          <div class="c_01_1">
            <img :src="register_info.TX||wx_userInfo.headimgurl" v-if="register_info.CARD_NUMBER==queryData.CNUM" />
            <img :src="$store.state.def_headimg" v-else />
          </div>
          <div class="c_01_2 f-df1">
            <div class="f-fs15 c_01_2_1">{{queryData.ORG_NAME}}健康体检报告</div>
            <div class="s-cc0c4cc f-fs13 f-mt8">{{queryData.TREAT_DATE|formatDate}}</div>
          </div>
        </div>
        <slide-up-down :active="active_top" :duration="300" class="f-fs12 f-lh40 f-mb12">
          <div class="c_02 f-df s-bgcfff">
            <div class="f-df1"><span class="s-c8b8b8b">申请单号：</span><span>{{queryData.LS_NUM}}</span></div>
            <div class="f-df1"><span class="s-c8b8b8b">姓名：</span><span>{{queryData.XM}}</span></div>
          </div>
          <!--  <div class="c_02 f-df s-bgcfff">
            <div class="f-df1"><span class="s-c8b8b8b">年龄：</span><span>{{queryData.NL}}</span></div>
            <div class="f-df1"><span class="s-c8b8b8b">性别：</span><span>{{queryData.XB}}</span></div>
          </div> -->
          <div class="c_02 f-df s-bgcfff">
            <div class="f-df1"><span class="s-c8b8b8b">总检医生：</span><span>{{queryData.YSXM}}</span></div>
            <div class="f-df1"><span class="s-c8b8b8b">体检日期：</span><span>{{queryData.TREAT_DATE|formatDate}}</span></div>
          </div>
        </slide-up-down>
        <div class="c_03 f-df s-bgcfff f-oh f-fs14">
          <div :class="['f-df1 c_03_1', {on:showIndex==0}]" @click.stop="showDetails(0)">报告结果</div>
          <div :class="['f-df1 c_03_1', {on:showIndex==1}]" @click.stop="showDetails(1)">报告详情</div>

        </div>
      </div>
    </van-sticky>
    <div class="m-items-box1" v-if="showIndex==1">
      <van-collapse v-model="activeNames">
        <van-collapse-item v-for="(b,i) in items1" :key="i" class="f-pt8">
          <template #title>
            <div>{{b.JCXM}}</div>
          </template>
          <!-- <template #value>
            <div class="s-c646464">{{查看}}</div>
          </template> -->
          <div v-for="(c,n) in b.XQ" :key="n">
            <div class="f-pl15 f-pr15 f-df">
              <div class="f-df15"> {{c.XMMC}}</div>
              <div class="f-df4" v-if="c.XMJG.length<'20'"> {{c.XMJG}}</div>
            </div>
            <div class="f-pt10" v-if="c.XMJG.length>'20'"> {{c.XMJG}}</div>
            <div class="f-pt10">小结：{{c.JG}}</div>
            <van-divider />
          </div>
        </van-collapse-item>
      </van-collapse>
      <div v-if="!items1" class="s-bgcfff f-fs15 f-lh26 f-pt20 f-pb20 f-fwb f-pl20">暂无数据</div>
    </div>
    <div class="m-items-box2" v-if="showIndex==0">
      <div class="c_01 s-bgcfff">
        <div class="c_01_1 f-fs15 f-lh26 f-pt5 f-pb5 f-fwb">总检结果</div>
        <div class="c_01_2 f-fs12 f-lh26 f-pb5">
          <div v-html="queryData.TJ_REASULT"></div>
        </div>
      </div>
      <div class="c_02 s-bgcfff f-mt10">
        <div class="c_02_1 f-fs15 f-lh26 f-pt5 f-pb5 f-fwb">健康建议</div>
        <div class="c_02_2 f-fs12 f-lh26 f-pb5">{{(queryData.JCJY.search('null')==-1)?queryData.JCJY:''}}</div>
      </div>
    </div>
    <!-- <div v-transfer-dom>
      <previewer :list="previewer_list" ref="previewer" @on-close="onClosePreviewer" v-if="close_flag&&previewer_list&&previewer_list.length"></previewer>
    </div>  -->
  </div>
</template>
<script>
  import SlideUpDown from 'vue-slide-up-down'
  /* import {
    Previewer,
    TransferDom
  } from 'vux' */
  export default {
    name: 'tjbg_details',
    directives: {
      /*   TransferDom */
    },
    components: {
      SlideUpDown,
      /*  
       Previewer */
    },
    created() {
      var t = this
      t.updateTitle('体检报告详情')
      console.log(789, t.queryData)
      t.getTjbgItems1()
    },
    computed: {},
    data() {
      return {
        activeNames: [],
        showIndex: 0,
        active: [],
        active_top: true,
        items1: '',
        items2: [],
        imgList: [],
        zjs_data: '',
        jkjy_data: '',
        close_flag: false,
        previewer_list: ''
      }
    },
    methods: {
      hideTips() {
        var t = this
        t.$nextTick(function() {
          var tips = document.querySelectorAll('.tjbg_details .m-ckz-tips')
          for (var i = 0; i < tips.length; i++) {
            tips[i].style.display = 'none'
          }
        })
      },
      onScroll(v) {
        var t = this
        t.active_top = !v.isFixed
      },
      showDetails(i) {
        var t = this
        t.showIndex = i
      },
      showLis(b, i) {
        var t = this
        if (!t.items2[i]) {
          t.getTjbgItems2(b, i)
        } else {
          // t.hideLis(i)
          t.$set(t.active, i, !t.active[i])
          t.previewer_list = t.imgList[i]
        }
      },
      hideLis(index) {
        var t = this
        t.$nextTick(function() {
          for (var i = 0; i < t.items1.length; i++) {
            if (index != i) {
              t.$set(t.active, i, false)
            }
          }
        })
      },
      getTjbgItems1() {
        var t = this
        t.ajax({
          url: t.serverPath1,
          method: 'post',
          serverId: 'BMFWTJBG00017',
          params: {
            ORG_ID: t.queryData.ORG_ID,
            ID: t.queryData.TREATMENT_ID,
          },
          success(data) {
            if (data[0].CONTENTS != 0) {
              t.items1 = data[0].CONTENTS
              console.log(7899999, t.items1)
            }
          }
        })
      },
      showImage(index) {
        var t = this
        t.close_flag = true
        history.pushState({
          page: 'tjbg_details'
        }, null, '')
        addEventListener('popstate', t.closeCurrentPage, false)
        t.$nextTick(function() {
          t.$refs.previewer.show(index)
        })
      },
    },
    filters: {
      formatDate(value) {
        let date = new Date(value);
        let y = date.getFullYear();
        let MM = date.getMonth() + 1;
        MM = MM < 10 ? ('0' + MM) : MM;
        let d = date.getDate();
        d = d < 10 ? ('0' + d) : d;
        return y + '-' + MM + '-' + d
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  .tjbg_details {
    padding: 10px 12px 10px 12px;
    min-height: 100%;
  }

  .tjbg_details .m-head-box {
    border-bottom: 1px solid #ebebeb;
  }

  .tjbg_details .m-head-box,
  .tjbg_details .m-head-box .c_03 {
    border-radius: 5px 5px 0 0;
  }


  .tjbg_details .m-head-box .c_03 .c_03_1 {
    height: 45px;
    line-height: 45px;
    text-align: center;
  }

  .tjbg_details .m-head-box .c_03 .c_03_1.on {
    background-color: #d4f1ed;
    color: #28b7a3;
  }

  .tjbg_details .m-head-box .c_01_1 {
    margin: 15px 0 8px 15px;
  }

  .tjbg_details .m-head-box .c_01_1 img {
    width: 42px;
    height: 42px;
    border-radius: 50%;
  }

  .tjbg_details .m-head-box .c_01_2,
  .tjbg_details .m-items-box1 .items .tt {
    padding: 22px 0 0 15px;
  }

  .tjbg_details .m-head-box .c_02 {
    border-top: 1px solid #ebebeb;
    padding: 0 15px;
  }

  .tjbg_details .m-items-box1 .items {
    margin-bottom: 10px;
  }

  .tjbg_details .m-items-box1 .items .cont .c_01 {
    padding: 15px;
    border-top: 1px solid #f0f0f0;
  }

  .tjbg_details .m-items-box2 .c_01,
  .tjbg_details .m-items-box2 .c_02 {
    padding: 15px;
  }

  .tjbg_details .m-items-box1 .items .cont .c_01 .img {
    display: inline-block;
    width: 60px;
    height: 60px;
    border: 1px solid #ddd;
    padding: 1px;
    margin: 0 8px 8px 0;
  }

  .tjbg_details .m-items-box1 .items .cont .c_01 .img img {
    width: 100%;
    height: 100%;
  }

  .tjbg_details .m-ckz-tips {
    padding: 1px 10px;
    background-color: #fff;
    border-radius: 10px;
    font-size: 14px;
    -webkit-box-shadow: 0 0 5px #eee;
    -moz-box-shadow: 0 0 5px #eee;
    box-shadow: 0 0 5px #eee;
    position: absolute;
    top: 15px;
    right: 40px;
    max-width: 260px;
  }

  @media screen and (max-width: 320px) {}
</style>