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
    getUser: (context,info)=>{
      axios.post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                  action: 'getUser',
                  token: info.token
              }).then((resp)=>{
                const data = resp.data
                context.commit('setLogin', data);
                if(info.cb){
                  info.cb();
                }
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
    login(context,formInfo){
      axios
        .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
            action: 'login',
            login: formInfo.login,
            password: formInfo.password
        }).then((response)=>{
            const data = response.data;
            if(data.errorId){
                return false;
            }
            context.dispatch('getUser',{
              token: data['token'],
              cb: function(){
                router.push('/user');
              }
            })

        })
    },
   
  },
  modules: {

  }
})
// 