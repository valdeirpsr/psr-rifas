/// <reference types="vitest" />

import path from 'node:path';
import { defineConfig } from 'vite';
import Vue from '@vitejs/plugin-vue';
import svgLoader from 'vite-svg-loader';

export default defineConfig({
  plugins: [
    Vue({
      template: {
        compilerOptions: {
          isCustomElement: (tag) => ['lottie-player'].includes(tag)
        }
      }
    }),
    svgLoader()
  ],
  test: {
    globals: true,
    environment: 'jsdom',
    setupFiles: ['./vitest.setup.ts'],
  },
  resolve: {
    alias: {
      '@Assets': path.resolve(__dirname, 'resources/assets'),
      '@Components': path.resolve(__dirname, 'resources/js/Components'),
      '@Composables': path.resolve(__dirname, 'resources/js/Composables'),
    }
  }
});
