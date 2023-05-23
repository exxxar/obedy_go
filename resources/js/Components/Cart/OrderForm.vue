<script setup>
import TextInput from "@/Components/Basic/TextInput.vue"
import {nextTick, onMounted, reactive, ref} from "vue"
import {modals, popover, sendNotify} from "@/app"
import {useCartStore} from '@/stores/cartStore.js'
import {useUserStore} from '@/stores/userStore.js'
import {storeToRefs} from "pinia"
import AddressesSelect from "@/Components/Basic/AddressesSelect.vue"
import Modal from "@/Components/Basic/Modal.vue"

defineProps({
    modelValue: {
        type: String,
    }
});

const emit = defineEmits(['update:modelValue']);

const cart = useCartStore()
const {items, getTotalPrice, getTotalWeight} = storeToRefs(cart)

const userStore = useUserStore()
const {user} = storeToRefs(userStore)

const form = reactive({
    name: '',
    address: null,
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

onMounted(() => {
    form.name = user.value.name
    form.phone = user.value.phone
})

const calcRange = async () => {
    await nextTick()
    message.value = "Мы ищем ваш Адрес, пожалуйста, ожидайте!"
    await axios.post(route('delivery.range'), {
            address: form.address,
        }).then( (response) => {
        message.value = "Цена доставки к Вам " + response.data.price + " руб."
        is_calc_distance.value = true
        form.delivery_price = response.data.price
        form.delivery_range = response.data.range
    })
}

const sendOrder = () => {
    form.products = items.value
    user.value.name = form.name
    user.value.phone = form.phone
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
        if(!user.isAuthorized)
            modals.getOrCreateInstance(document.getElementById('register')).show()
    }).catch(async error => {
        if (error.response.status === 422) {
            const errorJson = JSON.parse(await error.response.data.text());
            errors.value = errorJson.errors
        }
    })
}

const clearMessage = () => {
    message.value = null
}

</script>

<template>
    <form v-on:submit.prevent="sendOrder" class="order-form w-100">
        <button class="hider" @click="$emit('update:modelValue', false)">Скрыть</button>
        <TextInput :errors="errors.hasOwnProperty('name') ? errors.name : []" placeholder="Ваше имя" v-model="form.name"></TextInput>
        <TextInput :errors="errors.hasOwnProperty('phone') ? errors.phone : []" placeholder="Ваш номер телефона"
                   v-model="form.phone" mask="+7 (###) ###-##-##"></TextInput>
        <p v-if="message && form.address !== null" class="text-success mt-2">{{message}}</p>

      <AddressesSelect v-model:formAddress="form.address"
                       :errors="errors.hasOwnProperty('address') ? errors.address : []"
                       :blur="calcRange"
                       :clearMessage="clearMessage"/>

        <div v-if="'address' in errors && errors.address.length > 0"
             class="invalid-feedback" v-for="error in errors.address">{{ error }}</div>
        <textarea class="form-control mb-0 mt-2"
                  :class="[
                      errors.hasOwnProperty('message') && errors.message.length > 0 ? 'is-invalid' : '',
                      !errors.hasOwnProperty('message') && form.message !== '' ? 'is-valid' : '']"
                  placeholder="Текстовое сообщение" v-model="form.message"></textarea>
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
            промокодом для лотереи</p>
    </form>

    <Modal id="register" :header="false" :footer="false">
        <template #body>
            <div class="row justify-content-center">
                <p>Хотите ли вы стать нашим клиентом?</p>
            </div>
        </template>
        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Нет</button>
                <button type="button" class="btn btn-success" @click="userStore.register" data-bs-dismiss="modal">Да</button>
            </div>
        </template>
    </Modal>
</template>

<style lang="scss">

.v-select {
    width: 100%;
    padding: 15px;
    //border: 1px #d50c0d solid;
    margin-top: 0.5rem;
    border-radius: 5px;

    & .vs__dropdown-toggle {
        border: 0;
        padding: 0;

        & .vs__selected-options {
            flex-wrap: nowrap;
            word-wrap: break-word;
        }

        & .vs__search {
            width: 0 !important;
            border: 0 !important;
            margin: 4px 0 0 !important;
            padding: 0 7px !important;
        }

        & .vs__actions .vs__clear {
            display: flex;
        }

    }
}
</style>
