<template>
  <div class="tjys_qy_02 g-wrap g-hp100 s-bgcfff">
    <van-notice-bar class="f-fs13" wrapable :scrollable="false">
      实名制签约家庭医生，请确认信息正确无误，否则无法 完成签约服务。
    </van-notice-bar>
    <div class="steps-wrap">
      <div class="steps-item f-df8 on">
        <div class="steps">1</div>
        <div class="lines on"></div>
        <div class="tit on">确认资料</div>
      </div>
      <div class="steps-item f-df8 on">
        <div class="steps">2</div>
        <div class="lines"></div>
        <div class="tit">选择机构</div>
      </div>
      <div class="steps-item f-df8">
        <div class="steps">3</div>
        <div class="lines"></div>
        <div class="tit">签约确认</div>
      </div>
    </div>
    <div class="register-wrap">
      <van-cell title="签约机构" value-class="s-c303133" is-link @click="linkToPage('jtys_qy_org',initForm)">
        <span class="s-c303133" v-if="initForm.SIGN_ORG">{{initForm.SIGN_ORG}}</span>
        <span v-else class="s-c909399">请选择</span>
      </van-cell>
      <van-cell title="家庭医生" value-class="s-c303133">
        <span class="s-c303133" v-if="initForm.SIGN_DOCTOR">{{initForm.SIGN_DOCTOR}} 医生</span>
        <span v-else class="s-c909399"></span>
      </van-cell>
      <van-cell title="请选择服务包" value-class="s-c303133" is-link @click="linkToPage('jtys_qy_fwb',initForm)">
        <span class="s-c303133" v-if="initForm.TYPE">{{initForm.TYPE}}</span>
        <span v-else class="s-c909399">请选择</span>
      </van-cell>
      <van-cell title="签约周期" value-class="s-c303133">
        <span class="s-c303133" v-if="initForm.SIGN_TIME">{{initForm.SIGN_TIME}}</span>
        <span v-else class="s-c909399">请选择</span>
      </van-cell>
      <van-field input-align="right" clearable v-model="initForm.URGENT_CONTACT" label="紧急联系人" placeholder="请输入紧急联系人" />
      <van-cell title="与签约人关系" value-class="s-c303133" is-link @click="showPicker2 = true">
        <span class="s-c303133" v-if="initForm.URGENT_CONTACT_RELATE">{{initForm.URGENT_CONTACT_RELATE}}</span>
        <span v-else class="s-c909399">请选择</span>
      </van-cell>
      <van-popup v-model="showPicker2" position="bottom">
        <van-picker show-toolbar :columns="columns2" @cancel="showPicker2 = false" @confirm="onConfirm2" />
      </van-popup>
      <van-field input-align="right" clearable v-model="initForm.URGENT_CONTACT_PHONE" label="联系电话" maxlength="11" placeholder="请输入手机号码" />
      <van-checkbox class="cell-def" checked-color="#00c792" v-model="is_def" @change="changeDef"> <span :class="[!is_def?'s-c909399':'s-c303133']">已阅读并同意<i class="f-tdu" @click="toQYXY">家庭医生签约协议</i>。</span></van-checkbox>
    </div>
    <div class="g-wp100 f-pb30 s-bgcfff">
      <van-button class="item-btn f-bs3" color="#00c792" round @click="toQy02">下一步</van-button>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'tjys_qy_02',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('签约信息')
      t.initForm.XM = t.queryData.XM
      t.initForm.ZJHM = t.queryData.ZJHM
      t.initForm.PHONE = t.queryData.PHONE
      t.initForm.HKDZ = t.queryData.HKDZ || ''
      t.initForm.SIGN_ORG_ID = t.queryData.SIGN_ORG_ID
      t.initForm.SIGN_ORG = t.queryData.SIGN_ORG
      t.initForm.SIGN_DOCTOR = t.queryData.SIGN_DOCTOR
      t.initForm.SIGN_DOCTOR_ATTR = t.queryData.SIGN_DOCTOR_ATTR || ''
      t.initForm.SIGN_PHONE = t.queryData.SIGN_PHONE
      t.initForm.TYPE = t.queryData.TYPE || ''
      t.initForm.SIGN_ADDRESS = t.queryData.SIGN_ADDRESS
      t.initForm.URGENT_CONTACT = t.queryData.URGENT_CONTACT
      t.initForm.URGENT_CONTACT_RELATE = t.queryData.URGENT_CONTACT_RELATE
      t.initForm.URGENT_CONTACT_PHONE = t.queryData.URGENT_CONTACT_PHONE
      t.initForm.AREA = String(t.queryData.AREA).split('/')[1]
      var store_initForm = t.$store.state.store_jtys.initForm
      if (store_initForm) {
        t.initForm.URGENT_CONTACT = store_initForm.URGENT_CONTACT
        t.initForm.URGENT_CONTACT_RELATE = store_initForm.URGENT_CONTACT_RELATE
        t.initForm.URGENT_CONTACT_PHONE = store_initForm.URGENT_CONTACT_PHONE
      }
    },
    data() {
      return {
        activeIndex: 1,
        initForm: {
          XM: '',
          ZJHM: '',
          HKDZ: '',
          PHONE: '',
          SIGN_ORG_ID: '',
          SIGN_ORG: '',
          SIGN_DOCTOR: '',
          SIGN_DOCTOR_ATTR: 'tuanduiid',
          SIGN_PHONE: '',
          TYPE: '',
          SIGN_TIME: '1年',
          SIGN_ADDRESS: '',
          URGENT_CONTACT: '',
          URGENT_CONTACT_RELATE: '',
          URGENT_CONTACT_PHONE: '',
          AREA: ''
        },
        is_def: false,
        showPicker2: false,
        columns2: ['父亲', '母亲', '兄弟', '姐妹'],
      }
    },
    methods: {
      toQYXY() {
        var t = this
        t.$store.state.store_jtys.initForm = t.initForm
        t.linkToPage('jtys_fwnr', {
          title: '家庭医生签约协议'
        })
      },
      changeDef(val) {
        this.is_def = val
      },
      doValidate(cb) {
        var t = this
        if (!t.initForm.SIGN_ORG) {
          t.$dialog.alert({
            title: '提示',
            message: '签约机构不能为空'
          })
          return false
        } else if (!t.initForm.SIGN_DOCTOR) {
          t.$dialog.alert({
            title: '提示',
            message: '家庭医生不能为空'
          })
          return false
        } else if (!t.initForm.TYPE) {
          t.$dialog.alert({
            title: '提示',
            message: '服务包不能为空'
          })
          return false
        } else if (!t.initForm.URGENT_CONTACT) {
          t.$dialog.alert({
            title: '提示',
            message: '紧急联系人不能为空'
          })
          return false
        } else if (!t.initForm.URGENT_CONTACT_RELATE) {
          t.$dialog.alert({
            title: '提示',
            message: '与签约人关系不能为空'
          })
          return false
        } else if (!t.initForm.URGENT_CONTACT_PHONE) {
          t.$dialog.alert({
            title: '提示',
            message: '紧急联系人电话不能为空'
          })
          return false
        } else if (!t.$store.state.rules.mobile.test(t.initForm.URGENT_CONTACT_PHONE)) {
          t.$dialog.alert({
            title: '提示',
            message: '请填写真实有效的手机号'
          })
          return false
        } else if (!t.is_def) {
          t.$dialog.alert({
            title: '提示',
            message: '未同意家庭医生签约协议'
          })
          return false
        } else {
          if (typeof cb == 'function') {
            cb()
          }
        }
      },
      toQy02() {
        var t = this
        setTimeout(function() {
          t.doValidate(function() {
            console.log(t.initForm)
            t.linkToPage('jtys_qy_03', t.initForm)
          })
        }, 50)
      },
      onConfirm2(val) {
        var t = this;
        t.initForm.URGENT_CONTACT_RELATE = val;
        t.showPicker2 = false;
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}


  .tjys_qy_02 .g-wp100>>>.van-button {
    width: 90%;
    margin: 20px 5% 0;
    height: 50px;
    padding: 0 10px;
  }

  .tjys_qy_02>>>.van-cell {
    padding: 15px 20px;
    border-bottom: 1PX solid #ebeef5;
  }

  .tjys_qy_02>>>.van-cell__title {
    color: #606266
  }

  .tjys_qy_02>>>.van-cell__value {
    color: #303133;
  }

  .tjys_qy_02>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .tjys_qy_02>>>.van-field__button .van-button--small {
    height: 20px;
    min-width: 20px;
  }

  .tjys_qy_02 .tips-wrap {
    color: #c0c4cc;
    font-size: 14px;
    padding: 15px;
  }

  .tjys_qy_02 .steps-wrap {
    background: #f4f9f9;
    padding: 15px;
    font-size: 14px;
    display: flex;
    color: #909399;
  }

  .tjys_qy_02 .steps-wrap .steps-item .steps {
    position: relative;
    z-index: 2;
    background: #fff;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    line-height: 22px;
    font-size: 13px;
    margin: 0 auto 10px;
    border: 1PX solid #dde0e7;
  }

  .tjys_qy_02 .steps-wrap .steps-item .lines {
    border-bottom: 1PX solid #dde0e7;
    position: relative;
    top: -22px;
    left: 50%;
  }

    {}

  .tjys_qy_02 .steps-wrap .steps-item.on .steps,
  .tjys_qy_02 .steps-wrap .lines.on,
  .tjys_qy_02 .steps-wrap .tit.on {
    border-color: #00c792;
    color: #00c792;
  }

  .tjys_qy_02 .steps-wrap .steps-item:nth-last-child(1) .lines {
    border: 0;
  }

  .tjys_qy_02 .steps-wrap .steps-item {
    text-align: center;

  }

  .tjys_qy_02 .cell-def {
    font-size: 14px;
    padding: 15px;
    color: #c7c7c7;
  }

  .tjys_qy_02 .cell-def .van-checkbox__label {
    color: #c0c4cc
  }
</style>