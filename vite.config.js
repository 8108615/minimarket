import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/passkeys.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173, // Forzamos el puerto exacto que espera Laravel
        strictPort: true, // Si el 5173 está ocupado, avisa en lugar de cambiar al 5174
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
