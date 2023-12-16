import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            input: {
                tyresearch: 'resources/assets/js/tyre-search.js',
            },
        },
        outDir: 'public/', // This will output assets to public/
    },
});
