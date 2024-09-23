<script setup lang="ts">
  import PsrLogo from './PsrLogo.vue';
  import { IconSvgFacebook, IconSvgInstagram, IconSvgTwitter } from '@Assets/icons';

  const year = new Date().getFullYear();
  const facebook = import.meta.env.VITE_FACEBOOK;
  const twitter = import.meta.env.VITE_TWITTER;
  const instagram = import.meta.env.VITE_INSTAGRAM;
</script>

<template>
  <footer class="bg-white drop-shadow-2xl">
    <div
      class="container max-w-[1024px] flex flex-wrap p-5 flex-col sm:flex-row sm:flex-nowrap sm:gap-4 sm:justify-between"
    >
      <a
        :href="route('home')"
        class="flex order-first m-auto lg:w-1/5 font-medium items-center text-gray-900 lg:items-center lg:justify-center mb-4 md:mb-0 flex-1 flex-grow-0 sm:m-0"
      >
        <PsrLogo />
      </a>

      <p class="text-sm text-center leading-10 sm:border-l-2 sm:pl-4">
        © <span v-text="year"></span> PSRifas —
        <a href="https://github.com/valdeirpsr" class="text-gray-600 ml-1">@valdeirpsr</a>
      </p>

      <span class="social inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start items-center space-x-3">
        <a :href="facebook" v-if="facebook"><IconSvgFacebook /></a>
        <a :href="twitter" v-if="twitter"><IconSvgTwitter /></a>
        <a :href="instagram" v-if="instagram"><IconSvgInstagram /></a>
      </span>
    </div>
  </footer>
</template>

<style scoped>
  .social a {
    @apply text-gray-500 hover:text-gray-900;
  }
</style>
