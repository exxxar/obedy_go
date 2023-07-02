<script setup>
import {useMainStore} from "@/stores/mainStore"
import {useCartStore} from "@/stores/cartStore"
import {storeToRefs} from "pinia"
import {useLotteryStore} from "@/stores/lotteryStore"
import {useUserStore} from "@/stores/userStore"
import {inject, nextTick, ref} from "vue"
import {popover} from "@/app"
import { router } from '@inertiajs/vue3'

const main = useMainStore()
const cartStore = useCartStore()
const {foodParts, part} = storeToRefs(main)
const {items, getTotalCount} = storeToRefs(cartStore)
const {lottery_id, lotteries} = storeToRefs(useLotteryStore())

const userStore = useUserStore()
const {user} = storeToRefs(userStore)

const bottom_menu_show = ref(false)

const is_cart_open = inject('is_cart_open')

const setPopover = async () => {
    for (const el of items.value) {
        [...document.getElementsByName('btn-popover-' + el.product.id)].forEach(element => popover.getOrCreateInstance(element).dispose())
        await nextTick(
            () => { [...document.getElementsByName('btn-popover-' + el.product.id)].forEach(element => popover.getOrCreateInstance(element)) }
        )
    }
}

const cartOpen = () => {
    is_cart_open.value = true
    setPopover()
}

const logout = () => {
    userStore.logout().then( (response) => { router.reload() })
}

</script>

<template>
    <div class="navbar-menu-profile" @click="cartOpen()">
        <font-awesome-icon icon="fa-solid fa-cart-shopping" size="2xl" role="button"/>
        <span v-if="getTotalCount>0" class="cart-count">{{ getTotalCount }}</span>
    </div>
    <nav class="navbar navbar-menu d-sm-flex d-none" v-if="!is_cart_open">
        <font-awesome-icon icon="fa-solid fa-burger" size="2xl" data-bs-toggle="offcanvas" data-bs-target="#obedyNavbarMenu"
            aria-controls="obedyNavbarMenu" aria-label="Toggle navigation" role="button"/>
        <div class="offcanvas cart-container" tabindex="-1" id="obedyNavbarMenu">
            <div class="cart-icon cart-icon-close">
                <font-awesome-icon icon="fa-solid fa-xmark" size="2xl" data-bs-dismiss="offcanvas"/>
            </div>
            <div id="cartMenu" class="cart-menu">
                <div class="cart-icon login" data-bs-toggle="modal" data-bs-target="#login"
                     v-if="!user.isAuthorized">Войти
                </div>
                <div class="cart-icon login" @click="router.get(route('profile'))" v-else>Профиль</div>
                <div class="cart-icon cart" @click="cartOpen()">Корзина<span
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
                <div class="cart-icon icon-1" @click="router.get(route('specialists'))">Специалисты</div>
                <div class="cart-icon icon-2" v-if="user.isAuthorized" @click="router.get(route('chats'))">
                    Чаты
                </div>
                <div class="cart-icon icon-3" v-if="user.isAuthorized" @click="logout()">Выход</div>
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
                <li data-bs-toggle="modal" data-bs-target="#login" v-if="!user.isAuthorized">Войти</li>
                <li @click="router.get(route('profile'))" v-else>Профиль</li>
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
                <li v-if="user.isAuthorized" @click="router.get(route('chats'))">Чаты</li>
                <li @click="router.get(route('specialists'))">Специалисты</li>
                <li v-if="user.isAuthorized" @click="logout()">Выход</li>
            </ul>
        </template>
    </ul>
</template>
