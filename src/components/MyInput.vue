<template>
    <div class="input">
        <span>{{placeholder}}</span>
        <input :value="value" :type="type" @input="updateInput">
    </div>
</template>
<script>
export default {
    name: 'my-input',
    props:{
        value:[String,Number],
        placeholder:{
            type:String,
            default: ''
        },
        type:{
            type:String,
            default: 'text'
        }
    },
    methods: {
        updateInput(event){
            this.$emit('update:value',event.target.value)
        },
    }
}
</script>

<style scoped lang="scss">
    .input{
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin: 0 0 .4em;
        span{
            font-size: .8em;
        }
    }
    input{
        font-size: 14px;
        border: none;
        width: 100%;
        border-bottom: 1px solid #ccc;
        padding: 0.5em 2.5em .5em .3em;
        background: url(../assets/pencil.svg) right .5em center/1em no-repeat;
        &:focus{
            border-color: rgb(85, 53, 12);
            outline: none;
        }
    }
</style>