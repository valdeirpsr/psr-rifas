<script setup lang="ts">
  import { readonly } from 'vue';
  import PsrBadge from './PsrBadge.vue';
  import PsrCountdown from './PsrCountdown.vue';
  import { computed } from 'vue';

  const props = defineProps<{
    orders: OrderWithPayment[];
  }>();

  const statuses = readonly<OrderStatuses>({
    expired: 'Expirado',
    paid: 'Pago',
    reserved: 'Reservado',
    unknown: 'Desconhecido',
  });

  const badgeClasses = readonly({
    expired: 'danger',
    paid: 'success',
    reserved: 'warning',
  });

  const allOrders = computed(() =>
    props.orders.map((order) => ({
      ...order,
      paymentLink: order.payment ? route('payment.show', [order.payment.id]) : route('orders.show', [order.id]),
    }))
  );
</script>

<template>
  <div class="mt-4 space-y-4">
    <div
      v-for="order in allOrders"
      :key="`number-${order.id}`"
      class="bg-white gap-2 p-4 rounded-lg shadow space-y-3 text-start hover:bg-gray-50"
    >
      <span :class="order.status"> Situação: {{ statuses[order.status] ?? statuses.unknown }} </span>

      <div class="flex flex-wrap justify-start gap-2">
        <PsrBadge
          v-for="(number, idx) in order.numbers_reserved"
          :key="`number-${idx}`"
          :style="order.status"
          :type="badgeClasses[order.status] ?? 'default'"
        >
          {{ number }}
        </PsrBadge>
      </div>

      <p v-if="order.status === 'reserved' && order.expire_at" class="text-xs">
        <span>Expira em: </span>
        <PsrCountdown :time="order.expire_at" />
      </p>

      <p v-if="order.status === 'reserved'" class="text-center">
        <a
          class="bg-[#1e7dd4] uppercase px-3 py-2 rounded text-white text-sm w-full"
          data-test="pay"
          :href="order.paymentLink"
        >
          Pagar
        </a>
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
