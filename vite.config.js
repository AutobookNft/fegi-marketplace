import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

export default defineConfig({
    define: {
        global: 'globalThis',
    },
    server: {
        https: {
            key: fs.readFileSync('./.cert/localhost-key.pem'),
            cert: fs.readFileSync('./.cert/localhost.pem'),
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
