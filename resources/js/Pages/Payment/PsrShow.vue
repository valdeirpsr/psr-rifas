<script setup lang="ts">
  import { computed, ref } from 'vue';
  import Approved from './Approved.vue';
  import Confirm from './Confirm.vue';
  import Expired from './Expired.vue';

  defineProps<{
    payment: {
      qr_code: string;
      qr_code_img: string;
      ticket_url: string;
      transaction_amount: number;
      date_of_expiration: string;
    };
  }>();

  const isApproved = ref(false);
  const isExpired = ref(false);

  const component = computed(() => {
    if (isApproved.value) return Approved;
    if (isExpired.value) return Expired;
    return Confirm;
  });

  function expired() {
    isApproved.value = false;
    isExpired.value = true;
  }
</script>

<template>
  <component :is="component" v-bind="isApproved ? {} : $props" @end="expired" />
</template>
