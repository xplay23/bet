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
    setToken(state,token){
      state.token = token;
    },
    setLogin(state,userInfo){
      state.userInfo = userInfo;
      if(userInfo.id){
        state.userIsLogin = true;
      }else{
        state.userIsLogin = false;
      }
    },
    setAvatar(state,avatarInfo){
      state.userInfo = userInfo;
      if(userInfo.id){
        state.userIsLogin = true;
      }else{
        state.userIsLogin = false;
      }
    },
    
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

    getUser: (context,cb)=>{
      axios.post('https://devlink1.tk/bm/vue_lessons/betting_admin/index.php', {
                  action: 'getUser',
                  token: context.getters.getUserToken
              }).then((resp)=>{
                const data = resp.data
                context.commit('setLogin', data);
                if(cb){
                  cb();
                }
              })
    },

    unLogin(context){
      axios.post('https://devlink1.tk/bm/vue_lessons/betting_admin/index.php', {
          action: 'unLogin',
          token: context.getters.getUserToken
      })
      context.commit('setLogin', {});
      router.push('/');
    },

    async login(context,formInfo){
      return axios
        .post('https://devlink1.tk/bm/vue_lessons/betting_admin/index.php',{
            action: 'login',
            login: formInfo.login,
            password: formInfo.password
        }).then((response)=>{
            const data = response.data;
            
            if(data.errorId){
                // console.log(this,context,this.$root,this.$refs);
                // this.$refs.error_popup.open(data.errorText);
                return data;
              }
              context.commit('setToken', data['token']);
              
              context.dispatch('getUser',function(){
                router.push('/user');
              })
              return data;

        })
    },
    resetPass(context,formInfo){
      axios
        .post('https://devlink1.tk/bm/vue_lessons/betting_admin/index.php',{
            action: 'resetPass',
            login: formInfo.login
        }).then((response)=>{
            const data = response.data;
            
            if(data.errorId){
                return false;
            }
            // router.push('/changepass/'+data.token);

        })
    },
    changePass(context,formInfo){
      axios
        .post('https://devlink1.tk/bm/vue_lessons/betting_admin/index.php',{
            action: 'changePass',
            password: formInfo.password,
            token: formInfo.token,
        }).then((response)=>{
            const data = response.data;
            
            if(data.errorId){
                return false;
            }
            router.push('/login');

        })
    },
    updateAvatar(context,avatarId){
      axios
        .post('https://devlink1.tk/bm/vue_lessons/betting_admin/index.php',{
            action: 'updateAvatar',
            token: context.getters.getUserToken,
            avatarid: avatarId
        }).then(()=>{
            context.dispatch('getUser');
        })
    },
   
  },
  modules: {

  }
})
// 