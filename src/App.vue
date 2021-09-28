<template>
  <site-head></site-head>
  <router-view />
</template>
<script>
import SiteHead from '@/components/SiteHead'
export default {
  components:{
    SiteHead
  }
}
</script>
<style lang="scss">
*{
  box-sizing: border-box;
}
  #app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
  }
</style>
