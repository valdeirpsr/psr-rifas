<template>
  <span class="badge" :class="type">
    <slot />
  </span>
</template>

<script setup lang="ts">
  defineProps<{
    type: 'default' | 'danger' | 'information' | 'success' | 'warning';
  }>();
</script>

<style scoped>
  .badge {
    @apply text-xs px-2 rounded-full uppercase font-semibold tracking-wide;
  }

  .default {
    @apply bg-gray-200 text-gray-700;
  }

  .danger {
    @apply bg-red-500 text-white;
  }

  .information {
    @apply bg-blue-500 text-white;
  }

  .success {
    @apply bg-green-500 text-black;
  }

  .warning {
    @apply bg-yellow-300 text-yellow-900;
  }
</style>
