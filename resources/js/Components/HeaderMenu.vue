<script setup lang="ts">
  import PsrLogo from './PsrLogo.vue';

  defineEmits<{
    (event: 'update:active', value: boolean): void;
  }>();

  defineProps<{
    active: boolean;
  }>();
</script>

<template>
  <div class="wrapper" :class="{ '-translate-x-full': !active }">
    <nav class="flex flex-col order-3 w-full md:w-max md:divide-x-2 md:flex-row">
      <a :href="route('home')" class="menu-item">Página Inicial</a>
      <a :href="route('rifas.finalizadas')" class="menu-item">Rifas Finalizadas</a>
      <a :href="route('testimonials.list')" class="menu-item">Depoimentos</a>
      <a :href="route('contact')" class="menu-item">Contato</a>

      <button
        type="button"
        class="p-4 border-b-2 border-b-slate-50 text-start w-full md:p-0 md:px-4 md:border-0 md:w-auto md:hidden md:invisible"
        @click="$emit('update:active', false)"
      >
        Voltar
      </button>
    </nav>

    <div
      class="aspect-video order-1 bg-white flex flex-wrap drop-shadow place-content-center w-full md:hidden md:invisible"
      role="button"
      aria-label="Voltar para a página anterior"
      @click="$emit('update:active', false)"
    >
      <PsrLogo />
    </div>

    <p class="text-gray-400 text-xs my-4 order-2 md:hidden md:invisible">Menu</p>
  </div>
</template>

<style scoped>
  .wrapper {
    @apply flex
  items-center
  bg-white
  absolute
  h-screen
  inset-0
  transition-transform
  duration-300
  ease-in-out
  flex-col
  md:flex-row
  md:h-auto
  md:static
  md:gap-3
  md:translate-x-0;
  }
  .menu-item {
    @apply p-4 border-b-2 border-b-slate-50 w-full md:p-0 md:px-4 md:border-0 md:w-auto;
  }
</style>
