<template>
  <div class="jkda_jks g-wrap s-bgcfff g-hp100">
    <van-cell-group>
      <van-cell title="既往手术" :value="peopleData.SSS || '无'" />
      <van-cell title="既往病史" :value="peopleData.JBS || '无'" />
      <van-cell title="药物过敏" :value="peopleData.YWGMS || '无'" />
      <van-cell title="残疾情况" :value="peopleData.CJQK || '无'" />
      <van-cell title="遗传病史" :value="peopleData.YCBS || '无'" />
      <van-cell title="暴露史" :value="peopleData.BLS || '无'" />
    </van-cell-group>
  </div>
</template>
<script>
  export default {
    name: 'jkda_jks',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('健康史')
      t.searchForm = t.queryData
      t.getPeopleData()
    },
    data() {
      return {
        searchForm: '',
        peopleData: '',
      }
    },
    methods: {
      // 获取个人健康档案
      getPeopleData() {
        var t = this
        t.ajax({
          serverId: 'WXBM00035',
          url: t.serverPath2,
          params: {
            TOKEN: t.searchForm.token,
          },
          success: function(data) {
            console.log(555, data)
            t.peopleData = data[0]
            console.log(666, t.peopleData)
          }
        })
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jkda_jks>>>.van-cell {
    padding: 15px;
    font-size: 15px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 15px;
  }

  .jkda_jks>>>.van-cell__title {
    color: #606266;
    font-weight: bold;
  }

  .jkda_jks>>>.van-cell__value {
    color: #606266;
  }

  .jkda_jks>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }
</style>