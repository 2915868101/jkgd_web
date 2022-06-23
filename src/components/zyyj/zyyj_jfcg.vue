<template>
  <div class="zyyj_jfcg g-wrap s-bgcfff">
    

  </div>
</template>
<script>
export default {
  name: 'zyyj_jfcg',
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

.zyyj_jfcg>>>.van-cell {
  border-bottom: 1px solid #ebeef5;
}

.zyyj_jfcg>>>.van-field__body {
  height: 60px;
}

.zyyj_jfcg .input-wrap {
  font-size: 15PX;
  color: #606266;
  height: 80px;
  line-height: 80px;
  align-items: center;
  border-bottom: 1px solid #ebeef5;
  padding: 0 20px;
}

.zyyj_jfcg>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.zyyj_jfcg .btn-wrap {
  padding: 20px;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.zyyj_jfcg .btn-wrap .btn {
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

.zyyj_jfcg .btn-wrap .btn.on {
  color: #00c792;
  border-color: #00c792;
}

.zyyj_jfcg>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  font-size: 15PX;
  padding: 0 10px;
}
</style>