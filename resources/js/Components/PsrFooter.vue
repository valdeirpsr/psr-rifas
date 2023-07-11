<script setup lang="ts">
  import PsrLogo from './PsrLogo.vue';
  import { IconSvgFacebook, IconSvgInstagram, IconSvgTwitter } from '@Assets/icons';
</script>

<template>
  <footer class="bg-white drop-shadow-2xl">
    <div
      class="container max-w-[1024px] flex flex-wrap p-5 flex-col sm:flex-row sm:flex-nowrap sm:gap-4 sm:justify-between"
    >
      <a
        href="https://github.com/valdeirpsr"
        class="flex order-first m-auto lg:w-1/5 font-medium items-center text-gray-900 lg:items-center lg:justify-center mb-4 md:mb-0 flex-1 flex-grow-0 sm:m-0"
      >
        <PsrLogo />
      </a>

      <p class="text-sm text-center leading-10 sm:border-l-2 sm:pl-4">
        © 2022 PSRifas —
        <a href="https://github.com/valdeirpsr" class="text-gray-600 ml-1">@valdeirpsr</a>
      </p>

      <span class="social inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start items-center space-x-3">
        <a href="https://valdeir.dev"><IconSvgFacebook /></a>
        <a href="https://twitter.com/valdeirpsr"><IconSvgTwitter /></a>
        <a href="https://instagram.com/valdeirpsr"><IconSvgInstagram /></a>
      </span>
    </div>
  </footer>
</template>

<style scoped>
  .social a {
    @apply text-gray-500 hover:text-gray-900;
  }
</style>
