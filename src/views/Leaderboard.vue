<template>
    <my-head>Таблица лидеров</my-head>
    <!-- {{users}} -->
    <table>
        <thead>
            <tr>
                <td>Имя</td>
                <td>Счет</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(user,index) in users" :key="index">
                <td>{{user.name}}</td>
                <td>{{user.winCount - user.looseCount}}</td>
            </tr>
        </tbody>
    </table>
</template>
<script>
import axios from 'axios'
export default {
    data(){
        return{
            users:[]
        }
    },
    mounted(){

        axios.post('https://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                action: 'leaderboard',
            }).then(resp=>{
                if(Object.keys(resp.data).length < 1) return false;

                Object.keys(resp.data).forEach(el=>{
                    const tempEl = resp.data[el];
                    tempEl.winCount = tempEl['win'].reduce((prew,next)=>{
                        return prew + parseInt(next);
                    },0)
                    tempEl.looseCount = tempEl['loose'].reduce((prew,next)=>{
                        return prew + parseInt(next);
                    },0)
                    this.users.push(tempEl);
                })

                this.sortUsers();
            });

    },
    methods:{
        sortUsers(){
            this.users.sort(function(a,b){
                return (a.winCount - a.looseCount) - (b.winCount - b.looseCount);
            })
        }
    }
}
</script>

<style scoped lang="scss">
    table{
        width: 100%;
        border-collapse: collapse;
    }
    thead{
        background: rgb(236, 236, 236);
        font-weight: bold;
    }
    td{
        padding: .7em;
        border: 1px solid #ccc;
        &:first-child{
            text-align: left;
        }
    }
</style>