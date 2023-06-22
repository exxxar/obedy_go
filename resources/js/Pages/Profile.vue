<script setup>
import {nextTick, onMounted, ref, watch} from "vue"
import PageTitle from "@/Components/Layout/PageTitle.vue"
import Modal from '@/Components/Basic/Modal.vue'
import MenuCard from "@/Components/Menu/MenuCard.vue"
import TextInput from "@/Components/Basic/TextInput.vue"
import AddressesSelect from "@/Components/Basic/AddressesSelect.vue"
import { useProfileStore } from "@/stores/profileStore"
import { storeToRefs } from "pinia"
import { router } from '@inertiajs/vue3'
import {sendNotify} from "@/app";

const profileStore = useProfileStore()
const { profile, errors, menus, uploadImage } = storeToRefs(profileStore)

const passwordHidden = ref(true)
const selectSpecialId = ref(null)

onMounted(() => {
    profileStore.getProfile()
})

watch(() => profile.value.description, (newValue, oldValue) => {
    if (newValue)
        nextTick(() => {
            let element = document.getElementById('profile-description')
            element.style.height = "1px"
            element.style.height = (16 + element.scrollHeight) + "px"
        })
})

const changePasswordVisibility = () => {
    passwordHidden.value = !passwordHidden.value;
    document.getElementById('profile-password').setAttribute('type', passwordHidden.value ? 'password' : 'text')
}

const onFileChange = async (e) => {
    let files = e.target.files;
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
        e.target.value = ''
    }
}

const openProfileImage = (e) => {
    let el = e.target
    if (el.localName === 'button')
        el.previousElementSibling.click()
    else if(el.localName === 'svg' || el.localName === 'path')
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
                                <button class="accordion-button " type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">
                                    Данные профиля
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body d-flex flex-column gap-3 align-items-center">
                                    <form class="w-100 d-flex flex-column gap-3">
                                        <div class="input-group">
                                            <span class="input-group-text justify-content-center w-40px" id="basic-addon1">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <TextInput :errors="errors.hasOwnProperty('name') ? errors.name : []"
                                                       placeholder="Ваше Ф.И.О"
                                                       v-model="profile.name"
                                                       input-class="w-auto" aria-describedby="basic-addon1"></TextInput>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text justify-content-center w-40px" id="basic-addon2">
                                                <i class="fa-solid fa-phone"></i>
                                            </span>
                                            <TextInput :errors="errors.hasOwnProperty('phone') ? errors.phone : []"
                                                       placeholder="Номер телефона"
                                                       v-model="profile.phone" mask="+7 (###) ###-##-##"
                                                       input-class="w-auto" aria-describedby="basic-addon2"></TextInput>
                                        </div>
                                        <div class="input-group">
                                             <span class="input-group-text justify-content-center w-40px" id="basic-addon3">
                                                <i class="fa-solid fa-location-dot"></i>
                                            </span>
                                            <AddressesSelect v-model:formAddress="profile.address"
                                                             :errors="errors.hasOwnProperty('addresses') ? errors.addresses : []"
                                                             input-class="w-auto z-4-important" aria-describedby="basic-addon3"/>
                                            <div v-if="'addresses' in errors && errors.addresses.length > 0"
                                                 class="invalid-feedback" v-for="error in errors.addresses">{{ error }}
                                            </div>
                                        </div>
                                        <div class="input-group" v-if="profile.isSpecialist">
                                            <span class="input-group-text justify-content-center w-40px" id="basic-addon4">
                                                <i class="fa-solid fa-comment"></i>
                                            </span>
                                            <textarea name="description" v-model="profile.description" class="form-control px-4 py-3 overflow-hidden"
                                                      placeholder="Описание"
                                                      :class="[
                                                          errors.hasOwnProperty('description') && errors.description.length > 0 ? 'is-invalid' : '',
                                                          (!errors.hasOwnProperty('description') || errors.description.length === 0) && profile.description !== '' ? 'is-valid' : ''
                                                      ]"
                                                      style="resize:none"
                                                      aria-describedby="basic-addon4"
                                                      id="profile-description">
                                            </textarea>
                                            <div v-if="errors.hasOwnProperty('description') && errors.description.length > 0"
                                                 class="invalid-feedback" v-for="error in errors.description">{{ error }}
                                            </div>
                                        </div>
                                        <div class="input-group password-control">
                                            <span class="input-group-text justify-content-center w-40px" id="basic-addon5">
                                                <i class="fa-solid fa-lock"></i>
                                            </span>
                                            <TextInput :errors="errors.hasOwnProperty('password') ? errors.password : []"
                                                       placeholder="Пароль"
                                                       v-model="profile.password"
                                                       :type="passwordHidden ? 'password' : 'text'"
                                                       input-id="profile-password"
                                                       :error="false"
                                                       input-class="w-auto" aria-describedby="basic-addon5"></TextInput>
                                            <div class="input-group-text cursor-pointer" @click="changePasswordVisibility()">
                                                <font-awesome-icon v-if="passwordHidden" icon="fa-solid fa-eye-slash fa-lg" />
                                                <font-awesome-icon v-if="!passwordHidden" icon="fa-solid fa-eye fa-lg" />
                                            </div>
                                            <div v-if="errors.hasOwnProperty('password')"
                                                 class="invalid-feedback" v-for="err in errors.password">{{ err }}</div>
                                        </div>
                                    </form>
                                    <button class="btn btn-danger" type="button" @click="profileStore.updateProfile()">
                                        Сохранить
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" v-if="profile.isSpecialist">
                            <h2 class="accordion-header">
                                <button class="accordion-button " type="button" data-bs-toggle="collapse"
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
                                        <MenuCard v-model="selectSpecialId" :menu="menu"></MenuCard>
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
            <MenuCard :in-modal="true" v-model="selectSpecialId" :menu="menus[menus.map((o) => o.id).indexOf(selectSpecialId)]"></MenuCard>
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
.w-40px {
    width: 40px;
}
.end-3rem {
    right: 3rem;
}
</style>
