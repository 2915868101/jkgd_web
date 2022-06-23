<template>
  <div class="yygh_index g-wrap">
    <div class="filter-wrap f-mb10">
      <div class="wrap-item g-wp100 wrap-l">
        <van-dropdown-menu active-color="#00c792">
          <van-dropdown-item v-model="value1" :options="option1" @change="onChange" />
        </van-dropdown-menu>
      </div>
      <!-- <div class="wrap-item wrap-r" @click="doShowSelect">
        <i class="iconfont icon-commentdot"></i>
        <div class="select-wrap f-bs3" v-show="isShow">
          <div :class="[isActive ? 's-c00c792' : '', 'item','f-fs14']" @click.stop="doSelectItem">按等级</div>
        </div>
      </div> -->
    </div>
    <van-list v-model="loading" :finished="finished" finished-text="">
      <div class="hospital-item" v-for="(c,i) in hospitalLiat" :key="i" @click="doDepartment(c)">
        <img class="item-img" :src="c.ORG_PICTURE||$store.state.def_hospitalimg" @click.stop="doHospital(c)" />
        <div class="item-cont">
          <div class="name">{{c.ORG_NAME}}</div>
          <div class="f-df f-mt10">
            <span class="b-tag f-mr8" v-show="c.ORG_LEVEL">{{c.ORG_LEVEL}}</span>
            <span class="b-tag f-mr8" v-show="c.ORG_ATTR">{{c.ORG_ATTR}}</span>
          </div>
          <div class="addr f-mt10">
            <div class="addr-l">
              <i class="iconfont icon-position f-mr5 f-fs12"></i>
              <span>{{c.ORG_ADRESS}}</span>
            </div>
            <div class="addr-r">
              <span v-if="c.DISTANCE">&lt;{{Number(c.DISTANCE).toFixed(1)}}km</span>
              <span v-else>--</span>
            </div>
          </div>
        </div>
      </div>
    </van-list>
  </div>
</template>
<script>
  export default {
    name: 'yygh_index',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('选择医院')
      t.getAreaList()
      t.getLocation(t.wx_configParams)
      t.getHospitalList()

    },
    data() {
      return {
        currentPage: 1,
        isShow: false,
        isActive: false,
        hospitalLiat: [],
        loading: false,
        finished: false,
        value1: '广德',
        option1: [],
        searchForm: {
          ORG_AREA: '',
          LONGITUDE: '112.889211',
          LATITUDE: '28.21384',
          GRADE: '',
          Q_TYPE: 'ALL',
        },
        pathParams: {}
      }
    },
    methods: {
      getLocation(config) {
        var t = this
        t.getHospitalList()
        var wx_config = JSON.parse(JSON.stringify(config))
        wx_config.jsApiList = ['getLocation']
        t.$wechat.config(wx_config)
        t.$wechat.ready(function() {
          t.$wechat.getLocation({
            type: 'wgs84',
            success: function(res) {
              console.log(res)
              t.searchForm.LATITUDE = res.latitude; // 纬度，浮点数，范围为90 ~ -90
              t.searchForm.LONGITUDE = res.longitude; // 经度，浮点数，范围为180 ~ -180。 
              t.getHospitalList()
            },
            fail: function(res) {
              console.log(res)
              t.getHospitalList()
            }
          })
        })
      },
      getAreaList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00001',
          url: t.serverPath4 || t.serverPath1,
          params: {
            LONGITUDE: t.searchForm.LONGITUDE,
            LATITUDE: t.searchForm.LATITUDE,
            Q_TYPE: ''
          },
          success: function(data) {
            if (data[0].CONTENTS) {
              for (let item of data[0].CONTENTS) {
                t.option1.push({
                  text: item.ORG_AREA,
                  value: item.ORG_AREA,
                })
              }
            }
            console.log(t.option1)
          }
        })
      },
      getHospitalList() {
        var t = this
        t.ajax({
          serverId: 'WXBM00001',
          url: t.serverPath4 || t.serverPath1,
          params: t.searchForm,
          success: function(data) {
            console.log(789, data)
            t.hospitalLiat = data[0].CONTENTS || []
            t.finished = true
            console.log(t.hospitalLiat)
          }
        })
      },
      // 按等级筛选
      doShowSelect() {
        this.isShow = !this.isShow
      },
      doSelectItem() {
        var t = this
        t.isShow = !t.isShow
        t.isActive = !t.isActive
        t.searchForm.GRADE = t.isActive ? '等级' : ''
        t.getHospitalList()
      },
      onChange(val) {
        var t = this
        t.searchForm.ORG_AREA = val
        t.getHospitalList()
      },
      doDepartment(data) {
        var t = this
        console.log(data)
        if (t.serverType == 'cz' || t.serverType == 'gd') {
          if ((t.serverType == 'cz' && data.GH_FLAG == 1) || (t.serverType == 'gd' && data.ORG_ID != 'guangde001' && data.GH_FLAG == 1)) { //池州人民医院,东至人民&& 广德除人民医院外的
            t.pathParams.ORG_ID = data.ORG_ID
            t.pathParams.ORG_NAME = data.ORG_NAME
            t.pathParams.ORG_YQ = data.ORG_YQ
            t.$store.state.store_yygh.ks_tree_data = ''
            t.$store.state.store_yygh.ks_tree_index = 0
            t.$store.state.store_yygh.hz_code = ''
            t.linkToPage('yygh_ks', t.pathParams)
          } else if (data.ORG_ID == 'guangde001' && data.GH_FLAG == 1) { //广德人民医院
            t.linkToPage('yyghgd_index', {
              ORG_ID: data.ORG_ID,
              ORG_NAME: data.ORG_NAME
            })
          } else {
            t.$dialog.alert({
                confirmButtonText: '好的',
                message: data.ORG_NAME + '还未进行数据对接，暂时无法预约。',
              })
              .then(() => {

              })
          }
        } else {
          t.pathParams.ORG_ID = data.ORG_ID
          t.pathParams.ORG_NAME = data.ORG_NAME
          t.pathParams.ORG_YQ = data.ORG_YQ
          t.$store.state.store_yygh.ks_tree_data = ''
          t.$store.state.store_yygh.ks_tree_index = 0
          t.$store.state.store_yygh.hz_code = ''
          t.linkToPage('yygh_ks', t.pathParams)
        }

      },
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .yygh_index .filter-wrap {
    padding: 15px;
    font-size: 15px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .yygh_index .filter-wrap .wrap-l>>>.van-dropdown-menu__bar {
    height: 20px;
    font-size: 15px;
    box-shadow: none;
  }

  .yygh_index .filter-wrap .wrap-l>>>.van-dropdown-menu__item {
    justify-content: flex-start;
  }

  .yygh_index .filter-wrap .wrap-l>>>.van-dropdown-item {
    border-top: 1px solid #ebedf0;
    margin-top: 10px;
  }

  .yygh_index .filter-wrap .wrap-l>>>.van-dropdown-menu__title {
    padding-left: 0;
    width: 100%;
    font-weight: bold;
  }

  .yygh_index .filter-wrap .wrap-l>>>.van-dropdown-menu__title.van-dropdown-menu__title::after {
    position: absolute;
    right: 5px;
    top: 70%;
    display: inline-block;
    content: "";
    width: 7px;
    height: 7px;
    border: solid #606266;
    border-width: 1px 1px 0 0;
    -webkit-transform: translate(0, -50%) rotate(45deg);
    transform: translate(0, -50%) rotate(45deg);
  }

  .yygh_index .filter-wrap .wrap-l>>>.van-cell:not(:last-child)::after {
    left: 0;
  }

  .yygh_index .filter-wrap .wrap-l>>>.van-icon-success::before {
    content: '';
    width: 0;
  }

  .yygh_index .filter-wrap .wrap-l>>>.van-cell::before {
    position: absolute;
    right: 20px;
    top: 50%;
    display: inline-block;
    content: "";
    width: 7px;
    height: 7px;
    border: solid #606266;
    border-width: 1px 1px 0 0;
    -webkit-transform: translate(0, -50%) rotate(45deg);
    transform: translate(0, -50%) rotate(45deg);
  }

  .yygh_index .filter-wrap .wrap-l>>>.van-dropdown-item__option--active::before {
    border-color: #00c792;
  }

  .yygh_index .filter-wrap .wrap-l .iconfont {
    vertical-align: 1px;
  }

  .yygh_index .filter-wrap .wrap-r {
    position: relative;
  }

  .yygh_index .filter-wrap .wrap-r .iconfont {
    color: #c0c4cc;
  }

  .yygh_index .filter-wrap .wrap-r .select-wrap {
    position: absolute;
    right: 0px;
    top: 28px;
    width: 90px;
    border-radius: 3px;
    background: #fff;
    overflow: hidden;
  }

  .yygh_index .filter-wrap .wrap-r .select-wrap .item {
    text-align: center;
    padding: 10px;
  }

  .yygh_index .hospital-item {
    padding: 20px 15px;
    background: #fff;
    border-bottom: 1PX solid #ebeef5;
    overflow: hidden;
  }

  .yygh_index .hospital-item .item-img {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    float: left;
    background: #f9f9f9;
  }

  .yygh_index .hospital-item .item-cont {
    margin-left: 40px;
  }

  .yygh_index .hospital-item .item-cont .name {
    font-size: 16px;
    color: #303133;
    font-weight: bold;
    margin: 3px 0;
  }


  .yygh_index .hospital-item .item-cont .addr {
    font-size: 14px;
    line-height: 18px;
    color: #606266;
    display: flex;
  }

  .yygh_index .hospital-item .item-cont .addr .addr-l {
    width: 75%;
  }

  .yygh_index .hospital-item .item-cont .addr .addr-r {
    justify-content: space-around;
    width: 25%;
    text-align: right;
  }
</style>