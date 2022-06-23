<template>
  <div class="tjys_qy_01 g-wrap g-hp100 s-bgcfff">
    <van-notice-bar class="f-fs13" wrapable :scrollable="false">
      实名制签约家庭医生，请确认信息正确无误，否则无法 完成签约服务。
    </van-notice-bar>
    <div class="steps-wrap">
      <div class="steps-item f-df8 on">
        <div class="steps">1</div>
        <div class="lines"></div>
        <div class="tit">确认资料</div>
      </div>
      <div class="steps-item f-df8">
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
    <div class="register-wrap s-bgcfff">
      <van-field input-align="right" clearable v-model="initForm.XM" label="签约居民" placeholder="请输入姓名" readonly />
      <van-field input-align="right" clearable v-model="initForm.ZJHM" label="身份证号码" placeholder="请输入身份证号码" readonly />
      <!-- <van-field input-align="right" clearable v-model="initForm.AREA" label="所在地区" placeholder="请选择" /> -->
      <van-field input-align="right" readonly clickable label="所在地区" :value="initForm.AREA" placeholder="请选择" @click="showPicker=true" />
      <van-popup v-model="showPicker" round position="bottom">
        <van-picker show-toolbar :columns="areaList" @cancel="showPicker=false" @change="onChangeArea" @confirm="onConfirmArea" />
      </van-popup>
      <!--       <div class="cell-item f-df f-dfj">
        <span class="f-df6 s-c606266">所在地区</span>
        <div class="f-df16 f-tar f-pl5 f-df f-dfe">
          <div class="input-item f-mr5"><input type="text" :value="initForm.city">市</div>
          <div class="input-item f-mr5"><input type="text" :value="initForm.district">区/县</div>
          {{initForm.SZDQ}}
        </div>
        <i class="f-df1 iconfont icon-position" @click="isShowMap = true"></i>
      </div> -->
      <van-field input-align="right" clearable v-model="initForm.HKDZ" label="详细地址" placeholder="请输入详细地址" type="textarea" rows="1" autosize />
      <van-field input-align="right" clearable v-model="initForm.PHONE" maxlength="11" label="联系电话" placeholder="请输入手机号码" readonly>
        <template #button>
          <van-button size="small" plain round color="#00c792" @click="linkToPage('jtys_xg_sjhm',userInfo)">修改</van-button>
        </template>
      </van-field>
      <!-- <div class="tips-wrap">如资料不正确，请点击申诉修改。<span class="f-fs13 s-c00c792 f-tdu" @click="linkToPage('jkda_grzl',userInfo)">申诉修改</span></div> -->
    </div>
    <div class="g-wp100 f-pt30 f-pb30 s-bgcfff">
      <van-button class="item-btn f-bs3" color="#00c792" round @click.stop="toQy02">下一步</van-button>
    </div> 
  </div>
</template>
<script>
import '@/assets/js/util/vue_amap.js'
export default {
  name: 'tjys_qy_01',
  components: {},
  computed: {},
  created() {
    var t = this
    t.updateTitle('签约信息')
    // 判断是否注册
    t.isRegister({ openid: t.wx_userInfo.openid, type: 0 }, function(data) {
      t.initForm.XM = data.CONTENTS[0].NAME
      t.initForm.ZJHM = data.CONTENTS[0].CARD_NUMBER
      t.initForm.PHONE = data.CONTENTS[0].SJHM
      t.initForm.HKDZ = data.CONTENTS[0].HKDZ
      t.userInfo = data.CONTENTS[0]
    }) 
    if (t.$store.state.store_jtys.qy_01_area) {
      t.initForm.AREA = t.$store.state.store_jtys.qy_01_area
    }
    t.getCountyLists()
  },
  data() {
    return {
      activeIndex: 1,
      initForm: {
        XM: '',
        ZJHM: '',
        PHONE: '',
        HKDZ: '',
        AREA: ''
      },
      userInfo: '',
      isShowMap: false,
      showPicker: false,
      areaList: [{
          values: [],
          defaultIndex: 0,
        },
        {
          values: [],
          defaultIndex: 0,
        }
      ],
      areaFirst: ''
    }
  },
  watch: {
    areaFirst(v) {
      console.log(v)
      if(v){
        this.getTownsLists(v)
      }
    }
  },
  methods: {
    doValidate(cb) {
      var t = this
      if (!t.initForm.XM) {
        t.$toast('姓名不能为空')
        return false
      } else if (!t.initForm.ZJHM) {
        t.$toast('身份证号不能为空')
        return false
      } else if (!t.regIdCard(t.initForm.ZJHM)) {
        t.$toast('请填写真实有效的身份证号')
        return false
      } else if (!t.initForm.HKDZ) {
        t.$toast('详细地址不能为空')
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
          t.linkToPage('jtys_qy_02', t.initForm)
        })
      }, 50)
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
          list.forEach(function(v,i) {
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
    onChangeArea(v) {
      var t = this
      t.areaFirst = v.getColumnValue(0)
    },
    onConfirmArea(v) {
      var t = this
      t.initForm.AREA = String(v).replace(/,/g, '/')
      t.$store.state.store_jtys.qy_01_area = t.initForm.AREA
      t.showPicker = false
    }
  },
  mounted() {}
}
</script>
<style scoped>
@media screen and (max-width: 320px) {}


.tjys_qy_01 .position-wrap {
  position: fixed;
  top: 0;
  bottom: 0;
  width: 100%;
  z-index: 10;
}

.tjys_qy_01 .input-item input {
  border: 0;
  outline: 0;
  width: 50px;
  font-size: 15PX;
  text-align: right;
  padding-right: 5px;
}

.tjys_qy_01 .g-wp100>>>.van-button {
  width: 90%;
  margin: 20px 5% 0;
  height: 50px;
  padding: 0 10px;
}

.tjys_qy_01 .cell-item,
.tjys_qy_01>>>.van-cell {
  padding: 15px 20px;
  border-bottom: 1PX solid #ebeef5;
  font-size: 15PX;
}

.tjys_qy_01>>>.van-cell__title {
  color: #606266
}

.tjys_qy_01>>>.van-cell__value {
  color: #303133;
}

.tjys_qy_01>>>.van-cell:not(:last-child)::after {
  border-bottom: 0;
}

.tjys_qy_01>>>.van-field__button .van-button--small {
  height: 20px;
  min-width: 20px;
}

.tjys_qy_01 .tips-wrap {
  color: #c0c4cc;
  font-size: 14px;
  padding: 15px;
}

.tjys_qy_01 .steps-wrap {
  background: #f4f9f9;
  padding: 15px;
  font-size: 14px;
  display: flex;
  color: #909399;
}

.tjys_qy_01 .steps-wrap .steps-item .steps {
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

.tjys_qy_01 .steps-wrap .steps-item .lines {
  border-bottom: 1PX solid #dde0e7;
  position: relative;
  top: -22px;
  left: 50%;
}

.tjys_qy_01 .steps-wrap .steps-item.on .steps,
.tjys_qy_01 .steps-wrap .lines.on,
.tjys_qy_01 .steps-wrap .tit.on {
  border-color: #00c792;
  color: #00c792;
}

.tjys_qy_01 .steps-wrap .steps-item:nth-last-child(1) .lines {
  border: 0;
}

.tjys_qy_01 .steps-wrap .steps-item {
  text-align: center;

}
</style>
<style>
.tjys_qy_01 .m-areaList-box /deep/ .van-picker__columns .van-picker-column:nth-child(3) {
  display: none;
}
</style>