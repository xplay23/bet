<template>
    <div>
        <my-head>Регистрация</my-head>
        <div class="form">
            <my-input type="text" v-model:value="name" placeholder="Имя" />
            <my-input type="text" v-model:value="login" placeholder="Логин" />
            <my-input id="pass" v-model:value="password" type="text" placeholder="Пароль" />
            <div class="error">{{error.errorText}}</div>
            <label for="pass">
                пароль вбирай не из тех которые юзаешь где-то, ибо в базе можно глянуть
            </label>
            <my-button @click="register">Отправить</my-button>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    data(){
        return {
            name: "",
            login: "",
            password: "",
            error: {}
        }
    },
    methods:{
        valid(string,type){
            switch(type){
                case 'name':
                    return string.length > 1;
                    break;
                case 'login':
                    return string.length > 1;
                    break;
                case 'password':
                    return string.length > 3;
                    break;
            }
        },
        register(){
            if(!this.name){
                alert('Введи имя');
                return false;
            }
            if(!this.login){
                alert('Введи логин');
                return false;
            }
            if(!this.password ){
                alert('Введи пароль');
                return false;
            }
            if(!this.valid(this.name,'name')){
                alert('Имя хотя бы 2 символа');
                return false;
            }
            if(!this.valid(this.login,'login')){
                alert('Логин хотя бы 2 символа');
                return false;
            }
            if(!this.valid(this.password,'password')){
                alert('Пароль хотя бы 4 символа');
                return false;
            }
            axios
                .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
                    action: 'register',
                    avatarid: 1,
                    name: this.name,
                    login: this.login,
                    password: this.password

                }).then((response)=>{
                    console.log(response)
                    const data = response.data;
                    if(data.errorId){
                        this.error = data;
                        return false;
                    }
                    this.$router.push({ name: 'User', params: { 'id': data.userid } });
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