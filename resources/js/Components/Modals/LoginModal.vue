<script setup>
import {modals} from "@/app"
import {reactive, ref} from "vue"
import {useUserStore} from "@/stores/userStore"
import {storeToRefs} from "pinia"
import TextInput from "@/Components/Basic/TextInput.vue"
import Modal from '@/Components/Basic/Modal.vue'

const userStore = useUserStore()

const defaultUser = {
    phone: '',
    password: ''
}

const form = reactive({...defaultUser})
const errors = ref([])

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
            if (error.hasOwnProperty('response'))
                errors.value = error.response.data.errors
        }
    )
}
</script>

<template>
    <Modal id="login" title="Войти в аккаунт" :footer="false" :clear-function="clearForm">
        <template #body class="row d-flex justify-content-center">
            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <TextInput :errors="errors.hasOwnProperty('phone') ? errors.phone : []"
                               placeholder="Ваш номер телефона"
                               v-model="form.phone" mask="+7 (###) ###-##-##"></TextInput>
                </div>
                <div class="col-12">
                    <TextInput :errors="errors.hasOwnProperty('password') ? errors.password : []"
                               placeholder="Ваш пароль"
                               v-model="form.password" type="password"></TextInput>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-success" @click="btnLogin">Да</button>
            </div>
        </template>
    </Modal>
</template>
