<template>
  <div class="nopage g-wrap"></div>
</template>
<script>
export default {
  name: 'nopage',
  components: {
  },
  computed: {
  },
  created() {
    var t = this
    t.updateTitle('404')
  },
  data() {
    return {
    }
  },
  methods: {
  },
  mounted() {
  }
}

</script>
<style scoped>
.nopage {
  background: #fff url(../../assets/img/util/404.jpg) center 0 no-repeat;
  background-size: 100% auto;
}
</style>
