import { defineConfig } from 'vite';

export default defineConfig({
    base: '/build/',  // Base URL untuk asset yang dihasilkan
    build: {
        outDir: 'public/build',  // Path output yang benar
        manifest: true,
        rollupOptions: {
            input: 'resources/js/app.js',  // Entry point untuk build
        }
    }
});
