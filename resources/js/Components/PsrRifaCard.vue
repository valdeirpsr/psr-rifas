<script setup lang="ts">
  import { useLocaleCurrency, useLocaleDateLong } from '@Composables/Locale';
  import { computed } from 'vue';

  const props = defineProps<{
    rifa: Pick<Rifa, 'expired_at' | 'price' | 'thumbnail' | 'slug' | 'title'>;
  }>();

  const expiredAt = computed(() => (props.rifa.expired_at ? useLocaleDateLong(props.rifa.expired_at) : ''));
  const price = computed(() => (props.rifa.price ? useLocaleCurrency(props.rifa.price) : ''));
</script>

<template>
  <a :href="route('rifas.show', [rifa.slug])" class="block h-48 rounded overflow-hidden">
    <img alt="ecommerce" class="object-cover object-center w-full h-full block" :src="rifa.thumbnail" />
  </a>
  <div class="mt-4">
    <p v-if="rifa.expired_at" class="text-gray-500 text-xs mb-1">Expira em: {{ expiredAt }}</p>
    <h1 class="text-gray-900 text-lg font-bold">
      <a :href="route('rifas.show', [rifa.slug])">{{ rifa.title }}</a>
    </h1>
    <p class="mt-1">{{ price }}</p>
  </div>
</template>
