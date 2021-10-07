<template>
    <button>
        <slot></slot>
    </button>
</template>
<script>
export default {
    name:"my-button"
}
</script>
<style lang="scss" scoped>
    button{
        border: 1px solid #ccc;
        padding: .4em .7em;
        font-size: 14px;
        background: rgb(102, 104, 11);
        color: #fff;
        cursor: pointer;
        transition: background 150ms;
        &:hover{
            background: rgb(138, 139, 36);
        }
        &.active{
            background: rgb(138, 139, 36);
            cursor: default;
        }
    }
</style>