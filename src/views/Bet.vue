<template>
    <div class="bet_page" :class="isEnd ? 'complete_rate' : ''">
        <my-head>{{name}}</my-head>
        <div class="flexwrap">
            <div>
                Денег: 
                <span class="bold">
                    {{count}}
                </span>
            </div>
            <div class="status">
                Статус: 
                <span class="bold">
                    {{isEnd ? 'Окончено' : 'Не окончено'}}
                </span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <td>Имя</td>
                    <td v-for="(rate,index) in getKeys()" :key="index" :class="(winInfo && items[0].rateInfo[rate].id === winInfo) ? 'win' : '' ">
                        {{rate}}
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(rate,index) in items" :key="index" 
                    :class="[
                        currentUser(rate) ? 'current' : '',
                        madeRate(rate) ? 'made' : ''
                    ]">
                    <td>
                        {{rate.userName}}
                    </td>
                    <td @click="getRate(rateIn,rate)" v-for="rateIn in rate.rateInfo" :key="rateIn.id" class="ckeckedItem" :class="rateIn.status ? 'checked' : 'not_checked'">
                        {{rateIn.status ? 'x' : ''}}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>
<script>
import axios from 'axios'
export default {
    data(){
        return {
            info: null,
            name: null,
            count: null,
            statusid: null,
            userInfo: null,
            isEnd: null,
            winInfo: null,
            items:[]

        }
    },
    methods:{
        getKeys(){
            return Object.keys(this.items.length ? this.items[0].rateInfo : {});
        },
        currentUser(rate){
            return rate.userID === this.userInfo.id;
        },
        madeRate(rate){
            return Object.values(rate.rateInfo).some(el=>el.status);
        },
        getInfo(){
            axios
            .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
                action: 'withdrawRate',
                rateId: this.$route.params.id
            })
            .then(response => {
                this.info = response.data;
                
                this.name = this.info.rateinfo.name;
                
                this.count = this.info.rateinfo.count;
                this.statusid = this.info.rateinfo.statusid;
                this.userInfo = this.info.currentUser;
                this.isEnd = this.statusid == 1 ? true : false;

                this.winInfo = this.info.wininfo?.varid;
                

                this.items = [];
                this.info.userinfo.forEach(el=>{
                    let tempEl = {
                        userID: el.id,
                        userName: el.name,
                        rateInfo:{}
                    };

                    this.info.variations.forEach(elIn=>{
                        tempEl['rateInfo'][elIn.name] = {
                            'status': this.info.uservariations.some(elSome=> (elSome['userid'] === el.id && elSome['varid'] === elIn.id)),
                            'id': elIn.id
                        };
                    })
                    this.items.push(tempEl);
                })
                console.log('getInfo() => this.items',this.items);
                
            });
        },
        getRate(rateIn,rate){

            if(rate.userID != this.userInfo.id) return false;
            if(this.madeRate(rate)) return false;
            if(this.isEnd) return false;


            const result = confirm('Ты уверен в ставке?');
            if(!result) return false;
            axios
                .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
                    action: 'placeBet',
                    rateId: this.$route.params.id,
                    varId: rateIn.id
                }).then(()=>{
                    this.getInfo();
                })
        }
    },
    mounted() {
        this.getInfo();
    },
}
</script>
<style scoped>
    table{
        width: 100%;
        max-width: 800px;
        margin: auto;
        border-collapse: collapse;
        background: rgb(235, 233, 233);
    }
    thead{
        background: rgb(187, 187, 187);
    }
    td{
        border: 1px solid rgb(124, 124, 124);
        text-align: center;
        padding: .4em;
    }
    td:first-child{
        text-align: left;
    }
    .checked{
        background: rgb(36, 53, 34);
        color: #fff;
    }
    .not_checked{
        /* background: #fff; */
    }

    .bet_page:not(.complete_rate) .current:not(.made) .ckeckedItem.not_checked{
        cursor: pointer;
    }
    .bet_page:not(.complete_rate) .current:not(.made) .ckeckedItem.not_checked:hover{
       background: rgba(36, 53, 34,.3); 
    }
    .current{
        background: #fff;
        font-weight: bold;
    }
    .flexwrap{
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        max-width: 800px;
        margin: 0 auto 2em;
    }
    .bold{
        font-weight: bold;
    }
    .win{
        /* color: #f00; */
        background: rgb(179, 255, 164);
    }
</style>