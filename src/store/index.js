import axios from 'axios'
import router from '@/router'
import { createStore } from 'vuex'
import createPersistedState from "vuex-persistedstate";

export default createStore({
  plugins: [createPersistedState()],
  state: {
    userIsLogin: false,
    userInfo: {},
    token: ''
  },
  mutations: {
    login(state,token){
      state.token = token;
      this.dispatch('getUser',token);
    },
    setLogin(state,userInfo){
      state.userInfo = userInfo;

      if(userInfo.id){
        state.userIsLogin = true;
      }else{
        state.userIsLogin = false;
      }
    }
    
  },
  getters:{
    getUserInfo(state){
      return state.userInfo
    },
    isLogin(state){
      return state.userIsLogin
    },
    getUserToken(state){
      return state.token;
    },
  },
  actions: {
    getUser: (context,token)=>{
      axios.post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                  action: 'getUser',
                  token: token
              }).then((resp)=>{
                const data = resp.data
                context.commit('setLogin', data);
              })
    },
    unLogin(context){
      axios.post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                  action: 'unLogin',
                  token: context.getters.getUserToken
              })
      context.commit('setLogin', {});
      router.push('/');
    },
   
  },
  modules: {

  }
})
// 