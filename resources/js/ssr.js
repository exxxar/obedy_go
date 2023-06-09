import '../sass/app.scss'

import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import createServer from '@inertiajs/vue3/server';
import { ZiggyVue } from "ziggyVue";
import { Ziggy } from "./ziggy";

const appName = 'Обеды Go';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: () => `${appName}`,
        resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, Ziggy);
        },
    })
);
