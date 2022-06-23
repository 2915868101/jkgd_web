<template>
  <div class="face_verify_jg g-wrap g-hp100 s-bgcfff">
    <div v-if="flag==1">
      <div class="f-pt30 f-pb30 f-pl30 f-pr30 f-lh18 f-fs15">
        <p class="s-c303133 f-ml20 f-mt5 f-fwb">*请拍摄您本人人脸，请保证照片清晰真实</p>
      </div>
      <div class="f-pt30 f-pl20 f-pr20 f-pb30 f-tac" v-if="!isShowCamera">
        <img class="card-img " src="../../assets/img/register/face-img.png">
      </div>
      <div class="camera-wrap" v-else>
        <div class="face-bg">
          <video id="video" width="100%" height="100%" autoplay="autoplay" playsinline webkit-playsinline="true"></video>
          <div class="anim-border animation"></div>
        </div>
        <canvas id='canvas' width="300px" height="300px" style="display: none;"></canvas>
      </div>
    </div>
    <div class="tips-wrap s-bgcfff f-mb10" v-else>
      <div class="item-icon g-w50 g-h50 f-brp50 f-lh50">
        <van-icon name="clear" color="#f57852" size="60" />
      </div>
      <div class="f-mb10 f-mt10 s-c303133 tit f-ti1">认证失败！</div>
      <div class="f-mb5 f-mt5 s-c999 f-fs13 f-ti1">身份认证失败，请重新拍摄认证</div>
    </div>
    <van-button class="item-btn f-bs3 f-fs15" v-if="!isShowCamera" round color="#00c792" @click="doCamera">
      {{flag==1?'开始拍摄':'重新拍摄认证'}}
    </van-button>
    <!-- <img :src="'data:image/png;base64,'+imgs"> -->
  </div>
</template>
<script>
var video, canvas
export default {
  name: 'face_verify_jg',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle('身份验证-测试')
    // 获取注册用户信息
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0, faceimg: true }, function(data) {
      t.userInfo = data.CONTENTS[0]
    })
    t.type = !t.queryData.type ? 'detect' : 'match'
    t.type = 'match'
  },
  data() {
    return {
      interval: '',
      time: 0,
      imgs: '',

      userInfo: '',
      type: '',
      flag: 1,
      isShowCamera: false
    }
  },
  methods: {
    doCamera() {
      var t = this
      t.isShowCamera = !t.isShowCamera
      t.flag = t.flag == 2 || 1
      t.$nextTick(function() {
        t.initCamera()
      })
    },
    initCamera() {
      var t = this
      video = document.getElementById("video");
      canvas = document.getElementById("canvas");

      // 老的浏览器可能根本没有实现 mediaDevices，所以我们可以先设置一个空的对象
      if (navigator.mediaDevices === undefined) {
        navigator.mediaDevices = {};
      }

      // 一些浏览器部分支持 mediaDevices。我们不能直接给对象设置 getUserMedia
      // 因为这样可能会覆盖已有的属性。这里我们只会在没有getUserMedia属性的时候添加它。
      if (navigator.mediaDevices.getUserMedia === undefined) {
        navigator.mediaDevices.getUserMedia = function(constraints) {

          // 首先，如果有getUserMedia的话，就获得它
          var getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia

          // 一些浏览器根本没实现它 - 那么就返回一个error到promise的reject来保持一个统一的接口
          if (!getUserMedia) {
            return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
          }

          // 否则，为老的navigator.getUserMedia方法包裹一个Promise
          return new Promise(function(resolve, reject) {
            getUserMedia.call(navigator, constraints, resolve, reject);
          });
        }
      }
      //默认使用前摄像头，强制使用后置摄像头如下设置
      // let constraints = {video: { facingMode: { exact: "environment" } }};
      let constraints = { video: true };
      navigator.mediaDevices.getUserMedia(constraints)
        .then(function(stream) {
          // 旧的浏览器可能没有srcObject
          if ("srcObject" in video) {
            video.srcObject = stream;
          } else {
            // 防止在新的浏览器里使用它，应为它已经不再支持了
            video.src = window.URL.createObjectURL(stream);
          }
          video.onloadedmetadata = function() {
            video.play();
          }; 
          //调用canvas保存截图 
          t.interval = setInterval(t.getface, 500);
        })
        .catch(function(err) {
          console.log(err.name + ": " + err.message);
        });

    },
    getface() {
      var t = this
      t.time++;
      if (t.time > 15) {
        clearInterval(t.interval)
      }
      //绘制canvas图形
      canvas.getContext('2d').drawImage(video, 0, 0, 300, 300);
      //把canvas图像转为img图片
      var bdata = canvas.toDataURL("image/png");
      var base64 = bdata.split(',')[1]; //照片压缩成base位数据
      //提交至接口匹配人脸库
      t.imgs = base64
      t.faceByIMG(base64)
    },
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
        loading: false,
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
            t.time = 0
            clearInterval(t.interval)
            t.$toast('认证成功！') 
            setTimeout(function() {
              t.flag = 2
              if (t.type == 'detect') { //注册采集人脸时
                t.linkToPage(sessionStorage.getItem("prevUrl"), 'slide-right')
              } else { //对比人脸时
                // t.goBack(-1)
                t.linkToPage('/index', 'slide-right')
              }
            }, 1500)
          } else {
            if (t.time > 15) {
              t.time = 0
              clearInterval(t.interval)
              t.isShowCamera = !t.isShowCamera
              t.flag = 2
            }
          }
        }
      })
    },
  },
  mounted() {
    var t = this
    if (!t.queryData.isFaceImg||!t.queryData.type) {
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
  },
  beforeDestroy() {
    var t = this
    clearInterval(t.interval)
  },
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.face_verify_jg>>>.van-button {
  width: 90%;
  margin: 20px 5%;
  height: 50px;
  padding: 0 10px;
}

.face_verify_jg .card-img {
  width: 220px;
}

.face_verify_jg>>>.van-notice-bar {
  color: #ff8711;
  background: #fffde3;
}

.face_verify_jg .tips-wrap {
  text-align: center;
  padding: 30px 15px;
  font-size: 15px;
}

.face_verify_jg .tips-wrap .item-icon {
  display: inline-block;
  margin: 0 auto;
}

.face_verify_jg .tips-wrap .tit {
  font-size: 17px;
  margin: 15px 0 8px;
  font-weight: bold;
}

.face_verify_jg>>>.van-button {
  width: 90%;
  margin: 30px 5% 0;
  height: 50px;
}

.face_verify_jg .g-wp100 {
  position: fixed;
  bottom: 30%;
}

.face_verify_jg .camera-wrap {
  position: relative;
  height: 280px;
}

.face_verify_jg .face-bg {
  position: absolute;
  z-index: 90;
  width: 280px;
  height: 280px;
  border-radius: 50%;
  overflow: hidden;
  top: 0;
  left: 50%;
  margin-left: -140px;
}

.face_verify_jg .face-bg video {
  position: absolute;
  left: -50px;
  top: -30px;
  width: 380px;
  height: 380px;
}

.face_verify_jg .face-bg .anim-border {
  border: 1px solid #00c792;
  box-shadow: 0 0 10px #047658;
  width: 280px;
  position: absolute;
  top: 0;
  left: calc((100% - 280px)/2);
  animation: animBorder 4s linear infinite;
}

@keyframes animBorder {
  0% {
    top: -10px;
  }

  100% {
    top: 280px;
  }
}
</style>