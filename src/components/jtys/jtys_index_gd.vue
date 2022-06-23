<template>
  <div class="jtys_index g-wrap g-hp100">
    <template v-show="isShow">
      <!-- 未签约 -->
      <template v-if="!isSigned">
        <div class="jtys-index1 s-bgcfff g-hp100">
          <div class="f-bb10">
            <div class="f-pl15 f-pr15 f-pt25 f-pb25">
              <div class="banner-wrap f-bs3">
                <img class="banner-img" src="../../assets/img/jtys/jtys-banner1.jpg">
                <div class="banner-tips" v-if="signState!==0">
                  <div class="g-h50">
                    <div class="btn-item" @click="toQy">立即签约</div>
                  </div>
                  <div class=" ">暂无签约家庭医生</div>
                </div>
                <div class="banner-tips" v-else>
                  <div class="f-mt10">已提交家庭医生申请</div>
                  <div class="f-mt15">请等待审核通过</div>
                </div>
              </div>
            </div>
            <div class="s-bgcfff" v-if="signState===0">
              <div class="f-pl15 f-pr15 f-mt10">
                <div class="f-bb1"></div>
              </div>
              <van-cell title="签约申请中" is-link @click="linkToPage('jtys_qy_jd')" />
            </div>
          </div>
          <div class="box-wrap process-wrap">
            <div class="b-head f-pt15 f-pb15">
              <b class="b-border f-mr8"></b>
              <span class="f-fwb">家庭医生签约流程</span>
            </div>
            <div class="process-list">
              <div class="proce-item">
                <i class="iconfont icon-qylc1"></i>
                <div class="txt">
                  <p class="f-fwb f-fs15 f-mt1 f-mb5">确定签约对象</p>
                  <p class="s-c909399 f-fs14">选择已有的家庭成员</p>
                </div>
                <div class="line"></div>
              </div>
              <div class="proce-item">
                <i class="iconfont icon-qylc2"></i>
                <div class="txt">
                  <p class="f-fwb f-fs15 f-mt1 f-mb5">填写基本信息</p>
                  <p class="s-c909399 f-fs14">填写居住地址</p>
                </div>
                <div class="line"></div>
              </div>
              <div class="proce-item">
                <i class="iconfont icon-qylc3"></i>
                <div class="txt">
                  <p class="f-fwb f-fs15 f-mt1 f-mb5">选择家庭医生</p>
                  <p class="s-c909399 f-fs14">选择所属区域的家庭医生团队</p>
                </div>
                <div class="line"></div>
              </div>
              <div class="proce-item">
                <i class="iconfont icon-qylc4"></i>
                <div class="txt">
                  <p class="f-fwb f-fs15 f-mt1 f-mb5">选择服务包</p>
                  <p class="s-c909399 f-fs14">选择您所需的家庭医生服务包</p>
                </div>
                <div class="line"></div>
              </div>
              <div class="proce-item">
                <i class="iconfont icon-success"></i>
                <div class="txt">
                  <p class="f-fwb f-fs15 f-mt1 f-mb5">提交申请等待家医联系</p>
                  <p class="s-c909399 f-fs14">签约申请已提交至社区医生</p>
                </div>
                <div class="line"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- 签约审核中 -->
      </template>
      <template v-else>
        <!-- 签约通过后 -->
        <!-- <div class="jtys-index s-bgcf4f9f9" v-if="signState==1">
          <div class="banner-wrap">
            <img class="g-wp100" src="../../assets/img/jtys/jtys-banner.jpg">
          </div>
          <div class="menu-wrap s-bgcfff f-pt15 f-pb15 f-pl15 f-pr15">
            <div class="w-item f-df" @click="linkToPage('zxzx_index')">
              <i class="iconfont icon-consult1"></i>
              <div class="item-cont f-pl10">
                <div class="tit">线上咨询</div>
                <div class="txt">在线咨询医生</div>
              </div>
            </div>
            <div class="w-item f-df" >
              <i class="iconfont icon-consult2"></i>
              <div class="item-cont f-pl10">
                <div class="tit">电话咨询</div>
                <div class="txt">电话预约咨询</div>
              </div>
            </div>
          </div>
          <div class="s-bgcfff f-mt15">
            <van-cell title="签约成员" is-link @click="linkToPage('jtys_jtcy',signedData)" />
          </div>
          <div class="s-bgcfff f-mt15">
            <van-cell title="签约申请中"  value="暂无"/>
          </div>
        </div> -->
        <div class="jtys-index g-hp100 g-wp100 s-bgcfff" v-if="signState==1">
          <div class="head-wrap ">
            <div class="head-cont">
              <div class="img-box">
                <img src="../../assets/img/jtys/ys-icons.png">
                <div>我的家庭医生</div>
              </div>
              <div class="cont-box f-df1 f-pl15">
                <div><span class="f-fs15 f-fwb">医生</span> <span class="f-fs13">机构主任</span></div>
                <div class="s-c909399 f-mt12 f-mb8"><i class="iconfont icon-hjdz f-mr8"></i>凤岭山卫生服务社区机构</div>
                <div class="s-c909399"><i class=" iconfont icon-phone f-mr8"></i>021-56959660</div>
              </div>
            </div>
          </div>
          <div class="menu-list">
            <div class="list-item f-df" v-for="(c,i) in menuList" :key="i" @click="handlerMenu(c)">
              <img class="icons" :src="c.icons">
              <div class="f-df1 f-pl10">
                <p class="s-c303133 f-fwb">{{c.txt1}}</p>
                <p class="s-c909399 f-mt8 f-fs12">{{c.txt2}}</p>
              </div>
            </div>
          </div>
        </div>
      </template>
    </template>
  </div>
</template>
<script>
export default {
  name: 'jtys_index',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle(t.pageTitle)
    // 判断是否注册
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function(data) {
      t.initForm.ZJHM = data.CONTENTS[0].CARD_NUMBER
      // 判断是否签约家庭医生
      t.getSignedData()
    })
  },
  data() {
    return {
      pageTitle: '签约家庭医生',
      initForm: {
        ZJHM: '',
      },
      isSigned: false,
      isShow: false,
      signState: '',
      signedData: [],
      menuList: [{
        icons: require('../../assets/img/jtys/jygzt-icon5.png'),
        txt1: '咨询记录',
        txt2: '我的咨询记录',
        url: '/zxzx_index'
      }, {
        icons: require('../../assets/img/jtys/jygzt-icon6.png'),
        txt1: '随访记录',
        txt2: '家医的随访记录',
        url: '/jtys_qysf'
      }]
    }
  },
  methods: {
    getSignedData() {
      var t = this
      t.ajax({
        serverId: 'BMFW00019',
        url: t.serverPath1,
        params: {
          OPENID: t.wx_userInfo.openid,
          CARDNUM: t.initForm.ZJHM
        },
        success: function(data) {
          t.isShow = true
          if (data[0].CONTENTS) {
            if (data[0].CONTENTS[0].INFO[0].SIGN_STATE == 0) {
              t.isSigned = false
              t.signState = 0
              t.signedData = data[0].CONTENTS[0].INFO[0]
            } else {
              t.isSigned = true
              t.signState = 1
              t.pageTitle = '家庭医生'
              t.signedData = data[0].CONTENTS[0].INFO[0]
            }
          }
        }
      })
    },
    toQy() {
      var t = this
      t.linkToPage('jtys_qy')
    },
    handlerMenu(data) {
      var t = this
      if (data.islink == '1') {
        window.location.href = data.url
      } else {
        if (data.url) {
          t.linkToPage(data.url)
        } else {
          if (data.tit) {
            t.$toast('正在建设中')
          }
        }
      }
    },
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}

.jtys_index .jtys-index1 .f-bb1 {
  border-bottom: 1px solid #ebeef5;
}

.jtys_index .jtys-index1 .banner-wrap {
  background-size: 100%;
  width: 100%;
  position: relative;
  text-align: center;
  overflow: hidden;
}

.jtys_index .jtys-index1 .banner-wrap .banner-img {
  width: 100%;
  vertical-align: middle;
}

.jtys_index .jtys-index1 .btn-item {
  padding: 8px 10px;
  width: 100px;
  height: 34px;
  border: 1px solid #fff;
  font-size: 15px;
  font-weight: bold;
  border-radius: 20px;
  color: #fff;
  margin: 0 auto;
}

.jtys_index .jtys-index1 .banner-tips {
  position: absolute;
  right: 20px;
  top: 30%;
  bottom: 0;
  width: 45%;
  text-align: center;
  color: #fff;
  font-size: 15px;
}

.jtys_index .jtys-index1 .process-list {
  padding: 10px 20px 20px;
  margin-left: 10px;
}

.jtys_index .jtys-index1 .process-list .proce-item {
  display: flex;
  align-content: center;
  height: 70px;
  position: relative;
}

.jtys_index .jtys-index1 .process-list .txt {
  margin-left: 20px;
}

.jtys_index .jtys-index1 .process-list .iconfont {
  font-size: 18px;
  display: inline-block;
  width: 36px;
  height: 36px;
  line-height: 36px;
  text-align: center;
  border-radius: 50%;
  border: 1px solid #00c792;
  color: #00c792;
  background: #fff;
  z-index: 10;
  position: relative;
}

.jtys_index .jtys-index1 .proce-item:not(:last-child) .line {
  height: 100%;
  border: 1px dotted #00c792;
  position: absolute;
  left: 18px;

}

/*签约后样式*/
.jtys_index .jtys-index .menu-wrap {
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  color: #929395;
}

.jtys_index .jtys-index .menu-wrap .w-item {
  width: 48%;
  padding: 15px 0;
  border-radius: 5px;
  background: #fbf7f3;
  justify-content: center;
}

.jtys_index .jtys-index .w-item .iconfont {
  font-size: 28px;
  color: #fff;
  background: #ff8648;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
}

.jtys_index .jtys-index .w-item .item-cont .tit {
  font-size: 15px;
  color: #303133;
  font-weight: bold;
  margin: 5px 0 10px;
}

.jtys_index>>>.van-cell {
  padding: 20px 15px;
  font-size: 15px;
}

.jtys_index>>>.van-cell__title {
  color: #606266;
  font-weight: bold;
}

.jtys_index>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

/*我的家庭医生*/
.jtys_index .head-wrap {
  position: relative;
  background: -webkit-gradient(linear, left top, left bottom, from(#000), to(Gray), color-stop(0.5, #000), color-stop(0.5, Gray));
  height: 170px;
}

.jtys_index .head-wrap {
  background: #f4f9f9;
  position: relative;
  overflow: hidden;
}

.jtys_index .head-wrap:before {
  background-color: #59d59b;
  content: " ";
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  height: 70%;
}

.jtys_index .head-wrap .head-cont {
  height: 120px;
  background: #fff;
  position: absolute;
  top: 35px;
  left: 15px;
  right: 15px;
  border-radius: 5px;
  text-align: center;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.jtys_index .img-box {
  width: 100px;
  height: 100%;
  position: relative;
}

.jtys_index .img-box img {
  width: 60px;
  margin: 0 auto;
}

.jtys_index .img-box div {
  width: 100px;
  font-size: 13px;
  border: 1px solid #00c792;
  background: #fff;
  color: #00c792;
  padding: 4px 0;
  text-align: center;
  border-radius: 20px;
  position: absolute;
  bottom: 2px;
}

.jtys_index .cont-box {
  text-align: left;
  font-size: 14px; 
}

.jtys_index .menu-list {
  padding: 0 15px;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.jtys_index .menu-list .list-item {
  border-radius: 5px;
  font-size: 14px;
  width: 48%;
  padding: 10px;
  align-items: center;
  height: 70px;
  margin-top: 15px;
  background: #e9fdf6;
}

.jtys_index .menu-list .icons {
  width: 45px;
  height: 45px;
}
</style>