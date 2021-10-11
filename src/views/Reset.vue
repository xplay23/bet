<template>
    <div>
        <my-head>Сброс пароля</my-head>
        <div class="form">
            <my-input type="text" v-model:value="login" placeholder="Логин" />
            <div class="error">{{error.errorText}}</div>
            <my-button @click="register">Отправить</my-button>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            login: "",
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

            this.$store.dispatch('reset',{
                login:this.login,
            });
        }
    }
}
</script>
<style lang="scss" scoped>
    .form{
        max-width: 300px;
        margin: auto;
        padding: 1.4em;
        border: 1px solid #ccc;
        border-radius: 0.6em;
    }
    label{
        display: block;
        text-align: left;
        font-size: .8em;
        margin: 1em 0;
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