<template>
    <label>
        <input type="checkbox" @change="updateInput" v-model="value" :checked="value">
        {{placeholder}}
      </label>
</template>

<script>
export default {
    name: 'my-checkbox',
    props:{
        value:Boolean,
        placeholder:{
            type:String,
            default: ''
        }
    },
    methods: {
        updateInput(event){
            this.$emit('update:value',event.target.value)
        },
    }
}
</script>