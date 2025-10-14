import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import { fileURLToPath, URL } from "node:url";
import vue from "@vitejs/plugin-vue";

// https://vite.dev/config/
export default defineConfig({
  base: "/",
  plugins: [
    vue(),
    tailwindcss(),
    laravel({
      input: ["resources/css/app.css", "resources/js/main.js"],
      refresh: true
    })
  ],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./resources/js/", import.meta.url))
    }
  }
});
