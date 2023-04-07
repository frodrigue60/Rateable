import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                /* 'resources/sass/app.scss',
                'resources/js/app.js', */
                'resources/js/ajaxSearch.js',
                'resources/css/app.css',
                'resources/css/modalSearch.css',
                'resources/css/userProfile.css',
                'resources/css/post.css',
                'resources/css/ranking.css'
            ],
            refresh: true,
        }),
    ],
});
