<template>
  <div class="home">
    <my-head>Главная страница</my-head>
    <div class="labels">
      <my-button :class="type==='all' ? 'active' : ''" @click="changeTab('all')">Все</my-button>
      <my-button :class="type==='my' ? 'active' : ''" v-if="this.$store.getters.isLogin" @click="changeTab('my')">Доступные мне</my-button>
      <my-button :class="type==='complete' ? 'active' : ''" @click="changeTab('complete')">Законченые</my-button>

    </div>
    <rate-item class="link" v-for="(rate,index) in ratesActive" :rate="rate" :key="index" />
  </div>
</template>

<script>
// @ is an alias to /src
import axios from 'axios'
export default {
  name: 'Home',
  data(){
    return {
      rates: [],
      ratesActive: [],
      type: 'all'
    }
  },
  methods:{
    changeTab(type){
      this.type = type;
      switch(type){
        case 'complete':
          this.ratesActive = this.rates.filter(el=>{
              return el.statusid == 1;
          })
          break;
        case 'my':
          this.ratesActive = this.rates.filter(el=>{
              return el.UserCanRate.some(elIn => parseInt(elIn.userid) === parseInt(this.$store.getters.getUserInfo.id));
          });
          break;
        default:
          this.ratesActive = this.rates;
          break;
      }
    },
  },
  mounted() {
    axios
      .post('https://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
        action: 'history'
      })
      .then(response => {
        this.rates = response.data;
        this.changeTab('all')
      });
    
      
  }
}
</script>
<style scoped>
  .home{
    max-width: 800px;
    margin: auto;
  }
  .link{
    transition: opacity .3s ease;
  }
  .labels{
    text-align: left;
  }
</style>
