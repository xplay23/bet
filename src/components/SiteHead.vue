<template>
    <div class="site_head">
        <router-link class="logo" to="/">
            <img src="../assets/logo.svg" alt="">
        </router-link>

        <div id="nav">
            <router-link to="/">Главная</router-link> |
            <router-link to="/add" v-if="this.$store.state.userIsLogin">Добавить ставку</router-link>
        </div>
        <div class="user">
            <div v-if="this.$store.state.userIsLogin">
                <router-link class="link_inner" :to="{ name: 'User', params: { id: this.$store.state.userInfo.id }}">
                    <div v-if="this.$store.state.userInfo.avatarInfo.path" class="avatar">
                        <img :src="this.$store.state.userInfo.avatarInfo.path" alt="">
                    </div>
                    <span>{{this.$store.state.userInfo.name}}</span>
                </router-link>
            </div>
            <div v-else>
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
import axios from 'axios';
export default {
    name: 'site-head',
    mounted() {
         axios
            .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
                action: 'getUser'
            }).then((response)=>{
                const data = response.data;
                console.log('getUser',data);
                if(data.id){
                    this.$store.state.userIsLogin = true;
                    this.$store.state.userInfo = data;
                }else{
                    this.$store.state.userIsLogin = false;
                    this.$store.state.userInfo = {};

                }
            })
    },
}
</script>
<style scoped lang="scss">
    #nav {
    padding: 30px;
    margin: auto;
    a {
      font-weight: bold;
      color: #2c3e50;

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