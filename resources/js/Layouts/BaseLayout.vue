<script setup>
import {nextTick, onMounted, onUnmounted, provide, ref, watch} from 'vue'
import TopMenu from "@/Components/Layout/TopMenu.vue"
import CartModal from "@/Components/Cart/CartModal.vue"
import {useMainStore} from '@/stores/mainStore.js'
import {useCartStore} from '@/stores/cartStore.js'
import {storeToRefs} from "pinia"
import {useLotteryStore} from "@/stores/lotteryStore.js"
import {modals, popover, sendNotify} from "@/app"
import {useUserStore} from "@/stores/userStore"
import UserCartModal from "@/Components/Cart/UserCartModal.vue"
import LoginModal from "@/Components/Modals/LoginModal.vue"
import AboutModal from "@/Components/Modals/AboutModal.vue"
import DeliveryModal from "@/Components/Modals/DeliveryModal.vue"
import LotteryModal from "@/Components/Modals/LotteryModal.vue"
import MenuModal from "@/Components/Modals/MenuModal.vue"
import CallbackModal from "@/Components/Modals/CallbackModal.vue"
import CheckOrderModal from "@/Components/Modals/CheckOrderModal.vue"
import {router} from '@inertiajs/vue3'
import {useChatStore} from "@/stores/chatStore";

//get store
const main = useMainStore()
const cartStore = useCartStore()
const chatStore = useChatStore()
const {foodParts, part} = storeToRefs(main)
const {items, getTotalCount, hasOtherUserCart} = storeToRefs(cartStore)
const {lottery_id, lotteries} = storeToRefs(useLotteryStore())

const userStore = useUserStore()
const {user} = storeToRefs(userStore)

//data
const bottom_menu_show = ref(false)
const is_cart_open = ref(false)
const ready_to_order = ref(false)


//provide
provide('ready_to_order', ready_to_order)
provide('is_cart_open', is_cart_open)

// watch
watch(part, () => {
    if (part.value !== null)
        main.selectPart()
})

onMounted(() => {
    [...document.querySelectorAll('[data-bs-toggle="popover"]')].forEach(el => popover.getOrCreateInstance(el))
    switchFoodPart()
    window.addEventListener("keyup", switchKeyUp)
    userStore.getUser()

    document.getElementById('obedyNavbarMenu').addEventListener('hidden.bs.offcanvas', () => {
        setTimeout(() => document.getElementById('cartMenu').scrollTo(0, 0), 350);
    })
    if (user.value.isAuthorized) {
        Echo.private('chat.' + user.value.id)
            .listen('MessageSentEvent', data => {
                if(route().current('chats'))
                    chatStore.newMessage(data.chat)
                else
                    sendNotify('У Вас новое сообщение!')
            });
    }
})

const switchFoodPart = () => {
    if (route().current('products'))
        part.value = foodParts.value.find(foodPart => foodPart.slug === route().params.foodPart).partId
    else
        part.value = null

}

const switchKeyUp = event => {
    switch (event.keyCode) {
        case 27:
            part.value = 0
            break
        case 37:
            main.changePosition(false)
            break
        case 39:
            main.changePosition()
            break
    }
}

const swipeHandler = direction => {
    if (direction === 0)
        main.changePosition(false)
    else
        main.changePosition()
}

const setPopover = async () => {
    for (const el of items.value) {
        [...document.getElementsByName('btn-popover-' + el.product.id)].forEach(element => popover.getOrCreateInstance(element).dispose())
        await nextTick();
        [...document.getElementsByName('btn-popover-' + el.product.id)].forEach(element => popover.getOrCreateInstance(element))
    }
}

watch(hasOtherUserCart, (val) => {
    if (!val)
        modals.getOrCreateInstance(document.getElementById('changeUserCart')).hide()
})
</script>

<template>
    <div class="wrapper obedygo">
        <notifications position="top left" group="info"/>

        <div class="container-fluid d-flex align-items-center flex-wrap"
             style="min-height: 100vh;padding:70px 10px 70px 10px;">
            <slot v-if="part === 0"></slot>
            <TopMenu v-else></TopMenu>

            <div class="w-100 d-flex flex-column align-items-center text-uppercase text-white text-center mt-4"
                 v-if="part !== null" :class="part === 0 ? 'mb-5 mt-5' : ''">
                <h6 class="col-8 px-3">Полное меню на неделю можно глянуть <a
                    href="#" class="text-danger font-weight-bold" data-bs-toggle="modal" data-bs-target="#menu">ТУТ</a>
                </h6>
                <template v-for="foodPart in foodParts">
                    <h1 class="mt-2" v-if="part === foodPart.partId">{{ foodPart.title }}</h1>
                </template>
            </div>

            <div class="w-100" v-if="part === 0"></div>
            <div class="w-100" v-if="part === 0"></div>

            <slot v-if="part !== 0"></slot>


            <CartModal v-if="is_cart_open"/>

            <nav class="navbar navbar-menu d-sm-flex d-none" v-if="!is_cart_open">
                <i class="fa-solid fa-burger fa-2xl" data-bs-toggle="offcanvas" data-bs-target="#obedyNavbarMenu"
                   aria-controls="obedyNavbarMenu"
                   aria-label="Toggle navigation" role="button"></i>
                <div class="offcanvas cart-container" tabindex="-1" id="obedyNavbarMenu">
                    <div class="cart-icon cart-icon-close">
                        <i class="fa-solid fa-xmark fa-2xl" data-bs-dismiss="offcanvas"></i>
                    </div>
                    <div id="cartMenu" class="cart-menu">
                        <div class="cart-icon login" data-bs-toggle="modal" data-bs-target="#login"
                             v-if="!user.isAuthorized">Войти
                        </div>
                        <div class="cart-icon login" @click="userStore.logout()" v-else>Выйти</div>
                        <div class="cart-icon cart" @click="is_cart_open = true; setPopover();">Корзина<span
                            v-if="getTotalCount>0">{{ getTotalCount }}</span></div>
                        <div class="cart-icon about" data-bs-toggle="modal" data-bs-target="#about">О нас</div>
                        <div class="cart-icon callback" data-bs-toggle="modal" data-bs-target="#callback">Напиши нам
                        </div>
                        <div class="cart-icon delivery" data-bs-toggle="modal" data-bs-target="#delivery">Доставка</div>
                        <div class="cart-icon lottery" data-bs-toggle="modal" data-bs-target="#lottery"
                             @click="lottery_id = null" v-if="lotteries.length > 0">Акции
                        </div>
                        <div class="cart-icon checkOrder" data-bs-toggle="modal" data-bs-target="#checkOrder">Узнать
                            статус
                        </div>

                        <div class="cart-icon icon-1" v-if="user.isAuthorized" @click="router.get(route('chats'))">
                            Чаты
                        </div>
                        <div class="cart-icon icon-2" @click="router.get(route('specialists'))">Специалисты</div>
                        <div class="cart-icon icon-3" data-bs-toggle="modal" data-bs-target="#checkOrder">Узнать
                            статус
                        </div>
                    </div>
                </div>
            </nav>

            <ul class="footer-container d-sm-none" @mouseleave="bottom_menu_show = false">
                <li class="p-2 text-center" v-if="!bottom_menu_show" @click="bottom_menu_show = true">Показать меню
                    <span class="badge badge-danger" v-if="getTotalCount > 0">{{ getTotalCount }}</span></li>
                <template v-else>
                    <li class="p-2 text-center" @click="bottom_menu_show = false">Скрыть меню</li>
                    <ul class="bottom_menu">
                        <li v-for="foodPart in foodParts" @click="part = foodPart.partId">{{ foodPart.title }}</li>
                        <li class="hr"></li>
                        <li data-bs-toggle="modal" data-bs-target="#login" v-if="!user.isAuthorized">
                            Войти
                        </li>
                        <li @click="userStore.logout()" v-else>Выйти</li>
                        <li @click="is_cart_open = true">
                            Корзина <span class="badge badge-danger" v-if="countInCart>0">{{ countInCart }}</span>
                        </li>
                        <li data-bs-toggle="modal" data-bs-target="#about">О нас</li>
                        <li data-bs-toggle="modal" data-bs-target="#callback">Напиши нам</li>
                        <li data-bs-toggle="modal" data-bs-target="#delivery">Доставка</li>
                        <li data-bs-toggle="modal" data-bs-target="#lottery"
                            @click="lottery_id = null" v-if="lotteries.length > 0">Акции
                        </li>
                        <li data-bs-toggle="modal" data-bs-target="#checkOrder">Узнать статус</li>
                    </ul>
                </template>
            </ul>

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

