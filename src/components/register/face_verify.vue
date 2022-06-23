<template>
  <div class="face_verify g-wrap g-hp100 s-bgcfff">
    <div v-if="flag==1">
      <div class="f-pt30 f-pb30 f-pl30 f-pr30 f-lh18 f-fs15">
        <p class="s-c303133 f-ml20 f-mt5 f-fwb">*请拍摄您本人人脸，请保证照片清晰真实</p>
      </div>
      <div class="f-pt30 f-pl20 f-pr20 f-pb30 f-tac">
        <img class="card-img " src="../../assets/img/register/face-img.png">
      </div>
    </div>
    <div class="tips-wrap s-bgcfff f-mb10" v-else>
      <div class="item-icon g-w50 g-h50 f-brp50 f-lh50"><van-icon name="clear" color="#f57852" size="60"/></div>
      <div class="f-mb10 f-mt10 s-c303133 tit f-ti1">上传失败！</div>
      <div class="f-mb5 f-mt5 s-c999 f-fs13 f-ti1">您上传的照片无法识别，请重新上传</div> 
    </div>
    <van-button class="item-btn f-bs3 f-fs15" round color="#00c792" @click="chooseImage(wx_configParams)">
      {{flag==1?'开始拍摄':'重新拍照上传'}}
    </van-button>
  </div>
</template>
<script>
export default {
  name: 'face_verify',
  components: {
  },
  computed: {},
  created() {
    var t = this
    t.updateTitle('身份验证')
    if(t.$cookies.get('FACE_MATCH')&&t.serverType=='hz'){
      t.goBack(-2)
    }
    console.log(t.$route.fullPath)
    // 获取注册用户信息
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0,faceimg: true }, function(data) {
      t.userInfo = data.CONTENTS[0]
    })
    t.type = t.queryData.type=='match' ? 'match' : 'detect'

  },
  data() {
    return {
      userInfo: '',
      type: '',
      flag: 1
    }
  },
  methods: {
    //人脸验证接口
    faceByIMG(base64) {
      var t = this
      var sex = t.userInfo.JZXB == '1' ? 'male' : 'female'
      console.log({
        IMAGECONTENT: base64,
        TYPE: t.type,
        OPENID: t.wx_userInfo.openid,
        XM: t.userInfo.NAME,
        CNUM: t.userInfo.CARD_NUMBER,
        SEX: sex
      })
      t.ajax({
        method: 'post',
        // loading: false,
        params: {
          IMAGECONTENT: base64,
          TYPE: t.type,
          OPENID: t.wx_userInfo.openid,
          XM: t.userInfo.NAME,
          CNUM: t.userInfo.CARD_NUMBER,
          SEX: sex
        },
        serverId: 'WXBM00048',
        url: t.serverPath2,
        success(data) {
          console.log(data)
          if (data[0].CODE == 0) { 
            t.$toast('认证成功！')
            // 人脸认证保存两小时 
            t.$cookies.set('FACE_MATCH', t.wx_userInfo.openid, '7200s')
            setTimeout(function() {
              t.linkToPage(sessionStorage.getItem("prevUrl")||'/grzx_index', 'slide-right')
            }, 1500) 
          } else {
            t.flag = 2 
          }
        }
      })
    },
    //选择身份证
    chooseImage(config) {
      var t = this
      var wx_config = JSON.parse(JSON.stringify(config))
      wx_config.jsApiList = ['chooseImage', 'getLocalImgData']
      t.$wechat.config(wx_config)
      t.$wechat.ready(function() {
        console.log(config)
        t.$wechat.chooseImage({
          count: 1, // 只允许选择一张图片
          sizeType: 'original', //指定返回原图
          sourceType: ['camera'],
          success: function(res) {
            t.$wechat.getLocalImgData({
              localId: res.localIds[0],
              success: function(res1) {
                var localData = res1.localData;
                if (localData.indexOf('data:image') != 0) {
                  localData = 'data:image/jpeg;base64,' + localData
                }
                //得到正确的base64数据
                localData = localData.replace(/\r|\n/g, '').replace('data:image/jgp', 'data:image/jpeg');
                t.cardImg = localData
                t.isUploadimg = true

                t.faceByIMG(localData)
              },
              fail: function(res) {
                console.log(res);
              }
            });
          }
        });
      })
    }
  },
  mounted() {
    var t = this
    if (t.queryData.type!='match') {
      t.$dialog.confirm({
          title: '',
          message: '为了保证您后续功能正常使用\n需要采集注册本人人脸',
        })
        .then(() => {
          // on confirm
        })
        .catch(() => {
          t.goBack()
        })
    }
  }
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.face_verify>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  padding: 0 10px;
}

.face_verify .card-img {
  width: 220px;
}

.face_verify>>>.van-notice-bar {
  color: #ff8711;
  background: #fffde3;
}

.face_verify .tips-wrap {
  text-align: center;
  padding: 30px 15px;
  font-size: 15px;
}

.face_verify .tips-wrap .item-icon {
  display: inline-block;
  margin: 0 auto;
}

.face_verify .tips-wrap .tit {
  font-size: 17px;
  margin: 15px 0 8px;
  font-weight: bold;
}

.face_verify>>>.van-button {
  width: 90%;
  margin: 30px 5% 0;
  height: 50px;
}
.face_verify .g-wp100{
  position: fixed;
  bottom: 30%;
}
</style>