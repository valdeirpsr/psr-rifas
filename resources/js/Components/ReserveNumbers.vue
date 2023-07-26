<script setup lang="ts">
  import { computed, watch } from 'vue';
  import { useCounter } from '@vueuse/core';
  import { useLocaleCurrency } from '../Composables/Locale';

  import PsrCard from './PsrCard.vue';
  import PsrButton from './PsrButton.vue';

  const emits = defineEmits<{
    (event: 'reserveNumbers', quantity: number): void;
    (event: 'update:quantity', value: number): void;
  }>();

  const props = defineProps<{
    price: number;
    buyMax: number;
    buyMin: number;
  }>();

  const { count, inc, dec } = useCounter(props.buyMin, { max: props.buyMax, min: props.buyMin });

  const priceTotal = computed(() => useLocaleCurrency(props.price * count.value));

  watch(count, (newValue) => {
    if (!newValue) count.value = 1;
    emits('update:quantity', count.value);
  });
</script>

<template>
  <PsrCard>
    <template #heading>Selecione a quantidade de bilhetes</template>

    <div class="grid grid-cols-4 gap-2 w-full mx-auto sm:max-w-xl">
      <button class="button-qntd" data-testid="quantity-1" @click="inc(1)">+1</button>
      <button class="button-qntd" data-testid="quantity-10" @click="inc(10)">+10</button>
      <button class="button-qntd" data-testid="quantity-50" @click="inc(50)">+50</button>
      <button class="button-qntd" data-testid="quantity-100" @click="inc(100)">+100</button>
    </div>

    <div class="flex justify-between gap-2 mx-auto w-full sm:max-w-xl">
      <button class="button-qntd" data-testid="decrement" @click="dec(1)">-</button>
      <input
        v-model="count"
        type="number"
        class="border border-gray-300 rounded-md text-center flex-1 w-full"
        data-testid="quantity"
        :aria-label="`Escolha quantos números você quer comprar. Mínimo: ${buyMin}. Máximo: ${buyMax}`"
      />
      <button class="button-qntd" data-testid="increment" @click="inc()">+</button>
    </div>

    <p class="w-full mx-auto text-end text-sm sm:max-w-xl" data-testid="price-total">Valor total: {{ priceTotal }}</p>

    <div class="w-full max-w-xl mx-auto">
      <PsrButton class="w-full" data-testid="confirm" @click="$emit('reserveNumbers', count)">
        Reservar Bilhetes
      </PsrButton>
    </div>
  </PsrCard>
</template>

<style scoped>
  .button-qntd {
    @apply border border-gray-300 rounded-xl px-4 py-2 text-sm font-semibold;
  }
</style>
