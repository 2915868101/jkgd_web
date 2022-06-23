<template>
  <div class="tjys_qy_03 g-wrap">
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
        <div class="lines on"></div>
        <div class="tit on">选择机构</div>
      </div>
      <div class="steps-item f-df8 on">
        <div class="steps">3</div>
        <div class="lines"></div>
        <div class="tit">签约确认</div>
      </div>
    </div>
    <div class="register-wrap"> 
      <van-cell title="签约居民" value-class="s-c303133" :value="initForm.XM"></van-cell>
      <van-cell title="身份证号码" value-class="s-c303133" :value="initForm.ZJHM"></van-cell>
      <van-cell title="现住地址" value-class="s-c303133" :value="initForm.HKDZ"></van-cell>
      <van-cell title="联系电话" value-class="s-c303133" :value="initForm.PHONE"></van-cell>
    </div>
    <div class="register-wrap f-mt10 s-bgcfff">
      <van-cell title="签约机构" value-class="s-c303133" :value="initForm.SIGN_ORG">
      </van-cell>
      <van-cell title="家庭医生" value-class="s-c303133" :value="initForm.SIGN_DOCTOR"> </van-cell>
      <van-cell title="服务包" value-class="s-c303133" :value="initForm.TYPE">
      </van-cell> 
      <van-cell title="签约时间段" value-class="s-c303133" :value="initForm.SIGN_TIME">
      </van-cell> 
      <van-cell title="紧急联系人" value-class="s-c303133" :value="initForm.URGENT_CONTACT"></van-cell>
      <van-cell title="与签约人关系" value-class="s-c303133" :value="initForm.URGENT_CONTACT_RELATE">
      </van-cell>
      <van-cell title="紧急联系人电话" value-class="s-c303133" :value="initForm.URGENT_CONTACT_PHONE"></van-cell> 
      <van-checkbox class="cell-def" checked-color="#00c792" v-model="is_def" @change="changeDef"><span :class="[!is_def?'s-c909399':'s-c303133']">已阅读并同意<i class="f-tdu" @click="linkToPage('jtys_fwnr',{title:'家庭医生签约协议'})">家庭医生签约协议</i>。</span></van-checkbox>
    </div>
    <div class="g-wp100 f-pb10 f-pb30 s-bgcfff">
      <van-button class="item-btn f-bs3" color="#00c792" round @click="doSave">提交申请</van-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'tjys_qy_03',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('签约信息')
    t.initForm = t.queryData
    t.initForm.OPENID = t.wx_userInfo.openid
  },
  data() {
    return {
      activeIndex: 1,
      initForm: {
        OPENID:'',
        XM: '',
        ZJHM: '',
        XZDZ: '',
        PHONE: '',
        SIGN_ORG_ID: '',
        SIGN_ORG: '',
        SIGN_DOCTOR: '',
        SIGN_DOCTOR_ATTR: '',
        SIGN_PHONE: '',
        SIGN_TIME: '',
        TYPE: '',
        URGENT_CONTACT: '',
        URGENT_CONTACT_RELATE: '',
        URGENT_CONTACT_PHONE: '', 
      },
      is_def: true,
    }
  },
  methods: {
    changeDef(val) {
      this.is_def = val
    },
    doValidate(cb) {
      var t = this
      if (!t.is_def) {
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
    doSave() {
      var t = this
      console.log(t.initForm)
      setTimeout(function() {
        t.doValidate(function() {
          t.ajax({
            serverId: 'BMFW00020',
            url: t.serverPath1,
            params:t.initForm,
            success: function(data) {
              console.log(data)
              t.linkToPage('jtys_qy_wc')
            }
          })
        })
      }, 50)
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}


.tjys_qy_03 .g-wp100>>>.van-button {
  width: 90%;
  margin: 20px 5% 0;
  height: 50px;
  padding: 0 10px;
}

.tjys_qy_03>>>.van-cell {
  padding: 15px 20px;
  border-bottom: 1PX solid #ebeef5;
}
.tjys_qy_03>>>.van-cell__title {color: #606266;}
.tjys_qy_03>>>.van-cell__value {color: #303133;}

.tjys_qy_03>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.tjys_qy_03>>>.van-field__button .van-button--small {
  height: 20px;
  min-width: 20px;
}

.tjys_qy_03 .tips-wrap {
  color: #c0c4cc;
  font-size: 14px;
  padding: 15px;
}

.tjys_qy_03 .steps-wrap {
  background: #f4f9f9;
  padding: 15px;
  font-size: 14px;
  display: flex;
  color: #909399;
}

.tjys_qy_03 .steps-wrap .steps-item .steps {
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

.tjys_qy_03 .steps-wrap .steps-item .lines {
  border-bottom: 1PX solid #dde0e7;
  position: relative;
  top: -22px;
  left: 50%;
}

.tjys_qy_03 .steps-wrap .steps-item.on .steps,
.tjys_qy_03 .steps-wrap .lines.on,
.tjys_qy_03 .steps-wrap .tit.on {
  border-color: #00c792;
  color: #00c792;
}

.tjys_qy_03 .steps-wrap .steps-item:nth-last-child(1) .lines {
  border: 0;
}

.tjys_qy_03 .steps-wrap .steps-item {
  text-align: center;

}

.tjys_qy_03 .cell-def {
  font-size: 14px;
  padding: 15px;
  color: #c7c7c7;
}

.tjys_qy_03 .cell-def .van-checkbox__label {
  color: #c0c4cc
}
</style>