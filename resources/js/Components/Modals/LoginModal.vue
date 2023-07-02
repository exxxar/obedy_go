<script setup>
import {modals, myHasOwnProperty} from "@/app"
import {nextTick, reactive, ref} from "vue"
import {useUserStore} from "@/stores/userStore"
import TextInput from "@/Components/Basic/TextInput.vue"
import Modal from '@/Components/Basic/Modal.vue'

const userStore = useUserStore()

const defaultUser = () => ({
    phone: '',
    password: ''
})

const form = reactive(defaultUser())
const errors = ref([])

const passwordHidden = ref(true)

const clearForm = () => {
    Object.assign(form, defaultUser())
    errors.value = []
}

const btnLogin = () => {
    userStore.login(form).then((response) => {
            closeLoginModal()
            clearForm()
        }, (error) => {
            if (error.hasOwnProperty('response'))
                errors.value = error.response.data.errors
        }
    )
}

const closeLoginModal = async () => {
    await nextTick(() => {
        if (userStore.user.isAuthorized)
            modals.getOrCreateInstance(document.getElementById('login')).hide()
    })
}

const changePasswordVisibility = () => {
    passwordHidden.value = !passwordHidden.value;
    document.getElementById('modal-profile-password').setAttribute('type', passwordHidden.value ? 'password' : 'text')
}

</script>

<template>
    <Modal id="login" title="Войти в аккаунт" :footer="false" :clear-function="clearForm" class_size="modal-lg">
        <template #body>
            <div class="row d-flex gap-3">
                <TextInput :errors="myHasOwnProperty.call(errors, 'phone') ? errors.phone : []"
                           placeholder="Ваш номер телефона"
                           v-model="form.phone"
                           groupTextIconLeft="fa-solid fa-phone"
                           mask="+7 (###) ###-##-##"/>
                <TextInput :errors="myHasOwnProperty.call(errors, 'password') ? errors.password : []"
                           placeholder="Ваш пароль"
                           v-model="form.password"
                           :type="passwordHidden ? 'password' : 'text'"
                           input-id="modal-profile-password"
                           inputGroupClass="password-control"
                           groupTextIconLeft="fa-solid fa-lock">
                    <template #groupTextIconRight>
                        <div class="input-group-text cursor-pointer" @click="changePasswordVisibility()">
                            <font-awesome-icon v-if="passwordHidden" icon="fa-solid fa-eye-slash" size="lg"/>
                            <font-awesome-icon v-if="!passwordHidden" icon="fa-solid fa-eye" size="lg"/>
                        </div>
                    </template>
                </TextInput>
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
