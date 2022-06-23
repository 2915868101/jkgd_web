<template>
  <div class="empty f-tac">
    <div class="img" :style="{'background-image':'url('+src+')'}"></div>
    <div class="f-mt10 f-fs12">{{text}}</div>
  </div>
</template>
<script>
export default {
  name: 'empty',
  props: ['src','text'],
  directives: {},
  components: {

  },
  created() {
  },
  computed: {
  },
  data() {
    return {
      img: ''
    }
  },
  methods: {

  },
  mounted() {
  }
}

</script>
<style scoped>
  .empty .img{
    height: 100px;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: auto 80%;
  }
  .empty{
    margin-top: 150px;
    color: #c9c9c9;
  }
</style>
