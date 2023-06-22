<script setup>
import axios from "axios"
import {router} from '@inertiajs/vue3'
import {modals, sendNotify} from "@/app"
import {nextTick, ref, watch} from "vue"

const props = defineProps({
    specialist: {
        type: Object,
        default: {}
    },
    inModal: {
        type: Boolean,
        default: false
    },
    specialistId: {
        type: Number,
        default: null
    }
})

const chooseSpecialist = async (id) => {
    await axios.get(route('create.chat', id)).then(resp => {
        router.get(route('chats'), {'chatId': resp.data.chatId})
    }).catch(error => {
        if (error.response.status === 401) {
            sendNotify('Для начала общения Вам необходимо войти в свой аккаунт', 'error')
        }
        if (error.response.status === 404) {
            sendNotify('Выбранный специалист недоступен для общения', 'error')
        }
    })
}

const emit = defineEmits(['update:specialistId']);
const local = ref(props.specialistId);
watch(local, (newValue) => {
    emit('update:specialistId', newValue);
});
watch(() => props.specialistId, (newValue) => {
    local.value = newValue;
})
const selectSpecialist = async (id) => {
    local.value = id
    await nextTick(() => {
        let productModal = document.getElementById('specialistModal');
        modals.getOrCreateInstance(productModal).show()
        productModal.addEventListener('hidden.bs.modal', (event) => {
            local.value = null
        }, { once: true })
    })
}
</script>

<template>
    <div class="card text-center" :class="inModal ? 'border-0' : 'fixed-height-500px m-5px'">
        <img :src="specialist.image" class="card-img-top fixed-height-250px object-fit-cover" alt="...">
        <div class="card-body d-flex flex-column justify-content-between">
            <div class="d-flex flex-column">
                <h5 class="card-title text-truncate">{{ specialist.name }}</h5>
                <p class="card-text" :class="inModal ? '' : 'cropped-text'">{{ specialist.description }}</p>
                <p v-if="!inModal" class="link-primary cursor-pointer m-auto"
                   @click="selectSpecialist(specialist.id)">подробнее</p>
            </div>
            <button class="btn btn-lg btn-danger" :class="inModal ? 'mt-3' : ''" @click="chooseSpecialist(specialist.id)"
                    v-if="!specialist.isCurrentUser">Написать
            </button>
        </div>
    </div>
</template>

