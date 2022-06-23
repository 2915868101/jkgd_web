<template>
  <div class="mt_popup_picker">
    <div>
      <van-popup position="bottom" v-model="showPopup">
        <div class="mt_popup_picker_box">
          <div class="mt_popup_picker_header f-df">
            <div class="btn s-c8b8b8b f-pl15" v-if="textTitle">{{textTitle}}</div>
            <div class="btn s-c8b8b8b f-pl15" v-else>请选择预约时间</div>
            <div class="f-df1"></div>
            <div class="btn s-c00c792" @click="onConfirm">确定</div>
          </div>
          <div class="mt_popup_picker_content s-bgcfff">
            <div class="f-cb">
              <div v-for="(c,i) in datas" :key="i" @click="selectTime(c)" v-show="c[valueKey]" :class="['g-ms-'+splitNumber+' item',{'on':value_ori[valueKey]==c[valueKey]}]">
                <div>{{c[valueKey]}}</div>
              </div>
            </div>
          </div>
        </div>
      </van-popup>
    </div>
  </div>
</template>
<script>
  export default {
    name: "mt_popup_picker",
    props: ["datas", "valueKey", "columns", "confirm", "textTitle"],
    created() {
      const t = this;
      t.value_ori = "";
    },
    computed: {
      splitNumber() {
        const t = this;
        var n = "4";
        if (t.columns == 1) {
          n = '12'
        } else if (t.columns == 2) {
          n = '6 item2'
        } else if (t.columns == 3) {
          n = '4'
        } else if (t.columns == 4) {
          n = '3'
        } else if (t.columns == 6) {
          n = '2'
        }
        return n
      },
    },
    data() {
      return {
        showPopup: false,
        value_ori: "",
      };
    },
    methods: {
      selectTime(c) {
        const t = this;
        var date = c.END.split(' ')[0].split('-')
        var time = c.END.split(' ')[1].split(':')
        var month = new Date().getMonth() + 1
        var day = new Date().getDate()
        var hours = new Date().getHours()
        if (month == date[1] && day == date[2]) {
          console.log(time)
          if (hours >= time[0] - 1) {
            t.$toast('当前时间段内请去往窗口预约，或选择其他时间段预约')
          } else {
            t.value_ori = t.value_ori == c ? "" : c;
          }
        } else {
          t.value_ori = t.value_ori == c ? "" : c;
        }
      },
      onCancel() {
        const t = this;
        t.value_ori = "";
        t.showPopup = false;
      },
      onConfirm() {
        const t = this;
        t.showPopup = false;
        if (t.value_ori && typeof confirm == "function") {
          setTimeout(function() {
            t.confirm(t.value_ori);
          }, 300);
        } else {
          t.value_ori = "";
        }
      },
    },
    mounted() {},
  };
</script>
<style>
  .mt_popup_picker_box {
    position: relative;
    padding-top: 50px;
    z-index: 0;
    font-size: 14px;
  }

  .mt_popup_picker_header {
    width: 100%;
    height: 50px;
    line-height: 50px;
    background-color: #ebebeb;
    position: absolute;
    left: 0;
    top: 0;
    z-index: 2;
  }

  .mt_popup_picker_header .btn {
    min-width: 80px;
    text-align: center;
  }

  .mt_popup_picker_content {
    position: relative;
    z-index: 1;
    padding: 20px 10px 30px 10px;
  }

  .mt_popup_picker_content .item.item2 {
    padding: 5px 20px;
  }

  .mt_popup_picker_content .item>div {
    margin: 10px;
    font-size: 12px;
    border: 1px solid #00c792;
    color: #00c792;
    text-align: center;
    height: 33px;
    line-height: 33px;
    border-radius: 17px;
  }

  .mt_popup_picker_content .item.on>div {
    background-color: #00c792;
    color: #fff;
    font-weight: bold;
  }
</style>