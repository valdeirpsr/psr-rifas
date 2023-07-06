import path from 'node:path';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import svgLoder from 'vite-svg-loader';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
        vue({
            template: {
                compilerOptions: {
                    isCustomElement: (tag) => ['lottie-player'].includes(tag)
                }
            }
        }),
        svgLoder()
    ],
    resolve: {
        alias: {
            "@Assets": path.resolve(__dirname, 'resources/assets'),
            "@Components": path.resolve(__dirname, 'resources/js/Components'),
            "@Composables": path.resolve(__dirname, 'resources/js/Composables'),
        }
    }
});
