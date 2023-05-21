<script setup>
import TextInput from "@/Components/Basic/TextInput.vue"
import {reactive, ref} from "vue"
import {sendNotify} from "@/app"
import {useCartStore} from '@/stores/cartStore.js'
import {storeToRefs} from "pinia"

defineProps({
    modelValue: {
        type: String,
    }
});

const emit = defineEmits(['update:modelValue']);


const cart = useCartStore()

const {items, getTotalPrice, getTotalWeight} = storeToRefs(cart)

const form = reactive({
    name: '',
    address: '',
    phone: '',
    message: '',
    delivery_price: null,
    delivery_range: null,
    total_weight: 0,
    total_price: 0,
    total_count: 0
})

const is_calc_distance = ref(false)
const message = ref(null)
const errors = ref([])

const calcRange = () => {
    message.value = "Мы ищем ваш Адрес, пожалуйста, ожидайте!";
    axios.post(route('delivery.range'),{
            address: form.address,

        }).then(resp=>{
        message.value = "Цена доставки к Вам "+resp.data.price+" руб.";
        is_calc_distance.value = true;
        form.delivery_price = resp.data.price
        form.delivery_range = resp.data.range
    })
}

const sendOrder = () => {
    form.products = items.value
    axios.post(route('order'), form, {responseType: 'blob'}).then(response => {
        let fileURL = window.URL.createObjectURL(new Blob([response.data]));
        let fURL = document.createElement('a');

        fURL.href = fileURL;
        fURL.setAttribute('download', 'report.pdf');
        document.body.appendChild(fURL);

        fURL.click();

        items.value = [];

        sendNotify('Ваш заказ успешно отправлен! Ожидайте звонка оператора!')
        emit('update:modelValue', false)
    })

}

</script>

<template>
    <form v-on:submit.prevent="sendOrder" class="order-form w-100">
        <button class="hider" @click="$emit('update:modelValue', false)">Скрыть</button>
        <TextInput :errors="errors.name" placeholder="Ваше имя" v-model="form.name"></TextInput>
        <TextInput :errors="errors.phone" placeholder="Ваш номер телефона"
                   v-model="form.phone" mask="+7 (###) ###-##-##"></TextInput>

        <p v-if="message" class="text-success">{{message}}</p>
        <TextInput  :errors="errors.address" placeholder="Адрес доставки" v-model="form.address" :blur="calcRange"></TextInput>
        <textarea placeholder="Текстовое сообщение" v-model="form.message"></textarea>
        <ul class="summary">
            <li>
                <p>Цена заказа: <span>{{getTotalPrice}} руб</span></p>
            </li>
            <li>
                <p>Масса заказа: <span>{{getTotalWeight}} гр</span></p>
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
    </form>
</template>
