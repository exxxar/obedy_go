<script setup>
import {inject, provide, reactive} from "vue"
import {storeToRefs} from "pinia"
import {useCartStore} from '@/stores/cartStore.js'
import ProductCard from "@/Components/Products/ProductCard.vue"
import OrderForm from "@/Components/Cart/OrderForm.vue";
import UserCartModal from "@/Components/Cart/UserCartModal.vue"
import {useUserStore} from "@/stores/userStore.js"

const cart = useCartStore()
const {items, hasOtherUserCart} = storeToRefs(cart)
const {user} = storeToRefs(useUserStore())

const settings = reactive({
    suppressScrollX: true,
    suppressScrollY: false,
})

const ready_to_order = inject('ready_to_order')
const is_cart_open = inject('is_cart_open')

provide('inModal', true)

const hasMainProductInCart = () => {
    let tmp = items.value.filter(item => item.product.addition === 0)
    return tmp.length > 0
}

</script>

<template>
    <div class="cart-modal">
        <div class="cart-modal-inner">
            <div class="custom-modal-header" :class="hasOtherUserCart ? 'justify-content-between' : ''">
                <button v-if="hasOtherUserCart" class="btn btn-link fs-5 py-0"
                        data-bs-toggle="modal" data-bs-target="#changeUserCart">
                    <font-awesome-icon class="text-danger" icon="fa-solid fa-circle-info" beat-fade/>
                </button>
                <button class="btn btn-link" @click="is_cart_open = false">Закрыть</button>
            </div>
            <perfect-scrollbar class="scroll-area scroll-area-bottom ms-0 ps-0" :options="settings" tag="ul">
                <li v-for="item in items" class="day-item-wrapper list-group-numbered" v-if="cart.getTotalCount>0">
                    <ProductCard :product="item.product"></ProductCard>
                    <UserCartModal :productId="item.product.id"></UserCartModal>
                </li>
                <li v-if="cart.getTotalCount===0">
                    <img src="/images/logo_obed_go.jpg" class="img-fluid" alt="">
                    <h3 class="text-uppercase text-center">В корзине пусто...</h3>
                </li>
            </perfect-scrollbar>

            <div class="custom-modal-footer" v-if="cart.getTotalCount>0">
                <button class="btn btn-danger w-100 p-3 text-uppercase fw-bold" v-if="!ready_to_order"
                        :disabled="!hasMainProductInCart()"
                        @click="ready_to_order = true">Оформить
                    <strong>{{cart.getTotalPrice}} руб</strong>
                </button>
                <button class="btn btn-outline-danger mt-2 w-100 p-3 text-uppercase fw-bold"
                        v-if="!ready_to_order" @click="cart.clearCart">Очистить</button>
                <OrderForm v-if="ready_to_order" v-model="ready_to_order"></OrderForm>
            </div>
        </div>
        <div class="cart-shadow" @click="is_cart_open = false"></div>
    </div>
</template>
