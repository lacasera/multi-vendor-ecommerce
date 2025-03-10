import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import autoprefixer from "autoprefixer";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/index.tsx'],
            refresh: true,
            postcss: [
                tailwindcss(),
                autoprefixer()
            ]
        }),
        react(),
    ],

    server: {
        host: '0.0.0.0',
        hmr: {
            host: '0.0.0.0'
        }
    }
});
