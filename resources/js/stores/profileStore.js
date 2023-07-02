import { defineStore } from "pinia"
import {ref, watch} from "vue"
import axios from "axios"
import {useUserStore} from "@/stores/userStore"
import {sendNotify} from "@/app"

export const useProfileStore = defineStore('profileStore', () => {
    const errors = ref({})
    const profile = ref({})
    const menus = ref([])
    const uploadImage = ref(File | null)

    const userStore = useUserStore()

    watch(() => userStore.user.addresses, (newValue, oldValue) => {
        if (newValue)
            profile.value.addresses = newValue
    })

    async function getProfile(){
        await axios.get(route('profile.get')).then(resp => {
            profile.value = resp.data.profile
            menus.value = resp.data.menus
        }).catch(errors => {})
    }

    async function updateProfile(){
        await axios.put(route('profile.update'), profile.value).then(resp => {
            errors.value = {}
            sendNotify('Профиль успешно обновлён!', 'success')
            return Promise.resolve(resp);
        }).catch(err => {
            if (err.response.status === 422) {
                errors.value = err.response.data.errors
                sendNotify('Произошла ошибка!', 'error')
            }
        })
    }

    async function updateProfileAvatar(){
        let formData = new FormData();
        formData.append('image', uploadImage.value);

        await axios.post(route('profile.avatar'), formData, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(resp => {
                sendNotify('Аватар успешно обновлён!', 'success')
                profile.value.image = resp.data.avatar
                uploadImage.value = null
                fetch(profile.value.image, { cache: 'reload', mode: 'no-cors' })
                document.getElementById('imgProfileImage').src = profile.value.image
                return Promise.resolve(resp);
            }).catch(err => {
                if (err.response.status === 422) {
                    sendNotify('Произошла ошибка!', 'error')
                    uploadImage.value = null
                }
            })
    }

    return { profile, menus, errors, uploadImage, getProfile, updateProfile, updateProfileAvatar }
})
