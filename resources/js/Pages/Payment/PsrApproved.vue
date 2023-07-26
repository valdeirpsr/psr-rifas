<script setup lang="ts">
  import { Link as PsrLink } from '@inertiajs/vue3';
  import '@lottiefiles/lottie-player';
</script>

<template>
  <div class="flex-col flex fixed h-screen inset-0 items-stretch">
    <div class="bg-[#029ef2] flex-auto flex-shrink-0 h-[70vh] pb-32">
      <lottie-player class="m-auto" autoplay loop mode="normal" src="/anim8_.json" />

      <p class="text-center text-2xl font-bold text-white animate-bounce">Pagamento Aprovado</p>
    </div>

    <div class="flex flex-initial h-full items-center justify-center text-center">
      <PsrLink
        class="bg-[#1e7dd4] border-transparent uppercase px-3 py-2 rounded-md text-sm text-white"
        title="Ir para a página inicial"
        :href="route('home')"
      >
        Página Inicial
      </PsrLink>
    </div>
  </div>
</template>
