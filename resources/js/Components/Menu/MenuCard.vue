<script setup>
import {computed, nextTick, ref, watch} from "vue"
import { modals } from "@/app"
import { router } from '@inertiajs/vue3'


const props = defineProps({
    inModal: {
        type: Boolean,
        default: false
    },
    modelValue: {
        type: Number,
        default: null
    },
    menu: {
        type: Object,
        default: {}
    }
})

const emit = defineEmits(['update:modelValue']);
const local = ref(props.modelValue);
watch(local, (newValue) => {
    emit('update:modelValue', newValue);
});
watch(() => props.modelValue, (newValue) => {
    local.value = newValue;
})

const isProfile = computed(()=>{
    return route().current('profile')
})

const selectSpecial = async (id) => {
    local.value = id
    await nextTick(() => {
        let specialModal = document.getElementById('specialModal');
        modals.getOrCreateInstance(specialModal).show()
        specialModal.addEventListener('hidden.bs.modal', (event) => {
            local.value = null
        }, { once: true })
    })
}
</script>

<template>
    <div class="card text-center"
         :class="[
             inModal ? 'border-0' : '',
             (isProfile || inModal) ? '' : 'fixed-height-450px m-5px bg-transparent text-white'
         ]">
        <div class="position-absolute top-0 end-0 d-flex gap-1 mt-1 me-1">
            <button class="btn btn-success"
                    :class="inModal ? 'mt-3' : ''"
                    @click="router.get(route('menu.edit', menu.id))" v-if="isProfile">
                <font-awesome-icon icon="fa-solid fa-pen"/>
            </button>
            <button class="btn btn-danger"
                    :class="inModal ? 'mt-3' : ''"
                    @click="" v-if="isProfile">
                <font-awesome-icon icon="fa-solid fa-trash"/>
            </button>
        </div>

        <img v-lazy="menu.image"
             class="card-img-top fixed-height-300px object-fit-cover"
             alt="..." @click="selectSpecial(menu.id)">
        <div class="card-body d-flex flex-column justify-content-between border border-2 border-white rounded-bottom border-top-0"
             :class="inModal ? 'border-0' : ''">
            <div class="d-flex flex-column gap-2" @click="selectSpecial(menu.id)">
                <h3 class="card-title m-0" :class="inModal ? '' : 'text-truncate'">
                    {{ menu.title }}
                </h3>
                <div class="d-flex justify-content-center gap-1 fw-bold" v-if="!isProfile">
                    <p class="card-text">Специалист:</p>
                    <button class="btn btn-link p-0" :class="inModal ? '' : 'text-truncate'">Имя</button>
                </div>
                <div class="d-flex justify-content-center gap-1 fw-bold">
                    <p class="card-text">Цена:</p>
                    <p class="card-text" :class="inModal ? '' : 'text-truncate'">{{ menu.price }} ₽</p>
                </div>
                <p v-if="inModal">{{ menu.description }}</p>
            </div>
            <button class="btn btn-lg btn-danger"
                    :class="inModal ? 'mt-3' : ''"
                    @click="" v-if="!isProfile">Купить</button>
        </div>
    </div>
</template>
