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
            ],
            refresh: true,
        }),
    ],
});
