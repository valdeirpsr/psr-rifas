<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { useLocaleCurrency, useLocaleDateLong, useLocaleTelephone } from '@Composables/Locale';
  import PsrBadge from '@Components/PsrBadge.vue';
  import PsrCard from '@Components/PsrCard.vue';
  import PsrCountdown from '@Components/PsrCountdown.vue';
  import PsrButton from '@Components/PsrButton.vue';
  import PsrDialog from '@Components/PsrDialog.vue';

  const props = defineProps<{
    order: Order & { transaction_amount: number };
    rifa: Partial<Rifa>;
  }>();

  const hasError = ref<boolean>(false);
  const showNumbers = ref(false);
  const expireAt = computed(() => (props.order.expire_at ? useLocaleDateLong(props.order.expire_at) : ''));
  const transactionAmount = computed(() => useLocaleCurrency(props.order.transaction_amount));
  const telephone = useLocaleTelephone(props.order.customer_telephone);

  const expired = ref(false);

  const isPaid = ref(false);
  const checkIsPaid = computed(() => isPaid.value || props.order.status === 'paid');

  const form = useForm({
    orderId: props.order.id,
  });

  function countdownEnd() {
    location = route('rifas.show', [props.rifa.slug]);
  }

  function confirmOrder() {
    form.post(route('payment.store'), {
      onError: (errors) => {
        hasError.value = !!(errors?.warning ?? false);
      },
    });
  }
</script>

<template>
  <div class="container max-w-[558px] space-y-4 pt-10 pb-32 px-4">
    <div class="mb-4" data-testid="heading">
      <h1 class="text-2xl font-extrabold font-[Raleway] leading-none">Resumo do Pedido</h1>
      <PsrBadge v-if="!checkIsPaid && order.expire_at" type="warning" data-testid="expire">
        Expira em: {{ expireAt }}
      </PsrBadge>
    </div>

    <PsrDialog v-if="hasError" :button-confirm="false" @dismiss="hasError = false">
      Não foi possível finalizar o pedido. Entre em contato com o administrador do site.
    </PsrDialog>

    <PsrCard>
      <div class="!mt-0 space-y-3" role="table">
        <div role="row">
          <p role="cell">
            <span role="cell">Rifa: </span>
            <b role="cell">{{ rifa.title }}</b>
          </p>
        </div>
        <hr />
        <div role="row">
          <p role="cell">
            <span role="cell">Nome: </span>
            <b role="cell">{{ order.customer_email }}</b>
          </p>
        </div>
        <hr />
        <div role="row">
          <p role="cell">
            <span role="cell">Telefone: </span>
            <b role="cell">{{ telephone }}</b>
          </p>
        </div>
        <hr />
        <div role="row">
          <p role="cell">
            <span role="cell">Email: </span>
            <b role="cell">{{ order.customer_email }}</b>
          </p>
        </div>
        <hr />
        <div role="row">
          <p role="cell">
            <span role="cell">Quantidade de números: </span>
            <b role="cell">{{ order.numbers_reserved.length }}</b>
          </p>
        </div>
        <hr />
        <div role="row">
          <p role="cell">
            <span role="cell">Valor total: </span>
            <b role="cell">{{ transactionAmount }}</b>
          </p>
        </div>
      </div>

      <div class="!mt-8 text-center">
        <PsrButton type="link" data-testid="button-toggle-numbers" @click="showNumbers = !showNumbers">
          {{ showNumbers ? 'Esconder ^' : 'Ver números v' }}
        </PsrButton>

        <Transition name="fade">
          <div v-if="showNumbers" class="flex flex-wrap gap-2 mt-8" data-testid="numbers">
            <PsrBadge v-for="number in order.numbers_reserved" :key="`n-${number}`" type="default">
              {{ number }}
            </PsrBadge>
          </div>
        </Transition>
      </div>
    </PsrCard>
  </div>

  <div class="fixed bottom-0 left-0 w-screen !m-0 p-4 space-y-2 bg-white text-center">
    <p class="text-xs text-center">
      <span>Pague em até: </span>
      <PsrCountdown
        v-if="!checkIsPaid"
        class="inline-block"
        data-testid="countdown"
        :time="order.expire_at"
        @end="countdownEnd"
      />
    </p>

    <PsrButton
      v-if="!checkIsPaid"
      class="w-full max-w-[558px]"
      data-testid="button-payment"
      :disabled="expired || !!form.processing"
      @click="confirmOrder"
    >
      <span v-if="form.processing" class="text-white">Aguarde</span>
      <span v-else class="text-white">{{ expired ? 'Expirado' : 'Pagar' }}</span>
    </PsrButton>
  </div>
</template>

<style scoped>
  b {
    @apply font-semibold;
  }

  .fixed {
    box-shadow:
      rgba(0, 0, 0, 0.25) 0px 54px 55px,
      rgba(0, 0, 0, 0.12) 0px -12px 30px,
      rgba(0, 0, 0, 0.12) 0px 4px 6px,
      rgba(0, 0, 0, 0.17) 0px 12px 13px,
      rgba(0, 0, 0, 0.09) 0px -3px 5px;
  }
</style>
