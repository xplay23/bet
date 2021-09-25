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
                <tr v-for="(rate, index) in items" :key="index">
                    <td>
                        {{this.info.userinfo[index].name}}
                    </td>
                    <td v-for="(rateIn,indexIn) in getKeys()" :key="indexIn" class="ckeckedItem" :class="rate[rateIn] ? 'checked' : 'not_checked'">
                        {{rate[rateIn] ? 'Выбрано' : ''}}
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
            items:[]

        }
    },
    methods:{
        getKeys(){
            // console.log(Object.keys(this.items.length ? this.items[0] : {}));

            return Object.keys(this.items.length ? this.items[0] : {});
        }
    },
    mounted() {
        axios
            .post('http://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
                action: 'withdrawRate',
                rateId: this.$route.params.id
            })
            .then(response => {
                this.info = response.data;
                // console.log('this.info',this.info);
                this.name = this.info.rateinfo.name;
                this.count = this.info.rateinfo.count;
                this.statusid = this.info.rateinfo.statusid;
                
                

                this.info.userinfo.forEach(el=>{
                    let tempEl = {};
                    // console.log(el)
                    // tempEl['Имя'] = el.name;
                    this.info.variations.forEach(elIn=>{
                        tempEl[elIn.name] = this.info.uservariations.some(elSome=> (elSome['userid'] === el.id && elSome['varid'] === elIn.id));
                    })
                    this.items.push(tempEl);
                })
                // console.log('this.items',this.items);
                
            });
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

    .ckeckedItem.not_checked{
        cursor: pointer;
    }
    .ckeckedItem.not_checked:hover{
       background: rgba(36, 53, 34,.3); 
    }
</style>