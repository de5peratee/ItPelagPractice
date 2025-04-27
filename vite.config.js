import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: true,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/leaky-bucket/leaky-bucket.css',
                'resources/js/leaky-bucket/leaky-bucket.js',
                'resources/css/mainmenu.css',
                'resources/css/bloom/bloom.css',
                'resources/css/lossycount/lossycount.css',
            ],
            refresh: true,
        }),
    ],
});
