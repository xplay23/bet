<template>
    <div class="user_info">
        <my-head>
            {{this.$store.getters.getUserInfo?.name}}
        </my-head>
        <h4>
            {{this.$store.getters.getUserInfo?.login}}
        </h4>
        <div>
            Счет = <strong>{{allWinCashe - allLooseCashe}}</strong>
        </div>
    </div>
    <div>
        <h4>Созданые мною (<strong>{{rates.length}}</strong>)</h4>
        <rate-item class="link" v-for="rate in rates" :rate="rate" :key="rate.id" />
    </div>
    <div>
        <h4>Выиграные (<strong>{{winRates.length}}</strong>)</h4>
        <rate-item class="link" v-for="rate in winRates" :rate="rate" :key="rate.id" />
    </div>
    <div>
        <h4>Проигранные (<strong>{{loseRates.length}}</strong>)</h4>
        <rate-item class="link" v-for="rate in loseRates" :rate="rate" :key="rate.id" />
    </div>
</template>
<script>
import axios from 'axios'
export default {
    data(){
        return{
            rates: [],
            winRates:[],
            loseRates:[],
            allWinCashe:0,
            allLooseCashe:0,
        }
    },
    mounted(){

        axios.post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                action: 'myRates',
                token: this.$store.getters.getUserToken
            }).then(resp=>{
                this.rates = resp.data.length ? resp.data : [];
            });

        axios.post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                action: 'winRates',
                token: this.$store.getters.getUserToken
            }).then(resp=>{
                this.winRates = resp.data.length ? resp.data : [];
                this.allWinCashe =  this.winRates.reduce((previousValue, currentValue)=>{
                    return previousValue + parseInt(currentValue.count);
                },0);
            });

        axios.post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                action: 'loseRates',
                token: this.$store.getters.getUserToken
            }).then(resp=>{
                this.loseRates = resp.data.length ? resp.data : [];
                this.allLooseCashe =  this.loseRates.reduce((previousValue, currentValue)=>{
                    return previousValue + parseInt(currentValue.count);
                },0);
            });

    }
}
</script>

<style scoped>
    .user_info{
        text-align: left;
    }
    h4{
        text-align: left;
        font-size: 1.3em;
        font-weight: bold;
        margin-bottom: .2em;
    }
</style>