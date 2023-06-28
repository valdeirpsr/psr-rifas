<script setup lang="ts">
import { readonly, ref } from 'vue';
import VueCountdown from '@chenfengyuan/vue-countdown';
import { Order, OrderStatuses } from '../Types/Order';
import PsrBadge from './PsrBadge.vue';

defineProps<{
  orders: Order[]
}>();

const statuses = readonly<OrderStatuses>({
  expired: 'Expirado',
  paid: 'Pago',
  reserved: 'Reservado',
  unknown: 'Desconhecido'
});

const badgeClasses = readonly({
  expired: 'danger',
  paid: 'success',
  reserved: 'warning'
});
</script>

<template>
  <div class="mt-4 space-y-4">
    <div
      v-for="order in orders"
      :key="`number-${order.id}`"
      class="bg-white gap-2 p-4 rounded-lg shadow space-y-3 text-start hover:bg-gray-50"
    >
      <span :class="order.status">
        Situação: {{ statuses[order.status] ?? statuses.unknown }}
      </span>

      <div class="flex flex-wrap justify-between gap-2">
        <PsrBadge
          v-for="number,idx in order.numbers"
          :key="`number-${idx}`"
          :style="order.status"
          :type="badgeClasses[order.status] ?? 'default'"
        >{{ number }}</PsrBadge>
      </div>

      <p v-if="order.payment_expire_at" class="text-xs">
        <span>Expira em: </span>
        <VueCountdown
          :time="order.payment_expire_at"
          v-slot="{ totalHours, minutes, seconds }"
        >{{ totalHours }} horas, {{ minutes }} minutos e {{ seconds }} segundos</VueCountdown>
      </p>

      <p v-if="order.status === 'reserved'" class="text-center">
        <a
          href="#"
          class="bg-[#1e7dd4] uppercase px-3 py-2 rounded text-white text-sm w-full"
          data-test="pay"
        >Pagar</a>
      </p>
    </div>
  </div>
</template>

<style scoped>
span.badge.danger {
  text-decoration: line-through;
  text-decoration-thickness: 8px;
}
</style>
