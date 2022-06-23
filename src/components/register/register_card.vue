<template>
  <div class="register_card g-wrap g-hp100 s-bgcfff">
    <!-- <van-notice-bar v-if="!queryData.isRegister" class="f-fs13" wrapable :scrollable="false">
      本院采用实名认证注册就诊请填写正确的信息，未满18用户不可注册，需由监护人注册后绑定就诊人预约挂号。
    </van-notice-bar>
    <van-notice-bar v-else class="f-fs13" wrapable :scrollable="false">
      本院采用实名认证注册就诊，请填写真实有效信息，否者挂号无效，后果自行承担。
    </van-notice-bar> -->
    <div class="f-pt30 f-pl20 f-pr20 f-pb20">
      <img class="card-img g-wp100" :src="cardImg" @click="chooseImage(wx_configParams)">
    </div>
    <div class="f-pb30 f-pl30 f-pr30 f-lh18 f-fs13 s-bgcfff">
      <p class="s-c606266 f-ml20 f-mt5"><span class="s-cbfbfbf">&bull;</span> 请上传身份证人像面</p>
      <p class="s-c606266 f-ml20 f-mt5"><span class="s-cbfbfbf">&bull;</span> 垂直拍摄，证件边框完整，保证照片清晰</p>
      <p class="s-c606266 f-ml20 f-mt5"><span class="s-cbfbfbf">&bull;</span> 实名认证注册</p>
    </div>
    <van-button v-if="isUploadimg" class="item-btn f-bs3 f-fs15" round color="#00c792" @click="ocrIdCard(cardImg)">确认上传</van-button>
    <van-button v-else class="item-btn f-bs3 f-fs15" round color="#00c792" disabled>确认上传</van-button>
    <div class="f-pb20 f-pl30 f-pr30 f-lh18 f-fs12 s-bgcfff">
      <p class="f-tac f-mt30 f-fs15">
        <span class="s-c606266 f-tdu" @click="toRegister({ismanual:true,wechatCode:wechatCode,openid:openid,headimgurl:headimgurl})">手动填写 </span>
        <i class="iconfont icon-arrow-right f-fs11 f-ml5 s-c606266"></i>
      </p>
    </div>
    <div class="f-pb20 f-pl30 f-pr30 f-lh18 f-fs13 s-bgcfff">
      <!--  v-if="!isRegister" -->
      <p class="f-tac f-mt30 f-fs15">
        <span class="s-c00c792" @click="doChooseCard">选择授权人，一键绑卡，免填身份信息</span>
        <i class="iconfont icon-arrow-right f-fs11 s-c00c792 f-ml5"></i>
      </p>
    </div>
  </div>
</template>
<script>
var openid, headimgurl, prevUrl
export default {
  name: 'register_card',
  components: {},
  computed: {},
  created() {
    var t = this
    var title = !t.isRegister ? '注册电子健康卡' : '注册电子健康卡'
    t.updateTitle(title)
    var Request = undefined
    Request = t.GetRequest()
    t.wechatCode = Request["wechatCode"]
    t.isRegister = Request["isRegister"] || false
    openid = Request["openid"]
    headimgurl = Request["headimgurl"]
    prevUrl = Request["prevUrl"]
    sessionStorage.wx_userInfo = JSON.stringify({ openid: openid, headimgurl: headimgurl })
    t.$store.state.wx_userInfo = JSON.parse(sessionStorage.wx_userInfo)
    sessionStorage.setItem("registerForm", '')
    sessionStorage.setItem("code_ori", '')
    sessionStorage.setItem("prevUrl", prevUrl)
    console.log(t.wx_userInfo)
  },
  data() {
    return {
      cardImg: require('@/assets/img/register/upload-bg.jpg'),
      isUploadimg: false,
      wechatCode: '',
      isRegister: '',
    }
  },
  methods: {
    toRegister(params) {
      console.log(params)
      var t = this
      if (!t.isRegister) {
        t.linkToPage('/register_index', params)
      } else {
        t.linkToPage('/oper_people_detils', params)
      }
    },
    //身份证OCR识别
    ocrIdCard(base64) {
      var t = this
      t.ajax({
        method: 'post',
        // loading: false,
        params: {
          IMAGECONTENT: base64,
          CHANNELNUM: '0',
        },
        serverId: 'WXBM00041',
        url: t.serverPath1,
        success(data) {
          console.log(data)
          if (data[0].CODE == 0) {
            var _data = JSON.parse(data[0].rsp)
            _data = _data.cardInfo
            t.toRegister({ XM: _data.name, CNUM: _data.id, XB: _data.gender, HKDZ: _data.address, MZ: t.serverType=='cz'?_data.nation+'族':_data.nation, wechatCode: t.wechatCode, openid: openid, headimgurl: headimgurl })
          } else {
            t.$toast(data[0].MSG)
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
                console.log(localData);
                // t.ocrIdCard(localData)
              },
              fail: function(res) {
                console.log(res);
              }
            });
          }
        });
      })
    },
    doChooseCard() {
      var t = this
      var pageName = !t.isRegister ? '/register_index' : '/oper_people_detils'
      var redirect_uri = t.wx_health_card + pageName + "?openid=" + openid + '&headimgurl=' + headimgurl + '&prevUrl=' + prevUrl
      window.location.href = 'https://health.tengmed.com/open/getHealthCardList?redirect_uri=' + encodeURIComponent(redirect_uri);
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.register_card>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  padding: 0 10px;
}

.register_card .card-img {
  width: 100%;
  height: 200px;
}

.register_card>>>.van-notice-bar {
  color: #ff8711;
  background: #fffde3;
}

.register_card .quick-bind {
  font-size: 14px;
  color: #606266;
  text-align: center;
  margin-top: 10px;
  padding-bottom: 15px;
}
</style>