import axios from 'axios'
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
      if(userInfo.id){
        state.userInfo = userInfo;
        state.userIsLogin = true;
      }else{
        state.userInfo = {};
        state.userIsLogin = false;
      }
    }
    
  },
  getters:{
    getUserInfo(state){
      return {
        "userInfo":state.userInfo,
        "userIsLogin":state.userIsLogin
      }
    }
  },
  actions: {
    getUser: async (context,token)=>{
      const {data} = await axios.post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                  action: 'getUser',
                  token: token
              })
      context.commit('setLogin', data);
        
    },
  },
  modules: {

  }
})
// 