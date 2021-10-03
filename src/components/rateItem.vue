<template>
    <div class="link" :class="rate.statusid == 1 ? 'complete' : ''">
      <router-link class="link__inner" :to="{ name: 'Bet', params: { id: rate.id }}">{{rate.name}}</router-link>
      <div class="link__money">
        <img src="../assets/salary.svg">
        <span>
          {{rate.count}}
        </span>
      </div>
    </div>
</template>
<script>
export default {
    name: 'rate-item',
    props:{
        rate: Object
    }
}
</script>

<style lang="scss" scoped>
  .complete{
    background: #e3ffe3;
    text-decoration: line-through;
  }
 
  .link{
    margin-bottom: 1em;
    text-align: left;
    padding: 1em;
    border: 1px solid rgb(124, 124, 124);
    display: flex;
    align-items: flex-start;
  }
  .link__inner{
    color: #000;
    text-decoration: underline;
  }
  .complete .link__inner{
    text-decoration: inherit;
  }
  .link__inner:hover{
    text-decoration: none;
  }
  .link__money{
    margin-left: auto;
    font-weight: bold;
    display: flex;
    align-items: center;
  }
  .link__money img{
    width: 1.5em;
    margin-right: .5em;
  }
</style>