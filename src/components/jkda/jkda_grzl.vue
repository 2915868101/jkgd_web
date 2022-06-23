<template>
  <div class="jkda_grzl g-wrap g-hp100">
    <div class="table-wrap s-bgcfff">
      <table class="s-bgcfff">
        <tr>
          <td class="tit">姓名</td>
          <td class="cont">{{peopleData.XM||peopleInfo.NAME}}</td>
          <td class="tit">性别</td>
          <td class="cont" v-if="peopleData.SEX_NAME">{{peopleData.SEX_NAME}}</td>
          <td class="cont" v-else>{{peopleInfo.XB=='1'?'男':'女'}}</td>
        </tr>
        <tr>
          <td class="tit">出生日期</td>
          <td class="cont">
            <span v-if="peopleData.BORN_DATE">{{$moment(peopleData.BORN_DATE).format('YYYY-MM-DD')}}</span>
            <span v-else-if="peopleInfo.CARD_NUMBER">{{IdCard(peopleInfo.CARD_NUMBER,1)}}</span>
            <span v-else>--</span>
          </td>
          <td class="tit">婚姻状况</td>
          <td class="cont">{{peopleData.MARITAL_STATUS_NAME || '--'}}</td>
        </tr>
        <tr>
          <td class="tit">民族</td>
          <td class="cont">{{peopleData.NATION_NAME || peopleInfo.MZ || '--'}}</td>
          <td class="tit">教育程度</td>
          <td class="cont">{{peopleData.EDUCATION || '--'}}</td>
        </tr>
        <tr>
          <td class="tit">联系电话</td>
          <td class="cont" colspan="3">{{peopleData.TELEPHONE || '--'}}</td>
        </tr>
        <tr>
          <td class="tit">身份证号</td>
          <td class="cont" colspan="3">{{peopleData.ID_NUM || peopleInfo.CARD_NUMBER || '--'}}</td>
        </tr>
        <tr>
          <td class="tit">联系地址</td>
          <td class="cont" colspan="3">{{peopleData.ADDRESS || '--'}}</td>
        </tr>
        <tr>
          <td class="tit">工作单位</td>
          <td class="cont" colspan="3">{{peopleData.WORKING_ORG || '--'}}</td>
        </tr>
        <tr v-show="peopleData.FAMILY==[]">
          <td class="tit">家庭成员</td>
          <td class="cont" colspan="3">
            <div class="flex-item" v-for="(c,i) in peopleData.FAMILY" :key="i">
              <div class="f-df4">{{c.RELATION}}</div>
              <div class="f-df4">{{c.XM}}</div>
              <div class="f-df16">{{c.ID_NUM}}</div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jkda_grzl',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('个人基础资料')
      t.peopleInfo = t.queryData.data
      t.token = t.queryData.token
      t.searchForm.CNUM = t.peopleInfo.CARD_NUMBER
      t.getPeopleData()
    },
    data() {
      return {
        searchForm: {
          CNUM: ''
        },
        peopleData: '',
        token: '',
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
            TOKEN: t.token
          },
          success: function(data) {
            console.log(data)
            console.log(t.token)
            t.peopleData = data[0]
            // console.log(t.peopleData)
          }
        })
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jkda_grzl {
    padding: 15px;
    font-size: 14px;
    background: url(../../assets/img/jkda/grzl-bg.jpg);
    background-size: 100% 100%;
  }

  .jkda_grzl .table-wrap {
    border-radius: 5px;
    overflow: hidden;
  }

  .jkda_grzl .table-wrap table {
    width: 100%;
  }

  .jkda_grzl .table-wrap table td {
    border: 1PX solid #ebeef5;
  }

  .jkda_grzl .table-wrap table td.cont {
    width: 100px;
  }

  .jkda_grzl .table-wrap td {
    line-height: 20px;
    height: 50px;
  }

  .jkda_grzl .table-wrap .tit {
    text-align: center;
    color: #606266;
    width: 75px;
  }

  .jkda_grzl .table-wrap .cont {
    padding-left: 8px;
    text-align: left;
  }

  .jkda_grzl .table-wrap .cont .flex-item {
    display: flex;
  }
</style>