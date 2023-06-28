/// <reference types="vitest" />

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
});
