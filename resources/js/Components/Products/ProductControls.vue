<script setup>
import {inject, nextTick, ref} from "vue"
import {popover} from "@/app"
import {storeToRefs} from "pinia"
import {useCartStore} from "@/stores/cartStore"

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
const {items} = storeToRefs(cart)

const ready_to_order = inject('ready_to_order')
const is_cart_open = inject('is_cart_open')

const orderDay = (dayIndex) => {
    let dt = new Date()
    dt.setDate(dt.getDate() + 7 - dt.getDay() + dayIndex)
    return dt.getDate() + "." + (dt.getMonth() + 1) + "." + dt.getFullYear()
}

const hasMainProductInCart = () => {
    let tmp = items.value.filter(item => item.product.addition === 0)
    return tmp.length > 0 || props.product.addition === 0
}

const setPopover = async (id) => {
    [...document.getElementsByName('btn-popover-'+id)].forEach(element => popover.getOrCreateInstance(element).dispose());
    await nextTick();
    [...document.getElementsByName('btn-popover-'+id)].forEach(element => popover.getOrCreateInstance(element))
}

const openCart = () => {
    ready_to_order.value = true
    is_cart_open.value = true

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
                        @click="cart.removeProduct(product.id); setPopover(product.id);">
                    <span><i class="fas fa-trash"></i></span>
                </button>
                <button class="btn btn-danger btn-coutner" :name="'btn-popover-' + product.id"
                        @click="cart.decQuantity(product.id); setPopover(product.id);" data-bs-trigger="hover"
                        data-bs-toggle="popover" data-bs-placement="bottom" data-bs-content="Убрать порцию из корзины">
                    -
                </button>
            </div>
            <p v-html="cart.inCart(product.id)" :class="inModal ? 'text-dark' : 'text-white'"></p>
            <button class="btn btn-danger btn-coutner" :name="'btn-popover-' + product.id"
                    @click="cart.incQuantity(product.id); setPopover(product.id);" data-bs-trigger="hover"
                    data-bs-toggle="popover" data-bs-placement="bottom" data-bs-content="Добавить еще порцию в корзину">
                <span>+</span>
            </button>
            <div class="btn btn-success sub-btn z-1" @click="openCart()" v-if="show_addition_btn">Оформить</div>
        </div>
        <button class="btn btn-danger w-100 text-uppercase mt-2 p-3"
                :name="'btn-popover-' + product.id"
                data-bs-trigger="hover" data-bs-toggle="popover" data-bs-placement="bottom"
                :data-bs-content="'Добавить товар в корзину - ' + orderDay(product.day_index)"
                @click="cart.addToCart(product); setPopover(product.id);"
                :disabled="!hasMainProductInCart()"
                v-if="!(current_day_index < product.day_index) && cart.inCart(product.id) === 0">В КОРЗИНУ
            <span class="fw-bolder">{{ orderDay(product.day_index) }}</span>
        </button>
        <button class="btn btn-success w-100 text-uppercase mt-2 p-3 custom-btn-color-success"
                :name="'btn-popover-' + product.id"
                data-bs-trigger="hover" data-bs-toggle="popover" data-bs-placement="bottom"
                data-bs-content="Добавить товар в корзину"
                @click="cart.addToCart(product); setPopover(product.id);"
                :disabled="!hasMainProductInCart()"
                v-if="(current_day_index < product.day_index) && cart.inCart(product.id) === 0">
            В КОРЗИНУ
        </button>
    </div>
</template>
