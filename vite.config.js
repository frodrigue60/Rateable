import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/ajaxSearch.js',
                'resources/css/app.css',
                'resources/css/modalSearch.css'
            ],
            refresh: true,
        }),
    ],
});
