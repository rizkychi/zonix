import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";
import path from "path";
import fs from "fs-extra";

export default defineConfig({
    build: {
        outDir: "public/build",
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                assetFileNames: (asset) => {
                    if (asset.name?.endsWith(".css"))
                        return "css/[name]-[hash].min.css";
                    if (/\.(woff2?|ttf|eot|otf)$/.test(asset.name ?? ""))
                        return "fonts/[name]-[hash][extname]";
                    if (
                        /\.(png|jpe?g|gif|svg|webp|ico)$/.test(asset.name ?? "")
                    )
                        return "images/[name]-[hash][extname]";
                    return "assets/[name]-[hash][extname]";
                },
                entryFileNames: "js/[name]-[hash].js",
                chunkFileNames: "js/chunks/[name]-[hash].js",
            },
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                api: "modern-compiler",
                silenceDeprecations: [
                    "import",
                    "color-functions",
                    "global-builtin",
                    "if-function",
                ],
            },
        },
    },
    resolve: {
        alias: {
            "@fonts": path.resolve(__dirname, "resources/fonts"),
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/scss/bootstrap.scss",
                "resources/scss/icons.scss",
                "resources/scss/app.scss",
                "resources/scss/custom.scss",
                "resources/js/app.js",
                "resources/js/app-auth.js",
            ],
            refresh: [...refreshPaths, "resources/views/**"],
        }),
    ],
});
