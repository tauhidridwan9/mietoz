import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    base: '/build/',  // Base URL untuk asset yang dihasilkan
    build: {
        outDir: 'public/build',  // Path output yang benar
        manifest: true,          // Generate manifest untuk assets
        rollupOptions: {
            input: ['resources/js/app.js', 'resources/css/app.css'], // Input file JS dan CSS
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'], // Input file CSS dan JS
            refresh: true,  // Auto-refresh saat file diubah
        }),
    ],
});
