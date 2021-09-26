<template>
    <div>
        <my-head>{{name}}</my-head>
        <div>
            Денег: {{count}}
        </div>
        <div>
            Статус: {{statusid}}
        </div>

        <table>
            <thead>
                <tr>
                    <td>Имя</td>
                    <td v-for="(rate,index) in getKeys()" :key="index">
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
                        {{rateIn.status ? 'Выбрано' : ''}}
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
            // console.log(rate.rateInfo)
            // console.log()
            // return rate.userID === this.userInfo.id;
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

            // console.log(rateIn,rate)
            // const rate = this.info.variations[index];
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
        border-collapse: collapse;
    }
    thead{
        background: #ddd;
    }
    td{
        border: 1px solid;
    }
    .checked{
        background: rgb(36, 53, 34);
        color: #fff;
    }
    .not_checked{
        background: #fff;
    }

    .current:not(.made) .ckeckedItem.not_checked{
        cursor: pointer;
    }
    .current:not(.made) .ckeckedItem.not_checked:hover{
       background: rgba(36, 53, 34,.3); 
    }
    .current{
        background: #f00;
    }
</style>