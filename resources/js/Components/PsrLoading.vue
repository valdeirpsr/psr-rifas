<script setup lang="ts">
  import { onBeforeUnmount, onMounted } from 'vue';

  onMounted(() => {
    document.body.classList.add('overflow-hidden');
  });

  onBeforeUnmount(() => {
    document.body.classList.remove('overflow-hidden');
  });
</script>

<template>
  <div class="overlay" role="status" aria-live="assertive" aria-busy="true">
    <div class="badge">Aguarde...</div>
  </div>
</template>

<style scoped>
  .overlay {
    @apply flex
    items-center
    justify-center
    m-0
    w-screen
    h-screen
    border
    fixed
    top-0
    left-0
    bg-gray-800
    bg-opacity-95
    border-gray-700;
  }
  .badge {
    @apply px-8
    py-3
    font-medium
    leading-none
    text-center
    rounded-full
    animate-pulse
    bg-gray-100
    text-gray-800;
  }
</style>
