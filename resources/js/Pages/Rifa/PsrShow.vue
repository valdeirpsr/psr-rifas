<script setup lang="ts">
  import { computed, ref } from 'vue';
  import { useLocaleCurrency, useLocaleDateLong } from '@Composables/Locale';
  import { useSorted } from '@vueuse/core';
  import PsrCard from '@Components/PsrCard.vue';
  import PsrRanking from '@Components/PsrRanking.vue';
  import ReserveNumbers from '@Components/ReserveNumbers.vue';
  import PsrBadge from '@Components/PsrBadge.vue';
  import FormReserveNumbers from '@Components/FormReserveNumbers.vue';
  import ButtonListOrders from '@Components/ButtonListOrders.vue';
  import PsrHeader from '@Components/PsrHeader.vue';
  import PsrFooter from '@Components/PsrFooter.vue';

  const props = defineProps<{
    rifa: Rifa,
    ranking: Ranking[],
    winners: Winner[]
  }>();

  const displayFormReserveNumbers = ref(false);
  const orderQuantitySelected = ref(1);

  const expireAt = computed(() =>
    props.rifa.expired_at ? useLocaleDateLong(props.rifa.expired_at) : ''
  );

  const price = computed(() =>
    props.rifa.price ? useLocaleCurrency(props.rifa.price) : ''
  );

  const isFinished = computed(() =>
    !!props.winners.length
    || props.rifa.status === 'finished'
    || (props.rifa.expired_at && new Date(props.rifa.expired_at) < new Date())
  );

  const listWinners = useSorted(props.winners, (prev, cur) => prev.position > cur.position ? 1 : -1);

  const winnerVideo = computed(() =>
    listWinners.value.filter((winner) => winner.video)
  );

  function onReserveNumbers(): void {
    displayFormReserveNumbers.value = true;
  }
</script>

<template>
  <PsrHeader />

  <div v-if="rifa" class="container max-w-[1024px] space-y-4 pt-10 pb-32 px-4">
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
      <PsrCard class="md:flex-auto">
        <template #heading>Descrição</template>
        <!-- eslint-disable-next-line -->
        <p v-html="rifa.description" />
      </PsrCard>

      <PsrRanking v-if="ranking.length" class="sm:flex-auto md:flex-initial" :users="ranking" />
    </div>

    <ReserveNumbers
      v-if="!isFinished"
      v-model:quantity="orderQuantitySelected"
      :price="rifa.price"
      :buy-max="rifa.buy_max"
      :buy-min="rifa.buy_min"
      @reserve-numbers="onReserveNumbers"
    />

    <PsrCard v-if="listWinners.length">
      <template #heading>Ganhadores</template>

      <template #default>
        <ol class="text-center" data-testid="winners-list">
          <li v-for="winner in listWinners" :key="`winner-${winner.position}`">
            {{ winner.position }}º Prêmio - {{ winner.customer_fullname }}
          </li>
        </ol>
      </template>
    </PsrCard>

    <PsrCard v-if="winnerVideo.length">
      <template #heading>Vídeo do Ganhador</template>

      <template #default>
        <video class="max-h-[570px] m-auto" controls controlslist="nodownload noremoteplayback" :src="(winnerVideo[0].video as string)" />
      </template>
    </PsrCard>

    <div class="flex flex-wrap gap-4 justify-between text-center">
      <PsrCard class="md:flex-auto">
        <template #heading>Meio de Pagamento</template>
        <div><span class="text-[#51d2bb] font-bold text-xl">❖</span> PIX</div>
      </PsrCard>

      <PsrCard class="md:flex-auto">
        <template #heading>Sorteio</template>
        <div>🍀 {{ rifa.raffle }}</div>
      </PsrCard>

      <PsrCard class="flex-1 md:flex-auto">
        <template #heading>Consulte</template>
        <div><ButtonListOrders :rifa-slug="rifa.slug" /></div>
      </PsrCard>
    </div>

    <Teleport v-if="displayFormReserveNumbers" to="body">
      <FormReserveNumbers
        :rifa="rifa.id"
        :quantity="orderQuantitySelected"
        @dismiss="displayFormReserveNumbers = false"
      />
    </Teleport>
  </div>

  <PsrFooter />
</template>
