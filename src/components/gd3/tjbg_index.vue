<template>
  <div :class="['tjbg_index g-wrap',{'s-bgcf6f6f6':!tjbg_lists||tjbg_lists.length,'s-bgcfff':tjbg_lists&&!tjbg_lists.length}]">
    <van-sticky>
      <div class="user-top s-bgcfff">
        <img class="img-item f-fl" :src="register_info.TX||wx_userInfo.headimgurl" v-if="register_info.CARD_NUMBER==jzr_info.CNUM" />
        <img class="img-item f-fl" :src="jzr_info.TX||$store.state.def_headimg" v-else />
        <div class="cont-item">
          <div class="f-pt4">
            <span class="name f-fs16">
              <span class="f-fs15">{{jzr_info.NAME||'未知姓名'}}</span>
              <span class="f-fs13 f-pl10 f-pr10 s-cc0c4cc">{{jzr_info.XB==1?'男':'女'}}</span>
              <!--   <span class="s-cc7c7c7 f-fs14">{{年龄}}</span>  -->
            </span>
            <span class="c_04 s-c909399" @click="linkToPage('/people_lists',jzr_info)">切换就诊人 </span>
          </div>
          <div class="f-pt8 s-c606266">
            身份证号：{{regNumber(jzr_info.CARD_NUMBER)}}
          </div>
        </div>
      </div>
    </van-sticky>
    <ul class="m-lists">
      <li class="lis f-mb10 s-bgcfff" v-for="(c,i) in tjbg_lists" :key="i" @click="linkToPage('tjbg_details',c)">
        <div class="tit f-fs12">
          <div>{{c.ORG_NAME}}健康体检报告</div>
          <div class="s-cc0c4cc f-mt8">{{c.TREAT_DATE|formatDate}}</div>
        </div>
        <div class="cont s-c8b8b8b">
          <div class="f-df">
            <div class="f-df1">申请单号：{{c.TREATMENT_ID}}</div>
            <div class="f-df1">姓名：{{c.XM}}</div>
          </div>
          <div class="f-df f-mt8">
            <div class="f-df1">总检医生：{{c.YSXM}}</div>
            <div class="f-df1">体检日期：{{c.TREAT_DATE|formatDate}}</div>
          </div>
        </div>
      </li>
    </ul>
    <div v-if="tjbg_lists&&!tjbg_lists.length">
      <empty :src="require('@/assets/img/gd3/empty.jpg')" text="没有体检报告"></empty>
      <div class="f-tac f-fs12 s-cc9c9c9  f-mt8">可切换就诊人查看体检报告</div>
    </div>
  </div>
</template>
<script>
  import empty from '@/components/util/empty'
  export default {
    name: 'tjbg_index',
    directives: {},
    components: {
      empty,
    },
    created() {
      var t = this
      t.updateTitle('体检报告')
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(data) {
        t.jzr_info = data.CONTENTS[0]
        t.getTjbgLists(t.jzr_info.CARD_NUMBER)
      })

    },
    computed: {
      jzrInfo() {
        var t = this
        var jzr_info = {}
        jzr_info.ID = t.jzr_info.id || t.jzr_info.JZPID
        jzr_info.XM = t.jzr_info.xm || t.jzr_info.JZXM
        jzr_info.CARDTYPE = t.jzr_info.cardtype || t.jzr_info.CARDTYPE
        jzr_info.CNUM = t.jzr_info.kh || t.jzr_info.CNUM
        jzr_info.SJHM = t.jzr_info.sjhm || t.jzr_info.SJHM
        return jzr_info
      }
    },
    data() {
      return {
        jzr_info: '',
        tjbg_lists: ''
      }
    },
    methods: {
      getTjbgLists(val) {
        var t = this
        var myDate = new Date();
        var ksrq = (myDate.getFullYear() - 2) + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate()
        var jsrq = myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate()
        t.ajax({
          url: t.serverPath1,
          method: 'post',
          serverId: 'BMFWTJBG00016',
          params: {
            cardnum: val,
            KSRQ: ksrq,
            JSRQ: jsrq,
            TYPE: '3',
            NAME: t.jzr_info.NAME
          },
          success(data) {
            console.log(666, t.jzr_info.NAME)
            if (data[0].CODE == 0) {
              t.tjbg_lists = data[0].CONTENTS
            } else {
              t.tjbg_lists = []
            }


          }
        })
      }
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
  .tjbg_index .user-top {
    padding: 20px;
    font-size: 14px;
  }

  .tjbg_index .user-top .img-item {
    width: 45px;
    height: 45px;
    border-radius: 50%;
  }

  .tjbg_index .user-top .cont-item {
    margin-left: 65px;
    height: 45px;
  }

  .tjbg_index .user-top .name {
    font-weight: bold;
  }

  .tjbg_index .user-top {
    border-bottom: 10px solid #f6f6f6;
  }


  .tjbg_index .m-lists .lis {
    position: relative;
    border-radius: 7px;
    overflow: hidden;
    padding: 0 10px;
  }

  .tjbg_index .m-lists .lis:before {
    content: '体检报告';
    display: block;
    width: 110px;
    height: 22px;
    line-height: 22px;
    text-align: center;
    position: absolute;
    right: -25px;
    top: 10px;
    color: #9cd9cf;
    background-color: #e9f8f6;
    font-size: 12px;
    transform: rotate(35deg);
  }

  .tjbg_index .m-lists .lis .tit {
    padding: 15px;
    border-bottom: 1px solid #ebebeb;
  }

  .tjbg_index .m-lists .lis .cont {
    padding: 15px;
  }

  .tjbg_index .m-lists .lis .cont {
    font-size: 12px;
  }


  .user-top .c_04 {
    position: absolute;
    right: 15px;
    top: 15px;
    border: 1px solid #28b7a3;
    color: #28b7a3;
    width: 85px;
    height: 26px;
    line-height: 26px;
    text-align: center;
    border-radius: 15px;
    font-size: 12px;
  }

  @media screen and (max-width: 320px) {}
</style>