import {defineStore} from "pinia"
import {reactive, watch} from "vue"
import axios from "axios"
import {sendNotify} from "@/app"
import {useCartStore} from "@/stores/cartStore"


export const useUserStore = defineStore('userStore', () => {

    const user = window.localStorage.getItem('user') === null ?
        reactive({id: 0, name: '', phone: '', addresses: [], isAuthorized: false}) :
        reactive(JSON.parse(window.localStorage.getItem('user')))

    const cartStore = useCartStore()

    watch(() => user, (newValue, oldValue) => {
        localStorage.setItem('user', JSON.stringify(user))
    },{ deep: true })

    watch(() => user.phone, (newValue, oldValue) => {
        if (newValue)
            user.phone.replace(/D/, '')
    })

    async function register(){
        await axios.post(route('register', user)).then(resp => {
            user.isAuthorized = true
            sendNotify('Вы успешно зарегистрировались!')
        }).catch(errors => {
            sendNotify('Произошла ошибка при регистрации', 'error')
        })
    }

    async function login(credentials){
        await axios.post(route('login', credentials)).then(resp => {
            saveUser(resp.data)
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = resp.data.token
            sendNotify('Вы успешно вошли в аккаунт!')
            return Promise.resolve(resp);
        }).catch(errors => {
            return Promise.reject(errors);
        })
    }

    async function logout(){
        await axios.post(route('logout')).then(resp => {
            user.isAuthorized = false
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = resp.data.token
            sendNotify('Вы успешно вышли из аккаунта!')
        }).catch(errors => {
            user.isAuthorized = false
        })
    }

    async function getUser(){

        await axios.get(route('user')).then(resp => {
            if(resp.data.user.user === null)
                user.isAuthorized = false
            else{
                saveUser(resp.data.user)
            }
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = resp.data.token
        })

    }

    function saveUser(data){
        user.id = data.user.id
        user.name = data.user.name
        user.phone = data.user.phone
        user.addresses = data.user.addresses
        user.isAuthorized = true
        cartStore.addToCartFromBD(data.cart)
    }

    async function sendCode(){
        await axios.post(route('resend.code'), {phone: user.phone}).then(resp => {
            sendNotify('Код отправлен на ваш номера телефона')
            return Promise.resolve(resp);
        }).catch(errors => {
            sendNotify('Произошла ошибка при отправке кода', 'error')
        })
    }

    function dataUser(){
        return user
    }

    return {user, register, login, logout, getUser, sendCode, dataUser}
})
