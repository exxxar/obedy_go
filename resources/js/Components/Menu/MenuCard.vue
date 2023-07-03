<script setup>
import {computed, inject, nextTick} from "vue"
import {modals, sendNotify} from "@/app"
import { router } from '@inertiajs/vue3'

const props = defineProps({
    inModal: {
        type: Boolean,
        default: false
    },
    specialId: {
        type: Number,
        default: null
    },
    specialistId: {
        type: Number,
        default: null
    },
    menu: {
        type: Object,
        default: {}
    }
})

const emit = defineEmits(['update:specialId', "update:specialistId", "deleteMenu"])

const swal = inject('$swal')

const isProfile = computed(() => {
    return route().current('profile')
})

const selectSpecial = async (id) => {
    emit('update:specialId', id)
    await nextTick(() => {
        let specialModal = document.getElementById('specialModal');
        modals.getOrCreateInstance(specialModal).show()
        specialModal.addEventListener('hidden.bs.modal', (event) => {
            emit('update:specialId', null)
        }, { once: true })
    })
}

const editSpecial = async (id) => {
    await closeSpecialModal()
    router.get(route('menu.edit', id))
}

const closeSpecialModal = async () => {
    await nextTick(() => {
        if (props.inModal)
            modals.getOrCreateInstance(document.getElementById('specialModal')).hide()
    })
}

const selectSpecialist = async (id) => {
    emit('update:specialistId', id)
    await nextTick(() => {
        let specialistModal = document.getElementById('specialistModal')
        closeSpecialModal()
        modals.getOrCreateInstance(specialistModal).show()
        specialistModal.addEventListener('hidden.bs.modal', (event) => {
            emit('update:specialistId', null)
        }, { once: true })
    })
}

const buyMenu = () => {
    closeSpecialModal()
}
const getMenuProducts = () => {
    closeSpecialModal()
    router.get(route('special.menu', props.menu.slug))
}

const deleteSpecial = async (id) => {
    swalWithBootstrapButtons.fire({
        title: 'Вы уверены что хотите удалить меню?',
        text: "Действие отменить невозможно!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Нет, отменить!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(route('menu.destroy', id)).then(response => {
                sendNotify('Меню успешно удалено!')
                if(props.inModal)
                    closeSpecialModal()
                emit('deleteMenu', id)
            })
        }
    })
}

const swalWithBootstrapButtons = swal.mixin({
    customClass: {
        actions: 'gap-3',
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
})
</script>

<template>
    <div class="card text-center border-0"
         :class="[(isProfile || inModal) ? '' : 'p-5px bg-transparent text-white']">
        <div class="position-absolute top-0 end-0 d-flex gap-1 mt-1 me-1" v-if="isProfile">
            <button class="btn btn-success" @click="editSpecial(menu.id)">
                <font-awesome-icon icon="fa-solid fa-pen"/>
            </button>
            <button class="btn btn-danger" @click="deleteSpecial(menu.id)">
                <font-awesome-icon icon="fa-solid fa-trash"/>
            </button>
        </div>

        <img v-lazy="menu.image"
             class="card-img-top fixed-height-300px object-fit-cover"
             alt="..." @click="selectSpecial(menu.id)">
        <div class="card-body d-flex flex-column justify-content-between gap-3"
             :class="inModal ? '' : 'p-0'">
            <div class="d-flex flex-column gap-2" @click="selectSpecial(menu.id)" v-if="inModal">
                <h3 class="card-title m-0" :class="inModal ? '' : 'text-truncate'">
                    {{ menu.title }}
                </h3>
                <div class="d-flex justify-content-center gap-1 fw-bold" v-if="!isProfile">
                    <p class="card-text">Специалист:</p>
                    <button class="btn btn-link p-0" :class="inModal ? '' : 'text-truncate'"
                            @click="selectSpecialist(menu.specialist.id)">{{menu.specialist.name}}</button>
                </div>
                <div class="d-flex justify-content-center gap-1 fw-bold">
                    <p class="card-text">Цена:</p>
                    <p class="card-text" :class="inModal ? '' : 'text-truncate'">{{ menu.price }} ₽</p>
                </div>
                <p v-if="inModal">{{ menu.description }}</p>
            </div>
            <button v-if="!isProfile" class="btn w-100 text-uppercase mt-2 p-3"
                    :class="[menu.isUserMenu ? 'btn-success custom-btn-color-success' : 'btn-danger custom-btn-color-danger']"
                    @click="menu.isUserMenu ? getMenuProducts() : buyMenu()">{{menu.isUserMenu ? 'Подробнее' : 'Купить'}}</button>
        </div>
    </div>
</template>
