<script setup>
import axios from "axios";
import {router} from '@inertiajs/vue3'
import {sendNotify} from "@/app"
import {reactive} from "vue";

const props = defineProps({
    specialist: {
        type: Object,
        default: {}
    },
    inModal: {
        type: Boolean,
        default: false
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


</script>

<template>
    <div class="card text-center" :class="inModal ? 'border-0' : 'fixed-height-500px m-5px'">
        <img :src="specialist.image" class="card-img-top fixed-height-250px object-fit-cover" alt="...">
        <div class="card-body d-flex flex-column justify-content-between">
            <div class="d-flex flex-column">
                <h5 class="card-title">{{ specialist.name }}</h5>
                <p class="card-text" :class="inModal ? '' : 'cropped-text'">{{ specialist.description }}</p>
                <p v-if="!inModal" class="link-primary cursor-pointer m-auto" data-bs-toggle="modal" :data-bs-target="'#specialist-'+specialist.id">подробнее</p>
            </div>
            <button class="btn btn-lg btn-danger" :class="inModal ? 'mt-3' : ''" @click="chooseSpecialist(specialist.id)"
                    v-if="!specialist.isCurrentUser">Написать
            </button>
        </div>
    </div>
</template>

