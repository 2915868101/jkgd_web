<template>
  <div :class="['hsyy_yyjl g-wrap',{'s-bgcf6f6f6':!items||items.length,'s-bgcfff':items&&!items.length}]">
    <van-notice-bar class="f-fs13" wrapable :scrollable="false">
      如果要取消核酸检测，需要去预约医院的窗口进行取消。
    </van-notice-bar>
    <ul class="m-items-box s-bgcf6f6f6 f-fs15">
      <li class="item s-bgcfff f-bb10" v-for="(c,i) in items" :key="i">
        <div class="c_01 f-pt13 f-pb13 f-pl15 f-pr15 f-df">
          <div class="f-df1 f-df f-dfj f-fs15">
            <span class="f-fwb">新型冠状病毒核酸检测</span>
            <div class="s-cf30b32">&yen; {{Number(c.ORDERMON).toFixed(2)}}</div>
          </div>
        </div>
        <div class="c_02 f-pt15 f-pb15 f-pl15 f-pr15 f-df">
          <div class="f-df1 f-dfv">
            <div>
              <div><span class="s-c8b8b8b">检测人：</span><span>{{c.XM}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">检测医院：</span><span>{{c.JZDZ}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">身份证号：</span><span>{{regNumber(c.CARDNUM)}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">预约时间：</span><span>{{c.YYSJ}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">采样时段：</span><span>{{c.TIMERANGE}}</span></div>
            </div>
          </div>
        </div>
      </li>
    </ul>
    <van-empty v-if="items&&!items.length" description="暂无记录" />
  </div>
</template>
<script>
export default {
  name: 'hsyy_yyjl',
  directives: {},
  components: {},
  created() {
    var t = this
    t.updateTitle('核酸预约记录')
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function(sucFun) {
      var peopleInfo = sucFun.CONTENTS[0] ||[]
      t.CARDNUM = peopleInfo.CARD_NUMBER ||''
      t.getItemsList()
    }) 

  },
  computed: {},
  data() {
    return {
      items: '',
      CARDNUM:'',
    }
  },
  methods: {
    getItemsList() {
      var t = this
      t.ajax({
        serverId: 'WXBM00007',
        url: t.serverPath1,
        params: {
          OPENID: t.wx_userInfo.openid,
          CARDNUM:t.CARDNUM,
          TYPE:1
        }, 
        success(data) {
          t.items = data[0].CONTENTS || []
          console.log(t.items)
        }
      })
    },
  },
  mounted() {}
}
</script>
<style scoped>
.hsyy_yyjl .m-items-box .item .c_01 {
  border-bottom: 1px solid #ebebeb;
}
</style>