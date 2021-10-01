<template>
  <div class="home">
    <div class="labels">
      <label v-if="this.$store.state.userIsLogin">
        <input type="checkbox" @change="filterRates" v-model="onlyMe">
        Только те в которых могу учавствовать
      </label>
      <br>
      <label>
        <input type="checkbox" @change="filterRates" v-model="addComplete">
        Отображать законченые
      </label>
    </div>
    <div class="link" v-for="(rate,index) in ratesActive" :key="index" :class="rate.statusid == 1 ? 'complete' : ''">
      <router-link class="link__inner" :to="{ name: 'Bet', params: { id: rate.id }}">{{rate.name}}</router-link>
      <div class="link__money">
        <img src="../assets/salary.svg">
        <span>
          {{rate.count}}
        </span>
      </div>
    </div>
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
      addComplete: false,
      onlyMe: true,
    }
  },
  methods:{
    filterRates(){
      this.ratesActive = this.rates.filter(el=>{
        // console.log(el);
        if(!this.addComplete){
          return el.statusid == 0;
        }
        return true;
      }).filter(el=>{
        if(this.$store.state.userIsLogin && this.onlyMe){
          console.log(el.UserCanRate);
          return el.UserCanRate.some(elIn => elIn.usereid === this.$store.state.userInfo.id);
        }
        return true;
      });
    }
  },
   mounted() {
      axios
        .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
          action: 'history'
        })
        .then(response => {
          this.rates = response.data;
          this.filterRates();
        });
     
        
    }
}
</script>
<style scoped>
  .home{
    max-width: 800px;
    margin: auto;
  }
  .complete{
    background: #e3ffe3;
    text-decoration: line-through;
  }
  .link{
    margin-bottom: 1em;
    text-align: left;
    padding: 1em;
    border: 1px solid rgb(124, 124, 124);
    display: flex;
    align-items: flex-start;
  }
  .link__inner{
    color: #000;
    text-decoration: underline;
  }
  .complete .link__inner{
    text-decoration: inherit;
  }
  .link__inner:hover{
    text-decoration: none;
  }
  .link__money{
    margin-left: auto;
    font-weight: bold;
    display: flex;
    align-items: center;
  }
  .link__money img{
    width: 1.5em;
    margin-right: .5em;
  }
  .labels{
    text-align: left;
  }
</style>