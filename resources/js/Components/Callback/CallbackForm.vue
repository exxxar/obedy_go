<script setup>
import TextInput from "@/Components/Basic/TextInput.vue"
import AddressesSelect from "@/Components/Basic/AddressesSelect.vue"
import AudioCallback from "@/Components/Basic/AudioCallback.vue"
import TextTextarea from "@/Components/Basic/TextTextarea.vue"
import { onMounted, reactive, ref, watch } from "vue"
import { sendNotify, myHasOwnProperty } from "@/app"

const props = defineProps({
    selectedType: {
        type: Number,
        default: 0
    }
})

onMounted(() => {
    form.typeValue = props.selectedType
    selectForm.value = question_types[props.selectedType]
})

const question_types = [
    "Оформление заказа",
    "Проблемы с заказом",
    "Стать партнером",
    "Стать доставщиком",
    "Реклама и продвижение",
    "Другие вопросы"
]

const dialog_titles = [
    "Оформить заказ голосом",
    "Опиши свои проблемы",
    "Давай совместно работать",
    "Давай совместно работать",
    "Давай совместно работать",
    "Что-то другое..."
]

const baseForm = () => ({
    name: '',
    phone: '',
    address: '',
    recordings: [],
    message: '',
    typeValue: 0
})

const form = reactive(baseForm())
const errors = ref([])

const sendRequest = async () => {
    let formData = new FormData()
    formData.append('name', form.name)
    formData.append('phone', form.phone)
    formData.append('address', form.address)
    formData.append('message', form.message)
    formData.append('typeValue', form.typeValue)
    form.recordings.forEach((element, index) => {
        formData.append('files[' + index + ']', element.data)
    })

    await axios.post(route('callback'), formData, {
        headers: {'Content-Type': 'multipart/form-data'}
    }).then(response => {
        sendNotify('Ваше сообщение успешно отправлено!')
        errors.value = []
        Object.assign(form, baseForm())
    }).catch(error => {
        if (error.response.status === 422)
            errors.value = error.response.data.errors
    })
}

const selectForm = ref(null)
watch(() => selectForm.value, (newValue, oldValue) => {
    form.typeValue = question_types.indexOf(newValue)
})

</script>

<template>
    <div class="card mb-3 obedy-callback-card">
        <div class="card-body w-100 d-flex flex-column gap-3">
            <h5 class="card-title m-0">{{ dialog_titles[form.typeValue] }}</h5>

            <TextInput :errors="myHasOwnProperty.call(errors, 'name') ? errors.name : []"
                       placeholder="Ваше Ф.И.О"
                       v-model="form.name"
                       groupTextIconLeft="fa-solid fa-user"/>
            <TextInput :errors="myHasOwnProperty.call(errors, 'phone') ? errors.phone : []"
                       placeholder="Номер телефона"
                       v-model="form.phone"
                       groupTextIconLeft="fa-solid fa-phone"
                       mask="+7 (###) ###-##-##"/>
            <div class="input-group">
                <span class="input-group-text justify-content-center w-40px">
                    <font-awesome-icon icon="fa-solid fa-list"/>
                </span>
                <v-select
                    v-model="selectForm"
                    :options="question_types"
                    :clearable="false"
                    class="v--select form-control m-0 ps-3 pe-4 py-3 z-5-important"
                    :class="[selectForm !== null ? 'is-valid' : '']"
                >
                </v-select>
            </div>
            <AddressesSelect v-if="form.typeValue === 0" v-model:formAddress="form.address"
                             :errors="myHasOwnProperty.call(errors, 'address') ? errors.address : []"/>
            <TextTextarea  :errors="myHasOwnProperty.call(errors, 'message') ? errors.message : []"
                           placeholder="Текст сообщения"
                           v-model="form.message"
                           textareaName="message"
                           groupTextIconLeft="fa-solid fa-comment"/>
            <AudioCallback v-model:recordings="form.recordings"></AudioCallback>

            <button class="btn btn-danger text-uppercase fw-bolder p-3 mb-1"
                    @click="sendRequest">
                <font-awesome-icon icon="fa-solid fa-envelope"/>
                Отправить
            </button>
            <div class="input-group justify-content-center mb-2">
                <a data-bs-toggle="modal" data-bs-target="#delivery" class="btn btn-link mb-1"
                   title="Подробности о доставке" aria-label="Подробности о доставке">
                    <font-awesome-icon icon="fa-solid fa-box-tissue"/>
                    Подробности о доставке!
                </a>
            </div>
        </div>
    </div>
</template>
