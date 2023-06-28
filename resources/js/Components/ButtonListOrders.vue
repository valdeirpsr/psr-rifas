<script setup lang="ts">
  /**
   * @TODO: Realizar integração com back-end
   */

  import { computed, ref } from 'vue';
  import { useTimeoutFn } from '@vueuse/core';
  import { vMaska } from 'maska';
  import { useTelephone } from '../Composables/Validators';
  import { IconSvgWhatsApp } from '@Assets/icons';
  import PsrButton from './PsrButton.vue';
  import PsrDialog from './PsrDialog.vue';
  import ListOrders from './ListOrders.vue';
  import PsrLoading from './PsrLoading.vue';
  import { Order } from '../Types/Order';

  const opened = ref(false);
  const telephone = ref('');
  const orders = ref<Order[]>([]);
  const isLoading = ref(false);

  const isValidTelephone = computed(() => useTelephone(telephone.value));

  /**
   * @TODO: Integrar com a API a função searchOrders
   */
  function searchOrders() {
    isLoading.value = true;

    useTimeoutFn(() => {
      orders.value = [
        {
          id: 31,
          name: 'Valdeir Psr',
          numbers: ['89638', '94409', '41347', '95886', '33410'],
          payment_expire_at: new Date('2023-07-13T15:00:00').getTime() - Date.now(),
          status: 'reserved',
        },
        {
          id: 32,
          name: 'Valdeir Psr',
          numbers: ['1381'],
          payment_expire_at: null,
          status: 'paid',
        },
        {
          id: 32,
          name: 'Valdeir Psr',
          numbers: ['1307'],
          payment_expire_at: null,
          status: 'expired',
        },
      ];

      isLoading.value = false;
    }, 1000 * 5);
  }

  function onDialogClosed() {
    orders.value = [];
    opened.value = false;
    telephone.value = '';
  }
</script>

<template>
  <div>
    <PsrButton type="link" @click="opened = true">🔍 Meus Bilhetes</PsrButton>

    <Teleport v-if="opened" to="body">
      <PsrDialog :button-cancel="false" :button-confirm="false" @dismiss="onDialogClosed">
        <template #heading>
          <span v-if="orders.length === 0">Informe o número do seu telefone</span>
          <span v-else>Lista de pedidos</span>
        </template>

        <template #default>
          <Transition name="fade">
            <div v-if="orders.length === 0 && !isLoading">
              <p>Informe o número do celular cadastrado</p>

              <div class="rounded-md shadow-sm mt-4">
                <div class="flex left-0 top-0 bottom-0 items-center pl-2 relative">
                  <IconSvgWhatsApp class="absolute ml-1 text-gray-400" />

                  <input
                    v-model="telephone"
                    v-maska
                    type="tel"
                    class="block rounded-md border py-1 pl-10 text-[#333] shadow ring-inset ring-[#d1d5db] text-sm leading-6 h-12 w-full"
                    placeholder="(71) 9 0000-0000"
                    data-testid="input-telephone"
                    data-maska="(##) #####-####"
                  />
                </div>
              </div>

              <div class="mt-4">
                <PsrButton
                  v-show="isValidTelephone"
                  class="w-full"
                  data-testid="button-search-orders"
                  :disabled="!isValidTelephone"
                  @click="searchOrders"
                >
                  Buscar
                </PsrButton>
              </div>
            </div>

            <PsrLoading v-else-if="orders.length === 0 && isLoading" />

            <div v-else class="mt-4 space-y-4">
              <ListOrders :orders="orders" />
            </div>
          </Transition>
        </template>
      </PsrDialog>
    </Teleport>
  </div>
</template>
