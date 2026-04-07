import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/particles.js' // Separate chunk for lazy loading
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    'three': ['three'], // Separate THREE.js into its own chunk
                }
            }
        },
        chunkSizeWarningLimit: 1000,
    },
});
