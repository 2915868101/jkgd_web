<template>
  <div class="jtys_qysq g-wrap s-bgcfff">
    <van-tabs sticky title-active-color="#00c792" line-width="50px" v-model="type" @click="onClickTabs">
      <van-tab title="申请中">
        <div class="record-list s-bgcfff" v-if="recordList1">
          <div class="list-item" v-for="(c,i) in recordList1" :key="i">
            <div class="item-head f-pt15 f-pb15 f-pr15 f-pl15">
              <div class="flex-item">
                <div class="name s-c303133 f-fs15">{{c.SIGN_ORG}}</div>
              </div>
            </div>
            <div class="item-cont">
              <div class="line-item f-df f-lh24"><span class="tit">签约人姓名：</span><span class="f-df1">{{c.XM}}</span></div>
              <div class="line-item f-df f-lh24"><span class="tit">签约人手机：</span><span class="f-df1">{{c.PHONE}}</span></div>
              <div class="line-item f-df f-lh24"><span class="tit">申请时间：</span><span class="f-df1">{{c.CREATEDATE}}</span></div>
              <div class="line-item f-df f-lh24"><span class="tit">签约人地址：</span><span class="f-df1">{{c.HKDZ}}</span></div>
              <div class="btn-item">
                <van-button class="item-btn" round color="#00c792" plain @click="doPassed(c)">审核通过</van-button>
              </div>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordList1.length" description="暂无签约申请" />
      </van-tab>
      <van-tab title="已通过">
        <div class="record-list s-bgcfff" v-if="recordList2">
          <div class="list-item" v-for="(c,i) in recordList2" :key="i">
            <div class="item-head f-pt15 f-pb15 f-pr15 f-pl15">
              <div class="flex-item">
                <div class="name s-c303133 f-fs15">{{c.SIGN_ORG}}</div>
              </div>
            </div>
            <div class="item-cont">
              <div class="line-item f-df f-lh24"><span class="tit">签约人姓名：</span><span class="f-df1">{{c.XM}}</span></div>
              <div class="line-item f-df f-lh24"><span class="tit">签约人手机：</span><span class="f-df1">{{c.PHONE}}</span></div>
              <div class="line-item f-df f-lh24"><span class="tit">申请时间：</span><span class="f-df1">{{c.CREATEDATE}}</span></div>
              <div class="line-item f-df f-lh24"><span class="tit">签约人地址：</span><span class="f-df1">{{c.HKDZ}}</span></div>
            </div>
          </div>
        </div>
        <van-empty v-show="!recordList2.length" description="暂无已通过签约申请" />
      </van-tab>
    </van-tabs>
  </div>
</template>
<script>
  export default {
    name: 'jtys_qysq',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('签约申请')
      t.getRecordList()
    },
    data() {
      return {
        recordList1: '',
        recordList2: '',
        type: 0,
      }
    },
    methods: {
      // tabs切换
      onClickTabs(index) {
        var t = this
        t.type = index
        t.getRecordList()
      },
      getRecordList() {
        var t = this
        t.ajax({
          serverId: 'DYWJTYSTZH03',
          url: t.serverPath1,
          params: {
            CARDNUM: t.queryData.CARDNUM,
            TYPE: t.type
          },
          success: function(data) {
            if (data[0] && data[0].CODE == 0) {
              if (t.type == 0) {
                t.recordList1 = data[0].CONTENTS || []
              } else {
                t.recordList2 = data[0].CONTENTS || []
              }
            }
            console.log(data)
          }
        })
      },
      // 通过
      doPassed(c) {
        var t = this
        t.ajax({
          serverId: 'DYWJTYSTZH02',
          url: t.serverPath1,
          params: {
            CARDNUM: c.ZJHM,
            XM: c.XM,
            SJHM: c.PHONE,
            ADDRESS: c.HKDZ,
            ZGBH: t.queryData.YSID,
          },
          success: function(data) {
            if (data[0].code == 200) {
              t.$dialog.alert({
                confirmButtonText: '好的',
                message: data[0].msg
              }).then(function() {
                t.onClickTabs(t.type)
              })
            } else {
              t.$dialog.alert({
                confirmButtonText: '好的',
                message: data[0].msg
              }).then(function() {})
            }
          }
        })
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  .jtys_qysq>>>.van-button {
    height: 25px;
    line-height: 25px;
    width: 80px;
    padding: 0;
  }

  .jtys_qysq>>>.van-tabs--line .van-tabs__wrap {
    height: 60px;
    padding-bottom: 5px;
    border-bottom: 10px solid #f4f9f9;
  }

  .jtys_qysq>>>.van-tab__text {
    font-size: 15px;
  }

  .jtys_qysq>>>[class*=van-hairline]::after {
    border: 0;
  }

  .jtys_qysq>>>.van-tabs__line {
    background: #00c792;
  }

  .jtys_qysq .record-list {
    padding: 0 15px;
    font-size: 14px;
  }

  .jtys_qysq .record-list .list-item {
    background: #fff;
    border: 1PX solid #ebeef5;
    border-radius: 5px;
    margin: 15px 0;
    color: #606266;
  }

  .jtys_qysq .record-list .list-item .item-head .name {
    font-weight: bold;
  }

  .jtys_qysq .record-list .flex-item {
    display: flex;
    justify-content: space-between;
  }

  .jtys_qysq .record-list .dott-icon {
    display: inline-block;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    vertical-align: 2px;
  }

  .jtys_qysq .record-list .list-item .item-cont {
    border-top: 1PX solid #ebeef5;
    border-bottom: 1PX solid #ebeef5;
    padding: 10px 15px;
  }

  .jtys_qysq .record-list .list-item .line-item span.tit {
    display: inline-block;
    width: 90px;
    text-align-last: justify;
  }

  .jtys_qysq .record-list .list-item .btn-item {
    text-align: right;
    padding: 5px 0;
  }

  @media screen and (max-width: 320px) {}
</style>