import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import prefixer from "postcss-prefix-selector";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [`resources/views/**/*`],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        hmr: {
            host: "localhost",
        },
    },
    css: {
        postcss: {
            plugins: [
                prefixer({
                    prefix: "bs-",
                    includeFiles: ["bootstrap.min.css"],
                    skipGlobalSelectors: true,
                    transform(prefix, selector, prefixedSelector, filePath, rule) {
                        return selector.replace(/\.([\w-]+)/g, ".bs-$1");
                    },
                }),
            ],
        },
    },
});