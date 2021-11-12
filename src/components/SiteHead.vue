<template>
    <div class="site_head">
        <router-link class="logo" to="/">
            <img src="../assets/logo.svg" alt="">
        </router-link>

        <div id="nav">
            <router-link to="/add" v-if="this.$store.getters.isLogin">Добавить ставку</router-link>
            <router-link to="/leaderboard">Таблица лидеров</router-link>
        </div>
        <div class="user">
            <div v-if="this.$store.getters.isLogin"  style="display: flex;">
                <router-link class="link_inner username" to="/user">
                    <div v-if="this.$store.getters.getUserInfo?.avatarInfo?.path" class="avatar">
                        <img :src="this.$store.getters.getUserInfo?.avatarInfo?.path" alt="">
                    </div>
                    <span>{{this.$store.getters.getUserInfo?.name}} {{this.$store.getters.getUserInfo?.id}} </span>
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
    name: 'site-head',
    mounted(){
        this.$store.dispatch('getUser');
        // console.log(this.$store.getters.getUserInfo);
    }
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
      padding: .3em;
      display: inline-block;
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
          width: 100%;
          height: 100%;
          object-fit: cover;
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
          width: 100%;
          height: 100%;
          object-fit: cover;
          margin: auto;
      }
      &:hover{
          background: rgb(228, 228, 228);
      }
  }
  .avatar{
    //   padding: .1em;
      border: 1px solid #ccc;
      margin-right: 1em;
      width: 1.5em;
      height: 1.5em;
      flex-shrink: 0;
      display: flex;
      border-radius: 50%;
      overflow: hidden;
  }
  @media (max-width: 768px) {
    .site_head{
        flex-wrap: wrap;
        justify-content: space-between;
    }
    #nav{
        width: 100%;
        order: 2;
    }
    .username{
        span{
            max-width: 5em;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
  }
</style>