import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: process.env.REPLIT_DEV_DOMAIN || 'localhost',
            protocol: process.env.REPLIT_DEV_DOMAIN ? 'wss' : 'ws',
            clientPort: process.env.REPLIT_DEV_DOMAIN ? 443 : 5173,
        },
    },
});
