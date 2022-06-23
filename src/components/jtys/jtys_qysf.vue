<template>
  <div class="jtys_qysf g-wrap ">
    <div class="box-wrap f-mb10">
      <div class="b-head f-fwb s-bgcfff"><i class="b-border f-mr8"></i>我的随访记录(最近3天)</div>
      <template v-if="lists">
        <div class="b-cont f-pt5 f-pl15 f-pr15 s-bgcfff" v-if="lists.length">
          <div class="list-item f-bs3" v-for="(c,i) in lists" :key="i">
            <!-- <p class="date f-mb10 s-c303133 f-fwb">{{$moment(c.SJ.time).format('YYYY-MM-DD')}}</p> -->
            <div class="cont">
              <p class="f-df f-mb10">
                <span class="tit">随访时间：</span>
                <span class="txt f-df1">{{c.SJ}}</span>
              </p>
              <p class="f-df f-mb10">
                <span class="tit">随访人：</span>
                <span class="txt f-df1">{{c.NAME||'--'}}</span>
              </p>
              <p class="f-df f-mb10">
                <span class="tit">随访类型：</span>
                <span class="txt f-df1">{{c.TYPE||'--'}}</span>
              </p>
              <p class="f-df f-mb10">
                <span class="tit">随访地点：</span>
                <span class="txt f-df1">{{c.ADDRESS||'--'}}</span>
              </p>
            </div>
          </div>
        </div>
        <van-empty description="暂无随访记录" v-else />
      </template>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'jtys_qysf',
    components: {},
    computed: {},
    created() {
      var t = this
      t.updateTitle('签约随访')
      t.getData()
    },
    data() {
      return {
        lists: ''
      }
    },
    methods: {
      getData() {
        var t = this
        t.ajax({
          serverId: 'DYWJTYSTZH04',
          url: t.serverPath1,
          params: {
            CARDNUM: t.queryData.CARD_NUMBER,
            SJHM: t.queryData.SJHM,
            KSSJ: t.$moment().add(0, 'days').format('YYYY-MM-DD'),
            JSSJ: t.$moment().add(3, 'days').format('YYYY-MM-DD')
          },
          success: function(data) {
            console.log({
              CARDNUM: t.queryData.CARD_NUMBER,
              SJHM: t.queryData.SJHM,
              KSSJ: t.$moment().add(0, 'days').format('YYYY-MM-DD'),
              JSSJ: t.$moment().add(3, 'days').format('YYYY-MM-DD')
            }, )
            console.log(789, data)
            t.lists = data || []
          }
        })
      }
    },
    mounted() {}
  }
</script>
<style scoped>
  @media screen and (max-width: 320px) {}

  .jtys_qysf .list-item {
    margin-bottom: 15px;
    font-size: 14px;
    padding: 15px 15px 0;
    border: 1px solid #ebeef5;
    border-radius: 4px;
  }

  .jtys_qysf .list-item .cont {
    background: #fff;
  }

  .jtys_qysf .cont .tit {
    width: 80px;
    color: #909399;
    text-align-last: justify
  }

  .jtys_qysf .cont .txt {
    text-align: left;
  }
</style>