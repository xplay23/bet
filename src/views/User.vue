<template>
    <div class="user_info">
        <div class="avatar">
            <div class="avatar__img">
                <img v-if="this.$store.getters.getUserInfo.avatarInfo" :src="this.$store.getters.getUserInfo.avatarInfo.path" alt="">
                <label class="input_file">
                    <my-button>Загрузить фото</my-button>
                    <input type="file" ref="file" @change="loadAvatar">
                </label>
            </div>
            <my-head>
                {{this.$store.getters.getUserInfo?.name}}
            </my-head>
        </div>
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
    methods:{
        loadAvatar(){
            this.file = this.$refs.file.files[0];

            let formData = new FormData();
            formData.append('file', this.file);

            axios.post('https://devlink1.tk//bm/vue_lessons/betting_admin/index.php', formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then( (response) => {
                console.log(response.data.avatarId)
                if(!response.data || response.data.errorId){
                    alert('File not uploaded.');
                }else{
                    this.$store.dispatch('updateAvatar',response.data.avatarId);
                }

            });
        },
    },
    mounted(){

        axios.post('https://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                action: 'myRates',
                token: this.$store.getters.getUserToken
            }).then(resp=>{
                this.rates = resp.data.length ? resp.data : [];
            });

        axios.post('https://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                action: 'winRates',
                token: this.$store.getters.getUserToken
            }).then(resp=>{
                this.winRates = resp.data.rates.length ? resp.data.rates : [];
                this.allWinCashe = resp.data.count.reduce((previousValue, currentValue)=>{
                    return previousValue + parseInt(currentValue);
                },0);
            });

        axios.post('https://devlink1.tk//bm/vue_lessons/betting_admin/index.php', {
                action: 'loseRates',
                token: this.$store.getters.getUserToken
            }).then(resp=>{
                console.log(resp.data);
                this.loseRates = resp.data.rates.length ? resp.data.rates : [];
                this.allLooseCashe =   resp.data.count.reduce((previousValue, currentValue)=>{
                    return previousValue + parseInt(currentValue);
                },0);
            });

    }
}
</script>

<style scoped lang="scss">
    .user_info{
        text-align: left;
        padding: 1em;
        border: 1px solid #ccc;
    }
    .avatar{
        display: flex;
        align-items: center;
        &__img{
            width: 9em;
            height: 9em;
            border-radius: 50%;
            // background: #000;
            border: 1px solid #ccc;
            margin-right: 1em;
            z-index: 0;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            position: relative;
            img{
                border-radius: 50%;
                width: 100%;
                height: 100%;
                object-fit: cover;
                position: absolute;
                z-index: -1;
            }
        }
    }
    h4{
        text-align: left;
        font-size: 1.3em;
        font-weight: bold;
        margin-bottom: .2em;
    }
    .input_file{
        position: relative;
        input{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 0;
            opacity: 0;
            z-index: 1;
        }
    }
    @media (max-width: 768px) {
        .avatar{
            display: block;
        }
    }
</style>