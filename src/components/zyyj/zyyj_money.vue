<template>
  <div class="zyyj_money g-wrap s-bgcfff">
    <div class="input-wrap f-df">
      <span>预缴金额：</span>
      <div class="f-df f-ml15">
        <span>￥</span>
        <van-field v-model="money" type="digit" placeholder="请输入预缴金额" clearable />
      </div>
    </div>
    <div class="btn-wrap">
      <div :class="[currMoney===i?'on':'','btn']" v-for="(c,i) in moneyList" :key="i" @click="doSelect(c,i)">{{c}}元</div>
    </div>
    <div class="g-wp100 s-bgcfff f-pt40 f-pb15">
      <van-button v-if="money" size="small" round color="#00c792" @click="toSucc">确认充值</van-button>
      <van-button v-else size="small" round color="#00c792" disabled>确认充值</van-button>
    </div>

  </div>
</template>
<script>
export default {
  name: 'zyyj_money',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('住院详情')
  },
  data() {
    return {
      moneyList: ['500', '1000', '2000', '3000', '5000', '10000'],
      currMoney: '',
      money: ''
    }
  },
  methods: {
    doSelect(c, i) {
      var t = this
      t.currMoney = i
      t.money = c
    },
    toSucc() {
      var t = this
      t.linkToPage('zyyj_jfcg')
    }
  },
  watch:{
    money(val) {
      if(!val){
        this.currMoney=''
      }
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.zyyj_money>>>.van-cell {
  border-bottom: 1px solid #ebeef5;
}

.zyyj_money>>>.van-field__body {
  height: 60px;
}

.zyyj_money .input-wrap {
  font-size: 15PX;
  color: #606266;
  height: 80px;
  line-height: 80px;
  align-items: center;
  border-bottom: 1px solid #ebeef5;
  padding: 0 20px;
}

.zyyj_money>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.zyyj_money .btn-wrap {
  padding: 20px;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.zyyj_money .btn-wrap .btn {
  width: 30%;
  font-size: 15PX;
  color: #c0c4cc;
  border: 1px solid #c0c4cc;
  border-radius: 3px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  margin-top: 20px;
}

.zyyj_money .btn-wrap .btn.on {
  color: #00c792;
  border-color: #00c792;
}

.zyyj_money>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  font-size: 15PX;
  padding: 0 10px;
}
</style>