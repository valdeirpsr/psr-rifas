<script setup lang="ts">
  import { computed, onMounted, ref } from 'vue';
  import { useTimeoutFn } from '@vueuse/core';
  import axios from 'axios';
  import Approved from './PsrApproved.vue';
  import Confirm from './PsrConfirm.vue';
  import Expired from './PsrExpired.vue';

  const props = defineProps<{
    payment: {
      id: string;
      qr_code: string;
      qr_code_img: string;
      ticket_url: string;
      transaction_amount: number;
      date_of_expiration: string;
      date_approved: string;
    };
  }>();

  onMounted(checkPaymentStatus);

  const isApproved = ref(false);
  const isExpired = ref(false);

  const component = computed(() => {
    if (isApproved.value || props.payment.date_approved) return Approved;
    if (isExpired.value) return Expired;
    return Confirm;
  });

  function expired() {
    isApproved.value = false;
    isExpired.value = true;
  }

  async function checkPaymentStatus() {
    const { data } = await axios.get(route('payment.check', [props.payment.id]));
    isApproved.value = data.data.is_approved;

    if (!isApproved.value && !isExpired.value) useTimeoutFn(checkPaymentStatus, 5000);
  }
</script>

<template>
  <component :is="component" v-bind="isApproved ? {} : $props" @end="expired" />
</template>
