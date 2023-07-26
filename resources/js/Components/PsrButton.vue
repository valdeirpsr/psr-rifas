<script setup lang="ts">
  withDefaults(
    defineProps<{
      type?: 'primary' | 'link';
      disabled?: boolean;
    }>(),
    {
      type: 'primary',
      disabled: false,
    }
  );
</script>

<template>
  <button :class="type" :disabled="disabled">
    <slot />
  </button>
</template>

<style scoped>
  button {
    @apply uppercase
    px-3
    py-2
    rounded-md
    text-white
    text-sm
    transition-transform
    duration-200
    focus-visible:outline-none
    focus-visible:ring-2
    focus-visible:ring-offset-2
    disabled:bg-gray-400;
  }
  button:not([disabled]):active {
    @apply scale-95;
  }
  .primary {
    @apply bg-[#1e7dd4] border-transparent;
  }
  .primary:not([disabled]):hover {
    @apply bg-[hsl(209,75%,41%)];
  }
  .link {
    @apply cursor-pointer
    border
    text-gray-700
    hover:bg-gray-50
    focus:ring-offset-white
    focus:ring-gray-700;
  }
  .link:hover {
    @apply bg-[hsl(0,0%,95%)];
  }
</style>
