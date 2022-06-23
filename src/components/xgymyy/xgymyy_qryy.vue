<template>
  <div class="xgymyy_qryy g-wrap s-bgcfff">
    <div class="jcxm-head f-bb10" @click="linkToPage('/xgymyy_yyjl')">
      <div class="f-fwb">预约记录</div>
      <van-icon class="f-pl5" name="arrow" />
    </div>
    <div class="box-wrap f-bb10">
      <!-- <div class="b-head"><i class="b-border f-mr8"></i>预约信息</div> -->
      <van-field input-align="right" v-model="reserveForm.NAME" @click="isShowPopup=true">
        <template #label>
          <span :class="[reserveForm.NAME?'s-c909399':'s-c303133','f-fwb']">接种人</span>
        </template>
        <template #input>
          <span :class="[{'s-c909399':!reserveForm.NAME}]" v-if="!reserveForm.NAME">请选择 </span>
          <span :class="[{'s-c303133':reserveForm.NAME}]" v-if="reserveForm.NAME">{{reserveForm.NAME}} </span>
          <van-icon class="f-pl5 s-c909399" name="arrow" />
        </template>
      </van-field>
      <van-popup v-model="isShowPopup" position="bottom" :style="{height : '30%'}">
        <div class="pop-head">
          <span>请选择接种人</span>
          <van-icon name="cross" @click.stop="isShowPopup = false"></van-icon>
        </div>
        <div class="pop-cont">
          <div class="select-list">
            <div class="select-item" v-for="item in patientList" :key="item.CNUM" @click="doSelect(item)">
              <div class="item-icon" v-if="!item.TX">
                <i class="iconfont icon-user"></i>
              </div>
              <img class="item-icon" v-else :src="item.TX" />
              <div class="item-name">{{item.NAME}}</div>
            </div>
            <div class="select-item" @click="linkToPage('xgymyy_index',{ismanual:true})">
              <div class="item-icon">
                <i class="iconfont icon-add"></i>
              </div>
              <div class="item-name">添加接种人</div>
            </div>
          </div>
        </div>
      </van-popup>
      <van-field input-align="right" v-model="reserveForm.ORG_NAME" @click="showPicker=true">
        <template #label>
          <span :class="[reserveForm.ORG_NAME?'s-c909399':'s-c303133','f-fwb']">接种机构</span>
        </template>
        <template #input>
          <span :class="[{'s-c909399':!reserveForm.ORG_NAME}]" v-if="!reserveForm.ORG_NAME">请选择 </span>
          <span :class="[{'s-c303133':reserveForm.ORG_NAME}]" v-if="reserveForm.ORG_NAME">{{reserveForm.ORG_NAME}} </span>
          <van-icon class="f-pl5 s-c909399" name="arrow" />
        </template>
      </van-field>
      <van-field input-align="right" placeholder="请填写第几次">
        <template #label>
          <span :class="[reserveForm.NUM?'s-c909399':'s-c303133','f-fwb']">第几针</span>
        </template>
        <template #input>
          <van-radio-group v-model="reserveForm.NUM" direction="horizontal">
            <van-radio name="1" checked-color="#00c792"> 第1针 </van-radio>
            <van-radio name="2" checked-color="#00c792"> 第2针 </van-radio>
          </van-radio-group>
        </template>
      </van-field>
      <van-field input-align="right" v-model="reserveForm.YYSJ" @click="showPicker1=true">
        <template #label>
          <span :class="[reserveForm.YYSJ?'s-c909399':'s-c303133','f-fwb']">预约时间</span>
        </template>
        <template #input>
          <span :class="[{'s-c909399':!reserveForm.YYSJ}]" v-if="!reserveForm.YYSJ">请选择 </span>
          <span :class="[{'s-c303133':reserveForm.YYSJ}]" v-if="reserveForm.YYSJ">{{$moment(reserveForm.YYSJ).format('YYYY-MM-DD')}} </span>
          <van-icon class="f-pl5 s-c909399" name="arrow" />
        </template>
      </van-field>
      <van-popup v-model="showPicker1" position="bottom">
        <van-datetime-picker v-model="YYSJ" type="date" title="选择年月日" @confirm="onConfirmYYSJ" />
      </van-popup>
      <van-popup v-model="showPicker" position="bottom">
        <van-picker show-toolbar :columns="columns1" @cancel="showPicker = false" @confirm="onConfirmYY" />
      </van-popup>
      <van-field input-align="right" v-model="reserveForm.YYSJd" @click="show">
        <template #label>
          <span :class="[reserveForm.YYSJd?'s-c909399':'s-c303133','f-fwb']">预约时间段</span>
        </template>
        <template #input>
          <span :class="[{'s-c909399':!reserveForm.YYSJd}]" v-if="!reserveForm.YYSJd">请选择 </span>
          <span :class="[{'s-c303133':reserveForm.YYSJd}]" v-if="reserveForm.YYSJd">{{reserveForm.YYSJd}} </span>
          <van-icon class="f-pl5 s-c909399" name="arrow" />
        </template>
      </van-field>
      <van-popup v-model="showPicker2" position="bottom" :style="{height : '30%'}">
        <div class="pop-head2">
          <span class="s-c303133">请选择预约时间</span>
          <span @click.stop="showPicker2 = false" class="s-c00c792">确定</span>
        </div>
        <div class="pop-cont">
          <div v-for="(c,i) in timeList2" :key="i">
            <van-col span="8">
              <div class="item" @click="showSelect(c)">{{c.TIMEZONE}}</div>
            </van-col>
          </div>
        </div>
      </van-popup>
    </div>
    <div class="tips-wrap s-bgcfff">
      <div class="head f-pb10 f-fwb"><i class="icon f-mr5">!</i>接种须知</div>
      <div class="cont">
        <p>①目前新冠疫苗接种年龄限制为18至59岁，其它年龄段人群需要等待进一步临床试验；</p>
        <p>②孕妇、哺乳期妇女、药物不可控的高血压、糖尿病并发症等人员不适合接种；</p>
        <p>③推荐免疫程序为2针，其间至少间隔14天。</p>
      </div>
    </div>
    <div class="g-wp100 f-pt20 s-bgcfff">
      <van-button class="item-btn f-bs3" round color="#00c792" @click="doSubmit">确认</van-button>
    </div>
  </div>
</template>
<script>
  import areaList from '@/assets/js/util/arealist.js'
  export default {
    name: 'xgymyy_qryy',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('新冠疫苗预约')
      // 判断是否注册  
      t.getHospitalList()
      t.getPeopleList()
      t.reserveForm.OPENID = t.wx_userInfo.openid
      t.areaList = areaList
    },
    data() {
      return {
        isShowPopup: false,
        reserveForm: {
          ORG_ID: '',
          ORG_NAME: '',
          NUM: '',
          YYSJ: '',
          YYSJd: '',
          NAME: '',
          SJHM: '',
          OPENID: '',
          SEX: '',
          CARDNUM: '',
        },
        YYSJ: new Date(),
        showPicker: false,
        showPicker1: false,
        showPicker2: false,
        columns1: '',
        patientList: [],
        timeList: [],
        timeList2: [{
          TIMEZONE: '08:00-09:00'
        }, {
          TIMEZONE: '09:00-10:00'
        }, {
          TIMEZONE: '10:00-11:00'
        }, {
          TIMEZONE: '14:00-15:00'
        }, {
          TIMEZONE: '15:00-16:00'
        }, {
          TIMEZONE: '16:00-17:00'
        }]
      }
    },
    methods: {
      getPeopleList() {
        let t = this
        t.ajax({
          loading: false,
          serverId: 'WXGDYYGHTZH09',
          url: t.serverPath1,
          params: {
            OPENID: t.wx_userInfo.openid
          },
          success: function(data) {
            t.patientList = data[0].CONTENTS || []
          }
        })
      },
      doSelect(item) {
        var t = this
        t.isShowPopup = false
        t.reserveForm.NAME = item.NAME
        t.reserveForm.CARDNUM = item.CARDNUM
        t.reserveForm.SEX = t.IdCard(t.reserveForm.CARDNUM, 2) == '男' ? '1' : '2'
        t.reserveForm.SJHM = item.SJHM
      },
      // 获取医院列表 
      getHospitalList() {
        var t = this
        t.ajax({
          loading: false,
          serverId: 'WXGDYYGHTZH07',
          url: t.serverPath1,
          success: function(data) {
            t.hospitalList = data[0].CONTENTS || []
            t.columns1 = Object.values(t.hospitalList).map(function(row) {
              return row.ORG_NAME
            })
          }
        })
      },
      onConfirmYYSJ() {
        let t = this
        t.reserveForm.YYSJ = t.$moment(t.YYSJ).format('YYYY-MM-DD')
        t.showPicker1 = false
      },
      onConfirmYY(val, index) {
        var t = this;
        t.reserveForm.ORG_NAME = val
        t.reserveForm.ORG_ID = t.hospitalList[index].ORG_ID
        t.showPicker = false
      },
      show() {
        var t = this
        if (!t.reserveForm.YYSJ) {
          t.$dialog.alert({
            title: '提示',
            message: '预约时间不能为空'
          })
          return false
        } else {
          t.getDoctInfo()
          t.showPicker2 = true
        }
      },
      // 获取预约时间段
      getDoctInfo() {
        var t = this
        t.ajax({
          method: 'post',
          params: {
            PAGESIZE: 1,
            CURRENTPAGE: '100',
            KSSJ: t.reserveForm.YYSJ,
            JSSJ: t.reserveForm.YYSJ,
            TYPE: ''
          },
          serverId: 'WXGD00106',
          url: t.serverPath1_1,
          success(data) {
            t.timeList = data
            console.log(789, data)
            console.log(789, t.reserveForm.YYSJ)
          }
        })
      },
      showSelect(val) {
        var t = this
        t.showPicker2 = false
        t.reserveForm.YYSJd = val.TIMEZONE
      },
      doSubmit() {
        let t = this
        setTimeout(function() {
          t.doValidate(function() {
            t.ajax({
              loading: false,
              serverId: 'WXGDYYGHTZH06',
              url: t.serverPath1,
              params: t.reserveForm,
              success: function(data) {
                console.log(t.reserveForm)
                console.log(data)
                if (data[0].CODE == 0) {
                  // t.$toast('登记成功')
                  setTimeout(function() {
                    t.linkToPage('xgymyy_yycg')
                  }, 1000)
                } else {
                  t.$toast(data[0].MSG)
                }
              }
            })

          })
        }, 50)
      },
      doValidate(cb) {
        var t = this
        if (!t.reserveForm.ORG_NAME) {
          t.$dialog.alert({
            title: '提示',
            message: '疫苗接种医院不能为空'
          })
          return false
        } else if (!t.reserveForm.NUM) {
          t.$dialog.alert({
            title: '提示',
            message: '第几次接种不能为空'
          })
          return false
        } else if (!t.reserveForm.YYSJ) {
          t.$dialog.alert({
            title: '提示',
            message: '预约时间不能为空'
          })
          return false
        } else if (!t.reserveForm.YYSJd) {
          t.$dialog.alert({
            title: '提示',
            message: '预约时间段不能为空'
          })
          return false
        } else {
          if (typeof cb == 'function') {
            cb()
          }
        }
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .xgymyy_qryy .banner-wrap {
    padding: 15px 18px;
    text-align: center;
  }

  .xgymyy_qryy>>>.van-cell::after {
    border-bottom: 1px solid #ebedf0;
  }

  .xgymyy_qryy>>>.van-button {
    width: 90%;
    margin: 20px 5%;
    height: 50px;
    padding: 0 10px;
  }

  .xgymyy_qryy .info-box {
    height: 300px;
    overflow-y: auto;
    width: 100%;
    text-indent: 2em;
  }

  /*vant 样式*/
  .xgymyy_qryy>>>.van-cell::after {
    border: 0;
  }

  .xgymyy_qryy>>>.van-cell {
    border-bottom: 1px solid #ebeef5;
  }

  .xgymyy_qryy>>>.van-cell__title {
    width: 100px !important;
  }

  .xgymyy_qryy>>>.van-radio--horizontal {
    margin: 0 0 0 10px !important;
  }

  .xgymyy_qryy>>>.van-picker__toolbar {
    background: #f4f9f9;
  }

  .xgymyy_qryy>>>.van-picker__toolbar .van-picker__cancel {
    color: #303133;
  }

  .xgymyy_qryy>>>.van-picker__toolbar .van-picker__confirm {
    color: #00c792;
  }

  /*popup 样式*/
  .xgymyy_qryy .pop-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 15px;
    color: #c0c4cc;
    padding: 15px;
  }

  .xgymyy_qryy .pop-head2 {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    padding: 15px;
    background-color: #f4f9f9;
  }

  .xgymyy_qryy .pop-cont {
    font-size: 15px;
    padding: 15px;
  }

  .xgymyy_qryy .select-list {
    display: flex;
    flex-wrap: wrap;
    text-align: center;
  }

  .xgymyy_qryy .select-list .select-item {
    width: 25%;
    margin-bottom: 15px;
  }

  .xgymyy_qryy .select-list .select-item .item-icon {
    display: inline-block;
    background: #d2f6ec;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
    margin-bottom: 8px;
  }

  .xgymyy_qryy .select-list .select-item .iconfont {
    color: #00c792;
    font-size: 28px;
  }

  .xgymyy_qryy .jcxm-head {
    background: #fff;
    padding: 15px;
    font-size: 14px;
    display: flex;
    justify-content: space-between;
  }

  .xgymyy_qryy .tips-wrap {
    font-size: 15px;
    padding: 15px;
  }

  .xgymyy_qryy .tips-wrap .icon {
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #00c792;
    color: #fff;
    text-align: center;
  }

  .xgymyy_qryy .tips-wrap .cont {
    line-height: 24px;
    padding: 10px;
    border: 1px solid #ebeef5;
  }

  .item {
    margin: 0.26667rem;
    font-size: 0.32rem;
    border: 0.02667rem solid #00c792;
    color: #00c792;
    text-align: center;
    height: 0.88rem;
    line-height: 0.88rem;
    border-radius: 0.45333rem;
  }
</style>