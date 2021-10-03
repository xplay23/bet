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
      addComplete: true,
      onlyMe: false,
    }
  },
  methods:{
    filterRates(){
      this.ratesActive = this.rates.filter(el=>{
        if(!this.addComplete){
          return el.statusid == 0;
        }
        return true;
      }).filter(el=>{
        if(this.$store.state.userIsLogin && this.onlyMe){
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

  .labels{
    text-align: left;
  }
</style>