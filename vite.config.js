export default defineConfig({
    plugins: [laravel({
        input: ['resources/css/app.css', 'resources/js/app.js'],
        refresh: true,
    })],
    server: {
        host: true,              // <- permite acesso externo
        strictPort: true,        // <- evita fallback de porta
        port: 5173,              // <- força a usar 5173
        hmr: {
            host: 'localhost',   // <- necessário para Laravel/Vite HMR
        },
    },
})
