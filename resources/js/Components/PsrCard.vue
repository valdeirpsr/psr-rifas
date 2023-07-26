<template>
  <div class="border border-gray-300 bg-white p-4 rounded-2xl space-y-4 sm:p-6">
    <p v-if="$slots.heading" class="uppercase text-center font-bold font-[Raleway] text-xs sm:text-sm">
      <slot name="heading" />
    </p>
    <slot />
  </div>
</template>
