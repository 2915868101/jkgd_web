<template>
  <div :class="[{'tm-container':!resultsData},'zwb_jkzc_detail']">
    <div class="head-wrap s-cfff f-df f-dfv">
      <van-tag round type="success" class="history" @click="linkToPage('/zwb_jkzc_history',{cnum:userInfo.CARD_NUMBER,columnid:queryData.COLUMNID})">自测历史</van-tag>
    </div>
    <div v-if="!resultsData">
      <!--保存题目面板内容-->
      <div v-if="tabContent">
        <!-- 题目切换 -->
        <div class="tm-content">
          <!-- <transition name="fade"> -->
          <div v-if="currentData&&showQs">
            <div class="content-title">{{currentData[1]+1}}.{{currentData[0].TITLE}}</div>
            <div class="content-checker" v-for="(l,j) in currentData[0].OPTIONS" :key="j" @click="selectValue(l)">
              <span :class="['f-mr10',{'vux-radio-core':currentData[0].TOPIC=='R','vux-checkbox-core':currentData[0].TOPIC=='C',checked:(currentData[0].TOPIC=='C'&&array[currentData[1]]&&array[currentData[1]].OPTIONCODE&&array[currentData[1]].OPTIONCODE.indexOf(l.OPTIONCODE)!=-1)||(currentData[0].TOPIC=='R'&&array[currentData[1]]&&array[currentData[1]].OPTIONCODE==l.OPTIONCODE)}]"></span>{{l.CONTENT}}
            </div>
          </div>
          <!-- </transition> -->
        </div>
        <!-- 提示文字 -->
        <div class="middle-tip">
          <span v-if="currentData&&currentData[0].OPTIONS.length > 6">（选项框内向上滚动查看更多)</span>
        </div>
        <!-- 上一步/下一步 -->
        <div class="tm-choose" v-if="columnData.length">
          <div :class="['tm-btn prevBtn',{disabled:!oldi.length}]" @click="onPrevEvet">上一页</div>
          <div :class="['tm-btn nextBtn',{disabled:!(array[currentData[1]]&&array[currentData[1]].OPTIONCODE)}]" @click="onNextEvet" v-if="countNum!=columnData.length">下一页</div>
          <div :class="['tm-btn saveBtn',{disabled:!(array[currentData[1]]&&array[currentData[1]].OPTIONCODE)}]" @click="saveValues" v-else>保存</div>
        </div>
        <div class="tm-title">
          <span @click="showUnfinished" v-if="currentData"><span class="on">{{countNum}}</span> / {{columnData.length}}
            <van-icon name="search"></van-icon>
          </span>
        </div>
        <van-popup v-model="popupVisible" position="bottom" should-scroll-top-on-show should-rerender-on-show>
          <div class="m-unfinished-list">
            <template v-for="(a,i) in columnData">
              <div :class="['num',{isNull:!array[i]||(array[i] && !array[i].OPTIONCODE),disabled:contains(cacheOjumpData,a.QUESCODE)!=-1}]" :key="i" @click="doFinnished(a,i)" v-if="countNum!=columnData.length">{{i+1}}</div>
            </template>
            <div class="f-tac finished" v-if="countNum==columnData.length">问卷已完成，
              <van-button type="primary" mini @click.native="saveValues">点这里保存</van-button>
            </div>
          </div>
        </van-popup>
      </div>
    </div>
    <div class="wsd-indicator-wrapper f-fs14" v-show="showindicator">
      正在提交问卷...
    </div>
    <ul class="m-img-lists2 f-cb" v-if="resultsData">
      <li>
        <div v-if="!resultsData[0].INFO">
          <img class="previewer-demo-img" v-for="(t,i) in resultsData" :key="i" :src="t" @click="showImg(i)">
          <van-image-preview v-model="show" ref="imagePreview" :images="resultsData" @change="onChange" closeable>
            <template v-slot:index>{{ index+1 }} / {{ resultsData.length}}</template>
          </van-image-preview>
        </div>
        <div v-else class="text">{{resultsData1[0].INFO}}</div>
      </li>
    </ul>
  </div>
  <!-- 系统提示框 -->
  <!--  <div v-transfer-dom>
      <confirm v-model="showTip" >
        <p style="text-align:center;">请完成所有问卷题目后保存！</p>
      </confirm>
    </div> -->
</template>
<script>
  export default {
    name: 'question_detail',
    directives: {},
    components: {},
    created() {
      var t = this
      //判断是否注册
      t.isRegister({
        openid: t.wx_userInfo.openid,
        type: 0
      }, function(sucFun) {
        t.userInfo = sucFun.CONTENTS[0]
      })
      t.$store.state.cacheFlag = 0
      t.openid = t.queryData.openid
      t.columnid = t.queryData.COLUMNID
      t.updateTitle(t.queryData.COLNAME)
      t.getQuesImpl()
      t.oldi = localStorage['oldi' + t.columnid] ? JSON.parse(localStorage['oldi' + t.columnid]) : []
      t.array = localStorage['array' + t.columnid] ? JSON.parse(localStorage['array' + t.columnid]) : []
      t.current_OJUMP = localStorage['current_OJUMP' + t.columnid] ? localStorage['current_OJUMP' + t.columnid] : ''
      t.cacheOjump = localStorage['cacheOjump' + t.columnid] ? JSON.parse(localStorage['cacheOjump' + t.columnid]) : []
    },
    computed: {
      currentData() {
        var t = this
        let data = ''
        for (var i = 0; i < t.columnData.length; i++) {
          if ((!t.current_OJUMP && i == 0) || t.columnData[i].QUESCODE == t.current_OJUMP) {
            data = [t.columnData[i], i]
          }
        }
        return data
      },
      cacheOjumpData() {
        var t = this
        var i = t.currentData[1]
        if (t.array[i] && t.array[i].OJUMP) {
          t.cacheOjump = t.spliceIds(t.cacheOjump, t.array[i].QUESLIST.split(','), Number(t.array[i].ISOPEN) ? 'add' : 'del')
          if (!Number(t.array[i].ISOPEN)) {
            t.delArrayEle()
          }
        }
        localStorage['cacheOjump' + t.columnid] = JSON.stringify(t.cacheOjump)
        return t.cacheOjump
      },
      countNum() {
        var t = this
        var n = 0
        for (var i = 0; i < t.array.length; i++) {
          var arr = t.array[i]
          if (t.columnData[i]) {
            if (arr || String(t.cacheOjump).indexOf(t.columnData[i].QUESCODE) != -1) {
              n++
            }
          }
        }
        return n
      }
    },
    watch: {
      oldi() {
        var t = this
        t.showQs = false
        t.$nextTick(function() {
          t.showQs = true
        })
      }
    },
    data() {
      return {
        cacheOjump: [],
        showQs: true,
        oldi: [],
        current_OJUMP: '',
        array: [],
        flag: true,
        timer: null,
        openid: '',
        columnid: '',
        tabContent: true,
        showTip: false,
        columnData: '',
        popupVisible: false,
        showindicator: false,
        resultsData: '',
        loadOnce: true,
        flag_next: false,
        index: 0,
        show: false,
      }
    },
    methods: {
      showImg(i) {
        var t = this
        t.show = true;
        t.index = i
        t.$refs.imagePreview.swipeTo({
          index: t.index
        })
      },
      showUnfinished() {
        var t = this
        t.popupVisible = true
      },
      doFinnished(a, i) {
        var t = this
        if (t.contains(t.cacheOjump, a.QUESCODE) == -1) {
          t.current_OJUMP = t.columnData[i].QUESCODE
          t.popupVisible = false
        }
      },
      onPrevEvet() {
        var t = this
        if (t.oldi.length) {
          t.current_OJUMP = t.columnData[t.oldi[0]].QUESCODE
          t.$nextTick(function() {
            t.oldi.shift()
            t.cacheData()
          })
        }
      },
      //下一步
      onNextEvet() {
        var t = this
        var c = t.currentData
        clearTimeout(t.timer)
        if (t.countNum != t.columnData.length && t.currentData[1] == t.columnData.length - 1) {
          t.showTip = true
        }
        if (t.array[c[1]] && t.array[c[1]].OPTIONCODE) {
          if (t.contains(t.oldi, c[1]) == -1) {
            t.oldi.unshift(c[1])
          }
          if (t.array[c[1]].ISOPEN) {
            t.current_OJUMP = t.array[c[1]].OJUMP
          } else {
            t.current_OJUMP = t.columnData[c[1] + 1].QUESCODE
          }
          t.cacheData()
        }
      },
      //缓存数据
      cacheData() {
        var t = this
        localStorage['oldi' + t.columnid] = JSON.stringify(t.oldi)
        localStorage['array' + t.columnid] = JSON.stringify(t.array)
        localStorage['current_OJUMP' + t.columnid] = t.current_OJUMP
      },
      delArrayEle() {
        var t = this
        if (t.cacheOjump) {
          for (var i = 0; i < t.array.length; i++) {
            var arr = t.array[i]
            if (arr) {
              for (var j = 0; j < t.cacheOjump.length; j++) {
                var code = t.cacheOjump[j]
                if (arr.QUESCODE == code) {
                  t.$set(t.array, i, null)
                }
              }
            }
          }
          localStorage['array' + t.columnid] = JSON.stringify(t.array)
        }
      },
      //选择问题选项
      selectValue(l) {
        var t = this;
        var c = t.currentData[0]
        var i = t.currentData[1]
        t.$nextTick(function() {
          if (!t.array[i] || (t.array[i] && !t.array[i].OPTIONCODE) || c.TOPIC == 'R') {
            t.$set(t.array, i, {
              QUESCODE: c.QUESCODE,
              OPTIONCODE: l.OPTIONCODE
            })
            if (l.ISOPEN) {
              t.$set(t.array[i], 'ISOPEN', l.ISOPEN)
              t.$set(t.array[i], 'OJUMP', l.OJUMP)
              t.$set(t.array[i], 'QUESLIST', l.QUESLIST)
            }
            if (c.TOPIC == 'R') {
              t.timer = setTimeout(function() {
                if (i < t.columnData.length - 1) {
                  if (t.contains(t.oldi, t.currentData[1]) == -1) {
                    t.oldi.unshift(t.currentData[1])
                  }
                  if (l.ISOPEN) {
                    t.current_OJUMP = l.OJUMP
                    t.cacheData()
                  } else {
                    t.onNextEvet()
                  }
                } else {
                  t.cacheData()
                }
              }, 600)
            }
          } else {
            var arr = t.spliceIds(t.array[i].OPTIONCODE.split(','), l.OPTIONCODE)
            if (arr.length) {
              t.$set(t.array[i], 'OPTIONCODE', String(arr))
            } else {
              t.$set(t.array, i, null)
            }
            t.cacheData()
            t.flag_next = true
          }
        })
      },
      /*调用获取问卷题目接口*/
      getQuesImpl: function() {
        var t = this
        t.ajax({
          method: 'post',
          serverId: 'WXTJ00015',
          params: {
            OPENID: t.wx_userInfo.openid,
            COLUMNID: t.queryData.COLUMNID,
            CNUM: t.queryData.cnum
          },
          url: t.serverPath1,
          success(data) {
            t.columnData = JSON.parse(JSON.stringify(data).replace(/&lt;/g, '<').replace(/&gt;/g, '>')) || []
          }
        })
      },
      onChange(index) {
        this.index = index;
      },
      /*调用保存问卷答案接口*/
      saveValues() {
        var t = this;
        if (t.array[t.currentData[1]] && t.array[t.currentData[1]].OPTIONCODE) {
          var array = [];
          for (var i in t.array) {
            if (t.array[i] && t.array[i].OPTIONCODE) {
              array.push(t.array[i]);
            }
          }
        }
        t.$nextTick(function() {
          t.showindicator = true
          t.tabContent = !t.showindicator
          t.ajax({
            serverId: 'WXTJ00026',
            loading: false,
            params: {
              OPENID: t.wx_userInfo.openid,
              COLUMNID: t.queryData.COLUMNID,
              array: JSON.stringify(array),
              CNUM: t.queryData.cnum
            },
            url: t.serverPath1,
            success(data) {
              console.log({
                OPENID: t.wx_userInfo.openid,
                COLUMNID: t.queryData.COLUMNID,
                array: JSON.stringify(array),
                CNUM: t.queryData.cnum
              })
              console.log(data)
              if (!data || !data.length || (!data[0].IMAGE0 && !data[0].INFO)) {
                if (data && data.length) {
                  t.$dialog.alert({
                    title: '',
                    message: data[0].ERROR,
                  })
                } else {
                  t.$dialog.alert({
                    title: '',
                    message: '未知异常',
                  })
                }
                t.showindicator = false;
                t.tabContent = !t.showindicator
              } else {
                localStorage['oldi' + t.columnid] = ''
                localStorage['array' + t.columnid] = ''
                localStorage['current_OJUMP' + t.columnid] = ''
                localStorage['cacheOjump' + t.columnid] = ''
                if (!data[0].INFO) {
                  t.resultsData = []
                  for (var i = 0; i < data.length; i++) {
                    t.resultsData.push('data:image/jpeg;base64,' + data[i]['IMAGE' + i])
                  }
                } else {
                  t.resultsData = data
                }
                console.log(t.resultsData)
                setTimeout(function() {
                  t.$store.state.cacheFlag = 1
                  t.showindicator = false
                  t.tabContent = !t.showindicator
                }, 300)
              }
            }
          })
        })
        t.popupVisible = false
      },
    },
    mounted() {}
  }
</script>
<style scoped>
  html,
  body {
    height: 100%;
    overflow-x: hidden;
    overflow-y: hidden;
    background-color: #eef1f2;
  }

  .history {
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 13px;
  }

  .vux-radio-core,
  .vux-checkbox-core {
    display: inline-block;
    width: 12px;
    height: 12px;
    border: 1px solid #bbb;
    position: relative;
  }

  .vux-radio-core.checked,
  .vux-checkbox-core.checked {
    background-color: #00a0e9;
  }

  .vux-radio-core {
    border-radius: 50%;
  }

  .vux-checkbox-core.checked:before {
    content: '';
    display: inline-block;
    border: 2px solid #fff;
    border-top-width: 0;
    border-right-width: 0;
    width: 9px;
    height: 5px;
    -webkit-transform: rotate(-50deg);
    position: absolute;
    top: 2px;
    left: 1px;
  }

  .middle-tip {
    font-size: 14px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    color: white;
  }

  .tm-content {
    margin: 180px 30px 0 30px;
    height: 250px;
    background-color: #fff;
    padding: 15px;
    border-radius: 10px 10px;
    overflow-y: auto;
    font-size: 14px;
  }

  .tm-choose {
    text-align: center;
    /*margin-top: 20px;*/
  }

  @media screen and (max-width: 320px) {
    .tm-content {
      margin: 143px 20px 0 20px;
    }
  }

  @media screen and (max-width: 375px) and (max-height: 812px) {
    .tm-content {
      margin: 224px 20px 0 20px;
    }
  }

  @media screen and (max-width: 375px) and (max-height: 736px) {
    .tm-content {
      margin: 175px 20px 0 20px;
    }
  }

  @media screen and (min-width: 400px) {
    .tm-content {
      margin: 240px 30px 0 30px;
    }
  }

  @media screen and (min-height: 800px) {
    .tm-content {
      margin: 260px 30px 0 30px;
    }
  }

  .content-checker {
    margin-top: 14px;
    margin-left: 10px;
    color: #737373;
    font-size: 15px;
  }

  .content-title {
    font-size: 16px;
  }

  .tm-btn.disabled {
    opacity: 0.4;
  }

  .tm-btn {
    display: inline-block;
    width: 100px;
    height: 33px;
    line-height: 33px;
    font-size: 16px;
    text-align: center;
  }

  .tm-btn.prevBtn {
    background-color: #fff;
    color: #ff7723;
  }

  .tm-btn.nextBtn,
  .tm-btn.saveBtn {
    margin-left: 15px;
    background-color: #ff7723;
    color: #fff;
  }

  .tm-title {
    text-align: center;
    padding-top: 9px;
    font-size: 18px;
    color: #fff;
  }

  .tm-title .weui-icon-search {
    color: #fff;
  }

  .m-unfinished-list {
    padding-left: 15px;
    padding-right: 15px;
    padding-bottom: 15px;
    height: auto;
    max-height: 308px;
    overflow-y: auto;

  }

  .m-unfinished-list .num {
    display: inline-block;
    width: 35px;
    height: 35px;
    line-height: 35px;
    font-size: 14px;
    text-align: center;
    border-radius: 5px;
    margin-left: 15px;
    margin-top: 15px;
    background-color: #18aafd;
    color: #fff;
    position: relative;
  }

  .m-unfinished-list .num.isNull {
    background-color: #dcdcdc;
    color: #444;
  }

  .m-unfinished-list .num.disabled {
    background-color: #bbb;
    color: #999;
  }

  .m-unfinished-list .num.disabled:before {
    content: 'x';
    display: block;
    position: absolute;
    width: 100%;
    height: 100%;
    line-height: 35px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    color: #f00;
  }

  .m-unfinished-list .finished {
    padding-top: 15px;
    font-size: 14px;
  }

  .m-img-lists2 {
    margin-top: 56px;
  }

  .m-img-lists2 img {
    display: block;
    width: 100%;
  }

  .wsd-indicator-wrapper {
    position: fixed;
    left: 50%;
    top: 50%;
    margin-left: -90px;
    margin-top: -90px;
    border-radius: 5px;
    background: rgba(0, 0, 0, .7);
    color: #fff;
    box-sizing: border-box;
    text-align: center;
    z-index: 9;
    width: 180px;
    height: 180px;
    background-image: url(../../assets/img/gd2/22.gif);
    background-size: 101px 113px;
    background-repeat: no-repeat;
    background-position: center 20px;
    padding-top: 140px;
    font-size: 1rem;
  }

  .tm-container {
    width: 100%;
    height: 100%;
    background-image: url(../../assets/img/gd2/index_02.png);
    background-size: 100% 100%;
    background-repeat: no-repeat;
  }
</style>