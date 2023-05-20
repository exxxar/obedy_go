import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { run } from 'vite-plugin-run';
import path from 'path';

export default defineConfig({
    build: {
        chunkSizeWarningLimit: 5000,
    },
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            ssr: ['resources/js/ssr.js'],
            ssrOutputDirectory: 'bootstrap/ssr',
            refresh: true,
        }),
        run([
            {
                name: 'ziggy',
                run: ['php', 'artisan', 'ziggy:generate'],
                condition: (file) => file.includes('/routes/') && file.endsWith('.php')
            }
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            ziggyVue: path.resolve('vendor/tightenco/ziggy/dist/vue.es.js'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap')
        }
    }
});
