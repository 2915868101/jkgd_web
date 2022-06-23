<template>
  <div :class="['xgymyy_yyjl g-wrap',{'s-bgcf6f6f6':!items||items.length,'s-bgcfff':items&&!items.length}]">
    <!-- <van-notice-bar class="f-fs13" wrapable :scrollable="false">
      如果要取消核酸检测，需要去预约医院的窗口进行取消。
    </van-notice-bar> -->
    <ul class="m-items-box s-bgcf6f6f6 f-fs15">
      <li class="item s-bgcfff f-bb10" v-for="(c,i) in items" :key="i">
        <div class="c_02 f-pt15 f-pb15 f-pl15 f-pr15 f-df">
          <div class="f-df1 f-dfv">
            <div>
              <div><span class="s-c8b8b8b">接种人：</span><span>{{c.NAME}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">接种医院：</span><span>{{c.ORG_NAME}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">第几针：</span><span>{{c.NUM}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">身份证号：</span><span>{{regNumber(c.CARDNUM)}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">预约时间：</span><span>{{c.YYSJ}}</span></div>
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
  name: 'xgymyy_yyjl',
  directives: {},
  components: {},
  created() {
    var t = this
    t.updateTitle('疫苗预约记录')
    t.getItemsList()
  },
  computed: {},
  data() {
    return {
      items: '',
    }
  },
  methods: {
    getItemsList() {
      var t = this
      t.ajax({
        serverId: 'WXGDYYGHTZH010',
        url: t.serverPath1,
        params: {
          OPENID: t.wx_userInfo.openid,
        },
        success(data) {
          t.items = data[0].CONTENTS || []
        }
      })
    },
  },
  mounted() {}
}
</script>
<style scoped>
.xgymyy_yyjl .m-items-box .item .c_01 {
  border-bottom: 1px solid #ebebeb;
}
</style>