<script setup lang="ts">
  import { computed, ref } from 'vue';
  import { useLocaleCurrency, useLocaleDateLong } from '@Composables/Locale';
  import { useDanger } from '@Composables/Snackbar';
  import PsrCard from '@Components/PsrCard.vue';
  import PsrRanking from '@Components/PsrRanking.vue';
  import ReserveNumbers from '@Components/ReserveNumbers.vue';
  import PsrBadge from '@Components/PsrBadge.vue';
  import FormReserveNumbers from '@Components/FormReserveNumbers.vue';
  import ButtonListOrders from '@Components/ButtonListOrders.vue';
  import axios from 'axios';

  const props = defineProps<{
    rifa: Rifa,
    ranking: Ranking[]
  }>();

  const displayFormReserveNumbers = ref(false);
  const orderQuantitySelected = ref(1);

  const expireAt = computed(() =>
    props.rifa.expired_at ? useLocaleDateLong(props.rifa.expired_at) : ''
  );

  const price = computed(() =>
    props.rifa.price ? useLocaleCurrency(props.rifa.price) : ''
  );

  function onReserveNumbers(): void {
    displayFormReserveNumbers.value = true;
  }

  async function onConfirmFormReserveNumbers(form: Partial<FormReserveNumbers>): Promise<void> {
    form.quantity = orderQuantitySelected.value;
    form.rifa = props.rifa.id;

    const { data } = await axios.post(route('orders.store'), form)
      .catch((err) => {
        useDanger(err?.response?.data?.message || err?.message || err);

        return { data: null };
      });

    if (data.redirect) return location.href = data.redirect;
  }
</script>

<template>
  <div v-if="rifa" class="container max-w-[1024px] space-y-4 pt-10 pb-32">
    <div class="mb-4">
      <h1 class="text-2xl font-extrabold font-[Raleway] leading-none">{{ rifa.title }}</h1>
      <PsrBadge v-if="rifa.expired_at" type="warning">Compre até: {{ expireAt }}</PsrBadge>
    </div>

    <picture>
      <source media="(max-width: 425px)" :srcset="`${rifa.thumbnail}?width=425`" />
      <source media="(max-width: 768px)" :srcset="`${rifa.thumbnail}?width=768`" />
      <source media="(max-width: 1024px)" :srcset="`${rifa.thumbnail}?width=1024`" />
      <img :src="rifa.thumbnail" alt="Imagem" />
    </picture>

    <PsrCard class="text-center font-semibold">Por apenas {{ price }}</PsrCard>

    <div class="space-y-4 sm:space-y-0 sm:flex sm:gap-4">
      <PsrCard class="md:flex-auto lg:flex-1">
        <template #heading>Descrição</template>
        <!-- eslint-disable-next-line -->
        <p v-html="rifa.description" />
      </PsrCard>

      <PsrRanking class="sm:flex-auto md:flex-initial" :users="ranking" />
    </div>

    <ReserveNumbers
      v-model:quantity="orderQuantitySelected"
      :price="rifa.price"
      :buy-max="rifa.buy_max"
      :buy-min="rifa.buy_min"
      @reserve-numbers="onReserveNumbers"
    />

    <div class="flex flex-wrap gap-4 justify-between text-center">
      <PsrCard class="md:flex-auto">
        <template #heading>Meio de Pagamento</template>
        <div><span class="text-[#51d2bb] font-bold text-xl">❖</span> PIX</div>
      </PsrCard>

      <PsrCard class="md:flex-auto">
        <template #heading>Sorteio</template>
        <div>🍀 {{ rifa.raffle }}</div>
      </PsrCard>

      <PsrCard class="md:flex-auto">
        <template #heading>Consulte</template>
        <div><ButtonListOrders :rifa-slug="rifa.slug" /></div>
      </PsrCard>
    </div>

    <Teleport v-if="displayFormReserveNumbers" to="body">
      <FormReserveNumbers @confirm="onConfirmFormReserveNumbers" @dismiss="displayFormReserveNumbers = false" />
    </Teleport>
  </div>
</template>
