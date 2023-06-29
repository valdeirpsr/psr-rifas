/// <reference types="vitest" />

import path from 'node:path';
import { defineConfig } from 'vite';
import Vue from '@vitejs/plugin-vue';
import svgLoader from 'vite-svg-loader';

export default defineConfig({
  plugins: [
    Vue(),
    svgLoader()
  ],
  test: {
    globals: true,
    environment: 'jsdom',
  },
  resolve: {
    alias: {
      '@Assets': path.resolve(__dirname, 'resources/assets'),
      '@Components': path.resolve(__dirname, 'resources/js/Components'),
    }
  }
});
