<script setup lang="ts">
  import { useClipboard } from '@vueuse/core';
  import PsrBadge from '@Components/PsrBadge.vue';
  import PsrButton from '@Components/PsrButton.vue';
  import PsrCard from '@Components/PsrCard.vue';
  import { IconSvgPix } from '@Assets/icons';
  import { watch } from 'vue';
  import { ref } from 'vue';
  import { useLocaleCurrency } from '@Composables/Locale';
  import PsrCountdown from '@Components/PsrCountdown.vue';

  const props = defineProps<{
    payment: {
      qr_code: string;
      qr_code_img: string;
      ticket_url: string;
      transaction_amount: number;
      date_of_expiration: string;
    };
  }>();

  defineEmits<{
    (event: 'end'): void;
  }>();

  const copying = ref(false);
  const transactionAmount = useLocaleCurrency(props.payment.transaction_amount);

  const clipboard = useClipboard({ source: props.payment.qr_code });

  watch(clipboard.copied, () => {
    copying.value = true;

    setTimeout(() => (copying.value = false), 1500);
  });
</script>

<template>
  <div class="container max-w-[1024px] space-y-4 pt-10 pb-32">
    <div class="mb-4" data-testid="heading">
      <h1 class="text-2xl font-extrabold font-[Raleway] leading-none">Confirmação</h1>
      <PsrBadge type="warning">
        Pague até:
        <PsrCountdown :time="payment.date_of_expiration" @end="$emit('end')" />
      </PsrBadge>
    </div>

    <PsrCard>
      <p class="space-y-4">
        <span class="block text-2xl font-extrabold font-[Raleway] leading-none">
          Pague {{ transactionAmount }} via Pix
        </span>
        <span class="block text-sm">Pagar com PIX é rápido e seguro! É só seguir estes passos:</span>
      </p>

      <div class="space-y-4 text-sm sm:grid sm:grid-cols-2 sm:gap-8">
        <img :src="`data:image/png;base64,${payment.qr_code_img}`" :alt="payment.qr_code" />

        <div>
          <ol class="space-y-4 steps">
            <li data-step="1"><b>Abra o aplicativo ou Internet Banking</b> para pagar</li>
            <li data-step="2">Na opção Pix, escolha <b>Ler QR Code</b></li>
            <li data-step="3">
              <b>Leia o QR Code</b> ou, se preferir, <p>copie o código para o Pix Copia e Cola</p>

              <PsrButton @click="clipboard.copy()">
                <span v-if="!copying" class="text-white"><IconSvgPix class="inline w-4 h-4 mr-2" /> Copiar Código</span>
                <span v-else class="text-white">✔ Código copiado</span>
              </PsrButton>
            </li>
            <li data-step="4">Revise as informações e <b>confirme o pagamento</b></li>
          </ol>
        </div>
      </div>

      <div class="text-center">
        <small class="block mt-10">
          Se estiver com dificuldade,
          <a class="underline text-blue-600" :href="payment.ticket_url">Acesse o Mercado Pago</a>
        </small>

        <p class="mt-4 space-x-4">
          <small>Pagamento processado por</small>
          <img src="@Assets/images/mercado-pago.png" alt="Mercado pago" class="!m-auto" />
        </p>
      </div>
    </PsrCard>
  </div>
</template>

<style scoped>
  .steps > li {
    @apply ml-10;
  }
  .steps > li::before {
    @apply bg-[#1e7dd4] h-8 inline-block rounded-full leading-8 -ml-10 mr-2 text-center font-semibold text-white w-8;
    content: attr(data-step);
  }
</style>
