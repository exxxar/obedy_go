import './bootstrap';
import '../sass/app.scss'
import * as bootstrap from 'bootstrap';
import $ from "jquery";

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import VueLazyload from '@jambonn/vue-lazyload'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

window.$ = $;

createInertiaApp({
    title: () => `${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .use(ZiggyVue, Ziggy)
            .use(
                VueLazyload, {
                    preLoad: 1.3,
                    error: '/images/logo_obed_go.jpg',
                    loading: '/images/logo_obed_go.jpg',
                    attempt: 1
                }
            )
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
