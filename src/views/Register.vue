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
            Аватарка
            <img v-if="img" :src="img" alt="">
            <input type="file" ref="file" @change="loadAvatar">
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
            file:'',
            img: null,
            avatarId: 0,
            error: {}
        }
    },
    mounted(){
    },
    methods:{
        loadAvatar(){
            this.file = this.$refs.file.files[0];

            let formData = new FormData();
            formData.append('file', this.file);

            axios.post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php', formData,
            {
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })
            .then( (response) => {
                if(!response.data || response.data.errorId){
                    alert('File not uploaded.');
                }else{
                    this.img = response.data.url;
                    this.avatarId = response.data.avatarId;
                    console.log(response.data.avatarId);
                }

            });
        },
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
            console.log(this.avatarId);
            axios
                .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
                    action: 'register',
                    avatarid: this.avatarId,
                    name: this.name,
                    login: this.login,
                    password: this.password

                }).then((response)=>{
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
    .avatar{
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap;
        // overflow-x: auto;
        input{
            opacity: 0;
            position: absolute;
            &:checked ~ img{
                filter: drop-shadow(0 0 .4em #000)
            }
        }
        label{
            position: relative;
            flex-shrink: 0;
            cursor: pointer;
        }
        img{
            filter: drop-shadow(0 0 .4em rgba(#000,0));
            transition: filter 200ms;
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
    }
</style>