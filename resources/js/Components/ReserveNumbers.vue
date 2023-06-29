<template>
  <PsrCard>
    <template #heading>Selecione a quantidade de bilhetes</template>

    <div class="grid grid-cols-4 gap-2 w-full mx-auto sm:max-w-xl">
      <button class="button-qntd" data-testid="quantity-1" @click="increment(1)">+1</button>
      <button class="button-qntd" data-testid="quantity-10" @click="increment(10)">+10</button>
      <button class="button-qntd" data-testid="quantity-50" @click="increment(50)">+50</button>
      <button class="button-qntd" data-testid="quantity-100" @click="increment(100)">+100</button>
    </div>

    <div class="flex justify-between gap-2 mx-auto w-full sm:max-w-xl">
      <button class="button-qntd" data-testid="decrement" @click="decrement">-</button>
      <input
        type="number"
        class="border border-gray-300 rounded-md text-center flex-1 w-full"
        data-testid="quantity"
        v-model="quantity"
        :aria-label="`Escolha quantos números você quer comprar. Mínimo: ${buyMin}. Máximo: ${buyMax}`"
        @input="preventLetters"
      />
      <button class="button-qntd" data-testid="increment" @click="increment()">+</button>
    </div>

    <p class="w-full mx-auto text-end text-sm sm:max-w-xl" data-testid="price-total">Valor total: {{ priceTotal }}</p>

    <div class="w-full max-w-xl mx-auto">
      <PsrButton class="w-full" data-testid="confirm" @click="$emit('reserveNumbers', quantity)">
        Reservar Bilhetes
      </PsrButton>
    </div>
  </PsrCard>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { useLocaleCurrency } from '../Composables/Locale';

import PsrCard from './PsrCard.vue';
import PsrButton from './PsrButton.vue';

defineEmits<{
  (event: 'reserveNumbers', quantity:  number): void,
}>();

const props = defineProps<{
  price: number,
  buyMax: number,
  buyMin: number,
}>();

const quantity = ref(props.buyMin);

const priceTotal = computed(() =>
  useLocaleCurrency(props.price * quantity.value)
);

function increment(n: number = 1) {
  quantity.value = Math.min(quantity.value + n, props.buyMax);
}

function decrement() {
  quantity.value = Math.max(quantity.value - 1, props.buyMin);
}

function preventLetters() {
  quantity.value = parseFloat(quantity.value.toString().replace(/\D/g, '') || '1');
}
</script>

<style scoped>
.button-qntd {
  @apply border border-gray-300 rounded-xl px-4 py-2 text-sm font-semibold;
}
</style>
