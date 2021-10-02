<template>
    <h2>
        {{this.$store.getters.getUserInfo?.name}}
    </h2>
    <h4>
        {{this.$store.getters.getUserInfo?.login}}
    </h4>
    <div>
        Созданые мною
        <div v-for="rate in rates" :key="rate.id">
            <router-link class="link__inner" :to="{ name: 'Bet', params: { id: rate.id }}">{{rate.name}}</router-link>
        </div>
    </div>
    <div>
        Выиграные
        <div v-for="rate in winRates" :key="rate.id">
            <router-link class="link__inner" :to="{ name: 'Bet', params: { id: rate.id }}">{{rate.name}}</router-link>
        </div>
    </div>
    <div>
        Проигранные
        <div v-for="rate in loseRates" :key="rate.id">
            <router-link class="link__inner" :to="{ name: 'Bet', params: { id: rate.id }}">{{rate.name}}</router-link>
        </div>
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
            });

        axios.post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                action: 'loseRates',
                token: this.$store.getters.getUserToken
            }).then(resp=>{
                this.loseRates = resp.data.length ? resp.data : [];
            });
    }
}
</script>