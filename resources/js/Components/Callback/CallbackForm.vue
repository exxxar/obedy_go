<script setup>
import TextInput from "@/Components/Basic/TextInput.vue"
import AddressesSelect from "@/Components/Basic/AddressesSelect.vue"
import AudioCallback from "@/Components/Basic/AudioCallback.vue"
import {onMounted, reactive, ref} from "vue"
import {sendNotify} from "@/app";

const props = defineProps({
    selectedType: {
        type: Number,
        default: 0
    },
})

onMounted(() => {
    form.typeValue = props.selectedType
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

const baseForm = {
    name: '',
    phone: '',
    address: '',
    recordings: [],
    message: '',
    typeValue: 0
}

const errors = ref([])

const form = reactive({...baseForm})

const sendRequest = () => {

    let formData = new FormData()

    formData.append('phone', form.phone)
    formData.append('name', form.name)
    formData.append('address', form.address)
    formData.append('message', form.message)
    formData.append('typeValue', form.typeValue)

    for (let i = 0; i < form.recordings.length; i++) {
        let file = form.recordings[i].data;
        console.log(file);
        formData.append('files[' + i + ']', file)
    }

    axios.post(route('callback'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
        }
    ).then(function () {
        sendNotify('Ваше сообщение успешно отправлено!')
        Object.assign(form, baseForm)
        errors.value = []
    }).catch(async error => {
        if (error.response.status === 422) {
            errors.value = error.response.data.errors
        }
    })


}

</script>

<template>
    <div class="card mb-3 obedy-callback-card">
        <form v-on:submit.prevent="sendRequest" class="card-body">
            <h5 class="card-title">{{ dialog_titles[form.typeValue] }}</h5>
            <div class="form-group mb-3">
                <TextInput :errors="errors.hasOwnProperty('name') ? errors.name : []" placeholder="Ваше Ф.И.О"
                           v-model="form.name"></TextInput>
            </div>
            <div class="form-group mb-3">
                <TextInput :errors="errors.hasOwnProperty('phone') ? errors.phone : []" placeholder="Номер телефона"
                           v-model="form.phone" mask="+7 (###) ###-##-##"></TextInput>
            </div>
            <div class="form-group mb-3">
                <select name="question-type" v-model="form.typeValue" class="form-select px-4 py-3" required>
                    <option v-for="(option,index) in question_types" :value="index"
                            :selected="form.typeValue === index ? 'selected' : ''">
                        {{ option }}
                    </option>
                </select>
            </div>
            <div class="form-group mb-3" v-if="form.typeValue===0">
                <AddressesSelect v-model:formAddress="form.address"
                                 :errors="errors.hasOwnProperty('address') ? errors.address : []"
                />
                <div v-if="'address' in errors && errors.address.length > 0"
                     class="invalid-feedback" v-for="error in errors.address">{{ error }}
                </div>
            </div>
            <div class="form-group mb-3">
                    <textarea name="message" v-model="form.message" class="form-control"
                              placeholder="Текст сообщения"
                              :class="[errors.hasOwnProperty('message') && errors.message.length > 0 ? 'is-invalid' : '',
                              (!errors.hasOwnProperty('message') || errors.message.length === 0) && form.message.length > 0 ? 'is-valid' : '']">
                    </textarea>
                <div v-if="errors.hasOwnProperty('message') && errors.message.length > 0 " class="invalid-feedback"
                     v-for="error in errors.message">{{ error }}
                </div>
            </div>


            <div class="form-group mb-3">
                <AudioCallback v-model="form.recordings"></AudioCallback>
            </div>
            <div class="form-group">
                <button class="btn btn-danger w-100 p-3 text-uppercase font-weight-bold mr-1 mb-1 w-100">
                    <i class="icon ion-md-mail"></i>
                    Отправить
                </button>
            </div>
            <div class="form-group mb-2 d-flex justify-content-center">

                <a data-bs-toggle="modal" data-bs-target="#delivery" class="btn btn-link mr-1 mb-1"
                   title="Подробности о доставке"
                   aria-label="Подробности о доставке">
                    <i class="icon ion-ios-filing"></i>
                    Подробности о доставке!
                </a>

            </div>


        </form>
    </div>
</template>

