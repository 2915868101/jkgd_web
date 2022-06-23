<template>
  <div class="jtys_index g-wrap g-hp100">
    <!-- 未签约 -->
    <template v-if="!isSigned">
      <div class="jtysfwb_qy_01 g-wrap g-hp100">
        <div class="steps-wrap ">
          <div class="steps-item f-df8 on">
            <div class="steps">1</div>
            <div class="lines"></div>
            <div class="tit">填写信息</div>
          </div>
          <div class="steps-item f-df8">
            <div class="steps">2</div>
            <div class="lines"></div>
            <div class="tit">确认信息</div>
          </div>
          <div class="steps-item f-df8">
            <div class="steps">3</div>
            <div class="lines"></div>
            <div class="tit">签约完成</div>
          </div>
        </div>
        <div class=" s-bgcfff border f-oa" style="height:77%">
          <van-field class="border" readonly input-align="right" v-model="initForm.XM" label="签约对象：" placeholder="请选择">
            <template #button>
              <span @click="isShowPopup = true" class="f-pt4 f-fs20 f-pr5 s-c00c792 icon wsdfont wsd-ren2"></span>
            </template>
          </van-field>
          <van-popup v-model="isShowPopup" position="bottom" :style="{height : '30%'}">
            <div class="pop-head">
              <span>请选择签约对象</span>
              <van-icon name="cross" @click.stop="isShowPopup = false"></van-icon>
            </div>
            <div class="pop-cont">
              <div class="select-list">
                <div class="select-item" v-for="c in patientList" :key="c.CNUM" @click="doSelect(c)">
                  <div class="item-icon" v-if="!c.TX">
                    <i class="iconfont icon-user"></i>
                  </div>
                  <img class="item-icon" v-else :src="c.TX" />
                  <div class="item-name">{{c.XM}}</div>
                </div>
                <div class="select-item" @click="doAddCard">
                  <div class="item-icon f-fwb">
                    <i class="wsdfont wsd-xinzengjia2"></i>
                  </div>
                  <div class="item-name">添加签约对象</div>
                </div>
              </div>
            </div>
          </van-popup>
          <van-field input-align="right" readonly v-model="initForm.ZJHM" label="身份证号：" placeholder="自动获取" />
          <van-field input-align="right" readonly v-model="initForm.PHONE" maxlength="11" label="手机号码：" placeholder="自动获取"></van-field>
          <van-field input-align="right" readonly clickable label="与本人关系：" :value="initForm.URGENT_CONTACT_RELATE" is-link placeholder="请选择" @click="showPicker2=true" />
          <van-popup v-model="showPicker2" position="bottom">
            <van-picker show-toolbar :columns="columns2" @cancel="showPicker2 = false" @confirm="onConfirm2" />
          </van-popup>
          <van-field input-align="right" readonly clickable label="服务包：" :value="initForm.TYPE" is-link placeholder="请选择" @click="getfwb()" />
          <van-field input-align="right" readonly clickable label="所在地区" :value="initForm.AREA" is-link placeholder="请选择" @click="showPicker=true" />
          <van-popup v-model="showPicker" round position="bottom">
            <van-picker show-toolbar :columns="areaList" @cancel="showPicker=false" @change="onChangeArea" @confirm="onConfirmArea" />
          </van-popup>
          <van-field input-align="right" clearable v-model="initForm.HKDZ" label="详细地址：" placeholder="请输入" type="textarea" rows="1" autosize />
          <van-field input-align="right" readonly clearable v-model="initForm.ORG_NAME" label="医疗机构：" is-link placeholder="请选择" @click="linkToPage('jtysfwb_qy_org',initForm)" />
          <van-field input-align="right" clearable v-model="initForm.ORG_ADDR" label="机构地址：" placeholder="自动获取" readonly />
          <van-field input-align="right" clearable v-model="initForm.ORG_LEADER" label="负责人姓名：" placeholder="自动获取" readonly />
          <van-field input-align="right" clearable v-model="initForm.ORG_PHONE" label="负责人电话：" placeholder="自动获取" readonly />
        </div>
        <div class="fixed_btn g-wp100 f-pb30 s-bgcfff" style="height:13%">
          <van-button class="item-btn " color="#00c792" @click="onSubmit">下一步</van-button>
        </div>
      </div>
    </template>
    <template v-else>
      <!-- 签约通过后 -->
      <div class="jtys-index s-bgcf4f9f9" v-if="signState==1">
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
          <div class="w-item f-df">
            <i class="iconfont icon-consult2"></i>
            <a :href="'tel:' + signedData.YSTEL">
              <div class="item-cont f-pl10">
                <div class="tit">电话咨询</div>
                <div class="txt">电话预约咨询</div>
              </div>
            </a>
          </div>
        </div>
        <div class="s-bgcfff f-mt15">
          <van-cell title="签约成员" is-link @click="linkToPage('jtys_jtcy',signedData)" />
        </div>
        <div class="s-bgcfff f-mt15">
          <van-cell title="签约申请中" value="暂无" />
        </div>
      </div>
    </template>
  </div>
</template>
<script>
  import '@/assets/js/util/vue_amap.js'
  export default {
    name: 'jtys_index',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle(t.pageTitle)
      // 判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(data) {
        console.log(data.CONTENTS[0])
        t.getPatientList()
        if (t.queryData) {
          t.initForm = t.queryData
        }
        // 判断是否签约家庭医生
        t.getSignedData()
      })
      t.getCountyLists()
    },
    data() {
      return {
        patientList: [],
        pageTitle: '签约服务包',
        initForm: {
          XM: '',
          ZJHM: '',
          PHONE: '',
          URGENT_CONTACT_RELATE: '',
          TYPE: '',
          AREA: '',
          HKDZ: '',
          ORG_NAME: '',
          ORG_LEADER: '',
          ORG_ADDR: '',
          ORG_PHONE: '',
          FWB_ZIFU: '',
          FWB_NAME: '',
          ID: '',
          FWB_LB: '',
          FWB_YINGSHOU: '',
          ORG_ID: '',
          XH: '',
          SEX: '',
          AGE: ''
        },
        areaList: [{
            values: [],
            defaultIndex: 0,
          },
          {
            values: [],
            defaultIndex: 0,
          }
        ],
        areaFirst: '',
        isShowPopup: false,
        showPicker: false,
        showPicker2: false,
        columns2: ['本人', '父亲', '母亲', '子女', '兄弟', '姐妹'],
        isSigned: false,
        isShow: false,
        signState: '',
        signedData: []
      }
    },
    methods: {
      // 获取签约人
      getPatientList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00014',
          url: t.serverPath1,
          params: {
            FLAG: "Query",
            OPENID: t.wx_userInfo.openid
          },
          success: function(data) {
            t.patientList = data[0].CONTENTS
          },
          error: function(data) {
            t.$toast(data.MSG)
          }
        })
      },
      // 添加健康卡
      doAddCard() {
        var t = this
        if (t.serverHealthCard) {
          sessionStorage.setItem("prevUrl", t.$route.fullPath)
          var redirect_uri = t.wx_health_card + "/register_card?isRegister=true&openid=" + t.wx_userInfo.openid + '&headimgurl=' + t.wx_userInfo.headimgurl + '&prevUrl=' + t.$route.fullPath
          window.location.href = 'https://health.tengmed.com/open/getUserCode?redirect_uri=' + encodeURIComponent(redirect_uri)
        } else {
          t.linkToPage('register_card', {
            isRegister: true
          })
        }
      },
      // 签约人选择 
      doSelect(data) {
        var t = this
        t.initForm.XM = data.XM
        t.initForm.ZJHM = data.CNUM
        t.initForm.PHONE = data.SJHM
        t.isShowPopup = false
        t.getpd()
      },
      //禁用服务包
      getpd() {
        var t = this
        t.ajax({
          serverId: 'DYWJTYSTZH06',
          url: t.serverPath4,
          params: {
            CARDNUM: t.initForm.ZJHM,
          },
          success: function(data) {
            console.log(data)
            localStorage.setItem('key', data[0].CODE)
          }
        })
      },
      getfwb() {
        var t = this
        if (t.initForm.ZJHM == '') {
          t.$toast('请先选择签约对象')
        } else {
          t.initForm.SEX = t.IdCard(t.initForm.ZJHM, 2)
          t.initForm.AGE = t.IdCard(t.initForm.ZJHM, 3)
          t.linkToPage('jtysfwb_qy_fwb', t.initForm)
        }
      },
      getSignedData() {
        var t = this
        t.ajax({
          serverId: 'WXJT0005',
          url: t.serverPath4,
          params: {
            openid: t.wx_userInfo.openid,
          },
          success: function(data) {
            t.fwbList = data[0].CONTENTS
          }
        })
      },
      getCountyLists() {
        var t = this
        t.ajax({
          serverId: 'BMFW00028',
          url: t.serverPath1,
          params: {},
          success: function(data) {
            var list = data[0].CONTENTS || []
            var province_list = []
            list.forEach(function(v, i) {
              if (i == 0) {
                t.areaFirst = v.AREA
              }
              if (t.initForm.AREA) {
                var area = t.initForm.AREA.split('/')[0]
                if (area == v.AREA) {
                  t.areaList[0].defaultIndex = i - 1
                  t.getTownsLists(area)
                }
              }
              if (v.AREA) {
                province_list.push(v.AREA)
              }
            })
            t.$set(t.areaList[0], 'values', province_list)
            if (!t.initForm.AREA) {
              t.getTownsLists(province_list[0])
            }
          }
        })
      },
      getTownsLists(area) {
        var t = this
        t.ajax({
          serverId: 'BMFW00029',
          url: t.serverPath1,
          loading: false,
          params: {
            AREA: area
          },
          success: function(data) {
            var list = data[0].CONTENTS || []
            var city_list = []
            list.forEach(function(v, i) {
              if (t.initForm.AREA) {
                var area = t.initForm.AREA.split('/')[1]
                if (area == v.STREET) {
                  t.areaList[1].defaultIndex = i - 1
                }
              }
              if (v.STREET) {
                city_list.push(v.STREET)
              }
            })
            t.$set(t.areaList[1], 'values', city_list)
          }
        })
      },
      doValidate(cb) {
        var t = this
        if (!t.initForm.XM) {
          t.$toast('签约对象不能为空')
          return false
        } else if (!t.initForm.URGENT_CONTACT_RELATE) {
          t.$toast('与本人关系不能为空')
          return false
        } else if (!t.initForm.TYPE) {
          t.$toast('服务包不能为空')
          return false
        } else if (!t.initForm.AREA) {
          t.$toast('所在地区不能为空')
          return false
        } else if (!t.initForm.HKDZ) {
          t.$toast('详细地址不能为空')
          return false
        } else if (!t.initForm.ORG_NAME) {
          t.$toast('医疗机构不能为空')
          return false
        } else {
          if (typeof cb == 'function') {
            cb()
          }
        }
      },
      onSubmit() {
        var t = this
        setTimeout(function() {
          t.doValidate(function() {
            console.log(t.initForm)
            t.linkToPage('jtysfwb_qy', t.initForm)
          })
        }, 50)
      },
      onChangeArea(v) {
        var t = this
        t.areaFirst = v.getColumnValue(0)
      },
      onConfirmArea(v) {
        var t = this
        t.initForm.AREA = String(v).replace(/,/g, '/')
        t.$store.state.store_jtys.qy_01_area = t.initForm.AREA
        t.showPicker = false
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

  .jtysfwb_qy_01 .border {
    border-top-right-radius: 12px;
    border-top-left-radius: 12px;
  }

  /*签约前样式*/
  .jtysfwb_qy_01 .position-wrap {
    position: fixed;
    top: 0;
    bottom: 0;
    width: 100%;
    z-index: 10;
  }

  .jtysfwb_qy_01>>>.van-field__control {
    color: #909399
  }

  .jtysfwb_qy_01 .input-item input {
    border: 0;
    outline: 0;
    width: 50px;
    font-size: 15PX;
    text-align: right;
    padding-right: 5px;
  }

  .jtysfwb_qy_01 .g-wp100>>>.van-button {
    width: 90%;
    margin: 20px 5% 0;
    height: 50px;
    padding: 0 10px;
  }

  .jtysfwb_qy_01 .cell-item,
  .jtysfwb_qy_01>>>.van-cell {
    padding: 15px 20px;
    border-bottom: 1PX solid #ebeef5;
    font-size: 15PX;
  }

  .jtysfwb_qy_01>>>.van-cell__title {
    color: #606266
  }

  .jtysfwb_qy_01>>>.van-cell__value {
    color: #303133;
  }

  .jtysfwb_qy_01>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .jtysfwb_qy_01>>>.van-field__button .van-button--small {
    height: 20px;
    min-width: 20px;
  }

  .jtysfwb_qy_01 .tips-wrap {
    color: #c0c4cc;
    font-size: 14px;
    padding: 15px;
  }

  .jtysfwb_qy_01 .steps-wrap {
    background: #f4f9f9;
    padding: 15px;
    font-size: 14px;
    display: flex;
    color: #323233;
    height: 11%;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item .steps {
    position: relative;
    z-index: 2;
    background: #fff;
    border-radius: 50%;
    width: 27px;
    height: 27px;
    line-height: 27px;
    font-size: 14px;
    margin: 0 auto 10px;
    border: 1PX solid #dde0e7;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item .lines {
    border-bottom: 1PX solid #dde0e7;
    position: relative;
    top: -22px;
    left: 50%;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item.on .steps,
  .jtysfwb_qy_01 .steps-wrap .lines.on,
  .jtysfwb_qy_01 .steps-wrap .tit.on {
    border-color: #00c792;
    background: #00c792;
    color: #fff;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item:nth-last-child(1) .lines {
    border: 0;
  }

  .jtysfwb_qy_01 .steps-wrap .steps-item {
    text-align: center;

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

  .jtys_index .jtys-index .iconfont {
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
    padding: 13px 16px;
    font-size: 14px;
  }

  .jtys_index>>>.van-cell__title {
    color: #303133;
  }

  .jtys_index>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .jtys_index .m-areaList-box /deep/ .van-picker__columns .van-picker-column:nth-child(3) {
    display: none;
  }

  /*popup 样式*/
  .jtys_index .pop-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 15px;
    color: #c0c4cc;
    padding: 15px;
  }

  .jtys_index .pop-cont {
    font-size: 15px;
    padding: 15px;
  }

  .jtys_index .select-list {
    display: flex;
    flex-wrap: wrap;
    text-align: center;
  }

  .jtys_index .select-list .select-item {
    width: 25%;
    margin-bottom: 15px;
  }

  .jtys_index .select-list .select-item .item-icon {
    display: inline-block;
    background: #d2f6ec;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
    margin-bottom: 8px;
  }

  .jtys_index .select-list .select-item .iconfont {
    color: #00c792;
    font-size: 28px;
  }

  .jtys_index .select-list .select-item .wsdfont {
    color: #00c792;
    font-size: 28px;
  }

  .fixed_btn {
    position: fixed;
    bottom: 0px;
  }
</style>