import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        // Enable CORS headers for asset loading across custom domains
        cors: true,
        // Bind to localhost to avoid IPv6 [::1] mismatch issues
        host: "localhost",
        port: 5173,
        // Allow request headers from your custom local domain
        allowedHosts: ["psareco.test.com"],
        hmr: {
            host: "localhost",
        },
    },
});
