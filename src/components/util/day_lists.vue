<template>
  <div
    class="day_lists f-df f-pl10 f-pr10 s-bgcf6f6f6"
    :style="{'background-color':backgroundColor}"
  >
    <div
      v-for="(d,i) in days"
      :key="i"
      :class="['f-df1 f-dfv f-tac f-fs13 f-pt12 f-pb12 s-c8b8b8b',{on:activeIndex==i}]"
      @click="changeDays(d,i)"
    >
      <div>{{i==0?'今天':d.week}}</div>
      <div class="f-mt5">{{showFullDate?d.date:String(d.date).substring(5)}}</div>
    </div>
  </div>
</template>
<script>
export default {
  name: "day_lists",
  props: ["onChange", "backgroundColor", "showDays", "showFullDate"],
  directives: {},
  components: {},
  created() {
    this.days = this.getDayLists({
      num: this.showDays || 7,
    });
    this.onChange(this.days[0], 0);
  },
  computed: {},
  data() {
    return {
      activeIndex: 0,
      days: [],
    };
  },
  methods: {
    changeDays(d, i) {
      this.activeIndex = i;
      if (typeof this.onChange == "function") {
        this.onChange(d, i);
      }
    },
  },
  mounted() {},
};
</script>
<style scoped>
.day_lists .on {
  background-color: #00c792;
  color: #fff;
}
</style>
