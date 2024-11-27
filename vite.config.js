import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/pre-app.js",
                // Admin
                "resources/css/app-admin.css",
                "resources/js/app-admin.js",
                "resources/js/pre-app-admin.js",
                // custom
                "public/assets/js/dashboard.js",
            ],
            refresh: true,
        }),
    ],
});
