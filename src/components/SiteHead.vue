<template>
    <div class="site_head">
        <router-link class="logo" to="/">
            <img src="../assets/logo.svg" alt="">
        </router-link>

        <div id="nav">
            <router-link to="/">Главная</router-link>
            <router-link to="/add" v-if="this.$store.getters.isLogin">Добавить ставку</router-link>
        </div>
        <div class="user">
            <div v-if="this.$store.getters.isLogin"  style="display: flex;">
                <router-link class="link_inner" to="/user">
                    <div v-if="this.$store.getters.getUserInfo?.avatarInfo?.path" class="avatar">
                        <img :src="this.$store.getters.getUserInfo?.avatarInfo?.path" alt="">
                    </div>
                    <span>{{this.$store.getters.getUserInfo?.name}}</span>
                </router-link>
                <button @click="this.$store.dispatch('unLogin')" class="link_inner">
                    Выйти
                </button>
            </div>
            <div v-else style="display: flex;">
                <router-link class="link_inner" to="/register">
                    <span>Регистрация</span>
                </router-link>
                <router-link class="link_inner" to="/login">
                    <span>Вход</span>
                </router-link>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'site-head'
}
</script>
<style scoped lang="scss">
    #nav {
    padding: 30px;
    margin: auto;
    a {
      font-weight: bold;
      color: #2c3e50;
      margin: 0 .3em;
      &.router-link-exact-active {
        color: #42b983;
      }
    }
  }
  .site_head{
        display: flex;
        align-items: center;
        max-width: 800px;
        margin: auto;
  }
  .logo{
      width: 2.5em;
      img{
          max-width: 100%;
      }
  }
  .link_inner{
      text-decoration: none;
      padding: 0.4em 1.1em;
      border: 1px solid rgb(228, 228, 228);
      margin: 0 .2em;
      color: #000;
      transition: background 100ms;
      display: flex;
      align-items: center;
      cursor: pointer;
      img{
          max-width: 100%;
          margin: auto;
      }
      &:hover{
          background: rgb(228, 228, 228);
      }
  }
  .avatar{
      padding: .1em;
      border: 1px solid #ccc;
      margin-right: 1em;
      width: 1.5em;
      display: flex;
      border-radius: 50%;
      overflow: hidden;
  }
</style>