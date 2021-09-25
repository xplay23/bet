<template>
  <div>
    <!-- createBet -->
    <!-- name
    variations
    count -->
    <my-head>Добавить ставку</my-head>
    <my-input v-model:value="rateName" placeholder="На что ставим"></my-input>
    <my-input v-model:value="rateCount" placeholder="Денежки" type="number"></my-input>
    <hr>

    <my-input v-model:value="rateVariant" placeholder="Варианты ставки"></my-input>
    <button @click="addRate">Добавить вариант</button>

    <div v-for="(rate,index) in rates" :key="index">
        {{rate}}
        <input type="text" v-model="rates[index]">
    </div>
    <hr>
    <button @click="saveRate">Сохранить ставку</button>

  </div>
</template>

<script>
// @ is an alias to /src
import axios from 'axios'
export default {
  name: 'Home',
  data(){
    return {
      rateName: '',
      rateCount: 2,
      rateVariant: '',
      rates:[],

    }
  },
    methods:{
        addRate(){
            this.rates.push(this.rateVariant);
            this.rateVariant = '';
        },
        saveRate(){
            axios
                .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
                    action: 'createBet',
                    name:this.rateName,
                    count:this.rateCount,
                    variations:this.rates,
                })
                .then(response => {
                    console.log(response)
                });
        }
    }
}
</script>
