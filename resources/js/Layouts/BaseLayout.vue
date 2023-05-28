<script setup>
import {computed, nextTick, onMounted, onUnmounted, provide, ref, watch} from 'vue'
import TopMenu from "@/Components/Layout/TopMenu.vue"
import CartModal from "@/Components/Cart/CartModal.vue"
import {useMainStore} from '@/stores/mainStore.js'
import {useCartStore} from '@/stores/cartStore.js'
import {storeToRefs} from "pinia"
import {useLotteryStore} from "@/stores/lotteryStore.js"
import {modals, popover} from "@/app"
import {useUserStore} from "@/stores/userStore"
import UserCartModal from "@/Components/Cart/UserCartModal.vue"
import LoginModal from "@/Components/Modals/LoginModal.vue"
import AboutModal from "@/Components/Modals/AboutModal.vue"
import DeliveryModal from "@/Components/Modals/DeliveryModal.vue"
import LotteryModal from "@/Components/Modals/LotteryModal.vue"
import MenuModal from "@/Components/Modals/MenuModal.vue"
import CallbackModal from "@/Components/Modals/CallbackModal.vue"

//get store
const main = useMainStore()
const cartStore = useCartStore()
const {foodParts, part} = storeToRefs(main)
const {items, getTotalCount} = storeToRefs(cartStore)
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
    main.selectPart()
})

onMounted(() => {
    [...document.querySelectorAll('[data-bs-toggle="popover"]')].forEach(el => popover.getOrCreateInstance(el))
    switchFoodPart()
    window.addEventListener("keyup", switchKeyUp)
    console.log('base mount')
    userStore.getUser()
})

onUnmounted(() => {
    window.removeEventListener('keyup', switchKeyUp)
})

const switchFoodPart = () => {
    if (route().current('products') && part.value === 0) {
        switch (route().params.foodPart) {
            case "standard":
                part.value = 1
                break
            case "express":
                part.value = 2
                break
            case "premium":
                part.value = 3
                break
            case "self":
                part.value = 4
                break
        }
    }
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


const hasOtherUserCart = computed(() => {
    return items.value.find(item => {
        let res = item.users.find(userItem => userItem.phone !== user.value.phone)
        return !!res

    }) !== undefined
})

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
            <slot name="content" v-if="part === 0"></slot>
            <TopMenu v-else></TopMenu>

            <div class="w-100 d-flex flex-column align-items-center text-uppercase text-white text-center mt-4"
                 :class="part === 0 ? 'mb-5 mt-5' : ''">
                <h6 class="col-8 px-3">Полное меню на неделю можно глянуть <a
                    href="#" class="text-danger font-weight-bold" data-bs-toggle="modal" data-bs-target="#menu">ТУТ</a>
                </h6>
                <template v-for="foodPart in foodParts">
                    <h1 class="mt-2" v-if="part === foodPart.partId">{{ foodPart.title }}</h1>
                </template>
            </div>

            <div class="w-100" v-if="part === 0"></div>
            <div class="w-100" v-if="part === 0"></div>

            <slot name="content" v-if="part>0"></slot>


            <CartModal v-if="is_cart_open"/>

            <div class="cart-container d-sm-block d-none" v-if="!is_cart_open">
                <div class="cart-icon login" data-bs-toggle="modal" data-bs-target="#login" v-if="!user.isAuthorized">
                    Войти
                </div>
                <div class="cart-icon login" @click="userStore.logout()" v-else>Выйти</div>
                <div class="cart-icon cart" @click="is_cart_open = true; setPopover();">Корзина <span
                    v-if="getTotalCount>0">{{ getTotalCount }}</span>
                </div>
                <div class="cart-icon about" data-bs-toggle="modal" data-bs-target="#about">О нас</div>
                <div class="cart-icon callback" data-bs-toggle="modal" data-bs-target="#callback">Напиши нам</div>
                <div class="cart-icon delivery" data-bs-toggle="modal" data-bs-target="#delivery">Доставка</div>
                <div class="cart-icon lottery" data-bs-toggle="modal" data-bs-target="#lottery"
                     @click="lottery_id = null" v-if="lotteries.length > 0">Акции
                </div>
            </div>

            <ul class="footer-container d-sm-none" @mouseleave="bottom_menu_show = false">
                <li class="p-2 text-center" v-if="!bottom_menu_show" @click="bottom_menu_show = true">Показать меню
                    <span class="badge badge-danger" v-if="getTotalCount > 0">{{ getTotalCount }}</span></li>
                <template v-else>
                    <li class="p-2 text-center" @click="bottom_menu_show = false">Скрыть меню</li>
                    <ul class="bottom_menu">
                        <li v-for="foodPart in foodParts" @click="part = foodPart.partId">{{ foodPart.title }}</li>
                        <li class="hr"></li>
                        <li @click="is_cart_open = true">
                            Корзина <span class="badge badge-danger" v-if="countInCart>0">{{ countInCart }}</span>
                        </li>
                        <li data-bs-toggle="modal" data-bs-target="#about">О нас</li>
                        <li data-bs-toggle="modal" data-bs-target="#callback">Напиши нам</li>
                        <li data-bs-toggle="modal" data-bs-target="#delivery">Доставка</li>
                        <li data-bs-toggle="modal" data-bs-target="#lottery"
                            @click="lottery_id = null" v-if="lotteries.length > 0">Акции
                        </li>
                    </ul>
                </template>
            </ul>

            <AboutModal></AboutModal>
            <DeliveryModal></DeliveryModal>
            <CallbackModal></CallbackModal>
            <LotteryModal></LotteryModal>
            <MenuModal></MenuModal>
            <LoginModal></LoginModal>
            <UserCartModal v-if="user.isAuthorized && hasOtherUserCart"></UserCartModal>

            <div class="to-left" @click="main.changePosition(false)"></div>
            <div class="to-right" @click="main.changePosition()"></div>
        </div>
    </div>
</template>
