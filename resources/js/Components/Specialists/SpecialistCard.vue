<script setup>
import axios from "axios"
import { router } from '@inertiajs/vue3'
import { modals, sendNotify } from "@/app"
import { nextTick, onMounted } from "vue"

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

const emit = defineEmits(['update:specialistId'])

onMounted(() => {
    if (props.specialistId !== null)
        document.getElementById('specialistModal').addEventListener('hidden.bs.modal', (event) => {
            emit('update:specialistId', null)
        }, { once: true })
})

const selectSpecialist = async (id) => {
    emit('update:specialistId', id)
    await nextTick(() => {
        let specialistModal = document.getElementById('specialistModal');
        modals.getOrCreateInstance(specialistModal).show()
        specialistModal.addEventListener('hidden.bs.modal', (event) => {
            emit('update:specialistId', null)
        }, { once: true })
    })
}

const chooseSpecialist = async (id) => {
    await axios.get(route('create.chat', id)).then(resp => {
        closeSpecialistModal()
        router.get(route('chats'), {'chatId': resp.data.chatId})
    }).catch(error => {
        if (error.response.status === 401)
            sendNotify('Для начала общения Вам необходимо войти в свой аккаунт', 'error')
        if (error.response.status === 404)
            sendNotify('Выбранный специалист недоступен для общения', 'error')
    })
}

const closeSpecialistModal = async () => {
    await nextTick(() => {
        if (props.inModal)
            modals.getOrCreateInstance(document.getElementById('specialistModal')).hide()
    })
}
</script>

<template>
    <div class="card text-center border-0"
         :class="inModal ? '' : 'p-5px bg-transparent text-white'">
        <div class="ratio ratio-1x1">
            <img :src="specialist.image" class="object-fit-cover" alt="..."
                 @click="selectSpecialist(specialist.id)">
        </div>

        <div class="card-body d-flex flex-column justify-content-between"
             :class="inModal ? 'gap-3' : 'p-0'">
            <div class="d-flex flex-column gap-2"
                 @click="selectSpecialist(specialist.id)">
                <h5 class="card-title m-0" v-if="inModal">{{ specialist.name }}</h5>
                <h2 class="card-title m-0 text-truncate" v-if="!inModal">{{ specialist.name }}</h2>
                <p class="card-text" v-if="inModal">{{ specialist.description }}</p>
            </div>
            <button class="btn w-100 text-uppercase mt-2 p-3 btn-danger custom-btn-color-danger"
                    @click="chooseSpecialist(specialist.id)"
                    v-if="!specialist.isCurrentUser">Написать
            </button>
            <button class="btn w-100 text-uppercase mt-2 p-3 btn-success custom-btn-color-success"
                    @click="router.get(route('profile'))"
                    v-if="specialist.isCurrentUser">Профиль
            </button>
        </div>
    </div>
</template>

