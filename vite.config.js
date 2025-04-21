import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // permite acesso externo (do host)
        port: 5173,
        strictPort: true,
        hmr: {
            host: '192.168.1.14', // ← IMPORTANTE: IP da sua VM no VirtualBox
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});