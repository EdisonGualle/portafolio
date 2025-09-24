import { defineConfig } from 'vite';
import { fileURLToPath, URL } from 'node:url';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  server: {
    host: true,
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://nginx',
        changeOrigin: true,
        // Enruta /api/* hacia nginx (Laravel) dentro de la red Docker
      },
    },
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
});
