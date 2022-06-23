import Vue from 'vue'

Vue.mixin({
  computed: {
    __static() {
      return process.env.BASE_URL + 'static'
    },
    storeConfig() {
      return this.$store.state.storeConfig
    },
    wx_userInfo() {
      return this.$store.state.wx_userInfo
    },
    wx_configParams() {
      return this.$store.state.wx_configParams
    },
    register_info() {
      return this.$store.state.register_info
    },
    queryData() {
      var t = this
      var obj = ''
      if (t.$route.query.data) {
        obj = JSON.parse(t.$route.query.data)
      }
      return obj
    }
  },
  activated() {},
  created() {},
  data() {
    return {
    }
  },
  watch: {},
  methods: {
  },
  mounted() {},
  beforeDestroy() {},
  deactivated() {}
})