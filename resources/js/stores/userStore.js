import {defineStore} from "pinia"
import {reactive, watch} from "vue"
import axios from "axios";
import {modals, sendNotify} from "@/app";


export const useUserStore = defineStore('userStore', () => {

    const user = window.localStorage.getItem('user') === null ?
        reactive({name: '', phone: '', addresses: [], isAuthorized: false}) :
        reactive(JSON.parse(window.localStorage.getItem('user')))

    watch(user, (val) => {
        localStorage.setItem('user', JSON.stringify(user))
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
            user.name = resp.data.name
            user.phone = resp.data.phone
            user.addresses = resp.data.addresses
            user.isAuthorized = true
            sendNotify('Вы успешно вошли в аккаунт!')
            return Promise.resolve(resp);
        }).catch(errors => {
            return Promise.reject(errors);
        })
    }

    async function logout(){
        await axios.post(route('logout')).then(resp => {
            user.isAuthorized = false
            sendNotify('Вы успешно вышли из аккаунта!')
        }).catch(errors => {
            sendNotify('Произошла ошибка выходе из аккаунта', 'error')
        })
    }

    return {user, register, login, logout}
})
