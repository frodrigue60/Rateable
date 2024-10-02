import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                /* 'resources/sass/app.scss',
                'resources/js/app.js', */
                'resources/js/ajaxSearch.js',
                'resources/js/api_get_video.js',
                'resources/js/animes_infinite_scroll.js',
                'resources/js/favorites_infinite_scroll.js',
                'resources/js/themes_infinite_scroll.js',
                'resources/js/artists_infinite_scroll.js',
                'resources/css/app.css',
                'resources/css/modalSearch.css',
                'resources/css/userProfile.css',
                'resources/css/post.css',
                'resources/css/ranking.css',
                'resources/css/fivestars.css',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build',
        rollupOptions: {
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]',
            },
        },
       
    },
});
