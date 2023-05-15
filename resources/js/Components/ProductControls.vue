<script setup>
import {inject, nextTick, ref, watch} from "vue";
import {useCartStore} from "@/stores/cartStore";
import {storeToRefs} from "pinia";

const show_addition_btn = ref(false)
const current_day_index = ref(new Date().getDay())
const additional_day = ref(["Пн", "Вт", "Ср", "Чт", "Пт"])
const selected_additional_days = ref([])

const product = inject('product')
const inModal = inject('inModal', false)

const cart = useCartStore()
const {items} = storeToRefs(cart)

const orderDay = (dayIndex) => {
    let dt = new Date();
    dt.setDate(dt.getDate() + 7 - dt.getDay() + dayIndex);
    return dt.getDate() + "." + (dt.getMonth() + 1) + "." + dt.getFullYear();
}

const hasMainProductInCart = () => {
    let tmp = items.value.filter(item => item.product.addition === 0)
    return tmp.length > 0 || product.addition === 0
}

const setPopover = async (id) => {
    $(`button[name="btn-popover-${id}"]`).popover('dispose')
    await nextTick()
    $(`button[name="btn-popover-${id}"]`).popover()
}

</script>

<template>
    <div class="d-flex justify-content-center">
        <div class="cnt-container" v-if="cart.inCart(product.id)>0" @mouseenter="show_addition_btn=true"
             @mouseleave="show_addition_btn=false">
            <div class="group-btn-counter">
                <button class="btn btn-coutner mr-3" :name="'btn-popover-'+product.id"
                        data-trigger="hover" data-toggle="popover" data-placement="bottom"
                        data-content="Убрать весь продукт из корзины"
                        @click="cart.removeProduct(product.id); setPopover(product.id);">
                    <span><i class="fas fa-trash"></i></span>
                </button>

                <button class="btn btn-coutner" :name="'btn-popover-'+product.id"
                        @click="cart.decQuantity(product.id); setPopover(product.id);" data-trigger="hover"
                        data-toggle="popover" data-placement="bottom" data-content="Убрать порцию из корзины">
                    -
                </button>
            </div>
            <p v-html="cart.inCart(product.id)" :class="inModal ? 'text-dark' : 'text-white'"></p>
            <button class="btn btn-coutner" :name="'btn-popover-'+product.id"
                    @click="cart.incQuantity(product.id); setPopover(product.id);" data-trigger="hover"
                    data-toggle="popover" data-placement="bottom" data-content="Добавить еще порцию в корзину">
                <span>+</span>
            </button>
            <div class="btn btn-success sub-btn" @click="openCart()" v-if="show_addition_btn">Оформить</div>
        </div>

        <button class="btn btn-danger w-100 text-uppercase mt-2 p-3"
                @click="cart.addToCart(product)"
                :disabled="!hasMainProductInCart()"
                v-if="!(current_day_index < product.day_index) && cart.inCart(product.id) === 0">В КОРЗИНУ
            <span>{{ orderDay(product.day_index) }}</span>
        </button>

        <button class="btn btn-success w-100 text-uppercase mt-2 p-3 custom-btn-color-success"
                :name="'btn-popover-'+product.id"
                data-trigger="hover"
                data-toggle="popover" data-placement="bottom" data-content="Добавить товар в корзину"
                @click="cart.addToCart(product); setPopover(product.id);"
                :disabled="!hasMainProductInCart()"
                v-if="(current_day_index < product.day_index) && cart.inCart(product.id) === 0">
            В КОРЗИНУ
        </button>

    </div>
</template>
