import { fileURLToPath, URL } from "node:url"

import { defineConfig } from "vite"
import vue from "@vitejs/plugin-vue"


export default defineConfig({
  plugins: [
    vue(),
  //  vueDevTools(),
  ],

  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },

  server: {
    host: true,          // важно для Docker
    port: 5173,
    strictPort: true,

    // hot-reload в docker / windows / wsl
    watch: {
      usePolling: true,
      interval: 300,
    },

    // proxy → Laravel API
    proxy: {
      "/api": {
        target: "http://localhost:8000",
        changeOrigin: true,
      },
      "/sanctum": {
        target: "http://localhost:8000",
        changeOrigin: true,
      },
    },
  },
})
