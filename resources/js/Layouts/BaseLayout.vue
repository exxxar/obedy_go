<script setup>
import {nextTick, onBeforeMount, onMounted, onUnmounted, onUpdated, provide, ref, watch} from 'vue'
import TopMenu from "@/Components/Layout/TopMenu.vue"
import CartModal from "@/Components/Cart/CartModal.vue"
import { useMainStore } from '@/stores/mainStore.js'
import { useCartStore } from '@/stores/cartStore.js'
import { storeToRefs } from "pinia"
import { useLotteryStore } from "@/stores/lotteryStore.js"
import {modals, offcanvas, sendNotify} from "@/app"
import { useUserStore } from "@/stores/userStore"
import UserCartModal from "@/Components/Cart/UserCartModal.vue"
import LoginModal from "@/Components/Modals/LoginModal.vue"
import AboutModal from "@/Components/Modals/AboutModal.vue"
import DeliveryModal from "@/Components/Modals/DeliveryModal.vue"
import LotteryModal from "@/Components/Modals/LotteryModal.vue"
import MenuModal from "@/Components/Modals/MenuModal.vue"
import CallbackModal from "@/Components/Modals/CallbackModal.vue"
import CheckOrderModal from "@/Components/Modals/CheckOrderModal.vue"
import { useChatStore } from "@/stores/chatStore"
import SideMenu from "@/Components/Layout/SideMenu.vue"
import { router } from '@inertiajs/vue3'

/* ------------------------ STORE ----------------------------- */
const main = useMainStore()
const cartStore = useCartStore()
const chatStore = useChatStore()
const userStore = useUserStore()

const { foodParts, part } = storeToRefs(main)
const { items, hasOtherUserCart } = storeToRefs(cartStore)
const { lottery_id, lotteries } = storeToRefs(useLotteryStore())
const { user } = storeToRefs(userStore)
/* ------------------------------------------------------------ */

const is_cart_open = ref(false)
const ready_to_order = ref(false)

//provide
provide('ready_to_order', ready_to_order)
provide('is_cart_open', is_cart_open)

watch(hasOtherUserCart, (val) => {
    if (!val)
        modals.getOrCreateInstance(document.getElementById('changeUserCart')).hide()
})

onMounted(() => {
    listenerNavigate()
    window.addEventListener("keyup", switchKeyUp)
    main.switchFoodPart();
    userStore.getUser()
    chatListen()
    document.getElementById('obedyNavbarMenu').addEventListener('hidden.bs.offcanvas', () => {
        nextTick(() => { document.getElementById('cartMenu').scrollTo(0, 0) })
    })
})

onUnmounted(() => {
    chatListen(false)
})

const chatListen = (listen = true) => {
    if (user.value.isAuthorized) {
        if (listen)
            Echo.private(`chat.${user.value.id}`)
                .listen('MessageSentEvent', data => {
                    if(route().current('chats'))
                        chatStore.newMessage(data.chat)
                    else
                        sendNotify('У Вас новое сообщение!')
                })
        else
            Echo.leave(`chat.${user.value.id}`)
    }
}

const listenerNavigate = () => {
    router.on('navigate', (event) => {
        if (route().current('main'))
            part.value = 0
        else if (route().current('products'))
            part.value = foodParts.value.find(foodPart => foodPart.slug === route().params.foodPart).partId
        else if (route().current('self') || route().current('special'))
            part.value = foodParts.value.find(foodPart => foodPart.slug === route().current()).partId
        else
            part.value = null
        offcanvas.getOrCreateInstance(document.getElementById('obedyNavbarMenu')).hide()
    })
}

const switchKeyUp = event => {
    switch (event.keyCode) {
        case 27: // ESC
            part.value = 0
            break
        case 37: // Arrow right
            main.changePosition(false)
            break
        case 39: // Arrow left
            main.changePosition()
            break
    }
}
</script>

<template>
    <div class="wrapper obedygo">
        <notifications position="top left" group="info"/>
        <div class="container-fluid mh-100vh padding-layout d-flex flex-wrap"
             :class="part === 0 ? 'align-items-center' : 'flex-column'">

            <TopMenu v-if="part !== 0"></TopMenu>
            <slot></slot>

            <CartModal v-if="is_cart_open"/>

            <SideMenu></SideMenu>

            <AboutModal></AboutModal>
            <DeliveryModal></DeliveryModal>
            <CallbackModal></CallbackModal>
            <LotteryModal></LotteryModal>
            <MenuModal></MenuModal>
            <LoginModal></LoginModal>
            <CheckOrderModal></CheckOrderModal>
            <UserCartModal v-if="user.isAuthorized && hasOtherUserCart"></UserCartModal>

            <div class="to-left" @click="main.changePosition(false)"></div>
            <div class="to-right" @click="main.changePosition()"></div>
        </div>
    </div>
</template>

<style scoped>
.mh-100vh {
    min-height: 100vh
}
.padding-layout {
    padding: 70px 10px 70px 10px
}
</style>
