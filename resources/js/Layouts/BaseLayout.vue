<script setup>
import {computed, nextTick, onMounted, onUnmounted, provide, reactive, ref, watch} from 'vue'
import Modal from '@/Components/Basic/Modal.vue'
import TopMenu from "@/Components/Layout/TopMenu.vue"
import AudioCallback from "@/Components/Basic/AudioCallback.vue"
import CartModal from "@/Components/Cart/CartModal.vue"
import {useMainStore} from '@/stores/mainStore.js'
import {useCartStore} from '@/stores/cartStore.js'
import {storeToRefs} from "pinia"
import Carousel from "@/Components/Basic/Carousel.vue"
import Lotteries from "@/Components/Lotteries/Lotteries.vue"
import {useLotteryStore} from "@/stores/lotteryStore.js"
import LotteryGame from "@/Components/Lotteries/LotteryGame.vue"
import {modals, popover} from "@/app"
import {useUserStore} from "@/stores/userStore"
import TextInput from "@/Components/Basic/TextInput.vue"
import UserCartModal from "@/Components/Cart/UserCartModal.vue";

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

const defaultUser = {
    phone: '',
    password: ''
}

const form = reactive({...defaultUser})
const errors = ref([])

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

const clearForm = () => {
    Object.assign(form, defaultUser)
    errors.value = []
}

const btnLogin = () => {
    userStore.login(form).then(
        (response) => {
            modals.getOrCreateInstance(document.getElementById('login')).hide()
            clearForm()
        },
        (error) => {
            if(error.hasOwnProperty('response'))
                errors.value = error.response.data.errors
        }
    )
}

const hasOtherUserCart = computed(()=>{
    return  items.value.find(item =>{
        let res = item.users.find(userItem => userItem.phone !== user.value.phone)
        return !!res

    }) !== undefined
})

watch(hasOtherUserCart, (val) => {
    if(!val)
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

            <slot name="content" v-if="part>0&&part<4"></slot>

            <div class="w-100 row m-0 d-flex flex-wrap justify-content-center mb-5" v-if="part===4">
                <!-- проверить когда будет сделана форма -->
                <div class="col-lg-6 col-md-6 col-sm-8 col-12">
                    <h4 class="text-white">
                        <em>В данный момент этот раздел не доступен, но это не должно Вас огорчать - теперь вы можете
                            <span class="text-danger">оформить заказ голосом!</span>
                        </em>
                    </h4>
                    <!--  <obedy-callback></obedy-callback> -->
                </div>
            </div>

            <div class="w-100" v-if="part === 4"></div>

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

            <Modal id="lottery" class_size="modal-lg" title="Розыгрыши и призы" :clear-function="clearForm">
                <template #body>
                    <Lotteries v-if="!lottery_id"/>
                    <LotteryGame v-else/>
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

            <Modal id="login" title="Войти в аккаунт" :footer="false" :clear-function="clearForm">
                <template #body>
                    <div class="row justify-content-center">
                        <TextInput :errors="errors.hasOwnProperty('phone') ? errors.phone : []"
                                   placeholder="Ваш номер телефона"
                                   v-model="form.phone" mask="+7 (###) ###-##-##"></TextInput>
                        <TextInput :errors="errors.hasOwnProperty('password') ? errors.password : []"
                                   placeholder="Ваш пароль"
                                   v-model="form.password" type="password"></TextInput>
                    </div>
                </template>
                <template #footer>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-success" @click="btnLogin">Да</button>
                    </div>
                </template>
            </Modal>
            <UserCartModal v-if="user.isAuthorized && hasOtherUserCart"></UserCartModal>

            <div class="to-left" @click="main.changePosition(false)"></div>
            <div class="to-right" @click="main.changePosition()"></div>
        </div>
    </div>
</template>
