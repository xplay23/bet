<template>
  <div class="app">
    <site-head></site-head>
    <router-view />
    <my-modal ref="error_popup"></my-modal>
  </div>
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
  .app{
    max-width: 800px;
    margin: auto;
  }
</style>
