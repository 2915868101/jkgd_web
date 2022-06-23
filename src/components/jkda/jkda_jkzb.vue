<template>
  <div class="jkda_jkzb g-wrap s-bgcfff g-hp100 f-pl20 f-pr20 f-pt20 f-pb20">
    <div class="list-wrap">
      <div class="list-item">
        <img src="../../assets/img/jkda/quota-icon1.png">
        <p>身高</p>
        <p class="f-lh18">
          <span v-if="peopleData.HEIGHT">{{peopleData.HEIGHT}}cm</span>
          <span v-else>--</span>
        </p>
      </div>
      <div class="list-item">
        <img src="../../assets/img/jkda/quota-icon2.png">
        <p>体重</p>
        <p class="f-lh18">
          <span v-if="peopleData.BODY_WEIGHT">{{peopleData.BODY_WEIGHT}}kg</span>
          <span v-else>--</span>
        </p>
      </div>
      <div class="list-item">
        <img src="../../assets/img/jkda/quota-icon3.png">
        <p>血型</p>
        <p class="f-lh18">
          <span v-if="peopleData.ABO_CD">{{peopleData.ABO_CD}}</span>
          <span v-else>--</span>
        </p>
      </div>
      <div class="list-item gh-item">
        <img src="../../assets/img/jkda/quota-icon4.png">
        <p>心率</p>
        <p class="f-lh18">
          <span v-if="peopleData.HEART_RATE">{{Number(peopleData.HEART_RATE).toFixed(0)}}次/分钟</span>
          <span v-else>--</span>
        </p>
      </div>
      <div class="list-item gh-item">
        <img src="../../assets/img/jkda/quota-icon5.png">
        <p>肺活量</p>
        <p class="f-lh18">
          <span v-if="peopleData.FHL">{{peopleData.FHL}}次/m</span>
          <span v-else>--</span>
        </p>
      </div>
      <div class="list-item gh-item">
        <img src="../../assets/img/jkda/quota-icon6.png">
        <p>血压</p>
        <p class="f-lh18">
          <span class="f-lh18">
            <b v-if="peopleData.LEFT_SBP">{{Number(peopleData.LEFT_SBP)}}</b>
            <b v-else-if="peopleData.RIGHT_SBP">{{Number(peopleData.RIGHT_SBP)}}</b>
            <b v-else>--</b>
          </span>/<span class="f-lh18">
            <b v-if="peopleData.LEFT_DBP">{{Number(peopleData.LEFT_DBP)}}mmHg</b>
            <b v-else-if="peopleData.RIGHT_DBP">{{Number(peopleData.RIGHT_DBP)}}mmHg</b>
            <b v-else>--</b>
          </span>
        </p>
      </div>
      <div class="list-item">
        <img src="../../assets/img/jkda/quota-icon7.png">
        <p>血糖</p>
        <p class="f-lh18">
          <span v-if="peopleData.FBG_VALUE">{{peopleData.FBG_VALUE}}mmol/l</span>
          <span v-else>--</span>
        </p>
      </div>
      <div class="list-item">
        <img src="../../assets/img/jkda/quota-icon8.png">
        <p>血脂</p>
        <p class="f-lh18">
          <span v-if="peopleData.CHOL_VALUE">{{peopleData.CHOL_VALUE}}mmol/L</span>
          <span v-else>--</span>
        </p>
      </div>
      <div class="list-item">
        <img src="../../assets/img/jkda/quota-icon9.png">
        <p>视力</p>
        <p class="f-lh18">
          <span v-if="peopleData.LEFT_EYE_VISION">L{{peopleData.LEFT_EYE_VISION}} R{{peopleData.RIGHT_EYE_VISION}}</span>
          <span v-else>--</span>
        </p>
        <p></p>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jkda_jkzb',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('健康指标')
      t.searchForm = t.queryData
      // t.searchForm.CNUM = '441322198706196062'
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
          serverId: 'WXBM00055',
          url: t.serverPath2,
          params: t.searchForm,
          success: function(data) {
            t.peopleData = data[0].CONTENTS[0]
            console.log(t.peopleData)
          }
        })
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jkda_jkzb .list-wrap {
    border: 1PX solid #ebeef5;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    border-radius: 5px;
    font-size: 14px;
    color: #606266;
  }

  .jkda_jkzb .list-wrap .list-item {
    width: 33.3%;
    border-bottom: 1PX solid #ebeef5;
    border-right: 1PX solid #ebeef5;
    text-align: center;
    padding: 20px 0;
    line-height: 18px;
  }

  .jkda_jkzb .list-wrap .list-item.gh-item {
    height: 120px;
  }

  .jkda_jkzb .list-wrap .list-item img {
    width: 30px;
    margin-bottom: 6px;
  }

  .jkda_jkzb .list-wrap .list-item:nth-child(3n) {
    border-right: 0;
  }

  .jkda_jkzb .list-wrap .list-item:nth-last-child(3),
  .jkda_jkzb .list-wrap .list-item:nth-last-child(2),
  .jkda_jkzb .list-wrap .list-item:nth-last-child(1) {
    border-bottom: 0;
  }
</style>