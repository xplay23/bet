<template>
    <div>
        <my-head>Логин</my-head>
        <div class="form">
            <my-input type="text" v-model:value="login" placeholder="Логин" />
            <my-input id="pass" v-model:value="password" type="text" placeholder="Пароль" />
            <div class="error">{{error.errorText}}</div>
            <my-button @click="register">Отправить</my-button>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    data(){
        return {
            login: "",
            password: "",
            error: {}
        }
    },
    methods:{
        valid(string,type){
            switch(type){
                case 'login':
                    return string.length > 1;
                    break;
                case 'password':
                    return string.length > 3;
                    break;
            }
        },
        register(){
            if(!this.login){
                alert('Введи логин');
                return false;
            }
            if(!this.password ){
                alert('Введи пароль');
                return false;
            }
            axios
                .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{

                    action: 'login',
                    login: this.login,
                    password: this.password

                }).then((response)=>{
                    console.log(response.data)
                    const data = response.data;
                    if(data.errorId){
                        this.error = data;
                        return false;
                    }
                    this.$store.commit('login',data.token);
                    // this.$router.push({ name: 'User', params: { 'id': data.userid } });
                })
        }
    }
}
</script>
<style lang="scss" scoped>
    .form{
        max-width: 250px;
        margin: auto;
    }
    label{
        display: block;
        text-align: left;
        font-size: .8em;
        margin: 1em 0;
    }
    .error{
        color: #f00;
    }
</style>