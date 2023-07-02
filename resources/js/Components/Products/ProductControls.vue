<script setup>
import {inject, nextTick, onMounted, ref} from "vue"
import { storeToRefs } from "pinia"
import { popover } from "@/app"
import { useCartStore } from "@/stores/cartStore"

const props = defineProps({
    product: {
        type: Object,
        default: {}
    }
})

const show_addition_btn = ref(false)
const current_day_index = ref(new Date().getDay())

const inModal = inject('inModal', false)

const cart = useCartStore()
const { items } = storeToRefs(cart)

const ready_to_order = inject('ready_to_order')
const is_cart_open = inject('is_cart_open')

onMounted(() => {
    [...document.getElementsByName('btn-popover-' + props.product.id)].forEach(element => popover.getOrCreateInstance(element))
})

const orderDay = (dayIndex) => {
    let date = new Date()
    date.setDate(date.getDate() + 7 - date.getDay() + dayIndex)
    return date.getDate() + "." + (date.getMonth() + 1) + "." + date.getFullYear()
}

const hasMainProductInCart = () => {
    let tmp = items.value.filter(item => item.product.addition === 0)
    return tmp.length > 0 || props.product.addition === 0
}

const setPopover = async (id) => {
    [...document.getElementsByName('btn-popover-'+id)].forEach(element => popover.getOrCreateInstance(element).dispose())
    await nextTick(() => {
        [...document.getElementsByName('btn-popover-'+id)].forEach(element => popover.getOrCreateInstance(element))
    })
}

const openCart = () => {
    ready_to_order.value = true
    is_cart_open.value = true
}

const clickRemoveProduct = (productId) => {
    cart.removeProduct(productId)
    setPopover(productId)
}

const clickDecQuantity = (productId) => {
    cart.decQuantity(productId)
    setPopover(productId)
}

const clickIncQuantity = (productId) => {
    cart.incQuantity(productId)
    setPopover(productId)
}

const clickAddToCart = (product) => {
    cart.addToCart(product)
    setPopover(product.id)
}

</script>

<template>
    <div class="d-flex justify-content-center">
        <div class="cnt-container" v-if="cart.inCart(product.id ) > 0" @mouseenter="show_addition_btn = true"
             @mouseleave="show_addition_btn = false">
            <div class="group-btn-counter gap-3">
                <button class="btn btn-danger btn-coutner me-3" :name="'btn-popover-' + product.id"
                        data-bs-trigger="hover" data-bs-toggle="popover" data-bs-placement="bottom"
                        data-bs-content="Убрать весь продукт из корзины"
                        @click="clickRemoveProduct(product.id)">
                    <span><font-awesome-icon icon="fa-solid fa-trash"/></span>
                </button>
                <button class="btn btn-danger btn-coutner" :name="'btn-popover-' + product.id"
                        @click="clickDecQuantity(product.id)" data-bs-trigger="hover"
                        data-bs-toggle="popover" data-bs-placement="bottom" data-bs-content="Убрать порцию из корзины">
                    -
                </button>
            </div>
            <p v-html="cart.inCart(product.id)" :class="inModal ? 'text-dark' : 'text-white'"></p>
            <button class="btn btn-danger btn-coutner" :name="'btn-popover-' + product.id"
                    @click="clickIncQuantity(product.id)" data-bs-trigger="hover"
                    data-bs-toggle="popover" data-bs-placement="bottom" data-bs-content="Добавить еще порцию в корзину">
                <span>+</span>
            </button>
            <div class="btn btn-success sub-btn z-1" @click="openCart()" v-if="product.addition !== 1 && show_addition_btn">Оформить</div>
        </div>
        <button class="btn btn-danger w-100 text-uppercase mt-2 p-3"
                :name="'btn-popover-' + product.id"
                data-bs-trigger="hover" data-bs-toggle="popover" data-bs-placement="bottom"
                :data-bs-content="'Добавить товар в корзину - ' + orderDay(product.day_index)"
                @click="clickAddToCart(product)"
                :disabled="!hasMainProductInCart()"
                v-if="!(current_day_index < product.day_index) && cart.inCart(product.id) === 0">В КОРЗИНУ
            <span class="fw-bolder">{{ orderDay(product.day_index) }}</span>
        </button>
        <button class="btn btn-success w-100 text-uppercase mt-2 p-3 custom-btn-color-success"
                :name="'btn-popover-' + product.id"
                data-bs-trigger="hover" data-bs-toggle="popover" data-bs-placement="bottom"
                data-bs-content="Добавить товар в корзину"
                @click="clickAddToCart(product)"
                :disabled="!hasMainProductInCart()"
                v-if="(current_day_index < product.day_index) && cart.inCart(product.id) === 0">
            В КОРЗИНУ
        </button>
    </div>
</template>
