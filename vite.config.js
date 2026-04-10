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
                        return "css/[name].min.css";
                    if (/\.(woff2?|ttf|eot|otf)$/.test(asset.name ?? ""))
                        return "fonts/[name][extname]";
                    if (
                        /\.(png|jpe?g|gif|svg|webp|ico)$/.test(asset.name ?? "")
                    )
                        return "images/[name][extname]";
                    return "assets/[name][extname]";
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
        alias: [
            {
                find: /^images\//,
                replacement: path.resolve(__dirname, "resources/images/") + "/",
            },
            {
                find: /^plugins\/fonts\//,
                replacement: path.resolve(__dirname, "resources/fonts/") + "/",
            },
        ],
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
        // ── Plugin copy static assets ──────────────────
        {
            name: "copy-static-assets",
            apply: "build",
            async writeBundle() {
                const copies = [
                    ["resources/images", "public/build/images"],
                    ["resources/fonts", "public/build/fonts"],
                    ["resources/json", "public/build/json"],
                    ["resources/lang", "public/build/lang"],
                ];
                await Promise.all(
                    copies.map(([src, dest]) =>
                        fs.pathExists(src).then((exists) => {
                            if (exists) return fs.copy(src, dest);
                        }),
                    ),
                );
                console.log("✅ Static assets copied to public/build/");
            },
        },
        {
            name: "fix-font-path",
            configureServer(server) {
                server.middlewares.use((req, res, next) => {
                    if (req.url.startsWith("/resources/scss/fonts/")) {
                        req.url = req.url.replace(
                            "/resources/scss/fonts/",
                            "/resources/fonts/",
                        );
                    }
                    next();
                });
            },
        },
    ],
});
