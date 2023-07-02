<script setup>
import { nextTick, onMounted, ref } from "vue"
import PageTitle from "@/Components/Layout/PageTitle.vue"
import Modal from '@/Components/Basic/Modal.vue'
import MenuCard from "@/Components/Menu/MenuCard.vue"
import TextInput from "@/Components/Basic/TextInput.vue"
import AddressesSelect from "@/Components/Basic/AddressesSelect.vue"
import { useProfileStore } from "@/stores/profileStore"
import { storeToRefs } from "pinia"
import { router } from '@inertiajs/vue3'
import { sendNotify, myHasOwnProperty } from "@/app"
import TextTextarea from "@/Components/Basic/TextTextarea.vue"

const profileStore = useProfileStore()
const { profile, errors, menus, uploadImage } = storeToRefs(profileStore)

const passwordHidden = ref(true)
const selectSpecialId = ref(null)

onMounted(() => {
    profileStore.getProfile()
})

const changePasswordVisibility = () => {
    passwordHidden.value = !passwordHidden.value;
    document.getElementById('profile-password').setAttribute('type', passwordHidden.value ? 'password' : 'text')
}

const onFileChange = async (event) => {
    let files = event.target.files;
    if (!files.length)
        return
    if (files[0] && files[0].type.includes('image')) {
        uploadImage.value = files[0]
        await nextTick(() => {
            profileStore.updateProfileAvatar()
        })
    }
    else {
        sendNotify('Произошла ошибка! Файл не является изображением!', 'error')
        event.target.value = ''
    }
}

const openProfileImage = (event) => {
    let el = event.target
    if (el.localName === 'button')
        el.previousElementSibling.click()
    else if (el.localName === 'svg' || el.localName === 'path')
        el.closest('button').previousElementSibling.click()
}
</script>

<template>
    <PageTitle title="Профиль"/>
    <div class="container tab-content pb-3 z-10">
        <div class="row justify-content-center mt-4">
            <div class="col mt-5">
                <div class="profile-card card position-relative mt-5 p-3 pt-5">
                    <div class="profile-card__img">
                        <img :src="profile.image"
                             id="imgProfileImage" alt="profile card">
                        <input class="d-none" accept="image/*"
                               type="file" @change="onFileChange" />
                        <button class="btn rounded-circle border bg-white d-flex
                                       p-075 position-absolute end-0 bottom-0 text-dark"
                                @click="openProfileImage">
                            <i class="fa-solid fa-camera fa-xl"></i>
                        </button>
                    </div>

                    <div class="accordion w-100 d-flex flex-column mt-5" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">
                                    Данные профиля
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body d-flex flex-column gap-3 align-items-center">
                                    <div class="w-100 d-flex flex-column gap-3">
                                        <TextInput :errors="myHasOwnProperty.call(errors, 'name') ? errors.name : []"
                                                   placeholder="Ваше Ф.И.О"
                                                   v-model="profile.name"
                                                   groupTextIconLeft="fa-solid fa-user"/>
                                        <TextInput :errors="myHasOwnProperty.call(errors, 'phone') ? errors.phone : []"
                                                   placeholder="Номер телефона"
                                                   v-model="profile.phone"
                                                   groupTextIconLeft="fa-solid fa-phone"
                                                   mask="+7 (###) ###-##-##"/>
                                        <AddressesSelect v-model:formAddress="profile.address"
                                                         :errors="myHasOwnProperty.call(errors, 'addresses') ? errors.addresses : []"/>

                                        <TextTextarea  v-if="profile.isSpecialist"
                                                       :errors="myHasOwnProperty.call(errors, 'description') ? errors.description : []"
                                                       placeholder="Описание"
                                                       v-model="profile.description"
                                                       textareaName="description"
                                                       textareaId="profile-description"
                                                       groupTextIconLeft="fa-solid fa-comment"/>

                                        <TextInput :errors="myHasOwnProperty.call(errors, 'password') ? errors.password : []"
                                                   placeholder="Пароль"
                                                   v-model="profile.password"
                                                   :type="passwordHidden ? 'password' : 'text'"
                                                   input-id="profile-password"
                                                   inputGroupClass="password-control"
                                                   groupTextIconLeft="fa-solid fa-lock">
                                            <template #groupTextIconRight>
                                                <div class="input-group-text cursor-pointer" @click="changePasswordVisibility()">
                                                    <font-awesome-icon v-if="passwordHidden" icon="fa-solid fa-eye-slash fa-lg"/>
                                                    <font-awesome-icon v-if="!passwordHidden" icon="fa-solid fa-eye fa-lg"/>
                                                </div>
                                            </template>
                                        </TextInput>
                                    </div>
                                    <button class="btn btn-danger" type="button" @click="profileStore.updateProfile()">
                                        Сохранить
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" v-if="profile.isSpecialist">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                    Ваши меню
                                    <button class="btn btn-success position-absolute end-3rem"
                                            @click="router.get(route('menu.create'))">
                                        <font-awesome-icon icon="fa-solid fa-plus"/>
                                    </button>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse show"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body d-flex gap-3 align-items-center">
                                    <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-if="menus.length > 0" v-for="menu in menus">
                                        <MenuCard v-model:special-id="selectSpecialId" :menu="menu"></MenuCard>
                                    </div>
                                    <p v-else>У Вас пока нет добавленных меню</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Modal v-if="selectSpecialId !== null" id="specialModal">
        <template #body>
            <MenuCard :in-modal="true" v-model:special-id="selectSpecialId"
                      :menu="menus[menus.map((o) => o.id).indexOf(selectSpecialId)]"></MenuCard>
        </template>
    </Modal>
</template>

<style lang="scss" scoped>
$accordion-button-active-icon:  url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23%46%46%46'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");

.accordion{
    --bs-accordion-btn-focus-box-shadow: 0 0 0 0.25rem rgba(227, 52, 47, 0.25);
    --bs-accordion-active-bg: rgb(227, 52, 47);
    --bs-accordion-active-color: white;
    --bs-accordion-btn-active-icon: #{$accordion-button-active-icon};

}

.end-3rem {
    right: 3rem;
}
</style>
