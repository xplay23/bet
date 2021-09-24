<template>
  <div class="home">
    <div v-for="(rate,index) in rates" :key="index">
      <router-link :to="{ name: 'Bet', params: { id: rate.id }}">{{rate.name}}</router-link>
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
      rates: null
    }
  },
   mounted() {
      axios
        .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
          action: 'history'
        })
        .then(response => {
          this.rates = response.data;
          console.log(this.rates)
        });
        
    }
}
</script>
