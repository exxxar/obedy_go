<script setup>
import {onMounted, onUnmounted, provide, ref, watch} from 'vue'
import Modal from '@/Components/Modal.vue'
import TopMenu from "@/Components/TopMenu.vue"
import AudioCallback from "@/Components/AudioCallback.vue"
import CartModal from "@/Components/CartModal.vue"
import {useMainStore} from '@/stores/mainStore.js'
import {useCartStore} from '@/stores/cartStore.js'
import {storeToRefs} from "pinia"
import Carousel from "@/Components/Carousel.vue"

//get store
const main = useMainStore()
const {foodParts, part} = storeToRefs(main)
const {getTotalCount} = storeToRefs(useCartStore())

//data
const lottery_id = ref(null)
const bottom_menu_show = ref(false)
const is_cart_open = ref(false)
const ready_to_order = ref(false)

provide('ready_to_order', ready_to_order)
provide('is_cart_open', is_cart_open)

// watch
watch(part, () => {
    main.selectPart()
})

onMounted(() => {
    switchFoodPart()
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover()
    })
    window.addEventListener("keyup", switchKeyUp)
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

const selectLottery = id => {
    console.log("select=>" + id)
    lottery_id.value = id
}
</script>

<template>
    <div class="wrapper">
        <notifications position="top left" group="info"/>

        <div class="container-fluid d-flex align-items-center flex-wrap w-100 m-0 obedygo"
             style="min-height: 100vh;padding:50px 10px 10px 10px;">

            <slot name="content" v-if="part === 0"></slot>
            <TopMenu v-else></TopMenu>

            <div class="row w-100 d-flex justify-content-center mt-5 mb-0 ml-0">
                <h6 class="text-uppercase text-white text-center col-8 col-sm-8">Полное меню на неделю можно глянуть <a
                    href="#" class="text-danger font-weight-bold" data-toggle="modal" data-target="#menu">ТУТ</a>
                </h6>
                <template v-for="foodPart in foodParts">
                    <h1 class="text-uppercase w-100 text-white text-center mt-2 ml-0 mr-0"
                        v-if="part === foodPart.partId">{{ foodPart.title }}</h1>
                </template>
            </div>

            <slot name="content" v-if="part>0&&part<4"></slot>

            <div class="row w-100 m-0 d-flex justify-content-center " v-if="part===4">
                <div class="col-lg-6 col-md-6 col-sm-8 col-12">
                    <h4 class="text-white">
                        <em>В данный момент этот раздел не доступен, но это не должно Вас огорчать - теперь вы можете
                            <span class="text-danger">оформить заказ голосом!</span>
                        </em>
                    </h4>
                    <!--  <obedy-callback></obedy-callback> -->
                </div>
            </div>

            <div class="mb-5 w-100"></div>

            <CartModal v-if="is_cart_open"/>

            <div class="cart-container d-sm-block d-none" v-if="!is_cart_open">
                <div class="cart-icon cart" @click="is_cart_open = true">Корзина <span
                    v-if="getTotalCount>0">{{ getTotalCount }}</span>
                </div>
                <div class="cart-icon about" data-toggle="modal" data-target="#about">О нас</div>
                <div class="cart-icon callback" data-toggle="modal" data-target="#callback">Напиши нам</div>
                <div class="cart-icon delivery" data-toggle="modal" data-target="#delivery">Доставка</div>
                <div class="cart-icon lottery" data-toggle="modal" data-target="#lottery"
                     @click="lottery_id.value = null">Акции
                </div>
            </div>

            <ul class="footer-container d-sm-none d-flex justify-content-center flex-wrap"
                @mouseleave="bottom_menu_show=false">
                <li class="w-100 p-2 text-center" v-if="!bottom_menu_show" @click="bottom_menu_show=true">Показать меню
                    <span class="badge badge-danger" v-if="getTotalCount>0">{{ getTotalCount }}</span></li>
                <li class="w-100 p-2 text-center" v-if="bottom_menu_show" @click="bottom_menu_show=false">Скрыть меню
                </li>
                <ul class="w-100 bottom_menu" v-if="bottom_menu_show">
                    <li v-for="foodPart in foodParts" @click="part = foodPart.partId">{{ foodPart.title }}</li>
                    <li class="hr"></li>
                    <li @click="is_cart_open = true">Корзина <span class="badge badge-danger"
                                                                   v-if="countInCart>0">{{ countInCart }}</span></li>
                    <li data-toggle="modal" data-target="#about">О нас</li>
                    <li data-toggle="modal" data-target="#callback">Напиши нам</li>
                    <li data-toggle="modal" data-target="#delivery">Доставка</li>
                    <li data-toggle="modal" data-target="#lottery" @click="lottery_id.value = null">Акции</li>
                </ul>
            </ul>

            <div v-if="part===0" class="mb-5 w-100"></div>

            <Modal id="about" title="О Нас">
                <template #body>
                    <img src="/images/logo_obed_go.jpg" class="img-fluid" alt="О нас">
                    <p class="color--black text-justify">Обеды GO - это доставка вкуснейших обедов, приготовленных с
                        любовью. Здесь каждый найдет для себя то, что ищет - домашние блюда, блюда "премиум", здоровая
                        еда, стритфуд и многое другое. Вы можете выбрать для себя сформированное ежедневное комплексное
                        меню или же собрать свой рацион по вкусу.</p>
                </template>
            </Modal>

            <Modal id="delivery" title="Условия доставки">
                <template #body>
                    <ul class="about-list">
                        <li>Доставка осуществляется с 12:00 до 14:00 только в будние дни.</li>
                        <li>Блюда из разделов "стандарт" и "премиум" заказываются ЗА ДЕНЬ. Предзаказ на понедельник
                            также доступен и в выходные дни.
                        </li>
                        <li>Блюда из раздела "экспресс" заказываются и доставляются день в день.</li>
                        <li>Дополнительные блюда заказываются за сутки!</li>
                    </ul>
                </template>
            </Modal>

            <Modal id="callback" title="Обратная связь">
                <template #body>
                    <div class="row">
                        <div class="col-12">
                            <!-- <obedy-callback :selected_type="1"></obedy-callback> -->
                            <audio-callback></audio-callback>
                        </div>
                    </div>
                </template>
            </Modal>

            <Modal id="lottery" class_size="modal-lg" title="Розыгрыши и призы">
                <template #body>
                    <!-- <lottery-slider v-on:enter="selectLottery" v-if="!lottery_id"/>
                    <lottery-game :lottery_id="lottery_id" v-if="lottery_id"/> -->
                </template>
            </Modal>

            <Modal id="menu" class_size="modal-lg" :header="false">
                <template #body>
                    <Carousel
                        id="carousel-fade"
                        :items="[
                          '/images/full_1.jpg',
                          '/images/full_2.jpg',
                        ]">
                    </Carousel>
                </template>
            </Modal>

            <div class="to-left" @click="main.changePosition(false)"></div>
            <div class="to-right" @click="main.changePosition()"></div>
        </div>
    </div>
</template>
