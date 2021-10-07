<template>
    <div>
        <my-head>Регистрация</my-head>
        <div class="form">
            <my-input type="text" v-model:value="name" placeholder="Имя" />
            <my-input type="text" v-model:value="login" placeholder="Логин" />
            <my-input id="pass" v-model:value="password" type="password" placeholder="Пароль" />
            <div class="error">{{error.errorText}}</div>
            <label for="pass">
                пароль вбирай не из тех которые юзаешь где-то, ибо в базе можно глянуть
            </label>
            <div class="mini_head">Аватарка</div>
            <img v-if="img" :src="img" alt="">
            <label class="input_file">
                <input type="file" ref="file" @change="loadAvatar">
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
                    this.$store.dispatch('login',{
                        login:this.login,
                        password:this.password
                    });
                    // this.$router.push({ name: 'User', params: { 'id': data.userid } });
                })
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
        img{
            max-width: 100%;
        }
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
    [type="file"]{
        margin-bottom: 1em;
    }
    .mini_head{
        text-align: left;
        font-weight: bold;
        margin-bottom: .4em;
    }
</style>