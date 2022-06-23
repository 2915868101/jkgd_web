<template>
  <div class="xgymyy_index g-wrap s-bgcfff">
    <div class="banner-wrap">
      <img class="g-wp100" src="../../assets/img/xgymyy/banner.png">
    </div>
    <div>
      <van-field input-align="right">
        <template #label>
          <span :class="[reserveForm.PERSONTYPE?'s-c909399':'s-c303133']">疫苗接种类型</span>
        </template>
        <template #input>
          <van-radio-group v-model="reserveForm.PERSONTYPE" direction="horizontal">
            <van-radio name="成人" checked-color="#00c792">成人</van-radio>
            <van-radio name="儿童" checked-color="#00c792">儿童</van-radio>
          </van-radio-group>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="reserveForm.NAME" placeholder="请输入接种人真实姓名">
        <template #label>
          <span :class="[reserveForm.NAME?'s-c909399':'s-c303133']">姓名</span>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="reserveForm.CARDNUM" placeholder="请填写身份证号码" @blur="onBlurCnumInput">
        <template #label>
          <span :class="[reserveForm.CARDNUM?'s-c909399':'s-c303133']">身份证号码</span>
        </template>
      </van-field>
      <van-field input-align="right">
        <template #label>
          <span :class="[reserveForm.SEX?'s-c909399':'s-c303133']">性别</span>
        </template>
        <template #input>
          <van-radio-group v-model="reserveForm.SEX" direction="horizontal">
            <van-radio name="1" checked-color="#00c792" disabled>男</van-radio>
            <van-radio name="2" checked-color="#00c792" disabled>女</van-radio>
          </van-radio-group>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="reserveForm.BIRTHDAY" placeholder="请填写">
        <template #label>
          <span :class="[reserveForm.BIRTHDAY?'s-c909399':'s-c303133']">出生日期</span>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="reserveForm.SJHM" placeholder="请填写手机号码" @blur="onBlurCnumInput">
        <template #label>
          <span :class="[reserveForm.SJHM?'s-c909399':'s-c303133']">手机号码</span>
        </template>
      </van-field>
      <van-field input-align="right" clearable v-model="reserveForm.COMPANY" placeholder="请填写工作单位/学校">
        <template #label>
          <span :class="[reserveForm.COMPANY?'s-c909399':'s-c303133']">工作单位/学校</span>
        </template>
      </van-field>
      <van-field input-align="right" v-model="reserveForm.DZ" @click="showPicker=true">
        <template #label>
          <span :class="[reserveForm.DZ?'s-c909399':'s-c303133']">现居地址</span>
        </template>
        <template #input>
          <span :class="[{'s-c909399':!reserveForm.DZ}]" v-if="!reserveForm.DZ">请选择 </span>
          <span :class="[{'s-c303133':reserveForm.DZ}]" v-if="reserveForm.DZ">{{reserveForm.DZ}} </span>
          <van-icon class="f-pl5 s-c909399" name="arrow" />
        </template>
      </van-field>
      <van-popup v-model="showPicker" position="bottom">
        <van-area :area-list="areaList" value="341822" @cancel="showPicker=false" @confirm="onConfirmArea" />
      </van-popup>
      <van-field input-align="right" clearable v-model="reserveForm.XXDZ" placeholder="请填写详细地址">
        <template #label>
          <span :class="[reserveForm.XXDZ?'s-c909399':'s-c303133']">详细地址</span>
        </template>
      </van-field>
    </div>
    <div class="g-wp100 f-pt20 s-bgcfff">
      <van-button class="item-btn f-bs3" round color="#00c792" @click="doSubmit">确认</van-button>
    </div>
    <van-dialog v-model="show" @confirm="onConfirmInfo" :show-confirm-button="false">
      <div class="f-tal f-pl15 f-pr15 f-pt20 f-pb10">
        <div class="s-c303133 f-mb10">用户隐私条款</div>
        <div class="s-cffa54d f-mb10">登记前请仔细阅读用户隐私条款</div>
        <div class="info-box f-fs14 f-lh18 f-mb10 ">
          <p class="f-mb10">我们致力于保护您在使用我们网站时所提供的私隐、私人资料以及个人的资料(统称“个人资料")，使我们在收集、使用、储存和传送个人资料方面符合(与个人资料私隐有关的法律法规)及消费者保护方面的最高标准。为确保您对我们在处理个人资料上有充分信心，您切要详细阅读及理解隐私政策的条文。
          </p>
          <p class="f-mb10">特别是您一旦使用我们的网站，将被视为接受、同意、承诺和确认;您在自愿下连同所需的同意向我们披露个人资料;您会遵守本隐私政策的全部条款和限制;您在我们的网站上作登记、资料会被收集;您同意日后我们对隐私政策的任何修改;您同意我们的分公司、附属公司、雇员、就您可能会感兴趣的产品和服务与您联络(除非您已经表示不想收到该等讯息)。被收集的个人资料的种类经您的同意，我们会收集、管理和监控个人资料。
          </p>
          <p class="f-mb10">为了向您提供我们的各项服务，您需要提供个人资料信息，其中包括个人资料和不具名的资料，包括但不限于:个人资料（您的姓名、性别、年龄、出生日期、电话号码、传真号码、住址或通讯地址、电子邮箱地址）。
          </p>
          <p class="f-mb10">收集个人资料及不具名的资料的目的及用途如下:通过我们的网站向您提供我们的各项服务;当您使用我们的网站时，能辨认以及确认您的身份;让您使用我们的网站时得到为您而设的服务;我们的顾客服务人员有需要时可以与您联系;统计我们网站使用量的数据;让您在使用我们网站时更方便;为改进我们的产品、服务及网站内容而进行市场研究调查;为我们搞的活动、市场销售和推广计划收集资料;遵守法律、政府和监管机关的规定，包括但不限于对个人资料披露及通知的规定;就我们提供的各项服务、分析、核对或审查您的信用、付款或地位;处理在您要求下的任何付款指示，直接扣帐或信用安排;使您能运作您的账户以及使我们能从账户支取尚欠的服务费;您提供给我们的个人资料及不具名资料，只保留到搜集的目的已达到的时候，除非应适用的法律法规之规定而继续保留。个人资料的拥有权及披露在我们网站上所搜集的一切资料都由我们所拥有，不会出租或出售给任何无关的第三方。
          </p>
          <p class="f-mb10">您有权:查询我们是否持有您的任何个人资料;接达我们所持有的您的个人资料;要求我们更正任何不正确的个人资料;不时地征询有关我们所持有的个人资料的性质，政策和执行方法;然而在法律允许的极端有限的情况下，我们可以不允许您接达您的个人资料，例如:如您接达及得到您个人资料可能会对您有危险;当您的个人资料可能会影响一项正在进行的调查;当您的个人资料涉及到法庭程序，并且可能受到发现的限制;当您的个人资料涉及一项商业上敏感的决策过程;当另外一倜人的个人资料也包含在同一份记录中;若您欲接达或更正个人资料，或索取有关个人资料的政策、执行方法和被持有的个人资料的种类，应致函到我们的下列的地址;要求接达或更正资料可能要付合理的处理费用;安全保管您的密码，除了我们致力确保您的个人资料存放和处理的安全外，您不应向任何人披露您的登录密码或帐户资料，以保护您的个人资料。
            每当您登录我们网站时，尤其是当您使用他人的电脑或者是公共的互联网终端机时，请记着操作完毕后一定要点击退出。
          </p>
          <p class="f-mb10">您的努力和协助对于我们保护您的个人资料绝对有帮助。</p>
          <p class="f-mb10"> 隐私政策的修改:本隐私政策可以不时(无需事先向您通知)被修改。任何对隐私政策的修改都会刊登在我们网站上。</p>
        </div>
        <div class="">
          <van-checkbox v-model="checked" checked-color="#00c792">我已阅读并同意<span class="s-c00c792">《用户隐私条款》</span></van-checkbox>
        </div>
      </div>
      <div class="g-wp100">
        <van-button class="item-btn f-bs3" round color="#00c792" @click="onConfirmInfo">确定</van-button>
      </div>
    </van-dialog>
  </div>
</template>
<script>
import areaList from '@/assets/js/util/arealist.js'
export default {
  name: 'xgymyy_index',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle('新冠疫苗预约')
    // 判断是否注册 
    t.reserveForm.OPENID = t.wx_userInfo.openid
    if (!t.queryData.ismanual) {
      t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function(data) {
        t.reserveForm.NAME = data.CONTENTS[0].NAME
        t.reserveForm.CARDNUM = data.CONTENTS[0].CARD_NUMBER
        t.reserveForm.SEX = t.IdCard(t.reserveForm.CARDNUM, 2) == '男' ? '1' : '2'
        t.reserveForm.BIRTHDAY = t.IdCard(t.reserveForm.CARDNUM, 1)
        t.reserveForm.PERSONTYPE = t.IdCard(t.reserveForm.CARDNUM, 3) > 18 ? '成人' : '儿童'
        t.reserveForm.SJHM = data.CONTENTS[0].SJHM
        t.reserveForm.XXDZ = data.CONTENTS[0].HKDZ || ''
      })
    }
    t.areaList = areaList
  },
  data() {
    return {
      checked: false,
      show: true,
      reserveForm: {
        OPENID: '',
        PERSONTYPE: '',
        NAME: '',
        CARDNUM: '',
        SEX: '',
        SJHM: '',
        BIRTHDAY: '',
        COMPANY: '',
        DZ: '',
        XXDZ: '',
        COUNTRY: ''
      },
      showPicker: false,
      areaList: ''
    }
  },
  methods: {
    onConfirmInfo() {
      let t = this
      console.log(t.checked)
      if (t.checked) {
        t.show = false
      } else {
        t.$toast('未同意用户隐私协议')
      }
    },
    onBlurCnumInput() {
      var t = this
      if (t.regIdCard(t.reserveForm.CARDNUM)) {
        t.reserveForm.SEX = t.IdCard(t.reserveForm.CARDNUM, 2) == '男' ? '1' : '2'
        t.reserveForm.BIRTHDAY = t.IdCard(t.reserveForm.CARDNUM, 1)
        console.log(t.reserveForm.SEX)
      } else {
        t.$toast('身份证号码错误')
      }
    },
    onConfirmArea(v) {
      var t = this
      t.reserveForm.DZ = ''
      for (let c of v) {
        t.reserveForm.DZ += c.name
      }
      t.showPicker = false
    },
    doSubmit() {
      let t = this
      setTimeout(function() {
        t.doValidate(function() {
          t.ajax({
            loading: false,
            serverId: 'WXGDYYGHTZH05',
            url: t.serverPath1,
            params: t.reserveForm,
            success: function(data) {
              console.log(data)
              if (data[0].CODE == 0) {
                t.$toast(data[0].MSG)
                setTimeout(function() {
                  t.linkToPage('xgymyy_qryy')
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
      if (!t.reserveForm.PERSONTYPE) {
        t.$dialog.alert({
          title: '提示',
          message: '请选择疫苗接种类型'
        })
        return false
      } else if (!t.reserveForm.NAME) {
        t.$dialog.alert({
          title: '提示',
          message: '姓名不能为空'
        })
        return false
      } else if (!t.reserveForm.CARDNUM) {
        t.$dialog.alert({
          title: '提示',
          message: '身份证号码不能为空'
        })
        return false
      } else if (!t.reserveForm.SJHM) {
        t.$toast('手机号不能为空')
        return false
      } else if (!t.$store.state.rules.mobile.test(t.reserveForm.SJHM)) {
        t.$toast('请填写真实有效的手机号')
        return false
      } else if (!t.reserveForm.COMPANY) {
        t.$dialog.alert({
          title: '提示',
          message: '工作单位/学校不能为空'
        })
        return false
      } else if (!t.reserveForm.DZ) {
        t.$dialog.alert({
          title: '提示',
          message: '现居地址不能为空'
        })
        return false
      } else if (!t.reserveForm.XXDZ) {
        t.$dialog.alert({
          title: '提示',
          message: '详细地址不能为空'
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

.xgymyy_index .banner-wrap {
  padding: 15px 18px;
  text-align: center;
}

.xgymyy_index>>>.van-cell::after {
  border-bottom: 1px solid #ebedf0;
}

.xgymyy_index>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  padding: 0 10px;
}

.xgymyy_index .info-box {
  height: 300px;
  overflow-y: auto;
  width: 100%;
  text-indent: 2em;
}

/*vant 样式*/
.xgymyy_index>>>.van-cell::after {
  border: 0;
}

.xgymyy_index>>>.van-cell {
  border-bottom: 1px solid #ebeef5;
}

.xgymyy_index>>>.van-cell__title {
  width: 100px !important;
}

.xgymyy_index>>>.van-radio--horizontal {
  margin: 0 0 0 10px !important;
}

.xgymyy_index>>>.van-picker__toolbar {
  background: #f4f9f9;
}

.xgymyy_index>>>.van-picker__toolbar .van-picker__cancel {
  color: #303133;
}

.xgymyy_index>>>.van-picker__toolbar .van-picker__confirm {
  color: #00c792;
}
</style>