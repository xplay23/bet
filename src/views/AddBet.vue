<template>
  <div>
    <my-head>Добавить ставку</my-head>
    <div class="rate_form">
      <my-input title="На что ставим" v-model:value="rateName" placeholder="На что ставим"></my-input>
      <my-input title="Денежки" v-model:value="rateCount" placeholder="Денежки" type="number"></my-input>
    </div>
    
    <div class="rate_form">
      <my-input v-model:value="rateVariant" placeholder="Вариант ставки"></my-input>
      <my-button class="rate_form__input--button"  @click="addRate">Добавить вариант</my-button>
    </div>

    <div class="table_wrap">
      <table>
        <tr>
          <td>Юзернейм</td>
          <td v-for="(rate,index) in rates" :key="index">
              <div class="flex_wrap">
                <my-input type="text" v-model:value="rates[index]"></my-input>
                <button @click="removeBet(index)" class="remove_button"></button>
              </div>
          </td>
        </tr>
      </table>
    </div>

    <div class="table_wrap">
      <h3>Кто учавствует</h3>
      <table>
        <tr>
          <td :class="user.ckeck ? 'ckecked' : ''" class="with_label" v-for="user in users" :key="user.id">
              <label class="flex_wrap">
                <input type="checkbox" v-model="user.ckeck">
                {{user.name}}
                <span class="icon"></span>
              </label>
          </td>
        </tr>
      </table>
    </div>
    
    <my-button @click="saveRate">Сохранить ставку</my-button>

  </div>
</template>

<script>
// @ is an alias to /src
import axios from 'axios'
export default {
  name: 'Home',
  data(){
    return {
      rateName: '',
      rateCount: 2,
      rateVariant: '',
      rates:[],
      users:[],

    }
  },
  mounted() {
    // console.log(this.$store.getters.getUserInfo)
    if(!this.$store.getters.isLogin){
      this.$router.push('/');
    }
  // console.log(this.$store.state);
    axios
      .post('https://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
          action: 'getAllUsers'
      }).then((response)=>{
          const data = response.data;
          this.users = data.map(el=>{
            return {
              name: el.name,
              id: el.id,
              ckeck: true,
            }
          })
              // console.log('getAllUsers',this.users)
      })
  },
  methods:{
      addRate(){
          if(this.rateVariant == '') return false;
          this.rates.push(this.rateVariant);
          this.rateVariant = '';
      },
      removeBet(index){
        this.rates.splice(index,1);
      },
      getParUser(){
        return this.users.filter(el=>{
          return el.ckeck;
        }).map(el=>{
          return el.id
        })
      },
      saveRate(){

          if(this.rateName == ''){
            alert('Забыл добавить название!');
            return false;
          }

          if(this.rates.length < 2){
            alert('Сделай более одного варианта ставки!');
            return false;
          }

          if(this.getParUser().length < 2){
            alert("Добавить хотябы двух людей!");
            return false;
          }

          axios
              .post('https://devlink1.tk//bm/vue_lessons/betting_admin/index.php',{
                  action: 'createBet',
                  token: this.$store.getters.getUserToken,
                  name: this.rateName,
                  count: this.rateCount,
                  variations: this.rates,
                  participating: this.getParUser(),
              })
              .then(response => {
                const data = response.data;
                if(data.errorId === 0){
                  this.$router.push({ name: 'Bet', params: { id: data.rateID } })
                }
              });
      }
  }
}
</script>
<style scoped lang="scss">
  .rate_form{
    display: flex;
    margin-bottom: 2em;
    &__input{
      font-size: 14px;
      padding: .2em;
      line-height: 1;
      width: auto;
      height: auto;
    }
  }
  .table_wrap{
    margin-bottom: 2em;
    max-width: 100%;
    overflow-x: auto;
  }
  table{
    border-collapse: collapse;
    width: 100%;
  }
  td{
    padding: .3em 1em;
    border: 1px solid rgb(235, 233, 233);
    &:first-child{
      text-align: left;
    }
  }
  .flex_wrap{
    display: flex;
    align-items: center;
  }
  .remove_button{
    border-radius: 50%;
    // border: 1px solid #000;
    border: none;
    cursor: pointer;
    background: url(../assets/remove.svg) center/contain no-repeat;
    color: #fff;
    padding: 0;
    line-height: 1;
    width: 1.5em;
    height: 1.5em;
  }
  .with_label{
    padding: 0;
      text-decoration: line-through;
    &.ckecked{
      text-decoration: none;
      label{
        .icon{
          background-image: url(../assets/check.svg);
        }
      }

    }
    label{
      width: 100%;
      padding: .3em 1em;
      cursor: pointer;
      user-select: none;
      position: relative;
      .icon{
        width: 20px;
        height: 20px;
        margin-left: 20px;
        background: url(../assets/cross.svg) center/contain no-repeat;
      }
      input{
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 0;
        opacity: 0;
      }
      &:hover{
        background: rgba(#000,.05);
      }
    }
  }
  .ckecked{
    background: rgba(rgb(30, 255, 0),.05);
  }
  @media(max-width: 500px){
    .rate_form{
      flex-direction: column;
    }
  }
</style>