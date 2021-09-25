<template>
    <div class="input">
        <input :value="value" :type="type" :placeholder="placeholder" @input="updateInput">
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