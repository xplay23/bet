<template>
    <transition name="fade">
        <div class="popup-modal" v-if="isVisible">
            <div class="window">
                <div class="cont">{{cont}}</div>
                <slot></slot>
            </div>
        </div>
    </transition>
</template>
<script>
export default {
    name: 'MyModal',

    data: () => ({
        isVisible: false,
        cont: null
    }),
    props:{
        head: String
    },
    methods: {
        open(cont) {
            this.cont = cont;
            this.isVisible = true;
        },

        close() {
            this.isVisible = false;
        },
    },
}
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.popup-modal {
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    z-index: 1;
}

.window {
    background: rgb(36, 36, 36);
    border-radius: 5px;
    box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    max-width: 650px;
    min-height: 10rem;
    min-width: 250px;
    margin-left: auto;
    margin-right: auto;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
.cont{
    width: 100%;
    margin-bottom: 20px;
}
</style>