import 'bootstrap'
import '../sass/app.scss'
import {Modal, Popover} from "bootstrap";
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from "ziggyVue";
import { Ziggy } from "./ziggy";
import { createPinia } from 'pinia'
import VueLazyload from '@jambonn/vue-lazyload'
import Notifications, {useNotification} from '@kyvg/vue3-notification'
import PerfectScrollbar from 'vue3-perfect-scrollbar'
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css'
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css';

// font-awesome 6
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from '@fortawesome/free-solid-svg-icons'
library.add(fas);
import { fab } from '@fortawesome/free-brands-svg-icons';
library.add(fab);
import { far } from '@fortawesome/free-regular-svg-icons';
library.add(far);
import { dom } from "@fortawesome/fontawesome-svg-core";
dom.watch();

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

export const modals = Modal
export const popover = Popover

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    /*wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,*/
    forceTLS: true,
    //enabledTransports: ['ws', 'wss'],
});

export const sendNotify = (message, type = 'success') => {
    useNotification().notify({
        group: 'info',
        type: type,
        title: 'Оповещение ОбедыGO',
        text: message
    });
}

const VueLazyLoadOptions = {
    preLoad: 1.3,
    error: '/images/logo_obed_go.jpg',
    loading: '/images/logo_obed_go.jpg',
    attempt: 1
}

createInertiaApp({
    title: () => `${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(ZiggyVue, Ziggy)
            .use(plugin)
            .use(createPinia())
            .use(VueLazyload, VueLazyLoadOptions)
            .use(Notifications)
            .use(PerfectScrollbar)
            .component("v-select", vSelect)
            .mount(el);
    }
}).then(() => {
    InertiaProgress.init({
        color: '#4B5563',
        //showSpinner: true
    });
});
