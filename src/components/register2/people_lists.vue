<template>
  <div class="people_lists g-wrap s-bgcfff g-hp100">
    <div class="people-list s-bgcfff">
      <!-- <div class="list-item list1" v-for="item in patientList" :key="item.CNUM" @click="toPeopleDetail(item)">
        <div class="c-item c-01">XX省(市)卫生健康委员会</div>
        <div class="c-item c-02">就诊人</div>
        <div class="c-item c-03">{{item.XM}} <span class="tips s-bgc00c792 s-cfff" v-if="item.BZ=='1'">默认</span></div>
        <div class="c-item c-04">{{regNumber(item.CNUM)}}</div>
        <div class="c-item c-05">中华人民共和国国家卫生健康委员会监制</div>
      </div>  -->
      <div class="list-item s-cfff list2" v-for="item in patientList" :key="item.CNUM" @click="toPeopleDetail(item)">
        <!-- <div class="c-item c-02">惠州市人民医院</div> -->
        <div class="c-item c-03">{{item.XM}} <span class="tips s-bgcffcb2c s-c303133" v-if="item.BZ=='1'">默认</span></div>
        <div class="c-item c-04">{{regNumber(item.CNUM,4)}}</div>
      </div>
    </div>
    <div class="g-wp100 s-bgcfff f-pt20">
      <van-button plain color="#00c792" @click="linkToPage('register_card',{isRegister:true})">
        <van-icon class="f-mr10" name="plus" />添加就诊人</van-button>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'people_lists',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('就诊人管理')
      console.log(t.wx_userInfo)
      t.searchForm.OPENID = t.wx_userInfo.openid
      t.getPatientList()
    },
    data() {
      return {
        patientList: [],
        searchForm: {
          OPENID: '',
          FLAG: 'Query'
        }
      }
    },
    methods: {
      getPatientList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00014',
          url: t.serverPath1,
          params: t.searchForm,
          success: function(data) {
            t.patientList = data[0].CONTENTS
            console.log(t.patientList)
          }
        })
      },
      toPeopleDetail(item) {
        this.linkToPage('people_detail', item)
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .people_lists>>>.van-button {
    font-size: 16px;
    border-radius: 5px;
    width: 90%;
    margin: 20px 5% 30px;
  }

  .people_lists>>>.van-icon {
    vertical-align: -2px;
  }

  .people_lists .people-list {
    padding: 0 20px;
  }

  .people_lists .people-list .list-item {
    position: relative;
    width: 100%;
    height: 180px;
    border-radius: 5px;
    margin-top: 20px;
  }

  .people_lists .people-list .list-item.list1 {
    background: url(../../assets/img/register/cardnewbg1.jpg) no-repeat;
    background-size: 100%;
  }

  .people_lists .people-list .list-item.list2 {
    background: url(../../assets/img/register/cardnewbg2.jpg) no-repeat;
    background-size: 100%;
  }

  .people_lists .people-list .list-item .c-item {
    position: absolute;
    color: #000;
    font-size: 14px;
  }

  .people_lists .people-list .list-item.list2 .c-item {
    color: #fff;
  }

  .people_lists .people-list .list-item .c-01 {
    top: 20px;
    font-size: 12px;
    left: 20px;
  }

  .people_lists .people-list .list-item .c-02 {
    top: 20px;
    right: 20px;
    font-size: 16px;
  }

  .people_lists .people-list .list-item .c-03 {
    top: 90px;
    font-size: 16px;
    left: 20px;
  }

  .people_lists .people-list .list-item .c-03 .tips {
    padding: 2px 4px;
    margin-left: 6px;
    font-size: 11px;
    vertical-align: 1px;
    border-radius: 3px;
  }

  .people_lists .people-list .list-item .c-04 {
    top: 115px;
    font-size: 15px;
    left: 20px;
  }

  .people_lists .people-list .list-item .c-05 {
    bottom: 15px;
    width: 100%;
    text-align: center;
    font-size: 12px;
  }
</style>