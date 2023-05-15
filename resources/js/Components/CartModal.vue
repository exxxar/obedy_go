<script setup>
import {inject, provide, reactive} from "vue"
import {useCartStore} from '@/stores/cartStore.js'
import {storeToRefs} from "pinia"
import ProductCard from "@/Components/ProductCard.vue";
import VueCustomScrollbar from 'vue-custom-scrollbar/src/vue-scrollbar.vue'

const cart = useCartStore()
const {items} = storeToRefs(cart)

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
            <div class="custom-modal-header">
                <button class="btn btn-link" @click="is_cart_open = false">Закрыть</button>
            </div>
            <VueCustomScrollbar class="scroll-area scroll-area-bottom" :settings="settings" :tagname="'ul'">
                <li v-for="item in items" class="day-item-wrapper" v-if="cart.getTotalCount>0">
                    <ProductCard :product="item.product"></ProductCard>
                </li>
                <li v-if="cart.getTotalCount===0">
                    <img src="/images/logo_obed_go.jpg" class="img-fluid" alt="">
                    <h3 class="text-uppercase text-center">В корзине пусто...</h3>
                </li>
            </VueCustomScrollbar>

            <div class="custom-modal-footer" v-if="cart.getTotalCount>0">
                <button class="btn btn-danger w-100 p-3 text-uppercase font-weight-bold" v-if="!ready_to_order"
                        :disabled="!hasMainProductInCart()"
                        @click="ready_to_order = true">Оформить
                    <strong>{{cart.getTotalPrice}} руб</strong>
                </button>
                <button class="btn btn-outline-danger mt-2 w-100 p-3 text-uppercase font-weight-bold"
                        v-if="!ready_to_order"
                        @click="cart.clearCart">Очистить
                </button>

                <!--<form v-on:submit.prevent="sendOrder" class="order-form w-100" v-if="ready_to_order">
                    <button class="hider" @click="ready_to_order = false">Скрыть</button>
                    <input type="text" placeholder="Ваше имя" v-model="form.name" required>
                    <input type="text" placeholder="Номер телефона" v-model="form.phone"
                           pattern="[\+]\d{2} [\(]\d{3}[\)] \d{3}[\-]\d{2}[\-]\d{2}"
                           maxlength="19"
                           v-mask="['+38 (071) ###-##-##']"
                           required>
                    <p v-if="message" class="text-success">{{message}}</p>
                    <input type="text" placeholder="Адрес доставки" v-model="form.address" @blur="calcRange" required>
                    <textarea placeholder="Текстовое сообщение" v-model="form.message"></textarea>
                    <ul class="summary">
                        <li>
                            <p>Цена заказа: <span>{{goCartTotalPrice}} руб</span></p>
                        </li>
                        <li>
                            <p>Масса заказа: <span>{{goCartTotalWeight}} гр</span></p>
                        </li>
                        <li v-if="form.delivery_price">
                            <p>Примерная цена доставки: <span>{{form.delivery_price}} руб.</span></p>
                        </li>
                    </ul>
                    <button class="btn btn-success w-100 p-3 text-uppercase font-weight-bold" :disabled="!is_calc_distance">Отправить заявку
                    </button>
                    <p class="end-info">После оформления заявки вам будет предложен для скачивания чек с подробным
                        описанием заказа, его номером и
                        промокодом для лотереии</p>
                </form>-->
            </div>


        </div>
        <div class="cart-shadow" @click="is_cart_open = false"></div>
    </div>
</template>
