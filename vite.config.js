import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        {
            name: 'font-display-swap',
            transform(code, id) {
                if (code.includes('font-display: block') || code.includes('font-display:block')) {
                    return {
                        code: code.replaceAll('font-display: block', 'font-display: swap').replaceAll('font-display:block', 'font-display:swap'),
                        map: null,
                    };
                }
            },
        },
    ],
});
