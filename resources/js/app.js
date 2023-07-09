import 'bootstrap'
import '../sass/app.scss'
import {Modal, Popover, Offcanvas} from "bootstrap"
import axios from 'axios'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

import {createApp, h, nextTick} from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from "ziggyVue"
import { Ziggy } from "./ziggy"
import BaseLayout from "@/Layouts/BaseLayout.vue"
import { createPinia } from 'pinia'
import VueLazyload from '@jambonn/vue-lazyload'
import Notifications, {useNotification} from '@kyvg/vue3-notification'
import PerfectScrollbar from 'vue3-perfect-scrollbar'
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css'
import vSelect from "vue-select"
import 'vue-select/dist/vue-select.css'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

/* ----------------- font-awesome 6 ---------------------- */
import { library } from "@fortawesome/fontawesome-svg-core"
import { fas } from '@fortawesome/free-solid-svg-icons'
library.add(fas)
import { fab } from '@fortawesome/free-brands-svg-icons'
library.add(fab)
import { far } from '@fortawesome/free-regular-svg-icons'
library.add(far)
import { dom } from "@fortawesome/fontawesome-svg-core"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
dom.watch()
/* ------------------------------------------------------- */

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

export const modals = Modal
export const popover = Popover
export const offcanvas = Offcanvas

window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'


window.Pusher = Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    /*wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,*/
    forceTLS: true,
    //enabledTransports: ['ws', 'wss'],
})

export const sendNotify = (message, type = 'success') => {
    useNotification().notify({
        group: 'info',
        type: type,
        title: 'Оповещение ОбедыGO',
        text: message
    })
}

export const myHasOwnProperty = Object.prototype.hasOwnProperty

const VueLazyLoadOptions = {
    preLoad: 1.3,
    error: '/images/logo_obed_go.jpg',
    loading: '/images/logo_obed_go.jpg',
    attempt: 1
}

export const deleteAllUnclosedElements = async () => {
    await nextTick(() => {
        let elements = document.querySelectorAll('[id*="popover"]')
        if (elements.length > 0)
            [...elements].forEach(element => element.remove())
        elements = document.querySelectorAll('[class*="modal-backdrop"]')
        if (elements.length > 0)
            [...elements].forEach(element => element.remove())
    })
}

createInertiaApp({
    title: () => `${appName}`,
    resolve: async (name) => {
        const page = (await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))).default
        page.layout = page.layout || BaseLayout
        return page
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(ZiggyVue, Ziggy)
            .use(plugin)
            .use(createPinia())
            .use(VueLazyload, VueLazyLoadOptions)
            .use(Notifications)
            .use(PerfectScrollbar)
            .use(VueSweetalert2)
            .component("v-select", vSelect)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el)
    }
}).then(() => {
    InertiaProgress.init({
        color: '#4B5563',
        //showSpinner: true
    })
})
