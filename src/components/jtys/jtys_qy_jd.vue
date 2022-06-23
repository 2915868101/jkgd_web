<template>
  <div class="jtys_qy_jd g-wrap f-pl15 f-pr15 f-pt15">
    <div class="progress-wrap" v-if="signedData"> 
      <div class="w-item">
        <span class="tit">签约居民：</span>
        <div class="cont f-df f-dfj">
          <span class="f-fwb">{{signedData.XM}}</span>
          <div>
            <div class="s-cffa54d" v-if="signedData.SIGN_STATE==='0'"><i class="dott-icon f-mr5 s-bgcffa54d"></i>审核中</div>
            <div class="s-c00c792" v-else><i class="dott-icon f-mr5 s-bgc00c792"></i>已通过</div>
          </div>
        </div>
      </div>
      <div class="w-item">
        <span class="tit">签约团队：</span>
        <div class="cont">{{signedData.SIGN_DOCTOR||'--'}}团队</div>
      </div>
      <div class="w-item">
        <span class="tit">签约机构：</span>
        <div class="cont">{{signedData.SIGN_ORG||'--'}}</div>
      </div>
      <div class="w-item">
        <span class="tit">机构电话：</span>
        <div class="cont">{{signedData.SIGN_PHONE||'--'}}</div>
      </div>
      <div class="w-item">
        <span class="tit">身份证号：</span>
        <div class="cont">{{regNumber(signedData.ZJHM,4)}}</div>
      </div>
      <div class="w-item">
        <span class="tit">提交时间：</span>
        <div class="cont">{{$moment(signedData.CREATEDATE).format('YYYY-MM-DD HH:mm:ss')}}</div>
      </div>
    </div>
    <van-empty v-else description="暂无申请" />
  </div>
</template>
<script>
export default {
  name: 'jtys_qy_jd',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('签约进度')
    // 判断是否注册
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function(data) {
      // 判断是否签约家庭医生
      t.getSignedData(data.CONTENTS[0].CARD_NUMBER)
    })
  },
  data() {
    return {
      signedData: '',
    }
  },
  methods: {
    getSignedData(idcard) {
      var t = this
      t.ajax({
        serverId: 'BMFW00019',
        url: t.serverPath1,
        params: {
          OPENID: t.wx_userInfo.openid,
          CARDNUM: idcard
        },
        success: function(data) {
          console.log(data)
          if (data[0].CONTENTS) {
            t.signedData = data[0].CONTENTS[0].INFO[0]
          }
        }
      })
    },
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.jtys_qy_jd .progress-wrap {
  border: 1PX solid #ebeef5;
  border-top: 0;
  font-size: 15px;
  background: #fff;
  border-radius: 5px;
  color: #303133;
}

.jtys_qy_jd .progress-wrap .w-head {
  display: flex;
  justify-content: space-between;
  padding: 20px;
}

.jtys_qy_jd .progress-wrap .dott-icon {
  display: inline-block;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  vertical-align: 3px;
}

.jtys_qy_jd .progress-wrap .w-item {
  padding: 20px;
  border-top: 1PX solid #ebeef5;
  line-height: 20px;
}

.jtys_qy_jd .progress-wrap .name {
  font-weight: bold;
  font-size: 15px;
}

.jtys_qy_jd .progress-wrap .w-item .tit {
  display: inline-block;
  float: left;
  color: #606266;
  width: 80px;
}

.jtys_qy_jd .progress-wrap .w-item .cont {
  margin-left: 80px;
}
</style>