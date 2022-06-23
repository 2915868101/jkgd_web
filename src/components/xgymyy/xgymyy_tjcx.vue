<template>
  <div :class="['xgymyy_tjcx g-wrap',{'s-bgcf6f6f6':!items||items.length,'s-bgcfff':items&&!items.length}]"> 
    <div class="date-wrap">
      <div class="f-pt10 f-pb10 f-pl10 f-pr10 f-df f-dfj">
        <div class="date-input" @click="showDate1=true"><i class="f-ml10 f-mr8"></i><span>{{$moment(Date1).format('YYYY-MM-DD')}}</span></div>
        <div class="date-input" @click="showDate2=true"><i class="f-ml10 f-mr8"></i><span>{{$moment(Date2).format('YYYY-MM-DD')}}</span></div>
        <van-button color="#409EFF" @click="doSelect()">查询</van-button>
      </div>
      <van-popup v-model="showDate1" position="bottom" :style="{ height: '30%' }">
        <van-datetime-picker v-model="Date1" type="date" title="选择开始时间" :max-date="maxDate" @cancel="showDate1=false" @confirm="showDate1=false" />
      </van-popup>
      <van-popup v-model="showDate2" position="bottom" :style="{ height: '30%' }">
        <van-datetime-picker v-model="Date2" type="date" title="选择结束时间" :min-date="minDate" :max-date="maxDate" @cancel="showDate2=false" @confirm="showDate2=false" />
      </van-popup>
    </div>
    <ul class="m-items-box s-bgcf6f6f6 f-fs15">
      <li class="item s-bgcfff f-bb10" v-for="(c,i) in items" :key="i">
        <div class="c_02 f-pt15 f-pb15 f-pl15 f-pr15 f-df">
          <div class="f-df1 f-dfv">
            <div>
              <div><span class="s-c8b8b8b">接种人：</span><span>{{c.NAME}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">接种医院：</span><span>{{c.ORG_NAME}}</span></div>
              <div class="f-mt15"><span class="s-c8b8b8b">第几针：</span><span>{{c.NUM}}</span></div> 
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
  name: 'xgymtjcxyjl',
  directives: {},
  components: {},
  created() {
    var t = this
    t.updateTitle('疫苗查询统计')
    t.searchForm.KSSJ = t.$moment(t.Date1).format('YYYY-MM-DD')
    t.searchForm.JSSJ = t.$moment(t.Date2).format('YYYY-MM-DD')
    t.getItemsList()
  },
  computed: {},
  data() {
    return {
      items: '',
      searchForm: {
        KSSJ: '',
        JSSJ: ''
      },
      showDate1: false,
      showDate2: false,
      Date1: new Date(this.$moment().add(-30, 'days')),
      Date2: new Date(),
      maxDate: new Date(),
      minDate: new Date(this.$moment().add(-30, 'days')),
    }
  },
  methods: {
    // 查询点击
    doSelect() {
      var t = this
      if (!t.searchForm.KSSJ) {
        t.$toast('查询开始日期不能为空！')
      } else if (!t.searchForm.JSSJ) {
        t.$toast('查询结束日期不能为空！')
      } else {
        t.searchForm.KSSJ = t.$moment(t.Date1).format('YYYY-MM-DD')
        t.searchForm.JSSJ = t.$moment(t.Date2).format('YYYY-MM-DD')
        t.getItemsList()
      }
    },
    getItemsList() {
      var t = this
      t.ajax({
        serverId: 'WXGDYYGHTZH010',
        url: t.serverPath1,
        params: t.searchForm,
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
.xgymyy_tjcx .date-wrap .date-input {
  width: 39%;
  border: 1px solid #c0c4cc;
  border-radius: 3px;
  height: 32px;
  line-height: 32px;
  text-align: center;
  display: flex;
  align-items: center;
  font-size: 14px;
}

.xgymyy_tjcx .date-wrap .date-input i {
  background: url(../../assets/img/util/calendar.svg) no-repeat;
  background-size: 100%;
  display: inline-block;
  width: 15px;
  height: 15px;
}

.xgymyy_tjcx>>>.van-button {
  height: 32px;
}
.xgymyy_tjcx .m-items-box .item .c_01 {
  border-bottom: 1px solid #ebebeb;
}
</style>