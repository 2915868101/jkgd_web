<template>
  <div class="jtys_qy_team g-wrap g-hp100 s-bgcfff">
    <div class="top-wrap">
      <div class="name f-mb10">
        <span class="f-mr10 f-fs15">{{teamInfo.NAME||pathParams.SIGN_DOCTOR||'-'}}</span>
        <van-tag class="f-mr10" plain round size="mini" color="#00c792">
          <div v-if="teamInfo.SFTZ==1">团长</div>
          <div v-else>团员</div>
        </van-tag>
        <i>{{teamInfo.YSLB}}</i>
      </div>
      <div class="addr">
        <i class="iconfont icon-position f-fs14 f-mr8"></i>
        地址:{{pathParams.SIGN_ADDRESS}}
      </div>
    </div>
    <div class="info-wrap s-bgcfff">
      <van-cell title="服务电话" :value="pathParams.YSTEL" />
      <van-cell title="所属社区" :value="pathParams.SIGN_ORG" />
      <van-collapse v-model="activeName" accordion>
        <van-collapse-item title="团队擅长" name="1">
          <div class="f-pl20 f-pr20 f-pt20 f-pb20">--</div>
        </van-collapse-item>
        <van-collapse-item title="团队成员" name="2" v-if="ysList">
          <!-- 医生列表 -->
          <div class="doctor-list">
            <van-row class="list-item" type="flex" justify="space-between" :gutter="20" v-for="(c,i) in ysList" :key="i" @click="tuanzhang(i)">
              <van-col :span="4">
                <div class="item-icon">
                  <i class="iconfont icon-doctor"></i>
                </div>
              </van-col>
              <van-col :span="20">
                <div class="item-cont">
                  <div>
                    <span class="name f-mr10 s-c303133">{{c.NAME}}</span>
                    <span class="post">医生</span>
                  </div>
                  <div>电话：{{c.PHONE||'暂无'}}</div>
                </div>
              </van-col>
            </van-row>
          </div>
        </van-collapse-item>
      </van-collapse>
    </div>
    <div class="g-wp100 f-pt30 f-pb30 s-bgcfff" v-if="!queryData.isFWJG">
      <van-button class="item-btn f-bs3" color="#00c792" round @click="toQy02">确认</van-button>
    </div>
    <div class="g-wp100 f-pt30 f-pb30 s-bgcfff" v-else>
      <van-button class="item-btn f-bs3" color="#00c792" round @click="goBack(-2)">返回</van-button>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jtys_qy_team',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('医生团队')
      t.pathParams = t.queryData
      console.log(t.pathParams)
      t.getTeamList()
    },
    data() {
      return {
        activeName: '2',
        ysList: '',
        teamInfo: []
      }
    },
    methods: {
      getTeamList() {
        var t = this
        t.ajax({
          serverId: 'BMFW00022',
          url: t.serverPath1,
          params: {
            ORG_ID: t.queryData.SIGN_ORG_ID
          },
          success: function(data) {
            console.log(789, data)
            t.ysList = data[0].CONTENTS || []
            t.teamInfo = t.ysList.find(function(obj) {
              return obj.SFTZ === '1'
            }) || {}
          }
        })
      },
      toQy02() {
        var t = this
        t.pathParams.SIGN_DOCTOR = t.teamInfo.NAME
        t.linkToPage('jtys_qy_02', t.pathParams)
      },
      tuanzhang(val) {
        var t = this;
        t.teamInfo = t.ysList[val]
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtys_qy_team .g-wp100>>>.van-button {
    width: 90%;
    margin: 20px 5% 0;
    height: 50px;
    padding: 0 10px;
  }

  .jtys_qy_team .top-wrap {
    font-size: 14px;
    padding: 15px;
    border-bottom: 10px solid #f4f9f9;
  }

  .jtys_qy_team .top-wrap .name span {
    color: #303133;
    font-weight: bold;
  }

  .jtys_qy_team .info-wrap {
    background: #fff;
  }

  .jtys_qy_team>>>.van-cell {
    padding: 15px 20px;
    border-bottom: 1PX solid #ebeef5;
  }

  .jtys_qy_team>>>.van-cell:not(:last-child)::after {
    border-bottom: 0;
  }

  .jtys_qy_team>>>.van-collapse-item__content {
    padding: 0;
  }

  .jtys_qy_team .doctor-list .list-item {
    padding: 15px;
    font-size: 15px;
    line-height: 50px;
    text-align: center;
  }

  .jtys_qy_team .doctor-list .list-item:not(:last-child) {
    border-bottom: 1PX solid #ebeef5;
  }

  .jtys_qy_team .doctor-list .item-icon {
    background: #f2f6fc;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
  }

  .jtys_qy_team .doctor-list .item-icon .iconfont {
    color: #d7dee8;
    font-size: 32px;
  }

  .jtys_qy_team .doctor-list .item-cont {
    line-height: 25px;
    font-size: 14px;
    text-align: left;
  }

  .jtys_qy_team .doctor-list .item-cont .name {
    font-weight: bold;
    font-size: 15px;
  }

  .jtys_qy_team .doctor-list .item-cont .tips span {
    display: inline-block;
  }
</style>